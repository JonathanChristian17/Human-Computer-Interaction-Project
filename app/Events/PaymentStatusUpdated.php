<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaction;
    public $transactionData;

    /**
     * Create a new event instance.
     */
    public function __construct($transaction)
    {
        if ($transaction instanceof Transaction) {
            $this->transaction = $transaction;
            $this->transactionData = [
                'transaction_id' => $transaction->id,
                'order_id' => $transaction->order_id,
                'transaction_status' => $transaction->transaction_status,
                'payment_status' => $transaction->payment_status,
                'booking_id' => $transaction->booking_id,
            ];
        } else {
            $this->transactionData = $transaction;
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('payments'),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return $this->transactionData;
    }
} 