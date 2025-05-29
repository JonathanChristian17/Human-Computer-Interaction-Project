<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        $transactions = Transaction::with('booking')
            ->whereHas('booking', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        if (request()->wantsJson()) {
            return response()->json([
                'html' => view('transactions._list', compact('transactions'))->render()
            ]);
        }

        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        try {
            // Load the booking relationship with rooms and their pivot data
            $transaction->load(['booking' => function($query) {
                $query->with(['rooms' => function($query) {
                    $query->withPivot(['price_per_night', 'quantity', 'subtotal']);
                }]);
            }]);

            // Check authorization
            if (!$transaction->booking || $transaction->booking->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            // Calculate duration in nights
            $checkIn = new \DateTime($transaction->booking->check_in_date);
            $checkOut = new \DateTime($transaction->booking->check_out_date);
            $duration = $checkIn->diff($checkOut)->days;

            // Transform the data for better frontend display
            $data = [
                'id' => $transaction->id,
                'order_id' => $transaction->order_id,
                'transaction_id' => $transaction->transaction_id,
                'created_at' => $transaction->created_at,
                'transaction_status' => $transaction->transaction_status,
                'payment_status' => $transaction->payment_status,
                'payment_type' => $transaction->payment_type,
                'payment_code' => $transaction->payment_code,
                'gross_amount' => $transaction->gross_amount,
                'booking' => $transaction->booking ? [
                    'id' => $transaction->booking->id,
                    'check_in_date' => $transaction->booking->check_in_date,
                    'check_out_date' => $transaction->booking->check_out_date,
                    'duration' => $duration,
                    'guest_name' => $transaction->booking->full_name,
                    'email' => $transaction->booking->email,
                    'phone' => $transaction->booking->phone,
                    'rooms' => $transaction->booking->rooms->map(function($room) {
                        return [
                            'id' => $room->id,
                            'room_number' => $room->room_number,
                            'type' => $room->type,
                            'pivot' => [
                                'price_per_night' => $room->pivot->price_per_night,
                                'quantity' => $room->pivot->quantity,
                                'subtotal' => $room->pivot->subtotal
                            ]
                        ];
                    })
                ] : null
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Error in transaction details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load transaction details'
            ], 500);
        }
    }

    public function cancel(Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            // Check authorization
            if ($transaction->booking->user_id !== auth()->id()) {
                throw new \Exception('Unauthorized access');
            }

            // Check if transaction can be cancelled
            if (!in_array($transaction->transaction_status, ['pending']) || 
                !in_array($transaction->payment_status, ['pending', 'failed'])) {
                throw new \Exception('Transaction cannot be cancelled');
            }

            // Update transaction status
            $transaction->update([
                'transaction_status' => 'cancel',
                'payment_status' => 'cancelled'
            ]);

            // Update booking status
            $booking = $transaction->booking;
            $booking->update([
                'status' => 'cancelled',
                'payment_status' => 'cancelled'
            ]);

            // Release the room reservation if any
            if ($booking->rooms) {
                foreach ($booking->rooms as $room) {
                    $room->update(['status' => 'available']);
                }
            }

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaction cancelled successfully'
                ]);
            }

            return back()->with('success', 'Transaction cancelled successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Cancel Transaction Error: ' . $e->getMessage());

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function pay(Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            // Check if transaction can be paid
            if (!in_array($transaction->transaction_status, ['pending', 'cancel', 'error']) || 
                !in_array($transaction->payment_status, ['pending', 'cancelled', 'failed'])) {
                throw new \Exception('This transaction cannot be paid');
            }

            // Generate new order ID for retry
            $newOrderId = $transaction->order_id;
            if (strpos($transaction->order_id, '-retry-') !== false) {
                // If it's already a retry, get the base order ID
                $newOrderId = explode('-retry-', $transaction->order_id)[0];
            }
            $newOrderId = $newOrderId . '-retry-' . time();

            // Create Midtrans transaction parameters
            $params = [
                'transaction_details' => [
                    'order_id' => $newOrderId,
                    'gross_amount' => (int) $transaction->gross_amount,
                ],
                'customer_details' => [
                    'first_name' => $transaction->booking->full_name,
                    'email' => $transaction->booking->email,
                    'phone' => $transaction->booking->phone,
                ],
                'item_details' => [
                    [
                        'id' => 'ROOM-' . $transaction->booking->id,
                        'price' => (int) ($transaction->gross_amount),
                        'quantity' => 1,
                        'name' => 'Room Booking',
                    ]
                ],
                'enabled_payments' => [
                    'bca_va', 'bni_va', 'bri_va', 'mandiri_va', 'permata_va', 'other_va',
                    'gopay', 'shopeepay', 'qris', 'bank_transfer', 'indomaret', 'credit_card'
                ],
                'callbacks' => [
                    'finish' => route('payment.finish'),
                    'error' => route('payment.error'),
                    'cancel' => route('payment.cancel'),
                    'notification' => route('midtrans.webhook')
                ]
            ];

            \Log::info('Creating Midtrans transaction', [
                'transaction_id' => $transaction->id,
                'params' => $params
            ]);

            // Get Snap token
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            \Log::info('Midtrans snap token generated', [
                'transaction_id' => $transaction->id,
                'snap_token' => $snapToken
            ]);

            // Update transaction with new order ID and reset status
            $transaction->update([
                'order_id' => $newOrderId,
                'transaction_status' => 'pending',
                'payment_status' => 'pending',
                'snap_token' => $snapToken,
                'payment_type' => null,
                'payment_code' => null,
                'transaction_id' => null,
                'transaction_time' => null,
                'raw_response' => null
            ]);

            // Update booking status
            $transaction->booking->update([
                'status' => 'pending',
                'payment_status' => 'pending'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Midtrans Error', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function finish(Request $request)
    {
        try {
            $orderId = $request->query('order_id');
            \Log::info('Payment finish callback received', ['order_id' => $orderId]);

            // Find transaction by order ID
            $transaction = Transaction::where('order_id', $orderId)->firstOrFail();
            $booking = $transaction->booking;

            if (!$booking) {
                throw new \Exception('Booking not found');
            }

            // Get transaction status from Midtrans
            $midtransStatus = \Midtrans\Transaction::status($orderId);
            
            \Log::info('Midtrans status retrieved', [
                'order_id' => $orderId,
                'status' => $midtransStatus
            ]);

            DB::beginTransaction();
            try {
                // Update transaction with payment details
                $transaction->transaction_id = $midtransStatus->transaction_id;
                $transaction->payment_type = $this->formatPaymentType($midtransStatus->payment_type, $midtransStatus);
                $transaction->payment_code = $this->getPaymentCode($midtransStatus);
                $transaction->transaction_status = $midtransStatus->transaction_status;
                $transaction->transaction_time = $midtransStatus->transaction_time;
                $transaction->raw_response = json_encode($midtransStatus);

                // Update transaction and booking status based on Midtrans status
                if ($midtransStatus->transaction_status === 'settlement' || $midtransStatus->transaction_status === 'capture') {
                    $transaction->payment_status = 'paid';
                    $transaction->transaction_status = 'settlement';
                    $booking->payment_status = 'paid';
                    $booking->status = 'confirmed';
                } else if ($midtransStatus->transaction_status === 'pending') {
                    $transaction->payment_status = 'pending';
                    $transaction->transaction_status = 'pending';
                    $booking->payment_status = 'pending';
                    $booking->status = 'pending';
                } else if (in_array($midtransStatus->transaction_status, ['cancel', 'deny', 'expire'])) {
                    $transaction->payment_status = 'cancelled';
                    $transaction->transaction_status = $midtransStatus->transaction_status;
                    $booking->payment_status = 'cancelled';
                    $booking->status = 'cancelled';
                }

                $transaction->save();
                $booking->save();

                DB::commit();
                \Log::info('Payment status updated successfully', [
                    'order_id' => $orderId,
                    'transaction_id' => $transaction->id,
                    'booking_id' => $booking->id,
                    'status' => $midtransStatus->transaction_status
                ]);

                $response = [
                    'success' => true,
                    'status' => $midtransStatus->transaction_status,
                    'message' => $midtransStatus->transaction_status === 'pending' 
                        ? 'Please complete your payment using the provided payment instructions.'
                        : 'Payment successful! Your booking has been confirmed.',
                    'transaction' => [
                        'id' => $transaction->id,
                        'order_id' => $transaction->order_id,
                        'status' => $transaction->transaction_status,
                        'payment_status' => $transaction->payment_status,
                        'payment_type' => $transaction->payment_type,
                        'payment_code' => $transaction->payment_code
                    ]
                ];

                // Always return JSON for AJAX requests
                if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                    return response()->json($response);
                }

                // Fallback to redirect for non-AJAX requests
                return redirect()->route('landing', ['panel' => 'transactions'])
                    ->with($midtransStatus->transaction_status === 'pending' ? 'info' : 'success', $response['message']);

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Failed to update payment status: ' . $e->getMessage());
                throw $e;
            }

        } catch (\Exception $e) {
            \Log::error('Error in payment finish: ' . $e->getMessage());
            
            $errorResponse = [
                'success' => false,
                'message' => 'An error occurred while processing your payment. Please contact support.',
                'error' => $e->getMessage()
            ];
            
            if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json($errorResponse, 500);
            }

            return redirect()->route('landing', ['panel' => 'transactions'])
                ->with('error', $errorResponse['message']);
        }
    }

    public function finishAjax(Request $request)
    {
        try {
            $orderId = $request->query('order_id');
            \Log::info('AJAX Payment finish callback received', ['order_id' => $orderId]);

            // Find transaction by order ID
            $transaction = Transaction::where('order_id', $orderId)->firstOrFail();
            $booking = $transaction->booking;

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            // Get transaction status from Midtrans
            $midtransStatus = \Midtrans\Transaction::status($orderId);
            
            \Log::info('Midtrans status retrieved', [
                'order_id' => $orderId,
                'status' => $midtransStatus
            ]);

            DB::beginTransaction();
            try {
                // Update transaction with payment details
                $transaction->transaction_id = $midtransStatus->transaction_id;
                $transaction->payment_type = $this->formatPaymentType($midtransStatus->payment_type, $midtransStatus);
                $transaction->payment_code = $this->getPaymentCode($midtransStatus);
                $transaction->transaction_status = $midtransStatus->transaction_status;
                $transaction->transaction_time = $midtransStatus->transaction_time;
                $transaction->raw_response = json_encode($midtransStatus);

                // Update transaction status based on Midtrans status
                if ($midtransStatus->transaction_status === 'settlement' || $midtransStatus->transaction_status === 'capture') {
                    $transaction->payment_status = 'paid';
                    $transaction->transaction_status = 'settlement';
                    $booking->payment_status = 'paid';
                    $booking->status = 'confirmed';
                } else if ($midtransStatus->transaction_status === 'pending') {
                    $transaction->payment_status = 'pending';
                    $transaction->transaction_status = 'pending';
                    $booking->payment_status = 'pending';
                    $booking->status = 'pending';
                }

                $transaction->save();
                $booking->save();

                DB::commit();
                \Log::info('Payment status updated successfully', [
                    'order_id' => $orderId,
                    'transaction_id' => $transaction->id,
                    'booking_id' => $booking->id,
                    'status' => $midtransStatus->transaction_status
                ]);

                return response()->json([
                    'success' => true,
                    'status' => $midtransStatus->transaction_status,
                    'message' => $midtransStatus->transaction_status === 'pending' 
                        ? 'Please complete your payment using the provided payment instructions.'
                        : 'Payment successful! Your booking has been confirmed.',
                    'transaction' => [
                        'id' => $transaction->id,
                        'order_id' => $transaction->order_id,
                        'status' => $transaction->transaction_status,
                        'payment_status' => $transaction->payment_status,
                        'payment_type' => $transaction->payment_type,
                        'payment_code' => $transaction->payment_code
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Failed to update payment status: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update payment status',
                    'error' => $e->getMessage()
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Error in payment finish AJAX: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function formatPaymentType($paymentType, $midtransStatus)
    {
        if ($paymentType === 'bank_transfer') {
            if (!empty($midtransStatus->va_numbers)) {
                $bankName = strtoupper($midtransStatus->va_numbers[0]->bank);
                return $bankName . ' Virtual Account';
            } elseif (!empty($midtransStatus->permata_va_number)) {
                return 'PERMATA Virtual Account';
            }
            return 'Bank Transfer';
        } elseif ($paymentType === 'echannel') {
            return 'Mandiri Bill Payment';
        } elseif (in_array($paymentType, ['gopay', 'shopeepay'])) {
            return strtoupper($paymentType);
        } elseif ($paymentType === 'qris') {
            return 'QRIS';
        }
        return ucfirst($paymentType);
    }

    private function getPaymentCode($midtransStatus)
    {
        if (!empty($midtransStatus->va_numbers)) {
            return $midtransStatus->va_numbers[0]->va_number;
        } elseif (!empty($midtransStatus->permata_va_number)) {
            return $midtransStatus->permata_va_number;
        } elseif (!empty($midtransStatus->bill_key)) {
            return $midtransStatus->bill_key;
        }
        return null;
    }

    public function error(Request $request)
    {
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

                    // Update booking status
                    $booking = Booking::find($transaction->booking_id);
                    if ($booking) {
                        $booking->payment_status = 'failed';
                        $booking->status = 'cancelled';
                        $booking->save();
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error('Failed to update error status: ' . $e->getMessage());
                }
            }

            return redirect()->route('landing', ['panel' => 'transactions'])
                ->with('error', 'Payment failed. Please try again.');

        } catch (\Exception $e) {
            \Log::error('Error in payment error handler: ' . $e->getMessage());
            return redirect()->route('landing', ['panel' => 'transactions'])
                ->with('error', 'An error occurred while processing your payment. Please try again.');
        }
    }
} 