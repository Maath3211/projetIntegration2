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
use App\Http\Controllers\ObjectifController;

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
[ProfilController::class,'profil'])->name('profil.profil')->middleware('auth');

Route::GET('/profil/modification',
[ProfilController::class,'pageModification'])->name('profil.pageModification')->middleware('auth');

Route::GET('/stats',
[StatistiqueController::class,'index'])->name('statistique.index');

Route::GET('/graphique',
[StatistiqueController::class,'graphique'])->name('statistique.graphique');

Route::GET('/graphiqueExercice/{exercice}',
[StatistiqueController::class,'graphiqueExercice'])->name('statistique.graphiqueExercice');

Route::GET('/thermique',
[StatistiqueController::class,'thermique'])->name('statistique.thermique');

Route::post('/statistique/storeThermique', [StatistiqueController::class, 'storeThermique'])->name('statistique.storeThermique');

Route::post('/statistiques/save', [StatistiqueController::class, 'save'])->name('statistiques.save');

Route::delete('/statistiques/{id}', [StatistiqueController::class, 'delete'])->name('statistiques.delete');

Route::post('/statistiques/{id}/update-poids', [StatistiqueController::class, 'updatePoids'])->name('statistiques.updatePoids');


Route::post('/ajouter-poids', [StatistiqueController::class, 'ajouterPoids'])->name('ajouter-poids');

Route::post('/ajouter-score/{exercice}', [StatistiqueController::class, 'ajouterScoreExercice'])->name('ajouter-score');



Route::GET('/objectif',
[ObjectifController::class,'index'])->name('objectif.index');

Route::GET('/objectif/ajouter',
[ObjectifController::class,'create'])->name('objectif.create');


Route::GET('/objectif/edit/{id}',
[ObjectifController::class,'edit'])->name('objectif.edit');

Route::post('/objectif',
 [ObjectifController::class, 'store'])->name('objectif.store');

Route::put('/objectif/{id}',
 [ObjectifController::class, 'update'])->name('objectif.update');

Route::delete('/objectif/{id}',
 [ObjectifController::class, 'destroy'])->name('objectif.destroy');


Route::GET('/localisation', 
[GymController::class, 'index'])->name('localisation.index');
Route::get('/export/top-users', [ScoresController::class, 'exportTopUsers'])->name('export.topUsers');
Route::get('/export/top-clans', [ScoresController::class, 'exportTopClans'])->name('export.topClans');
