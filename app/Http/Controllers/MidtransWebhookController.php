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
            
            Log::info('Processing notification', [
                'order_id' => $orderId,
                'status' => $transactionStatus,
                'fraud' => $fraudStatus,
                'payment_type' => $paymentType
            ]);

            // Find transaction by order ID
            $transaction = Transaction::where('order_id', $orderId)->first();
            if (!$transaction) {
                throw new \Exception("Transaction not found for order_id: {$orderId}");
            }

            // Find booking
            $booking = Booking::find($transaction->booking_id);
            if (!$booking) {
                throw new \Exception("Booking not found for transaction: {$transaction->id}");
            }

            // Store complete notification data
            $transaction->raw_response = json_encode($notificationBody);
            $transaction->transaction_id = $transactionId;
            $transaction->payment_type = $paymentType;
            $transaction->transaction_status = $transactionStatus;

            // Update transaction and booking status based on notification
            switch ($transactionStatus) {
                case 'capture':
                    if ($fraudStatus == 'challenge') {
                        $transaction->payment_status = 'challenge';
                        $booking->payment_status = 'pending';
                        $booking->status = 'pending';
                    } else if ($fraudStatus == 'accept') {
                        $transaction->payment_status = 'paid';
                        $booking->payment_status = 'paid';
                        $booking->status = 'confirmed';
                    }
                    break;

                case 'settlement':
                    $transaction->payment_status = 'paid';
                    $booking->payment_status = 'paid';
                    $booking->status = 'confirmed';
                    break;

                case 'pending':
                    $transaction->payment_status = 'pending';
                    $booking->payment_status = 'pending';
                    $booking->status = 'pending';
                    break;

                case 'deny':
                case 'expire':
                case 'cancel':
                    $transaction->payment_status = 'failed';
                    $booking->payment_status = 'failed';
                    $booking->status = 'cancelled';
                    break;
            }

            // Save changes
            $transaction->save();
            $booking->save();

            Log::info('Webhook processed successfully', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payment_status' => $transaction->payment_status,
                'booking_status' => $booking->status
            ]);

            DB::commit();
            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error processing webhook: ' . $e->getMessage(), [
                'exception' => $e,
                'payload' => $request->all()
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
} 