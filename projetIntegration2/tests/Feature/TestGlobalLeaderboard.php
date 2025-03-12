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

class TestGlobalLeaderboard extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function le_composant_global_leaderboard_peut_etre_rendu()
    {
        // Tester si le composant peut être rendu
        $composant = Livewire::test(GlobalLeaderboard::class);
        $composant->assertStatus(200);
    }

    #[Test]
    public function le_composant_affiche_les_meilleurs_utilisateurs()
    {
        // Créer des utilisateurs et des scores pour le test
        $utilisateurs = User::factory()->count(5)->create();
        
        foreach ($utilisateurs as $utilisateur) {
            Score::create([
                'user_id' => $utilisateur->id,
                'score' => rand(50, 100),
                'date' => Carbon::now()
            ]);
        }
        
        // Récupérer les données pour le test
        $topUtilisateurs = DB::table('users')
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
        
        // Tester si le composant affiche correctement les utilisateurs
        $composant = Livewire::test(GlobalLeaderboard::class, [
            'topUsers' => $topUtilisateurs
        ]);
        
        $composant->assertViewHas('topUsers');
    }

    #[Test]
    public function le_composant_affiche_les_meilleurs_clans()
    {
        // Créer des clans, des utilisateurs et des scores pour le test
        $clan1 = Clan::factory()->create();
        $clan2 = Clan::factory()->create();
        $utilisateurs = User::factory()->count(4)->create();
        
        // Associer les utilisateurs aux clans
        DB::table('clan_users')->insert([
            ['user_id' => $utilisateurs[0]->id, 'clan_id' => $clan1->id],
            ['user_id' => $utilisateurs[1]->id, 'clan_id' => $clan1->id],
            ['user_id' => $utilisateurs[2]->id, 'clan_id' => $clan2->id],
            ['user_id' => $utilisateurs[3]->id, 'clan_id' => $clan2->id]
        ]);
        
        // Ajouter des scores
        foreach ($utilisateurs as $utilisateur) {
            Score::create([
                'user_id' => $utilisateur->id,
                'score' => rand(50, 100),
                'date' => Carbon::now()
            ]);
        }
        
        // Récupérer les données pour le test
        $topClans = DB::table('clan_users as cu')
            ->join(DB::raw('(SELECT user_id, SUM(score) as total_score FROM scores GROUP BY user_id) as su'), 'cu.user_id', '=', 'su.user_id')
            ->join('clans', 'clans.id', '=', 'cu.clan_id')
            ->select('cu.clan_id', 'clans.nom as clan_nom', 'clans.image as clan_image', DB::raw('SUM(su.total_score) as clan_total_score'))
            ->groupBy('cu.clan_id', 'clans.nom', 'clans.image')
            ->orderByDesc('clan_total_score')
            ->limit(10)
            ->get();
        
        // Tester si le composant affiche correctement les clans
        $composant = Livewire::test(GlobalLeaderboard::class, [
            'topClans' => $topClans
        ]);
        
        $composant->assertViewHas('topClans');
    }

    #[Test]
    public function le_composant_peut_basculer_en_mode_graphique()
    {
        // Tester si le composant peut basculer entre les différents modes de graphique
        $composant = Livewire::test(GlobalLeaderboard::class);
        
        // Par défaut, le graphique est masqué
        $composant->assertSet('showingGraph', false);
        
        // Afficher le graphique des clans
        $composant->call('showClansGraph');
        $composant->assertSet('showingGraph', true);
        $composant->assertSet('chartType', 'clans');
        
        // Afficher le graphique des utilisateurs
        $composant->call('showUsersGraph');
        $composant->assertSet('showingGraph', true);
        $composant->assertSet('chartType', 'users');
        
        // Masquer le graphique
        $composant->call('hideGraph');
        $composant->assertSet('showingGraph', false);
    }
    
    #[Test]
    public function le_composant_gere_le_changement_de_langue()
    {
        // Tester si le composant gère correctement le changement de langue
        $composant = Livewire::test(GlobalLeaderboard::class);
        
        // Changer la langue en français
        $composant->call('handleLocaleChanged', ['locale' => 'fr']);
        $this->assertEquals('fr', app()->getLocale());
        
        // Changer la langue en anglais
        $composant->call('handleLocaleChanged', ['locale' => 'en']);
        $this->assertEquals('en', app()->getLocale());
    }
}