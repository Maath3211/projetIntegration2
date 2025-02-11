<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SidebarClans extends Component
{
    public $userClans;

    public function mount($userClans)
    {
        $this->userClans = $userClans;
    }

    public function selectClan($clanId)
    {
        $this->dispatch('clanSelected', $clanId);
    }

    public function render()
    {
        return view('livewire.sidebar-clans');
    }
}
