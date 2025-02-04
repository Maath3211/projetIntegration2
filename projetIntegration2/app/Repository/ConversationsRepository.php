<?php

namespace App\Repository;
use App\Models\User;
use App\Models\Message;


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
            'envoyeur_id' => $envoyeur,
            'receveur_id' => $receveur,
            'created_at' => now()
        ]);

    }


    public function getMessageFor($envoyeur,$receveur){
        return $this->message->newQuery()
        ->whereRaw("((envoyeur_id = $envoyeur AND receveur_id = $receveur) OR (envoyeur_id = $receveur AND receveur_id = $envoyeur ))")
        ->orderBy('created_at', 'ASC')
        ->with([
            'from' => function($query){return $query->select('email','id');}
        ]);
    }
/**
 * 
 * @params int $UserId
 * @return \Illuminate\Support\Collection
 */
    private function unreadCount(int $UserId){
        return $this->message->newQuery()
        ->where('receveur_id', $UserId)
        ->groupBy('envoyeur_id')
        ->selectRaw('envoyeur_id, count(id) as count')
        ->whereRaw('read_at IS NULL')
        ->get()
        ->pluck('count','envoyeur_id');
    }
}