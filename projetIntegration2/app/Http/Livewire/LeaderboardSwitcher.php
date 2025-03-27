<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\Clan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LeaderboardSwitcher extends Component
{
    // Propriétés publiques accessibles dans la vue
    public $selectedClanId = 'global';     // ID du clan sélectionné, 'global' par défaut
    public $topClans;                      // Collection des meilleurs clans
    public $topUsers;                      // Collection des meilleurs utilisateurs
    public $refreshKey;                    // Clé utilisée pour forcer le rafraîchissement du composant
    
    // Événements écoutés par ce composant
    protected $listeners = [
        'clanSelected' => 'updateSelectedClan',     // Événement de sélection d'un clan
        'localeChanged' => 'handleLocaleChanged',   // Événement de changement de langue
    ];

    /**
     * Méthode exécutée lors de l'initialisation du composant
     * 
     * @param array|null $topClans Collection des meilleurs clans (optionnel)
     * @param array|null $topUsers Collection des meilleurs utilisateurs (optionnel)
     */
    public function mount($topClans = null, $topUsers = null)
    {
        // Initialisation des collections avec les valeurs passées ou des collections vides
        $this->topClans = $topClans ?? collect();
        $this->topUsers = $topUsers ?? collect();
        // Création d'une clé de rafraîchissement basée sur le timestamp actuel
        $this->refreshKey = now()->timestamp;
    }

    /**
     * Met à jour le clan sélectionné et rafraîchit les données si nécessaire
     * 
     * @param string $clanId ID du clan sélectionné ou 'global' pour la vue globale
     */
    public function updateSelectedClan($clanId)
    {
        // Stocke l'ID du clan précédemment sélectionné pour comparaison
        $previousClanId = $this->selectedClanId;

        // Ne rafraîchit que si le clan sélectionné a changé
        if ($previousClanId !== $clanId) {
            $this->selectedClanId = $clanId;
            $this->refreshKey = now()->timestamp;  // Force un rafraîchissement du composant

            // Si la vue globale est sélectionnée, rafraîchit les données globales
            if ($clanId === 'global') {
                // Récupère les 10 meilleurs utilisateurs par score total
                $this->topUsers = DB::table('users')
                    ->join('scores', 'users.id', '=', 'scores.user_id')   // Jointure avec la table des scores
                    ->select(
                        'users.prenom',
                        'users.nom',
                        'users.imageProfil',
                        'users.email as email',
                        DB::raw('SUM(scores.score) as total_score')       // Calcul du score total de l'utilisateur
                    )
                    ->groupBy('users.id', 'users.prenom', 'users.nom', 'users.imageProfil', 'users.email')  // Regroupement par utilisateur
                    ->orderByDesc('total_score')                          // Tri par score total décroissant
                    ->limit(10)                                           // Limite aux 10 premiers résultats
                    ->get();

                // Récupère les 10 meilleurs clans par score total combiné de leurs membres
                $this->topClans = DB::table('clan_users as cu')
                    // Sous-requête pour calculer les scores totaux par utilisateur
                    ->join(DB::raw('(SELECT user_id, SUM(score) as total_score FROM scores GROUP BY user_id) as su'), 'cu.user_id', '=', 'su.user_id')
                    ->join('clans', 'clans.id', '=', 'cu.clan_id')        // Jointure avec la table des clans
                    ->select('cu.clan_id', 'clans.nom as clan_nom', 'clans.image as clan_image', DB::raw('SUM(su.total_score) as clan_total_score'))  // Sélection des informations et calcul du score total du clan
                    ->groupBy('cu.clan_id', 'clans.nom', 'clans.image')   // Regroupement par clan
                    ->orderByDesc('clan_total_score')                     // Tri par score total décroissant
                    ->limit(10)                                           // Limite aux 10 premiers résultats
                    ->get();
            }

            // Émet un événement pour informer les autres composants du changement de clan
            $this->dispatch('switchedClan', clanId: $clanId);
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
     * Méthode de rendu du composant
     */
    public function render()
    {
        // Vérifie si la langue est définie dans la session et l'applique
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        // Retourne la vue avec les données nécessaires
        return view('livewire.leaderboard-switcher', [
            // Passe le clan sélectionné à la vue, ou null si 'global' est sélectionné
            'selectedClan' => $this->selectedClanId !== 'global' ? Clan::find($this->selectedClanId) : null
        ]);
    }
}
