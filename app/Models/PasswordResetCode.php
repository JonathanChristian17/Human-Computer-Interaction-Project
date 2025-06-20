<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetCode extends Model
{
    protected $fillable = [
        'email',
        'code',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function isValid()
    {
        return !$this->isExpired();
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }
}