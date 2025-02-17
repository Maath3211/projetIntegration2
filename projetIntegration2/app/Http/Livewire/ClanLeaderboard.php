<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Clan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ClanLeaderboard extends Component
{
    public $selectedClanId;
    public $selectedClan;
    public $meilleursMembres;
    public $topScoreImprovement;

    protected $listeners = ['clanSelected' => 'updateClan'];

    public function mount($selectedClanId)
    {
        $this->updateClan($selectedClanId);
    }

    public function updateClan($clanId)
    {
        $this->selectedClanId = $clanId;
        $oneMonthAgo = \Carbon\Carbon::now()->subMonth();
        Log::debug('Updating clan with ID: ' . $clanId);

        try {
            $this->selectedClan = Clan::find($clanId);
        } catch (\Exception $e) {
            Log::error('Error querying the Clan model: ' . $e->getMessage());
        }

        if (!$this->selectedClan) {
            Log::error("Clan with ID {$clanId} not found.");
        }

        // Fetch meilleursMembres for the selected clan.
        $this->meilleursMembres = DB::table('users')
            ->join('clan_users', 'users.id', '=', 'clan_users.user_id')
            ->join('scores', 'users.id', '=', 'scores.user_id')
            ->where('clan_users.clan_id', $clanId)
            ->select(
                'users.imageProfil as user_image', // Adjust the field if needed.
                'users.nom as user_nom',
                'users.prenom as user_prenom',
                DB::raw('SUM(scores.score) as user_total_score')
            )
            ->groupBy('users.id', 'users.imageProfil', 'users.nom', 'users.prenom')
            ->orderByDesc('user_total_score')
            ->limit(10)
            ->get();

        $this->topScoreImprovement = DB::table('users')
            ->join('clan_users', 'users.id', '=', 'clan_users.user_id')
            ->join('scores', 'users.id', '=', 'scores.user_id')
            ->where('clan_users.clan_id', $clanId)
            ->where('scores.created_at', '>=', $oneMonthAgo)
            ->select(
                'users.imageProfil as user_image',
                'users.nom as user_nom',
                'users.prenom as user_prenom',
                DB::raw('SUM(scores.score) as score_improvement')
            )
            ->groupBy('users.id', 'users.imageProfil', 'users.nom', 'users.prenom') 
            ->orderByDesc('score_improvement')
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.clan-leaderboard', [
            'selectedClan' => $this->selectedClan,
            'meilleursMembres' => $this->meilleursMembres,
            'topScoreImprovement' => $this->topScoreImprovement,
        ]);
    }
}
