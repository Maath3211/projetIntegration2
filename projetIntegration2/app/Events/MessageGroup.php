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
    public $idExpediteur;
    public $idGroupe;
    public $idCanal;
    public $supprime; // Ajout du flag de suppression
    public $dernierId;
    public $photo;
    public $email;

    /**
     * Crée une nouvelle instance de l'événement.
     */
    public function __construct($message, $idExpediteur, $idGroupe, $idCanal, $supprime = false, $dernierId, $photo = null, $email)
    {
        $this->message = $message;
        $this->idExpediteur = $idExpediteur;
        $this->idGroupe = $idGroupe;
        $this->idCanal = $idCanal;
        $this->supprime = $supprime; // Défaut à false
        $this->dernierId = $dernierId;
        $this->photo = $photo;
        $this->email = $email;
    }

    /**
     * Obtenez les canaux sur lesquels l'événement doit être diffusé.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $nomCanal = "chat-" . $this->idGroupe . "-" . $this->idCanal;
        return [new Channel($nomCanal)];
    }

    public function broadcastAs(): string
    {
        return 'event-group';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'id_expediteur' => $this->idExpediteur,
            'id_groupe' => $this->idGroupe,
            'id_canal' => $this->idCanal,
            'supprime' => $this->supprime,
            'dernier_id' => $this->dernierId,
            'photo' => $this->photo,
            'email' => $this->email
        ];
    }
}
