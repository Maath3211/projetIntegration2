<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Statistiques;
use App\Models\StatThermique;
use App\Models\PoidsUtilisateur;
use App\Models\ScoreExercice;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class StatistiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (session()->has('locale')) {
            App::setLocale(session('locale'));
        }
        if (auth()->check()) {
            $usager = User::find(Auth::id());

            // Exclure Poids, Streak et FoisGym des statistiques
            $statistiques = Statistiques::where('user_id', Auth::id())
                ->whereNotIn('nomStatistique', ['Poids', 'Streak', 'FoisGym'])
                ->get();

            $poids = PoidsUtilisateur::where('user_id', Auth::id())->orderBy('poids', 'asc')->first()->poids;
            $scoreExercice = ScoreExercice::All();
            $streak = Statistiques::where('user_id', Auth::id())->where('nomStatistique', 'Streak')->get();
            $foisGym = Statistiques::where('user_id', Auth::id())->where('nomStatistique', 'FoisGym')->get();
        }

        return view("statistique.index", compact('statistiques', 'usager', 'poids', 'streak', 'foisGym', 'scoreExercice'));
    }


    public function graphique()
    {
        $user = Auth::user();
        $dateCreationCompte = Carbon::parse($user->created_at);

        $dateAjd = Carbon::now();
        $diffSemaines = (int) $dateCreationCompte->diffInWeeks($dateAjd) + 1;


        $donnees = PoidsUtilisateur::where('user_id', Auth::id())
            ->orderBy('semaine', 'asc')
            ->get();

        // Extraire les semaines et poids
        $semaines = $donnees->pluck('semaine')->toArray();
        $poids = $donnees->pluck('poids')->toArray();


        return view("statistique.graphique", compact('dateCreationCompte', 'semaines', 'poids', 'diffSemaines'));
    }





    public function graphiqueExercice(Statistiques $exercice)
    {

        $dateCreationExercice = Carbon::parse($exercice->created_at);

        $dateAjd = Carbon::now();
        $diffSemaines = (int) $dateCreationExercice->diffInWeeks($dateAjd) + 1;

        $donnees = ScoreExercice::where('statistique_id', $exercice->id)
            ->orderBy('semaine', 'asc')
            ->get();


        $semaines = $donnees->pluck('semaine')->toArray();
        $score = $donnees->pluck('score')->toArray();

        return view("statistique.graphiqueExercice", compact('dateCreationExercice', 'semaines', 'score', 'diffSemaines', 'exercice'));
    }


    public function ajouterScoreExercice(Request $request, Statistiques $exercice)
    {
        $request->validate([
            'score' => 'required|numeric',
            'score' => 'gt:0',
        ]);

        $statistique = Statistiques::find($exercice->id);
        $dateCreationExercice = Carbon::parse($exercice->created_at);
        $dateAjd = Carbon::now();

        $score = (int) $request->input('score');

        $data = [
            'statistique_id' => $statistique->id,
            'semaine' => (int)$dateCreationExercice->diffInWeeks($dateAjd) + 1,
            'score' => $score
        ];



        $scoreExercice = ScoreExercice::updateOrCreate(
            ['statistique_id' => $data['statistique_id'], 'semaine' => $data['semaine']],
            $data
        );

        return redirect()->back()->with('success', 'Score ajouté avec succès !');
    }





    public function ajouterPoids(Request $request)
    {
        $request->validate([
            'poids' => 'required|numeric',
            'poids' => 'gt:0',
        ]);

        $user = Auth::user();
        $dateCreationCompte = Carbon::parse($user->created_at);
        $dateAjd = Carbon::now();

        $poids = (int) $request->input('poids');

        $data = [
            'user_id' => $user->id,
            'semaine' => (int)$dateCreationCompte->diffInWeeks($dateAjd) + 1,
            'poids' => $poids
        ];



        $poidsUtilisateur = PoidsUtilisateur::updateOrCreate(
            ['user_id' => $data['user_id'], 'semaine' => $data['semaine']],
            $data
        );

        return redirect()->back()->with('success', 'Poids ajouté avec succès !');
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
            $statistique = Statistiques::create([
                'user_id' => auth()->id(),
                'nomStatistique' => $stat['nomStatistique'],
                'score' => $stat['score']
            ]);

            ScoreExercice::create([
                'statistique_id' => $statistique->id,
                'semaine' => 1,
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

    public function updateWeight(Request $request)
    {
        \Log::info('Request received for updateWeight', ['request' => $request->all()]);

        $user = Auth::user();
        $poid = $user->poids()->first();

        if ($poid) {
            $poid->score = $request->input('poids');
            $poid->save();
            \Log::info('Weight updated successfully', ['poid' => $poid]);
            return response()->json(['success' => true]);
        } else {
            \Log::error('Weight update failed: Weight not found');
            return response()->json(['success' => false]);
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
