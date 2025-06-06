<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::select('id', 'name', 'room_number', 'type', 'price_per_night', 'capacity', 'description', 'image', 'status');

        // If check-in and check-out dates are provided, filter available rooms
        if ($request->filled(['check_in', 'check_out'])) {
            $checkIn = $request->check_in;
            $checkOut = $request->check_out;

            // Get rooms that are not booked for these dates and are available
            $query->where(function($query) {
                $query->where('status', 'available');
            })->whereDoesntHave('bookings', function($query) use ($checkIn, $checkOut) {
                $query->join('transactions', 'bookings.id', '=', 'transactions.booking_id')
                    ->where(function($q) {
                        // Include only valid bookings
                        $q->whereNotIn('bookings.status', ['cancelled'])
                          ->whereNotIn('bookings.payment_status', ['cancelled'])
                          ->where(function($q) {
                              $q->where('transactions.payment_status', '!=', 'expire')
                                ->orWhere(function($q) {
                                    // For pending payments, check if they're not expired (within 1 hour)
                                    $q->where('transactions.payment_status', 'pending')
                                      ->where('transactions.created_at', '>=', now()->subHour());
                                });
                          });
                    })
                    ->where(function($q) use ($checkIn, $checkOut) {
                        // Check for any date overlap scenarios
                        $q->where(function($q) use ($checkIn, $checkOut) {
                            $q->where('check_in_date', '<=', $checkOut)
                              ->where('check_out_date', '>=', $checkIn);
                        });
                    });
            });
        }

        $rooms = $query->paginate(6)->withQueryString();

        if ($request->ajax()) {
            return view('partials.room-list', compact('rooms'));
        }

        return view('kamar', compact('rooms'));
    }

    /**
     * Show the form for creating a new room booking.
     */
    public function create()
    {
        $rooms = Room::where('status', 'available')->get();
        return view('rooms.create', compact('rooms'));
    }

    /**
     * Store a newly created room booking.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guest_count' => 'required|integer|min:1'
        ]);

        // Check if room is available
        $room = Room::findOrFail($validated['room_id']);
        if ($room->status !== 'available') {
            return back()->with('error', 'Room is not available for booking.');
        }

        // Create booking
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'guest_count' => $validated['guest_count'],
            'status' => 'pending'
        ]);

        // Attach room to booking
        $booking->rooms()->attach($room->id);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking created successfully.');
    }
}