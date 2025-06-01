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
        try {
            $orderId = $request->query('order_id');
            Log::info('Payment finish callback received', ['order_id' => $orderId]);

            $transaction = Transaction::where('order_id', $orderId)->firstOrFail();
            $booking = $transaction->booking;

            if (!$booking) {
                throw new \Exception('Booking not found');
            }

            // Get transaction status from Midtrans
            $midtransStatus = \Midtrans\Transaction::status($orderId);
            
            Log::info('Midtrans status retrieved', [
                'order_id' => $orderId,
                'status' => $midtransStatus
            ]);

            // Update transaction status based on Midtrans response
            switch ($midtransStatus->transaction_status) {
                case 'capture':
                    if ($midtransStatus->fraud_status == 'challenge') {
                        $transaction->payment_status = 'pending';
                        $transaction->transaction_status = 'challenge';
                        $booking->payment_status = 'pending';
                        $booking->status = 'pending';
                    } else if ($midtransStatus->fraud_status == 'accept') {
                        $transaction->payment_status = 'paid';
                        $transaction->transaction_status = 'success';
                        $booking->payment_status = 'paid';
                        $booking->status = 'confirmed';
                    }
                    break;
                case 'settlement':
                    $transaction->payment_status = 'paid';
                    $transaction->transaction_status = 'settlement';
                    $booking->payment_status = 'paid';
                    $booking->status = 'confirmed';
                    break;
                case 'pending':
                    $transaction->payment_status = 'pending';
                    $transaction->transaction_status = 'pending';
                    $booking->payment_status = 'pending';
                    $booking->status = 'pending';
                    break;
                case 'deny':
                case 'cancel':
                case 'expire':
                    $transaction->payment_status = 'cancelled';
                    $transaction->transaction_status = $midtransStatus->transaction_status;
                    $booking->payment_status = 'cancelled';
                    $booking->status = 'cancelled';
                    break;
            }

            // Save the changes
            $transaction->save();
            $booking->save();

            if ($transaction->payment_status === 'paid') {
                return redirect()->route('landing', ['panel' => 'transactions'])
                    ->with('success', 'Pembayaran berhasil! Pesanan Anda telah dikonfirmasi.');
            } else if ($transaction->payment_status === 'pending') {
                return redirect()->route('landing', ['panel' => 'transactions'])
                    ->with('info', 'Pembayaran sedang diproses. Silakan selesaikan pembayaran sesuai instruksi.');
            } else {
                return redirect()->route('landing', ['panel' => 'transactions'])
                    ->with('error', 'Pembayaran dibatalkan atau ditolak.');
            }

        } catch (\Exception $e) {
            Log::error('Error in payment finish: ' . $e->getMessage());
            return redirect()->route('landing', ['panel' => 'transactions'])
                ->with('error', 'Terjadi kesalahan dalam memproses pembayaran. Silakan hubungi admin.');
        }
    }

    public function error(Request $request)
    {
        Log::error('Payment error callback received', $request->all());

        try {
            $orderId = $request->order_id;
            
            // Find transaction
            $transaction = Transaction::where('order_id', $orderId)->first();
            if ($transaction) {
                DB::beginTransaction();
                try {
                    // Update transaction status
                    $transaction->payment_status = 'failed';
                    $transaction->transaction_status = 'error';
                    $transaction->save();

                    // Update booking status if exists
                    $booking = Booking::find($transaction->booking_id);
                    if ($booking) {
                        $booking->payment_status = 'failed';
                        $booking->status = 'cancelled';
                        $booking->save();

                        DB::commit();
                        return redirect()->route('landing', ['panel' => 'transactions'])
                            ->with('error', 'Pembayaran gagal. Silakan coba lagi.');
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Failed to update error status: ' . $e->getMessage());
                    throw $e;
                }
            }

            return redirect()->route('landing', ['panel' => 'transactions'])
                ->with('error', 'Pembayaran gagal. Silakan coba lagi.');

        } catch (\Exception $e) {
            Log::error('Error in payment error handler: ' . $e->getMessage());
            return redirect()->route('landing', ['panel' => 'transactions'])
                ->with('error', 'Terjadi kesalahan dalam memproses pembayaran. Silakan coba lagi.');
        }
    }

    public function cancel(Request $request)
    {
        Log::info('Payment cancel callback received', $request->all());

        try {
            $orderId = $request->order_id;
            
            // Find transaction
            $transaction = Transaction::where('order_id', $orderId)->first();
            if ($transaction) {
                DB::beginTransaction();
                try {
                    // Update transaction status
                    $transaction->payment_status = 'cancelled';
                    $transaction->transaction_status = 'cancel';
                    $transaction->save();

                    // Update booking status if exists
                    $booking = Booking::find($transaction->booking_id);
                    if ($booking) {
                        $booking->payment_status = 'cancelled';
                        $booking->status = 'cancelled';
                        $booking->save();

                        DB::commit();
                        return redirect()->route('landing', ['panel' => 'transactions'])
                            ->with('warning', 'Pembayaran dibatalkan.');
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Failed to update cancel status: ' . $e->getMessage());
                    throw $e;
                }
            }

            return redirect()->route('landing', ['panel' => 'transactions'])
                ->with('warning', 'Pembayaran dibatalkan.');

        } catch (\Exception $e) {
            Log::error('Error in payment cancel handler: ' . $e->getMessage());
            return redirect()->route('landing', ['panel' => 'transactions'])
                ->with('error', 'Terjadi kesalahan dalam membatalkan pembayaran.');
        }
    }
} 