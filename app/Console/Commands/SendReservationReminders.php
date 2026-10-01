<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Reservation;
use App\Mail\ReservationReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SendReservationReminders extends Command
{
    protected $signature = 'reservations:remind';
    protected $description = 'Send email reminders to users with reservations starting in ~1 hour';

    public function handle()
    {
        try {
            $now = Carbon::now();
            $targetStart = $now->copy()->addMinutes(55)->startOfMinute();
            $targetEnd = $now->copy()->addMinutes(65)->endOfMinute();

            // Always log what the scheduler sees so we can debug via Railway deploy logs
            $this->info("[Reminder] Now: {$now->toDateTimeString()} | Window: {$targetStart->toDateTimeString()} - {$targetEnd->toDateTimeString()}");

            $reservations = Reservation::with(['user', 'court'])
                ->whereBetween('start_time', [$targetStart, $targetEnd])
                ->where('status', 'confirmed')
                ->whereNotNull('user_id')
                ->get();

            $this->info("[Reminder] Found {$reservations->count()} confirmed reservation(s) in window.");

            $count = 0;
            foreach ($reservations as $reservation) {
                // Skip if we already sent a reminder (cache prevents duplicates)
                if (Cache::has('reminder_sent_' . $reservation->id)) {
                    $this->info("[Reminder] Skipping reservation #{$reservation->id} - already sent.");
                    continue;
                }

                $user = $reservation->user;
                if (!$user) {
                    $this->info("[Reminder] Skipping reservation #{$reservation->id} - no user found.");
                    continue;
                }

                $date = Carbon::parse($reservation->start_time)->format('F j, Y');
                $start = Carbon::parse($reservation->start_time)->format('g:i A');
                $end = Carbon::parse($reservation->end_time)->format('g:i A');
                $sport = $reservation->sport ?? 'Badminton';

                $this->info("[Reminder] Sending reminder to {$user->name} ({$user->email}) for {$sport} at {$start}...");

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

                    Http::post('https://api.semaphore.co/api/v4/messages', $payload);
                    $this->info("[Reminder] SMS sent to {$user->contact}");
                } catch (\Exception $e) {
                    $this->error("[Reminder] SMS failed for {$user->contact}: " . $e->getMessage());
                    Log::error("Failed to auto-send reminder SMS to {$user->contact}: " . $e->getMessage());
                }

                // Send Email Notification
                try {
                    Mail::to($user->email)->send(
                        new ReservationReminderMail(
                            $user->name,
                            $sport,
                            $date,
                            $start . ' - ' . $end,
                            $reservation->court_id
                        )
                    );
                    $this->info("[Reminder] Email sent to {$user->email}");
                } catch (\Exception $e) {
                    $this->error("[Reminder] Email failed for {$user->email}: " . $e->getMessage());
                    Log::error("Failed to send reminder Email to {$user->email}: " . $e->getMessage());
                }

                // Mark as sent for 2 hours to prevent duplicate emails
                Cache::put('reminder_sent_' . $reservation->id, true, 7200);
                $count++;
            }

            $this->info("[Reminder] Done. Sent {$count} reminder(s).");
        } catch (\Exception $e) {
            $this->error("CRITICAL ERROR: " . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
