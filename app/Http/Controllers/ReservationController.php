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
        // 1. Validate the basic incoming format
        $request->validate([
            'court_id' => 'required|exists:courts,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required', // e.g., "14:00"
            'end_time' => 'required',   // e.g., "16:00"
        ]);

        // 2. Parse exact dates and times into clean standardized timestamps
        $start = Carbon::parse($request->reservation_date . ' ' . $request->start_time);
        $end = Carbon::parse($request->reservation_date . ' ' . $request->end_time);

        // Fail Safe: Ensure the meeting doesn't end before it starts
        if ($end->lessThanOrEqualTo($start)) {
            return back()->withErrors(['end_time' => 'The end time must be after the start time.']);
        }

        $courtId = $request->court_id;

        // 3. The Anti-Double-Booking Guard Clause
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
        // ... (inside your store function)
        $court = Court::find($courtId);
        $durationInHours = $start->diffInMinutes($end) / 60;
        $totalPrice = $durationInHours * ($court->price_per_hour ?? 230); 

        // ADDED: Fetch the specific sport from your database (Defaults to Badminton if not found)
        $sport = $court->sport ?? 'Badminton'; 

        // BRIDGE: Forward everything to the Payment page!
        return redirect()->route('payment.index')->with([
            'court_id' => $courtId,
            'sport' => $sport, // Pass the sport to the payment page
            'start_time' => $start->format('Y-m-d H:i:s'),
            'end_time' => $end->format('Y-m-d H:i:s'),
            'total_price' => $totalPrice
        ]);
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
        // 1. Validate the form, image, AND the hidden fields passed from the previous page
        $request->validate([
            'payment_type' => 'required|in:full,half',
            'receipt'      => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'court_id'     => 'required',
            'start_time'   => 'required',
            'end_time'     => 'required',
            'total_amount' => 'required',
        ]);

        // 2. Save the uploaded GCash receipt securely
        $imagePath = null;
        if ($request->hasFile('receipt')) {
            // Saves to storage/app/public/receipts
            $imagePath = $request->file('receipt')->store('receipts', 'public');
        }

        // Generate a unique 6-character reservation code (e.g., RES-X9A2B4)
        $reservationCode = 'RES-' . strtoupper(Str::random(6));

        // 3. Save EVERYTHING to the database
        $reservation = new Reservation();
        $reservation->user_id = Auth::id();
        $reservation->court_id = $request->court_id;
        $reservation->start_time = $request->start_time;
        $reservation->end_time = $request->end_time;
        $reservation->total_price = $request->total_amount;
        $reservation->payment_type = $request->payment_type;
        $reservation->receipt_path = $imagePath;
        $reservation->status = 'pending';
        $reservation->reservation_code = $reservationCode;
        $reservation->save();

        // 4. Send back to the page and trigger the Success Modal!
        return back()->with('success', true)->with('reservation_id', $reservation->reservation_code);
    }
}