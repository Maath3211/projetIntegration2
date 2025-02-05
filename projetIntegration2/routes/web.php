<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserCommunication;
use App\Http\Controllers\Conversations;

Route::get('/', function () {
    return view('welcome');
});


Route::GET('/yup',
[UserCommunication::class,'index'])->name('user.index');



Route::GET('/conversations/{user}',
[Conversations::class,'show'])->name('conversations.show');

Route::post('/conversations/{user}',
[Conversations::class,'store']);

Route::post('/broadcast',
[Conversations::class,'broadcast']);
Route::post('/receive',
[Conversations::class,'receive']);
Route::GET('/conversations',
[Conversations::class,'index'])->name('conversations');


