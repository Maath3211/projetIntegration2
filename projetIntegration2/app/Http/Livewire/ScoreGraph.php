<?php
// app/Http/Livewire/ScoreGraph.php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScoreGraph extends Component
{
    public $graphData = [];

    public function mount()
    {
        // Generate sample data since the actual data might be missing
        $this->generateSampleData();
    }

    // This is a simple method to generate sample data for testing
    private function generateSampleData()
    {
        // Generate last 6 months of dates
        $dates = [];
        for ($i = 5; $i >= 0; $i--) {
            $dates[] = Carbon::now()->subMonths($i)->format('Y-m');
        }
        
        // Generate sample data for clans
        $clanScores = [1200, 1350, 1500, 1450, 1600, 1750];
        
        // Generate sample data for users
        $userScores = [800, 950, 1100, 1250, 1300, 1400];

        $this->graphData = [
            'labels' => $dates,
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
    }

    public function render()
    {
        // Use dispatch instead of emit for Livewire v3
        // And use the compatibility method for both v2 and v3
        if (method_exists($this, 'dispatch')) {
            // Livewire v3
            $this->dispatch('chartDataUpdated', $this->graphData);
        } else if (method_exists($this, 'emitSelf')) {
            // Livewire v2
            $this->emitSelf('chartDataUpdated', $this->graphData);
        }
        
        return view('livewire.score-graph', [
            'graphData' => $this->graphData
        ]);
    }
}