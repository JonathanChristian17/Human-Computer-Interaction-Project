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
            'price_per_night' => 250000,
            'is_available' => true,
            'image' => 'room-1.jpg',
        ]);

        Room::create([
            'room_number' => '102',
            'name' => 'Kamar Deluxe',
            'type' => 'deluxe',
            'description' => 'Kamar lebih luas dengan fasilitas lengkap.',
            'capacity' => 4,
            'price_per_night' => 500000,
            'is_available' => true,
            'image' => 'room-2.jpg',
        ]);

        Room::create([
            'room_number' => '201',
            'name' => 'Suite VIP',
            'type' => 'suite',
            'description' => 'Kamar eksklusif dengan layanan premium.',
            'capacity' => 2,
            'price_per_night' => 1000000,
            'is_available' => true,
            'image' => 'room-3.jpg',
        ]);

        Room::create([
            'room_number' => '205',
            'name' => 'Suite VIP',
            'type' => 'suite',
            'description' => 'Kamar eksklusif dengan layanan premium.',
            'capacity' => 2,
            'price_per_night' => 1000000,
            'is_available' => true,
            'image' => 'room-4.jpg',
        ]);

        Room::create([
            'room_number' => '202',
            'name' => 'Twin bed',
            'type' => 'suite',
            'description' => 'Kamar eksklusif dengan layanan premium.',
            'capacity' => 2,
            'price_per_night' => 1000000,
            'is_available' => true,
            'image' => 'room-5.jpg',
        ]);

        Room::create([
            'room_number' => '203',
            'name' => 'Deluxe',
            'type' => 'suite',
            'description' => 'Kamar eksklusif dengan layanan premium.',
            'capacity' => 2,
            'price_per_night' => 1000000,
            'is_available' => true,
            'image' => 'room-6.jpg',
        ]);

        Room::create([
            'room_number' => '301',
            'name' => 'Superior Room',
            'type' => 'standard',
            'description' => 'Kamar nyaman dengan fasilitas lengkap untuk dua orang.',
            'capacity' => 2,
            'price_per_night' => 650000,
            'is_available' => true,
            'image' => 'room-7.jpg',
        ]);

        Room::create([
            'room_number' => '302',
            'name' => 'Executive Suite',
            'type' => 'suite',
            'description' => 'Suite luas dengan ruang tamu terpisah dan pemandangan kota.',
            'capacity' => 3,
            'price_per_night' => 1250000,
            'is_available' => true,
            'image' => 'room-8.jpg',
        ]);

        Room::create([
            'room_number' => '303',
            'name' => 'Single Room',
            'type' => 'standard',
            'description' => 'Kamar minimalis untuk satu orang dengan desain modern.',
            'capacity' => 1,
            'price_per_night' => 400000,
            'is_available' => true,
            'image' => 'room-9.jpg',
        ]);

        Room::create([
            'room_number' => '304',
            'name' => 'Family Room',
            'type' => 'family',
            'description' => 'Kamar besar untuk keluarga dengan tempat tidur ganda.',
            'capacity' => 4,
            'price_per_night' => 950000,
            'is_available' => true,
            'image' => 'room-10.jpg',
        ]);

        Room::create([
            'room_number' => '305',
            'name' => 'Twin Room',
            'type' => 'standard',
            'description' => 'Kamar dengan dua tempat tidur single, cocok untuk teman atau rekan kerja.',
            'capacity' => 2,
            'price_per_night' => 600000,
            'is_available' => true,
            'image' => 'room-11.jpg',
        ]);

        Room::create([
            'room_number' => '306',
            'name' => 'Presidential Suite',
            'type' => 'luxury',
            'description' => 'Suite mewah dengan fasilitas kelas atas dan layanan VIP.',
            'capacity' => 5,
            'price_per_night' => 3000000,
            'is_available' => true,
            'image' => 'room-12.jpg',
        ]);

        Room::create([
            'room_number' => '307',
            'name' => 'Junior Suite',
            'type' => 'suite',
            'description' => 'Suite elegan dengan area duduk dan tempat tidur besar.',
            'capacity' => 2,
            'price_per_night' => 850000,
            'is_available' => true,
            'image' => 'room-13.jpg',
        ]);
    }
}
