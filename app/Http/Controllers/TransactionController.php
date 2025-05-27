<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        $transactions = Booking::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        if (request()->wantsJson()) {
            return response()->json([
                'html' => view('transactions._list', compact('transactions'))->render()
            ]);
        }

        return view('transactions.index', compact('transactions'));
    }

    public function cancel(Booking $transaction)
    {
        try {
            DB::beginTransaction();

            // Check authorization
            if ($transaction->user_id !== auth()->id()) {
                throw new \Exception('Unauthorized access');
            }

            // Check if transaction can be cancelled
            if (!in_array($transaction->status, ['pending', 'confirmed']) || 
                !in_array($transaction->payment_status, ['pending', 'failed'])) {
                throw new \Exception('Transaction cannot be cancelled');
            }

            // Update booking status
            $transaction->update([
                'status' => 'cancelled',
                'payment_status' => 'cancelled'
            ]);

            // If there's an associated Midtrans transaction, cancel it
            if ($transaction->transaction) {
                $transaction->transaction->update([
                    'transaction_status' => 'cancelled'
                ]);
            }

            // Release the room reservation
            foreach ($transaction->rooms as $room) {
                $room->update(['status' => 'available']);
            }

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaction cancelled successfully'
                ]);
            }

            return back()->with('success', 'Transaction cancelled successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Cancel Transaction Error: ' . $e->getMessage());

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function pay(Booking $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($transaction->payment_status !== 'pending' || $transaction->status === 'cancelled') {
            return response()->json(['message' => 'Transaction cannot be paid'], 400);
        }

        try {
            $orderId = 'ORDER-' . time() . '-' . $transaction->id;

            // Create Midtrans transaction parameters
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $transaction->total_price,
                ],
                'customer_details' => [
                    'first_name' => $transaction->full_name,
                    'email' => $transaction->email,
                    'phone' => $transaction->phone,
                ],
                'item_details' => [
                    [
                        'id' => 'ROOM-' . $transaction->id,
                        'price' => (int) ($transaction->total_price),
                        'quantity' => 1,
                        'name' => 'Room Booking',
                    ]
                ],
                'enabled_payments' => [
                    // Virtual Account Banks
                    'bca_va', 
                    'bni_va',
                    'bri_va',
                    'mandiri_va',
                    'permata_va',
                    'other_va',
                    
                    // E-Wallet
                    'gopay',
                    'shopeepay',
                    
                    // Convenience Store
                    'indomaret',
                    
                    // Internet Banking
                    'bca_klikbca',
                    'bca_klikpay',
                    'cimb_clicks',
                    'danamon_online',
                    'bri_epay'
                ],
                'payment_options' => [
                    'gopay' => ['enable_callback' => true],
                    'bca_va' => ['va_number' => '12345678901'],
                    'bni_va' => ['va_number' => '12345678'],
                    'bri_va' => ['va_number' => '12345678'],
                ],
                'callbacks' => [
                    'finish' => '/?panel=transactions&source=midtrans',
                    'error' => '/?panel=transactions&source=midtrans',
                    'cancel' => '/?panel=transactions&source=midtrans'
                ]
            ];

            // Get Snap token
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken
            ]);

        } catch (\Exception $e) {
            \Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment. Please try again.'
            ], 500);
        }
    }
} 