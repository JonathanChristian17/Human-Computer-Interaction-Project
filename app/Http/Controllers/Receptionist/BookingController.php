<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Revenue;
use App\Models\Activity;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['rooms', 'user'])
            ->latest();

        // Apply search filter
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        // Apply status filter
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(10);

        return view('receptionist.bookings.index', compact('bookings'));
    }

    public function generateInvoice(Booking $booking)
    {
        return view('receptionist.bookings.invoice', compact('booking'));
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,confirmed,cancelled'
            ]);

            // Don't allow status change if booking is already checked in or out
            if (in_array($booking->status, ['checked_in', 'checked_out'])) {
                throw new \Exception('Tidak dapat mengubah status booking yang sudah check-in atau check-out.');
            }

            DB::beginTransaction();

            // Update booking status
            $booking->status = $request->status;
            
            // If status is cancelled, update payment status too
            if ($request->status === 'cancelled') {
                $booking->payment_status = Booking::PAYMENT_CANCELLED;
                
                // Update associated transaction if exists
                if ($booking->transactions()->exists()) {
                    $transaction = $booking->transactions()->latest()->first();
                    $transaction->update([
                        'transaction_status' => Transaction::STATUS_CANCEL,
                        'payment_status' => Transaction::PAYMENT_CANCELLED
                    ]);

                    // Emit payment status updated event
                    event(new \App\Events\PaymentStatusUpdated([
                        'transaction_id' => $transaction->id,
                        'order_id' => $transaction->order_id,
                        'transaction_status' => $transaction->transaction_status,
                        'payment_status' => $transaction->payment_status,
                        'booking_id' => $booking->id
                    ]));
                }
                
                // Release the room reservation if any
                if ($booking->rooms) {
                    foreach ($booking->rooms as $room) {
                        $room->update(['status' => 'available']);
                    }
                }
            }
            
            $booking->save();

            DB::commit();
            return redirect()->back()->with('success', 'Status booking berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update booking status', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    /**
     * Update payment status
     */
    public function updatePayment(Request $request, Booking $booking)
    {
        try {
            $request->validate([
                'payment_status' => 'required|in:paid'
            ]);

            // Only allow updating from deposit to paid
            if ($booking->payment_status !== 'deposit') {
                throw new \Exception('Hanya dapat mengubah status pembayaran dari deposit menjadi lunas.');
            }

            DB::beginTransaction();

            $oldStatus = $booking->payment_status;
            $booking->payment_status = $request->payment_status;
            $booking->save();

            // Update associated transaction
            $transaction = $booking->transactions()->latest()->first();
            if ($transaction) {
                $transaction->payment_status = 'paid';
                $transaction->transaction_status = 'settlement';
                $transaction->save();

                // Emit payment status updated event
                event(new \App\Events\PaymentStatusUpdated([
                    'transaction_id' => $transaction->id,
                    'order_id' => $transaction->order_id,
                    'transaction_status' => $transaction->transaction_status,
                    'payment_status' => $transaction->payment_status,
                    'booking_id' => $booking->id
                ]));
            }

            // Create revenue record for the remaining payment
            $remainingAmount = $booking->total_price - $booking->deposit_amount;
            Revenue::create([
                'booking_id' => $booking->id,
                'amount' => $remainingAmount,
                'payment_method' => 'manual',
                'payment_date' => now(),
                'description' => 'Pelunasan pembayaran booking #' . $booking->id
            ]);

            // Log activity
            Activity::log(
                auth()->id(),
                'Update status pembayaran',
                "Mengubah status pembayaran booking #{$booking->id} dari {$oldStatus} menjadi {$booking->payment_status}",
                'payment_status_update',
                $booking
            );

            DB::commit();
            return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update payment status', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Gagal mengubah status pembayaran: ' . $e->getMessage());
        }
    }
} 