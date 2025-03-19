<?php

namespace Tests\Unit;

use App\Models\GraphSauvegarde;
use App\Models\Clan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TestGraphSauvegarde extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function le_modèle_graph_sauvegarde_a_les_bonnes_relations()
    {
        // Créer les données nécessaires
        $utilisateur = User::factory()->create();
        $clan = Clan::factory()->create();

        // Créer un graphique sauvegardé
        $graphique = GraphSauvegarde::create([
            'user_id' => $utilisateur->id,
            'type' => 'clan',
            'clan_id' => $clan->id,
            'titre' => 'Graphique de test',
            'date_debut' => Carbon::now()->subMonths(3),
            'date_fin' => Carbon::now(),
            'data' => ['labels' => [], 'values' => [], 'interval' => 'monthly'],
            'date_expiration' => Carbon::now()->addDays(90)
        ]);

        // Tester la relation avec l'utilisateur
        $this->assertEquals($utilisateur->id, $graphique->user->id);
        $this->assertInstanceOf(User::class, $graphique->user);

        // Tester la relation avec le clan
        $this->assertEquals($clan->id, $graphique->clan->id);
        $this->assertInstanceOf(Clan::class, $graphique->clan);
    }

    #[Test]
    public function le_modèle_gère_correctement_les_conversions_de_types()
    {
        $utilisateur = User::factory()->create();

        // Données à stocker
        $données = [
            'labels' => ['Jan', 'Fév', 'Mar'],
            'values' => [100, 150, 200],
            'interval' => 'monthly'
        ];

        // Créer un graphique
        $graphique = GraphSauvegarde::create([
            'user_id' => $utilisateur->id,
            'type' => 'global',
            'titre' => 'Test des castings',
            'date_debut' => '2025-01-01',
            'date_fin' => '2025-03-31',
            'data' => $données,
            'date_expiration' => '2025-06-30'
        ]);

        // Récupérer le graphique pour vérifier les conversions
        $graphiqueRécupéré = GraphSauvegarde::find($graphique->id);

        // Vérifier les conversions de dates
        $this->assertInstanceOf(Carbon::class, $graphiqueRécupéré->date_debut);
        $this->assertInstanceOf(Carbon::class, $graphiqueRécupéré->date_fin);
        $this->assertInstanceOf(Carbon::class, $graphiqueRécupéré->date_expiration);

        // Vérifier la conversion JSON -> array pour les données
        $this->assertIsArray($graphiqueRécupéré->data);
        $this->assertEquals($données['labels'], $graphiqueRécupéré->data['labels']);
        $this->assertEquals($données['values'], $graphiqueRécupéré->data['values']);
        $this->assertEquals($données['interval'], $graphiqueRécupéré->data['interval']);
    }

    #[Test]
    public function le_modèle_définit_automatiquement_la_date_d_expiration()
    {
        // Créer un utilisateur pour le test
        $utilisateur = User::factory()->create();

        // Créer un graphique sans spécifier date_expiration
        $graphique = new GraphSauvegarde([
            'user_id' => $utilisateur->id,
            'type' => 'global',
            'titre' => 'Test expiration',
            'date_debut' => Carbon::now()->subMonths(3),
            'date_fin' => Carbon::now(),
            'data' => ['labels' => [], 'values' => []],
        ]);

        // Enregistrer le graphique
        $graphique->save();

        // Vérifier que la date d'expiration a été définie à environ 90 jours
        $this->assertInstanceOf(Carbon::class, $graphique->date_expiration);

        // Vérifier que la date d'expiration est bien dans le futur
        $this->assertTrue(
            $graphique->date_expiration->isFuture(),
            'La date d\'expiration devrait être dans le futur'
        );

        // Méthode 1: Calculer la différence en jours depuis maintenant jusqu'à l'expiration
        $now = Carbon::now();
        $diffJours = $now->diffInDays($graphique->date_expiration);

        // La différence devrait être proche de 90 jours (entre 89 et 90)
        $this->assertGreaterThanOrEqual(
            89,
            $diffJours,
            'La date d\'expiration devrait être d\'au moins 89 jours dans le futur'
        );
        $this->assertLessThanOrEqual(
            90,
            $diffJours,
            'La date d\'expiration ne devrait pas dépasser 90 jours dans le futur'
        );
    }

    #[Test]
    public function les_fields_fillables_sont_corrects()
    {
        $modèle = new GraphSauvegarde();

        $attendu = [
            'user_id',
            'type',
            'clan_id',
            'titre',
            'date_debut',
            'date_fin',
            'data',
            'date_expiration',
        ];

        $this->assertEquals($attendu, $modèle->getFillable());
    }

    #[Test]
    public function les_casts_sont_correctement_définis()
    {
        $modèle = new GraphSauvegarde();

        // Récupérer les casts actuels
        $actualCasts = $modèle->getCasts();

        // Vérifier seulement les casts que nous attendons, ignorant les autres
        $this->assertArrayHasKey('data', $actualCasts);
        $this->assertEquals('array', $actualCasts['data']);

        $this->assertArrayHasKey('date_debut', $actualCasts);
        $this->assertEquals('date', $actualCasts['date_debut']);

        $this->assertArrayHasKey('date_fin', $actualCasts);
        $this->assertEquals('date', $actualCasts['date_fin']);

        $this->assertArrayHasKey('date_expiration', $actualCasts);
        $this->assertEquals('date', $actualCasts['date_expiration']);
    }
}
