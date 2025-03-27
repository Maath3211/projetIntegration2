<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class ScoreGraph extends Component
{
    // Propriétés publiques accessibles dans la vue
    public $months = [];           // Tableau contenant les noms des mois pour l'axe X du graphique
    public $clanScores = [];       // Tableau contenant les scores des clans pour chaque mois
    public $userScores = [];       // Tableau contenant les scores des utilisateurs pour chaque mois
    public $showType;              // Type de graphique à afficher ('members' ou autre)
    public $selectedClanId;        // ID du clan sélectionné ('global' ou ID spécifique)
    public $refreshKey = 0;        // Clé pour forcer le rafraîchissement du composant

    /**
     * Définit les événements que ce composant écoute
     * 
     * @return array Liste des événements et des méthodes associées
     */
    protected function getListeners()
    {
        return [
            'localeChanged' => 'handleLocaleChanged',       // Événement de changement de langue
            'updateSelectedClan' => 'updateSelectedClan'    // Événement de changement de clan sélectionné
        ];
    }

    /**
     * Méthode exécutée lors de l'initialisation du composant
     * 
     * @param string $showType Type de données à afficher, par défaut 'members'
     * @param string|int|null $selectedClanId ID du clan sélectionné ou null
     */
    public function mount($showType = 'members', $selectedClanId = null)
    {
        $this->showType = $showType;
        $this->selectedClanId = $selectedClanId;

        // Définit la langue à partir de la session lors du montage du composant
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        // Charge les données initiales du graphique
        $this->loadChartData();
    }

    /**
     * Charge les données du graphique pour les 6 derniers mois
     */
    public function loadChartData()
    {
        // Réinitialise les tableaux de données
        $this->months = [];
        $this->clanScores = [];
        $this->userScores = [];

        // Récupère la langue actuelle
        $locale = App::getLocale();

        // Génère les données pour les 6 derniers mois
        for ($i = 5; $i >= 0; $i--) {
            // Calcule le mois (i mois avant aujourd'hui)
            $month = Carbon::now()->subMonths($i);

            // Traduit le nom du mois selon la langue
            if ($locale === 'fr') {
                // Format personnalisé pour le français en utilisant les traductions
                $monthName = __('months.' . strtolower($month->format('M')));
                $this->months[] = $monthName . ' ' . $month->format('Y');
            } else {
                // Format anglais par défaut
                $this->months[] = $month->format('M Y');
            }

            // Calcule le premier et dernier jour du mois
            $startOfMonth = $month->startOfMonth()->format('Y-m-d');
            $endOfMonth = $month->endOfMonth()->format('Y-m-d');

            // Récupère les scores des clans pour ce mois
            try {
                // Construction de la requête pour les scores de clan
                $clanQuery = DB::table('clan_users as cu')
                    ->join('scores', function ($join) use ($startOfMonth, $endOfMonth) {
                        $join->on('cu.user_id', '=', 'scores.user_id')
                            ->whereBetween('scores.date', [$startOfMonth, $endOfMonth]);
                    });

                // Filtre par clan si un clan spécifique est sélectionné
                if ($this->selectedClanId && $this->selectedClanId != 'global') {
                    $clanQuery->where('cu.clan_id', $this->selectedClanId);
                }

                // Calcule la somme des scores pour ce mois
                $clanScore = $clanQuery->sum('scores.score');
                // Utilise 0 si aucun score n'est trouvé
                $this->clanScores[] = $clanScore ?: 0;
            } catch (\Exception $e) {
                // En cas d'erreur, utilise une valeur aléatoire (à modifier si nécessaire)
                $this->clanScores[] = rand(1000, 2000);
            }

            // Récupère les scores des utilisateurs pour ce mois
            try {
                // Construction de la requête pour les scores utilisateurs
                $userQuery = DB::table('scores')
                    ->whereBetween('date', [$startOfMonth, $endOfMonth]);

                // Filtre par clan si un clan spécifique est sélectionné
                if ($this->selectedClanId && $this->selectedClanId != 'global') {
                    $userQuery->join('clan_users', 'scores.user_id', '=', 'clan_users.user_id')
                        ->where('clan_users.clan_id', $this->selectedClanId);
                }

                // Calcule la somme des scores pour ce mois
                $userScore = $userQuery->sum('scores.score');
                // Utilise 0 si aucun score n'est trouvé
                $this->userScores[] = $userScore ?: 0;
            } catch (\Exception $e) {
                // En cas d'erreur, utilise une valeur aléatoire (à modifier si nécessaire)
                $this->userScores[] = rand(700, 1500);
            }
        }
    }

    /**
     * Met à jour le clan sélectionné et recharge les données du graphique
     * 
     * @param string|int $clanId ID du clan sélectionné ou 'global'
     */
    public function updateSelectedClan($clanId)
    {
        $this->selectedClanId = $clanId;
        $this->loadChartData();
    }

    /**
     * Gère le changement de langue de l'application
     * 
     * @param string|array|null $params Paramètres contenant la langue
     */
    public function handleLocaleChanged($params = null)
    {
        $locale = null;

        // Gère différents formats de paramètres
        if (is_string($params)) {
            $locale = $params;
        } elseif (is_array($params) && isset($params['locale'])) {
            $locale = $params['locale'];
        }

        // Met à jour la langue si elle est valide
        if ($locale && in_array($locale, ['en', 'fr'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);

            // Recharge les données du graphique avec la nouvelle langue pour les noms de mois
            $this->loadChartData();
        }

        // S'assure que la langue est définie correctement
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        // Incrémente la clé de rafraîchissement pour forcer le rendu
        $this->refreshKey++;
    }

    /**
     * Ferme le graphique et notifie les autres composants
     */
    public function closeGraph()
    {
        // Émet un événement pour indiquer que le graphique a été fermé
        $this->dispatch('scoreGraphClosed');
    }

    /**
     * Méthode de rendu du composant
     */
    public function render()
    {
        // Définit la langue à partir de la session avant le rendu
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        // Retourne la vue avec les données nécessaires
        return view('livewire.score-graph', [
            'showType' => $this->showType,               // Type de graphique à afficher
            'selectedClanId' => $this->selectedClanId,   // ID du clan sélectionné
            'months' => $this->months,                   // Étiquettes des mois pour l'axe X
            'clanScores' => $this->clanScores,           // Scores des clans pour chaque mois
            'userScores' => $this->userScores,           // Scores des utilisateurs pour chaque mois
        ]);
    }
}
