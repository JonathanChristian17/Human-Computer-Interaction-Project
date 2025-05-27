<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'order_id',
        'gross_amount',
        'payment_type',
        'transaction_id',
        'transaction_status',
        'payment_status',
        'transaction_time',
        'payment_code',
        'pdf_url',
        'raw_response'
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'raw_response' => 'json'
    ];

    protected $attributes = [
        'transaction_status' => 'pending',
        'payment_status' => 'pending'
    ];

    // Add status constants
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_SETTLEMENT = 'settlement';
    const STATUS_CHALLENGE = 'challenge';
    const STATUS_DENY = 'deny';
    const STATUS_CANCEL = 'cancel';
    const STATUS_EXPIRE = 'expire';
    const STATUS_REFUND = 'refund';

    // Add payment status constants
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_CANCELLED = 'cancelled';
    const PAYMENT_REFUNDED = 'refunded';

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function isPending()
    {
        return $this->transaction_status === self::STATUS_PENDING;
    }

    public function isSuccess()
    {
        return $this->transaction_status === self::STATUS_SUCCESS;
    }

    public function isSettlement()
    {
        return $this->transaction_status === self::STATUS_SETTLEMENT;
    }

    public function isChallenge()
    {
        return $this->transaction_status === self::STATUS_CHALLENGE;
    }

    public function isDenied()
    {
        return $this->transaction_status === self::STATUS_DENY;
    }

    public function isCancelled()
    {
        return $this->transaction_status === self::STATUS_CANCEL;
    }

    public function isExpired()
    {
        return $this->transaction_status === self::STATUS_EXPIRE;
    }

    public function isRefunded()
    {
        return $this->transaction_status === self::STATUS_REFUND;
    }
} 