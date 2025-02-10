<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Clan;

class ClanLeaderboard extends Component
{
    public $selectedClanId;
    public $selectedClan;

    protected $listeners = ['clanSelected' => 'updateClan'];

    public function updateClan($clanId)
    {
        $this->selectedClanId = $clanId;
        $this->selectedClan = Clan::find($clanId);
    }

    public function render()
    {
        return view('livewire.clan-leaderboard', [
            'selectedClan' => $this->selectedClan,
        ]);
    }
}
