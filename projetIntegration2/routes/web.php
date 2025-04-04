<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\VerifierMembreClan;
use App\Http\Middleware\VerifierAdminClan;
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
use App\Http\Controllers\AmisController;
use App\Models\User;
use App\Events\PusherBroadcast;
use App\Http\Controllers\GraphiquePersoController;
use App\Http\Controllers\ObjectifController;

// pour quand il y a une erreur dans la navigation
Route::fallback(function () {
    return redirect()->route('profil.profil');
});

Route::get('/', function () {
    return redirect()->route('profil.profil');
});

Route::middleware([VerifierMembreClan::class])->group(function () {
    Route::GET('/clan/{id}', [ClanController::class, 'index'])->name('clan.montrer');
    Route::GET('/clan/{id}/canal/{canal}', [ClanController::class, 'showCanalClan'])->name('clan.canal');
});

Route::middleware([AuthMiddleware::class])->group(function () {
    Route::POST('/clan/creer', [ClanController::class, 'creerClan'])->name('clan.creer');
    Route::GET('/clan/invitation/{clan}', [ClanController::class, 'accepterInvitation'])->name('invitation.accepter');
    Route::delete('/messagesClan/{message}', [ClanController::class, 'destroy'])->middleware('auth')->name('messages.destroy');
    Route::GET('/conversations/{user}', [ConversationsController::class, 'show'])->name('conversations.show');
    Route::POST('/conversations/{user}', [ConversationsController::class, 'store']);
    Route::GET('/conversations', [ConversationsController::class, 'index'])->name('conversations.index');
    Route::GET('/modificationMessage', [ConversationsController::class, 'showModificationMessage'])->name('conversations.showModificationMessage');
    Route::delete('/messages/{message}', [ConversationsController::class, 'destroy'])->middleware('auth')->name('messages.destroy');
    Route::put('/messages/{id}', [ConversationsController::class, 'updateMessage'])->name('messages.update');
    Route::put('/messagesAmi/{id}', [ConversationsController::class, 'updateMessageAmi'])->name('messagesAmi.update');
    Route::GET('/classements', [ScoresController::class, 'meilleursGroupes'])->name('scores.meilleursGroupes');
});

Route::middleware([VerifierAdminClan::class])->group(function () {
    Route::GET('/clan/{id}/parametres/general', [ClanController::class, 'parametres'])->name('clan.parametres');
    Route::POST('/clan/{id}/action/canal', [ClanController::class, 'actionsCanal'])->name('canal.actions');
    Route::POST('/clan/{id}/parametres/general',  [ClanController::class, 'parametres'])->name('clan.parametres.post');
    Route::GET('/clan/{id}/parametres/canaux', [ClanController::class, 'parametres'])->name('clan.parametres.canaux');
    Route::POST('/clan/{id}/enregistrerCanaux', [ClanController::class, 'miseAJourCanaux'])->name('clan.miseAJour.canaux');
    Route::POST('/clan/{id}/enregistrerGeneral', [ClanController::class, 'miseAJourGeneral'])->name('clan.miseAJour.general');
    Route::GET('/clan/{id}/parametres/membres', [ClanController::class, 'parametres'])->name('clan.parametres.membres');
    Route::POST('/clan/{id}/enregistrerMembres', [ClanController::class, 'miseAJourMembres'])->name('clan.miseAJour.membres');
    Route::POST('/clan/{id}/supprimer', [ClanController::class, 'supprimer'])->name('clan.supprimer');
    Route::POST('/clan/{id}/televerser', [ClanController::class, 'televerserImage'])->name('clan.televerserImage');
});


Route::POST('/broadcastClan', [ClanController::class,'broadcastClan']);
Route::POST('/receiveClan', [ClanController::class,'receiveClan']);

Route::POST('/broadcast', [ConversationsController::class, 'broadcast']);
Route::POST('/receive', [ConversationsController::class, 'receive']);

Route::GET('/testClan/{clans}', [ConversationsController::class, 'showClan'])->name('conversations.showClan');
//Test Clan Message
//Route::POST('/broadcastClan', [ConversationsController::class,'broadcastClan']);
//Route::POST('/receiveClan', [ConversationsController::class,'receiveClan']);

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

Route::POST(
    '/deconnexion',
    [ProfilController::class, 'deconnexion']
)->name('profil.deconnexion');

Route::GET(
    '/confirmation/{codeVerification}',
    [ProfilController::class, 'confCourriel']
)->name('profil.confirmation');


Route::GET(
    '/profil',
    [ProfilController::class, 'profil']
)->name('profil.profil')->middleware('auth');

Route::GET(
    '/profil/modification',
    [ProfilController::class, 'modification']
)->name('profil.modification')->middleware('auth');

Route::delete(
    '/profil/suppressionProfil',
    [ProfilController::class, 'suppressionProfil']
)->name('profil.suppressionProfil')->middleware('auth');

Route::patch(
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
)->name('Statistique.index')->middleware('auth');

Route::GET(
    '/graphique',
    [StatistiqueController::class, 'graphique']
)->name('Statistique.graphique')->middleware('auth');

Route::GET(
    '/thermique',
    [StatistiqueController::class, 'thermique']
)->name('Statistique.thermique')->middleware('auth');

Route::GET(
    '/localisation',
    [GymController::class, 'index']
)->name('localisation.index')->middleware('auth');

Route::get(
    '/ajouterFoisGym',
    [StatistiqueController::class, 'ajouterFoisGym']
)->name('Statistique.ajouterFoisGym');

Route::GET(
    '/graphiqueExercice/{exercice}',
    [StatistiqueController::class, 'graphiqueExercice']
)->name('Statistique.graphiqueExercice')->middleware('auth');

Route::GET(
    '/thermique',
    [StatistiqueController::class, 'thermique']
)->name('Statistique.thermique')->middleware('auth');

Route::post('/statistique/storeThermique', [StatistiqueController::class, 'storeThermique'])->name('Statistique.storeThermique')->middleware('auth');

Route::post('/statistiques/save', [StatistiqueController::class, 'save'])->name('Statistiques.save')->middleware('auth');

Route::delete('/statistiques/{id}', [StatistiqueController::class, 'delete'])->name('Statistiques.delete')->middleware('auth');

Route::post('/statistiques/{id}/update-poids', [StatistiqueController::class, 'updatePoids'])->name('Statistiques.updatePoids')->middleware('auth');


Route::post('/ajouter-poids', [StatistiqueController::class, 'ajouterPoids'])->name('ajouter-poids')->middleware('auth');

Route::post('/ajouter-score/{exercice}', [StatistiqueController::class, 'ajouterScoreExercice'])->name('ajouter-score')->middleware('auth');



Route::GET(
    '/objectif',
    [ObjectifController::class, 'index']
)->name('Objectif.index')->middleware('auth');

Route::GET(
    '/objectif/ajouter',
    [ObjectifController::class, 'create']
)->name('Objectif.create')->middleware('auth');


Route::GET(
    '/objectif/edit/{id}',
    [ObjectifController::class, 'edit']
)->name('Objectif.edit')->middleware('auth');

Route::post(
    '/objectif',
    [ObjectifController::class, 'store']
)->name('Objectif.store')->middleware('auth');

Route::put(
    '/objectif/{id}',
    [ObjectifController::class, 'update']
)->name('Objectif.update')->middleware('auth');

Route::put(
    '/objectif/update/{id}',
    [ObjectifController::class, 'updateComplet']
)->name('Objectif.updateComplet')->middleware('auth');

Route::delete(
    '/objectif/{id}',
    [ObjectifController::class, 'destroy']
)->name('Objectif.destroy')->middleware('auth');


Route::GET(
    '/localisation',
    [GymController::class, 'index']
)->name('localisation.index');

Route::get('/export/top-users', [ScoresController::class, 'exportTopUsers'])->name('export.topUsers')->middleware('auth');
Route::get('/export/top-clans', [ScoresController::class, 'exportTopClans'])->name('export.topClans')->middleware('auth');
Route::get('/export/top-membres/{clanId}', [ScoresController::class, 'exportTopMembres'])->name('export.topMembres')->middleware('auth');
Route::get('/export/top-amelioration/{clanId}', [ScoresController::class, 'exportTopAmelioration'])->name('export.topAmelioration')->middleware('auth');
Route::get('/scores/view-graph', [App\Http\Controllers\ScoresController::class, 'viewScoreGraph'])
    ->name('scores.view-graph')->middleware('auth');
Route::get('/test-chart', [App\Http\Controllers\ScoresController::class, 'testChart'])
    ->name('test.chart')->middleware('auth');
Route::get('/scores/chart-page', [App\Http\Controllers\ScoresController::class, 'showChart'])
    ->name('scores.chart-page')->middleware('auth');

//Route pour l'ajout/recherche d'amis/clans
Route::get('amis', [AmisController::class, 'index'])->name('amis.index')->middleware('auth');
Route::match(['get', 'post'], 'amis/recherche', [AmisController::class, 'recherche'])->name('amis.recherche')->middleware('auth');
Route::post('amis/ajouter', [AmisController::class, 'ajouter'])->name('amis.ajouter')->middleware('auth');
Route::post('clans/recherche', [AmisController::class, 'rechercheClan'])->name('clans.recherche')->middleware('auth');

// Affichage de la liste des demandes d'amis
Route::get('amis/demandes', [AmisController::class, 'demandes'])->name('amis.demandes')->middleware('auth');

// Traitement de l'acceptation d'une demande d'ami
Route::post('amis/accepter', [AmisController::class, 'accepter'])->name('amis.accepter')->middleware('auth');

// Traitement du refus d'une demande d'ami
Route::post('amis/refuser', [AmisController::class, 'refuser'])->name('amis.refuser')->middleware('auth');

// Routes pour la recherche et la gestion des clans
Route::match(['get', 'post'], 'clans/recherche', [ClanController::class, 'rechercheClans'])->name('clans.recherche')->middleware('auth');
Route::post('clans/rejoindre', [ClanController::class, 'rejoindre'])->name('clans.rejoindre')->middleware('auth');

// Custom Graph Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/graphs/create', [GraphiquePersoController::class, 'create'])->name('graphs.create')->middleware('auth');
    Route::post('/graphs', [GraphiquePersoController::class, 'store'])->name('graphs.store')->middleware('auth');
    Route::get('/graphs', [GraphiquePersoController::class, 'index'])->name('graphs.index')->middleware('auth');
    Route::get('/graphs/{id}', [GraphiquePersoController::class, 'show'])->name('graphs.show')->middleware('auth');
    Route::get('/graphs/{id}/edit', [GraphiquePersoController::class, 'edit'])->name('graphs.edit')->middleware('auth');
    Route::put('/graphs/{id}', [GraphiquePersoController::class, 'update'])->name('graphs.update')->middleware('auth');
    Route::delete('/graphs/{id}', [GraphiquePersoController::class, 'destroy'])->name('graphs.delete')->middleware('auth');
});

// Add this to web.php if not already present
Route::post('/switch-language', function (\Illuminate\Http\Request $request) {
    $locale = $request->input('locale');
    if (in_array($locale, ['en', 'fr'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
        \Log::info('Switching language to: ' . $locale);
    }

    return response()->json(['success' => true, 'locale' => app()->getLocale(), 'session_locale' => session('locale')]);
})->name('switchLanguage');
