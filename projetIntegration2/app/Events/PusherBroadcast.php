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
    public $idExpediteur;
    public $idDestinataire;
    public $supprime; 
    public $dernierId;
    public $photo;
    public $email;

    public function __construct($message, $idExpediteur, $idDestinataire, $supprime = false, $dernierId, $photo = null, $email)
    {
        $this->message = $message;
        $this->idExpediteur = $idExpediteur;
        $this->idDestinataire = $idDestinataire;
        $this->supprime = $supprime; 
        $this->dernierId = $dernierId;
        $this->photo = $photo;
        $this->email = $email;
    }

    public function broadcastOn()
    {
        
        $channelName = "chat-" . min($this->idExpediteur, $this->idDestinataire) . "-" . max($this->idExpediteur, $this->idDestinataire);
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
            'id_expediteur' => $this->idExpediteur,
            'id_destinataire' => $this->idDestinataire,
            'supprime' => $this->supprime,
            'dernier_id' => $this->dernierId,
            'photo' => $this->photo,
            'email' => $this->email
        ];
    }
}