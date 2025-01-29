<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserCommunication extends Controller
{
    //
    public function index()
    {
        
        return View('Communication.user-ami');
    }
}
