<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run()
    {
        Room::create([
            'name' => 'Kamar Standard',
            'description' => 'Kamar nyaman dengan fasilitas standar.',
            'capacity' => 2,
            'price' => 250000,
        ]);

        Room::create([
            'name' => 'Kamar Deluxe',
            'description' => 'Kamar lebih luas dengan fasilitas lengkap.',
            'capacity' => 4,
            'price' => 500000,
        ]);

        Room::create([
            'name' => 'Suite VIP',
            'description' => 'Kamar eksklusif dengan layanan premium.',
            'capacity' => 2,
            'price' => 1000000,
        ]);
    }
}
