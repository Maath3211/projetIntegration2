<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('private-chat-{id1}-{id2}', function ($user, $id1, $id2) {
    return $user->id == $id1 || $user->id == $id2;
});
