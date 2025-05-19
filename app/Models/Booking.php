<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'full_name',
        'email',
        'phone',
        'id_number',
        'check_in',
        'check_out',
        'guests',
        'total_price',
        'status',
        'billing_address',
        'billing_city',
        'billing_province',
        'billing_postal_code',
        'special_requests'
    ];

    protected $dates = ['check_in', 'check_out'];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'total_price' => 'decimal:2'
    ];

    // Nilai default untuk atribut
    protected $attributes = [
        'status' => 'pending',
    ];

    // Relasi ke model Room
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk pemesanan aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'confirmed')
                    ->where('check_out', '>=', now());
    }

    // Hitung jumlah malam menginap
    public function getNightsAttribute()
    {
        return $this->check_in->diffInDays($this->check_out);
    }

    // Format tanggal untuk tampilan
    public function getFormattedCheckInAttribute()
    {
        return $this->check_in->format('d M Y');
    }

    public function getFormattedCheckOutAttribute()
    {
        return $this->check_out->format('d M Y');
    }

    // Format harga untuk tampilan
    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp' . number_format($this->total_price, 0, ',', '.');
    }
}