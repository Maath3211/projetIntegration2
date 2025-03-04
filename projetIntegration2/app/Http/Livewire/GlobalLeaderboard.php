<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class GlobalLeaderboard extends Component
{
    public $topClans;
    public $topUsers;
    public $showingGraph = false;
    public $chartType = 'clans';
    public $refreshKey = 0; // Add this to force refreshes

    protected function getListeners()
    {
        return [
            'localeChanged' => 'handleLocaleChanged',
            'scoreGraphClosed' => 'hideGraph'
        ];
    }

    public function mount($topClans = null, $topUsers = null)
    {
        // Your existing mount logic
        $this->topClans = $topClans;
        $this->topUsers = $topUsers;

        // Set locale from session
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
    }

    public function handleLocaleChanged($params = null)
    {
        // Get locale from params array
        $locale = is_array($params) && isset($params['locale']) ? $params['locale'] : null;

        // Update locale if valid
        if ($locale && in_array($locale, ['en', 'fr'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);
            $this->refreshKey = now()->timestamp; // Force refresh
        }
    }

    public function showClansGraph()
    {
        $this->showingGraph = true;
        $this->chartType = 'clans';
    }

    public function showUsersGraph()
    {
        $this->showingGraph = true;
        $this->chartType = 'users';
    }

    public function hideGraph()
    {
        $this->showingGraph = false;
    }

    public function render()
    {
        // Set locale from session before rendering
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        // Your existing render logic
        return view('livewire.global-leaderboard', [
            'topClans' => $this->topClans,
            'topUsers' => $this->topUsers,
            'refreshKey' => $this->refreshKey
        ]);
    }
}
