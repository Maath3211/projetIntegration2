<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class SidebarClans extends Component
{
    public $userClans;
    public $selectedClanId = 'global';
    
    protected $listeners = ['switchedClan'];

    public function mount($userClans)
    {
        $this->userClans = $userClans;
    }

    public function selectClan($clanId)
    {
        
        if ($this->selectedClanId !== $clanId) {
            $this->selectedClanId = $clanId;
            $this->dispatch('clanSelected', $clanId);
        }
    }

    public function switchedClan($clanId)
    {
        $this->selectedClanId = $clanId;
    }

    public function render()
    {
        return view('livewire.sidebar-clans');
    }
}