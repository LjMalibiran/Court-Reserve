<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Court;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str; // <-- ADDED: Allows us to generate random codes

class ReservationController extends Controller
{
    /**
     * Handle checking availability and creating a reservation.
     */
    public function store(Request $request)
    {
        // 1. Validate the basic incoming format
        $request->validate([
            'court_id' => 'required|exists:courts,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required', // e.g., "14:00"
            'end_time' => 'required',   // e.g., "16:00"
        ]);

        // 2. Parse exact dates and times into clean standardized timestamps
        // FIX: Changed $request->date to $request->reservation_date to match your form
        $start = Carbon::parse($request->reservation_date . ' ' . $request->start_time);
        $end = Carbon::parse($request->reservation_date . ' ' . $request->end_time);

        // Fail Safe: Ensure the meeting doesn't end before it starts or have zero duration
        if ($end->lessThanOrEqualTo($start)) {
            return back()->withErrors(['end_time' => 'The end time must be after the start time.']);
        }

        $courtId = $request->court_id;

        // 3. The Anti-Double-Booking Guard Clause
        // Checks if there are any existing confirmed/pending records overlapping the requested window
        $isDoubleBooked = Reservation::where('court_id', $courtId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
                });
            })->exists();

        if ($isDoubleBooked) {
            return back()->withErrors(['availability' => 'This court is already reserved during your selected hours.']);
        }

        // 4. Automated Financial Calculations
        $court = Court::find($courtId);
        $durationInHours = $start->diffInMinutes($end) / 60;
        
        // ADDED FALLBACK: Just in case your courts table doesn't have a price_per_hour column yet, it defaults to 230
        $totalPrice = $durationInHours * ($court->price_per_hour ?? 230); 
        
        // Compute 50% down payment required by system policy
        $requiredDownPayment = $totalPrice * 0.50; 

        // Generate a unique 6-character reservation code (e.g., RES-X9A2B4)
        $reservationCode = 'RES-' . strtoupper(Str::random(6));

        // 5. Database Insertion
        Reservation::create([
            'user_id' => Auth::id(),
            'court_id' => $courtId,
            'start_time' => $start,
            'end_time' => $end,
            'total_price' => $totalPrice,
            'amount_paid' => 0.00, // Starts at zero until they complete checkout
            'status' => 'pending',
            'reservation_code' => $reservationCode, // <-- ADDED: Saves the unique code
        ]);

        // UPDATED: Your custom success message
        return redirect('/home')->with('success', 'The request is sent. Please wait for confirmation.');
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
}