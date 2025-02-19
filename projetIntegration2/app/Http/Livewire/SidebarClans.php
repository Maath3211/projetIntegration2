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
        Log::debug('SidebarClans mounted', [
            'initial_clan' => $this->selectedClanId,
            'clans_count' => $this->userClans->count()
        ]);
    }

    public function selectClan($clanId)
    {
        Log::debug('SidebarClans selectClan called', [
            'from' => $this->selectedClanId,
            'to' => $clanId
        ]);
        
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