<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClanController extends Controller
{
    public function index(){
        return View('Clans.accueilClans');
    }
}
