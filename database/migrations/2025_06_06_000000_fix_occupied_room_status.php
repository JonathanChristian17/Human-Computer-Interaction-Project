<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Room;
use App\Models\Booking;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all rooms with 'occupied' status
        $occupiedRooms = Room::where('status', 'occupied')->get();

        foreach ($occupiedRooms as $room) {
            // Check if room has active booking
            $hasActiveBooking = $room->bookings()
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->exists();

            // If room has active booking, keep it as is
            // If not, set it to available
            if (!$hasActiveBooking) {
                $room->update(['status' => 'available']);
            }
        }

        // Update any remaining 'occupied' rooms to 'available'
        Room::where('status', 'occupied')->update(['status' => 'available']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this migration as it's a data fix
    }
}; 