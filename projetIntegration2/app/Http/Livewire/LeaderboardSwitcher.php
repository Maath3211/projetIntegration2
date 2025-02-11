<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class LeaderboardSwitcher extends Component
{
    public $selectedClanId = 'global';
    public $topClans;
    public $topUsers;

    protected $listeners = ['clanSelected' => 'updateSelectedClan'];

    public function mount($topClans = null, $topUsers = null)
    {
        $this->topClans = $topClans ?? collect();
        $this->topUsers = $topUsers ?? collect();
        Log::debug('LeaderboardSwitcher mounted', [
            'initial_clan' => $this->selectedClanId,
            'has_topClans' => !empty($this->topClans),
            'has_topUsers' => !empty($this->topUsers)
        ]);
    }

    public function updateSelectedClan($clanId)
    {
        Log::debug('updateSelectedClan called', ['clanId' => $clanId]);
        $this->selectedClanId = $clanId;
    }

    public function render()
    {
        Log::debug('LeaderboardSwitcher rendering', ['current_clan' => $this->selectedClanId]);
        return view('livewire.leaderboard-switcher');
    }
}
