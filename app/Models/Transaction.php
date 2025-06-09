<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'raw_response',
        'is_deposit',
        'payment_deadline'
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'raw_response' => 'json',
        'payment_deadline' => 'datetime',
        'transaction_time' => 'datetime',
        'is_deposit' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
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

    protected static function booted()
    {
        static::creating(function ($transaction) {
            // Set payment deadline to 1 hour from now when creating a new transaction
            if (!$transaction->payment_deadline) {
                $transaction->payment_deadline = Carbon::now()->addHour();
            }
        });
    }

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

    public function getRemainingTimeAttribute()
    {
        if (!$this->payment_deadline || !$this->isPending()) {
            return null;
        }

        $now = Carbon::now();
        if ($now->gt($this->payment_deadline)) {
            return 0;
        }

        return $now->diffInSeconds($this->payment_deadline);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->timezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }
} 