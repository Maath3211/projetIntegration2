<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClanController extends Controller
{
    public function index($id){
        //$clan = Clan::findOrFail($id);
        return View('Clans.accueilClans'/*, compact('clan')*/);
    }

    public function parametres($id){
        // $clan = Clan::findOrFail($id);
        return View('Clans.parametresClan'/*, compact('clan')*/);
    }
}
