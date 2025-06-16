<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
            ->join('transactions', 'bookings.id', '=', 'transactions.booking_id')
            ->where(function($query) {
                $query->where(function($q) {
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
                });
            })
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
                $endDate
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
            'payment_method' => $request->input('payment_method'),
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
                'payment_method' => 'required|in:midtrans,direct,deposit',
                'payment_status' => 'nullable|string',
                'order_id' => 'nullable|string',
                'transaction_id' => 'nullable|string',
                'payment_type' => 'nullable|string',
                'payment_code' => 'nullable|string',
                'raw_payment_type' => 'nullable|string',
                'raw_bank' => 'nullable|string'
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

            // Parse dates in Asia/Jakarta timezone
            $jakartaTz = new \DateTimeZone('Asia/Jakarta');
            
            // Create DateTime objects in Jakarta timezone
            $checkIn = \DateTime::createFromFormat('Y-m-d', $validated['check_in_date'], $jakartaTz);
            $checkOut = \DateTime::createFromFormat('Y-m-d', $validated['check_out_date'], $jakartaTz);
            
            if (!$checkIn || !$checkOut) {
                throw new \Exception('Invalid date format');
            }

            // Format dates for database (keeping the same date regardless of timezone)
            $checkInDate = $checkIn->format('Y-m-d');
            $checkOutDate = $checkOut->format('Y-m-d');
            
            \Log::info('Date processing', [
                'original_check_in' => $validated['check_in_date'],
                'original_check_out' => $validated['check_out_date'],
                'formatted_check_in' => $checkInDate,
                'formatted_check_out' => $checkOutDate,
                'timezone' => $jakartaTz->getName()
            ]);

            // Calculate nights
            $nights = $checkIn->diff($checkOut)->days;

            // Calculate totals
            $subtotal = 0;
            foreach ($selectedRooms as $roomData) {
                $room = Room::find($roomData['id']);
                if (!$room) {
                    throw new \Exception("Room with ID {$roomData['id']} not found.");
                }

                \Log::info('Checking room availability for dates', [
                    'room_id' => $room->id,
                    'room_type' => $room->type,
                    'check_in' => $checkInDate,
                    'check_out' => $checkOutDate,
                    'exclude_booking_id' => null
                ]);

                // Check if room is available
                $isAvailable = $room->isAvailableForDates($checkInDate, $checkOutDate);
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

            if ($validated['payment_method'] === 'midtrans' || $validated['payment_method'] === 'deposit') {
                // If this is the initial request (no payment info yet)
                if (!isset($validated['payment_type']) && !isset($validated['payment_status'])) {
                    // Store booking data in session
                    $bookingData = [
                        'user_id' => auth()->id(),
                        'full_name' => $validated['full_name'],
                        'email' => $validated['email'],
                        'phone' => $validated['phone'],
                        'id_number' => $validated['id_number'],
                        'check_in_date' => $checkInDate,
                        'check_out_date' => $checkOutDate,
                        'total_price' => $finalTotal,
                        'special_requests' => $validated['special_requests'] ?? null,
                        'status' => 'pending',
                        'payment_status' => 'pending',
                        'deposit_amount' => $validated['payment_method'] === 'deposit' ? 100000 : 0,
                        'selected_rooms' => $selectedRooms
                    ];

                    session(['temp_booking' => $bookingData]);

                    // Set up Midtrans configuration
                    \Midtrans\Config::$serverKey = config('midtrans.server_key');
                    \Midtrans\Config::$isProduction = config('midtrans.is_production');
                    \Midtrans\Config::$paymentIdempotencyKey = true;
                    \Midtrans\Config::$overrideNotifUrl = route('midtrans.webhook');

                    $orderId = sprintf('ORDER-%d-%d', time(), auth()->id());
                    $startTime = now('Asia/Jakarta');
                    $paymentDeadline = $startTime->copy()->addHour();
                    
                    // Set payment amount based on payment method
                    $paymentAmount = $validated['payment_method'] === 'deposit' ? 100000 : $finalTotal;
                    $itemName = $validated['payment_method'] === 'deposit' ? 'Room Deposit' : 'Room Charge for ' . $nights . ' night(s)';
                    $itemId = $validated['payment_method'] === 'deposit' ? 'deposit_payment' : 'room_charge';

                    $params = [
                        'transaction_details' => [
                            'order_id' => $orderId,
                            'gross_amount' => (int) $paymentAmount
                        ],
                        'customer_details' => [
                            'first_name' => $validated['full_name'],
                            'email' => $validated['email'],
                            'phone' => $validated['phone']
                        ],
                        'item_details' => [
                            [
                                'id' => $itemId,
                                'price' => (int) $paymentAmount,
                                'quantity' => 1,
                                'name' => $itemName
                            ]
                        ],
                        'enabled_payments' => [
                            'bca_va', 'bni_va', 'bri_va', 'mandiri_va', 'permata_va', 'other_va',
                            'gopay', 'shopeepay', 'indomaret',
                            'bca_klikbca', 'bca_klikpay', 'cimb_clicks', 'danamon_online', 'bri_epay'
                        ],
                        'expiry' => [
                            'start_time' => $startTime->format('Y-m-d H:i:s O'),
                            'unit' => 'minutes',
                            'duration' => $startTime->diffInMinutes($paymentDeadline)
                        ],
                        'custom_field1' => json_encode([
                            'payment_deadline' => $paymentDeadline->format('Y-m-d H:i:s O'),
                            'is_deposit' => $validated['payment_method'] === 'deposit'
                        ]),
                        'callbacks' => [
                            'finish' => route('payment.finish'),
                            'error' => route('payment.error'),
                            'cancel' => route('payment.cancel')
                        ]
                    ];

                    \Log::info('Sending parameters to Midtrans:', $params);
                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                    \Log::info('Received snap token:', ['token' => $snapToken]);

                    return response()->json([
                        'success' => true,
                        'snap_token' => $snapToken,
                        'booking_data' => [
                            'order_id' => $orderId,
                            'payment_deadline' => $paymentDeadline->format('Y-m-d H:i:s')
                        ]
                    ]);
                } 
                // If payment method has been selected (callback with payment info)
                else {
                    DB::beginTransaction();
                    try {
                        // Get booking data from session
                        $bookingData = session('temp_booking');
                        if (!$bookingData) {
                            throw new \Exception('Booking data not found in session');
                        }

                        // Create booking
                        $booking = Booking::create([
                            'user_id' => $bookingData['user_id'],
                            'full_name' => $bookingData['full_name'],
                            'email' => $bookingData['email'],
                            'phone' => $bookingData['phone'],
                            'id_number' => $bookingData['id_number'],
                            'check_in_date' => $bookingData['check_in_date'],
                            'check_out_date' => $bookingData['check_out_date'],
                            'total_price' => $bookingData['total_price'],
                            'special_requests' => $bookingData['special_requests'],
                            'status' => 'pending',
                            'payment_status' => $validated['payment_status'] ?? 'pending',
                            'deposit_amount' => $bookingData['deposit_amount']
                        ]);

                        // Attach rooms
                        foreach ($bookingData['selected_rooms'] as $roomData) {
                            $room = Room::find($roomData['id']);
                            $roomSubtotal = $room->price_per_night * $nights;
                            
                            $booking->rooms()->attach($room->id, [
                                'price_per_night' => $room->price_per_night,
                                'quantity' => 1,
                                'subtotal' => $roomSubtotal
                            ]);
                        }

                        // Set payment code based on payment type
                        $paymentCode = null;
                        if ($validated['raw_payment_type'] === 'bank_transfer' && !empty($validated['raw_bank'])) {
                            $paymentCode = $validated['payment_code'] ?? null;
                        } elseif ($validated['raw_payment_type'] === 'qris') {
                            $paymentCode = $validated['transaction_id'] ?? null;
                        } elseif ($validated['raw_payment_type'] === 'gopay' || $validated['raw_payment_type'] === 'shopeepay') {
                            $paymentCode = $validated['transaction_id'] ?? null;
                        } else {
                            $paymentCode = $validated['payment_code'] ?? $validated['transaction_id'] ?? null;
                        }

                        // Create transaction
                        $transaction = Transaction::create([
                            'booking_id' => $booking->id,
                            'order_id' => $validated['order_id'],
                            'gross_amount' => $validated['payment_method'] === 'deposit' ? 100000 : $bookingData['total_price'],
                            'payment_status' => $validated['payment_status'] ?? 'pending',
                            'transaction_status' => $validated['payment_status'] === 'paid' ? 'settlement' : 'pending',
                            'is_deposit' => $validated['payment_method'] === 'deposit',
                            'payment_deadline' => now()->addHour(),
                            'transaction_id' => $validated['transaction_id'],
                            'payment_type' => $validated['payment_type'],
                            'payment_code' => $paymentCode,
                            'raw_payment_type' => $validated['raw_payment_type'],
                            'raw_bank' => $validated['raw_bank']
                        ]);

                        // If payment is successful
                        if ($validated['payment_status'] === 'paid') {
                            if ($validated['payment_method'] === 'deposit') {
                                $booking->payment_status = 'deposit';
                                $transaction->payment_status = 'deposit';
                            } else {
                                $booking->payment_status = 'paid';
                                $transaction->payment_status = 'paid';
                            }
                            $booking->status = 'confirmed';
                            $booking->save();
                            $transaction->save();
                        }

                        // Clear session data
                        session()->forget('temp_booking');

                        DB::commit();

                        return response()->json([
                            'success' => true,
                            'message' => 'Booking created successfully',
                            'booking_id' => $booking->id
                        ]);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        throw $e;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in booking process: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
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
            ->join('transactions', 'bookings.id', '=', 'transactions.booking_id')
            ->where('booking_room.room_id', $roomId)
            ->where(function($query) {
                $query->where(function($q) {
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
                });
            })
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

        return response()->json([
            'unavailable_dates' => array_values(array_unique($dates))
        ]);
    }

    public function getTransactionStatus($orderId)
    {
        try {
            $transaction = Transaction::where('order_id', $orderId)->firstOrFail();
            return response()->json([
                'success' => true,
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting transaction status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }
    }

    public function finishAjax(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            $transaction = Transaction::where('order_id', $orderId)->firstOrFail();
            
            return response()->json([
                'success' => true,
                'data' => $transaction
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in finish-ajax: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }
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
                    
                    // Check if this is a deposit payment
                    $customField1 = json_decode($transaction->raw_response ?? '{}', true);
                    $isDeposit = $customField1['is_deposit'] ?? false;
                    
                    if ($isDeposit) {
                        $transaction->payment_status = 'deposit';
                        $transaction->booking->payment_status = 'deposit';
                        $transaction->booking->status = 'confirmed';
                        $transaction->booking->save();
                        $transaction->save();
                    }
                    
                    // Show success message and redirect
                    $message = $isDeposit ? 'Deposit payment successful! Please pay the remaining amount at check-in.' : 'Payment processed successfully!';
                    
                    return redirect()->to('/?panel=transactions&source=midtrans')
                        ->with('success', $message);
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

    public function createWithRoom(Room $room)
    {
        // Check if user is verified
        if (!auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Please verify your email address before booking a room.')
                ->with('showAlert', true);
        }

        $selectedRooms = [$room];
        $allBookedDates = [];
        $unionBookedDates = [];

        // Get booked dates for this room
        $bookings = DB::table('booking_room')
            ->join('bookings', 'booking_room.booking_id', '=', 'bookings.id')
            ->join('transactions', 'bookings.id', '=', 'transactions.booking_id')
            ->where('booking_room.room_id', $room->id)
            ->where(function($query) {
                $query->where(function($q) {
                    $q->whereNotIn('bookings.status', ['cancelled'])
                      ->whereNotIn('bookings.payment_status', ['cancelled'])
                      ->where(function($q) {
                          $q->where('transactions.payment_status', '!=', 'expire')
                            ->orWhere(function($q) {
                                $q->where('transactions.payment_status', 'pending')
                                  ->where('transactions.created_at', '>=', now()->subHour());
                            });
                      });
                });
            })
            ->where(function($query) {
                $query->where('bookings.check_out_date', '>=', now()->format('Y-m-d'))
                    ->orWhere('bookings.check_in_date', '>=', now()->format('Y-m-d'));
            })
            ->select(
                'bookings.check_in_date',
                'bookings.check_out_date'
            )
            ->get();

        foreach ($bookings as $booking) {
            $startDate = new \DateTime($booking->check_in_date);
            $endDate = new \DateTime($booking->check_out_date);
            $period = new \DatePeriod(
                $startDate,
                new \DateInterval('P1D'),
                $endDate
            );

            foreach ($period as $date) {
                $dateStr = $date->format('Y-m-d');
                if (!isset($allBookedDates[$room->id])) {
                    $allBookedDates[$room->id] = [];
                }
                $allBookedDates[$room->id][$dateStr] = true;
                
                if (!isset($unionBookedDates[$dateStr])) {
                    $unionBookedDates[$dateStr] = [];
                }
                $unionBookedDates[$dateStr][] = $room->id;
            }
        }

        // Calculate initial totals
        $nights = 1;
        $subtotal = $room->price_per_night * $nights;
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

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_ids' => 'required|array',
            'room_ids.*' => 'exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date'
        ]);

        $roomIds = $request->room_ids;
        $checkIn = $request->check_in_date;
        $checkOut = $request->check_out_date;

        $unavailableRooms = [];

        foreach ($roomIds as $roomId) {
            $room = Room::find($roomId);
            if (!$room->isAvailableForDates($checkIn, $checkOut)) {
                $unavailableRooms[] = $room->name;
            }
        }

        if (!empty($unavailableRooms)) {
            return response()->json([
                'available' => false,
                'message' => 'The following rooms are not available for the selected dates: ' . implode(', ', $unavailableRooms)
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'All selected rooms are available for the selected dates.'
        ]);
    }
}