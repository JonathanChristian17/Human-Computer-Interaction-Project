<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\Activity;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        // Room statistics
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $maintenanceRooms = Room::where('status', 'maintenance')->count();

        // User statistics
        $totalUsers = User::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalReceptionists = User::where('role', 'receptionist')->count();
        $totalAdmins = User::where('role', 'admin')->count();

        // Booking statistics
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $activeBookings = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();
        $completedBookings = Booking::where('status', 'checked_out')->count();

        // Revenue calculations
        $totalRevenue = Transaction::where('transaction_status', 'settlement')
            ->sum('gross_amount');
        $pendingRevenue = Transaction::whereIn('transaction_status', ['pending', 'authorize'])
            ->sum('gross_amount');
        $monthlyRevenue = Transaction::where('transaction_status', 'settlement')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('gross_amount');

        // Daily statistics
        $dailyBookings = Booking::whereDate('created_at', $today)->count();
        $dailyCheckins = Booking::whereDate('checked_in_at', $today)->count();
        $dailyCheckouts = Booking::whereDate('checked_out_at', $today)->count();
        $dailyRevenue = Transaction::where('transaction_status', 'settlement')
            ->whereDate('created_at', $today)
            ->sum('gross_amount');

        // Monthly statistics
        $monthlyBookings = Booking::whereMonth('created_at', Carbon::now()->month)->count();
        $monthlyOccupancyRate = 75.5; // This should be calculated based on actual data
        $monthlyADR = 850000; // This should be calculated based on actual data

        // Yearly statistics
        $yearlyBookings = Booking::whereYear('created_at', Carbon::now()->year)->count();
        $yearlyRevenue = Transaction::where('transaction_status', 'settlement')
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('gross_amount');

        // Calculate occupancy rates
        $totalRoomDaysYear = $totalRooms * Carbon::now()->startOfYear()->diffInDays(Carbon::now());
        $occupiedRoomDaysYear = Booking::where('status', 'checked_out')
            ->whereYear('check_out_date', Carbon::now()->year)
            ->sum(DB::raw('DATEDIFF(check_out_date, check_in_date)'));
        $yearlyOccupancyRate = $totalRoomDaysYear > 0 ? ($occupiedRoomDaysYear / $totalRoomDaysYear) * 100 : 0;

        // Calculate revenue growth
        $lastYearRevenue = Transaction::where('transaction_status', 'settlement')
            ->whereYear('created_at', Carbon::now()->subYear()->year)
            ->sum('gross_amount');
        $revenueGrowth = $lastYearRevenue > 0 ? 
            (($yearlyRevenue - $lastYearRevenue) / $lastYearRevenue) * 100 : 0;

        // Recent activities query builder
        $activitiesQuery = Activity::with('user');

        // Filter by activity type
        if ($request->filled('activity_type')) {
            $activitiesQuery->where('activity_type', $request->activity_type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $activitiesQuery->where('user_id', $request->user_id);
        }

        // Get unique activity types for filter dropdown
        $activityTypes = Activity::distinct()->pluck('activity_type');
        
        // Get users for filter dropdown (only admin and receptionist)
        $users = User::whereIn('role', ['admin', 'receptionist'])
            ->orderBy('name')
            ->get();

        // Get paginated activities
        $recentActivities = $activitiesQuery
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.dashboard', compact(
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'maintenanceRooms',
            'totalUsers',
            'totalCustomers',
            'totalReceptionists',
            'totalAdmins',
            'totalBookings',
            'pendingBookings',
            'activeBookings',
            'completedBookings',
            'totalRevenue',
            'pendingRevenue',
            'monthlyRevenue',
            'dailyBookings',
            'dailyCheckins',
            'dailyCheckouts',
            'dailyRevenue',
            'monthlyBookings',
            'monthlyRevenue',
            'monthlyOccupancyRate',
            'monthlyADR',
            'yearlyBookings',
            'yearlyRevenue',
            'yearlyOccupancyRate',
            'revenueGrowth',
            'recentActivities',
            'activityTypes',
            'users'
        ));
    }
} 