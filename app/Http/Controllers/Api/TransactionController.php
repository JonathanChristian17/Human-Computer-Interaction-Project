<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Transaction as MidtransTransaction;

class TransactionController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
    }

    public function checkStatus($orderId)
    {
        try {
            // Get status from Midtrans
            $midtransStatus = MidtransTransaction::status($orderId);
            
            Log::info('Checking Midtrans status via API', [
                'order_id' => $orderId,
                'status' => $midtransStatus
            ]);
            
            // Find local transaction
            $transaction = Transaction::where('order_id', $orderId)
                ->orWhereRaw("order_id LIKE ?", ["%$orderId%"])
                ->first();
            
            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found',
                ], 404);
            }
            
            $booking = $transaction->booking;
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking not found',
                ], 404);
            }
            
            // Update status based on Midtrans response
            $statusUpdated = false;
            $oldStatus = [
                'transaction_status' => $transaction->transaction_status,
                'payment_status' => $transaction->payment_status,
                'booking_status' => $booking->status,
                'booking_payment_status' => $booking->payment_status
            ];

            switch ($midtransStatus->transaction_status) {
                case 'capture':
                    if ($midtransStatus->fraud_status == 'challenge') {
                        $transaction->transaction_status = 'challenge';
                        $transaction->payment_status = 'pending';
                        $booking->payment_status = 'pending';
                        $booking->status = 'pending';
                    } else if ($midtransStatus->fraud_status == 'accept') {
                        $transaction->transaction_status = 'success';
                        $transaction->payment_status = 'paid';
                        $booking->payment_status = 'paid';
                        $booking->status = 'confirmed';
                        $statusUpdated = true;
                    }
                    break;
                case 'settlement':
                    $transaction->transaction_status = 'settlement';
                    $transaction->payment_status = 'paid';
                    $booking->payment_status = 'paid';
                    $booking->status = 'confirmed';
                    $statusUpdated = true;
                    break;
                case 'pending':
                    $transaction->transaction_status = 'pending';
                    $transaction->payment_status = 'pending';
                    $booking->payment_status = 'pending';
                    $booking->status = 'pending';
                    break;
                case 'deny':
                case 'cancel':
                case 'expire':
                    $transaction->transaction_status = $midtransStatus->transaction_status;
                    $transaction->payment_status = 'cancelled';
                    $booking->payment_status = 'cancelled';
                    $booking->status = 'cancelled';
                    break;
            }

            // Save changes if there are any
            if ($transaction->isDirty() || $booking->isDirty()) {
                $transaction->save();
                $booking->save();
                
                if ($statusUpdated) {
                    event(new \App\Events\PaymentStatusUpdated($transaction));
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Status updated successfully',
                    'data' => [
                        'old_status' => $oldStatus,
                        'new_status' => [
                            'transaction_status' => $transaction->transaction_status,
                            'payment_status' => $transaction->payment_status,
                            'booking_status' => $booking->status,
                            'booking_payment_status' => $booking->payment_status
                        ],
                        'midtrans_status' => $midtransStatus
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status is already up to date',
                'data' => [
                    'current_status' => [
                        'transaction_status' => $transaction->transaction_status,
                        'payment_status' => $transaction->payment_status,
                        'booking_status' => $booking->status,
                        'booking_payment_status' => $booking->payment_status
                    ],
                    'midtrans_status' => $midtransStatus
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error checking Midtrans status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error checking transaction status: ' . $e->getMessage()
            ], 500);
        }
    }
} 