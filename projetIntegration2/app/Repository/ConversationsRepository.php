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


    public function getConversations(){
        return $this->user->newQuery()->
        select('email','id')
        ->get();
        
    }

    public function createMessage(string $content, int $envoyeur, int $receveur){
        return $this->message->newQuery()->create([
            'message' => $content,
            'envoyeur_id' => $envoyeur,
            'receveur_id' => $receveur,
            'created_at' => now()
        ]);

    }
}