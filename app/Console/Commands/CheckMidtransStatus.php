<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Transaction as MidtransTransaction;

class CheckMidtransStatus extends Command
{
    protected $signature = 'midtrans:check-status {order_id?}';
    protected $description = 'Check transaction status from Midtrans and update local database';

    public function __construct()
    {
        parent::__construct();
        
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
    }

    public function handle()
    {
        try {
            $orderId = $this->argument('order_id');
            
            if ($orderId) {
                // Check single transaction
                $this->checkTransaction($orderId);
            } else {
                // Check all pending transactions
                $this->checkAllPendingTransactions();
            }

            $this->info('Transaction check completed successfully');
            
        } catch (\Exception $e) {
            $this->error('Error checking transaction status: ' . $e->getMessage());
            Log::error('Error in CheckMidtransStatus command: ' . $e->getMessage());
        }
    }

    protected function checkAllPendingTransactions()
    {
        $pendingTransactions = Transaction::where('payment_status', 'pending')->get();
        
        $this->info("Found {$pendingTransactions->count()} pending transactions");
        
        foreach ($pendingTransactions as $transaction) {
            $this->checkTransaction($transaction->order_id);
        }
    }

    protected function checkTransaction($orderId)
    {
        $this->info("Checking transaction status for order ID: {$orderId}");
        
        try {
            // Get status from Midtrans
            $midtransStatus = MidtransTransaction::status($orderId);
            
            Log::info('Midtrans status response', [
                'order_id' => $orderId,
                'status' => $midtransStatus
            ]);
            
            // Find local transaction
            $transaction = Transaction::where('order_id', $orderId)
                ->orWhereRaw("order_id LIKE ?", ["%$orderId%"])
                ->first();
            
            if (!$transaction) {
                $this->error("Transaction not found for order ID: {$orderId}");
                return;
            }
            
            $booking = $transaction->booking;
            if (!$booking) {
                $this->error("Booking not found for transaction ID: {$transaction->id}");
                return;
            }
            
            // Update status based on Midtrans response
            $statusUpdated = false;
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

            // Save changes
            if ($transaction->isDirty() || $booking->isDirty()) {
                $transaction->save();
                $booking->save();
                
                $this->info("Updated status for order ID {$orderId}: {$transaction->payment_status}");
                
                if ($statusUpdated) {
                    event(new \App\Events\PaymentStatusUpdated($transaction));
                    $this->info("Broadcasted payment status update event");
                }
            } else {
                $this->info("No status changes needed for order ID {$orderId}");
            }
            
        } catch (\Exception $e) {
            $this->error("Error checking order ID {$orderId}: " . $e->getMessage());
            Log::error("Error checking Midtrans status for order {$orderId}: " . $e->getMessage());
        }
    }
} 