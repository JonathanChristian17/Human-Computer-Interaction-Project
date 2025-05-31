<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CheckExpiredBookings extends Command
{
    protected $signature = 'bookings:check-expired';
    protected $description = 'Check and update status of expired bookings';

    public function handle()
    {
        $this->info('Checking expired bookings...');
        
        try {
            DB::beginTransaction();

            // Get all pending bookings with pending payments older than 1 hour
            $expiredBookings = Booking::where('status', 'pending')
                ->where('payment_status', 'pending')
                ->whereHas('transaction', function($query) {
                    $query->where('created_at', '<=', now()->subHour())
                        ->where('payment_status', 'pending');
                })
                ->with('transaction')
                ->get();

            $count = 0;
            foreach ($expiredBookings as $booking) {
                // Update booking status
                $booking->status = 'cancelled';
                $booking->payment_status = 'cancelled';
                $booking->save();

                // Update transaction status
                if ($booking->transaction) {
                    $booking->transaction->payment_status = 'expire';
                    $booking->transaction->transaction_status = 'expire';
                    $booking->transaction->save();
                }

                $count++;
                $this->info("Updated booking #{$booking->id} to expired status");
                Log::info("Booking #{$booking->id} marked as expired", [
                    'booking_id' => $booking->id,
                    'transaction_id' => $booking->transaction->id ?? null
                ]);
            }

            DB::commit();
            $this->info("Successfully processed {$count} expired bookings");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing expired bookings: ' . $e->getMessage());
            $this->error('Error processing expired bookings: ' . $e->getMessage());
        }
    }
} 