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
        $today = \Carbon\Carbon::today();
        $reservations = \App\Models\Reservation::with('user')
            ->whereDate('start_time', $today)
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

            try {
                $apiKey = env('SEMAPHORE_API_KEY');
                $senderName = env('SEMAPHORE_SENDER_NAME', '');

                $payload = [
                    'apikey' => $apiKey,
                    'number' => $user->contact,
                    'message' => "Hello {$user->name},\n\nThis is an automated reminder for your {$reservation->sport} reservation TODAY at Batangas Badminton Center.\n\nDate: {$date}\nTime: {$start} - {$end}\nCourt: Court {$reservation->court_id}\n\nPlease arrive on time. We look forward to seeing you!",
                ];

                if (!empty($senderName)) {
                    $payload['sendername'] = $senderName;
                }

                \Illuminate\Support\Facades\Http::post('https://api.semaphore.co/api/v4/messages', $payload);
                $count++;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to auto-send reminder SMS to {$user->contact}: " . $e->getMessage());
            }
        }

        $this->info("Successfully sent {$count} reminder SMS messages for today's reservations.");
    }
}
