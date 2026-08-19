<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if they are logged in AND if their role is exactly 'cashier'
        // (Change 'role' if your database uses a different column name like 'usertype')
        if (Auth::check() && Auth::user()->role === 'cashier') {
            return $next($request); // Let them in!
        }

        // If they are a normal user (like ven matira), kick them back to home
        return redirect('/home')->with('error', 'Unauthorized access. Cashier strictly only.');
    }
}