<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{

public function index()
{
    $rooms = Room::all(); // Ambil semua kamar dari database
    return view('landingpage', compact('rooms')); // Sesuaikan nama view dengan yang kamu pakai
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

        // Add user_id to the booking
        $validated['user_id'] = auth()->id();

        // Create the booking
        $booking = Booking::create($validated);

        return redirect()->route('bookings.show', $booking)
                       ->with('success', 'Pemesanan berhasil dibuat!');
    }
}