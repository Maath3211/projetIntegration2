<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class GlobalLeaderboard extends Component
{
    // Propriétés publiques accessibles dans la vue
    public $topClans;                  // Liste des meilleurs clans
    public $topUsers;                  // Liste des meilleurs utilisateurs
    public $showingGraph = false;      // État d'affichage du graphique (visible ou caché)
    public $chartType = 'clans';       // Type de graphique à afficher: clans ou utilisateurs
    public $refreshKey = 0;            // Clé utilisée pour forcer les rafraîchissements de composant

    /**
     * Définit les événements que ce composant écoute
     */
    protected function getListeners()
    {
        return [
            'localeChanged' => 'handleLocaleChanged',   // Événement de changement de langue
            'scoreGraphClosed' => 'hideGraph'           // Événement de fermeture du graphique
        ];
    }

    /**
     * Méthode exécutée lors de l'initialisation du composant
     * 
     * @param array|null $topClans Les meilleurs clans à afficher
     * @param array|null $topUsers Les meilleurs utilisateurs à afficher
     */
    public function mount($topClans = null, $topUsers = null)
    {
        $this->topClans = $topClans ?? collect();
        $this->topUsers = $topUsers ?? collect();
        
        // Reset graph state when component is mounted/remounted
        $this->showingGraph = false;
        $this->chartType = 'clans';
        
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
    }

    /**
     * Gère le changement de langue de l'application
     * 
     * @param array|null $params Tableau contenant les paramètres, notamment la langue
     */
    public function handleLocaleChanged($params = null)
    {
        // Récupère la langue depuis le tableau de paramètres
        $locale = is_array($params) && isset($params['locale']) ? $params['locale'] : null;

        // Met à jour la langue si elle est valide
        if ($locale && in_array($locale, ['en', 'fr'])) {
            Session::put('locale', $locale);                 // Enregistre la langue dans la session
            App::setLocale($locale);                         // Définit la langue de l'application
            $this->refreshKey = now()->timestamp;            // Force le rafraîchissement avec une nouvelle clé temporelle
        }
    }

    /**
     * Affiche le graphique des clans
     */
    public function showClansGraph()
    {
        $this->showingGraph = true;            // Affiche le graphique
        $this->chartType = 'clans';            // Définit le type de graphique à "clans"
    }

    /**
     * Affiche le graphique des utilisateurs
     */
    public function showUsersGraph()
    {
        $this->showingGraph = true;            // Affiche le graphique
        $this->chartType = 'users';            // Définit le type de graphique à "utilisateurs"
    }

    /**
     * Cache le graphique
     */
    public function hideGraph()
    {
        $this->showingGraph = false;           // Cache le graphique
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
        return view('livewire.global-leaderboard', [
            'topClans' => $this->topClans,         // Liste des meilleurs clans
            'topUsers' => $this->topUsers,         // Liste des meilleurs utilisateurs
            'refreshKey' => $this->refreshKey      // Clé de rafraîchissement pour forcer les mises à jour
        ]);
    }
}
