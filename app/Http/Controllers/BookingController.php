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
        
        // Get booked dates for all rooms
        $bookedDates = [];
        foreach ($selectedRooms as $room) {
            $bookings = DB::table('booking_room')
                ->join('bookings', 'booking_room.booking_id', '=', 'bookings.id')
                ->where('booking_room.room_id', $room->id)
                ->whereNotIn('bookings.status', ['cancelled', 'refunded']) // Exclude cancelled and refunded bookings
                ->where(function($query) {
                    $query->where('bookings.check_out_date', '>=', now()->format('Y-m-d'))
                        ->orWhere('bookings.check_in_date', '>=', now()->format('Y-m-d'));
                })
                ->select('bookings.check_in_date', 'bookings.check_out_date', 'booking_room.room_id')
                ->get();

            foreach ($bookings as $booking) {
                $startDate = new \DateTime($booking->check_in_date);
                $endDate = new \DateTime($booking->check_out_date);
                $period = new \DatePeriod(
                    $startDate,
                    new \DateInterval('P1D'),
                    $endDate->modify('+1 day')
                );

                foreach ($period as $date) {
                    $dateStr = $date->format('Y-m-d');
                    if (!isset($bookedDates[$dateStr])) {
                        $bookedDates[$dateStr] = [];
                    }
                    $bookedDates[$dateStr][] = $room->id;
                }
            }
        }

        // Log booked dates for debugging
        \Log::info('Booked dates:', $bookedDates);
        
        // Calculate initial totals
        $nights = 1; // Default to 1 night for initial calculation
        $subtotal = 0;
        foreach ($selectedRooms as $room) {
            $subtotal += $room->price_per_night * $nights;
        }
        
        // No tax, no deposit
        $tax = 0;
        $deposit = 0;
        $total = $subtotal;
        
        if ($request->ajax()) {
            return view('bookings.create', compact('selectedRooms', 'subtotal', 'tax', 'deposit', 'total', 'bookedDates'))
                ->render();
        }
        
        return view('bookings.create', compact('selectedRooms', 'subtotal', 'tax', 'deposit', 'total', 'bookedDates'));
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
                'payment_method' => 'required|in:midtrans,direct'
            ]);

            \Log::info('Validation passed', $validated);

            // Decode selected rooms
            $selectedRooms = json_decode($validated['selected_rooms'], true);
            if (empty($selectedRooms)) {
                \Log::error('No rooms selected');
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please select at least one room.'
                    ], 422);
                }
                return back()->withErrors([
                    'selected_rooms' => 'Please select at least one room.'
                ])->withInput();
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
                    throw new \Exception("Room {$room->name} is not available for the selected dates.");
                }

                $pricePerNight = $room->price_per_night;
                $roomSubtotal = $pricePerNight * $nights;
                $subtotal += $roomSubtotal;
            }

            // Calculate final total (no tax, no deposit)
            $finalTotal = $subtotal;
            $tax = 0;
            $deposit = 0;

            \Log::info('Price calculation', [
                'subtotal' => $subtotal,
                'tax' => $tax,
                'deposit' => $deposit,
                'finalTotal' => $finalTotal,
                'nights' => $nights,
                'payment_method' => $validated['payment_method']
            ]);

            if ($validated['payment_method'] === 'midtrans') {
                try {
                    DB::beginTransaction();

                    // Generate order ID
                    $orderId = sprintf('ORDER-%d-%d', time(), auth()->id());

                    // Create booking first
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

                    // Attach rooms
                    foreach ($selectedRooms as $roomData) {
                        $room = Room::find($roomData['id']);
                        $booking->rooms()->attach($room->id, [
                            'price_per_night' => $room->price_per_night,
                            'subtotal' => $room->price_per_night * $nights,
                            'quantity' => 1
                        ]);
                    }

                    // Create transaction record
                    $transaction = Transaction::create([
                        'booking_id' => $booking->id,
                        'order_id' => $orderId,
                        'gross_amount' => $finalTotal,
                        'payment_type' => 'pending',
                        'transaction_status' => 'pending',
                        'payment_status' => 'pending',
                        'raw_response' => json_encode([
                            'transaction_details' => [
                                'order_id' => $orderId,
                                'gross_amount' => $finalTotal
                            ],
                            'customer_details' => [
                                'first_name' => $validated['full_name'],
                                'email' => $validated['email'],
                                'phone' => $validated['phone']
                            ],
                            'item_details' => [
                                [
                                    'id' => 'room_charge',
                                    'price' => $finalTotal,
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
                        ])
                    ]);

                    \Log::info('Booking and transaction created successfully', [
                        'booking_id' => $booking->id,
                        'transaction_id' => $transaction->id,
                        'order_id' => $orderId
                    ]);

                    // Set up Midtrans configuration
                    \Midtrans\Config::$serverKey = config('midtrans.server_key');
                    \Midtrans\Config::$isProduction = config('midtrans.is_production');

                    \Log::info('Setting up Midtrans with config', [
                        'server_key' => config('midtrans.server_key'),
                        'is_production' => config('midtrans.is_production'),
                        'order_id' => $orderId,
                        'amount' => $finalTotal
                    ]);

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

                    \Log::info('Midtrans parameters prepared', $params);

                    // Get Snap Token
                    $snapToken = \Midtrans\Snap::getSnapToken($params);

                    \Log::info('Midtrans snap token generated', [
                        'order_id' => $orderId,
                        'snap_token' => $snapToken
                    ]);

                    DB::commit();

                    if ($request->ajax()) {
                        return response()->json([
                            'success' => true,
                            'snap_token' => $snapToken
                        ]);
                    }

                    return redirect()->route('bookings.show', $booking)
                        ->with('snap_token', $snapToken);

                } catch (\Exception $e) {
                    DB::rollback();
                    \Log::error('Error in booking process: ' . $e->getMessage(), [
                        'exception' => $e,
                        'params' => $params ?? null
                    ]);

                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to process payment. Please try again.'
                        ], 500);
                    }

                    return back()->withErrors([
                        'payment' => 'Failed to process payment. Please try again.'
                    ])->withInput();
                }
            } else {
                // Handle direct payment
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'booking_id' => $booking->id
                    ]);
                }
            }

            return redirect()->route('bookings.show', $booking->id)
                ->with('success', 'Booking created successfully!');

        } catch (\Exception $e) {
            \Log::error('Error in booking process: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
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
}