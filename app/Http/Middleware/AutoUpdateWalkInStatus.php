<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Reservation;

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

        return $next($request);
    }
}

