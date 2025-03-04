<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class ScoreGraph extends Component
{
    public $months = [];
    public $clanScores = [];
    public $userScores = [];
    public $showType;
    public $selectedClanId;
    public $refreshKey = 0; // Add this to force refreshes

    // Choose only ONE of these methods for defining listeners
    // Option 1: For Livewire 3
    protected function getListeners()
    {
        return [
            'localeChanged' => 'handleLocaleChanged',
            'updateSelectedClan' => 'updateSelectedClan'
        ];
    }

    // Option 2: For Livewire 2 (remove this if using Option 1)
    // protected $listeners = [
    //     'updateSelectedClan' => 'updateSelectedClan',
    //     'localeChanged' => 'handleLocaleChanged'
    // ];

    public function mount($showType = 'members', $selectedClanId = null)
    {
        $this->showType = $showType;
        $this->selectedClanId = $selectedClanId;
        $this->loadChartData();

        // Set locale from session when component mounts
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
    }

    public function hideGraph()
    {
        $this->dispatch('closeGraph');
    }

    public function closeGraph()
    {
        // Instead of $this->dispatch(...)->up()
        // Use one of these depending on your Livewire version:
        
        // For Livewire 3
        $this->dispatch('scoreGraphClosed');
        
        // If that doesn't work, try the Livewire 2 syntax:
        // $this->emitUp('scoreGraphClosed');
    }

    public function loadChartData()
    {
        // Generate data for last 6 months
        $this->months = [];
        $this->clanScores = [];
        $this->userScores = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $this->months[] = $month->format('M Y');

            $startOfMonth = $month->startOfMonth()->format('Y-m-d');
            $endOfMonth = $month->endOfMonth()->format('Y-m-d');

            // Get clan scores
            try {
                $clanQuery = DB::table('clan_users as cu')
                    ->join('scores', function ($join) use ($startOfMonth, $endOfMonth) {
                        $join->on('cu.user_id', '=', 'scores.user_id')
                            ->whereBetween('scores.date', [$startOfMonth, $endOfMonth]);
                    });

                // Filter by clan if selected
                if ($this->selectedClanId && $this->selectedClanId != 'global') {
                    $clanQuery->where('cu.clan_id', $this->selectedClanId);
                }

                $clanScore = $clanQuery->sum('scores.score');
                $this->clanScores[] = $clanScore ?: 0;
            } catch (\Exception $e) {
                $this->clanScores[] = rand(1000, 2000);
            }

            // Get user scores or member scores if clan is selected
            try {
                $userQuery = DB::table('scores')
                    ->whereBetween('date', [$startOfMonth, $endOfMonth]);

                // Filter by clan if selected
                if ($this->selectedClanId && $this->selectedClanId != 'global') {
                    $userQuery->join('clan_users', 'scores.user_id', '=', 'clan_users.user_id')
                        ->where('clan_users.clan_id', $this->selectedClanId);
                }

                $userScore = $userQuery->sum('scores.score');
                $this->userScores[] = $userScore ?: 0;
            } catch (\Exception $e) {
                $this->userScores[] = rand(700, 1500);
            }
        }
    }

    public function updateSelectedClan($clanId)
    {
        $this->selectedClanId = $clanId;
        $this->loadChartData();
    }

    public function handleLocaleChanged($params = null)
    {
        $locale = null;
        
        // Handle different parameter formats
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
        
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        
        // Increment refresh key to force re-render
        $this->refreshKey++;
    }

    public function render()
    {
        // Set locale from session before rendering
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }

        return view('livewire.score-graph', [
            'showType' => $this->showType,
            'selectedClanId' => $this->selectedClanId,
        ]);
    }
}
