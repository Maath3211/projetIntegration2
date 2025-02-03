<?php

namespace App\Repository;
use App\Models\User;


class ConversationsRepository{

    /**
     * @var User
     */

    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }


    public function getConversations(){
        return $this->user->newQuery()->
        select('email','id')
        ->get();
        
    }
}