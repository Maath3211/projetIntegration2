<?php

namespace Tests\Feature;

use App\Models\Clan;
use App\Models\User;
use App\Models\Score;
use App\Models\GraphSauvegarde;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;

class TestGraphiquePerso extends TestCase
{
    use DatabaseTransactions;

    /**
     * Configuration initiale pour les tests
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer un utilisateur pour les tests
        $this->utilisateur = User::factory()->create();
        
        // Créer un clan pour les tests
        $this->clan = Clan::factory()->create();
        
        // Associer l'utilisateur au clan
        DB::table('clan_users')->insert([
            'user_id' => $this->utilisateur->id,
            'clan_id' => $this->clan->id
        ]);
        
        // Authentifier l'utilisateur
        $this->actingAs($this->utilisateur);
        
        // Créer des scores
        $this->créerScoresDePériode();
    }
    
    /**
     * Crée des scores de test pour plusieurs mois
     */
    protected function créerScoresDePériode()
    {
        // Créer des scores pour les derniers mois
        for ($i = 0; $i < 10; $i++) {
            Score::create([
                'user_id' => $this->utilisateur->id,
                'score' => rand(50, 200),
                'date' => Carbon::now()->subDays(rand(1, 90))
            ]);
        }
    }

    #[Test]
    public function la_page_index_affiche_les_graphiques_de_l_utilisateur()
    {
        // Créer quelques graphiques sauvegardés pour l'utilisateur
        $graphique1 = GraphSauvegarde::create([
            'user_id' => $this->utilisateur->id,
            'type' => 'global',
            'titre' => 'Mon graphique global',
            'date_debut' => Carbon::now()->subMonths(3),
            'date_fin' => Carbon::now(),
            'data' => ['labels' => ['Jan', 'Fév', 'Mar'], 'values' => [100, 150, 200], 'interval' => 'monthly'],
            'date_expiration' => Carbon::now()->addDays(90)
        ]);
        
        $graphique2 = GraphSauvegarde::create([
            'user_id' => $this->utilisateur->id,
            'type' => 'clan',
            'clan_id' => $this->clan->id,
            'titre' => 'Mon graphique de clan',
            'date_debut' => Carbon::now()->subMonths(2),
            'date_fin' => Carbon::now(),
            'data' => ['labels' => ['Fév', 'Mar'], 'values' => [120, 180], 'interval' => 'monthly'],
            'date_expiration' => Carbon::now()->addDays(90)
        ]);
        
        // Visiter la page d'index des graphiques
        $réponse = $this->get(route('graphs.index'));
        
        // Vérifier que la page charge correctement
        $réponse->assertStatus(200);
        
        // Vérifier que les graphiques sont présents dans la vue
        $réponse->assertSee('Mon graphique global');
        $réponse->assertSee('Mon graphique de clan');
        
        // Vérifier que le nom du clan apparaît
        $réponse->assertSee($this->clan->nom);
    }
    
    #[Test]
    public function la_page_de_création_affiche_le_formulaire()
    {
        // Visiter la page de création de graphique
        $réponse = $this->get(route('graphs.create'));
        
        // Vérifier que la page charge correctement
        $réponse->assertStatus(200);
        
        // Vérifier la présence des éléments du formulaire
        $réponse->assertSee('Titre du graphique');
        $réponse->assertSee('Type de graphique');
        $réponse->assertSee('Date de début');
        $réponse->assertSee('Date de fin');
        
        // Vérifier que le clan de l'utilisateur est présent
        $réponse->assertSee($this->clan->nom);
    }
    
    #[Test]
    public function peut_créer_un_graphique_global()
    {
        // Données pour la création du graphique
        $données = [
            'titre' => 'Test Graphique Global',
            'type' => 'global',
            'date_debut' => Carbon::now()->subMonths(3)->format('Y-m-d'),
            'date_fin' => Carbon::now()->format('Y-m-d'),
        ];
        
        // Soumettre le formulaire de création
        $réponse = $this->post(route('graphs.store'), $données);
        
        // Vérifier la redirection
        $réponse->assertRedirect();
        $réponse->assertSessionHas('success');
        
        // Vérifier que le graphique a été créé en base de données
        $this->assertDatabaseHas('graph_sauvegardes', [
            'user_id' => $this->utilisateur->id,
            'titre' => 'Test Graphique Global',
            'type' => 'global',
        ]);
    }
    
    #[Test]
    public function peut_créer_un_graphique_de_clan()
    {
        // Données pour la création du graphique
        $données = [
            'titre' => 'Test Graphique Clan',
            'type' => 'clan',
            'clan_id' => $this->clan->id,
            'date_debut' => Carbon::now()->subMonths(3)->format('Y-m-d'),
            'date_fin' => Carbon::now()->format('Y-m-d'),
        ];
        
        // Soumettre le formulaire de création
        $réponse = $this->post(route('graphs.store'), $données);
        
        // Vérifier la redirection
        $réponse->assertRedirect();
        $réponse->assertSessionHas('success');
        
        // Vérifier que le graphique a été créé en base de données
        $this->assertDatabaseHas('graph_sauvegardes', [
            'user_id' => $this->utilisateur->id,
            'titre' => 'Test Graphique Clan',
            'type' => 'clan',
            'clan_id' => $this->clan->id,
        ]);
    }
    
    #[Test]
    public function ne_peut_pas_créer_un_graphique_pour_un_clan_non_membre()
    {
        // Créer un nouveau clan dont l'utilisateur n'est pas membre
        $autreClan = Clan::factory()->create();
        
        // Données pour la création du graphique
        $données = [
            'titre' => 'Test Graphique Clan Interdit',
            'type' => 'clan',
            'clan_id' => $autreClan->id,
            'date_debut' => Carbon::now()->subMonths(3)->format('Y-m-d'),
            'date_fin' => Carbon::now()->format('Y-m-d'),
        ];
        
        // Soumettre le formulaire de création
        $réponse = $this->post(route('graphs.store'), $données);
        
        // Vérifier la redirection et le message d'erreur
        $réponse->assertRedirect();
        $réponse->assertSessionHas('error');
        
        // Vérifier que le graphique n'a pas été créé
        $this->assertDatabaseMissing('graph_sauvegardes', [
            'user_id' => $this->utilisateur->id,
            'titre' => 'Test Graphique Clan Interdit',
        ]);
    }
    
    #[Test]
    public function peut_afficher_un_graphique_sauvegardé()
    {
        // Créer un graphique pour l'utilisateur
        $graphique = GraphSauvegarde::create([
            'user_id' => $this->utilisateur->id,
            'type' => 'global',
            'titre' => 'Graphique à afficher',
            'date_debut' => Carbon::now()->subMonths(3),
            'date_fin' => Carbon::now(),
            'data' => ['labels' => ['Jan', 'Fév', 'Mar'], 'values' => [100, 150, 200], 'interval' => 'monthly'],
            'date_expiration' => Carbon::now()->addDays(90)
        ]);
        
        // Visiter la page du graphique
        $réponse = $this->get(route('graphs.show', $graphique->id));
        
        // Vérifier que la page charge correctement
        $réponse->assertStatus(200);
        
        // Vérifier que le titre du graphique est présent
        $réponse->assertSee('Graphique à afficher');
        
        // Vérifier que les dates sont affichées
        $réponse->assertSee($graphique->date_debut->format('d/m/Y'));
        $réponse->assertSee($graphique->date_fin->format('d/m/Y'));
    }
    
    #[Test]
    public function ne_peut_pas_voir_le_graphique_d_un_autre_utilisateur()
    {
        // Créer un autre utilisateur
        $autreUtilisateur = User::factory()->create();
        
        // Créer un graphique pour l'autre utilisateur
        $graphique = GraphSauvegarde::create([
            'user_id' => $autreUtilisateur->id,
            'type' => 'global',
            'titre' => 'Graphique privé',
            'date_debut' => Carbon::now()->subMonths(3),
            'date_fin' => Carbon::now(),
            'data' => ['labels' => ['Jan', 'Fév', 'Mar'], 'values' => [100, 150, 200], 'interval' => 'monthly'],
            'date_expiration' => Carbon::now()->addDays(90)
        ]);
        
        // Essayer de visiter la page du graphique de l'autre utilisateur
        $réponse = $this->get(route('graphs.show', $graphique->id));
        
        // Vérifier que l'accès est refusé ou redirigé
        $réponse->assertRedirect(route('graphs.index'));
        $réponse->assertSessionHas('error');
    }
    
    #[Test]
    public function peut_modifier_un_graphique()
    {
        // Créer un graphique pour l'utilisateur
        $graphique = GraphSauvegarde::create([
            'user_id' => $this->utilisateur->id,
            'type' => 'global',
            'titre' => 'Graphique à modifier',
            'date_debut' => Carbon::now()->subMonths(3),
            'date_fin' => Carbon::now(),
            'data' => ['labels' => ['Jan', 'Fév', 'Mar'], 'values' => [100, 150, 200], 'interval' => 'monthly'],
            'date_expiration' => Carbon::now()->addDays(90)
        ]);
        
        // Visiter la page d'édition
        $réponse = $this->get(route('graphs.edit', $graphique->id));
        
        // Vérifier que la page charge correctement
        $réponse->assertStatus(200);
        
        // Vérifier que le titre du graphique est présent
        $réponse->assertSee('Graphique à modifier');
        
        // Données pour la modification
        $nouvelleDonnées = [
            'titre' => 'Graphique modifié',
            'type' => 'global', // Type reste le même
            'date_debut' => Carbon::now()->subMonths(6)->format('Y-m-d'),
            'date_fin' => Carbon::now()->format('Y-m-d'),
        ];
        
        // Soumettre le formulaire de modification
        $réponse = $this->put(route('graphs.update', $graphique->id), $nouvelleDonnées);
        
        // Vérifier la redirection
        $réponse->assertRedirect(route('graphs.show', $graphique->id));
        $réponse->assertSessionHas('success');
        
        // Vérifier que les modifications ont été enregistrées
        $this->assertDatabaseHas('graph_sauvegardes', [
            'id' => $graphique->id,
            'titre' => 'Graphique modifié',
            'date_debut' => Carbon::parse($nouvelleDonnées['date_debut'])->format('Y-m-d'),
        ]);
    }
    
    #[Test]
    public function peut_supprimer_un_graphique()
    {
        // Créer un graphique pour l'utilisateur
        $graphique = GraphSauvegarde::create([
            'user_id' => $this->utilisateur->id,
            'type' => 'global',
            'titre' => 'Graphique à supprimer',
            'date_debut' => Carbon::now()->subMonths(3),
            'date_fin' => Carbon::now(),
            'data' => ['labels' => ['Jan', 'Fév', 'Mar'], 'values' => [100, 150, 200], 'interval' => 'monthly'],
            'date_expiration' => Carbon::now()->addDays(90)
        ]);
        
        // Vérifier que le graphique existe
        $this->assertDatabaseHas('graph_sauvegardes', [
            'id' => $graphique->id,
            'titre' => 'Graphique à supprimer'
        ]);
        
        // Soumettre la demande de suppression
        $réponse = $this->delete(route('graphs.delete', $graphique->id));
        
        // Vérifier la redirection
        $réponse->assertRedirect(route('graphs.index'));
        $réponse->assertSessionHas('success');
        
        // Vérifier que le graphique a été supprimé
        $this->assertDatabaseMissing('graph_sauvegardes', [
            'id' => $graphique->id
        ]);
    }
    
    #[Test]
    public function ne_peut_pas_supprimer_le_graphique_d_un_autre_utilisateur()
    {
        // Créer un autre utilisateur
        $autreUtilisateur = User::factory()->create();
        
        // Créer un graphique pour l'autre utilisateur
        $graphique = GraphSauvegarde::create([
            'user_id' => $autreUtilisateur->id,
            'type' => 'global',
            'titre' => 'Graphique d\'un autre utilisateur',
            'date_debut' => Carbon::now()->subMonths(3),
            'date_fin' => Carbon::now(),
            'data' => ['labels' => ['Jan', 'Fév', 'Mar'], 'values' => [100, 150, 200], 'interval' => 'monthly'],
            'date_expiration' => Carbon::now()->addDays(90)
        ]);
        
        // Essayer de supprimer le graphique
        $réponse = $this->delete(route('graphs.delete', $graphique->id));
        
        // Vérifier la redirection et le message d'erreur
        $réponse->assertRedirect(route('graphs.index'));
        $réponse->assertSessionHas('error');
        
        // Vérifier que le graphique existe toujours
        $this->assertDatabaseHas('graph_sauvegardes', [
            'id' => $graphique->id
        ]);
    }
    
    #[Test]
    public function vérifie_génération_correcte_des_données_graphiques()
    {
        // Créer plusieurs scores pour tester la génération de données
        for ($i = 0; $i < 20; $i++) {
            Score::create([
                'user_id' => $this->utilisateur->id,
                'score' => rand(50, 200),
                'date' => Carbon::now()->subDays(rand(1, 60))
            ]);
        }
        
        // Données pour la création du graphique
        $données = [
            'titre' => 'Test Génération Données',
            'type' => 'global',
            'date_debut' => Carbon::now()->subMonths(2)->format('Y-m-d'),
            'date_fin' => Carbon::now()->format('Y-m-d'),
        ];
        
        // Soumettre le formulaire de création
        $réponse = $this->post(route('graphs.store'), $données);
        
        // Vérifier la redirection
        $réponse->assertRedirect();
        
        // Récupérer le graphique créé
        $graphique = GraphSauvegarde::where('titre', 'Test Génération Données')->first();
        
        // Vérifier que le graphique existe
        $this->assertNotNull($graphique);
        
        // Vérifier que les données ont été générées correctement
        $this->assertIsArray($graphique->data);
        $this->assertArrayHasKey('labels', $graphique->data);
        $this->assertArrayHasKey('values', $graphique->data);
        $this->assertArrayHasKey('interval', $graphique->data);
        
        // Vérifier que les labels et valeurs correspondent au bon intervalle (mensuel)
        $this->assertEquals('monthly', $graphique->data['interval']);
        $this->assertCount(3, $graphique->data['labels']); // Mois actuel + 2 mois précédents
        $this->assertCount(3, $graphique->data['values']);
    }
    
    #[Test]
    public function les_graphiques_expirés_ne_sont_pas_affichés()
    {
        // Créer un graphique expiré
        $graphiqueExpiré = GraphSauvegarde::create([
            'user_id' => $this->utilisateur->id,
            'type' => 'global',
            'titre' => 'Graphique expiré',
            'date_debut' => Carbon::now()->subMonths(6),
            'date_fin' => Carbon::now()->subMonths(3),
            'data' => ['labels' => ['Jan', 'Fév', 'Mar'], 'values' => [100, 150, 200], 'interval' => 'monthly'],
            'date_expiration' => Carbon::now()->subDays(1) // Expiré hier
        ]);
        
        // Créer un graphique valide
        $graphiqueValide = GraphSauvegarde::create([
            'user_id' => $this->utilisateur->id,
            'type' => 'global',
            'titre' => 'Graphique valide',
            'date_debut' => Carbon::now()->subMonths(3),
            'date_fin' => Carbon::now(),
            'data' => ['labels' => ['Jan', 'Fév', 'Mar'], 'values' => [100, 150, 200], 'interval' => 'monthly'],
            'date_expiration' => Carbon::now()->addDays(90)
        ]);
        
        // Visiter la page d'index des graphiques
        $réponse = $this->get(route('graphs.index'));
        
        // Vérifier que le graphique valide est présent
        $réponse->assertSee('Graphique valide');
        
        // Vérifier que le graphique expiré n'est pas présent
        $réponse->assertDontSee('Graphique expiré');
    }
}