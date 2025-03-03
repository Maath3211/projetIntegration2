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


class SuppressionMessageGroup implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $messageId;
    public $groupId;

    public function __construct($messageId, $groupId)
    {
        $this->messageId = $messageId;
        $this->groupId = $groupId;
    }

    public function broadcastOn(): array
    {
        return [new Channel("chat-" . $this->groupId)];
    }

    public function broadcastAs(): string
    {
        return 'message-deleted'; // Utiliser un événement spécifique pour la suppression
    }

    public function broadcastWith(): array
    {
        return [
            'messageId' => $this->messageId,
        ];
    }
}
