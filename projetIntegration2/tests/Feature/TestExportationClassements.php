<?php

namespace Tests\Feature;

use App\Http\Controllers\ScoresController;
use App\Models\Clan;
use App\Models\User;
use App\Models\Score;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

class TestExportationClassements extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function peut_exporter_les_meilleurs_utilisateurs()
    {
        // Créer des utilisateurs et des scores
        $utilisateurs = User::factory()->count(5)->create();
        
        foreach ($utilisateurs as $utilisateur) {
            Score::create([
                'user_id' => $utilisateur->id,
                'score' => rand(10, 100),
                'date' => Carbon::now()
            ]);
        }

        // Tester l'exportation des meilleurs utilisateurs
        $response = $this->get('/export/top-users');
        
        // Vérifier que la réponse est de type CSV
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="meilleurs_membres_global_' . date('d-m-Y') . '.csv"');
    }

    #[Test]
    public function peut_exporter_les_meilleurs_clans()
    {
        // Créer des clans, des utilisateurs et des scores
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
                'score' => rand(10, 100),
                'date' => Carbon::now()
            ]);
        }

        // Tester l'exportation des meilleurs clans
        $response = $this->get('/export/top-clans');
        
        // Vérifier que la réponse est de type CSV
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="meilleurs_clans_global_' . date('d-m-Y') . '.csv"');
    }

    #[Test]
    public function peut_exporter_les_meilleurs_membres_dun_clan()
    {
        // Créer un clan et des utilisateurs
        $clan = Clan::factory()->create();
        $utilisateurs = User::factory()->count(5)->create();
        
        // Associer les utilisateurs au clan
        foreach ($utilisateurs as $utilisateur) {
            DB::table('clan_users')->insert([
                'user_id' => $utilisateur->id,
                'clan_id' => $clan->id
            ]);
            
            // Ajouter des scores
            Score::create([
                'user_id' => $utilisateur->id,
                'score' => rand(10, 100),
                'date' => Carbon::now()
            ]);
        }

        // Tester l'exportation des meilleurs membres d'un clan
        $response = $this->get('/export/top-membres/' . $clan->id);
        
        // Vérifier que la réponse est de type CSV
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    #[Test]
    public function peut_exporter_les_meilleures_ameliorations_dun_clan()
    {
        // Créer un clan et des utilisateurs
        $clan = Clan::factory()->create();
        $utilisateurs = User::factory()->count(5)->create();
        
        // Associer les utilisateurs au clan
        foreach ($utilisateurs as $utilisateur) {
            DB::table('clan_users')->insert([
                'user_id' => $utilisateur->id,
                'clan_id' => $clan->id
            ]);
            
            // Ajouter des scores récents (dernier mois)
            Score::create([
                'user_id' => $utilisateur->id,
                'score' => rand(10, 100),
                'date' => Carbon::now()->subDays(rand(1, 25))
            ]);
        }

        // Tester l'exportation des meilleures améliorations d'un clan
        $response = $this->get('/export/top-amelioration/' . $clan->id);
        
        // Vérifier que la réponse est de type CSV
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }
}