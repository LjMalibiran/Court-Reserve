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
        $totalReserved = Reservation::where('status', '!=', 'cancelled')->count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $registeredUsers = User::whereNotIn('role', ['admin', 'cashier'])
            ->where('email', 'NOT LIKE', 'walkin_%')
            ->whereNotNull('phone_verified_at')
            ->get();
            
        $totalUsers = $registeredUsers->count();

        $todayReservations = Reservation::whereDate('start_time', \Carbon\Carbon::today())
            ->whereIn('status', ['confirmed', 'in-play'])
            ->orderBy('start_time', 'asc')
            ->get();

        return view('cashier.dashboard', compact('totalReserved', 'pendingReservations', 'totalUsers', 'registeredUsers', 'todayReservations'));
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





    public function transactionsIndex(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Reservation::with(['user', 'court'])->orderBy('created_at', 'desc');
        
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('user', function($q2) use ($searchTerm) {
                    $q2->where('name', 'like', "%{$searchTerm}%");
                })->orWhere('walk_in_name', 'like', "%{$searchTerm}%")
                  ->orWhere('reservation_code', 'like', "%{$searchTerm}%");
            });
        }
        
        if ($request->filled('court') && $request->court != 'all') {
            $query->where('court_id', $request->court);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $reservations = $query->get();
        
        $activeRes = $reservations->where('status', '!=', 'cancelled');

        // Export logic
        if ($request->has('export') && $request->export == 1) {
            $totalRevenue = 0;
            $cashPayments = 0;
            $pendingAmount = 0;
            
            foreach($activeRes as $r) {
                $paid = (float)$r->amount_paid;
                if ($paid == 0 && in_array($r->payment_type, ['full', 'half'])) {
                    $paid = ($r->payment_type == 'half') ? ($r->total_price / 2) : $r->total_price;
                }
                $effectivePaid = min($paid, $r->total_price);
                $unpaid = $r->total_price - $effectivePaid;
                
                if ($r->status === 'pending') {
                    $pendingAmount += $r->total_price;
                    continue;
                }
                $totalRevenue += $effectivePaid;
                $pendingAmount += $unpaid;
                
                if (in_array($r->payment_type, ['GCash', 'full', 'half'])) {
                    if ($r->payment_type === 'half') {
                        $onlineHalf = $r->total_price / 2;
                        if ($effectivePaid > $onlineHalf) {
                            $cashPayments += ($effectivePaid - $onlineHalf);
                        }
                    }
                } else {
                    $cashPayments += $effectivePaid;
                }
            }
            return view('cashier.transactions_pdf', compact('reservations', 'totalRevenue', 'cashPayments', 'pendingAmount'));
        }

        $totalRevenue = 0;
        $gcashPayments = 0;
        $cashPayments = 0;
        $pendingAmount = 0;
        
        foreach($activeRes as $r) {
            $paid = (float)$r->amount_paid;
            if ($paid == 0 && in_array($r->payment_type, ['full', 'half'])) {
                $paid = ($r->payment_type == 'half') ? ($r->total_price / 2) : $r->total_price;
            }
            $effectivePaid = min($paid, $r->total_price);
            $unpaid = $r->total_price - $effectivePaid;
            
            if ($r->status === 'pending') {
                $pendingAmount += $r->total_price;
                continue;
            }
            $totalRevenue += $effectivePaid;
            $pendingAmount += $unpaid;
            
            if (in_array($r->payment_type, ['GCash', 'full', 'half'])) {
                if ($r->payment_type === 'half') {
                    $onlineHalf = $r->total_price / 2;
                    $gcashPayments += min($effectivePaid, $onlineHalf);
                    if ($effectivePaid > $onlineHalf) {
                        $cashPayments += ($effectivePaid - $onlineHalf);
                    }
                } else {
                    $gcashPayments += $effectivePaid;
                }
            } else {
                $cashPayments += $effectivePaid;
            }
        }

        return view('cashier.transactions', compact('reservations', 'totalRevenue', 'gcashPayments', 'cashPayments', 'pendingAmount'));
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

                // Send SMS via iProgSMS
                $phone = $reservation->user->contact ?? $reservation->user->phone_number;
                if (!empty($phone)) {
                    $date = \Carbon\Carbon::parse($reservation->start_time)->format('F j, Y');
                    $start = \Carbon\Carbon::parse($reservation->start_time)->format('g:i A');
                    $smsMsg = "Good day! Your reservation for Court {$reservation->court_id} on {$date} at {$start} has been officially CONFIRMED. Thank you!";
                    \App\Services\SmsService::send($phone, $smsMsg);
                }
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

            // Automatically flag for full refund if paid online (Admin/Cashier cancellation)
            if (in_array($reservation->payment_type, ['full', 'half'])) {
                $reservation->refund_status = 'pending';
                $paid = (float)$reservation->amount_paid;
                if ($paid == 0) {
                    $paid = ($reservation->payment_type == 'half') ? ((float)$reservation->total_price / 2) : (float)$reservation->total_price;
                }
                $reservation->refund_amount = $paid;
            }

            $reservation->save();

            if ($reservation->user_id) {
                \App\Models\Notification::create([
                    'user_id' => $reservation->user_id,
                    'reservation_id' => $reservation->id,
                    'title' => 'Reservation Cancelled',
                    'message' => 'Your reservation for Court ' . $reservation->court_id . ' has been cancelled by the cashier.'
                ]);

                // Send SMS via iProgSMS
                $phone = $reservation->user->contact ?? $reservation->user->phone_number;
                if (!empty($phone)) {
                    $date = \Carbon\Carbon::parse($reservation->start_time)->format('F j, Y');
                    $smsMsg = "Notice: Your reservation for Court {$reservation->court_id} on {$date} has been CANCELLED. If you paid online, please expect a refund.";
                    \App\Services\SmsService::send($phone, $smsMsg);
                }
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
            if ($reservation->amount_paid < $reservation->total_price) {
                $reservation->amount_paid = $reservation->total_price;
            }
            $reservation->save();

            return redirect('/cashier/dashboard')->with('success', 'Reservation Verified! Court ' . $reservation->court_id . ' is now In Play.');
        }

        return back()->with('error', 'Could not verify reservation.');
    }

    public function salesReportIndex(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Reservation::with(['user', 'court'])->orderBy('created_at', 'desc');
        
        if ($request->filled('sport') && $request->sport != 'all') {
            $query->where('sport', $request->sport);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $reservations = $query->get();

        // Fetch distinct sports from reservations table
        $sports = \App\Models\Reservation::whereNotNull('sport')->distinct()->pluck('sport');
        if ($sports->isEmpty()) {
            $sports = collect(['Badminton', 'Pickleball']);
        }
        
        $activeRes = $reservations->where('status', '!=', 'cancelled');

        $totalRevenue = 0;
        $gcashPayments = 0;
        $cashPayments = 0;
        $pendingAmount = 0;
        
        foreach($activeRes as $r) {
            // Determine actual paid amount safely (handle missing online amount_paid)
            $paid = (float)$r->amount_paid;
            if ($paid == 0 && in_array($r->payment_type, ['full', 'half'])) {
                $paid = ($r->payment_type == 'half') ? ($r->total_price / 2) : $r->total_price;
            }
            
            // Cap effective paid to avoid counting physical change/sukli as revenue
            $effectivePaid = min($paid, $r->total_price);
            $unpaid = $r->total_price - $effectivePaid;
            
            // If the reservation itself is completely unverified/pending, all of it is pending.
            if ($r->status === 'pending') {
                $pendingAmount += $r->total_price;
                continue;
            }
            
            // For approved reservations
            $totalRevenue += $effectivePaid;
            $pendingAmount += $unpaid;
            
            // Split GCash vs Cash
            if (in_array($r->payment_type, ['GCash', 'full', 'half'])) {
                // If it was half GCash and they paid the rest, the rest was likely Cash at the counter
                if ($r->payment_type === 'half') {
                    $onlineHalf = $r->total_price / 2;
                    $gcashPayments += min($effectivePaid, $onlineHalf);
                    if ($effectivePaid > $onlineHalf) {
                        $cashPayments += ($effectivePaid - $onlineHalf);
                    }
                } else {
                    $gcashPayments += $effectivePaid;
                }
            } else {
                $cashPayments += $effectivePaid;
            }
        }

        return view('cashier.sales-report', compact('reservations', 'totalRevenue', 'gcashPayments', 'cashPayments', 'pendingAmount', 'sports'));
    }

    public function salesRefundsIndex(\Illuminate\Http\Request $request)
    {
        $tab = $request->get('tab', 'pending');
        $search = $request->get('search', '');
        $refund_status = 'pending';
        if ($tab == 'completed') $refund_status = 'refunded';
        // Note: Rejected tab is removed, so we only handle pending and completed

        $query = \App\Models\Reservation::where('status', 'cancelled')
                    ->where('refund_status', $refund_status)
                    ->with('user', 'court');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('reservation_code', 'like', "%{$search}%")
                  ->orWhere('walk_in_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $refunds = $query->orderBy('cancelled_at', 'desc')->get();
                    
        $pendingCount = \App\Models\Reservation::where('status', 'cancelled')->where('refund_status', 'pending')->count();
        $completedCount = \App\Models\Reservation::where('status', 'cancelled')->where('refund_status', 'refunded')->count();

        return view('cashier.refunds', compact('refunds', 'pendingCount', 'completedCount', 'tab', 'search'));
    }

    public function approveRefund($id)
    {
        $reservation = \App\Models\Reservation::findOrFail($id);
        if ($reservation->refund_status === 'pending') {
            $reservation->refund_status = 'refunded';
            $reservation->save();

            // Notify user
            \App\Models\Notification::create([
                'user_id' => $reservation->user_id,
                'reservation_id' => $reservation->id,
                'title' => 'Refund Approved',
                'message' => "Your refund of ₱" . number_format($reservation->refund_amount, 2) . " for booking {$reservation->reservation_code} has been approved and processed.",
            ]);
        }
        return back()->with('success', 'Refund approved successfully.');
    }

    public function rejectRefund($id)
    {
        $reservation = \App\Models\Reservation::findOrFail($id);
        if ($reservation->refund_status === 'pending') {
            $reservation->refund_status = 'rejected';
            $reservation->save();

            // Notify user
            \App\Models\Notification::create([
                'user_id' => $reservation->user_id,
                'reservation_id' => $reservation->id,
                'title' => 'Refund Rejected',
                'message' => "Your refund request for booking {$reservation->reservation_code} was rejected by the cashier.",
            ]);
        }
        return back()->with('success', 'Refund rejected successfully.');
    }
    public function updateProfilePicture(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();
        
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists and not default
            if ($user->profile_picture && \Illuminate\Support\Facades\File::exists(public_path($user->profile_picture))) {
                \Illuminate\Support\Facades\File::delete(public_path($user->profile_picture));
            }

            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/profiles'), $filename);
            
            $user->profile_picture = 'uploads/profiles/' . $filename;
            $user->save();
        }

        return redirect()->back()->with('success', 'Profile picture updated successfully!');
    }
}
