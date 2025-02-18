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

class MessageGroup implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $senderId;
    public $groupId;
    public $deleted; // Ajout du flag de suppression
    public $lastId;


    /**
     * Create a new event instance.
     */
    public function __construct($message,$senderId, $groupId, $deleted = false, $lastId)
    {
        $this->message = $message;
        $this->senderId = $senderId;
        $this->groupId = $groupId;
        $this->deleted = $deleted; // Défaut à false
        $this->lastId = $lastId;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channelName = "chat-" . $this->groupId;
        return [new Channel($channelName)];

    }

    public function broadcastAs(): string
    {
        return 'event-group';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'sender_id' => $this->senderId,
            'group_id' => $this->groupId,
            'deleted' => $this->deleted,
            'last_id' => $this->lastId
        ];
    }


}
