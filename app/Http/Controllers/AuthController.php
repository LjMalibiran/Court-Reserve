<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // <-- ADDED: Needed to log the fake SMS

class AuthController extends Controller
{
    // Show the register form
    public function showRegister() {
        return view('register');
    }

    // Handle the registration logic
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|unique:users,name',
            'contact' => 'required',
            'password' => 'required|min:6',
        ]);

        // 1. Generate a random 4-digit code
        $verificationCode = rand(1000, 9999);

        // 2. Create the user and save the code
        $user = User::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'password' => Hash::make($request->password),
            'verification_code' => $verificationCode, // <-- ADDED: Save code to database
        ]);

        // 3. "SEND" THE SMS (Log it to a file so we can see it locally)
        Log::info("SMS SENT TO {$user->contact}: Your Court Reserve verification code is: {$verificationCode}");

        Auth::login($user);
        
        // 4. Send them to the verification screen instead of /home
        return redirect()->route('verify.index'); 
    }

    // Handle the login logic
    // Handle the login logic
    public function login(Request $request) {
        // 1. Validate the incoming data 
        $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string'
        ]);

        $remember = $request->has('remember');
        $loginId = $request->login_id;

        // 2. The Dual-Login Trick (Checks Contact OR Name)
        if (
            Auth::attempt(['contact' => $loginId, 'password' => $request->password], $remember) ||
            Auth::attempt(['name' => $loginId, 'password' => $request->password], $remember)
        ) {
            
            $user = Auth::user();
            $request->session()->regenerate();

            // 3. STAFF CHECK: Are they an Admin or Cashier?
            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'cashier') {
                return redirect()->intended('/cashier/dashboard');
            }

            // 4. REGULAR USER CHECK: Are they verified yet?
            if (is_null($user->phone_verified_at)) {
                // Generate a fresh code because they need to verify!
                $newCode = rand(1000, 9999);
                $user->verification_code = $newCode;
                $user->save();

                // Log the new code
                \Illuminate\Support\Facades\Log::info("NEW SMS SENT TO {$user->contact}: Your fresh verification code is: {$newCode}");

                return redirect()->route('verify.index');
            }

            // Success and Verified! Send regular user to the main app dashboard
            return redirect()->intended('/home');
        }

        // 5. Failure! Send them back with an error attached to the 'login_id' input
        return back()->withErrors([
            'login_id' => 'The provided credentials do not match our records.',
        ])->onlyInput('login_id');
    }
    public function logout(Request $request)
    {
        // 1. Log the user out of the system
        Auth::logout();
        
        // 2. Invalidate their active session to keep it secure
        $request->session()->invalidate();
        
        // 3. Regenerate the CSRF token to prevent hijacking
        $request->session()->regenerateToken();
        
        // 4. Send them back to the login screen!
        return redirect('admin/login'); 
    }
}