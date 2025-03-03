<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Conversations;
use App\Http\Controllers\ProfilController;

Route::post('/login', [ProfilController::class, 'connexion']);

Route::group([], function () {
    // List all conversations for the authenticated user
    Route::get('/conversations', [Conversations::class, 'index']);
    
    // Show messages between the authenticated user and a given user
    Route::get('/conversations/{user}', [Conversations::class, 'show']);
    
    // Send a message to a given user
    Route::post('/conversations/{user}', [Conversations::class, 'store']);
});