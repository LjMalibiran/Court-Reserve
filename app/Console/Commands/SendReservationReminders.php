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

            error_log("[Reminder] Now: {$now->toDateTimeString()} | Window: {$targetStart->toDateTimeString()} - {$targetEnd->toDateTimeString()}");

            $reservations = Reservation::with(['user', 'court'])
                ->whereBetween('start_time', [$targetStart, $targetEnd])
                ->where('status', 'confirmed')
                ->whereNotNull('user_id')
                ->get();

            error_log("[Reminder] Found {$reservations->count()} confirmed reservation(s) in window.");

            $count = 0;
            foreach ($reservations as $reservation) {
                if (Cache::has('reminder_sent_' . $reservation->id)) {
                    error_log("[Reminder] Skipping reservation #{$reservation->id} - already sent.");
                    continue;
                }

                $user = $reservation->user;
                if (!$user) continue;

                $date = Carbon::parse($reservation->start_time)->format('F j, Y');
                $start = Carbon::parse($reservation->start_time)->format('g:i A');
                $end = Carbon::parse($reservation->end_time)->format('g:i A');
                $sport = $reservation->sport ?? 'Badminton';

                error_log("[Reminder] Sending to {$user->name} ({$user->email}) for {$sport} at {$start}...");

                $message = "?? Court Reserve ??\n\nHello {$user->name}, this is a reminder that your {$sport} reservation is starting soon! ?\n\n?? Court {$reservation->court_id}\n?? {$date}\n? {$start} - {$end}\n\nPlease arrive on time. See you!";

                // Send SMS via iProgSMS
                $phone = $user->contact ?? $user->phone_number;
                if (!empty($phone)) {
                    $success = \App\Services\SmsService::send($phone, $message);
                    if ($success) {
                        error_log("[Reminder] SMS sent to {$phone}");
                    } else {
                        error_log("[Reminder] SMS failed for {$phone}");
                    }
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
                    error_log("[Reminder] Email sent to {$user->email}");
                } catch (\Exception $e) {
                    error_log("[Reminder] Email failed: " . $e->getMessage());
                }

                Cache::put('reminder_sent_' . $reservation->id, true, 7200);
                $count++;
            }

            error_log("[Reminder] Done. Sent {$count} reminder(s).");
        } catch (\Exception $e) {
            error_log("CRITICAL REMINDER ERROR: " . $e->getMessage());
            error_log($e->getTraceAsString());
        }
    }
}
