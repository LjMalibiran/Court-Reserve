<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            return redirect('login');
        }

        // Check if the authenticated user's role matches the required role
        if (Auth::user()->role !== $role) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}