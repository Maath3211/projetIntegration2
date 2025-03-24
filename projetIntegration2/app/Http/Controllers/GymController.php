<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GymController extends Controller
{

    public function index()
    {
        $utilisateur = Auth::user();
        $clans = $utilisateur->clans;

        return view('localisation.localisation', compact('clans'));
    }

}
