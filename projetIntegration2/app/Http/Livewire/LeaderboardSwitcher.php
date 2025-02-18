<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class LeaderboardSwitcher extends Component
{
    public $selectedClanId = 'global';
    public $topClans;
    public $topUsers;
    public $refreshKey;

    protected $listeners = [
        'clanSelected' => 'updateSelectedClan',
        'refreshComponent' => '$refresh'
    ];

    public function mount($topClans = null, $topUsers = null)
    {
        $this->topClans = $topClans ?? collect();
        $this->topUsers = $topUsers ?? collect();
        $this->refreshKey = now()->timestamp;
    }

    public function updateSelectedClan($params)
    {
        $clanId = $params['clanId'] ?? 'global';
        Log::debug('LeaderboardSwitcher updating clan', [
            'from' => $this->selectedClanId,
            'to' => $clanId
        ]);
        
        // Reset state and update
        $this->selectedClanId = $clanId;
        $this->refreshKey = now()->timestamp;
        
        // Force component refresh
        $this->dispatch('refreshComponent');
    }

    public function render()
    {
        Log::debug('LeaderboardSwitcher rendering', [
            'selectedClanId' => $this->selectedClanId,
            'refreshKey' => $this->refreshKey
        ]);
        
        return view('livewire.leaderboard-switcher');
    }
}
