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
        'total_price',
        'special_requests',
        'status',
        'payment_status',
        'checked_in_at',
        'checked_out_at'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price' => 'decimal:2',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime'
    ];

    protected $attributes = [
        'status' => 'pending',
        'payment_status' => 'pending'
    ];

    protected $appends = ['room'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class)
                    ->withPivot(['price_per_night', 'quantity', 'subtotal'])
                    ->withTimestamps();
    }

    // Helper method to get the first room
    public function getRoomAttribute()
    {
        if ($this->relationLoaded('rooms')) {
            return $this->rooms->first();
        }
        return $this->rooms()->first();
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
        return $this->status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isCheckedIn()
    {
        return $this->status === 'checked_in';
    }

    public function isCheckedOut()
    {
        return $this->status === 'checked_out';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
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
        return 'Rp' . number_format($this->total_price, 0, ',', '.');
    }
}