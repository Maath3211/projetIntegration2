<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Clan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class SidebarClans extends Component
{
    // Propriétés publiques accessibles dans la vue
    public $userClans;           // Collection des clans de l'utilisateur
    public $selectedClanId;      // ID du clan actuellement sélectionné
    public $refreshKey = 0;      // Clé utilisée pour forcer le rafraîchissement du composant

    // Dans Livewire 3, on utilise getListeners() au lieu de $listeners
    protected function getListeners()
    {
        return [
            'switchedClan' => 'selectClan',         // Écoute les changements de clan depuis d'autres composants
            'localeChanged' => 'handleLocaleChanged' // Écoute les changements de langue
        ];
    }

    /**
     * Méthode exécutée lors de l'initialisation du composant
     * 
     * @param string|int|null $selectedClanId ID du clan sélectionné par défaut
     */
    public function mount($selectedClanId = null)
    {
        // Initialise avec le clan fourni ou 'global' par défaut
        $this->selectedClanId = $selectedClanId ?: 'global';
        // Charge les clans de l'utilisateur connecté
        $this->loadUserClans();
        
        // Définit la langue à partir de la session
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
    }
    
    /**
     * Charge les clans auxquels l'utilisateur est abonné
     */
    private function loadUserClans()
    {
        // Journal pour déboguer l'état de l'authentification
        Log::debug('Chargement des clans utilisateur, Auth::check(): ' . (Auth::check() ? 'vrai' : 'faux'));
        
        if (Auth::check()) {
            // Récupère l'ID de l'utilisateur connecté
            $userId = Auth::id();
            
            // Charge les clans à travers une requête à la base de données
            $this->userClans = DB::table('clan_users')
                ->join('clans', 'clans.id', '=', 'clan_users.clan_id')   // Joint la table des clans
                ->where('clan_users.user_id', $userId)                   // Filtre par utilisateur courant
                ->select('clans.id as clan_id', 'clans.nom as clan_nom', 'clans.image as clan_image')  // Sélectionne les données nécessaires
                ->get();
                
            // Journal avec les détails des clans chargés
            Log::debug('Clans chargés pour l\'utilisateur', [
                'user_id' => $userId,
                'clans_count' => $this->userClans->count(),
                'clans' => $this->userClans->toArray()
            ]);
        } else {
            // Pour le développement/test, charge des données de test si non authentifié
            // À commenter en production
            $this->userClans = DB::table('clans')
                ->limit(3)                                               // Limite à 3 clans pour les tests
                ->select('id as clan_id', 'nom as clan_nom', 'image as clan_image')
                ->get();
            
            // Journal avec les clans de test chargés
            Log::debug('Utilisateur non authentifié, chargement des clans de test', [
                'clans_count' => $this->userClans->count()
            ]);
        }
    }
    
    /**
     * Sélectionne un clan et émet un événement pour informer les autres composants
     * 
     * @param string|int $clanId ID du clan sélectionné ou 'global'
     */
    public function selectClan($clanId)
    {
        // Met à jour le clan sélectionné localement
        $this->selectedClanId = $clanId;
        
        // Émet un événement pour que les autres composants soient informés du changement
        $this->dispatch('clanSelected', $clanId);
        
        // Journal pour le débogage des événements
        Log::debug('Événement clanSelected envoyé depuis SidebarClans', ['clan_id' => $clanId]);
    }

    /**
     * Gère le changement de langue de l'application
     * 
     * @param string|array|null $params Paramètres contenant la langue
     */
    public function handleLocaleChanged($params = null)
    {
        // Extrait la langue depuis les paramètres
        $locale = null;
        if (is_string($params)) {
            $locale = $params;
        } elseif (is_array($params) && isset($params['locale'])) {
            $locale = $params['locale'];
        }
        
        // Met à jour la langue si elle est valide
        if ($locale && in_array($locale, ['en', 'fr'])) {
            Session::put('locale', $locale);                 // Enregistre la langue dans la session
            App::setLocale($locale);                         // Définit la langue de l'application
        }
        
        // S'assure également que la langue est définie depuis la session
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        
        // Force le rafraîchissement du composant
        $this->refreshKey++;
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
        return view('livewire.sidebar-clans', [
            'userClans' => $this->userClans,             // Liste des clans de l'utilisateur
            'selectedClanId' => $this->selectedClanId,   // ID du clan actuellement sélectionné
            'refreshKey' => $this->refreshKey            // Clé de rafraîchissement pour forcer les mises à jour
        ]);
    }
}