<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class CashierController extends Controller
{
    // ==========================================
    // RESERVATION LOGIC
    // ==========================================
    public function reservationsIndex()
    {
        // Fetch all reservations exactly like the Admin does
        $reservations = Reservation::with('user')->orderBy('created_at', 'desc')->get();
        
        return view('cashier.reservations', compact('reservations'));
    }

    public function confirmReservation($id)
    {
        $reservation = Reservation::find($id);
        
        if($reservation) {
            $reservation->status = 'confirmed';
            $reservation->save();
            return back()->with('success', 'Reservation confirmed successfully!');
        }
        
        return back()->with('error', 'Reservation not found.');
    }

    public function cancelReservation($id)
    {
        $reservation = Reservation::find($id);
        
        if($reservation) {
            $reservation->status = 'cancelled';
            $reservation->save();
            return back()->with('success', 'Reservation cancelled.');
        }

        return back()->with('error', 'Reservation not found.');
    }

    // ==========================================
    // QR VERIFICATION LOGIC
    // ==========================================
    
    // 1. Show the QR Scanner Page
    public function qrIndex()
    {
        return view('cashier.qr-verification');
    }

    // 2. Search for the scanned QR Code
    public function qrSearch(Request $request)
    {
        $request->validate([
            'qr_code' => 'required'
        ]);

        // Search for a matching reservation ID in the database
        $reservation = Reservation::where('reservation_id', $request->qr_code)->first();

        if ($reservation) {
            // Found it! Send the reservation data to the screen
            return back()->with('reservation', $reservation);
        }

        // Not found. Send an error message.
        return back()->with('error', 'Invalid QR Code or Reservation not found.');
    }

    // 3. Mark the reservation as Verified/Checked-In
    public function qrVerify($id)
    {
        $reservation = Reservation::find($id);

        if ($reservation) {
            // Change the status to indicate they have arrived
            $reservation->status = 'completed'; // Or 'verified' depending on your database setup
            $reservation->save();
            
            return redirect('/cashier/qr-verification')->with('success', 'Reservation successfully verified!');
        }

        return back()->with('error', 'Reservation not found.');
    }
}   