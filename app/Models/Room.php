<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms'; // nama tabel yang digunakan
    protected $fillable = [
        'room_number',
        'name',
        'type',
        'description',
        'price_per_night',
        'capacity',
        'image',
        'is_available',
        'status'
    ];

    protected $casts = [
        'price_per_night' => 'integer',
        'is_available' => 'boolean',
        'capacity' => 'integer'
    ];

    // Define status constants
    const STATUS_AVAILABLE = 'available';
    const STATUS_OCCUPIED = 'occupied';
    const STATUS_CLEANING = 'cleaning';
    const STATUS_MAINTENANCE = 'maintenance';

    public function bookings()
    {
        return $this->belongsToMany(Booking::class)
                    ->withPivot(['price_per_night', 'quantity', 'subtotal'])
                    ->withTimestamps();
    }

    public function currentBooking()
    {
        return $this->bookings()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->orderBy('check_in_date', 'desc')
            ->limit(1);
    }

    public function isAvailable()
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    public function isOccupied()
    {
        return $this->status === self::STATUS_OCCUPIED;
    }

    public function isCleaning()
    {
        return $this->status === self::STATUS_CLEANING;
    }

    public function isUnderMaintenance()
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price_per_night, 0, ',', '.');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'green',
            self::STATUS_OCCUPIED => 'red',
            self::STATUS_CLEANING => 'yellow',
            self::STATUS_MAINTENANCE => 'gray',
            default => 'gray'
        };
    }

    public function isAvailableForDates($checkIn, $checkOut)
    {
        // Convert dates to Carbon instances for comparison
        $checkIn = \Carbon\Carbon::parse($checkIn)->startOfDay();
        $checkOut = \Carbon\Carbon::parse($checkOut)->endOfDay();

        \Log::info('Checking room availability for dates', [
            'room_id' => $this->id,
            'room_type' => $this->type,
            'check_in' => $checkIn->toDateString(),
            'check_out' => $checkOut->toDateString()
        ]);

        // First check if room status allows booking
        if (!in_array($this->status, [self::STATUS_AVAILABLE, self::STATUS_CLEANING])) {
            \Log::info('Room not available - status not bookable', [
                'room_id' => $this->id,
                'status' => $this->status
            ]);
            return false;
        }

        // Check for any overlapping bookings (including pending)
        $conflictingBookings = $this->bookings()
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->whereNotIn('status', ['cancelled', 'refunded']) // Exclude cancelled and refunded bookings
                    ->where(function($q) use ($checkIn, $checkOut) {
                        // Check for any date overlap scenarios:
                        // 1. New booking's check-in date falls between existing booking
                        // 2. New booking's check-out date falls between existing booking
                        // 3. New booking completely encompasses an existing booking
                        // 4. Existing booking completely encompasses the new booking
                        $q->where(function($q) use ($checkIn, $checkOut) {
                            $q->where('check_in_date', '<=', $checkOut)
                              ->where('check_out_date', '>=', $checkIn);
                        });
                    });
            })
            ->get();

        if ($conflictingBookings->isNotEmpty()) {
            \Log::info('Room not available - conflicting bookings found', [
                'room_id' => $this->id,
                'check_in' => $checkIn->toDateString(),
                'check_out' => $checkOut->toDateString(),
                'conflicts' => $conflictingBookings->map(function($booking) {
                    return [
                        'booking_id' => $booking->id,
                        'check_in' => $booking->check_in_date,
                        'check_out' => $booking->check_out_date,
                        'status' => $booking->status
                    ];
                })
            ]);
            return false;
        }

        return true;
    }
}
