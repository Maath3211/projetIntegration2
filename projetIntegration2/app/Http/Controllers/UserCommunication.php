<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;

class UserCommunication extends Controller
{
    //
    public function index()
    {
        $userId = DB::table('users')->where('id', '1')->first();
        $yup = DB::table('user_ami')->where('id', '1')->first();
        //dd($message1->message);
        return View('Communication.user-ami', compact('userId', 'yup'));
    }
}
