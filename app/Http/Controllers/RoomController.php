<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::where('status', 'available')
                    ->select('id', 'name', 'price_per_night', 'capacity', 'description', 'image');

        // If check-in and check-out dates are provided, filter available rooms
        if ($request->filled(['check_in', 'check_out'])) {
            $checkIn = $request->check_in;
            $checkOut = $request->check_out;

            // Get rooms that are not booked for these dates
            $query->whereDoesntHave('bookings', function($query) use ($checkIn, $checkOut) {
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
        $rooms = Room::all(); // Or any specific query to get available rooms
        return view('rooms.create', compact('rooms'));
    }

    /**
     * Store a newly created room booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
        ]);

        // Calculate number of nights
        $checkIn = new \DateTime($validated['check_in']);
        $checkOut = new \DateTime($validated['check_out']);
        $nights = $checkIn->diff($checkOut)->days;

        // Get the room
        $room = Room::findOrFail($validated['room_id']);

        // Calculate total price
        $totalPrice = $room->price_per_night * $nights;

        // Create the booking
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'check_in_date' => $validated['check_in'],
            'check_out_date' => $validated['check_out'],
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        // Attach the room with pivot data
        $booking->rooms()->attach($room->id, [
            'price_per_night' => $room->price_per_night,
            'quantity' => 1,
            'subtotal' => $totalPrice
        ]);

        return redirect()->route('bookings.show', $booking)
                       ->with('success', 'Pemesanan berhasil dibuat!');
    }
}