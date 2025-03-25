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
    public $userClans;
    public $selectedClanId;
    public $refreshKey = 0;

    // In Livewire 3, use getListeners() instead of $listeners
    protected function getListeners()
    {
        return [
            'switchedClan' => 'selectClan',
            'localeChanged' => 'handleLocaleChanged'
        ];
    }

    public function mount($selectedClanId = null)
    {
        $this->selectedClanId = $selectedClanId ?: 'global';
        $this->loadUserClans();
        
        // Set locale from session
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
    }
    
    private function loadUserClans()
    {
        // Debug the auth state
        Log::debug('Loading user clans, Auth::check(): ' . (Auth::check() ? 'true' : 'false'));
        
        if (Auth::check()) {
            $userId = Auth::id();
            
            // Load clans through database query
            $this->userClans = DB::table('clan_users')
                ->join('clans', 'clans.id', '=', 'clan_users.clan_id')
                ->where('clan_users.user_id', $userId)
                ->select('clans.id as clan_id', 'clans.nom as clan_nom', 'clans.image as clan_image')
                ->get();
                
            Log::debug('Loaded clans for user', [
                'user_id' => $userId,
                'clans_count' => $this->userClans->count(),
                'clans' => $this->userClans->toArray()
            ]);
        } else {
            // For development/testing, load some test data when not authenticated
            // Comment this out in production
            $this->userClans = DB::table('clans')
                ->limit(3)
                ->select('id as clan_id', 'nom as clan_nom', 'image as clan_image')
                ->get();
            
            Log::debug('User not authenticated, loaded test clans', [
                'clans_count' => $this->userClans->count()
            ]);
        }
    }
    
    public function selectClan($clanId)
    {
        $this->selectedClanId = $clanId;
        
        // CHANGE THIS LINE - replace emit() with dispatch()
        $this->dispatch('clanSelected', $clanId);
        
        Log::debug('SidebarClans clanSelected dispatched', ['clan_id' => $clanId]);
    }

    public function handleLocaleChanged($params = null)
    {
        // Extract locale from parameters
        $locale = null;
        if (is_string($params)) {
            $locale = $params;
        } elseif (is_array($params) && isset($params['locale'])) {
            $locale = $params['locale'];
        }
        
        // Update locale if valid
        if ($locale && in_array($locale, ['en', 'fr'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);
        }
        
        // Also set from session
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        
        // Force component refresh
        $this->refreshKey++;
    }

    public function render()
    {
        // Set locale from session before rendering
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        
        return view('livewire.sidebar-clans', [
            'userClans' => $this->userClans,
            'selectedClanId' => $this->selectedClanId,
            'refreshKey' => $this->refreshKey
        ]);
    }
}