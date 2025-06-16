<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\DB;
use App\Events\BookingStatusChanged;
use PDF;

class TransactionController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
        Config::$paymentIdempotencyKey = true;
        Config::$overrideNotifUrl = route('midtrans.webhook');
    }

    public function index()
    {
        try {
            $user = auth()->user();
            $transactions = Transaction::with(['booking.rooms'])
                ->whereHas('booking', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->latest()
                ->get()
                ->map(function($transaction) {
                    // Calculate payment deadline and expired status
                    $paymentDeadline = null;
                    $isExpired = false;
                    
                    if ($transaction->payment_status === 'pending') {
                        $createdAt = \Carbon\Carbon::parse($transaction->created_at)->timezone('Asia/Jakarta');
                        $deadline = $createdAt->copy()->addHour();
                        $now = \Carbon\Carbon::now()->timezone('Asia/Jakarta');
                        
                        $paymentDeadline = $deadline->format('Y-m-d H:i:s');
                        $isExpired = $now->isAfter($deadline);
                    }

                    // Format the transaction data
                    return [
                        'id' => $transaction->id,
                        'order_id' => $transaction->order_id,
                        'transaction_id' => $transaction->transaction_id,
                        'created_at' => $transaction->created_at,
                        'status' => $isExpired ? 'Expired' : ucfirst($transaction->payment_status),
                        'payment_status' => $transaction->payment_status,
                        'transaction_status' => $transaction->transaction_status,
                        'payment_type' => $transaction->payment_type,
                        'payment_code' => $transaction->payment_code,
                        'gross_amount' => $transaction->gross_amount,
                        'payment_deadline' => $paymentDeadline,
                        'is_expired' => $isExpired,
                        'booking' => [
                            'check_in_date' => $transaction->booking->check_in_date,
                            'check_out_date' => $transaction->booking->check_out_date,
                            'room_number' => $transaction->booking->rooms->first()->room_number ?? null,
                        ]
                    ];
                });

            $html = view('transactions._list', [
                'transactions' => $transactions,
                'showPayButton' => function($transaction) {
                    return $transaction['payment_status'] !== 'paid' 
                        && $transaction['payment_status'] !== 'deposit'
                        && $transaction['payment_status'] !== 'cancelled'
                        && !$transaction['is_expired'];
                },
                'showCancelButton' => function($transaction) {
                    return $transaction['payment_status'] !== 'paid'
                        && $transaction['payment_status'] !== 'deposit'
                        && $transaction['payment_status'] !== 'cancelled'
                        && !$transaction['is_expired'];
                }
            ])->render();

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            \Log::error('Error loading transactions: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to load transactions'], 500);
        }
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
            if (!in_array($transaction->payment_status, ['pending'])) {
                throw new \Exception('This transaction cannot be cancelled.');
            }

            // Update transaction status using constants
            $transaction->update([
                'transaction_status' => Transaction::STATUS_CANCEL,
                'payment_status' => Transaction::PAYMENT_CANCELLED
            ]);

            // Update booking status using constants
            $booking = $transaction->booking;
            $booking->update([
                'status' => Booking::STATUS_CANCELLED,
                'payment_status' => Booking::PAYMENT_CANCELLED
            ]);

            // Release the room reservation if any
            if ($booking->rooms) {
                foreach ($booking->rooms as $room) {
                    $room->update(['status' => 'available']);
                }
            }

            // Get room IDs from the booking
            $roomIds = $booking->rooms->pluck('id')->toArray();

            // Broadcast the event
            event(new BookingStatusChanged($roomIds, 'Booking has been cancelled'));

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

            // Set payment deadline
            $startTime = \Carbon\Carbon::now('Asia/Jakarta');
            $paymentDeadline = $startTime->copy()->addHour();

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
                'expiry' => [
                    'start_time' => $startTime->format('Y-m-d H:i:s O'),
                    'unit' => 'minutes',
                    'duration' => $startTime->diffInMinutes($paymentDeadline)
                ],
                'custom_field1' => json_encode(['payment_deadline' => $paymentDeadline->format('Y-m-d H:i:s O')]),
                'callbacks' => [
                    'finish' => route('payment.finish'),
                    'error' => route('payment.error'),
                    'cancel' => route('payment.cancel'),
                    'notification' => route('midtrans.webhook')
                ]
            ];

            \Log::info('Creating Midtrans transaction', [
                'transaction_id' => $transaction->id,
                'params' => $params,
                'payment_deadline' => $paymentDeadline->format('Y-m-d H:i:s')
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
                'raw_response' => null,
                'payment_deadline' => $paymentDeadline
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

            // Find transaction
            $transaction = Transaction::where('order_id', $orderId)->first();
            
            // If no transaction found, just redirect without error
            if (!$transaction) {
                \Log::info('No transaction found, user probably closed the payment window', ['order_id' => $orderId]);
                return redirect()->to('/?panel=transactions&source=midtrans');
            }

            $booking = $transaction->booking;
            if (!$booking) {
                return redirect()->to('/?panel=transactions&source=midtrans')
                    ->with('error', 'Booking not found');
            }

            // Get transaction status from Midtrans
            try {
                $midtransStatus = \Midtrans\Transaction::status($orderId);
                
                \Log::info('Midtrans status retrieved', [
                    'order_id' => $orderId,
                    'status' => $midtransStatus,
                    'is_deposit' => $transaction->is_deposit
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
                    if (in_array($midtransStatus->transaction_status, ['settlement', 'capture'])) {
                        if ($midtransStatus->fraud_status == 'accept' || $midtransStatus->transaction_status == 'settlement') {
                            $transaction->transaction_status = 'settlement';
                            
                            // Check if this is a deposit payment
                            if ($transaction->is_deposit) {
                                $transaction->payment_status = 'deposit';
                                $booking->payment_status = 'deposit';
                            } else {
                                $transaction->payment_status = 'paid';
                                $booking->payment_status = 'paid';
                            }
                            $booking->status = 'confirmed';
                        }
                    } else if ($midtransStatus->transaction_status === 'pending') {
                        $transaction->payment_status = 'pending';
                        $transaction->transaction_status = 'pending';
                        $booking->payment_status = 'pending';
                        $booking->status = 'pending';
                    }

                    $transaction->save();
                    $booking->save();

                    DB::commit();

                    // Redirect based on payment status
                    if (in_array($transaction->payment_status, ['paid', 'deposit'])) {
                        $message = $transaction->is_deposit ? 
                            'Deposit payment successful! Please pay the remaining amount at check-in.' : 
                            'Payment successful!';
                        return redirect()->to('/?panel=transactions&source=midtrans')
                            ->with('success', $message);
                    } else {
                        return redirect()->to('/?panel=transactions&source=midtrans')
                            ->with('info', 'Payment is being processed. Please check your payment status.');
                    }

                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to get Midtrans status', [
                    'order_id' => $orderId,
                    'error' => $e->getMessage()
                ]);
                return redirect()->to('/?panel=transactions&source=midtrans')
                    ->with('info', 'Payment status will be updated shortly. Please check your transaction history.');
            }

        } catch (\Exception $e) {
            \Log::error('Error in payment finish: ' . $e->getMessage());
            return redirect()->to('/?panel=transactions&source=midtrans')
                ->with('error', 'An error occurred while processing your payment. Please check your transaction history.');
        }
    }

    private function getStatusMessage($status, $isDeposit)
    {
        return match($status) {
            'deposit' => 'Deposit payment successful! Please pay the remaining amount at check-in.',
            'paid' => 'Payment successful! Your booking has been confirmed.',
            'pending' => 'Please complete your payment using the provided payment instructions.',
            'cancelled' => 'Payment has been cancelled.',
            'expired' => 'Payment has expired.',
            default => 'Payment status: ' . ucfirst($status)
        };
    }

    public function finishAjax(Request $request)
    {
        try {
            $orderId = $request->query('order_id');
            \Log::info('AJAX Payment finish callback received', ['order_id' => $orderId]);

            // Find transaction
            $transaction = Transaction::where('order_id', $orderId)->first();
            
            // If no transaction found, return success without error
            if (!$transaction) {
                \Log::info('No transaction found, user probably closed the payment window', ['order_id' => $orderId]);
                return response()->json([
                    'success' => true,
                    'status' => 'paid',
                    'message' => 'Payment Success'
                ]);
            }

            $booking = $transaction->booking;
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            // Get transaction status from Midtrans
            try {
                $midtransStatus = \Midtrans\Transaction::status($orderId);
                
                \Log::info('Midtrans status retrieved', [
                    'order_id' => $orderId,
                    'status' => $midtransStatus,
                    'is_deposit' => $transaction->is_deposit
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

                    // Update payment status based on transaction status
                    if ($midtransStatus->transaction_status == 'capture') {
                        if ($midtransStatus->fraud_status == 'challenge') {
                            $transaction->payment_status = 'pending';
                            $booking->payment_status = 'pending';
                            $booking->status = 'pending';
                        } else if ($midtransStatus->fraud_status == 'accept') {
                            $transaction->payment_status = $transaction->is_deposit ? 'deposit' : 'paid';
                            $booking->payment_status = $transaction->is_deposit ? 'deposit' : 'paid';
                            $booking->status = 'confirmed';
                        }
                    } else if ($midtransStatus->transaction_status == 'settlement') {
                        $transaction->payment_status = $transaction->is_deposit ? 'deposit' : 'paid';
                        $booking->payment_status = $transaction->is_deposit ? 'deposit' : 'paid';
                        $booking->status = 'confirmed';
                    } else if ($midtransStatus->transaction_status == 'pending') {
                        $transaction->payment_status = 'pending';
                        $booking->payment_status = 'pending';
                        $booking->status = 'pending';
                    } else if (in_array($midtransStatus->transaction_status, ['deny', 'cancel', 'expire', 'failure'])) {
                        $transaction->payment_status = 'cancelled';
                        $booking->payment_status = 'cancelled';
                        $booking->status = 'cancelled';
                    }

                    // Save changes
                    $transaction->save();
                    $booking->save();
                    DB::commit();

                    // Log successful update
                    \Log::info('Transaction and booking updated successfully', [
                        'transaction_id' => $transaction->id,
                        'booking_id' => $booking->id,
                        'payment_status' => $transaction->payment_status,
                        'transaction_status' => $transaction->transaction_status
                    ]);

                    return response()->json([
                        'success' => true,
                        'status' => $transaction->payment_status,
                        'message' => $this->getStatusMessage($transaction->payment_status, $transaction->is_deposit),
                        'transaction' => [
                            'id' => $transaction->id,
                            'order_id' => $transaction->order_id,
                            'status' => $transaction->transaction_status,
                            'payment_status' => $transaction->payment_status,
                            'payment_type' => $transaction->payment_type,
                            'payment_code' => $transaction->payment_code,
                            'is_deposit' => $transaction->is_deposit
                        ]
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to get Midtrans status', [
                    'order_id' => $orderId,
                    'error' => $e->getMessage()
                ]);
                return response()->json([
                    'success' => true,
                    'status' => 'pending',
                    'message' => 'Payment status will be updated shortly'
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Error in payment finish AJAX: ' . $e->getMessage());
            return response()->json([
                'success' => true,
                'status' => 'error',
                'message' => 'An error occurred while processing your payment'
            ]);
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
        } elseif ($paymentType === 'echannel') {
            return 'Mandiri Bill Payment';
        } elseif ($paymentType === 'qris') {
            return 'QRIS';
        } elseif ($paymentType === 'gopay') {
            return 'GoPay';
        } elseif ($paymentType === 'shopeepay') {
            return 'ShopeePay';
        } elseif ($paymentType === 'credit_card') {
            return 'Credit Card';
        } elseif ($paymentType === 'cstore') {
            return ucfirst($midtransStatus->store ?? 'Convenience Store');
        }
        
        return ucfirst($paymentType);
    }

    private function getPaymentCode($midtransStatus)
    {
        $paymentType = $midtransStatus->payment_type;
        
        if ($paymentType === 'bank_transfer') {
            if (!empty($midtransStatus->va_numbers)) {
                return $midtransStatus->va_numbers[0]->va_number;
            } elseif (!empty($midtransStatus->permata_va_number)) {
                return $midtransStatus->permata_va_number;
            }
        } elseif ($paymentType === 'echannel') {
            return $midtransStatus->bill_key ?? null;
        } elseif ($paymentType === 'qris') {
            return $midtransStatus->transaction_id;
        } elseif ($paymentType === 'gopay' || $paymentType === 'shopeepay') {
            return $midtransStatus->transaction_id;
        } elseif ($paymentType === 'credit_card') {
            return $midtransStatus->masked_card ?? $midtransStatus->transaction_id;
        } elseif ($paymentType === 'cstore') {
            return $midtransStatus->payment_code ?? $midtransStatus->transaction_id;
        }
        
        return $midtransStatus->transaction_id;
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

    public function downloadInvoice(Transaction $transaction)
    {
        // Check if transaction is paid
        if (!in_array($transaction->payment_status, ['paid', 'settlement', 'deposit'])) {
            return response()->json([
                'error' => 'Invoice is only available for paid transactions'
            ], 403);
        }

        $booking = $transaction->booking;
        if (!$booking) {
            return response()->json([
                'error' => 'Booking not found'
            ], 404);
        }

        try {
            $pdf = PDF::loadView('transactions.invoice', [
                'transaction' => $transaction,
                'booking' => $booking
            ]);

            return $pdf->download('invoice-' . $transaction->order_id . '.pdf');
        } catch (\Exception $e) {
            \Log::error('Error generating invoice: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to generate invoice'
            ], 500);
        }
    }
} 