<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function daily(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        
        $bookings = Booking::whereDate('created_at', $date)
            ->with(['user', 'rooms', 'receptionist'])
            ->get();
            
        $totalRevenue = $bookings->sum('total_price');
        $totalBookings = $bookings->count();
        
        return view('admin.reports.daily', compact('bookings', 'date', 'totalRevenue', 'totalBookings'));
    }

    public function monthly(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
        
        $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'rooms', 'receptionist'])
            ->get();
            
        $totalRevenue = $bookings->sum('total_price');
        $totalBookings = $bookings->count();
        
        // Daily breakdown for the month
        $dailyStats = collect();
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dailyBookings = $bookings->where('created_at', '>=', $date->copy()->startOfDay())
                                    ->where('created_at', '<=', $date->copy()->endOfDay());
            
            $dailyStats->push([
                'date' => $date->format('Y-m-d'),
                'bookings' => $dailyBookings->count(),
                'revenue' => $dailyBookings->sum('total_price')
            ]);
        }
        
        return view('admin.reports.monthly', compact('bookings', 'month', 'totalRevenue', 'totalBookings', 'dailyStats'));
    }

    public function yearly(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
        $endDate = Carbon::createFromDate($year, 12, 31)->endOfYear();
        
        $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->with(['user', 'rooms', 'receptionist'])
            ->get();
            
        $totalRevenue = $bookings->sum('total_price');
        $totalBookings = $bookings->count();
        
        // Monthly breakdown for the year
        $monthlyStats = collect();
        for ($month = 1; $month <= 12; $month++) {
            $monthStart = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            
            $monthlyBookings = $bookings->where('created_at', '>=', $monthStart)
                                      ->where('created_at', '<=', $monthEnd);
            
            $monthlyStats->push([
                'month' => $monthStart->format('F'),
                'bookings' => $monthlyBookings->count(),
                'revenue' => $monthlyBookings->sum('total_price')
            ]);
        }
        
        return view('admin.reports.yearly', compact('bookings', 'year', 'totalRevenue', 'totalBookings', 'monthlyStats'));
    }

    public function exportExcel(Request $request, $type)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $year = $request->input('year', Carbon::now()->year);

        switch ($type) {
            case 'daily':
                $bookings = Booking::whereDate('created_at', $date)
                    ->with(['user', 'rooms', 'receptionist'])
                    ->get();
                $filename = 'daily_report_' . $date;
                break;

            case 'monthly':
                $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
                $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
                $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])
                    ->with(['user', 'rooms', 'receptionist'])
                    ->get();
                $filename = 'monthly_report_' . $month;
                break;

            case 'yearly':
                $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
                $endDate = Carbon::createFromDate($year, 12, 31)->endOfYear();
                $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])
                    ->with(['user', 'rooms', 'receptionist'])
                    ->get();
                $filename = 'yearly_report_' . $year;
                break;

            default:
                return back()->with('error', 'Invalid report type');
        }

        return Excel::download(new BookingExport($bookings, $type), $filename . '.xlsx');
    }

    public function exportPdf(Request $request, $type)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $year = $request->input('year', Carbon::now()->year);

        switch ($type) {
            case 'daily':
                $bookings = Booking::whereDate('created_at', $date)
                    ->with(['user', 'rooms', 'receptionist'])
                    ->get();
                $title = 'Daily Report - ' . Carbon::parse($date)->format('d F Y');
                break;

            case 'monthly':
                $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
                $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
                $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])
                    ->with(['user', 'rooms', 'receptionist'])
                    ->get();
                $title = 'Monthly Report - ' . Carbon::parse($month)->format('F Y');
                break;

            case 'yearly':
                $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
                $endDate = Carbon::createFromDate($year, 12, 31)->endOfYear();
                $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])
                    ->with(['user', 'rooms', 'receptionist'])
                    ->get();
                $title = 'Yearly Report - ' . $year;
                break;

            default:
                return back()->with('error', 'Invalid report type');
        }

        $pdf = PDF::loadView('admin.reports.pdf', compact('bookings', 'title'));
        return $pdf->download($title . '.pdf');
    }
} 