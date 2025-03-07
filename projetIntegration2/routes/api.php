<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Conversations;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ClanController;

// Define all your routes without middleware groups
Route::post('/login', [ProfilController::class, 'connexion']);
Route::get('/conversations', [Conversations::class, 'index']);
Route::get('/conversations/{user}', [Conversations::class, 'show']);
Route::post('/conversations/{user}', [Conversations::class, 'store']);
Route::get('/clans', [ClanController::class, 'getUserClans'])->middleware('auth:sanctum');

// Handle OPTIONS requests
Route::options('/{any}', function () {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', 'http://localhost:8081')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-XSRF-TOKEN');
})->where('any', '.*');