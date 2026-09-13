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
        // This counts everyone EXCEPT the admin, cashier, and walk-in users
        $registeredUsers = User::whereNotIn('role', ['admin', 'cashier'])
            ->where('email', 'NOT LIKE', 'walkin_%')
            ->get();
            
        $totalUsers = $registeredUsers->count();

        return view('admin.dashboard', compact('totalReserved', 'pendingReservations', 'totalUsers', 'registeredUsers'));
    }

    public function filterSales(Request $request)
    {
        $query = \App\Models\Reservation::whereNotIn('status', ['pending', 'cancelled']);

        $startDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : now()->subDays(6)->startOfDay();
        $endDate = $request->filled('end_date') ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : now()->endOfDay();

        $query->whereBetween('created_at', [$startDate, $endDate]);

        $reservations = $query->get();
        
        $chartData = [];
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);
        
        // Ensure max 30 days are rendered on chart to avoid massive datasets if range is huge
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

        // Search the database for the exact QR code string, safely trimmed
        $code = trim($request->qr_code);
        $reservation = \App\Models\Reservation::where('reservation_code', $code)->first();

        if (!$reservation) {
            return back()->with('error', 'Invalid Code: No reservation found.');
        }

        return back()->with('reservation', $reservation);
    }

    public function qrVerify($id)
    {
        $reservation = \App\Models\Reservation::findOrFail($id);
        
        // Update the reservation status to activate the court
        $reservation->status = 'in-play';
        $reservation->save();

        return redirect('/admin/dashboard')->with('success', 'Verified! Court ' . $reservation->court_id . ' is now In Play.');
    }

    public function reservationsIndex()
    {
        // Fetch all reservations, including the associated user data, ordered by newest first
        $reservations = \App\Models\Reservation::with('user')->orderBy('created_at', 'desc')->get();
        
        return view('admin.reservations', compact('reservations'));
    }

    public function confirmReservation(\Illuminate\Http\Request $request, $id)
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

            return back()->with('success', 'Reservation confirmed successfully! The user will now see this on their dashboard.')->with('active_tab', $request->tab ?? 'pending');
        }
        
        return back()->with('error', 'Reservation not found.')->with('active_tab', $request->tab ?? 'pending');
    }

    public function cancelReservation(\Illuminate\Http\Request $request, $id)
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

            return back()->with('success', 'Reservation cancelled successfully.')->with('active_tab', $request->tab ?? 'pending');
        }

        return back()->with('error', 'Reservation not found.')->with('active_tab', $request->tab ?? 'pending');
    }

    public function sendReminder($id)
    {
        $reservation = \App\Models\Reservation::find($id);
        
        if ($reservation && $reservation->user_id) {
            $user = $reservation->user;
            
            // Format time for email
            $date = \Carbon\Carbon::parse($reservation->start_time)->format('F j, Y');
            $start = \Carbon\Carbon::parse($reservation->start_time)->format('g:i A');
            $end = \Carbon\Carbon::parse($reservation->end_time)->format('g:i A');
            
            // Send SMS via Semaphore
            try {
                $apiKey = env('SEMAPHORE_API_KEY');
                $senderName = env('SEMAPHORE_SENDER_NAME', '');
                
                $payload = [
                    'apikey' => $apiKey,
                    'number' => $user->contact,
                    'message' => "Hello {$user->name},\n\nThis is a friendly reminder for your upcoming {$reservation->sport} reservation at Batangas Badminton Center.\n\nDate: {$date}\nTime: {$start} - {$end}\nCourt: Court {$reservation->court_id}\n\nWe look forward to seeing you!",
                ];
                
                if (!empty($senderName)) {
                    $payload['sendername'] = $senderName;
                }
                
                \Illuminate\Support\Facades\Http::post('https://api.semaphore.co/api/v4/messages', $payload);
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send reminder SMS to {$user->contact}: " . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Failed to send SMS. Check logs.']);
            }
        }
        
        return response()->json(['success' => false, 'message' => 'User not found or is a walk-in.']);
    }

    public function walkInIndex()
    {
        return view('admin.walk-in');
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

        return view('admin.sales-report', compact('reservations', 'totalRevenue', 'gcashPayments', 'cashPayments', 'pendingAmount'));
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