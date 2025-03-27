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


    public $idMessage;
    public $idGroupe;
    public $canal;


    public function __construct($idMessage, $idGroupe, $canal)
    {
        $this->idMessage = $idMessage;
        $this->idGroupe = $idGroupe;
        $this->canal = $canal;
    }


    public function broadcastOn(): array
    {
        \Log::info('Diffusion sur le canal :', ["chat-" . $this->idGroupe . "-" . $this->canal]);
        return [new Channel("chat-" . $this->idGroupe . "-" . $this->canal)];
    }

    public function broadcastAs(): string
    {
        return 'message-supression'; // Utiliser un événement spécifique pour la suppression
    }

    public function broadcastWith(): array
    {
        return [
            'idMessage' => $this->idMessage,
        ];
    }
}
