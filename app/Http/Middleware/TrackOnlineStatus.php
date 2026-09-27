<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackOnlineStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            // To prevent massive database writes on every single page load, we only update if last_seen_at is more than 1 minute ago
            if (!$user->last_seen_at || \Carbon\Carbon::parse($user->last_seen_at)->diffInMinutes(now()) >= 1) {
                // Using DB directly to prevent firing model events unnecessarily
                \Illuminate\Support\Facades\DB::table('users')
                    ->where('id', $user->id)
                    ->update(['last_seen_at' => now()]);
            }
        }

        return $next($request);
    }
}
