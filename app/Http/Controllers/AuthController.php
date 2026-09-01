<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http; // <-- Added to send HTTP requests to Semaphore
use Illuminate\Support\Facades\Mail;

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
            'email' => 'required|email|unique:users,email|ends_with:@gmail.com',
            'contact' => 'required|digits:11',
            'password' => [
                'required',
                'string',
                'min:9',             // more than 8 characters
                'regex:/[a-zA-Z]/',  // at least one letter
                'regex:/[0-9]/',     // at least one number
                'regex:/[^a-zA-Z0-9]/', // at least one symbol
            ],
        ], [
            'password.regex' => 'The password must contain at least one letter, one number, and one symbol.',
            'password.min' => 'The password must be more than 8 characters.',
        ]);

        // 1. Generate a random 4-digit code
        $verificationCode = rand(1000, 9999);

        // 2. Create the user and save the code
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'password' => Hash::make($request->password),
            'verification_code' => $verificationCode,
        ]);

        // 3. SEND VERIFICATION EMAIL TO GMAIL
        try {
            Mail::raw("Your Court Reserve verification code is: {$verificationCode}", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Court Reserve - Verification Code');
            });
            Log::info("EMAIL SENT TO {$user->email}: Your Court Reserve verification code is: {$verificationCode}");
        } catch (\Exception $e) {
            Log::error("Failed to send Email to {$user->email}: " . $e->getMessage());
        }

        Auth::login($user);
        
        // 4. Send them to the verification screen instead of /home
        return redirect()->route('verify.index'); 
    }

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

            // 4. REGULAR USER CHECK: Are they verified yet or is 2FA enabled?
            if (is_null($user->phone_verified_at) || $user->two_factor_enabled) {
                // Generate a fresh code because they need to verify!
                $newCode = rand(1000, 9999);
                $user->verification_code = $newCode;
                // Unverify them so middleware catches them
                $user->phone_verified_at = null;
                $user->save();

                // Send fresh Email
                try {
                    Mail::raw("Your fresh Court Reserve verification code is: {$newCode}", function ($message) use ($user) {
                        $message->to($user->email)
                                ->subject('Court Reserve - New Verification Code');
                    });
                    Log::info("NEW EMAIL SENT TO {$user->email}: Your fresh verification code is: {$newCode}");
                } catch (\Exception $e) {
                    Log::error("Failed to send Email to {$user->email}: " . $e->getMessage());
                }

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
        
        // 4. Send them back to the main login screen!
        return redirect('/login'); // <-- Change this to '/' if your login is on the homepage
    }
}