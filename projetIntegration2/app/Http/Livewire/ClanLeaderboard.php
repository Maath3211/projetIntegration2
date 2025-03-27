<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Clan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class ClanLeaderboard extends Component
{
    // Propriétés publiques accessibles dans la vue
    public $selectedClanId;           // ID du clan sélectionné
    public $selectedClan;             // Objet du clan sélectionné
    public $meilleursMembres;         // Liste des meilleurs membres du clan par score total
    public $topScoreImprovement;      // Liste des membres avec la meilleure amélioration de score récente
    public $showingGraph = false;     // État d'affichage du graphique (visible ou caché)
    public $chartType = 'members';    // Type de graphique à afficher: membres ou améliorations
    public $refreshKey = 0;           // Clé pour forcer le rafraîchissement du composant
    public $dataLoaded = false;

    /**
     * Définit les événements que ce composant écoute
     */
    protected function getListeners()
    {
        return [
            'localeChanged' => 'handleLocaleChanged',       // Changement de langue
            'scoreGraphClosed' => 'hideGraph',              // Fermeture du graphique des scores
            'clanSelected' => 'updateClan',                 // Sélection d'un nouveau clan
            'closeGraph' => 'hideGraph'                     // Action de fermeture du graphique
        ];
    }

    /**
     * Méthode exécutée lors de l'initialisation du composant
     */
    public function mount($selectedClanId = null)
    {
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        // Reset graph state when component is mounted/remounted
        $this->showingGraph = false;
        $this->chartType = 'members';

        // Initialize the clan selection
        $this->updateClan($selectedClanId);
    }

    /**
     * Met à jour les données du clan sélectionné et récupère les classements associés
     */
    public function updateClan($clanId)
    {
        $this->selectedClanId = $clanId;
        $oneMonthAgo = \Carbon\Carbon::now()->subMonth();   // Date d'il y a un mois pour les améliorations récentes

        // Essaye de récupérer les informations du clan
        try {
            $this->selectedClan = Clan::find($clanId);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la requête sur le modèle Clan: ' . $e->getMessage());
        }

        // Journal si le clan n'est pas trouvé
        if (!$this->selectedClan) {
            Log::error("Clan avec l'ID {$clanId} non trouvé.");
        }

        // Récupère les meilleurs membres du clan par score total
        $this->meilleursMembres = DB::table('users')
            ->join('clan_users', 'users.id', '=', 'clan_users.user_id')         // Jointure avec la table des relations clan-utilisateurs
            ->join('scores', 'users.id', '=', 'scores.user_id')                 // Jointure avec la table des scores
            ->where('clan_users.clan_id', $clanId)                              // Filtre pour le clan spécifique
            ->select(
                'users.imageProfil as user_image',                              // Image de profil de l'utilisateur
                'users.nom as user_nom',                                         // Nom de l'utilisateur
                'users.prenom as user_prenom',                                   // Prénom de l'utilisateur
                'users.email as email',                                          // Email de l'utilisateur
                DB::raw('SUM(scores.score) as user_total_score')                // Somme des scores de l'utilisateur
            )
            ->groupBy('users.id', 'users.imageProfil', 'users.nom', 'users.prenom', 'users.email')  // Regroupement par utilisateur
            ->orderByDesc('user_total_score')                                   // Tri par score total décroissant
            ->limit(10)                                                         // Limite aux 10 premiers résultats
            ->get();

        // Récupère les membres avec la meilleure amélioration récente (dernier mois)
        $this->topScoreImprovement = DB::table('users')
            ->join('clan_users', 'users.id', '=', 'clan_users.user_id')         // Jointure avec la table des relations clan-utilisateurs
            ->join('scores', 'users.id', '=', 'scores.user_id')                 // Jointure avec la table des scores
            ->where('clan_users.clan_id', $clanId)                              // Filtre pour le clan spécifique
            ->where('scores.date', '>=', $oneMonthAgo)                          // Seulement les scores du dernier mois
            ->select(
                'users.imageProfil as user_image',                              // Image de profil de l'utilisateur
                'users.nom as user_nom',                                         // Nom de l'utilisateur
                'users.prenom as user_prenom',                                   // Prénom de l'utilisateur
                'users.email as email',                                          // Email de l'utilisateur
                DB::raw('SUM(scores.score) as score_improvement')               // Somme des améliorations de score
            )
            ->groupBy('users.id', 'users.imageProfil', 'users.nom', 'users.prenom', 'users.email')  // Regroupement par utilisateur
            ->orderByDesc('score_improvement')                                  // Tri par amélioration décroissante
            ->limit(10)                                                         // Limite aux 10 premiers résultats
            ->get();
    }

    /**
     * Affiche le graphique des membres du clan
     */
    public function showMembersGraph()
    {
        $this->chartType = 'members';     // Définit le type de graphique à afficher
        $this->showingGraph = true;       // Affiche le graphique
    }

    /**
     * Affiche le graphique des améliorations de score
     */
    public function showImprovementsGraph()
    {
        $this->chartType = 'improvements'; // Définit le type de graphique à afficher
        $this->showingGraph = true;        // Affiche le graphique
    }
    public function loadData()
    {
        $this->dataLoaded = true;
    }

    /**
     * Cache le graphique
     */
    public function hideGraph()
    {
        $this->showingGraph = false;      // Cache le graphique
    }

    /**
     * Force le rafraîchissement du composant
     */
    public function refreshComponent()
    {
        // Définir la langue à partir de la session
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        // Force un rafraîchissement du composant
        $this->emit('$refresh');
    }

    /**
     * Gère le changement de langue
     */
    public function handleLocaleChanged($params = null)
    {
        // Récupère la langue depuis le tableau de paramètres
        $locale = is_array($params) && isset($params['locale']) ? $params['locale'] : null;

        // Met à jour la langue si elle est valide
        if ($locale && in_array($locale, ['en', 'fr'])) {
            Session::put('locale', $locale);                  // Enregistre la langue dans la session
            App::setLocale($locale);                          // Définit la langue de l'application
            $this->refreshKey = now()->timestamp;             // Force le rafraîchissement avec une nouvelle clé
        }
    }

    /**
     * Méthode de rendu du composant
     */
    public function render()
    {
        // S'assure que la langue est correctement définie avant le rendu
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        // Retourne la vue avec les données nécessaires
        return view('livewire.clan-leaderboard', [
            'selectedClan' => $this->selectedClan,              // Le clan sélectionné
            'meilleursMembres' => $this->meilleursMembres,      // La liste des meilleurs membres
            'topScoreImprovement' => $this->topScoreImprovement, // La liste des meilleures améliorations
            'refreshKey' => $this->refreshKey                   // La clé de rafraîchissement
        ]);
    }
}
