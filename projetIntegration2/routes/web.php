<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserCommunication;

Route::get('/', function () {
    return view('welcome');
});


Route::GET('/yup',
[UserCommunication::class,'index'])->name('user.index');


