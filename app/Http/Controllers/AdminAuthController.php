<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminAuthController extends Controller
{
    // Process the Admin Login
    public function login(Request $request)
    {
        // 1. Validate the form inputs
        $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');
        $loginId = $request->login_id;

        // The Triple-Login Trick for Admins (Checks Email OR Name)
        if (
            Auth::attempt(['email' => $loginId, 'password' => $request->password, 'role' => 'admin'], $remember) ||
            Auth::attempt(['name' => $loginId, 'password' => $request->password, 'role' => 'admin'], $remember)
        ) {
            $request->session()->regenerate();
            
            // Success! Send them to the admin dashboard unconditionally
            return redirect('/admin/dashboard');
        }

        // 3. Failure! Send them back with an error
        return back()->withErrors([
            'login_id' => 'Access Denied. Invalid admin credentials.',
        ])->onlyInput('login_id');
    }
}