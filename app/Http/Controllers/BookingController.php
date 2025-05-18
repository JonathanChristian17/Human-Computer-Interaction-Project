<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create()
    {
        $rooms = Room::all();
        return view('bookings.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'total_guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
        ]);

        $room = Room::findOrFail($validated['room_id']);

        // Hitung total harga
        $checkIn = new \DateTime($validated['check_in']);
        $checkOut = new \DateTime($validated['check_out']);
        $nights = $checkIn->diff($checkOut)->days;
        $totalPrice = $nights * $room->price;

        // Simpan booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $validated['room_id'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'total_guests' => $validated['total_guests'],
            'total_price' => $totalPrice,
            'special_requests' => $validated['special_requests'],
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.show', $booking)
                       ->with('success', 'Pemesanan berhasil dibuat!');
    }

    public function show(Booking $booking)
    {
        return view('bookings.show', compact('booking'));
    }
}