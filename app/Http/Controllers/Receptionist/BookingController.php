<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Revenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['rooms', 'user'])
            ->whereIn('status', ['pending', 'confirmed', 'cancelled'])
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

        return view('receptionist.bookings', compact('bookings'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        try {
            DB::beginTransaction();

            // Validate request
            $request->validate([
                'status' => ['required', 'string', 'in:pending,confirmed,cancelled']
            ]);

            $oldStatus = $booking->status;
            $newStatus = $request->status;

            Log::info('Attempting to update booking status', [
                'booking_id' => $booking->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);

            // Validate status transition
            if (!$this->isValidStatusTransition($oldStatus, $newStatus)) {
                throw new \Exception('Status tidak dapat diubah dari ' . $oldStatus . ' ke ' . $newStatus);
            }

            // Handle status-specific actions
            switch ($newStatus) {
                case 'confirmed':
                    // Only create revenue record if not already paid
                    if ($booking->payment_status !== 'paid') {
                        Revenue::create([
                            'booking_id' => $booking->id,
                            'amount' => $booking->total_price,
                            'type' => 'check_in',
                            'notes' => 'Pembayaran di muka',
                            'recorded_by' => auth()->id()
                        ]);
                        
                        $booking->payment_status = 'paid';
                    }
                    break;

                case 'cancelled':
                    // Handle payment status for cancellation
                    if ($booking->payment_status === 'paid') {
                        $booking->payment_status = 'refunded';
                    }
                    break;
            }

            // Update booking status
            $booking->status = $newStatus;
            $booking->save();

            DB::commit();
            return redirect()->back()->with('success', 'Status pemesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update booking status', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Gagal memperbarui status pemesanan: ' . $e->getMessage());
        }
    }

    public function updatePaymentStatus(Request $request, Booking $booking)
    {
        try {
            DB::beginTransaction();

            // Validate request
            $request->validate([
                'payment_status' => ['required', 'string', 'in:pending,paid,refunded']
            ]);

            $oldStatus = $booking->payment_status;
            $newStatus = $request->payment_status;

            Log::info('Attempting to update payment status', [
                'booking_id' => $booking->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);

            if (!$this->isValidPaymentStatusTransition($oldStatus, $newStatus)) {
                throw new \Exception('Status pembayaran tidak dapat diubah.');
            }

            $booking->payment_status = $newStatus;
            $booking->save();

            DB::commit();

            Log::info('Payment status updated successfully', [
                'booking_id' => $booking->id,
                'new_status' => $newStatus
            ]);

            return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update payment status', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Gagal memperbarui status pembayaran: ' . $e->getMessage());
        }
    }

    public function generateInvoice(Booking $booking)
    {
        return view('receptionist.invoice', compact('booking'));
    }

    private function isValidStatusTransition($oldStatus, $newStatus): bool
    {
        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['cancelled'],
            'cancelled' => ['confirmed', 'pending'],  // Allow transitioning back to confirmed or pending
        ];

        return in_array($newStatus, $allowedTransitions[$oldStatus] ?? []);
    }

    private function isValidPaymentStatusTransition($oldStatus, $newStatus): bool
    {
        $allowedTransitions = [
            'pending' => ['paid', 'refunded'],
            'paid' => ['refunded'],
            'refunded' => []
        ];

        return in_array($newStatus, $allowedTransitions[$oldStatus] ?? []);
    }
} 