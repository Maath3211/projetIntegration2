<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GymController extends Controller
{

    public function index()
    {
        $utilisateur = auth()->id();
        $utilisateur = User::findOrFail($utilisateur);

        // Si l'utilisateur est pas connecté
        if (!$utilisateur){
            Log::info('Utilisateur pas connecté.');
            return redirect('/connexion')->with('erreur', 'Vous devez être connectés pour afficher les clans.');
        }

        //obtenir les clans dont l'utilisateur fait partie
        $clans = $utilisateur->clans()->get();

        return view('localisation.localisation', compact('clans'));
    }

}
