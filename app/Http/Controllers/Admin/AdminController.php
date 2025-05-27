<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Set timezone to Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta');
        
        // Basic stats
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalReceptionists = User::where('role', 'receptionist')->count();
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        
        // Daily stats
        $today = Carbon::today();
        $dailyBookings = Booking::whereDate('created_at', $today)->count();
        $dailyRevenue = Booking::whereDate('created_at', $today)->sum('total_price');
        $dailyCheckIns = Booking::whereDate('check_in_date', $today)->count();
        $dailyCheckOuts = Booking::whereDate('check_out_date', $today)->count();
        
        // Monthly stats
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        $monthlyBookings = Booking::whereBetween('created_at', [$monthStart, $monthEnd])->count();
        $monthlyRevenue = Booking::whereBetween('created_at', [$monthStart, $monthEnd])->sum('total_price');
        $newCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->count();
            
        // Calculate monthly occupancy rate
        $totalRoomDays = $totalRooms * Carbon::now()->daysInMonth;
        $occupiedRoomDays = Booking::whereBetween('check_in_date', [$monthStart, $monthEnd])
            ->whereBetween('check_out_date', [$monthStart, $monthEnd])
            ->sum(DB::raw('DATEDIFF(check_out_date, check_in_date)'));
        $monthlyOccupancyRate = $totalRoomDays > 0 ? round(($occupiedRoomDays / $totalRoomDays) * 100, 1) : 0;
        
        // Yearly stats
        $yearStart = Carbon::now()->startOfYear();
        $yearEnd = Carbon::now()->endOfYear();
        $yearlyBookings = Booking::whereBetween('created_at', [$yearStart, $yearEnd])->count();
        $yearlyRevenue = Booking::whereBetween('created_at', [$yearStart, $yearEnd])->sum('total_price');
            
        // Recent bookings
        $recentBookings = Booking::with(['user', 'rooms'])
            ->orderBy('created_at', 'desc')
            ->orderBy('check_in_date', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRooms',
            'availableRooms',
            'totalCustomers',
            'totalReceptionists',
            'totalBookings',
            'pendingBookings',
            'dailyBookings',
            'dailyRevenue',
            'dailyCheckIns',
            'dailyCheckOuts',
            'monthlyBookings',
            'monthlyRevenue',
            'newCustomers',
            'monthlyOccupancyRate',
            'yearlyBookings',
            'yearlyRevenue',
            'recentBookings'
        ));
    }
} 