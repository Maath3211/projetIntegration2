<?php

namespace App\Http\Livewire;

use Livewire\Component;

class GlobalLeaderboard extends Component
{
    public $showingGraph = false;
    public $topClans;
    public $topUsers;
    public $chartType = 'users'; // Default to showing user data

    protected $listeners = [
        'closeGraph' => 'hideGraph'
    ];

    public function mount($topClans, $topUsers)
    {
        $this->topClans = $topClans;
        $this->topUsers = $topUsers;
    }

    public function showGraph($chartType = 'users')
    {
        $this->chartType = $chartType;
        $this->showingGraph = true;
    }

    public function showClansGraph()
    {
        $this->showGraph('clans');
    }

    public function showUsersGraph()
    {
        $this->showGraph('users');
    }

    public function hideGraph()
    {
        $this->showingGraph = false;
    }

    public function render()
    {
        return view('livewire.global-leaderboard');
    }
}