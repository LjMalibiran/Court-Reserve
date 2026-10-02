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
        // This counts everyone EXCEPT the admin, cashier, walk-in users, and unverified users
        $registeredUsers = User::whereNotIn('role', ['admin', 'cashier'])
            ->where('email', 'NOT LIKE', 'walkin_%')
            ->whereNotNull('phone_verified_at')
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
            if ($reservation->amount_paid < $reservation->total_price) {
                $reservation->amount_paid = $reservation->total_price;
            }
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
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send reminder SMS to {$user->contact}: " . $e->getMessage());
            }

            // Send Email Notification
            try {
                $emailContent = "Hello {$user->name},\n\nThis is a friendly reminder for your upcoming {$reservation->sport} reservation at Batangas Badminton Center.\n\nDate: {$date}\nTime: {$start} - {$end}\nCourt: Court {$reservation->court_id}\n\nWe look forward to seeing you!";
                \Illuminate\Support\Facades\Mail::raw($emailContent, function ($message) use ($user) {
                    $message->to($user->email)->subject('Friendly Reminder: Upcoming Reservation');
                });
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send reminder Email to {$user->email}: " . $e->getMessage());
            }

            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'User not found or is a walk-in.']);
    }

    public function walkInIndex()
    {
        $settingsPath = storage_path('app/settings.json');
        $settings = file_exists($settingsPath) ? json_decode(file_get_contents($settingsPath), true) : [];
        return view('admin.walk-in', compact('settings'));
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

        return view('admin.sales-report', compact('reservations', 'totalRevenue', 'gcashPayments', 'cashPayments', 'pendingAmount', 'sports'));
    }

    public function salesTransactionsIndex()
    {
        return view('admin.sales-transactions');
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

        return view('admin.sales-refunds', compact('refunds', 'pendingCount', 'completedCount', 'tab', 'search'));
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
                'message' => "Your refund request for booking {$reservation->reservation_code} was rejected by the admin.",
            ]);
        }
        return back()->with('success', 'Refund rejected successfully.');
    }

    public function settingsIndex()
    {
        $settingsPath = storage_path('app/settings.json');
        $settings = file_exists($settingsPath) ? json_decode(file_get_contents($settingsPath), true) : [
            'price_badminton' => 230,
            'price_pickleball' => 250,
            'price_racket' => 50,
            'price_shuttlecock' => 50,
        ];
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(\Illuminate\Http\Request $request)
    {
        $settingsPath = storage_path('app/settings.json');
        $oldSettings = file_exists($settingsPath) ? json_decode(file_get_contents($settingsPath), true) : [];

        $blockedDates = [];
        if ($request->filled('blocked_dates')) {
            $blockedDates = json_decode($request->input('blocked_dates'), true) ?? [];
        }

        $settings = [
            'price_badminton' => (int) $request->input('price_badminton', 230),
            'price_pickleball' => (int) $request->input('price_pickleball', 250),
            'price_racket' => (int) $request->input('price_racket', 50),
            'price_shuttlecock' => (int) $request->input('price_shuttlecock', 50),
            'operating_hours' => $request->input('operating_hours', []),
            'blocked_dates' => $blockedDates,
        ];

        // Check for changes and create announcements automatically
        if (!empty($oldSettings)) {
            if (isset($oldSettings['price_badminton']) && $oldSettings['price_badminton'] != $settings['price_badminton']) {
                \App\Models\Announcement::where('title', 'Price Update: Badminton')->delete();
                \App\Models\Announcement::create([
                    'title' => 'Price Update: Badminton',
                    'content' => "The price for Badminton courts has been updated from ₱" . $oldSettings['price_badminton'] . " to ₱" . $settings['price_badminton'] . " per hour."
                ]);
            }
            if (isset($oldSettings['price_pickleball']) && $oldSettings['price_pickleball'] != $settings['price_pickleball']) {
                \App\Models\Announcement::where('title', 'Price Update: Pickleball')->delete();
                \App\Models\Announcement::create([
                    'title' => 'Price Update: Pickleball',
                    'content' => "The price for Pickleball courts has been updated from ₱" . $oldSettings['price_pickleball'] . " to ₱" . $settings['price_pickleball'] . " per hour."
                ]);
            }
            if (isset($oldSettings['price_racket']) && $oldSettings['price_racket'] != $settings['price_racket']) {
                \App\Models\Announcement::where('title', 'Price Update: Racket')->delete();
                \App\Models\Announcement::create([
                    'title' => 'Price Update: Racket',
                    'content' => "The price for Racket Rentals has been updated from ₱" . $oldSettings['price_racket'] . " to ₱" . $settings['price_racket'] . "."
                ]);
            }
            if (isset($oldSettings['price_shuttlecock']) && $oldSettings['price_shuttlecock'] != $settings['price_shuttlecock']) {
                \App\Models\Announcement::where('title', 'Price Update: Shuttlecock')->delete();
                \App\Models\Announcement::create([
                    'title' => 'Price Update: Shuttlecock',
                    'content' => "The price for Shuttlecocks has been updated from ₱" . $oldSettings['price_shuttlecock'] . " to ₱" . $settings['price_shuttlecock'] . "."
                ]);
            }
            if (isset($oldSettings['blocked_dates']) && $oldSettings['blocked_dates'] != $settings['blocked_dates']) {
                $newBlocks = array_map(function($b) { return json_encode($b); }, $settings['blocked_dates']);
                $oldBlocks = array_map(function($b) { return json_encode($b); }, $oldSettings['blocked_dates']);
                
                $addedBlocks = array_diff($newBlocks, $oldBlocks);
                $removedBlocks = array_diff($oldBlocks, $newBlocks);
                
                $msg = "";
                if (!empty($addedBlocks)) {
                    $addedStrings = [];
                    foreach ($addedBlocks as $json) {
                        $b = json_decode($json, true);
                        $date = \Carbon\Carbon::parse($b['date'])->format('M j, Y');
                        $start = \Carbon\Carbon::parse($b['start'])->format('g:i A');
                        $end = \Carbon\Carbon::parse($b['end'])->format('g:i A');
                        $addedStrings[] = "$date ($start - $end)";
                    }
                    $msg .= "New blocked times added:\n" . implode("\n", $addedStrings) . "\n\n";
                }
                if (!empty($removedBlocks)) {
                    $removedStrings = [];
                    foreach ($removedBlocks as $json) {
                        $b = json_decode($json, true);
                        $date = \Carbon\Carbon::parse($b['date'])->format('M j, Y');
                        $start = \Carbon\Carbon::parse($b['start'])->format('g:i A');
                        $end = \Carbon\Carbon::parse($b['end'])->format('g:i A');
                        $removedStrings[] = "$date ($start - $end)";
                    }
                    $msg .= "Blocked times removed (now available):\n" . implode("\n", $removedStrings);
                }
                
                if (trim($msg)) {
                    \App\Models\Announcement::where('title', 'Schedule Update')->delete();
                    \App\Models\Announcement::create([
                        'title' => 'Schedule Update',
                        'content' => trim($msg)
                    ]);
                }
            }
            if (isset($oldSettings['operating_hours']) && $oldSettings['operating_hours'] != $settings['operating_hours']) {
                $hoursMsg = [];
                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                foreach ($days as $day) {
                    $oldStart = $oldSettings['operating_hours'][$day]['start'] ?? '07:00';
                    $oldEnd = $oldSettings['operating_hours'][$day]['end'] ?? '21:00';
                    $newStart = $settings['operating_hours'][$day]['start'] ?? '07:00';
                    $newEnd = $settings['operating_hours'][$day]['end'] ?? '21:00';
                    
                    if ($oldStart != $newStart || $oldEnd != $newEnd) {
                        $oldStartFmt = \Carbon\Carbon::parse($oldStart)->format('g:i A');
                        $oldEndFmt = \Carbon\Carbon::parse($oldEnd)->format('g:i A');
                        $newStartFmt = \Carbon\Carbon::parse($newStart)->format('g:i A');
                        $newEndFmt = \Carbon\Carbon::parse($newEnd)->format('g:i A');
                        $hoursMsg[] = ucfirst($day) . " from {$oldStartFmt}-{$oldEndFmt} to {$newStartFmt}-{$newEndFmt}";
                    }
                }
                if (!empty($hoursMsg)) {
                    \App\Models\Announcement::where('title', 'Operating Hours Update')->delete();
                    \App\Models\Announcement::create([
                        'title' => 'Operating Hours Update',
                        'content' => "Our operating hours have been updated:\n" . implode("\n", $hoursMsg)
                    ]);
                }
            }
        }

        file_put_contents($settingsPath, json_encode($settings));
        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    public function profileIndex()
    {
        return view('admin.profile');
    }

    public function helpIndex()
    {
        return view('admin.help');
    }

    public function updateProfile(Request $request)
    {
        $user = \Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'contact' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->contact = $request->contact;

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profiles'), $filename);
            $user->profile_picture = 'uploads/profiles/' . $filename;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8',
            'new_password_confirmation' => 'required|same:new_password',
        ]);

        $user = \Auth::user();

        if (!\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $user->password = \Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
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

    // ==========================================
    // ANNOUNCEMENTS
    // ==========================================
    public function announcementsIndex()
    {
        $announcements = \App\Models\Announcement::orderBy('created_at', 'desc')->get();
        return view('admin.announcements', compact('announcements'));
    }

    public function storeAnnouncement(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        \App\Models\Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Announcement created successfully!');
    }

    public function deleteAnnouncement($id)
    {
        $announcement = \App\Models\Announcement::findOrFail($id);
        $announcement->delete();

        return back()->with('success', 'Announcement deleted successfully!');
    }
}



