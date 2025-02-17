<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SidebarClans extends Component
{
    public $userClans;
    public $selectedClanId = 'global'; // Default value

    public function mount($userClans)
    {
        $this->userClans = $userClans;
    }

    public function selectClan($clanId)
    {
        $this->selectedClanId = $clanId; // Update local state
        $this->emit('clanSelected', $clanId); // This method exists on Livewire\Component (version 2+)
    }

    public function render()
    {
        return view('livewire.sidebar-clans', [
            'userClans'      => $this->userClans,
            'selectedClanId' => $this->selectedClanId, // Pass the variable to the view
        ]);
    }
}
