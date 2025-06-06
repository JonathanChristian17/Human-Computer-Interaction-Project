<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $roomId;
    public $status;
    public $message;

    public function __construct(int $roomId, string $status)
    {
        $this->roomId = $roomId;
        $this->status = $status;
        $this->message = "Room status has been updated to {$status}";
    }

    public function broadcastOn()
    {
        return new Channel('rooms');
    }

    public function broadcastAs()
    {
        return 'room.status.changed';
    }

    public function broadcastWith()
    {
        $statusMessages = [
            'available' => 'Tersedia',
            'maintenance' => 'Dalam Perbaikan'
        ];

        $statusMessage = $statusMessages[$this->status] ?? 'Tidak Tersedia';

        return [
            'room_id' => $this->roomId,
            'status' => $this->status,
            'status_text' => $statusMessage,
            'message' => "Status kamar telah diperbarui menjadi {$statusMessage}",
            'timestamp' => now()->toISOString()
        ];
    }
} 