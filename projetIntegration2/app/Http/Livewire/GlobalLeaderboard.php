<?php

namespace App\Http\Livewire;

use Livewire\Component;

class GlobalLeaderboard extends Component
{
    public $topClans;
    public $topUsers;

    public function mount($topClans, $topUsers)
    {
        $this->topClans = $topClans;
        $this->topUsers = $topUsers;
    }

    public function render()
    {
        return view('livewire.global-leaderboard');
    }
}
