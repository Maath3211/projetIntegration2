<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Statistiques;
use App\Models\StatThermique;
use Illuminate\Support\Facades\Auth;

class StatistiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->check()) {
        $usager= User::Find(Auth::id());
        $statistiques = Statistiques::where('user_id', Auth::id())->get();
        $poid = Statistiques::where('user_id', Auth::id())->where('nomStatistique', 'Poids')->get();
        $streak = Statistiques::where('user_id', Auth::id())->where('nomStatistique', 'Streak')->get();
        $foisGym = Statistiques::where('user_id', Auth::id())->where('nomStatistique', 'FoisGym')->get();

        }
        return View("statistique.index",compact('statistiques','usager','poid','streak','foisGym'));
    }


    public function graphique()
    {
        $statistiques = Statistiques::where('user_id', Auth::id())->get();
        return View("statistique.graphique");
    }

    public function thermique()
    {
        $donnees = StatThermique::all(); // Récupère toutes les données, vous pouvez filtrer par date si nécessaire
        return view("statistique.thermique", compact('donnees'));
    }

    public function storeThermique(Request $request)
    {
        $donnees = json_decode($request->input('donnees'), true);

        if (is_null($donnees)) {
            return redirect()->back()->with('error', 'Aucune donnée reçue.');
        }
        foreach ($donnees as $item) {
            $data = [
                'date' => $item['date'],
                'type_activite' => $item['type_activite'],
            ];

            StatThermique::updateOrCreate(
                ['date' => $item['date']], // Critères de recherche
                $data // Données à mettre à jour ou à créer
            );
        }

        return redirect()->back()->with('success', 'Données sauvegardées avec succès !');
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
}
