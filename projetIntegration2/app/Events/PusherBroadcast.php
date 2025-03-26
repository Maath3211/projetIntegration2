<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Models\User;

class PusherBroadcast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $senderId;
    public $receiverId;
    public $deleted; // Ajout du flag de suppression
    public $lastId;
    public $photo;
    public $email;

    public function __construct($message, $senderId, $receiverId, $deleted = false, $lastId, $photo = null, $email)
    {
        $this->message = $message;
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
        $this->deleted = $deleted; // Défaut à false
        $this->lastId = $lastId;
        $this->photo = $photo;
        $this->email = $email;
    }

    public function broadcastOn()
    {
        // Canal unique pour chaque conversation
        $channelName = "chat-" . min($this->senderId, $this->receiverId) . "-" . max($this->senderId, $this->receiverId);
        return new Channel($channelName);
    }

    public function broadcastAs(): string
    {
        return 'mon-event';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'sender_id' => $this->senderId,
            'receiver_id' => $this->receiverId,
            'deleted' => $this->deleted,
            'last_id' => $this->lastId,
            'photo' => $this->photo,
            'email' => $this->email
        ];
    }
}