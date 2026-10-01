<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendReservationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:remind';
    protected $description = 'Send email reminders to users with reservations today';

    public function handle()
    {
        try {
            // Find reservations starting in exactly 60 minutes
            $now = \Carbon\Carbon::now();
            $targetStart = $now->copy()->addMinutes(60)->startOfMinute();
            $targetEnd = $now->copy()->addMinutes(60)->endOfMinute();

            $reservations = \App\Models\Reservation::with('user')
                ->whereBetween('start_time', [$targetStart, $targetEnd])
                ->where('status', 'confirmed')
                ->whereNotNull('user_id')
                ->get();

            $count = 0;
            foreach ($reservations as $reservation) {
                $user = $reservation->user;
                if (!$user) continue;

                $date = \Carbon\Carbon::parse($reservation->start_time)->format('F j, Y');
                $start = \Carbon\Carbon::parse($reservation->start_time)->format('g:i A');
                $end = \Carbon\Carbon::parse($reservation->end_time)->format('g:i A');

                $sport = $reservation->court ? $reservation->court->type : 'Badminton';

                $message = "Hello {$user->name},\n\nThis is an automated reminder for your {$sport} reservation at Batangas Badminton Center.\n\nDate: {$date}\nTime: {$start} - {$end}\nCourt: Court {$reservation->court_id}\n\nPlease arrive on time. We look forward to seeing you!";

                // Send SMS via Semaphore
                try {
                    $apiKey = env('SEMAPHORE_API_KEY');
                    $senderName = env('SEMAPHORE_SENDER_NAME', '');

                    $payload = [
                        'apikey' => $apiKey,
                        'number' => $user->contact,
                        'message' => $message,
                    ];

                    if (!empty($senderName)) {
                        $payload['sendername'] = $senderName;
                    }

                    \Illuminate\Support\Facades\Http::post('https://api.semaphore.co/api/v4/messages', $payload);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to auto-send reminder SMS to {$user->contact}: " . $e->getMessage());
                }

                // Send Email Notification
                try {
                    \Illuminate\Support\Facades\Mail::to($user->email)->send(
                        new \App\Mail\ReservationReminderMail(
                            $user->name,
                            $sport,
                            $date,
                            $start . ' - ' . $end,
                            $reservation->court_id
                        )
                    );
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to send reminder Email to {$user->email}: " . $e->getMessage());
                }
                
                $count++;
            }

            $this->info("Successfully sent {$count} reminder messages.");
        } catch (\Exception $e) {
            $this->error("CRITICAL ERROR IN SCHEDULER: " . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
