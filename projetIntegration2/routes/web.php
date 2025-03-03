<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserCommunication;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ScoresController;
use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\ClanController;
use App\Http\Controllers\TraductionController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Events\PusherBroadcast;

Route::get('/', function () {
    return redirect()->route('profil.pageConnexion');
});
Route::post('/switch-language', function (\Illuminate\Http\Request $request) {
    $locale = $request->input('locale');
    if (in_array($locale, ['en', 'fr'])) {
        \Illuminate\Support\Facades\Session::put('locale', $locale);
        \Illuminate\Support\Facades\App::setLocale($locale);
        Log::info('Switching language to: ' . $locale);
    }
    return redirect()->back();
});

Route::GET(
    '/clan/{id}',
    [ClanController::class, 'index']
)->name('clan.montrer');

Route::GET(
    '/clan/{id}/parametres/general',
    [ClanController::class, 'parametres']
)->name('clan.parametres');

Route::POST(
    '/clan/{id}/action/canal',
    [ClanController::class, 'actionsCanal']
)->name('canal.actions');

Route::POST(
    '/clan/{id}/parametres/general',
    [ClanController::class, 'parametres']
)->name('clan.parametres.post');

Route::POST(
    '/clan/{id}/enregistrerGeneral',
    [ClanController::class, 'miseAJourGeneral']
)->name('clan.miseAJour.general');

Route::GET(
    '/clan/{id}/parametres/canaux',
    [ClanController::class, 'parametres']
)->name('clan.parametres.canaux');

Route::POST(
    '/clan/{id}/enregistrerCanaux',
    [ClanController::class, 'miseAJourCanaux']
)->name('clan.miseAJour.canaux');

Route::POST(
    '/clan/{id}/televerser',
    [ClanController::class, 'televerserImage']
)->name('clan.televerserImage');
Route::GET(
    '/clan/{id}/parametres',
    [ClanController::class, 'parametres']
)->name('clan.parametres');

Route::POST(
    '/clan/{id}/parametres',
    [ClanController::class, 'parametres']
)->name('clan.parametres.post');

Route::POST(
    '/clan/{id}/televerser',
    [ClanController::class, 'televerserImage']
)->name('clan.televerserImage');


Route::GET('/yup', [UserCommunication::class, 'index'])->name('user.index');

Route::GET('/conversations/{user}', [ConversationsController::class, 'show'])->name('conversations.show');
Route::POST('/conversations/{user}', [ConversationsController::class, 'store']);
Route::POST('/broadcast', [ConversationsController::class, 'broadcast']);
Route::POST('/receive', [ConversationsController::class, 'receive']);
Route::GET('/conversations', [ConversationsController::class, 'index'])->name('conversations');

Route::GET('/testClan/{clans}', [ConversationsController::class, 'showClan'])->name('conversations.showClan');
Route::POST('/broadcastClan', [ConversationsController::class, 'broadcastClan']);
Route::POST('/receiveClan', [ConversationsController::class, 'receiveClan']);
Route::GET('/modificationMessage', [ConversationsController::class, 'showModificationMessage'])->name('conversations.showModificationMessage');

Route::delete('/messages/{message}', [ConversationsController::class, 'destroy'])->middleware('auth')->name('messages.destroy');
Route::put('/messages/{id}', [ConversationsController::class, 'updateMessage'])->name('messages.update');





Route::GET(
    '/connexion',
    [ProfilController::class, 'index']
)->name('profil.pageConnexion');

Route::POST(
    '/connexion',
    [ProfilController::class, 'connexion']
)->name('profil.connexion');

Route::GET(
    '/auth/google',
    [ProfilController::class, 'connexionGoogle']
)->name('profil.connexionGoogle');

Route::GET(
    '/auth/google/callback',
    [ProfilController::class, 'googleCallback']
)->name('profil.googleCallback');

Route::GET(
    '/creerCompteGoogle',
    [ProfilController::class, 'creerCompteGoogle']
)->name('profil.creerCompteGoogle');

Route::POST(
    '/creerCompteGoogle',
    [ProfilController::class, 'storeCreerCompteGoogle']
)->name('profil.storeCreerCompteGoogle');

Route::GET(
    '/creerCompte',
    [ProfilController::class, 'creerCompte']
)->name('profil.creerCompte');

Route::POST(
    '/creerCompte',
    [ProfilController::class, 'storeCreerCompte']
)->name('profil.storeCreerCompte');

Route::GET(
    '/meilleursGroupes',
    [ScoresController::class, 'meilleursGroupes']
)->name('scores.meilleursGroupes');

Route::POST(
    '/deconnexion',
    [ProfilController::class, 'deconnexion']
)->name('profil.deconnexion');

Route::GET(
    '/confirmation/{codeVerification}',
    [ProfilController::class, 'confCourriel']
)->name('profil.confirmation');

Route::GET(
    '/meilleursGroupes',
    [ScoresController::class, 'meilleursGroupes']
)->name('scores.meilleursGroupes');
Route::POST(
    '/deconnexion',
    [ProfilController::class, 'deconnexion']
)->name('profil.deconnexion');

Route::GET(
    '/profil',
    [ProfilController::class, 'profil']
)->name('profil.profil')->middleware('auth');

Route::GET(
    '/profil/modification',
    [ProfilController::class, 'modification']
)->name('profil.modification')->middleware('auth');

Route::POST(
    '/profil/modification/update',
    [ProfilController::class, 'updateModification']
)->name('profil.updateModification')->middleware('auth');

Route::GET(
    '/profil/{email}',
    [ProfilController::class, 'profilPublic']
)->name('profil.profilPublic')->middleware('auth');

Route::GET(
    '/reinitialisation',
    [ProfilController::class, 'pageMotDePasseOublie']
)->name('profil.reinitialisation');

Route::post(
    '/reinitialisation',
    [ProfilController::class, 'motDePasseOublieEmail']
)->name('profil.motDePasseOublieEmail');

Route::get(
    '/reinitialisation/{token}',
    [ProfilController::class, 'showResetPasswordForm']
)->name('profil.reinitialisation.token');

Route::post(
    '/reinitialisationMDP',
    [ProfilController::class, 'resetPassword']
)->name('profil.resetPassword');

Route::GET(
    '/stats',
    [StatistiqueController::class, 'index']
)->name('statistique.index');

Route::GET(
    '/graphique',
    [StatistiqueController::class, 'graphique']
)->name('statistique.graphique');

Route::GET(
    '/thermique',
    [StatistiqueController::class, 'thermique']
)->name('statistique.thermique');

Route::GET(
    '/localisation',
    [GymController::class, 'index']
)->name('localisation.index');

Route::get('/export/top-users', [ScoresController::class, 'exportTopUsers'])->name('export.topUsers');
Route::get('/export/top-clans', [ScoresController::class, 'exportTopClans'])->name('export.topClans');
Route::get('/export/top-membres/{clanId}', [ScoresController::class, 'exportTopMembres'])->name('export.topMembres');
Route::get('/export/top-amelioration/{clanId}', [ScoresController::class, 'exportTopAmelioration'])->name('export.topAmelioration');
Route::get('/scores/view-graph', [App\Http\Controllers\ScoresController::class, 'viewScoreGraph'])
    ->name('scores.view-graph');
Route::get('/test-chart', [App\Http\Controllers\ScoresController::class, 'testChart'])
    ->name('test.chart');
Route::get('/scores/chart-page', [App\Http\Controllers\ScoresController::class, 'showChart'])
    ->name('scores.chart-page');
