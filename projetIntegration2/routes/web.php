<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserCommunication;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ScoresController;
use App\Http\Controllers\Conversations;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\ClanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GymController;

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
Route::get('/export/top-membres/{clanId}', [ScoresController::class, 'exportTopMembres'])->name('export.topMembres');
Route::get('/export/top-amelioration/{clanId}', [ScoresController::class, 'exportTopAmelioration'])->name('export.topAmelioration');
