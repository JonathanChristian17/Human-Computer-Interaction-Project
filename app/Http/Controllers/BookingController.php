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
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'id_number' => 'required|string|max:50',
        'check_in' => 'required|date|after_or_equal:today',
        'check_out' => 'required|date|after:check_in',
        'guests' => 'required|integer|min:1',
        'billing_address' => 'required|string',
        'billing_city' => 'required|string|max:100',
        'billing_province' => 'required|string|max:100',
        'billing_postal_code' => 'required|string|max:10',
        'special_requests' => 'nullable|string',
    ]);

    // Hitung total harga
    $room = Room::findOrFail($validated['room_id']);
    $checkIn = new \DateTime($validated['check_in']);
    $checkOut = new \DateTime($validated['check_out']);
    $nights = $checkIn->diff($checkOut)->days;
    $totalPrice = $nights * $room->price;

    // Buat booking
    $booking = Booking::create([
        'user_id' => auth()->id(),
        'room_id' => $validated['room_id'],
        'full_name' => $validated['full_name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'id_number' => $validated['id_number'],
        'check_in' => $validated['check_in'],
        'check_out' => $validated['check_out'],
        'guests' => $validated['guests'],
        'total_price' => $totalPrice,
        'billing_address' => $validated['billing_address'],
        'billing_city' => $validated['billing_city'],
        'billing_province' => $validated['billing_province'],
        'billing_postal_code' => $validated['billing_postal_code'],
        'special_requests' => $validated['special_requests'],
    ]);

    return redirect()->route('bookings.show', $booking)
                   ->with('success', 'Pemesanan berhasil dibuat!');
}

    public function show(Booking $booking)
    {
        return view('bookings.show', compact('booking'));
    }
}