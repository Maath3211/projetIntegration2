<?php
// app/Http/Livewire/ScoreGraph.php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\WeeklyScore;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScoreGraph extends Component
{
    public $showModal = false;
    public $graphData = [];

    protected $listeners = ['showScoreGraph' => 'show'];

    public function show()
    {
        $this->showModal = true;
        $this->dispatch('showScoreGraph');
    }

    public function mount()
    {
        $this->loadGraphData();
    }

    private function loadGraphData()
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        
        // Get data and prepare it for Chart.js
        $weeklyData = WeeklyScore::where('week_start', '>=', $sixMonthsAgo)
            ->orderBy('week_start')
            ->get()
            ->groupBy('week_start');

        $this->graphData = [
            'labels' => $weeklyData->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'Score Evolution',
                    'data' => $weeklyData->map->avg('score')->values()->toArray(),
                    'borderColor' => '#4CAF50',
                    'tension' => 0.1
                ]
            ]
        ];
    }

    public function render()
    {
        // Generate last 6 months of dates
        $dates = collect(range(0, 5))->map(function($i) {
            return Carbon::now()->subMonths($i)->format('Y-m');
        })->reverse();

        // Sample data until WeeklyScore table is populated
        $clanScores = [1200, 1350, 1500, 1450, 1600, 1750];
        $userScores = [800, 950, 1100, 1250, 1300, 1400];

        $graphData = [
            'labels' => $dates->toArray(),
            'datasets' => [
                [
                    'label' => 'Score des Clans',
                    'data' => $clanScores,
                    'borderColor' => '#4CAF50',
                    'backgroundColor' => 'rgba(76, 175, 80, 0.1)',
                    'tension' => 0.4,
                    'fill' => true
                ],
                [
                    'label' => 'Score des Utilisateurs',
                    'data' => $userScores,
                    'borderColor' => '#2196F3',
                    'backgroundColor' => 'rgba(33, 150, 243, 0.1)',
                    'tension' => 0.4,
                    'fill' => true
                ]
            ]
        ];

        return view('livewire.score-graph', ['graphData' => $graphData]);
    }
}
