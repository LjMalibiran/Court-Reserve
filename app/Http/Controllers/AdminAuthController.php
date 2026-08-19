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

        $credentials = [
            'name' => $request->login_id, 
            'password' => $request->password,
            'role' => 'admin' // <-- CHANGE: Use the 'role' column instead of 'is_admin'
        ];

        // Try to log the user in
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Success! Send them to the admin dashboard
            return redirect()->intended('/admin/dashboard');
        }

        // 3. Failure! Send them back with an error
        return back()->withErrors([
            'login_id' => 'Access Denied. Invalid admin credentials.',
        ])->onlyInput('login_id');
    }
}