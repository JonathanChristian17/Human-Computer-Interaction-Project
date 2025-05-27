<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'rooms'])
            ->latest()
            ->paginate(10);
            
        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $rooms = Room::where('status', 'available')->get();
        $users = User::where('role', 'customer')->get();
        
        return view('admin.bookings.create', compact('rooms', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);

        $booking = Booking::create($validated);

        // Update room status
        $room = Room::find($request->room_id);
        $room->update(['status' => 'occupied']);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking created successfully');
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'rooms']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $rooms = Room::where('status', 'available')
            ->orWhere('id', $booking->room_id)
            ->get();
        $users = User::where('role', 'customer')->get();
        
        return view('admin.bookings.edit', compact('booking', 'rooms', 'users'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);

        // If room is changed, update old and new room status
        if ($booking->room_id !== $request->room_id) {
            $oldRoom = Room::find($booking->room_id);
            $oldRoom->update(['status' => 'available']);
            
            $newRoom = Room::find($request->room_id);
            $newRoom->update(['status' => 'occupied']);
        }

        $booking->update($validated);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking updated successfully');
    }

    public function destroy(Booking $booking)
    {
        // Update room status back to available
        $room = Room::find($booking->room_id);
        $room->update(['status' => 'available']);

        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully');
    }
} 