<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Court;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'court_id' => 'required|exists:courts,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required',
            'sport' => 'nullable|string' 
        ]);

        $start = Carbon::parse($request->reservation_date . ' ' . $request->start_time);
        $end = Carbon::parse($request->reservation_date . ' ' . $request->end_time);

        if ($start->isPast()) {
            return back()->withErrors(['start_time' => 'You cannot book a time slot that has already passed.']);
        }

        if ($end->lessThanOrEqualTo($start)) {
            return back()->withErrors(['end_time' => 'The end time must be after the start time.']);
        }

        $courtId = $request->court_id;

        $isDoubleBooked = Reservation::where('court_id', $courtId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)->where('end_time', '>', $start);
                });
            })->exists();

        if ($isDoubleBooked) {
            return back()->withErrors(['availability' => 'This court is already reserved.']);
        }

        $court = Court::find($courtId);
        $durationInHours = $start->diffInMinutes($end) / 60;
        $totalPrice = $durationInHours * ($court->price_per_hour ?? 230); 

        $sport = $request->sport ?? 'Badminton';

        session()->put([
            'court_id' => $courtId,
            'sport' => $sport,
            'start_time' => $start->format('Y-m-d H:i:s'),
            'end_time' => $end->format('Y-m-d H:i:s'),
            'total_price' => $totalPrice
        ]);

        return redirect()->route('payment.index');
    }

    public function checkAvailability(\Illuminate\Http\Request $request)
    {
        $date = $request->date;
        $courtId = $request->court_id;
        $excludeId = $request->exclude_id; // Add this to ignore the current reservation

        $query = Reservation::where('court_id', $courtId)
            ->whereDate('start_time', $date)
            ->whereIn('status', ['pending', 'confirmed']); // Ignores cancelled automatically
            
        // If we are editing, ignore the user's own current reservation blocks!
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $reservations = $query->get();
        $bookedSlots = [];

        foreach ($reservations as $res) {
            $start = Carbon::parse($res->start_time);
            $end = Carbon::parse($res->end_time);

            while ($start->lt($end)) {
                $bookedSlots[] = $start->format('g:i A'); 
                $start->addHour(); 
            }
        }

        return response()->json(['booked_slots' => $bookedSlots]);
    }
    
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_type' => 'required|in:full,half',
            'receipt'      => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'court_id'     => 'required',
            'sport'        => 'required', 
            'start_time'   => 'required',
            'end_time'     => 'required',
            'total_amount' => 'required',
        ]);

        $imagePath = null;
        if ($request->hasFile('receipt')) {
            $imagePath = $request->file('receipt')->store('receipts', 'public');
        }

        $prefix = ($request->sport == 'Pickleball') ? 'PC' : 'BC';
        $reservationCode = $prefix . date('y') . '-' . strtoupper(Str::random(4));

        $reservation = new Reservation();
        $reservation->user_id = Auth::id();
        $reservation->court_id = $request->court_id;
        $reservation->sport = $request->sport; 
        $reservation->start_time = $request->start_time;
        $reservation->end_time = $request->end_time;
        $reservation->total_price = $request->total_amount;
        $reservation->payment_type = $request->payment_type;
        $reservation->receipt_path = $imagePath;
        $reservation->status = 'pending';
        $reservation->reservation_code = $reservationCode;
        $reservation->save();

        session()->forget(['court_id', 'sport', 'start_time', 'end_time', 'total_price']);

        return back()->with('success', true)
                     ->with('reservation_code', $reservation->reservation_code)
                     ->with('flash_sport', $reservation->sport)
                     ->with('flash_court', $reservation->court_id)
                     ->with('flash_start', $reservation->start_time);
    }

    public function cancelUserReservation(Request $request, $id)
    {
        $reservation = Reservation::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $reservation->status = 'cancelled';
        $reservation->save();

        return response()->json(['success' => true]);
    }

    public function markNotificationsRead()
    {
        if(Auth::check()) {
            Auth::user()->customNotifications()->where('is_read', false)->update(['is_read' => true]);
        }
        return response()->json(['success' => true]);
    }

    // Mark a SINGLE notification as read
    public function markSingleNotificationRead($id)
    {
        $notification = \App\Models\Notification::where('id', $id)
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->first();
            
        if ($notification) {
            $notification->is_read = 1;
            $notification->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    // Save the User's Edited Reservation
    public function editUserReservation(Request $request, $id)
    {
        $reservation = Reservation::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $request->validate([
            'reservation_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required',
            'court_id' => 'required'
        ]);

        $start = Carbon::parse($request->reservation_date . ' ' . $request->start_time);
        $end = Carbon::parse($request->reservation_date . ' ' . $request->end_time);

        if ($start->isPast()) {
            return back()->withErrors(['start_time' => 'You cannot book a time slot that has already passed.']);
        }

        // Check if the NEW time and NEW court is already booked by someone else
        $isDoubleBooked = Reservation::where('court_id', $request->court_id)
            ->where('id', '!=', $reservation->id) // Ignore their own current reservation
            ->whereIn('status', ['pending', 'confirmed']) // Ignore cancelled
            ->where(function ($query) use ($start, $end) {
                $query->where('start_time', '<', $end)->where('end_time', '>', $start);
            })->exists();

        if ($isDoubleBooked) {
            return back()->withErrors(['availability' => 'This court is already reserved at that time.']);
        }

        // Update the reservation in the database
        $reservation->start_time = $start;
        $reservation->end_time = $end;
        $reservation->court_id = $request->court_id;
        
        // Revert to pending so the Admin/Cashier can approve the new time
        $reservation->status = 'pending'; 
        $reservation->save();

        // Notify Admin
        \App\Models\Notification::create([
            'user_id' => 1, // Sends to Admin
            'reservation_id' => $reservation->id,
            'title' => 'Reservation Rescheduled',
            'message' => Auth::user()->name . ' moved their ' . $reservation->sport . ' booking to ' . $start->format('M j, g:i A') . ' on Court ' . $request->court_id . '. Needs approval.'
        ]);

        return back()->with('success', 'Reservation updated successfully! Please wait for admin approval.');
    }
    // --- WALK-IN RESERVATION METHODS ---

    public function walkInIndex()
    {
        // Fetch only walk-ins (identified by the W- prefix we are about to create)
        $walkIns = \App\Models\Reservation::where('reservation_code', 'LIKE', 'W-%')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Automatically returns the correct view depending on if Admin or Cashier is logged in
        if (request()->segment(1) == 'cashier') {
            return view('cashier.walk-in', compact('walkIns'));
        }
        return view('admin.walk-in', compact('walkIns'));
    }

    public function storeWalkIn(\Illuminate\Http\Request $request)
    {
        // 1. Create a fast "stub" user account
        // FIX: Removed the 'role' line to let your database apply the default automatically!
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => 'walkin_' . \Illuminate\Support\Str::random(6) . '@batangas.com',
            'password' => \Illuminate\Support\Facades\Hash::make('walkin123')
        ]);

        // 2. Format a specific Walk-In Reservation Code (e.g. W-BC26-ABCD)
        $prefix = $request->sport == 'Pickleball' ? 'W-PC' : 'W-BC';
        $code = $prefix . date('y') . '-' . strtoupper(\Illuminate\Support\Str::random(4));

        // 3. Save the actual reservation
        $res = new \App\Models\Reservation();
        $res->user_id = $user->id;
        $res->court_id = $request->court;
        $res->sport = $request->sport;
        $res->start_time = \Carbon\Carbon::parse($request->date . ' ' . $request->start_time);
        $res->end_time = \Carbon\Carbon::parse($request->date . ' ' . $request->end_time);
        $res->total_price = $request->total_amount;
        $res->payment_type = $request->payment_method ?? 'Cash';
        $res->status = 'confirmed'; // Automatically blocks the slot for online users!
        $res->reservation_code = $code;
        $res->save();

        return redirect()->back()->with('success', 'Walk-in added! The time slot is now blocked for online users.');
    }
    
    public function updateWalkInStatus($id, $status)
    {
        $res = \App\Models\Reservation::findOrFail($id);
        if ($status == 'delete') {
            $res->delete();
            return redirect()->back()->with('success', 'Walk-in record deleted.');
        }
        $res->status = $status;
        $res->save();
        return redirect()->back()->with('success', 'Walk-in status updated!');
    }
}