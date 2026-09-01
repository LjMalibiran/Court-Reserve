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
            
            // Success! Update their status and clear the code
            $user->phone_verified_at = now();
            $user->verification_code = null;
            $user->save();

            // Success! Route them to the home dashboard
            return redirect()->route('home')->with('success', 'Identity verified successfully!');
        }

        // 4. If it fails, send them back with an error
        return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
    }

    // Show the verification form
    public function show()
    {
        return view('verify'); // This will load resources/views/verify.blade.php
    }
}