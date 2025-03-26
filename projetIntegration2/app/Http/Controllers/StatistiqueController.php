<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Statistiques;
use App\Models\StatThermique;
use App\Models\PoidsUtilisateur;
use App\Models\ScoreExercice;
use App\Models\Objectif;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

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
            $scoreHaut = ScoreExercice::select('statistique_id', \DB::raw('MAX(score) as max_score'))
                ->groupBy('statistique_id')
                ->get();
            $streak = Statistiques::where('user_id', Auth::id())->where('nomStatistique', 'Streak')->get();
            $foisGym = Objectif::where('user_id', Auth::id())->where('completer', true)->count();
            $utilisateur = Auth::user();
            $clans = $utilisateur->clans; // Fetch all clans associated with the user
        }
        
        return view("statistique.index", compact('statistiques', 'usager', 'poids', 'foisGym', 'scoreExercice', 'scoreHaut', 'streak', 'clans'));
    }


    public function ajouterFoisGym()
    {
        $user = Auth::user();
        $statistique = Statistiques::where('user_id', $user->id)->where('nomStatistique', 'FoisGym')->first();
        if ($statistique) {
            $statistique->score += 1;
            $statistique->save();
        } else {
            Statistiques::create([
                'user_id' => $user->id,
                'nomStatistique' => 'FoisGym',
                'score' => 0
            ]);
        }
        return redirect()->back()->with('success', 'FoisGym ajouté avec succès !');
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
        $utilisateur = Auth::user();
        $clans = $utilisateur->clans; // Fetch all clans associated with the user
        return view("statistique.graphique", compact('dateCreationCompte', 'semaines', 'poids', 'diffSemaines', 'clans'));
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
        $utilisateur = Auth::user();
        $clans = $utilisateur->clans; // Fetch all clans associated with the user
        return view("statistique.graphiqueExercice", compact('dateCreationExercice', 'semaines', 'score', 'diffSemaines', 'exercice', 'clans'));
    }


    public function ajouterScoreExercice(Request $request, Statistiques $exercice)
    {
        $request->validate([
            'score' => 'required|numeric|gt:0|lt:1000',
        ], [
            'score.required' => 'Le score est requis.',
            'score.numeric' => 'Le score doit être un nombre.',
            'score.gt' => 'Le score doit être supérieur à 0.',
            'score.lt' => 'Le score doit être inférieur à 1000.',

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
            'poids' => 'required|numeric|gt:0|lt:1000',
        ], [
            'poids.required' => 'Le poids est requis.',
            'poids.numeric' => 'Le poids doit être un nombre.',
            'poids.gt' => 'Le poids doit être supérieur à 0.',
            'poids.lt' => 'Le poids doit être inférieur à 1000.',
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
        $donnees = StatThermique::where('user_id', Auth::id())->get();
        $utilisateur = Auth::user();     
        $clans = $utilisateur->clans; // Fetch all clans associated with the user          // Récupère toutes les données, vous pouvez filtrer par date si nécessaire
        return view("statistique.thermique", compact('donnees', 'clans'));
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
            'stats.*.nomStatistique' => 'required|regex:/^[a-zA-Z]+$/|max:100',
            'stats.*.score' => 'required|numeric|gt:0',
        ], [
            'stats.required' => 'Les statistiques sont requises.',
            'stats.*.nomStatistique.required' => 'Le nom de la statistique est requis.',
            'stats.*.nomStatistique.regex' => 'Le nom de la statistique doit contenir uniquement des lettres.',
            'stats.*.nomStatistique.max' => 'Le nom de la statistique ne doit pas dépasser 100 caractères.',
            'stats.*.score.required' => 'Le score est requis.',
            'stats.*.score.numeric' => 'Le score doit être un nombre.',
            'stats.*.score.gt' => 'Le score doit être supérieur à 0.',
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
        \Log::info('modiferPoids', ['request' => $request->all()]);

        $user = Auth::user();
        $poid = $user->poids()->first();

        if ($poid) {
            $poid->score = $request->input('poids');
            $poid->save();
            \Log::info('Poids modifier avec succes', ['poid' => $poid]);
            return response()->json(['success' => true]);
        } else {
            \Log::error('Poids non trouvé');
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
