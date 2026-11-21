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

        // Pending reservations past their start time -> mark as overdue on user dashboard and notify user to cancel for refund
        $overduePending = Reservation::with('user')
            ->where('status', 'pending')
            ->where('start_time', '<=', $now)
            ->get();

        foreach ($overduePending as $res) {
            $res->status = 'overdue';
            $res->save();

            if ($res->user_id) {
                Notification::create([
                    'user_id' => $res->user_id,
                    'reservation_id' => $res->id,
                    'title' => 'Reservation Overdue - Action Required',
                    'message' => "Your pending reservation ({$res->reservation_code}) was not confirmed before its scheduled time. Please cancel your overdue reservation on your dashboard to refund your payment.",
                ]);

                $phone = $res->user->contact ?? $res->user->phone_number ?? null;
                if (!empty($phone)) {
                    $date = \Carbon\Carbon::parse($res->start_time)->format('F j, Y');
                    $start = \Carbon\Carbon::parse($res->start_time)->format('g:i A');
                    $smsMsg = "Notice: Your pending reservation ({$res->reservation_code}) for Court {$res->court_id} on {$date} at {$start} was not approved before its scheduled time and is now OVERDUE. Please cancel your overdue reservation on the website to refund your payment.";
                    SmsService::send($phone, $smsMsg);
                }
            }
        }

        return $next($request);
    }
}

