<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Notification;

class AutoUpdateWalkInStatus
{
    public function handle(Request $request, Closure $next)
    {
        $now = now();
        
        // Walk-ins confirmed -> in-play
        Reservation::where('reservation_code', 'LIKE', 'W-%')
            ->where('status', 'confirmed')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->update(['status' => 'in-play']);

        // Walk-ins in-play or confirmed -> completed
        Reservation::where('reservation_code', 'LIKE', 'W-%')
            ->whereIn('status', ['confirmed', 'in-play'])
            ->where('end_time', '<=', $now)
            ->update(['status' => 'completed']);

        // Pending reservations past their start time -> move to refund queue as overdue
        $overduePending = Reservation::where('status', 'pending')
            ->where('start_time', '<=', $now)
            ->get();

        foreach ($overduePending as $res) {
            $paid = (float) $res->amount_paid;
            if ($paid <= 0) {
                $paid = ($res->payment_type === 'half')
                    ? ((float) $res->total_price / 2)
                    : (float) $res->total_price;
            }

            $res->status = 'cancelled';
            $res->previous_status = 'overdue';
            $res->refund_status = 'pending';
            $res->refund_amount = $paid;
            $res->cancelled_at = $now;
            $res->cancellation_reason = 'Overdue - Failed to confirm before start time';
            $res->save();

            if ($res->user_id) {
                Notification::create([
                    'user_id' => $res->user_id,
                    'reservation_id' => $res->id,
                    'title' => 'Reservation Overdue - Refund Queued',
                    'message' => "Your pending reservation ({$res->reservation_code}) was not confirmed before its start time and has been moved to refunds.",
                ]);
            }
        }

        return $next($request);
    }
}

