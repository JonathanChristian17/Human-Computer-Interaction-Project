<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Booking;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OfflineBookingController extends Controller
{
    public function create()
    {
        // Get all available rooms
        $rooms = Room::all();
        $selectedRooms = [];
        return view('receptionist.offline-booking', compact('rooms', 'selectedRooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'id_number' => 'required|string|max:16',
            'payment_status' => 'required|in:pending,paid,deposit',
            'special_requests' => 'nullable|string',
            'selected_rooms' => 'required|json',
        ]);

        try {
            // Decode selected rooms
            $selectedRooms = json_decode($request->selected_rooms, true);
            if (empty($selectedRooms)) {
                return back()->withErrors(['error' => 'Please select at least one room.'])->withInput();
            }

            // Calculate nights
            $checkIn = Carbon::parse($request->check_in_date);
            $checkOut = Carbon::parse($request->check_out_date);
            $nights = $checkIn->diffInDays($checkOut);

            // Calculate total amount and tax
            $totalAmount = 0;
            foreach ($selectedRooms as $roomData) {
                $totalAmount += $roomData['price_per_night'] * $nights;
            }
            $tax = $totalAmount * 0.1; // 10% tax
            $deposit = $request->payment_status === 'deposit' ? 100000 : 0;

            DB::beginTransaction();

            try {
                // Create or find guest user
                $guestEmail = $request->email ?? 'guest_' . Str::random(10) . '@offline.booking';
                $guestUser = User::firstOrCreate(
                    ['email' => $guestEmail],
                    [
                        'name' => $request->full_name,
                        'password' => Hash::make(Str::random(16)),
                        'role' => 'customer'
                    ]
                );

                // Create booking
                $booking = new Booking();
                $booking->user_id = $guestUser->id; // Use guest user ID instead of receptionist ID
                $booking->full_name = $request->full_name;
                $booking->email = $guestEmail;
                $booking->phone = $request->phone;
                $booking->id_number = $request->id_number;
                $booking->check_in_date = $request->check_in_date;
                $booking->check_out_date = $request->check_out_date;
                $booking->status = 'confirmed';
                $booking->payment_status = $request->payment_status;
                $booking->total_price = $totalAmount;
                $booking->tax = $tax;
                $booking->deposit = $deposit;
                $booking->special_requests = $request->special_requests;
                $booking->managed_by = auth()->id(); // Keep track of which receptionist created the booking
                $booking->check_in_time = '14:00:00';
                $booking->check_out_time = '12:00:00';
                $booking->save();

                // Attach rooms to booking
                foreach ($selectedRooms as $roomData) {
                    $room = Room::findOrFail($roomData['id']);
                    
                    // Check if room is available
                    if (!$room->isAvailableForDates($request->check_in_date, $request->check_out_date)) {
                        DB::rollBack();
                        return back()->withErrors(['error' => "Room {$room->name} is not available for the selected dates."])->withInput();
                    }

                    // Attach room with pivot data
                    $booking->rooms()->attach($room->id, [
                        'quantity' => 1,
                        'price_per_night' => $room->price_per_night,
                        'subtotal' => $room->price_per_night * $nights
                    ]);
                }

                // Log activity after successful booking creation
                Activity::log(
                    auth()->id(),
                    'Membuat booking offline',
                    "Membuat booking offline untuk {$booking->full_name}, " . count($selectedRooms) . " kamar, check-in: {$booking->check_in_date->format('Y-m-d')}",
                    'offline_booking_create',
                    $booking
                );

                DB::commit();

                return redirect()->route('receptionist.bookings')
                    ->with('success', 'Booking created successfully. Booking ID: ' . $booking->id);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Offline booking error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred while creating the booking. Please try again.'])->withInput();
        }
    }

    public function getBookedDates(Room $room)
    {
        $bookedDates = Booking::where('room_id', $room->id)
            ->where(function($query) {
                $query->where('status', 'confirmed')
                    ->orWhere('status', 'checked_in');
            })
            ->get()
            ->flatMap(function ($booking) {
                return $this->getDatesRange($booking->check_in_date, $booking->check_out_date);
            })
            ->unique()
            ->values()
            ->all();

        return response()->json([
            'unavailable_dates' => $bookedDates
        ]);
    }

    private function getDatesRange($startDate, $endDate)
    {
        $dates = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current < $end) {
            $dates[] = $current->format('Y-m-d');
            $current->addDay();
        }

        return $dates;
    }
} 