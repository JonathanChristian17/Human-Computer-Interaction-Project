<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $roomIds;
    public $message;

    public function __construct(array $roomIds, string $message = 'Booking status has changed')
    {
        $this->roomIds = $roomIds;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('bookings');
    }

    public function broadcastAs()
    {
        return 'booking.status.changed';
    }

    public function broadcastWith()
    {
        return [
            'room_ids' => $this->roomIds,
            'message' => $this->message,
            'timestamp' => now()->toISOString()
        ];
    }
} 