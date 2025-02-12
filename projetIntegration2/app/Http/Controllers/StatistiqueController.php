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
            $usager = User::find(Auth::id());
            
            // Exclure Poids, Streak et FoisGym des statistiques
            $statistiques = Statistiques::where('user_id', Auth::id())
                ->whereNotIn('nomStatistique', ['Poids', 'Streak', 'FoisGym'])
                ->get();
    
            $poid = Statistiques::where('user_id', Auth::id())->where('nomStatistique', 'Poids')->get();
            $streak = Statistiques::where('user_id', Auth::id())->where('nomStatistique', 'Streak')->get();
            $foisGym = Statistiques::where('user_id', Auth::id())->where('nomStatistique', 'FoisGym')->get();
        }
    
        return view("statistique.index", compact('statistiques', 'usager', 'poid', 'streak', 'foisGym'));
    }


    public function graphique()
    {
        $statistiques = Statistiques::where('user_id', Auth::id())->get();
        return View("statistique.graphique");
    }

    public function thermique()
    {
        $donnees = StatThermique::where('user_id', Auth::id())->get();;                  // Récupère toutes les données, vous pouvez filtrer par date si nécessaire
        return view("statistique.thermique", compact('donnees'));
    }

    public function storeThermique(Request $request)
    {

        $userid = Auth::id();
        $donnees = json_decode($request->input('donnees'), true);

        if (is_null($donnees)) {
            return redirect()->back()->with('error', 'Aucune donnée reçue.');
        }
        foreach ($donnees as $item) {
            $data = [
                'date' => $item['date'],
                'type_activite' => $item['type_activite'],
                'user_id' => $userid
            ];

            StatThermique::updateOrCreate(
                ['date' => $item['date']], // Critères de recherche
                $data // Données à mettre à jour ou à créer
            );
        }

        return redirect()->back()->with('success', 'Données sauvegardées avec succès !');
    }


    public function save(Request $request)
    {
        $request->validate([
            'stats' => 'required|array',
            'stats.*.nomStatistique' => 'required|string',
            'stats.*.score' => 'required|numeric',
        ]);
    
        foreach ($request->stats as $stat) {
            Statistiques::create([
                'user_id' => auth()->id(),
                'nomStatistique' => $stat['nomStatistique'],
                'score' => $stat['score']
            ]);
        }
    
        return response()->json(['message' => 'Statistiques enregistrées avec succès !']);
    }

    
    public function delete($id)
    {
        // Récupérer l'exercice par son ID et vérifier s'il appartient à l'utilisateur authentifié
        $statistique = Statistiques::where('id', $id)->where('user_id', auth()->id())->first();
    
        // Vérifier si l'exercice existe
        if ($statistique) {
            // Supprimer l'exercice
            $statistique->delete();
            
            return response()->json(['message' => 'Exercice supprimé avec succès !']);
        } else {
            return response()->json(['message' => 'Exercice non trouvé ou vous n\'êtes pas autorisé à le supprimer.'], 404);
        }
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
