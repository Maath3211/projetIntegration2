<?php

namespace Tests\Unit;

use App\Models\GraphSauvegarde;
use App\Models\Clan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class GraphSauvegardeTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $clan;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un utilisateur et un clan pour les tests
        $this->user = User::factory()->create();
        $this->clan = Clan::factory()->create();
    }

    #[Test]
    public function peut_creer_un_graphique_sauvegarde()
    {
        $data = [
            'user_id' => $this->user->id,
            'type' => 'global',
            'titre' => 'Mon graphique de test',
            'date_debut' => Carbon::now()->subMonths(3),
            'date_fin' => Carbon::now(),
            'data' => ['labels' => ['Jan', 'Fév', 'Mar'], 'values' => [100, 150, 200]],
        ];

        $graph = GraphSauvegarde::create($data);

        $this->assertInstanceOf(GraphSauvegarde::class, $graph);
        $this->assertEquals('Mon graphique de test', $graph->titre);
        $this->assertEquals('global', $graph->type);
        $this->assertIsArray($graph->data);
        $this->assertEquals(['Jan', 'Fév', 'Mar'], $graph->data['labels']);
    }

    #[Test]
    public function peut_creer_un_graphique_de_clan()
    {
        $data = [
            'user_id' => $this->user->id,
            'type' => 'clan',
            'clan_id' => $this->clan->id,
            'titre' => 'Graphique du clan',
            'date_debut' => Carbon::now()->subMonths(3),
            'date_fin' => Carbon::now(),
            'data' => ['labels' => ['Jan', 'Fév', 'Mar'], 'values' => [100, 150, 200]],
        ];

        $graph = GraphSauvegarde::create($data);

        $this->assertInstanceOf(GraphSauvegarde::class, $graph);
        $this->assertEquals($this->clan->id, $graph->clan_id);
        $this->assertEquals('clan', $graph->type);
    }

    #[Test]
    public function la_date_expiration_est_ajoutee_automatiquement()
    {
        $graph = GraphSauvegarde::create([
            'user_id' => $this->user->id,
            'type' => 'global',
            'titre' => 'Sans date d\'expiration',
            'date_debut' => Carbon::now()->subMonths(3), // Ajout de date_debut
            'date_fin' => Carbon::now(), // Ajout de date_fin
            'data' => ['labels' => [], 'values' => []]
        ]);

        $this->assertNotNull($graph->date_expiration);

        // La date d'expiration doit être à environ 90 jours dans le futur
        $expected = Carbon::now()->addDays(90)->startOfDay();
        $actual = Carbon::parse($graph->date_expiration)->startOfDay();

        // Considérer une marge d'erreur d'un jour à cause des heures
        $this->assertTrue(
            $expected->equalTo($actual) || $expected->addDay()->equalTo($actual),
            'La date d\'expiration n\'est pas correctement définie à environ 90 jours'
        );
    }

    #[Test]
    public function les_dates_sont_convertis_en_objets_carbon()
    {
        $graph = GraphSauvegarde::create([
            'user_id' => $this->user->id,
            'type' => 'global',
            'titre' => 'Test des dates',
            'date_debut' => '2023-01-01',
            'date_fin' => '2023-03-31',
            'data' => ['labels' => [], 'values' => []]
        ]);

        $this->assertInstanceOf(Carbon::class, $graph->date_debut);
        $this->assertInstanceOf(Carbon::class, $graph->date_fin);
        $this->assertInstanceOf(Carbon::class, $graph->date_expiration);

        $this->assertEquals('2023-01-01', $graph->date_debut->format('Y-m-d'));
        $this->assertEquals('2023-03-31', $graph->date_fin->format('Y-m-d'));
    }

    #[Test]
    public function les_relations_fonctionnent_correctement()
    {
        $graph = GraphSauvegarde::create([
            'user_id' => $this->user->id,
            'clan_id' => $this->clan->id,
            'type' => 'clan',
            'titre' => 'Test des relations',
            'date_debut' => Carbon::now()->subMonths(3), // Ajout de date_debut
            'date_fin' => Carbon::now(), // Ajout de date_fin
            'data' => ['labels' => [], 'values' => []]
        ]);

        $this->assertInstanceOf(User::class, $graph->user);
        $this->assertInstanceOf(Clan::class, $graph->clan);

        $this->assertEquals($this->user->id, $graph->user->id);
        $this->assertEquals($this->clan->id, $graph->clan->id);
    }

    #[Test]
    public function peut_mettre_a_jour_un_graphique()
    {
        $graph = GraphSauvegarde::create([
            'user_id' => $this->user->id,
            'type' => 'global',
            'titre' => 'Titre original',
            'date_debut' => Carbon::now()->subMonths(3), // Ajout de date_debut
            'date_fin' => Carbon::now(), // Ajout de date_fin
            'data' => ['labels' => ['Jan'], 'values' => [100]]
        ]);

        $graph->titre = 'Titre modifié';
        $graph->data = ['labels' => ['Jan', 'Fév'], 'values' => [100, 200]];
        $graph->save();

        $updatedGraph = GraphSauvegarde::find($graph->id);

        $this->assertEquals('Titre modifié', $updatedGraph->titre);
        $this->assertEquals(['Jan', 'Fév'], $updatedGraph->data['labels']);
        $this->assertEquals([100, 200], $updatedGraph->data['values']);
    }

    #[Test]
    public function peut_supprimer_un_graphique()
    {
        $graph = GraphSauvegarde::create([
            'user_id' => $this->user->id,
            'type' => 'global',
            'titre' => 'Graphique à supprimer',
            'date_debut' => Carbon::now()->subMonths(3), // Ajout de date_debut
            'date_fin' => Carbon::now(), // Ajout de date_fin
            'data' => ['labels' => [], 'values' => []]
        ]);

        $graphId = $graph->id;
        $graph->delete();

        $this->assertNull(GraphSauvegarde::find($graphId));
    }
}
