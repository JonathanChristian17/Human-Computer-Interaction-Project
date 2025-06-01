<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function checkAvailability(Request $request, Room $room)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        return response()->json([
            'available' => $room->isAvailableForDates($request->check_in, $request->check_out)
        ]);
    }

    public function getUnavailableDates(Room $room)
    {
        // Get all valid bookings (excluding cancelled, expired, etc.)
        $bookings = $room->bookings()
            ->join('transactions', 'bookings.id', '=', 'transactions.booking_id')
            ->where(function($query) {
                $query->where(function($q) {
                    // Include only valid bookings
                    $q->whereNotIn('bookings.status', ['cancelled'])
                      ->whereNotIn('bookings.payment_status', ['cancelled'])
                      ->where(function($q) {
                          $q->where('transactions.payment_status', '!=', 'expire')
                            ->orWhere(function($q) {
                                // For pending payments, check if they're not expired (within 1 hour)
                                $q->where('transactions.payment_status', 'pending')
                                  ->where('transactions.created_at', '>=', now()->subHour());
                            });
                      });
                });
            })
            ->where(function($query) {
                // Only include future dates and ongoing bookings
                $query->where('bookings.check_out_date', '>', now())
                    ->orWhere(function($q) {
                        $q->where('bookings.check_in_date', '<=', now())
                          ->where('bookings.check_out_date', '>', now());
                    });
            })
            ->get();

        $unavailableDates = [];
        foreach ($bookings as $booking) {
            $period = \Carbon\CarbonPeriod::create(
                $booking->check_in_date,
                $booking->check_out_date->subDay() // Exclude checkout day as it can be a check-in day for another booking
            );

            foreach ($period as $date) {
                // Only add dates that are today or in the future
                if ($date->startOfDay()->gte(now()->startOfDay())) {
                    $unavailableDates[] = $date->format('Y-m-d');
                }
            }
        }

        return response()->json([
            'unavailable_dates' => array_values(array_unique($unavailableDates))
        ]);
    }
} 