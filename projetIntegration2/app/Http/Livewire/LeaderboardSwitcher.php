<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Models\Clan;
use Illuminate\Support\Facades\DB;

class LeaderboardSwitcher extends Component
{
    public $selectedClanId = 'global';
    public $topClans;
    public $topUsers;
    public $refreshKey;
    
    protected $listeners = ['clanSelected' => 'updateSelectedClan'];

    public function mount($topClans = null, $topUsers = null)
    {
        $this->topClans = $topClans ?? collect();
        $this->topUsers = $topUsers ?? collect();
        $this->refreshKey = now()->timestamp;
    }

    public function updateSelectedClan($clanId)
    {
        Log::debug('LeaderboardSwitcher received clanSelected event', [
            'current' => $this->selectedClanId,
            'new' => $clanId,
            'timestamp' => now()
        ]);

        // Store the previous state before updating
        $previousClanId = $this->selectedClanId;
        
        if ($previousClanId !== $clanId) {
            $this->selectedClanId = $clanId;
            $this->refreshKey = now()->timestamp;

            if ($clanId === 'global') {
                // Refresh global data
                $this->topUsers = DB::table('users')
                    ->join('scores', 'users.id', '=', 'scores.user_id')
                    ->select(
                        'users.prenom',
                        'users.nom',
                        'users.imageProfil',
                        DB::raw('SUM(scores.score) as total_score')
                    )
                    ->groupBy('users.id', 'users.prenom', 'users.nom', 'users.imageProfil')
                    ->orderByDesc('total_score')
                    ->limit(10)
                    ->get();

                $this->topClans = DB::table('clan_users as cu')
                    ->join(DB::raw('(SELECT user_id, SUM(score) as total_score FROM scores GROUP BY user_id) as su'), 'cu.user_id', '=', 'su.user_id')
                    ->join('clans', 'clans.id', '=', 'cu.clan_id')
                    ->select('cu.clan_id', 'clans.nom as clan_nom', 'clans.image as clan_image', DB::raw('SUM(su.total_score) as clan_total_score'))
                    ->groupBy('cu.clan_id', 'clans.nom', 'clans.image')
                    ->orderByDesc('clan_total_score')
                    ->limit(10)
                    ->get();
            }

            $this->dispatch('switchedClan', clanId: $clanId);
        }
    }

    public function render()
    {
        Log::debug('LeaderboardSwitcher rendering', [
            'selectedClanId' => $this->selectedClanId,
            'refreshKey' => $this->refreshKey,
            'hasTopClans' => $this->topClans->isNotEmpty(),
            'hasTopUsers' => $this->topUsers->isNotEmpty()
        ]);

        return view('livewire.leaderboard-switcher', [
            'selectedClan' => $this->selectedClanId !== 'global' ? Clan::find($this->selectedClanId) : null
        ]);
    }
}