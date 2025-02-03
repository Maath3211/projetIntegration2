<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserCommunication;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\Conversations;
use App\Http\Controllers\StatistiqueController;

Route::get('/', function () {
    return view('welcome');
});


Route::GET('/yup',
[UserCommunication::class,'index'])->name('user.index');

Route::GET('/conversations',
[Conversations::class,'index'])->name('conversations');

Route::GET('/conversations/{user}',
[Conversations::class,'show'])->name('conversations.show');

Route::post('/conversations/{user}',
[Conversations::class,'store']);


Route::GET('/connexion',
[ProfilController::class,'index'])->name('profil.pageConnexion');

Route::POST('/connexion',
[ProfilController::class,'connexion'])->name('profil.connexion');

Route::GET('/stats',
[StatistiqueController::class,'index'])->name('statistique.index');