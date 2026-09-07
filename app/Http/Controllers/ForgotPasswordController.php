<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    // 1. Process the email and send the code
    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();
        
        $newCode = rand(1000, 9999);
        $user->verification_code = $newCode;
        $user->verification_code_expires_at = now()->addMinutes(3);
        $user->save();

        // Send SMS via Semaphore
        try {
            $apiKey = env('SEMAPHORE_API_KEY');
            $senderName = env('SEMAPHORE_SENDER_NAME', '');
            
            $payload = [
                'apikey' => $apiKey,
                'number' => $user->contact,
                'message' => "Your password reset code is: {$newCode}",
            ];
            
            if (!empty($senderName)) {
                $payload['sendername'] = $senderName;
            }
            
            \Illuminate\Support\Facades\Http::post('https://api.semaphore.co/api/v4/messages', $payload);
            Log::info("PASSWORD RESET SMS SENT TO {$user->contact}: {$newCode}");
        } catch (\Exception $e) {
            Log::error("Failed to send SMS to {$user->contact}: " . $e->getMessage());
        }

        // Save email in session so we know who is resetting
        $request->session()->put('reset_email', $user->email);

        return redirect()->route('forgot.verify');
    }

    // 2. Show the verification page
    public function showVerifyReset(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('forgot.password');
        }
        
        $user = User::where('email', $request->session()->get('reset_email'))->first();
        $expiresAt = $user->verification_code_expires_at;
        $secondsLeft = $expiresAt ? \Carbon\Carbon::now()->diffInSeconds($expiresAt, false) : 0;
        if ($secondsLeft < 0) $secondsLeft = 0;

        return view('verify-reset', compact('secondsLeft'));
    }

    // 3. Verify the code
    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'code' => 'required|array|size:4',
            'code.*' => 'required|string|max:1',
        ]);

        $email = $request->session()->get('reset_email');
        if (!$email) return redirect()->route('forgot.password');

        $user = User::where('email', $email)->first();
        $enteredCode = implode('', $request->code);

        if ($enteredCode == $user->verification_code) {
            if (now()->greaterThan($user->verification_code_expires_at)) {
                return back()->withErrors(['code' => 'Code has expired. Please request a new one.']);
            }

            // Success! Allow them to reset
            $request->session()->put('reset_verified', true);
            $user->verification_code = null;
            $user->verification_code_expires_at = null;
            $user->save();

            return redirect()->route('forgot.reset');
        }

        return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
    }

    // 4. Resend code
    public function resendResetCode(Request $request)
    {
        $email = $request->session()->get('reset_email');
        if (!$email) return redirect()->route('forgot.password');

        $user = User::where('email', $email)->first();
        
        $newCode = rand(1000, 9999);
        $user->verification_code = $newCode;
        $user->verification_code_expires_at = now()->addMinutes(3);
        $user->save();

        try {
            $apiKey = env('SEMAPHORE_API_KEY');
            $senderName = env('SEMAPHORE_SENDER_NAME', '');
            
            $payload = [
                'apikey' => $apiKey,
                'number' => $user->contact,
                'message' => "Your new password reset code is: {$newCode}",
            ];
            
            if (!empty($senderName)) {
                $payload['sendername'] = $senderName;
            }
            
            \Illuminate\Support\Facades\Http::post('https://api.semaphore.co/api/v4/messages', $payload);
        } catch (\Exception $e) {}

        return back()->with('success', 'A new code has been sent to your phone number.');
    }

    // 5. Show Reset Password Form
    public function showResetPassword(Request $request)
    {
        if (!$request->session()->has('reset_verified')) {
            return redirect()->route('forgot.password');
        }
        return view('reset-password');
    }

    // 6. Update Password
    public function updatePassword(Request $request)
    {
        if (!$request->session()->has('reset_verified')) {
            return redirect()->route('forgot.password');
        }

        $request->validate([
            'password' => [
                'required',
                'string',
                'min:9',             
                'regex:/[a-zA-Z]/',  
                'regex:/[0-9]/',     
                'regex:/[^a-zA-Z0-9]/', 
                'confirmed' // requires password_confirmation
            ],
        ], [
            'password.regex' => 'The password must contain at least one letter, one number, and one symbol.',
            'password.min' => 'The password must be more than 8 characters.',
        ]);

        $email = $request->session()->get('reset_email');
        $user = User::where('email', $email)->first();

        $user->password = Hash::make($request->password);
        $user->save();

        // Check role for redirection
        $role = $user->role;

        // Clear sessions
        $request->session()->forget(['reset_email', 'reset_verified']);

        if ($role === 'admin') {
            return redirect()->route('admin.login')->with('success', 'Your admin password has been successfully reset. You can now log in.');
        } elseif ($role === 'cashier') {
            return redirect()->route('cashier.login')->with('success', 'Your cashier password has been successfully reset. You can now log in.');
        }

        return redirect()->route('login')->with('success', 'Your password has been successfully reset. You can now log in.');
    }
}
