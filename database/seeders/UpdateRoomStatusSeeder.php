<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;

class UpdateRoomStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update all rooms based on their is_available field
        Room::where('is_available', true)
            ->update(['status' => Room::STATUS_AVAILABLE]);

        Room::where('is_available', false)
            ->update(['status' => Room::STATUS_MAINTENANCE]);
    }
}
