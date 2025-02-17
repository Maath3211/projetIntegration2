<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Utilisé pour la recherche d'amis

class AmisController extends Controller
{
    // Affiche le formulaire de recherche
    public function index()
    {
        return view('amis.index');
    }

    // Recherche les utilisateurs par nom d'utilisateur
    public function recherche(Request $requete)
    {
        $requete->validate([
            'q' => 'required|string'
        ]);

        $query = $requete->input('q');
        $utilisateurs = User::where('username', 'like', "%{$query}%")->get();

        return view('amis.index', compact('utilisateurs'));
    }

    // Ajoute l'utilisateur en ami
    public function ajouter(Request $requete)
    {
        $requete->validate([
            'utilisateur_id' => 'required|exists:users,id'
        ]);

        $amiId = $requete->input('utilisateur_id');

        // Exemple de logique simplifiée pour les tests :
        // Utilisation d'un utilisateur fictif avec l'ID 1
        $utilisateur = User::find(1);
        $ami = User::find($amiId);
        $utilisateur->amis()->attach($ami);

        return back()->with('success', 'Ami ajouté avec succès.');
    }
}