<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class LeaderboardSwitcher extends Component
{
    public $selectedClanId = 'global';
    public $topClans;
    public $topUsers;
    public $refreshCounter = 0; // Add counter

    protected $listeners = [
        'clanSelected' => 'updateSelectedClan',
        'refreshComponent' => '$refresh'
    ];

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
        $this->refreshCounter++; // Increment on each change
    }

    public function selectClan($clanId)
    {
        Log::debug('selectClan called', ['from' => $this->selectedClanId, 'to' => $clanId]);
        // Do not reset here; simply update the selected clan
        $this->selectedClanId = $clanId;
        $this->emit('clanSelected', $clanId);
        $this->emit('refreshComponent');
    }

    public function render()
    {
        Log::debug('LeaderboardSwitcher rendering', ['selectedClanId' => $this->selectedClanId]);
        return view('livewire.leaderboard-switcher')->with('refreshCounter', $this->refreshCounter);
    }
}
