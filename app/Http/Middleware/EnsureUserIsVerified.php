<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Check if the user is logged in
        if (Auth::check()) {
            
            // 2. Check if their 'phone_verified_at' column is still NULL
            if (is_null(Auth::user()->phone_verified_at)) {
                
                // 3. Block them and redirect to the Verification UI we built
                return redirect()->route('verify.index')
                    ->with('warning', 'Please verify your account to continue.');
            }
        }

        // If they are verified, let them pass!
        return $next($request);
    }
}