<?php

namespace App\Repository;
use App\Models\User;
use App\Models\Message;
use App\Models\UtilisateurClan;


class ConversationsClan{

    /**
     * @var User
     */

    private $user;
    /**
     * @var UtilisateurClan
     */

     private $message;
    public function __construct(User $user, UtilisateurClan $message){
        $this->user = $user;
        $this->message = $message;
    }

    public function getConversationsClan(){
        $conversation =  $this->user->newQuery()->
        select('email','id')
        ->get();
        /*
        $unread = $this->unreadCount(1);

        foreach($conversation as $conv){
            if(isset($unread[$conv->id])){
                $conv->unread = $unread[$conv->id];
            }else{ 
                $conv->unread = 0;
            }
        } */
        return $conversation;
    }

    public function createMessage(string $content, int $envoyeur, int $receveur){
        return $this->message->newQuery()->create([
            'message' => $content,
            'idEnvoyer' => $envoyeur,
            'idReceveur' => $receveur,
            'created_at' => now()
        ]);

    }


    public function getMessageClanFor($clanId)
    {
        return $this->message->newQuery()
            ->where('idClan', $clanId) // On récupère tous les messages du clan
            ->orderBy('created_at', 'ASC')
            ->with([
                'user' => function($query) { // Assurez-vous que 'sender' est bien défini dans le modèle Message
                    return $query->select('id', 'email');
                }
            ])->paginate(300);
    }
    
/**
 * 
 * @params int $UserId
 * @return \Illuminate\Support\Collection
 */
/*
    private function unreadCount(int $UserId){
        return $this->message->newQuery()
        ->where('idReceveur', $UserId)
        ->groupBy('idEnvoyer')
        ->selectRaw('idEnvoyer, count(id) as count')
        ->get()
        ->pluck('count','idEnvoyer');
    }
        */
}