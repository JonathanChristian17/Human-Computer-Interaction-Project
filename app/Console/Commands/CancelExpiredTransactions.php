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
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '<', now())
            ->get();

        foreach ($expiredTransactions as $transaction) {
            try {
                DB::beginTransaction();

                // Update transaction status
                $transaction->update([
                    'transaction_status' => Transaction::STATUS_EXPIRE,
                    'payment_status' => Transaction::PAYMENT_CANCELLED
                ]);

                // Update booking status
                if ($transaction->booking) {
                    $transaction->booking->update([
                        'status' => 'cancelled',
                        'payment_status' => 'cancelled'
                    ]);

                    // Release the room(s) for this booking
                    foreach ($transaction->booking->rooms as $room) {
                        if ($room->status === 'booked') {
                            $room->update(['status' => 'available']);
                        }
                    }
                }

                DB::commit();

                Log::info('Transaction and booking cancelled due to payment deadline', [
                    'transaction_id' => $transaction->id,
                    'order_id' => $transaction->order_id,
                    'booking_id' => $transaction->booking->id ?? null
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to cancel expired transaction', [
                    'transaction_id' => $transaction->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("Successfully processed {$expiredTransactions->count()} expired transactions.");
    }
} 