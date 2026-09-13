<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\User;

class CashierController extends Controller
{
    // ==========================================
    // DASHBOARD METRICS
    // ==========================================
    public function dashboard()
    {
        // 1. Exact same calculation as Admin
        $totalReserved = Reservation::where('status', '!=', 'cancelled')->count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        // This counts everyone EXCEPT the admin, cashier, and walk-in users
        $registeredUsers = User::whereNotIn('role', ['admin', 'cashier'])
            ->where('email', 'NOT LIKE', 'walkin_%')
            ->get();
            
        $totalUsers = $registeredUsers->count();

        return view('cashier.dashboard', compact('totalReserved', 'pendingReservations', 'totalUsers', 'registeredUsers'));


    public function filterSales(Request $request)
    {
        $query = \App\Models\Reservation::whereNotIn('status', ['pending', 'cancelled']);

        $startDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : now()->subDays(6)->startOfDay();
        $endDate = $request->filled('end_date') ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

        $query->whereBetween('created_at', [$startDate, $endDate]);

        $reservations = $query->get();
        
        $chartData = [];
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        
        if ($period->count() > 31) {
            $period = \Carbon\CarbonPeriod::create($endDate->copy()->subDays(30), $endDate);
        }

        foreach ($period as $date) {
            $chartData[$date->format('M d')] = 0;
        }

        $total = 0;
        foreach ($reservations as $res) {
            $date = $res->created_at->format('M d');
            if (isset($chartData[$date])) {
                $chartData[$date] += (float)$res->total_price;
            }
            $total += (float)$res->total_price;
        }

        return response()->json([
            'total' => '₱' . number_format($total, 2),
            'labels' => array_keys($chartData),
            'data' => array_values($chartData)
        ]);
    }
    public function reservationsIndex()
    {
        // Fetch all reservations exactly like the Admin does
        $reservations = Reservation::with('user')->orderBy('created_at', 'desc')->get();
        
        return view('cashier.reservations', compact('reservations'));
    }

    public function confirmReservation(\Illuminate\Http\Request $request, $id)
    {
        $reservation = Reservation::find($id);
        
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

            return back()->with('success', 'Reservation confirmed successfully!')->with('active_tab', $request->tab ?? 'pending');
        }
        
        return back()->with('error', 'Reservation not found.')->with('active_tab', $request->tab ?? 'pending');
    }

    public function cancelReservation(\Illuminate\Http\Request $request, $id)
    {
        $reservation = Reservation::find($id);
        
        if($reservation) {
            $reservation->status = 'cancelled';
            $reservation->save();

            if ($reservation->user_id) {
                \App\Models\Notification::create([
                    'user_id' => $reservation->user_id,
                    'reservation_id' => $reservation->id,
                    'title' => 'Reservation Cancelled',
                    'message' => 'Your reservation for Court ' . $reservation->court_id . ' has been cancelled by the cashier.'
                ]);
            }

            return back()->with('success', 'Reservation cancelled successfully.')->with('active_tab', $request->tab ?? 'pending');
        }
        
        return back()->with('error', 'Reservation not found.')->with('active_tab', $request->tab ?? 'pending');
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

        // Search for a matching reservation ID in the database, safely trimmed
        $code = trim($request->qr_code);
        $reservation = \App\Models\Reservation::where('reservation_code', $code)->first();

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
        $reservation = \App\Models\Reservation::find($id);

        if ($reservation) {
            $reservation->status = 'in-play';
            $reservation->save();

            return redirect('/cashier/dashboard')->with('success', 'Reservation Verified! Court ' . $reservation->court_id . ' is now In Play.');
        }

        return back()->with('error', 'Could not verify reservation.');
    }

    public function salesReportIndex()
    {
        $reservations = \App\Models\Reservation::with(['user', 'court'])->orderBy('created_at', 'desc')->get();
        
        $activeRes = $reservations->where('status', '!=', 'cancelled');

        $totalRevenue = $activeRes->sum('amount_paid');
        
        // Treat 'full' and 'half' (online) as GCash.
        $gcashPayments = $activeRes->whereIn('payment_type', ['GCash', 'full', 'half'])->sum('amount_paid');
        $cashPayments = $activeRes->where('payment_type', 'Cash')->sum('amount_paid');
        
        $pendingAmount = 0;
        foreach($activeRes as $r) {
            $diff = $r->total_price - $r->amount_paid;
            if($diff > 0) {
                $pendingAmount += $diff;
            }
        }

        return view('cashier.sales-report', compact('reservations', 'totalRevenue', 'gcashPayments', 'cashPayments', 'pendingAmount'));
    }
}
