<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        // Get selected room IDs from the request
        $roomIds = $request->input('room_ids', []);
        
        // Fetch the selected rooms
        $selectedRooms = Room::whereIn('id', $roomIds)->get();
        
        if ($selectedRooms->isEmpty()) {
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Please select at least one room to proceed with booking.'
                ], 400);
            }
            return redirect()->route('kamar.index')
                ->with('error', 'Please select at least one room to proceed with booking.');
        }

        // First, get ALL bookings for ALL rooms
        $allBookings = DB::table('booking_room')
            ->join('bookings', 'booking_room.booking_id', '=', 'bookings.id')
            ->whereNotIn('bookings.status', ['cancelled', 'refunded'])
            ->where(function($query) {
                $query->where('bookings.check_out_date', '>=', now()->format('Y-m-d'))
                    ->orWhere('bookings.check_in_date', '>=', now()->format('Y-m-d'));
            })
            ->select(
                'booking_room.room_id',
                'bookings.check_in_date',
                'bookings.check_out_date'
            )
            ->get();

        // Initialize arrays to store booked dates
        $allBookedDates = [];
        $unionBookedDates = [];

        // Process all bookings
        foreach ($allBookings as $booking) {
            $startDate = new \DateTime($booking->check_in_date);
            $endDate = new \DateTime($booking->check_out_date);
            $period = new \DatePeriod(
                $startDate,
                new \DateInterval('P1D'),
                $endDate->modify('+1 day')
            );

            foreach ($period as $date) {
                $dateStr = $date->format('Y-m-d');
                
                // Store in allBookedDates for individual room view
                if (!isset($allBookedDates[$booking->room_id])) {
                    $allBookedDates[$booking->room_id] = [];
                }
                $allBookedDates[$booking->room_id][$dateStr] = true;

                // If this is a selected room, add to unionBookedDates
                if (in_array($booking->room_id, $roomIds)) {
                    if (!isset($unionBookedDates[$dateStr])) {
                        $unionBookedDates[$dateStr] = [];
                    }
                    $unionBookedDates[$dateStr][] = $booking->room_id;
                }
            }
        }

        // Debug logging
        \Log::info('Selected Room IDs:', $roomIds);
        \Log::info('All Booked Dates:', $allBookedDates);
        \Log::info('Union Booked Dates:', $unionBookedDates);
        
        // Calculate initial totals
        $nights = 1;
        $subtotal = 0;
        foreach ($selectedRooms as $room) {
            $subtotal += $room->price_per_night * $nights;
        }
        
        $tax = 0;
        $deposit = 0;
        $total = $subtotal;
        
        return view('bookings.create', compact(
            'selectedRooms',
            'allBookedDates',
            'unionBookedDates',
            'subtotal',
            'tax',
            'deposit',
            'total'
        ));
    }

    public function store(Request $request)
    {
        \Log::info('Booking request received', [
            'request' => $request->all(),
            'user_id' => auth()->id(),
            'route' => $request->route()->getName(),
            'url' => $request->url(),
            'method' => $request->method()
        ]);

        try {
            // Validate the request
            $validated = $request->validate([
                'selected_rooms' => 'required|json',
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'id_number' => 'required|string|max:50',
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_out_date' => 'required|date|after:check_in_date',
                'special_requests' => 'nullable|string',
                'payment_method' => 'required|in:midtrans,direct',
                'payment_status' => 'nullable|string',
                'order_id' => 'nullable|string'
            ]);

            \Log::info('Validation passed', $validated);

            // Decode selected rooms
            $selectedRooms = json_decode($validated['selected_rooms'], true);
            if (empty($selectedRooms)) {
                \Log::error('No rooms selected');
                return response()->json([
                    'success' => false,
                    'message' => 'Please select at least one room.'
                ], 422);
            }

            // Calculate nights
            $checkIn = \Carbon\Carbon::parse($validated['check_in_date']);
            $checkOut = \Carbon\Carbon::parse($validated['check_out_date']);
            $nights = $checkIn->diffInDays($checkOut);

            // Calculate totals
            $subtotal = 0;
            foreach ($selectedRooms as $roomData) {
                $room = Room::find($roomData['id']);
                if (!$room) {
                    throw new \Exception("Room with ID {$roomData['id']} not found.");
                }

                // Check if room is available
                $isAvailable = $room->isAvailableForDates(
                    $validated['check_in_date'],
                    $validated['check_out_date']
                );

                if (!$isAvailable) {
                    return response()->json([
                        'success' => false,
                        'message' => "Room {$room->name} is not available for the selected dates."
                    ], 422);
                }

                $pricePerNight = $room->price_per_night;
                $roomSubtotal = $pricePerNight * $nights;
                $subtotal += $roomSubtotal;
            }

            // Calculate final total (no tax, no deposit)
            $finalTotal = $subtotal;
            $tax = 0;
            $deposit = 0;

            if ($validated['payment_method'] === 'midtrans') {
                if (!isset($validated['payment_status'])) {
                    // This is the initial request to get snap token
                    $orderId = sprintf('ORDER-%d-%d', time(), auth()->id());

                    // Set up Midtrans configuration
                    \Midtrans\Config::$serverKey = config('midtrans.server_key');
                    \Midtrans\Config::$isProduction = config('midtrans.is_production');

                    // Prepare Midtrans parameters
                    $params = [
                        'transaction_details' => [
                            'order_id' => $orderId,
                            'gross_amount' => (float) $finalTotal
                        ],
                        'customer_details' => [
                            'first_name' => $validated['full_name'],
                            'email' => $validated['email'],
                            'phone' => $validated['phone']
                        ],
                        'item_details' => [
                            [
                                'id' => 'room_charge',
                                'price' => (float) $finalTotal,
                                'quantity' => 1,
                                'name' => 'Room Charge for ' . $nights . ' night(s)'
                            ]
                        ],
                        'enabled_payments' => [
                            'bca_va', 'bni_va', 'bri_va', 'mandiri_va', 'permata_va', 'other_va',
                            'gopay', 'shopeepay', 'indomaret',
                            'bca_klikbca', 'bca_klikpay', 'cimb_clicks', 'danamon_online', 'bri_epay'
                        ],
                        'callbacks' => [
                            'finish' => url('/payment/finish'),
                            'error' => url('/payment/error'),
                            'cancel' => url('/payment/cancel')
                        ]
                    ];

                    try {
                        $snapToken = \Midtrans\Snap::getSnapToken($params);

                        return response()->json([
                            'success' => true,
                            'snap_token' => $snapToken,
                            'booking_data' => [
                                'order_id' => $orderId
                            ]
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Error getting snap token: ' . $e->getMessage());
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to initialize payment'
                        ], 500);
                    }
                }
            }

            // At this point, we're either creating a direct payment booking
            // or creating a Midtrans booking after payment method selection
            DB::beginTransaction();
            try {
                // Create booking
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
                    'payment_status' => $validated['payment_status'] ?? 'pending',
                    'total_price' => $finalTotal,
                    'tax' => $tax,
                    'deposit' => $deposit
                ]);

                // Attach rooms
                foreach ($selectedRooms as $roomData) {
                    $room = Room::find($roomData['id']);
                    $booking->rooms()->attach($room->id, [
                        'price_per_night' => $room->price_per_night,
                        'subtotal' => $room->price_per_night * $nights,
                        'quantity' => 1
                    ]);
                }

                // Create transaction record for Midtrans payments
                if ($validated['payment_method'] === 'midtrans' && isset($validated['order_id'])) {
                    // Map 'success' to 'paid' for payment_status
                    $paymentStatus = $validated['payment_status'] === 'success' ? 'paid' : $validated['payment_status'];
                    
                    $transaction = Transaction::create([
                        'booking_id' => $booking->id,
                        'order_id' => $validated['order_id'],
                        'gross_amount' => $finalTotal,
                        'payment_type' => $validated['payment_method'],
                        'transaction_status' => $validated['payment_status'],
                        'payment_status' => $paymentStatus
                    ]);

                    // Update booking payment status
                    $booking->update([
                        'payment_status' => $paymentStatus,
                        'status' => $paymentStatus === 'paid' ? 'confirmed' : 'pending'
                    ]);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'booking_id' => $booking->id,
                    'message' => 'Booking created successfully'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error creating booking: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create booking: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Error in booking process: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function show(Booking $booking)
    {
        return view('bookings.show', compact('booking'));
    }

    public function riwayat()
    {
        $riwayat = Booking::with('rooms')
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
            ->whereNotIn('bookings.status', ['cancelled', 'refunded']) // Exclude cancelled and refunded bookings
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

    public function finish(Request $request)
    {
        Log::info('Finish endpoint called', [
            'request' => $request->all(),
            'session' => session()->all()
        ]);

        try {
            // Get transaction from order_id if available
            $orderId = $request->input('order_id');
            if ($orderId) {
                $transaction = Transaction::where('order_id', $orderId)->first();
                if ($transaction) {
                    Log::info('Transaction found', ['transaction_id' => $transaction->id]);
                    // Redirect to landing page with transaction panel open
                    return redirect()->to('/?panel=transactions&source=midtrans')
                        ->with('success', 'Payment processed successfully!');
                }
            }

            // If no transaction found, redirect to home with error
            Log::error('No transaction found for order_id: ' . $orderId);
            return redirect()->route('home')
                ->with('error', 'Transaction not found. Please contact support if you have made a payment.');

        } catch (\Exception $e) {
            Log::error('Error in finish endpoint: ' . $e->getMessage());
            return redirect()->route('home')
                ->with('error', 'An error occurred while processing your payment.');
        }
    }

    public function error()
    {
        session()->forget('temp_booking');
        return redirect()->route('home')
            ->with('error', 'Payment failed. Please try again.');
    }

    public function cancel()
    {
        session()->forget('temp_booking');
        return redirect()->route('home')
            ->with('info', 'Booking cancelled.');
    }

    public function destroy(Booking $booking)
    {
        try {
            // Check if booking belongs to current user
            if ($booking->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Only allow deletion of pending bookings
            if ($booking->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending bookings can be deleted'
                ], 400);
            }

            DB::beginTransaction();
            try {
                // Delete associated transaction if exists
                if ($booking->transaction) {
                    $booking->transaction->delete();
                }

                // Delete booking
                $booking->delete();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Booking deleted successfully'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting booking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete booking'
            ], 500);
        }
    }
}