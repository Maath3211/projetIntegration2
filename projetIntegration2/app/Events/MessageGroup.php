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
    public $canalId;
    public $deleted; // Ajout du flag de suppression
    public $lastId;
    public $photo;


    /**
     * Create a new event instance.
     */
    public function __construct($message, $senderId, $groupId, $canalId ,$deleted = false, $lastId, $photo = null)
    {
        $this->message = $message;
        $this->senderId = $senderId;
        $this->groupId = $groupId;
        $this->canalId = $canalId;
        $this->deleted = $deleted; // Défaut à false
        $this->lastId = $lastId;
        $this->photo = $photo;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channelName = "chat-" . $this->groupId . "-" . $this->canalId;
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
            'canal_id' => $this->canalId,
            'deleted' => $this->deleted,
            'last_id' => $this->lastId,
            'photo' => $this->photo
        ];
    }
}
