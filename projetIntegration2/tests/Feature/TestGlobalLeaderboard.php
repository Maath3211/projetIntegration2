<?php

namespace Tests\Feature;

use App\Http\Livewire\GlobalLeaderboard;
use App\Models\Clan;
use App\Models\User;
use App\Models\Score;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class TestGlobalLeaderboard extends TestCase
{
    use DatabaseTransactions;

    /**
     * Create sample data for testing
     */
    private function createSampleData()
    {
        // Create users and scores
        $users = User::factory()->count(5)->create();
        foreach ($users as $user) {
            Score::create([
                'user_id' => $user->id,
                'score' => rand(50, 100),
                'date' => Carbon::now()
            ]);
        }

        // Create clans
        $clan1 = Clan::factory()->create();
        $clan2 = Clan::factory()->create();

        // Associate first two users with clan1, rest with clan2
        DB::table('clan_users')->insert([
            ['user_id' => $users[0]->id, 'clan_id' => $clan1->id],
            ['user_id' => $users[1]->id, 'clan_id' => $clan1->id],
            ['user_id' => $users[2]->id, 'clan_id' => $clan2->id],
            ['user_id' => $users[3]->id, 'clan_id' => $clan2->id],
            ['user_id' => $users[4]->id, 'clan_id' => $clan2->id]
        ]);
    }

    /**
     * Get top users collection
     */
    private function getTopUsers(): SupportCollection
    {
        return DB::table('users')
            ->join('scores', 'users.id', '=', 'scores.user_id')
            ->select(
                'users.prenom',
                'users.nom',
                'users.imageProfil',
                'users.email',
                DB::raw('SUM(scores.score) as total_score')
            )
            ->groupBy('users.id', 'users.prenom', 'users.nom', 'users.imageProfil', 'users.email')
            ->orderByDesc('total_score')
            ->limit(10)
            ->get();
    }

    /**
     * Get top clans collection
     */
    private function getTopClans(): SupportCollection
    {
        return DB::table('clan_users as cu')
            ->join(DB::raw('(SELECT user_id, SUM(score) as total_score FROM scores GROUP BY user_id) as su'), 'cu.user_id', '=', 'su.user_id')
            ->join('clans', 'clans.id', '=', 'cu.clan_id')
            ->select('cu.clan_id', 'clans.nom as clan_nom', 'clans.image as clan_image', DB::raw('SUM(su.total_score) as clan_total_score'))
            ->groupBy('cu.clan_id', 'clans.nom', 'clans.image')
            ->orderByDesc('clan_total_score')
            ->limit(10)
            ->get();
    }

    #[Test]
    public function le_composant_global_leaderboard_peut_etre_rendu()
    {
        // Create sample data first
        $this->createSampleData();

        // Get the data required by the component
        $topUsers = $this->getTopUsers();
        $topClans = $this->getTopClans();

        // Test if component can be rendered with required data
        $component = Livewire::test(GlobalLeaderboard::class, [
            'topUsers' => $topUsers,
            'topClans' => $topClans
        ]);

        $component->assertStatus(200);
    }

    #[Test]
    public function le_composant_affiche_les_meilleurs_utilisateurs()
    {
        // Create sample data
        $this->createSampleData();

        // Get the data
        $topUsers = $this->getTopUsers();
        $topClans = $this->getTopClans();

        // Test the component with both required datasets
        $component = Livewire::test(GlobalLeaderboard::class, [
            'topUsers' => $topUsers,
            'topClans' => $topClans
        ]);

        // Assert the component has the topUsers property
        $component->assertViewHas('topUsers');

        // Optionally, add more specific assertions about the users
        $this->assertGreaterThan(0, $topUsers->count());
    }

    #[Test]
    public function le_composant_affiche_les_meilleurs_clans()
    {
        // Create sample data
        $this->createSampleData();

        // Get the data
        $topUsers = $this->getTopUsers();
        $topClans = $this->getTopClans();

        // Test the component with both required datasets
        $component = Livewire::test(GlobalLeaderboard::class, [
            'topUsers' => $topUsers,
            'topClans' => $topClans
        ]);

        // Assert the component has the topClans property
        $component->assertViewHas('topClans');

        // Optionally, add more specific assertions about the clans
        $this->assertGreaterThan(0, $topClans->count());
    }

    #[Test]
    public function le_composant_peut_basculer_en_mode_graphique()
    {
        // Create sample data
        $this->createSampleData();

        // Get the data
        $topUsers = $this->getTopUsers();
        $topClans = $this->getTopClans();

        // Test the component with all required data
        $component = Livewire::test(GlobalLeaderboard::class, [
            'topUsers' => $topUsers,
            'topClans' => $topClans
        ]);

        // By default, graph is hidden
        $component->assertSet('showingGraph', false);

        // Show clans graph
        $component->call('showClansGraph');
        $component->assertSet('showingGraph', true);
        $component->assertSet('chartType', 'clans');

        // Show users graph
        $component->call('showUsersGraph');
        $component->assertSet('showingGraph', true);
        $component->assertSet('chartType', 'users');

        // Hide graph
        $component->call('hideGraph');
        $component->assertSet('showingGraph', false);
    }

    #[Test]
    public function le_composant_gere_le_changement_de_langue()
    {
        // Create sample data
        $this->createSampleData();

        // Get the data
        $topUsers = $this->getTopUsers();
        $topClans = $this->getTopClans();

        // Test the component with all required data
        $component = Livewire::test(GlobalLeaderboard::class, [
            'topUsers' => $topUsers,
            'topClans' => $topClans
        ]);

        // Change language to French
        $component->call('handleLocaleChanged', ['locale' => 'fr']);
        $this->assertEquals('fr', app()->getLocale());

        // Change language to English
        $component->call('handleLocaleChanged', ['locale' => 'en']);
        $this->assertEquals('en', app()->getLocale());
    }
}
