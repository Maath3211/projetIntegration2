<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserCommunication;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ScoresController;
use App\Http\Controllers\Conversations;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\GymController;
use App\Models\User;
use App\Events\PusherBroadcast;

Route::get('/', function () {
    return view('welcome');
});

Route::GET('/yup', [UserCommunication::class,'index'])->name('user.index');

Route::GET('/conversations/{user}', [Conversations::class,'show'])->name('conversations.show');
Route::POST('/conversations/{user}', [Conversations::class,'store']);
Route::POST('/broadcast', [Conversations::class,'broadcast']);
Route::POST('/receive', [Conversations::class,'receive']);
Route::GET('/conversations', [Conversations::class,'index'])->name('conversations');



Route::GET('/connexion',
[ProfilController::class,'index'])->name('profil.pageConnexion');

Route::POST('/connexion',
[ProfilController::class,'connexion'])->name('profil.connexion');

Route::GET('/meilleursGroupes',
[ScoresController::class,'meilleursGroupes'])->name('scores.meilleursGroupes');
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

Route::GET('/localisation', 
[GymController::class, 'index'])->name('localisation.index');
Route::get('/export/top-users', [ScoresController::class, 'exportTopUsers'])->name('export.topUsers');
Route::get('/export/top-clans', [ScoresController::class, 'exportTopClans'])->name('export.topClans');
