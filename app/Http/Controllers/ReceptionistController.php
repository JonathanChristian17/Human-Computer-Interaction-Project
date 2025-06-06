<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\Revenue;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use PDF;

class ReceptionistController extends Controller
{
    /**
     * Display the receptionist dashboard.
     */
    public function dashboard(): View
    {
        // Set timezone ke Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta');
        
        $today = now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        
        // Get room statistics
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        
        // Count rooms with active guests (checked-in) for today
        $occupiedRooms = Room::whereHas('bookings', function($query) use ($today) {
            $query->where('status', 'checked_in')
                  ->whereDate('check_in_date', '<=', $today)
                  ->whereDate('check_out_date', '>', $today);
        })->count();
        
        // Get today's check-ins with guest and room details - only show ready for check-in
        $todayCheckIns = Booking::with(['rooms'])
            ->whereDate('check_in_date', $today)
            ->where(function($q) {
                $q->where('status', 'confirmed')
                  ->orWhere('status', 'checked_in');
            })
            ->where('payment_status', 'paid')
            ->get();
            
        // Get today's check-outs with guest and room details - only show currently checked in
        $todayCheckOuts = Booking::with(['rooms'])
            ->whereDate('check_out_date', $today)
            ->where('status', 'checked_in')
            ->get();

        // Calculate today's revenue
        $todayRevenue = Revenue::whereDate('created_at', $today)->sum('amount');

        return view('receptionist.dashboard', compact(
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'todayCheckIns',
            'todayCheckOuts',
            'todayRevenue'
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

        // Add eager loading for current booking and guest information
        $query->with(['bookings' => function($query) {
            $query->whereIn('status', ['confirmed', 'checked_in'])
                  ->with(['user' => function($query) {
                      $query->select('id', 'name', 'email', 'phone');
                  }])
                  ->orderBy('check_in_date', 'asc');
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

        $rooms = $query->latest()->paginate(9)->withQueryString();
        return view('receptionist.rooms', compact('rooms'));
    }

    /**
     * Update room status.
     */
    public function updateRoomStatus(Request $request, Room $room)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:available,maintenance'],
        ]);

        try {
            // Check if room has active bookings
            $hasActiveBooking = $room->bookings()
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->where('check_in_date', '<=', now())
                ->where('check_out_date', '>', now())
                ->exists();

            if ($hasActiveBooking) {
                return response()->json([
                    'message' => 'Cannot change status of room with active bookings.'
                ], 422);
            }

            $room->status = $validated['status'];
            $room->save();

            // Return success response for AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Room status updated successfully.',
                    'status' => $room->status
                ]);
            }

            // Redirect with success message for non-AJAX request
            return back()->with('success', 'Room status updated successfully.');
        } catch (\Exception $e) {
            // Return error response for AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to update room status: ' . $e->getMessage()
                ], 500);
            }

            // Redirect with error message for non-AJAX request
            return back()->with('error', 'Failed to update room status. ' . $e->getMessage());
        }
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
        // Get monthly bookings from actual bookings in database
        $monthlyBookings = Booking::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function ($booking) {
                return [
                    'month' => \Carbon\Carbon::createFromFormat('Y-m', $booking->month),
                    'total' => $booking->total
                ];
            });

        // Calculate monthly revenue from paid bookings
        $monthlyRevenue = Booking::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_price) as revenue')
            ->where('payment_status', 'paid')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function ($revenue) {
                return [
                    'month' => \Carbon\Carbon::createFromFormat('Y-m', $revenue->month),
                    'revenue' => $revenue->revenue
                ];
            });

        // Calculate this month's total revenue from paid bookings
        $thisMonthRevenue = Booking::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        // Calculate average price per night for this month's bookings
        $thisMonthBookings = Booking::with('rooms')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

        $totalNights = 0;
        $totalPrice = 0;
        foreach ($thisMonthBookings as $booking) {
            $nights = $booking->check_in_date->diffInDays($booking->check_out_date);
            $totalNights += $nights;
            $totalPrice += $booking->total_price;
        }
        $averageRatePerNight = $totalNights > 0 ? $totalPrice / $totalNights : 0;

        // Get all booking records with rooms and user information with pagination
        $bookingDetails = Booking::with(['rooms', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->through(function ($booking) {
                return [
                    'tanggal' => $booking->created_at,
                    'guest_name' => $booking->full_name,
                    'rooms' => $booking->rooms->map(function ($room) {
                        return $room->room_number . ' (' . $room->type . ')';
                    })->join(', '),
                    'check_in' => $booking->check_in_date->format('d M Y'),
                    'check_out' => $booking->check_out_date->format('d M Y'),
                    'total_price' => $booking->total_price,
                    'status' => $booking->status,
                    'payment_status' => $booking->payment_status
                ];
            });

        // Calculate occupancy rate
        $totalRooms = Room::count();
        $occupiedRooms = Room::whereHas('bookings', function($query) {
            $query->where('status', 'checked_in')
                  ->whereDate('check_in_date', '<=', now())
                  ->whereDate('check_out_date', '>', now());
        })->count();
        
        $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;

        // Get last month's revenue for comparison
        $lastMonthRevenue = Booking::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_price');

        $revenueGrowth = $lastMonthRevenue > 0 ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;

        return view('receptionist.reports', compact(
            'monthlyBookings', 
            'monthlyRevenue', 
            'bookingDetails',
            'occupancyRate',
            'thisMonthRevenue',
            'averageRatePerNight',
            'revenueGrowth'
        ));
    }

    /**
     * Download reports as PDF
     */
    public function downloadReports()
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

        // Get all booking records with rooms and user information
        $bookingDetails = Booking::with(['rooms', 'user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                return [
                    'tanggal' => $booking->created_at,
                    'guest_name' => $booking->full_name,
                    'rooms' => $booking->rooms->map(function ($room) {
                        return $room->room_number . ' (' . $room->type . ')';
                    })->join(', '),
                    'check_in' => $booking->check_in_date->format('d M Y'),
                    'check_out' => $booking->check_out_date->format('d M Y'),
                    'total_price' => $booking->total_price,
                    'status' => $booking->status,
                    'payment_status' => $booking->payment_status
                ];
            });

        // Calculate occupancy rate
        $totalRooms = Room::count();
        $occupiedRooms = Room::whereHas('bookings', function($query) {
            $query->where('status', 'checked_in')
                  ->whereDate('check_in_date', '<=', now())
                  ->whereDate('check_out_date', '>', now());
        })->count();
        
        $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;

        // Calculate average rate
        $thisMonthBookings = $monthlyBookings->where('month', now()->month)->first()?->total ?? 0;
        $thisMonthRevenue = $monthlyRevenue->where('month', now()->month)->first()?->revenue ?? 0;
        $averageRate = $thisMonthBookings > 0 ? $thisMonthRevenue / $thisMonthBookings : 0;

        $pdf = PDF::loadView('receptionist.reports-pdf', compact(
            'monthlyBookings', 
            'monthlyRevenue', 
            'bookingDetails',
            'occupancyRate',
            'averageRate'
        ));

        return $pdf->download('laporan-pendapatan-' . now()->format('Y-m') . '.pdf');
    }
} 