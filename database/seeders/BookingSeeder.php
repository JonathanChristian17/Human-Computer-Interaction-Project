<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some rooms and users for our bookings
        $rooms = Room::all();
        $users = User::where('role', 'customer')->get();

        if ($rooms->isEmpty()) {
            $this->command->error('No rooms found. Please run room seeder first.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->error('No users found. Please run user seeder first.');
            return;
        }

        // Create bookings with different statuses and dates
        $bookings = [
            [
                'user_id' => $users->random()->id,
                'full_name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '081234567890',
                'id_number' => '3171234567890001',
                'check_in_date' => Carbon::parse('2025-06-16'),
                'check_out_date' => Carbon::parse('2025-06-18'),
                'total_price' => 1000000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'special_requests' => 'Extra pillow',
                'checked_in_at' => null,
                'checked_out_at' => null,
                'room_ids' => [1], // Room 101
            ],
            [
                'user_id' => $users->random()->id,
                'full_name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '081234567891',
                'id_number' => '3171234567890002',
                'check_in_date' => Carbon::parse('2025-06-17'),
                'check_out_date' => Carbon::parse('2025-06-19'),
                'total_price' => 800000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'special_requests' => 'Late check-out',
                'checked_in_at' => null,
                'checked_out_at' => null,
                'room_ids' => [2], // Room 102
            ],
            [
                'user_id' => $users->random()->id,
                'full_name' => 'Bob Wilson',
                'email' => 'bob@example.com',
                'phone' => '081234567892',
                'id_number' => '3171234567890003',
                'check_in_date' => Carbon::parse('2025-06-20'),
                'check_out_date' => Carbon::parse('2025-06-23'),
                'total_price' => 1500000,
                'status' => 'pending',
                'payment_status' => 'pending',
                'special_requests' => null,
                'checked_in_at' => null,
                'checked_out_at' => null,
                'room_ids' => [3], // Room 103
            ],
            [
                'user_id' => $users->random()->id,
                'full_name' => 'Alice Brown',
                'email' => 'alice@example.com',
                'phone' => '081234567893',
                'id_number' => '3171234567890004',
                'check_in_date' => Carbon::parse('2025-06-10'),
                'check_out_date' => Carbon::parse('2025-06-12'),
                'total_price' => 600000,
                'status' => 'checked_in',
                'payment_status' => 'paid',
                'special_requests' => 'Early check-in',
                'checked_in_at' => Carbon::parse('2025-06-10 14:00:00'),
                'checked_out_at' => Carbon::parse('2025-06-12 12:00:00'),
                'room_ids' => [4], // Room 104
            ],
            [
                'user_id' => $users->random()->id,
                'full_name' => 'Charlie Davis',
                'email' => 'charlie@example.com',
                'phone' => '081234567894',
                'id_number' => '3171234567890005',
                'check_in_date' => Carbon::parse('2025-06-20'),
                'check_out_date' => Carbon::parse('2025-06-22'),
                'total_price' => 1200000,
                'status' => 'cancelled',
                'payment_status' => 'cancelled',
                'special_requests' => null,
                'checked_in_at' => null,
                'checked_out_at' => null,
                'room_ids' => [5], // Room 105
            ],
            [
                'user_id' => $users->random()->id,
                'full_name' => 'David Lee',
                'email' => 'david@example.com',
                'phone' => '081234567895',
                'id_number' => '3171234567890006',
                'check_in_date' => Carbon::parse('2025-06-13'),
                'check_out_date' => Carbon::parse('2025-06-14'),
                'total_price' => 500000,
                'status' => 'checked_in',
                'payment_status' => 'paid',
                'special_requests' => null,
                'checked_in_at' => Carbon::parse('2025-06-13 14:00:00'),
                'checked_out_at' => null,
                'room_ids' => [6], // Room 106
            ],
            [
                'user_id' => $users->random()->id,
                'full_name' => 'Eva Chen',
                'email' => 'eva@example.com',
                'phone' => '081234567896',
                'id_number' => '3171234567890007',
                'check_in_date' => Carbon::parse('2025-06-14'),
                'check_out_date' => Carbon::parse('2025-06-16'),
                'total_price' => 750000,
                'status' => 'checked_in',
                'payment_status' => 'paid',
                'special_requests' => null,
                'checked_in_at' => Carbon::parse('2025-06-14 15:00:00'),
                'checked_out_at' => null,
                'room_ids' => [7], // Room 107
            ],
            [
                'user_id' => $users->random()->id,
                'full_name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '081234567897',
                'id_number' => '3171234567890008',
                'check_in_date' => Carbon::parse('2025-06-13'),
                'check_out_date' => Carbon::parse('2025-06-15'),
                'total_price' => 2000000,
                'status' => 'checked_in',
                'payment_status' => 'paid',
                'special_requests' => 'Extra towels',
                'checked_in_at' => Carbon::parse('2025-06-13 14:00:00'),
                'checked_out_at' => null,
                'room_ids' => [8], // Room 201
            ],
            [
                'user_id' => $users->random()->id,
                'full_name' => 'Siti Rahayu',
                'email' => 'siti@example.com',
                'phone' => '081234567898',
                'id_number' => '3171234567890009',
                'check_in_date' => Carbon::parse('2025-06-14'),
                'check_out_date' => Carbon::parse('2025-06-15'),
                'total_price' => 1000000,
                'status' => 'checked_in',
                'payment_status' => 'paid',
                'special_requests' => 'High floor room',
                'checked_in_at' => Carbon::parse('2025-06-14 15:30:00'),
                'checked_out_at' => null,
                'room_ids' => [9], // Room 202
            ],
            [
                'user_id' => $users->random()->id,
                'full_name' => 'Ahmad Wijaya',
                'email' => 'ahmad@example.com',
                'phone' => '081234567899',
                'id_number' => '3171234567890010',
                'check_in_date' => Carbon::parse('2025-06-12'),
                'check_out_date' => Carbon::parse('2025-06-15'),
                'total_price' => 3000000,
                'status' => 'checked_in',
                'payment_status' => 'paid',
                'special_requests' => 'Connecting rooms',
                'checked_in_at' => Carbon::parse('2025-06-12 13:45:00'),
                'checked_out_at' => null,
                'room_ids' => [10, 11], // Room 203 & 204
            ],
        ];

        foreach ($bookings as $bookingData) {
            // Get room IDs and remove from data before creating booking
            $roomIds = $bookingData['room_ids'];
            unset($bookingData['room_ids']);
            
            // Create the booking
            $booking = Booking::create($bookingData);

            // Attach specified rooms to the booking
            foreach ($roomIds as $roomId) {
                $room = $rooms->find($roomId);
                if ($room) {
                    $booking->rooms()->attach($room->id, [
                        'price_per_night' => $room->price_per_night,
                        'quantity' => 1,
                        'subtotal' => $room->price_per_night * Carbon::parse($bookingData['check_in_date'])->diffInDays($bookingData['check_out_date'])
                    ]);
                }
            }
        }
    }
}
