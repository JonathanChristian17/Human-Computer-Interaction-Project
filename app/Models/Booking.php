<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone',
        'id_number',
        'check_in_date',
        'check_out_date',
        'status',
        'payment_status',
        'total_price',
        'special_requests',
        'checked_in_at',
        'checked_out_at',
        'managed_by',
        'check_in_time',
        'check_out_time'
    ];

    protected $casts = [
        'check_in_date' => 'datetime',
        'check_out_date' => 'datetime',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'total_price' => 'decimal:2'
    ];

    protected $attributes = [
        'status' => 'pending',
        'payment_status' => 'pending'
    ];

    protected $appends = ['room'];

    // Add status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_CHECKED_IN = 'checked_in';
    const STATUS_CHECKED_OUT = 'checked_out';

    // Add payment status constants
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_CANCELLED = 'cancelled';
    const PAYMENT_REFUNDED = 'refunded';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'booking_room')
                    ->withPivot(['price_per_night', 'quantity', 'subtotal'])
                    ->withTimestamps();
    }

    // Helper method to get the first room
    public function room()
    {
        return $this->belongsToMany(Room::class, 'booking_room')->first();
    }

    public function receptionist()
    {
        return $this->belongsTo(User::class, 'managed_by');
    }

    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed()
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isCheckedIn()
    {
        return $this->status === self::STATUS_CHECKED_IN;
    }

    public function isCheckedOut()
    {
        return $this->status === self::STATUS_CHECKED_OUT;
    }

    public function isPaymentPending()
    {
        return $this->payment_status === self::PAYMENT_PENDING;
    }

    public function isPaymentPaid()
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function isPaymentCancelled()
    {
        return $this->payment_status === self::PAYMENT_CANCELLED;
    }

    public function isPaymentRefunded()
    {
        return $this->payment_status === self::PAYMENT_REFUNDED;
    }

    public function markAsPaid()
    {
        $this->payment_status = self::PAYMENT_PAID;
        $this->status = self::STATUS_CONFIRMED;
        return $this->save();
    }

    public function markAsCancelled()
    {
        $this->payment_status = self::PAYMENT_CANCELLED;
        $this->status = self::STATUS_CANCELLED;
        return $this->save();
    }

    // Scope untuk pemesanan aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'confirmed')
                    ->where('check_out_date', '>=', now());
    }

    // Hitung jumlah malam menginap
    public function getNightsAttribute()
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    // Format tanggal untuk tampilan
    public function getFormattedCheckInAttribute()
    {
        return $this->check_in_date->format('d M Y');
    }

    public function getFormattedCheckOutAttribute()
    {
        return $this->check_out_date->format('d M Y');
    }

    // Format harga untuk tampilan
    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }

    public function getCheckInDateAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value) : null;
    }

    public function getCheckOutDateAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value) : null;
    }

    public function setCheckInDateAttribute($value)
    {
        $this->attributes['check_in_date'] = $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function setCheckOutDateAttribute($value)
    {
        $this->attributes['check_out_date'] = $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
    }
}