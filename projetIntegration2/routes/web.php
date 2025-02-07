<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserCommunication;
use App\Http\Controllers\Conversations;
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

//TEST TUTO
Route::GET('/userRegistration', function() {return view('conversations.userRegistration');});
Route::POST('/userRegistration', function() {
    $name = request()->input('name');
    event(new PusherBroadcast($name));


});


