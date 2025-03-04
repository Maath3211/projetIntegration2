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
    public $selectedClanId;
    public $selectedClan;
    public $meilleursMembres;
    public $topScoreImprovement;
    public $showingGraph = false;
    public $chartType = 'members'; // Options: 'members', 'improvements'
    public $refreshKey = 0; // Add this to force refreshes


    protected function getListeners()
    {
        return [
            'localeChanged' => 'handleLocaleChanged',
            'scoreGraphClosed' => 'hideGraph',
            'clanSelected' => 'updateClan',
            'closeGraph' => 'hideGraph'
        ];
    }

    public function mount($selectedClanId = null)
    {
        // Set locale from session
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        $this->updateClan($selectedClanId);
    }

    public function updateClan($clanId)
    {
        $this->selectedClanId = $clanId;
        $oneMonthAgo = \Carbon\Carbon::now()->subMonth();
        Log::debug('Updating clan with ID: ' . $clanId);

        try {
            $this->selectedClan = Clan::find($clanId);
        } catch (\Exception $e) {
            Log::error('Error querying the Clan model: ' . $e->getMessage());
        }

        if (!$this->selectedClan) {
            Log::error("Clan with ID {$clanId} not found.");
        }

        // Fetch meilleursMembres for the selected clan.
        $this->meilleursMembres = DB::table('users')
            ->join('clan_users', 'users.id', '=', 'clan_users.user_id')
            ->join('scores', 'users.id', '=', 'scores.user_id')
            ->where('clan_users.clan_id', $clanId)
            ->select(
                'users.imageProfil as user_image',
                'users.nom as user_nom',
                'users.prenom as user_prenom',
                DB::raw('SUM(scores.score) as user_total_score')
            )
            ->groupBy('users.id', 'users.imageProfil', 'users.nom', 'users.prenom')
            ->orderByDesc('user_total_score')
            ->limit(10)
            ->get();

        $this->topScoreImprovement = DB::table('users')
            ->join('clan_users', 'users.id', '=', 'clan_users.user_id')
            ->join('scores', 'users.id', '=', 'scores.user_id')
            ->where('clan_users.clan_id', $clanId)
            ->where('scores.date', '>=', $oneMonthAgo)
            ->select(
                'users.imageProfil as user_image',
                'users.nom as user_nom',
                'users.prenom as user_prenom',
                DB::raw('SUM(scores.score) as score_improvement')
            )
            ->groupBy('users.id', 'users.imageProfil', 'users.nom', 'users.prenom')
            ->orderByDesc('score_improvement')
            ->limit(10)
            ->get();
    }

    public function showMembersGraph()
    {
        $this->chartType = 'members';
        $this->showingGraph = true;
    }

    public function showImprovementsGraph()
    {
        $this->chartType = 'improvements';
        $this->showingGraph = true;
    }

    public function hideGraph()
    {
        $this->showingGraph = false;
    }

    public function refreshComponent()
    {
        // Set locale from session
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        // Force a refresh of the component
        $this->emit('$refresh');
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

    public function render()
    {
        // Ensure the locale is set correctly before rendering
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        return view('livewire.clan-leaderboard', [
            'selectedClan' => $this->selectedClan,
            'meilleursMembres' => $this->meilleursMembres,
            'topScoreImprovement' => $this->topScoreImprovement,
            'refreshKey' => $this->refreshKey
        ]);
    }
}
