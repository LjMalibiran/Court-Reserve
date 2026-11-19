<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Notification;
use App\Services\SmsService;

class AutoUpdateWalkInStatus
{
    public function handle(Request $request, Closure $next)
    {
        // Immediately kick out any logged-in user who has been banned
        if (Auth::check() && !Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->with('error', 'Your account has been banned by the administrator.');
        }

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
        $overduePending = Reservation::with('user')
            ->where('status', 'pending')
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

                $phone = $res->user->contact ?? $res->user->phone_number ?? null;
                if (!empty($phone)) {
                    $date = \Carbon\Carbon::parse($res->start_time)->format('F j, Y');
                    $start = \Carbon\Carbon::parse($res->start_time)->format('g:i A');
                    $smsMsg = "Notice: Your pending reservation ({$res->reservation_code}) for Court {$res->court_id} on {$date} at {$start} failed to be approved before its scheduled time and has been moved for refund. Please book another reservation.";
                    SmsService::send($phone, $smsMsg);
                }
            }
        }

        return $next($request);
    }
}

