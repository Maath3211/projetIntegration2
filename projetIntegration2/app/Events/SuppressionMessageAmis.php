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
use Illuminate\Support\Facades\Log;

class SuppressionMessageAmis implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageId;
    public $receveurId;
    public $envoyeurId;

    public function __construct($messageId, $receveurId, $envoyeurId)
    {
        $this->messageId = $messageId;
        $this->receveurId = $receveurId;
        $this->envoyeurId = $envoyeurId;

        Log::info('SuppressionMessageAmis event created', [
            'messageId' => $this->messageId,
            'receveurId' => $this->receveurId,
            'envoyeurId' => $this->envoyeurId,
        ]);
    }

    public function broadcastOn(): array
    {
        $channel = new Channel("chat-" . min($this->receveurId, $this->envoyeurId) . "-" . max($this->envoyeurId, $this->receveurId));
        Log::info('Broadcasting on channel', ['channel' => $channel->name]);
        return [$channel];
    }

    public function broadcastAs(): string
    {
        $eventName = 'message-deleted-ami';
        Log::info('Broadcasting as event', ['event' => $eventName]);
        return $eventName;
    }

    public function broadcastWith(): array
    {
        $data = [
            'messageId' => $this->messageId,
        ];
        Log::info('Broadcasting with data', $data);
        return $data;
    }
}
