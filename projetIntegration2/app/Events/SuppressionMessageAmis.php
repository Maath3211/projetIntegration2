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

    public $idMessage;
    public $idReceveur;
    public $idEnvoyeur;

    public function __construct($idMessage, $idReceveur, $idEnvoyeur)
    {
        $this->idMessage = $idMessage;
        $this->idReceveur = $idReceveur;
        $this->idEnvoyeur = $idEnvoyeur;

        Log::info('SuppressionMessageAmis event created', [
            'idMessage' => $this->idMessage,
            'idReceveur' => $this->idReceveur,
            'idEnvoyeur' => $this->idEnvoyeur,
        ]);
    }

    public function broadcastOn(): array
    {
        $channel = new Channel("chat-" . min($this->idReceveur, $this->idEnvoyeur) . "-" . max($this->idEnvoyeur, $this->idReceveur));
        //Log::info('Broadcasting on channel', ['channel' => $channel->name]);
        return [$channel];
    }

    public function broadcastAs(): string
    {
        $eventName = 'message-supprime-ami';
        //Log::info('Broadcasting as event', ['event' => $eventName]);
        return $eventName;
    }

    public function broadcastWith(): array
    {
        $data = [
            'idMessage' => $this->idMessage,
        ];
        //Log::info('Broadcasting with data', $data);
        return $data;
    }
}
