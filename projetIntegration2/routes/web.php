<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserCommunication;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\Conversations;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\ClanController;

Route::get('/', function () {
    return view('welcome');
});


Route::GET('/clan/{id}', 
[ClanController::class, 'index'])->name('clan.montrer');

Route::GET('/clan/{id}/parametres',
[ClanController::class, 'parametres'])->name('clan.parametres');

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

Route::POST('/deconnexion',
[ProfilController::class,'deconnexion'])->name('profil.deconnexion');

Route::GET('/profil',
[ProfilController::class,'profil'])->name('profil.profil');

Route::GET('/profil/modification',
[ProfilController::class,'pageModification'])->name('profil.pageModification');

Route::GET('/stats',
[StatistiqueController::class,'index'])->name('statistique.index');


Route::GET('/graphique',
[StatistiqueController::class,'graphique'])->name('statistique.graphique');


Route::GET('/thermique',
[StatistiqueController::class,'thermique'])->name('statistique.thermique');
