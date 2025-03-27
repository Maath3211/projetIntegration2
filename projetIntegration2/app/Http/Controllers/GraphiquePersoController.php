<?php
// app/Http/Controllers/GraphiquePersoController.php
namespace App\Http\Controllers;

use App\Models\Clan;
use App\Models\GraphSauvegarde;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GraphiquePersoController extends Controller
{
    /**
     * Affiche la liste des graphiques sauvegardés
     */
    public function index()
    {
        $user = Auth::user();
        $clans = $user->clans;
        // Récupère uniquement les graphiques non expirés, triés par date de création (les plus récents d'abord)
        $graphs = GraphSauvegarde::where('user_id', $user->id)
            ->where('date_expiration', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('graphs.index', compact('graphs', 'clans'));
    }

    /**
     * Affiche le formulaire pour créer un nouveau graphique
     */
    public function create()
    {
        $user = Auth::user();
        // Récupère les clans auxquels l'utilisateur appartient pour le menu déroulant
        $clans = $user->clans()->get();

        return view('graphs.create', compact('clans'));
    }

    /**
     * Génère et sauvegarde un graphique personnalisé
     */
    public function store(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'titre' => 'required|max:255',
            'type' => 'required|in:global,clan',
            'clan_id' => 'required_if:type,clan|exists:clans,id', // clan_id obligatoire si type=clan
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut', // date_fin doit être après ou égale à date_debut
        ]);

        $user = Auth::user();

        // Vérifie l'appartenance au clan si un clan spécifique est sélectionné
        if ($request->type == 'clan') {
            $isMember = DB::table('clan_users')
                ->where('user_id', $user->id)
                ->where('clan_id', $request->clan_id)
                ->exists();

            // Redirige avec erreur si l'utilisateur n'est pas membre du clan
            if (!$isMember) {
                return redirect()->back()
                    ->with('error', __('graphs.erreur_membre_clan'));
            }
        }

        // Génère les données du graphique selon le type et la plage de dates
        $data = $this->generateGraphData(
            $request->type,
            $request->clan_id,
            $request->date_debut,
            $request->date_fin
        );

        // Sauvegarde le graphique en base de données
        try {
            $savedGraph = GraphSauvegarde::create([
                'user_id' => $user->id,
                'type' => $request->type,
                'clan_id' => $request->type == 'clan' ? $request->clan_id : null, // clan_id uniquement si type=clan
                'titre' => $request->titre,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'data' => $data,
                'date_expiration' => Carbon::now()->addDays(90), // Expire après 90 jours
            ]);

            // Redirige vers la vue du graphique créé avec message de succès
            return redirect()->route('graphs.show', $savedGraph->id)
                ->with('success', __('graphs.cree_avec_succes'));
        } catch (\Exception $e) {
            // En cas d'erreur, retourne au formulaire avec le message d'erreur
            return redirect()->back()
                ->with('error', __('graphs.erreur_creation') . ': ' . $e->getMessage());
        }
    }

    /**
     * Affiche un graphique sauvegardé spécifique
     */
    public function show($id)
    {
        try {
            // Récupération du graphique par son ID
            $graph = GraphSauvegarde::findOrFail($id);

            // Vérification que l'utilisateur est bien le propriétaire du graphique
            if ($graph->user_id != Auth::id()) {
                return redirect()->route('graphs.index')
                    ->with('error', __('graphs.non_autorise'));
            }

            // Journal pour le débogage des données du graphique
            \Log::debug('Graph data:', ['data' => $graph->data]);

            $user = Auth::user();
            $clans = $user->clans()->get();
            return view('graphs.show', compact('graph', 'clans'));
        } catch (\Exception $e) {
            // Redirection vers la liste en cas d'erreur
            return redirect()->route('graphs.index')
                ->with('error', __('graphs.erreur_affichage') . ': ' . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire d'édition du graphique
     */
    public function edit($id)
    {
        try {
            // Récupération du graphique par son ID
            $graph = GraphSauvegarde::findOrFail($id);

            // Vérification que l'utilisateur est bien le propriétaire du graphique
            if ($graph->user_id != Auth::id()) {
                return redirect()->route('graphs.index')
                    ->with('error', __('graphs.non_autorise'));
            }

            // Récupération des clans de l'utilisateur pour le menu déroulant
            $clans = Auth::user()->clans()->get();

            return view('graphs.edit', compact('graph', 'clans'));
        } catch (\Exception $e) {
            return redirect()->route('graphs.index')
                ->with('error', __('graphs.erreur_chargement_formulaire') . ': ' . $e->getMessage());
        }
    }

    /**
     * Met à jour le graphique spécifié
     */
    public function update(Request $request, $id)
    {
        // Validation des données du formulaire
        $request->validate([
            'titre' => 'required|max:255',
            'type' => 'required|in:global,clan',
            'clan_id' => 'required_if:type,clan|exists:clans,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);

        try {
            // Récupération du graphique par son ID
            $graph = GraphSauvegarde::findOrFail($id);

            // Vérification que l'utilisateur est bien le propriétaire du graphique
            if ($graph->user_id != Auth::id()) {
                return redirect()->route('graphs.index')
                    ->with('error', __('graphs.non_autorise'));
            }

            // Vérifie si les données du graphique doivent être régénérées
            // (si les dates, le type ou le clan ont changé)
            $regenerateData = $graph->date_debut->format('Y-m-d') != $request->date_debut ||
                $graph->date_fin->format('Y-m-d') != $request->date_fin ||
                $graph->type != $request->type ||
                ($graph->type == 'clan' && $graph->clan_id != $request->clan_id);

            // Mise à jour des propriétés du graphique
            $graph->titre = $request->titre;
            $graph->type = $request->type;
            $graph->clan_id = $request->type == 'clan' ? $request->clan_id : null;
            $graph->date_debut = $request->date_debut;
            $graph->date_fin = $request->date_fin;

            // Régénère les données si nécessaire
            if ($regenerateData) {
                $graph->data = $this->generateGraphData(
                    $request->type,
                    $request->clan_id,
                    $request->date_debut,
                    $request->date_fin
                );
            }

            // Sauvegarde les modifications
            $graph->save();

            // Redirection vers la vue du graphique mis à jour avec message de succès
            return redirect()->route('graphs.show', $graph->id)
                ->with('success', __('graphs.modifie_avec_succes'));
        } catch (\Exception $e) {
            // En cas d'erreur, retourne au formulaire avec le message d'erreur
            return redirect()->back()
                ->with('error', __('graphs.erreur_modification') . ': ' . $e->getMessage());
        }
    }

    /**
     * Supprime un graphique sauvegardé
     */
    public function destroy($id)
    {
        try {
            // Récupération du graphique par son ID
            $graph = GraphSauvegarde::findOrFail($id);

            // Vérification que l'utilisateur est bien le propriétaire du graphique
            if ($graph->user_id != Auth::id()) {
                return redirect()->route('graphs.index')
                    ->with('error', __('graphs.non_autorise'));
            }

            // Suppression du graphique
            $graph->delete();

            // Redirection vers la liste des graphiques avec message de succès
            return redirect()->route('graphs.index')
                ->with('success', __('graphs.supprime_avec_succes'));
        } catch (\Exception $e) {
            return redirect()->route('graphs.index')
                ->with('error', __('graphs.erreur_suppression') . ': ' . $e->getMessage());
        }
    }

    /**
     * Génère les données du graphique en fonction du type et de la plage de dates
     */
    private function generateGraphData($type, $clanId, $startDate, $endDate)
    {
        // S'assure que nous travaillons avec des instances Carbon
        $startDate = $startDate instanceof Carbon ? $startDate : Carbon::parse($startDate);
        $endDate = $endDate instanceof Carbon ? $endDate : Carbon::parse($endDate);

        // Force l'heure au début/fin de journée pour inclure tous les enregistrements
        $startDate = $startDate->startOfDay();
        $endDate = $endDate->endOfDay();

        // Vérifie si nous avons des données dans la base pour cette plage de dates
        $scoreCount = DB::table('scores')
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->count();

        // Si nous n'avons pas de scores, enregistre toutes les dates disponibles pour le débogage
        if ($scoreCount == 0) {
            $allScores = DB::table('scores')
                ->select('date')
                ->distinct()
                ->orderBy('date')
                ->get()
                ->pluck('date');
        }

        // Calcule le nombre de mois entre les dates
        $diffInMonths = $startDate->diffInMonths($endDate) + 1;

        // Préparation des tableaux pour les étiquettes et les valeurs
        $labels = [];
        $values = [];

        // Si les dates sont très proches, utilise un intervalle journalier
        if ($diffInMonths <= 1) {
            $interval = 'daily';
            $current = $startDate->copy();
            // Parcours jour par jour jusqu'à la date de fin
            while ($current <= $endDate) {
                // Format "jour mois" pour les étiquettes (ex: "15 Jan")
                $labels[] = $current->format('d M');

                // Récupère le score pour ce jour selon le type de graphique
                if ($type == 'global') {
                    $values[] = $this->getGlobalScoreForDay($current);
                } else {
                    $values[] = $this->getClanScoreForDay($clanId, $current);
                }

                // Passe au jour suivant
                $current = $current->addDay();
            }
        } else {
            // Intervalles mensuels pour les périodes plus longues
            $current = $startDate->copy()->startOfMonth();
            while ($current <= $endDate) {
                // Fin du mois ou date de fin si plus tôt
                $endOfMonth = min($current->copy()->endOfMonth(), $endDate);

                // Format "mois année" pour les étiquettes (ex: "Jan 2023")
                $labels[] = $current->format('M Y');

                // Récupère le score pour ce mois selon le type de graphique
                if ($type == 'global') {
                    $values[] = $this->getGlobalScoreForPeriod($current, $endOfMonth);
                } else {
                    $values[] = $this->getClanScoreForPeriod($clanId, $current, $endOfMonth);
                }

                // Passe au mois suivant
                $current = $current->addMonth();
            }
        }

        // Retourne les données structurées pour le graphique
        return [
            'labels' => $labels,
            'values' => $values,
            'interval' => $diffInMonths <= 1 ? 'daily' : 'monthly'
        ];
    }

    /**
     * Récupère les données de score global pour un jour spécifique
     */
    private function getGlobalScoreForDay(Carbon $date)
    {
        // Récupère tous les scores pour cette date spécifique, avec info de débogage
        $scores = DB::table('scores')
            ->whereDate('date', $date->format('Y-m-d'))
            ->get();

        // Retourne la somme des scores ou 0 si aucun score
        return DB::table('scores')
            ->whereDate('date', $date->format('Y-m-d'))
            ->sum('score') ?: 0;
    }

    /**
     * Récupère les données de score d'un clan pour un jour spécifique
     */
    private function getClanScoreForDay($clanId, Carbon $date)
    {
        // Récupère les scores avec info de débogage
        $scores = DB::table('scores')
            ->join('clan_users', 'scores.user_id', '=', 'clan_users.user_id')
            ->where('clan_users.clan_id', $clanId)
            ->whereDate('scores.date', $date->format('Y-m-d'))
            ->get();

        // Retourne la somme des scores pour les membres du clan ou 0 si aucun score
        return DB::table('scores')
            ->join('clan_users', 'scores.user_id', '=', 'clan_users.user_id')
            ->where('clan_users.clan_id', $clanId)
            ->whereDate('scores.date', $date->format('Y-m-d'))
            ->sum('scores.score') ?: 0;
    }

    /**
     * Récupère les données de score global pour une période
     */
    private function getGlobalScoreForPeriod(Carbon $startDate, Carbon $endDate)
    {
        // Retourne la somme des scores entre les dates spécifiées ou 0 si aucun score
        return DB::table('scores')
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->sum('score') ?: 0;
    }

    /**
     * Récupère les données de score d'un clan pour une période
     */
    private function getClanScoreForPeriod($clanId, Carbon $startDate, Carbon $endDate)
    {
        // Retourne la somme des scores des membres du clan entre les dates spécifiées ou 0 si aucun score
        return DB::table('scores')
            ->join('clan_users', 'scores.user_id', '=', 'clan_users.user_id')
            ->where('clan_users.clan_id', $clanId)
            ->whereBetween('scores.date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->sum('scores.score') ?: 0;
    }
}
