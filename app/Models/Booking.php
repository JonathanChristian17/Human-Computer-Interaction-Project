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
        'check_in',
        'check_out',
        'guests',
    ];

    protected $dates = ['check_in', 'check_out'];

    protected $casts = [
    'check_in' => 'datetime',
    'check_out' => 'datetime',
];


    // Relasi (optional)
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
