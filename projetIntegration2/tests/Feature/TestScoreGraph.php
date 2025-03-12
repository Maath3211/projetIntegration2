<?php

namespace Tests\Feature;

use App\Http\Livewire\ScoreGraph;
use App\Models\Clan;
use App\Models\User;
use App\Models\Score;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

class TestScoreGraph extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function le_composant_score_graph_peut_etre_rendu()
    {
        // Tester si le composant peut être rendu
        $composant = Livewire::test(ScoreGraph::class, [
            'showType' => 'clans',
            'selectedClanId' => 'global'
        ]);
        
        $composant->assertStatus(200);
    }

    #[Test]
    public function le_composant_charge_les_donnees_correctement()
    {
        // Créer des données de test
        $utilisateur = User::factory()->create();
        $clan = Clan::factory()->create();
        
        // Associer l'utilisateur au clan
        DB::table('clan_users')->insert([
            'user_id' => $utilisateur->id,
            'clan_id' => $clan->id
        ]);
        
        // Ajouter des scores pour les 6 derniers mois
        for ($i = 5; $i >= 0; $i--) {
            $mois = Carbon::now()->subMonths($i);
            Score::create([
                'user_id' => $utilisateur->id,
                'score' => rand(10, 100),
                'date' => $mois->format('Y-m-d')
            ]);
        }
        
        // Tester le composant avec différentes configurations
        $types = ['clans', 'users', 'members', 'improvements'];
        
        foreach ($types as $type) {
            $composant = Livewire::test(ScoreGraph::class, [
                'showType' => $type,
                'selectedClanId' => $type === 'members' || $type === 'improvements' ? $clan->id : 'global'
            ]);
            
            // Vérifier que les tableaux de données sont initialisés
            $this->assertIsArray($composant->get('months'));
            $this->assertIsArray($composant->get('clanScores'));
            $this->assertIsArray($composant->get('userScores'));
            
            // Vérifier qu'il y a 6 éléments (mois) dans les tableaux
            $this->assertEquals(6, count($composant->get('months')));
        }
    }

    #[Test]
    public function le_composant_peut_fermer_le_graphique()
    {
        // Tester si le composant peut fermer le graphique
        $composant = Livewire::test(ScoreGraph::class, [
            'showType' => 'clans',
            'selectedClanId' => 'global'
        ]);
        
        // Émettre l'événement de fermeture
        $composant->emit('closeGraph');
    }
}