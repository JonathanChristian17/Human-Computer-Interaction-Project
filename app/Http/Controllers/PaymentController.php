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

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set your Midtrans server key and environment
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

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
                'enabled_payments' => $enabledPayments
            ];

            Log::info('Midtrans parameters:', $params);

            try {
                // Create Snap token
                $snapToken = Snap::getSnapToken($params);
                
                // Create transaction record
                $transaction = Transaction::create([
                    'booking_id' => $booking->id,
                    'order_id' => $orderId,
                    'gross_amount' => $booking->total_price,
                    'payment_type' => $validated['payment_method'],
                    'transaction_status' => 'pending',
                    'snap_token' => $snapToken
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
        Log::info('Payment finish callback received', $request->all());

        try {
            $orderId = $request->order_id;
            
            // Find transaction
            $transaction = Transaction::where('order_id', $orderId)->first();
            if (!$transaction) {
                Log::error('Transaction not found for order_id: ' . $orderId);
                return redirect()->route('landing', ['panel' => 'transactions'])
                    ->with('error', 'Transaksi tidak ditemukan. Silakan hubungi admin.');
            }

            // Find booking
            $booking = Booking::find($transaction->booking_id);
            if (!$booking) {
                Log::error('Booking not found for transaction_id: ' . $transaction->id);
                return redirect()->route('landing', ['panel' => 'transactions'])
                    ->with('error', 'Booking tidak ditemukan. Silakan hubungi admin.');
            }

            // Update status jika belum settlement
            if ($transaction->payment_status !== 'paid') {
                DB::beginTransaction();
                try {
                    // Update transaction status
                    $transaction->payment_status = 'paid';
                    $transaction->transaction_status = 'settlement';
                    $transaction->save();

                    // Update booking status
                    $booking->payment_status = 'paid';
                    $booking->status = 'confirmed';
                    $booking->save();

                    DB::commit();
                    Log::info('Payment status updated successfully', [
                        'order_id' => $orderId,
                        'transaction_id' => $transaction->id,
                        'booking_id' => $booking->id
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Failed to update payment status: ' . $e->getMessage());
                    throw $e;
                }
            }

            return redirect()->route('landing', ['panel' => 'transactions', 'source' => 'payment'])
                ->with('success', 'Pembayaran berhasil! Pesanan Anda telah dikonfirmasi.');

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