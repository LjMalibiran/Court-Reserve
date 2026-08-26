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
    /**
     * Handle checking availability and redirecting to the payment screen.
     */
    public function store(Request $request)
    {
        $request->validate([
            'court_id' => 'required|exists:courts,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required',
            'sport' => 'nullable|string' // Catch the sport from the frontend!
        ]);

        $start = Carbon::parse($request->reservation_date . ' ' . $request->start_time);
        $end = Carbon::parse($request->reservation_date . ' ' . $request->end_time);

        if ($start->isPast()) {
            return back()->withErrors(['start_time' => 'You cannot book a time slot that has already passed.']);
        }

        if ($end->lessThanOrEqualTo($start)) {
            return back()->withErrors(['end_time' => 'The end time must be after the start time.']);
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

        // 1. Grab the sport from the request (defaults to Badminton if empty)
        $sport = $request->sport ?? 'Badminton';

        // 2. Use PUT to lock the data into the session so it survives page refreshes!
        session()->put([
            'court_id' => $courtId,
            'sport' => $sport,
            'start_time' => $start->format('Y-m-d H:i:s'),
            'end_time' => $end->format('Y-m-d H:i:s'),
            'total_price' => $totalPrice
        ]);

        return redirect()->route('payment.index');
    }

    /**
     * Fetch booked time slots for the frontend AJAX check.
     */
    public function checkAvailability(\Illuminate\Http\Request $request)
    {
        $date = $request->date;
        $courtId = $request->court_id;

        // Find all active reservations for this specific date and court
        $reservations = Reservation::where('court_id', $courtId)
            ->whereDate('start_time', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $bookedSlots = [];

        // Loop through them and mark every hour they take up as "booked"
        foreach ($reservations as $res) {
            $start = Carbon::parse($res->start_time);
            $end = Carbon::parse($res->end_time);

            while ($start->lt($end)) {
                $bookedSlots[] = $start->format('g:i A'); // e.g., "8:00 AM"
                $start->addHour(); // Move to the next hour
            }
        }

        // Send the list of booked hours back to the browser
        return response()->json(['booked_slots' => $bookedSlots]);
    }

    /**
     * Handle final payment submission and save to the database.
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_type' => 'required|in:full,half',
            'receipt'      => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'court_id'     => 'required',
            'sport'        => 'required', // Validate the sport
            'start_time'   => 'required',
            'end_time'     => 'required',
            'total_amount' => 'required',
        ]);

        $imagePath = null;
        if ($request->hasFile('receipt')) {
            $imagePath = $request->file('receipt')->store('receipts', 'public');
        }

        // 3. Dynamically build the prefix: PC for Pickleball, BC for Badminton!
        $prefix = ($request->sport == 'Pickleball') ? 'PC' : 'BC';
        $reservationCode = $prefix . date('y') . '-' . strtoupper(Str::random(4));

        $reservation = new Reservation();
        $reservation->user_id = Auth::id();
        $reservation->court_id = $request->court_id;
        $reservation->sport = $request->sport; // Save the sport to the DB
        $reservation->start_time = $request->start_time;
        $reservation->end_time = $request->end_time;
        $reservation->total_price = $request->total_amount;
        $reservation->payment_type = $request->payment_type;
        $reservation->receipt_path = $imagePath;
        $reservation->status = 'pending';
        $reservation->reservation_code = $reservationCode;
        $reservation->save();

        // 4. Clear the temporary session data safely
        session()->forget(['court_id', 'sport', 'start_time', 'end_time', 'total_price']);

        return back()->with('success', true)->with('reservation_id', $reservation->reservation_code);
    }
}