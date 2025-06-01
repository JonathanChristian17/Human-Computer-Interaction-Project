<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\DB;
use App\Models\Room;

class MidtransWebhookController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Log Midtrans configuration for debugging
        Log::info('Midtrans Configuration', [
            'server_key' => config('midtrans.server_key'),
            'is_production' => config('midtrans.is_production')
        ]);
    }

    protected function verifySignature(Request $request)
    {
        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $serverKey = config('midtrans.server_key');
        $signature = $request->header('X-Midtrans-Signature');
        $timestamp = $request->header('X-Midtrans-Timestamp');

        Log::info('Verifying Midtrans signature', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature' => $signature,
            'timestamp' => $timestamp
        ]);

        // For testing purposes, skip signature verification in local environment
        if (app()->environment('local')) {
            Log::info('Skipping signature verification in local environment');
            return true;
        }

        $expectedSignature = hash('sha512', $orderId . $timestamp . $grossAmount . $serverKey);
        return hash_equals($expectedSignature, $signature);
    }

    public function handle(Request $request)
    {
        Log::info('Webhook Request Received', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'body' => $request->all()
        ]);

        try {
            DB::beginTransaction();

            // Get notification body
            $notificationBody = $request->all();
            
            // Extract order ID and other fields from notification
            $orderId = $notificationBody['order_id'];
            $transactionStatus = $notificationBody['transaction_status'];
            $fraudStatus = $notificationBody['fraud_status'] ?? null;
            $paymentType = $notificationBody['payment_type'] ?? null;
            $transactionId = $notificationBody['transaction_id'] ?? null;
            $vaNumbers = $notificationBody['va_numbers'] ?? null;
            $permataVaNumber = $notificationBody['permata_va_number'] ?? null;
            $paymentCode = null;
            
            // Extract custom fields
            $customField1 = json_decode($notificationBody['custom_field1'] ?? '{}', true);
            $isDeposit = $customField1['is_deposit'] ?? false;
            
            Log::info('Processing webhook notification', [
                'order_id' => $orderId,
                'status' => $transactionStatus,
                'fraud' => $fraudStatus,
                'payment_type' => $paymentType,
                'is_deposit' => $isDeposit
            ]);

            // Find transaction by order ID with retries
            $maxRetries = 3;
            $retryDelay = 1; // seconds
            $attempt = 1;
            $transaction = null;

            while ($attempt <= $maxRetries) {
                $transaction = Transaction::where('order_id', $orderId)->first();
                if ($transaction) {
                    break;
                }
                Log::info("Transaction not found on attempt {$attempt}, retrying...", ['order_id' => $orderId]);
                sleep($retryDelay);
                $attempt++;
            }

            if (!$transaction) {
                throw new \Exception("Transaction not found for order_id: {$orderId}");
            }

            // Find booking
            $booking = $transaction->booking;
            if (!$booking) {
                throw new \Exception("Booking not found for transaction: {$transaction->id}");
            }

            // Set payment type based on Midtrans response
            if ($paymentType === 'bank_transfer') {
                if (!empty($vaNumbers)) {
                    $bankName = strtoupper($vaNumbers[0]['bank']);
                    $paymentType = $bankName . ' Virtual Account';
                    $paymentCode = $vaNumbers[0]['va_number'];
                } elseif (!empty($permataVaNumber)) {
                    $paymentType = 'PERMATA Virtual Account';
                    $paymentCode = $permataVaNumber;
                }
            } elseif ($paymentType === 'echannel') {
                $paymentType = 'Mandiri Bill Payment';
                $paymentCode = $notificationBody['bill_key'] ?? null;
            } elseif ($paymentType === 'qris') {
                $paymentType = 'QRIS';
                $paymentCode = $transactionId;
            } elseif ($paymentType === 'gopay') {
                $paymentType = 'GoPay';
                $paymentCode = $transactionId;
            } elseif ($paymentType === 'shopeepay') {
                $paymentType = 'ShopeePay';
                $paymentCode = $transactionId;
            } elseif ($paymentType === 'credit_card') {
                $paymentType = 'Credit Card';
                $paymentCode = $notificationBody['masked_card'] ?? $transactionId;
            } elseif ($paymentType === 'cstore') {
                $paymentType = ucfirst($notificationBody['store'] ?? 'Convenience Store');
                $paymentCode = $notificationBody['payment_code'] ?? $transactionId;
            }

            // Store complete notification data
            $transaction->raw_response = json_encode($notificationBody);
            $transaction->transaction_id = $transactionId;
            $transaction->payment_type = $paymentType;
            $transaction->payment_code = $paymentCode;
            $transaction->transaction_status = $transactionStatus;
            $transaction->transaction_time = $notificationBody['transaction_time'] ?? null;

            // Update payment status based on transaction status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $transaction->payment_status = 'pending';
                    $booking->payment_status = 'pending';
                    $booking->status = 'pending';
                } else if ($fraudStatus == 'accept') {
                    $transaction->payment_status = $transaction->is_deposit ? 'deposit' : 'paid';
                    $booking->payment_status = $transaction->is_deposit ? 'deposit' : 'paid';
                    $booking->status = 'confirmed';
                }
            } else if ($transactionStatus == 'settlement') {
                $transaction->payment_status = $transaction->is_deposit ? 'deposit' : 'paid';
                $booking->payment_status = $transaction->is_deposit ? 'deposit' : 'paid';
                $booking->status = 'confirmed';
            } else if ($transactionStatus == 'pending') {
                $transaction->payment_status = 'pending';
                $booking->payment_status = 'pending';
                $booking->status = 'pending';
            } else if (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {
                $transaction->payment_status = 'cancelled';
                $booking->payment_status = 'cancelled';
                $booking->status = 'cancelled';
            }

            // Save changes
            $transaction->save();
            $booking->save();

            DB::commit();

            Log::info('Webhook processing completed', [
                'order_id' => $orderId,
                'final_status' => [
                    'transaction_status' => $transaction->transaction_status,
                    'payment_status' => $transaction->payment_status,
                    'booking_status' => $booking->status
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification processed successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
} 