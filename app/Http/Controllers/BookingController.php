<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function create()
    {
        $rooms = Room::where('status', Room::STATUS_AVAILABLE)->get();
        return view('bookings.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        \Log::info('Booking request received', [
            'request' => $request->all(),
            'user_id' => auth()->id()
        ]);

        try {
            $validated = $request->validate([
                'selected_rooms' => 'required|json',
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'id_number' => 'required|string|max:50',
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_out_date' => 'required|date|after:check_in_date',
                'special_requests' => 'nullable|string'
            ]);

            // Decode selected rooms
            $selectedRooms = json_decode($validated['selected_rooms'], true);
            if (empty($selectedRooms)) {
                return back()->withErrors([
                    'selected_rooms' => 'Silakan pilih setidaknya satu kamar.'
                ])->withInput();
            }

            \Log::info('Selected rooms decoded', [
                'selected_rooms' => $selectedRooms,
                'check_in' => $validated['check_in_date'],
                'check_out' => $validated['check_out_date']
            ]);

            // Start database transaction
            DB::beginTransaction();

            try {
                // Calculate initial total price
                $checkIn = new \DateTime($validated['check_in_date']);
                $checkOut = new \DateTime($validated['check_out_date']);
                $nights = $checkIn->diff($checkOut)->days;

                $totalPrice = 0;
                foreach ($selectedRooms as $roomData) {
                    $totalPrice += $roomData['price_per_night'] * $nights;
                }

                $tax = round($totalPrice * 0.1);
                $deposit = count($selectedRooms) * 300000;
                $finalTotal = $totalPrice + $tax + $deposit;

                // Create the booking with initial total price
                $booking = Booking::create([
                    'user_id' => auth()->id(),
                    'full_name' => $validated['full_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'id_number' => $validated['id_number'],
                    'check_in_date' => $validated['check_in_date'],
                    'check_out_date' => $validated['check_out_date'],
                    'special_requests' => $validated['special_requests'],
                    'status' => 'pending',
                    'payment_status' => 'pending',
                    'total_price' => $finalTotal,
                    'tax' => $tax,
                    'deposit' => $deposit
                ]);

                // Attach rooms to booking
                foreach ($selectedRooms as $roomData) {
                    $room = Room::find($roomData['id']);
                    if (!$room) {
                        throw new \Exception("Kamar dengan ID {$roomData['id']} tidak ditemukan.");
                    }

                    // Check if room is available for the selected dates
                    $isAvailable = $room->isAvailableForDates(
                        $validated['check_in_date'],
                        $validated['check_out_date']
                    );

                    if (!$isAvailable) {
                        throw new \Exception("Kamar {$room->name} tidak tersedia untuk tanggal yang dipilih.");
                    }

                    // Calculate room price
                    $roomPrice = $room->price_per_night * $nights;

                    // Attach room to booking
                    $booking->rooms()->attach($room->id, [
                        'price_per_night' => $room->price_per_night,
                        'subtotal' => $roomPrice,
                        'quantity' => 1
                    ]);
                }

                DB::commit();

                \Log::info('Booking created successfully', ['booking_id' => $booking->id]);

                // Clear selected rooms from session storage and redirect with success message
                return redirect()->route('bookings.show', $booking)
                    ->with('success', 'Pemesanan berhasil dibuat! Silakan lakukan pembayaran untuk mengkonfirmasi pemesanan Anda.')
                    ->with('clearCart', true);

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error creating booking: ' . $e->getMessage());
                return back()->withErrors(['error' => $e->getMessage()])->withInput();
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Unexpected error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat pemesanan. Silakan coba lagi.'])->withInput();
        }
    }

    public function show(Booking $booking)
    {
        return view('bookings.show', compact('booking'));
    }

    public function riwayat()
    {
        $riwayat = Booking::with('rooms') // Updated to use rooms relationship
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        return view('bookings.riwayat', compact('riwayat'));
    }

    public function getBookedDates($roomId)
    {
        $bookedDates = DB::table('booking_room')
            ->join('bookings', 'booking_room.booking_id', '=', 'bookings.id')
            ->where('booking_room.room_id', $roomId)
            ->where('bookings.status', '!=', 'cancelled')
            ->where(function($query) {
                $query->where('bookings.check_out_date', '>=', now())
                    ->orWhere('bookings.check_in_date', '>=', now());
            })
            ->select('bookings.check_in_date', 'bookings.check_out_date')
            ->get();

        $dates = [];
        foreach ($bookedDates as $booking) {
            $period = new \DatePeriod(
                new \DateTime($booking->check_in_date),
                new \DateInterval('P1D'),
                (new \DateTime($booking->check_out_date))->modify('+1 day')
            );

            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');
            }
        }

        return response()->json(array_values(array_unique($dates)));
    }
}