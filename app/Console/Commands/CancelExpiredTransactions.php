<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CancelExpiredTransactions extends Command
{
    protected $signature = 'transactions:cancel-expired';
    protected $description = 'Cancel transactions that have exceeded their payment deadline';

    public function handle()
    {
        $expiredTransactions = Transaction::where('transaction_status', Transaction::STATUS_PENDING)
            ->where('payment_status', Transaction::PAYMENT_PENDING)
            ->where(function($query) {
                $query->where(function($q) {
                    // Check transactions with payment_deadline
                    $q->whereNotNull('payment_deadline')
                      ->where('payment_deadline', '<', now());
                })
                ->orWhere(function($q) {
                    // Check transactions without payment_deadline (use created_at + 1 hour)
                    $q->whereNull('payment_deadline')
                      ->where('created_at', '<=', now()->subHour());
                });
            })
            ->get();

        $count = 0;
        foreach ($expiredTransactions as $transaction) {
            try {
                DB::beginTransaction();

                // Update transaction status
                $transaction->update([
                    'transaction_status' => Transaction::STATUS_EXPIRE,
                    'payment_status' => 'expired'
                ]);

                // Update booking status
                if ($transaction->booking) {
                    $transaction->booking->update([
                        'status' => 'expired',
                        'payment_status' => 'expired'
                    ]);

                    // Release the room(s) for this booking
                    foreach ($transaction->booking->rooms as $room) {
                        if ($room->status === 'booked') {
                            $room->update(['status' => 'available']);
                        }
                    }
                }

                DB::commit();
                $count++;

                Log::info('Transaction and booking expired due to payment deadline', [
                    'transaction_id' => $transaction->id,
                    'order_id' => $transaction->order_id,
                    'booking_id' => $transaction->booking->id ?? null
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to expire transaction', [
                    'transaction_id' => $transaction->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("Successfully processed {$count} expired transactions.");
    }
} 