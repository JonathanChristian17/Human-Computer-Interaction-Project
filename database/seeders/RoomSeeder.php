<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run()
    {
        Room::create([
            'room_number' => '101',
            'name' => 'Kamar Standard',
            'type' => 'standard',
            'description' => 'Kamar nyaman dengan fasilitas standar.',
            'capacity' => 2,
            'price' => 250000,
            'is_available' => true,
        ]);

        Room::create([
            'room_number' => '102',
            'name' => 'Kamar Deluxe',
            'type' => 'deluxe',
            'description' => 'Kamar lebih luas dengan fasilitas lengkap.',
            'capacity' => 4,
            'price' => 500000,
            'is_available' => true,
        ]);

        Room::create([
            'room_number' => '201',
            'name' => 'Suite VIP',
            'type' => 'suite',
            'description' => 'Kamar eksklusif dengan layanan premium.',
            'capacity' => 2,
            'price' => 1000000,
            'is_available' => true,
        ]);
    }
}
