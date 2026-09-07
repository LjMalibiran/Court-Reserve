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
                \Illuminate\Support\Facades\Mail::raw(
                    "Hello {$user->name},\n\nThis is an automated reminder for your {$reservation->sport} reservation TODAY at Batangas Badminton Center.\n\nDate: {$date}\nTime: {$start} - {$end}\nCourt: Court {$reservation->court_id}\n\nPlease arrive on time. We look forward to seeing you!", 
                    function ($message) use ($user) {
                        $message->to($user->email)->subject('Reminder: You have a reservation TODAY!');
                    }
                );
                $count++;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to auto-send reminder to {$user->email}: " . $e->getMessage());
            }
        }

        $this->info("Successfully sent {$count} reminder emails for today's reservations.");
    }
}
