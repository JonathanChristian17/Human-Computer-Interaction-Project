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
                'check_in_date' => Carbon::today(),
                'check_out_date' => Carbon::today()->addDays(2),
                'total_price' => 1000000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'special_requests' => 'Extra pillow',
                'checked_in_at' => null,
                'checked_out_at' => null,
            ],
            [
                'user_id' => $users->random()->id,
                'full_name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '081234567891',
                'id_number' => '3171234567890002',
                'check_in_date' => Carbon::yesterday(),
                'check_out_date' => Carbon::tomorrow(),
                'total_price' => 800000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'special_requests' => 'Late check-out',
                'checked_in_at' => Carbon::yesterday()->addHours(14),
                'checked_out_at' => null,
            ],
            [
                'user_id' => $users->random()->id,
                'full_name' => 'Bob Wilson',
                'email' => 'bob@example.com',
                'phone' => '081234567892',
                'id_number' => '3171234567890003',
                'check_in_date' => Carbon::tomorrow(),
                'check_out_date' => Carbon::tomorrow()->addDays(3),
                'total_price' => 1500000,
                'status' => 'pending',
                'payment_status' => 'pending',
                'special_requests' => null,
                'checked_in_at' => null,
                'checked_out_at' => null,
            ],
            [
                'user_id' => $users->random()->id,
                'full_name' => 'Alice Brown',
                'email' => 'alice@example.com',
                'phone' => '081234567893',
                'id_number' => '3171234567890004',
                'check_in_date' => Carbon::yesterday()->subDays(2),
                'check_out_date' => Carbon::yesterday(),
                'total_price' => 600000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'special_requests' => 'Early check-in',
                'checked_in_at' => Carbon::yesterday()->subDays(2)->addHours(14),
                'checked_out_at' => Carbon::yesterday()->addHours(12),
            ],
            [
                'user_id' => $users->random()->id,
                'full_name' => 'Charlie Davis',
                'email' => 'charlie@example.com',
                'phone' => '081234567894',
                'id_number' => '3171234567890005',
                'check_in_date' => Carbon::today()->addDays(5),
                'check_out_date' => Carbon::today()->addDays(7),
                'total_price' => 1200000,
                'status' => 'cancelled',
                'payment_status' => 'refunded',
                'special_requests' => null,
                'checked_in_at' => null,
                'checked_out_at' => null,
            ],
            [
                'user_id' => $users->random()->id,
                'full_name' => 'David Lee',
                'email' => 'david@example.com',
                'phone' => '081234567895',
                'id_number' => '3171234567890006',
                'check_in_date' => Carbon::today(),
                'check_out_date' => Carbon::today()->addDays(1),
                'total_price' => 500000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'special_requests' => null,
                'checked_in_at' => null,
                'checked_out_at' => null,
            ],
            [
                'user_id' => $users->random()->id,
                'full_name' => 'Eva Chen',
                'email' => 'eva@example.com',
                'phone' => '081234567896',
                'id_number' => '3171234567890007',
                'check_in_date' => Carbon::yesterday(),
                'check_out_date' => Carbon::today()->addDays(1),
                'total_price' => 750000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'special_requests' => null,
                'checked_in_at' => Carbon::yesterday()->addHours(15),
                'checked_out_at' => null,
            ],
        ];

        foreach ($bookings as $bookingData) {
            // Create the booking
            $booking = Booking::create($bookingData);

            // Attach random room(s) to the booking
            $roomCount = rand(1, 2); // Each booking can have 1-2 rooms
            $selectedRooms = $rooms->random($roomCount);
            
            foreach ($selectedRooms as $room) {
                $booking->rooms()->attach($room->id, [
                    'price_per_night' => $room->price_per_night,
                    'quantity' => 1,
                    'subtotal' => $room->price_per_night * Carbon::parse($bookingData['check_in_date'])->diffInDays($bookingData['check_out_date'])
                ]);
            }
        }
    }
}
