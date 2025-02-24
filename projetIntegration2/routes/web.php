<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserCommunication;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ScoresController;
use App\Http\Controllers\Conversations;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\AmisController;
use App\Models\User;
use App\Events\PusherBroadcast;


Route::get('/', function () {
    return view('welcome');
});

Route::GET('/clan/{id}', 
[ClanController::class, 'index'])->name('clan.montrer');

Route::GET('/clan/{id}/parametres',
[ClanController::class, 'parametres'])->name('clan.parametres');

Route::POST('/clan/{id}/parametres', 
[ClanController::class, 'parametres'])->name('clan.parametres.post');

Route::POST('/clan/{id}/televerser',
[ClanController::class, 'televerserImage'])->name('clan.televerserImage');




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

Route::GET('/creerCompte',
[ProfilController::class,'creerCompte'])->name('profil.creerCompte');

Route::POST('/creerCompte',
[ProfilController::class,'storeCreerCompte'])->name('profil.storeCreerCompte');

Route::GET('/meilleursGroupes',
[ScoresController::class,'meilleursGroupes'])->name('scores.meilleursGroupes');
Route::POST('/deconnexion',
[ProfilController::class,'deconnexion'])->name('profil.deconnexion');

Route::GET('/profil',
[ProfilController::class,'profil'])->name('profil.profil')->middleware('auth');

Route::GET('/profil/modification',
[ProfilController::class,'modification'])->name('profil.modification')->middleware('auth');

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

//Route pour l'ajout/recherche d'amis/clans
Route::get('amis', [AmisController::class, 'index'])->name('amis.index');
Route::match(['get','post'], 'amis/recherche', [AmisController::class, 'recherche'])->name('amis.recherche');
Route::post('amis/ajouter', [AmisController::class, 'ajouter'])->name('amis.ajouter');
Route::post('clans/recherche', [AmisController::class, 'rechercheClan'])->name('clans.recherche');

// Affichage de la liste des demandes d'amis
Route::get('amis/demandes', [AmisController::class, 'demandes'])->name('amis.demandes');

// Traitement de l'acceptation d'une demande d'ami
Route::post('amis/accepter', [AmisController::class, 'accepter'])->name('amis.accepter');

// Traitement du refus d'une demande d'ami
Route::post('amis/refuser', [AmisController::class, 'refuser'])->name('amis.refuser');