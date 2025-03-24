<?php

namespace Tests\Feature;

use App\Http\Livewire\ClanLeaderboard;
use App\Models\Clan;
use App\Models\User;
use App\Models\Score;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

class TestClanLeaderboard extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function le_composant_clan_leaderboard_peut_etre_rendu()
    {
        // Créer un clan pour le test
        $clan = Clan::factory()->create();
        
        // Tester si le composant peut être rendu
        $composant = Livewire::test(ClanLeaderboard::class, ['selectedClanId' => $clan->id]);
        $composant->assertStatus(200);
    }

    #[Test]
    public function le_composant_affiche_les_meilleurs_membres()
    {
        // Créer un clan pour le test
        $clan = Clan::factory()->create();
        
        // Créer des utilisateurs pour le test
        $utilisateurs = User::factory()->count(5)->create();
        
        // Associer les utilisateurs au clan
        foreach ($utilisateurs as $utilisateur) {
            DB::table('clan_users')->insert([
                'user_id' => $utilisateur->id,
                'clan_id' => $clan->id
            ]);
            
            // Ajouter des scores pour chaque utilisateur
            Score::create([
                'user_id' => $utilisateur->id,
                'score' => rand(10, 100),
                'date' => Carbon::now()
            ]);
        }
        
        // Tester si les données des meilleurs membres sont chargées
        $composant = Livewire::test(ClanLeaderboard::class, ['selectedClanId' => $clan->id]);
        $composant->assertViewHas('meilleursMembres');
        
        // Vérifier qu'il y a des données de membres
        $meilleursMembres = $composant->get('meilleursMembres');
        $this->assertNotNull($meilleursMembres);
        $this->assertGreaterThan(0, count($meilleursMembres));
    }

    #[Test]
    public function le_composant_affiche_les_meilleures_ameliorations()
    {
        // Créer un clan pour le test
        $clan = Clan::factory()->create();
        
        // Créer des utilisateurs pour le test
        $utilisateurs = User::factory()->count(5)->create();
        
        // Associer les utilisateurs au clan et créer des scores
        foreach ($utilisateurs as $utilisateur) {
            DB::table('clan_users')->insert([
                'user_id' => $utilisateur->id,
                'clan_id' => $clan->id
            ]);
            
            // Scores récents (dans le dernier mois)
            Score::create([
                'user_id' => $utilisateur->id,
                'score' => rand(10, 100),
                'date' => Carbon::now()->subDays(rand(1, 25))
            ]);
        }
        
        // Tester si les données d'amélioration sont chargées
        $composant = Livewire::test(ClanLeaderboard::class, ['selectedClanId' => $clan->id]);
        $composant->assertViewHas('topScoreImprovement');
        
        // Vérifier qu'il y a des données d'amélioration
        $ameliorations = $composant->get('topScoreImprovement');
        $this->assertNotNull($ameliorations);
    }

    #[Test]
    public function le_composant_peut_basculer_en_mode_graphique()
    {
        // Créer un clan pour le test
        $clan = Clan::factory()->create();
        
        // Tester si le composant peut basculer en mode graphique
        $composant = Livewire::test(ClanLeaderboard::class, ['selectedClanId' => $clan->id]);
        $composant->assertSet('showingGraph', false);
        
        // Afficher le graphique des membres
        $composant->call('showMembersGraph');
        $composant->assertSet('showingGraph', true);
        $composant->assertSet('chartType', 'members');
        
        // Masquer le graphique
        $composant->call('hideGraph');
        $composant->assertSet('showingGraph', false);
        
        // Afficher le graphique des améliorations
        $composant->call('showImprovementsGraph');
        $composant->assertSet('showingGraph', true);
        $composant->assertSet('chartType', 'improvements');
    }
}