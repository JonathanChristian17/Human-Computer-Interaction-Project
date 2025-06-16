<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['rooms', 'user', 'transactions' => function($query) {
                $query->latest();
            }])
            ->whereHas('transactions')
            ->latest();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('transactions', function($q) use ($search) {
                      $q->where('order_id', 'like', "%{$search}%")
                        ->orWhere('transaction_id', 'like', "%{$search}%");
                  });
            });
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $status = $request->payment_status;
            
            // Map status to transaction and payment statuses
            $statusMap = [
                'pending' => ['transaction_status' => 'pending'],
                'paid' => ['transaction_status' => ['settlement', 'capture']],
                'expired' => ['transaction_status' => 'expire'],
                'cancelled' => ['transaction_status' => ['cancel', 'deny']]
            ];

            if (isset($statusMap[$status])) {
                $query->whereHas('transactions', function($q) use ($statusMap, $status) {
                    if (isset($statusMap[$status]['transaction_status'])) {
                        if (is_array($statusMap[$status]['transaction_status'])) {
                            $q->whereIn('transaction_status', $statusMap[$status]['transaction_status']);
                        } else {
                            $q->where('transaction_status', $statusMap[$status]['transaction_status']);
                        }
                    }
                });
            }
        }

        $bookings = $query->paginate(10);

        return view('receptionist.transactions.index', compact('bookings'));
    }
} 