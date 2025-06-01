<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalRooms = Room::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalReceptionists = User::where('role', 'receptionist')->count();
        $totalBookings = Booking::count();
        
        // Daily stats
        $dailyBookings = Booking::whereDate('created_at', Carbon::today())->count();
        $dailyRevenue = Booking::whereDate('created_at', Carbon::today())->sum('total_price');
        
        // Monthly stats
        $monthlyBookings = Booking::whereMonth('created_at', Carbon::now()->month)->count();
        $monthlyRevenue = Booking::whereMonth('created_at', Carbon::now()->month)->sum('total_price');
        
        // Yearly stats
        $yearlyBookings = Booking::whereYear('created_at', Carbon::now()->year)->count();
        $yearlyRevenue = Booking::whereYear('created_at', Carbon::now()->year)->sum('total_price');

        return view('admin.dashboard', compact(
            'totalRooms',
            'totalCustomers',
            'totalReceptionists',
            'totalBookings',
            'dailyBookings',
            'dailyRevenue',
            'monthlyBookings',
            'monthlyRevenue',
            'yearlyBookings',
            'yearlyRevenue'
        ));
    }
} 