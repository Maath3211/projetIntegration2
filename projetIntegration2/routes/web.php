<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserCommunication;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ScoresController;

Route::get('/', function () {
    return view('welcome');
});


Route::GET('/yup',
[UserCommunication::class,'index'])->name('user.index');


Route::GET('/connexion',
[ProfilController::class,'index'])->name('profil.pageConnexion');

Route::POST('/connexion',
[ProfilController::class,'connexion'])->name('profil.connexion');

Route::GET('/meilleursGroupes',
[ScoresController::class,'meilleursGroupes'])->name('scores.meilleursGroupes');