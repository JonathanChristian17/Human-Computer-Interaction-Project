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
        // Get all bookings that are pending, confirmed or checked in
        $bookings = $room->bookings()
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->get();

        $unavailableDates = [];
        foreach ($bookings as $booking) {
            $period = \Carbon\CarbonPeriod::create(
                $booking->check_in_date,
                $booking->check_out_date->subDay() // Exclude checkout day as it can be a check-in day for another booking
            );

            foreach ($period as $date) {
                $unavailableDates[] = $date->format('Y-m-d');
            }
        }

        return response()->json([
            'unavailable_dates' => array_values(array_unique($unavailableDates))
        ]);
    }
} 