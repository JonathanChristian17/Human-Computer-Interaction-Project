<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Revenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CheckInOutController extends Controller
{
    /**
     * Display list of bookings ready for check-in today.
     */
    public function checkInList(Request $request)
    {
        $query = Booking::with(['rooms'])
            ->where(function($q) {
                $q->where('status', 'confirmed')
                  ->orWhere('status', 'checked_in');
            })
            ->whereDate('check_in_date', today())
            ->whereDate('check_out_date', '>', today())
            ->where('payment_status', 'paid');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('check_in_date')
            ->paginate(10)
            ->withQueryString();

        return view('receptionist.check-in.index', compact('bookings'));
    }

    /**
     * Display list of bookings ready for check-out today.
     */
    public function checkOutList(Request $request)
    {
        $query = Booking::with(['rooms'])
            ->where('status', 'checked_in')
            ->whereDate('check_out_date', today());

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->orderBy('check_out_date')
            ->paginate(10)
            ->withQueryString();

        return view('receptionist.check-out.index', compact('bookings'));
    }

    /**
     * Display list of completed bookings.
     */
    public function completedList(Request $request)
    {
        $query = Booking::with(['rooms'])
            ->where('status', 'checked_out');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('check_out_date', $request->date);
        }

        $bookings = $query->latest('checked_out_at')
            ->paginate(10)
            ->withQueryString();

        return view('receptionist.bookings.completed', compact('bookings'));
    }

    /**
     * Process check-in for a booking.
     */
    public function checkIn(Request $request, Booking $booking)
    {
        try {
            DB::beginTransaction();

            // Validate booking status
            if ($booking->status !== 'confirmed' || $booking->payment_status !== 'paid') {
                throw new \Exception('Booking harus dalam status confirmed dan sudah dibayar untuk melakukan check-in.');
            }

            if ($booking->checked_in_at) {
                throw new \Exception('Booking sudah di-check-in sebelumnya.');
            }

            // Validate check-in date
            if (!$booking->check_in_date->isToday()) {
                throw new \Exception('Booking hanya bisa di-check-in pada tanggal check-in yang ditentukan.');
            }

            // Update booking status
            $booking->status = 'checked_in';
            $booking->checked_in_at = now();
            $booking->save();

            DB::commit();
            return redirect()->back()->with('success', 'Check-in berhasil dilakukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Check-in failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Gagal melakukan check-in: ' . $e->getMessage());
        }
    }

    /**
     * Process check-out for a booking.
     */
    public function checkOut(Request $request, Booking $booking)
    {
        try {
            DB::beginTransaction();

            // Validate booking status
            if ($booking->status !== 'checked_in') {
                throw new \Exception('Booking harus dalam status checked-in untuk melakukan check-out.');
            }

            if ($booking->checked_out_at) {
                throw new \Exception('Booking sudah di-check-out sebelumnya.');
            }

            // Validate check-out date
            if (!$booking->check_out_date->isToday()) {
                throw new \Exception('Booking hanya bisa di-check-out pada tanggal check-out yang ditentukan.');
            }

            // Update booking status
            $booking->status = 'checked_out';
            $booking->checked_out_at = now();
            $booking->save();

            // Update room status to available
            foreach ($booking->rooms as $room) {
                $room->status = 'available';
                $room->save();
            }

            DB::commit();
            return redirect()->route('receptionist.bookings.completed')->with('success', 'Check-out berhasil dilakukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Check-out failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Gagal melakukan check-out: ' . $e->getMessage());
        }
    }
} 