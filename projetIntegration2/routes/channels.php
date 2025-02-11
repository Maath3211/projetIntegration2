<?php

use Illuminate\Support\Facades\Broadcast;

//Utile pour les canaux privés, pas important pour notre projet
/*
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
*/
/*
Broadcast::channel('private-chat-{user1}-{user2}', function ($user, $user1, $user2) {
    return in_array($user->id, [$user1, $user2]); // Seuls ces 2 users peuvent rejoindre le canal
});
*/


