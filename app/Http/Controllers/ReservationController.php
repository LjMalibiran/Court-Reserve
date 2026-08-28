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

        $reservations = Reservation::where('court_id', $courtId)
            ->whereDate('start_time', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

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

    public function updateUserReservation(Request $request, $id)
    {
        $reservation = Reservation::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $request->validate([
            'reservation_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        $start = Carbon::parse($request->reservation_date . ' ' . $request->start_time);
        $end = Carbon::parse($request->reservation_date . ' ' . $request->end_time);

        if ($start->isPast()) {
            return back()->withErrors(['start_time' => 'You cannot book a time slot that has already passed.']);
        }

        $isDoubleBooked = Reservation::where('court_id', $reservation->court_id)
            ->where('id', '!=', $reservation->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)->where('end_time', '>', $start);
                });
            })->exists();

        if ($isDoubleBooked) {
            return back()->withErrors(['availability' => 'This court is already reserved.']);
        }

        $reservation->start_time = $start;
        $reservation->end_time = $end;
        // REQUIRED: Force status back to pending so Admin confirms the new time!
        $reservation->status = 'pending'; 
        $reservation->save();

        return back()->with('success', 'Reservation updated successfully. It is now awaiting approval.');
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
}