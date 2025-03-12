<?php

namespace App\Repository;
use App\Models\User;
use App\Models\Message;
use App\Models\UtilisateurClan;


class ConversationsRepository{

    /**
     * @var User
     */

    private $user;
    /**
     * @var Message
     */

     private $message;
    public function __construct(User $user, Message $message){
        $this->user = $user;
        $this->message = $message;
    }


    //TODO: Changer pour la connextion de l'utilisateur
    //A ajouter where "user_id = auth()->id()"
    public function getConversations(){
        $conversation =  $this->user->newQuery()->
        select('email','id')
        ->get();
        
        $unread = $this->unreadCount(1);

        foreach($conversation as $conv){
            if(isset($unread[$conv->id])){
                $conv->unread = $unread[$conv->id];
            }else{ 
                $conv->unread = 0;
            }
        }
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


    public function getMessageFor($envoyeur,$receveur){
        return $this->message->newQuery()
        ->whereRaw("((idEnvoyer = $envoyeur AND idReceveur = $receveur) OR (idEnvoyer = $receveur AND idReceveur = $envoyeur ))")
        ->orderBy('created_at', 'ASC')
        ->with('from')->paginate(300);
    }
/**
 * 
 * @params int $UserId
 * @return \Illuminate\Support\Collection
 */
    private function unreadCount(int $UserId){
        return $this->message->newQuery()
        ->where('idReceveur', $UserId)
        ->groupBy('idEnvoyer')
        ->selectRaw('idEnvoyer, count(id) as count')
        ->get()
        ->pluck('count','idEnvoyer');
    }
}