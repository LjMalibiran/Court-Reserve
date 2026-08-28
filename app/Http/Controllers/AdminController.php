<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation; 
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ==========================================
    // DASHBOARD METRICS
    // ==========================================
    public function dashboard()
    {
        // 1. Exact same calculation as Cashier
        $totalReserved = Reservation::where('status', '!=', 'cancelled')->count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        // This counts everyone EXCEPT the admin (1) and cashier (2)
        $totalUsers = User::whereNotIn('role', [1, 2, 'admin', 'cashier'])->count();

        return view('admin.dashboard', compact('totalReserved', 'pendingReservations', 'totalUsers'));
    }

    // ==========================================
    // EXISTING LOGIC
    // ==========================================
    public function qrIndex()
    {
        return view('admin.qr-verification', [
            'reservation' => session('reservation'),
            'error' => session('error')
        ]);
    }

    public function qrSearch(Request $request)
    {
        $request->validate(['qr_code' => 'required']);

        // Search the database for the exact QR code string
        $reservation = Reservation::where('qr_code', $request->qr_code)->first();

        if (!$reservation) {
            return back()->with('error', 'Invalid Code: No reservation found.');
        }

        return back()->with('reservation', $reservation);
    }

    public function qrVerify($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Update the reservation status to activate the court
        $reservation->status = 'In Play';
        $reservation->save();

        return redirect('/admin/dashboard')->with('success', 'Verified! Court ' . $reservation->court_number . ' is now In Play.');
    }

    public function reservationsIndex()
    {
        // Fetch all reservations, including the associated user data, ordered by newest first
        $reservations = \App\Models\Reservation::with('user')->orderBy('created_at', 'desc')->get();
        
        return view('admin.reservations', compact('reservations'));
    }

    public function confirmReservation($id)
    {
        $reservation = \App\Models\Reservation::find($id);
        
        if($reservation) {
            $reservation->status = 'confirmed'; 
            $reservation->save();

            if ($reservation->user_id) {
                \App\Models\Notification::create([
                    'user_id' => $reservation->user_id,
                    'reservation_id' => $reservation->id,
                    'title' => 'Reservation Confirmed',
                    'message' => 'Your reservation for Court ' . $reservation->court_id . ' has been confirmed.'
                ]);
            }

            return back()->with('success', 'Reservation confirmed successfully! The user will now see this on their dashboard.');
        }
        
        return back()->with('error', 'Reservation not found.');
    }

    public function cancelReservation($id)
    {
        $reservation = \App\Models\Reservation::find($id);
        
        if($reservation) {
            $reservation->status = 'cancelled';
            $reservation->save();

            if ($reservation->user_id) {
                \App\Models\Notification::create([
                    'user_id' => $reservation->user_id,
                    'reservation_id' => $reservation->id,
                    'title' => 'Reservation Cancelled',
                    'message' => 'Your reservation for Court ' . $reservation->court_id . ' has been cancelled by the admin.'
                ]);
            }

            return back()->with('success', 'Reservation cancelled.');
        }

        return back()->with('error', 'Reservation not found.');
    }

    public function walkInIndex()
    {
        return view('admin.walk-in');
    }

    public function salesReportIndex()
    {
        return view('admin.sales-report');
    }

    public function salesTransactionsIndex()
    {
        return view('admin.sales-transactions');
    }

    public function salesRefundsIndex()
    {
        return view('admin.sales-refunds');
    }

    public function settingsIndex()
    {
        return view('admin.settings');
    }

    public function profileIndex()
    {
        return view('admin.profile');
    }

    public function createStaff()
    {
        return view('admin.create-staff');
    }

    public function storeStaff(Request $request)
    {
        // 1. Validate the form inputs
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:15',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,cashier',
        ]);

        // 2. Create the user WITHOUT logging them in
        User::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'verification_code' => 0000, // Dummy code
            'phone_verified_at' => now(), // Auto-verify the account!
        ]);

        // 3. Send the Admin back to the form with a success message
        return back()->with('success', ucfirst($request->role) . ' account created successfully! They can now log in.');
    }
}