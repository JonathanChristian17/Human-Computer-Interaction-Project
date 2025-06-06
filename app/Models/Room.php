<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    const STATUS_MAINTENANCE = 'maintenance';

    /**
     * The bookings that belong to the room.
     */
    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_room')
            ->withTimestamps();
    }

    /**
     * Check if room has an active booking
     */
    public function hasActiveBooking(): bool
    {
        return $this->bookings()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->exists();
    }

    /**
     * Get current active booking
     */
    public function getCurrentBooking()
    {
        return $this->bookings()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->orderBy('check_in_date', 'asc')
            ->first();
    }

    /**
     * Check if room has checked-in guests
     */
    public function hasCheckedInGuests(): bool
    {
        return $this->bookings()
            ->where('status', 'checked_in')
            ->exists();
    }

    /**
     * Get current checked-in booking
     */
    public function getCheckedInBooking()
    {
        return $this->bookings()
            ->where('status', 'checked_in')
            ->with('user')
            ->first();
    }

    /**
     * Update room status and handle related bookings
     */
    public function updateStatus(string $status): bool
    {
        // If room has checked-in guests, prevent any status change
        if ($this->hasCheckedInGuests()) {
            return false;
        }

        // If room has active bookings, only allow changing to 'available'
        if ($this->hasActiveBooking() && $status !== 'available') {
            return false;
        }

        $this->status = $status;
        return $this->save();
    }

    public function isAvailable()
    {
        return $this->status === self::STATUS_AVAILABLE;
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
            self::STATUS_MAINTENANCE => 'gray',
            default => 'gray'
        };
    }

    public function isAvailableForDates($checkIn, $checkOut, $excludeBookingId = null)
    {
        // Convert dates to Carbon instances for comparison
        $checkIn = \Carbon\Carbon::parse($checkIn)->startOfDay();
        $checkOut = \Carbon\Carbon::parse($checkOut)->startOfDay();

        \Log::info('Checking room availability for dates', [
            'room_id' => $this->id,
            'room_type' => $this->type,
            'check_in' => $checkIn->toDateString(),
            'check_out' => $checkOut->toDateString(),
            'exclude_booking_id' => $excludeBookingId
        ]);

        // Check for any overlapping bookings (including pending)
        $conflictingBookings = $this->bookings()
            ->join('transactions', 'bookings.id', '=', 'transactions.booking_id')
            ->where(function($query) use ($checkIn, $checkOut, $excludeBookingId) {
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
                })
                ->where(function($q) use ($checkIn, $checkOut) {
                    // New booking starts before existing booking ends AND
                    // New booking ends after existing booking starts
                    // BUT exclude case where new booking starts on existing booking's check-out date
                    $q->where(function($q) use ($checkIn, $checkOut) {
                        $q->where('check_in_date', '<', $checkOut)
                          ->where('check_out_date', '>', $checkIn);
                    });
                });

                // Exclude the specified booking ID if provided
                if ($excludeBookingId) {
                    $query->where('bookings.id', '!=', $excludeBookingId);
                }
            })
            ->exists();

        if ($conflictingBookings) {
            \Log::info('Room not available - conflicting bookings found', [
                'room_id' => $this->id,
                'check_in' => $checkIn->toDateString(),
                'check_out' => $checkOut->toDateString(),
                'exclude_booking_id' => $excludeBookingId
            ]);
            return false;
        }

        return true;
    }
}
