<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set your Midtrans server key and environment
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        Config::$paymentIdempotencyKey = true;
        Config::$overrideNotifUrl = route('midtrans.webhook');

        // Validate Midtrans configuration
        if (empty(config('midtrans.server_key'))) {
            Log::error('Midtrans server key is not configured');
        }
        if (empty(config('midtrans.client_key'))) {
            Log::error('Midtrans client key is not configured');
        }
    }

    public function showPaymentPage(Request $request)
    {
        try {
            $bookingId = $request->query('booking_id');
            if (!$bookingId) {
                throw new \Exception('Booking ID is required');
            }

            $booking = Booking::with('rooms')->findOrFail($bookingId);
            
            // Ensure the booking belongs to the current user
            if ($booking->user_id !== auth()->id()) {
                throw new \Exception('Unauthorized access to booking');
            }

            return view('bookings.payment', compact('booking'));
        } catch (\Exception $e) {
            return redirect()->route('bookings.riwayat')->with('error', $e->getMessage());
        }
    }

    public function show(Booking $booking)
    {
        try {
            // Ensure the booking belongs to the current user
            if ($booking->user_id !== auth()->id()) {
                throw new \Exception('Unauthorized access to booking');
            }

            return response()->json([
                'success' => true,
                'booking' => $booking->load('rooms'),
                'payment_details' => [
                    'subtotal' => $booking->total_price - $booking->tax - $booking->deposit,
                    'tax' => $booking->tax,
                    'deposit' => $booking->deposit,
                    'total' => $booking->total_price
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }

    public function process(Request $request)
    {
        DB::beginTransaction();
        
        try {
            Log::info('Payment process started', $request->all());
            
            // Validate request
            $validated = $request->validate([
                'booking_id' => 'required|exists:bookings,id',
                'payment_method' => 'required|in:bank_transfer,gopay'
            ]);

            // Get the booking
            $booking = Booking::with('rooms')->findOrFail($validated['booking_id']);
            
            // Ensure booking belongs to current user
            if ($booking->user_id !== auth()->id()) {
                throw new \Exception('Unauthorized access to booking');
            }

            // Check if booking is already paid
            if ($booking->payment_status === 'paid') {
                throw new \Exception('This booking has already been paid');
            }

            // Generate unique order ID
            $orderId = 'ORDER-' . time() . '-' . $booking->id;

            // Prepare item details
            $itemDetails = [];
            foreach ($booking->rooms as $room) {
                $itemDetails[] = [
                    'id' => 'ROOM-' . $room->id,
                    'price' => (int) $room->pivot->price_per_night,
                    'quantity' => (int) $room->pivot->quantity,
                    'name' => $room->name,
                ];
            }

            // Add tax and deposit if applicable
            if ($booking->tax > 0) {
                $itemDetails[] = [
                    'id' => 'TAX-' . $booking->id,
                    'price' => (int) $booking->tax,
                    'quantity' => 1,
                    'name' => 'Tax'
                ];
            }

            if ($booking->deposit > 0) {
                $itemDetails[] = [
                    'id' => 'DEPOSIT-' . $booking->id,
                    'price' => (int) $booking->deposit,
                    'quantity' => 1,
                    'name' => 'Security Deposit'
                ];
            }

            // Set enabled payments based on selected method
            $enabledPayments = [];
            if ($validated['payment_method'] === 'bank_transfer') {
                $enabledPayments = ['bca_va', 'bni_va', 'bri_va', 'mandiri_va'];
            } else {
                $enabledPayments = [$validated['payment_method']];
            }

            // Create Midtrans transaction parameters
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $booking->total_price,
                ],
                'customer_details' => [
                    'first_name' => $booking->full_name,
                    'email' => $booking->email,
                    'phone' => $booking->phone,
                ],
                'item_details' => $itemDetails,
                'enabled_payments' => $enabledPayments,
                'expiry' => [
                    'start_time' => $startTime->format('Y-m-d H:i:s O'),
                    'unit' => 'minutes',
                    'duration' => $startTime->diffInMinutes($paymentDeadline)
                ],
                'custom_field1' => json_encode(['payment_deadline' => $paymentDeadline->format('Y-m-d H:i:s O')])
            ];

            Log::info('Midtrans parameters:', $params);

            try {
                // Create Snap token
                $snapToken = Snap::getSnapToken($params);
                
                // Set payment deadline
                $startTime = Carbon::now('Asia/Jakarta');
                $paymentDeadline = $startTime->copy()->addHour();

                // Create transaction record
                $transaction = Transaction::create([
                    'booking_id' => $booking->id,
                    'order_id' => $orderId,
                    'gross_amount' => $booking->total_price,
                    'payment_type' => $validated['payment_method'],
                    'transaction_status' => 'pending',
                    'snap_token' => $snapToken,
                    'payment_deadline' => $paymentDeadline
                ]);

                // Update booking status
                $booking->update([
                    'status' => 'awaiting_payment',
                    'payment_status' => 'pending'
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken
                ]);

            } catch (\Exception $e) {
                Log::error('Midtrans Error: ' . $e->getMessage());
                throw new \Exception('Failed to create payment transaction. Please try again.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function webhook(Request $request)
    {
        try {
            $notification = new Notification();
            
            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;

            Log::info('Midtrans Notification', [
                'order_id' => $orderId,
                'status' => $transactionStatus,
                'fraud' => $fraudStatus
            ]);

            $transaction = Transaction::where('order_id', $orderId)->firstOrFail();
            $booking = $transaction->booking;

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $transaction->transaction_status = 'challenge';
                } else if ($fraudStatus == 'accept') {
                    $this->completePayment($transaction, $booking);
                }
            } else if ($transactionStatus == 'settlement') {
                $this->completePayment($transaction, $booking);
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $transaction->transaction_status = $transactionStatus;
                $booking->payment_status = 'cancelled';
                $booking->status = 'cancelled';
            } else if ($transactionStatus == 'pending') {
                $transaction->transaction_status = $transactionStatus;
            }

            $transaction->save();
            $booking->save();

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    private function completePayment($transaction, $booking)
    {
        DB::beginTransaction();
        try {
            $transaction->transaction_status = 'settlement';
            $transaction->payment_status = 'paid';
            $transaction->save();
            
            $booking->payment_status = 'paid';
            $booking->status = 'confirmed';
            $booking->save();
            
            DB::commit();
            
            // Emit payment status updated event
            event(new \App\Events\PaymentStatusUpdated([
                'transaction_id' => $transaction->id,
                'order_id' => $transaction->order_id,
                'transaction_status' => $transaction->transaction_status,
                'payment_status' => $transaction->payment_status,
                'booking_id' => $booking->id
            ]));
            
            Log::info('Payment completed successfully', [
                'transaction_id' => $transaction->id,
                'booking_id' => $booking->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error completing payment', [
                'error' => $e->getMessage(),
                'transaction_id' => $transaction->id,
                'booking_id' => $booking->id
            ]);
            throw $e;
        }
    }

    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ]);
        }

        try {
            $status = $this->getMidtransStatus($orderId);

            if ($status === 'settlement' || $status === 'capture') {
                $transaction->update([
                    'status' => 'paid',
                    'payment_status' => 'success'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment completed successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment is still pending or failed'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking payment status: ' . $e->getMessage()
            ]);
        }
    }

    public function unfinish(Request $request)
    {
        return redirect()->route('landing')
            ->with('error', 'Payment is incomplete. Please complete your payment.');
    }

    public function error(Request $request)
    {
        return redirect()->route('landing')
            ->with('error', 'Payment failed. Please try again or contact support.');
    }

    public function cancel(Request $request)
    {
        return redirect()->route('landing')
            ->with('error', 'Payment has been cancelled.');
    }
} 