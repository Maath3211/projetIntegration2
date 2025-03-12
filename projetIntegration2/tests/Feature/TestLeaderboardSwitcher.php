<?php

namespace Tests\Feature;

use App\Http\Livewire\LeaderboardSwitcher;
use App\Models\Clan;
use App\Models\User;
use App\Models\Score;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

class TestLeaderboardSwitcher extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function le_composant_leaderboardswitcher_peut_etre_rendu()
    {
        // Tester si le composant peut être rendu sans erreurs
        $composant = Livewire::test(LeaderboardSwitcher::class);
        $composant->assertStatus(200);
    }

    #[Test]
    public function le_composant_peut_basculer_vers_vue_globale()
    {
        // Tester si le composant peut passer à la vue globale
        $composant = Livewire::test(LeaderboardSwitcher::class);
        $composant->call('updateSelectedClan', 'global');
        $composant->assertSet('selectedClanId', 'global');
    }

    #[Test]
    public function le_composant_peut_basculer_vers_vue_clan()
    {
        // Créer un clan pour le test
        $clan = Clan::factory()->create();

        // Tester si le composant peut passer à la vue d'un clan spécifique
        $composant = Livewire::test(LeaderboardSwitcher::class);
        $composant->call('updateSelectedClan', $clan->id);
        $composant->assertSet('selectedClanId', $clan->id);
    }

    #[Test]
    public function le_composant_charge_donnees_des_meilleurs_clans()
    {
        // Créer des utilisateurs et des clans pour le test
        $utilisateur1 = User::factory()->create();
        $utilisateur2 = User::factory()->create();
        
        $clan1 = Clan::factory()->create();
        $clan2 = Clan::factory()->create();
        
        // Associer les utilisateurs aux clans
        DB::table('clan_users')->insert([
            ['user_id' => $utilisateur1->id, 'clan_id' => $clan1->id],
            ['user_id' => $utilisateur2->id, 'clan_id' => $clan2->id]
        ]);
        
        // Créer des scores pour les utilisateurs
        Score::create(['user_id' => $utilisateur1->id, 'score' => 100, 'date' => Carbon::now()]);
        Score::create(['user_id' => $utilisateur2->id, 'score' => 50, 'date' => Carbon::now()]);
        
        // Tester si les données sont chargées correctement
        $composant = Livewire::test(LeaderboardSwitcher::class);
        $composant->call('updateSelectedClan', 'global');
        
        // Vérifier la structure des données
        $this->assertIsObject($composant->get('topClans'));
        $this->assertGreaterThanOrEqual(1, $composant->get('topClans')->count());
    }

    #[Test]
    public function le_composant_gere_le_changement_de_langue()
    {
        // Tester si le composant gère correctement le changement de langue
        $composant = Livewire::test(LeaderboardSwitcher::class);
        $composant->call('handleLocaleChanged', ['locale' => 'fr']);
        
        // Vérifier que la session a été mise à jour
        $this->assertEquals('fr', app()->getLocale());
    }
}