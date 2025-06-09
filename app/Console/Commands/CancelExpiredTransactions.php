<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Events\BookingStatusChanged;

class CancelExpiredTransactions extends Command
{
    protected $signature = 'transactions:cancel-expired';
    protected $description = 'Cancel expired transactions based on payment deadline';

    public function handle()
    {
        $this->info('Starting to check for expired transactions...');

        try {
            DB::beginTransaction();

            // Get all pending transactions that have passed their payment deadline
            $expiredTransactions = Transaction::where('payment_status', 'pending')
                ->where('transaction_status', 'pending')
                ->where('payment_deadline', '<', Carbon::now())
                ->with('booking.rooms')
                ->get();

            $count = 0;
            foreach ($expiredTransactions as $transaction) {
                // Update transaction status
                $transaction->update([
                    'transaction_status' => 'expire',
                    'payment_status' => 'expired'
                ]);

                // Update booking status
                if ($transaction->booking) {
                    $transaction->booking->update([
                        'status' => 'cancelled',
                        'payment_status' => 'expired'
                    ]);

                    // Release the room reservation if any
                    if ($transaction->booking->rooms) {
                        foreach ($transaction->booking->rooms as $room) {
                            $room->update(['status' => 'available']);
                        }

                        // Get room IDs from the booking
                        $roomIds = $transaction->booking->rooms->pluck('id')->toArray();

                        // Broadcast the event
                        event(new BookingStatusChanged($roomIds, 'Booking has expired'));
                    }
                }

                $count++;
            }

            DB::commit();

            $this->info("Successfully processed {$count} expired transactions.");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error processing expired transactions: ' . $e->getMessage());
            \Log::error('Error in CancelExpiredTransactions command: ' . $e->getMessage());
        }
    }
}