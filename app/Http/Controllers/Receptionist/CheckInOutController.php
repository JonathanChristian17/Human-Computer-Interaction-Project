<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Revenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckInOutController extends Controller
{
    /**
     * Display list of bookings ready for check-in.
     */
    public function checkInList(Request $request)
    {
        $query = Booking::with(['user', 'rooms'])
            ->where('status', 'confirmed')
            ->where('check_in_date', '<=', now())
            ->where('check_out_date', '>', now());

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('check_in_date', $request->date);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'checked_in') {
                $query->whereNotNull('checked_in_at');
            } elseif ($request->status === 'not_checked_in') {
                $query->whereNull('checked_in_at');
            }
        }

        $bookings = $query->orderBy('check_in_date')
            ->paginate(10)
            ->withQueryString();

        return view('receptionist.check-in', compact('bookings'));
    }

    /**
     * Display list of bookings ready for check-out.
     */
    public function checkOutList(Request $request)
    {
        $query = Booking::with(['user', 'rooms'])
            ->whereNotNull('checked_in_at')
            ->where('check_out_date', '<=', now());

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('check_out_date', $request->date);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'checked_out') {
                $query->whereNotNull('checked_out_at');
            } elseif ($request->status === 'not_checked_out') {
                $query->whereNull('checked_out_at');
            }
        }

        $bookings = $query->orderBy('check_out_date')
            ->paginate(10)
            ->withQueryString();

        return view('receptionist.check-out', compact('bookings'));
    }

    /**
     * Process check-in for a booking.
     */
    public function checkIn(Request $request, Booking $booking)
    {
        try {
            DB::beginTransaction();

            // Validate booking status
            if ($booking->status !== 'confirmed') {
                throw new \Exception('Booking harus dalam status confirmed untuk check-in.');
            }

            // Validate payment status
            if ($booking->payment_status !== 'paid') {
                throw new \Exception('Pembayaran harus lunas sebelum check-in.');
            }

            // Update room status
            foreach ($booking->rooms as $room) {
                $room->update(['status' => 'occupied']);
            }

            // Update check-in time
            $booking->update([
                'checked_in_at' => now()
            ]);

            // Record revenue for check-in if not already recorded
            if (!$booking->revenues()->where('type', 'check_in')->exists()) {
                Revenue::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_price,
                    'type' => 'check_in',
                    'notes' => 'Pendapatan saat check-in',
                    'recorded_by' => auth()->id()
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Check-in berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Check-in error: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Process check-out for a booking.
     */
    public function checkOut(Request $request, Booking $booking)
    {
        try {
            DB::beginTransaction();

            // Validate booking has been checked in
            if (!$booking->checked_in_at) {
                throw new \Exception('Tamu harus sudah check-in sebelum bisa check-out.');
            }

            // Update room status
            foreach ($booking->rooms as $room) {
                $room->update(['status' => 'cleaning']);
            }

            // Update check-out time
            $booking->update([
                'checked_out_at' => now()
            ]);

            // Record revenue for check-out
            Revenue::create([
                'booking_id' => $booking->id,
                'amount' => 0, // Additional charges would be added here if any
                'type' => 'check_out',
                'notes' => 'Pendapatan tambahan saat check-out',
                'recorded_by' => auth()->id()
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Check-out berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Check-out error: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
} 