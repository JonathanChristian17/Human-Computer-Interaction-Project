<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\Revenue;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ReceptionistController extends Controller
{
    /**
     * Display the receptionist dashboard.
     */
    public function dashboard(): View
    {
        // Set timezone ke Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta');
        
        $totalRooms = Room::count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $availableRooms = Room::where('status', 'available')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        
        $today = now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        
        // Get today's check-ins with guest and room details - show all bookings for today
        $todayCheckIns = Booking::with(['user', 'rooms'])
            ->whereRaw('DATE(check_in_date) = ?', [$today])
            ->get();
            
        // Get today's check-outs with guest and room details
        $todayCheckOuts = Booking::with(['user', 'rooms'])
            ->whereRaw('DATE(check_out_date) = ?', [$today])
            ->get();

        return view('receptionist.dashboard', compact(
            'totalRooms',
            'occupiedRooms',
            'availableRooms',
            'pendingBookings',
            'todayCheckIns',
            'todayCheckOuts'
        ));
    }

    /**
     * Display the bookings list.
     */
    public function bookings(Request $request): View
    {
        $query = Booking::with(['user', 'rooms']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();
        
        return view('receptionist.bookings', compact('bookings'));
    }

    /**
     * Update booking status.
     */
    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,checked_in,checked_out,cancelled'],
        ]);

        try {
            DB::beginTransaction();

            // Update room status based on booking status
            if ($validated['status'] === 'confirmed') {
                // Record revenue when booking is confirmed
                Revenue::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_price,
                    'type' => 'confirmed',
                    'notes' => 'Pembayaran lunas',
                    'recorded_by' => auth()->id()
                ]);
                
                $booking->payment_status = 'paid';
            } elseif ($validated['status'] === 'checked_in') {
                // Just update room status and check-in time, no revenue recording
                foreach ($booking->rooms as $room) {
                    $room->update(['status' => 'occupied']);
                }
                $booking->checked_in_at = now();
            } elseif ($validated['status'] === 'checked_out') {
                // Just update room status and check-out time, no revenue recording
                foreach ($booking->rooms as $room) {
                    $room->update(['status' => 'available']);
                }
                $booking->checked_out_at = now();
            } elseif ($validated['status'] === 'cancelled' && $booking->payment_status === 'paid') {
                // If cancelling a paid booking, mark as refunded
                $booking->payment_status = 'refunded';
            }

            $booking->status = $validated['status'];
            $booking->save();

            DB::commit();
            return back()->with('success', 'Status pemesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui status pemesanan. ' . $e->getMessage());
        }
    }

    /**
     * Validate if the status transition is allowed
     */
    private function isValidStatusTransition($oldStatus, $newStatus): bool
    {
        $allowedTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['checked_in', 'cancelled'],
            'checked_in' => ['checked_out'],
            'checked_out' => [],
            'cancelled' => []
        ];

        return in_array($newStatus, $allowedTransitions[$oldStatus] ?? []);
    }

    /**
     * Generate invoice for a booking.
     */
    public function generateInvoice(Booking $booking): View
    {
        return view('receptionist.invoice', compact('booking'));
    }

    /**
     * Display rooms management page.
     */
    public function rooms(Request $request): View
    {
        $query = Room::query();

        // Add eager loading for current booking
        $query->with(['bookings' => function($query) {
            $query->whereIn('status', ['confirmed', 'checked_in'])
                  ->orderBy('check_in_date', 'desc')
                  ->limit(1);
        }]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('room_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rooms = $query->latest()->paginate(10)->withQueryString();
        return view('receptionist.rooms', compact('rooms'));
    }

    /**
     * Update room status.
     */
    public function updateRoomStatus(Request $request, Room $room)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:available,occupied,cleaning,maintenance'],
        ]);

        $room->update($validated);

        return back()->with('success', 'Room status updated successfully.');
    }

    /**
     * Display guests list.
     */
    public function guests(Request $request): View
    {
        $query = User::where('role', 'customer');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $guests = $query->withCount('bookings')
            ->latest()
            ->paginate(10)
            ->withQueryString();
            
        return view('receptionist.guests', compact('guests'));
    }

    /**
     * Display transactions list.
     */
    public function transactions(Request $request): View
    {
        $query = Booking::with(['user', 'rooms'])
            ->whereIn('status', ['confirmed', 'completed', 'checked_in', 'checked_out']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('rooms', function($roomQuery) use ($search) {
                    $roomQuery->where('room_number', 'like', "%{$search}%");
                });
            });
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $transactions = $query->latest()
            ->paginate(10)
            ->withQueryString();
            
        return view('receptionist.transactions', compact('transactions'));
    }

    /**
     * Update payment status for a booking.
     */
    public function updatePaymentStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'in:pending,paid,refunded'],
        ]);

        try {
            $booking->update($validated);
            return back()->with('success', 'Status pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui status pembayaran. ' . $e->getMessage());
        }
    }

    /**
     * Validate if the payment status transition is allowed
     */
    private function isValidPaymentStatusTransition($oldStatus, $newStatus): bool
    {
        $allowedTransitions = [
            'pending' => ['paid', 'refunded'],
            'paid' => ['refunded'],
            'refunded' => []
        ];

        return in_array($newStatus, $allowedTransitions[$oldStatus] ?? []);
    }

    /**
     * Display reports page.
     */
    public function reports(): View
    {
        $monthlyBookings = Booking::selectRaw('MONTH(check_in_date) as month, COUNT(*) as total')
            ->whereYear('check_in_date', date('Y'))
            ->groupBy('month')
            ->get();

        // Calculate monthly revenue from revenues table
        $monthlyRevenue = Revenue::selectRaw('MONTH(created_at) as month, SUM(amount) as revenue')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get();

        // Get detailed revenue records for the current month
        $revenueDetails = Revenue::with('recorder')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->latest()
            ->get();

        return view('receptionist.reports', compact('monthlyBookings', 'monthlyRevenue', 'revenueDetails'));
    }
} 