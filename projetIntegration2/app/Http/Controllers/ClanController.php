<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clan; // Assurez-vous que le modèle Clan est importé
use Illuminate\Support\Facades\DB;

class ClanController extends Controller
{
    // Accueil d'un clan
    public function index($id){
        //$clan = Clan::findOrFail($id);
        return View('Clans.accueilClans'/*, compact('clan')*/);
    }

    // Paramètres d'un clan
    public function parametres($id){
        // $clan = Clan::findOrFail($id);
        return View('Clans.parametresClan'/*, compact('clan')*/);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Affiche la page de recherche de clans.
     * Si la méthode est GET, affiche la vue de recherche (rechercheClans.blade.php) sans résultat.
     * Si c'est POST, effectue la recherche et retourne les résultats.
     */
    public function rechercheClans(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('Clans.rechercheClans');
        }

        $request->validate([
            'q' => 'required|string'
        ]);

        $query = $request->input('q');
        $userId = auth()->check() ? auth()->user()->id : 999;

        // Récupérer uniquement les clans publics qui correspondent à la recherche et non déjà rejoints
        $clans = Clan::where('nom', 'like', "%{$query}%")
                    ->where('public', true)
                    ->whereNotIn('id', function($q) use ($userId) {
                        $q->select('clan_id')
                          ->from('clan_user')
                          ->where('user_id', $userId);
                    })
                    ->get();

        return view('Clans.rechercheClans', compact('clans'));
    }

    /**
     * Permet à l'utilisateur de rejoindre un clan.
     * L'utilisateur est déterminé par l'authentification, ou 999 pour les tests.
     */
    public function rejoindre(Request $request)
    {
        $request->validate([
            'clan_id' => 'required|integer|exists:clan,id',
        ]);

        $userId = auth()->check() ? auth()->user()->id : 999;

        // Vérifier si l'utilisateur a déjà rejoint le clan
        $exists = DB::table('clan_user')
            ->where('clan_id', $request->input('clan_id'))
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            return back()->withErrors('Vous avez déjà rejoint ce clan.');
        }

        DB::table('clan_user')->insert([
            'clan_id'    => $request->input('clan_id'),
            'user_id'    => $userId,
            'joined_at' => now(),
        ]);

        return back()->with('success', 'Vous avez rejoint le clan !');
    }
}
