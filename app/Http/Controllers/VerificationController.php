<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        // 1. Validate that they actually sent an array of 4 items
        $request->validate([
            'code' => 'required|array|size:4',
            'code.*' => 'required|string|max:1',
        ]);

        $user = auth()->user();

        // 2. Smash the array together! 
        // If they type [4, 8, 2, 9], implode turns it into "4829"
        $enteredCode = implode('', $request->code);


        // 3. Check if it matches the database
        if ($enteredCode == $user->verification_code) {
            
            // Check if the code has expired
            if (now()->greaterThan($user->verification_code_expires_at)) {
                return back()->withErrors(['code' => 'Verification code has expired. Please request a new one.']);
            }

            // Success! Update their status and clear the code
            $user->phone_verified_at = now();
            $user->verification_code = null;
            $user->verification_code_expires_at = null;
            $user->save();

            // Success! Route them to the home dashboard
            return redirect()->route('home')->with('success', 'Identity verified successfully!');
        }

        // 4. If it fails, send them back with an error
        return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
    }

    // Resend a new OTP code
    public function resend(Request $request)
    {
        $user = auth()->user();
        
        $newCode = rand(1000, 9999);
        $user->verification_code = $newCode;
        $user->verification_code_expires_at = now()->addMinutes(3);
        $user->save();

        try {
            $apiKey = env('SEMAPHORE_API_KEY');
            $senderName = env('SEMAPHORE_SENDER_NAME', ''); // Keep blank until approved
            
            $payload = [
                'apikey' => $apiKey,
                'number' => $user->contact,
                'message' => "Your new Court Reserve verification code is: {$newCode}",
            ];
            
            if (!empty($senderName)) {
                $payload['sendername'] = $senderName;
            }
            
            \Illuminate\Support\Facades\Http::post('https://api.semaphore.co/api/v4/messages', $payload);
            \Illuminate\Support\Facades\Log::info("SMS SENT TO {$user->contact}: {$newCode}");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send SMS to {$user->contact}: " . $e->getMessage());
        }

        return back()->with('success', 'A new code has been sent to your phone number.');
    }

    // Show the verification form
    public function show()
    {
        return view('verify'); // This will load resources/views/verify.blade.php
    }
}