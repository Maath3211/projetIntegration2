<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Clan;
use App\Models\User;
use App\Models\CategorieCanal;
use App\Models\Canal;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class ClanTest extends TestCase
{
    use DatabaseTransactions;

    /*
        CLAN
    */

    #[Test]
    public function peut_creer_un_clan(){
        # On crée un clan
        $clan = Clan::factory()->create();

        # On vérifie que les attributs sont bien remplis
        $this->assertNotNull($clan->id);
        $this->assertNotEmpty($clan->nom);
        $this->assertNotNull($clan->public);
        $this->assertNotNull($clan->image);
        $this->assertNotNull($clan->adminId);
    }

    #[Test]
    public function peut_modifier_details_un_clan(){
        # On crée un clan
        $clan = Clan::factory()->create();
        
        # On modifie les détails du clan
        $clan->update([
            'nom' => 'Nouveau Nom',
            'public' => false,
        ]);

        # On vérifie que les modifications ont bien été prises en compte
        $clan = $clan->fresh();

        # On vérifie que les attributs sont bien remplis
        $this->assertEquals('Nouveau Nom', $clan->nom);
        $this->assertSame(0, $clan->public);
    }

    #[Test]
    public function peut_supprimer_un_clan(){
        # On crée un clan
        $clan = Clan::factory()->create();

        # On supprime le clan
        $clanId = $clan->id;
        $clan->delete();

        # On vérifie que le clan a bien été supprimé
        $this->assertNull(Clan::find($clanId));
        $this->assertDatabaseMissing('clans', ['id' => $clanId]);
    }

    #[Test]
    public function les_clans_ont_une_relation_n_a_n_avec_les_utilisateurs(){
        # On crée un clan
        $clan = Clan::factory()->create();
        # On crée trois utilisateurs
        $users = User::factory()->count(3)->create();

        # On attache les utilisateurs au clan
        $clan->utilisateurs()->attach($users->pluck('id'));

        # On vérifie que la relation est bien établie
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $clan->utilisateurs);
        $this->assertCount(3, $clan->utilisateurs);
        $this->assertTrue($clan->utilisateurs->contains($users->first()));
    }

    #[Test]
    public function le_nom_du_clan_ne_doit_pas_depasser_cinquante_caracteres(){
        # On vérifie que le nom du clan ne dépasse pas 50 caractères
        $this->expectException(\Illuminate\Database\QueryException::class);

        $clan = Clan::factory()->create([
            'nom' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        ]);
    }

    #[Test]
    public function le_clan_doit_avoir_un_admin(){
        # On vérifie que le clan doit avoir un admin
        $this->expectException(\Illuminate\Database\QueryException::class);

        $clan = Clan::factory()->create([
            'adminId' => null,
        ]);
    }

    #[Test]
    public function les_clans_ont_les_bons_attributs_completables(){
        # On vérifie que les clans ont les bons attributs completables
        $clan = new Clan();

        $this->assertEquals([
            'adminId',
            'image',
            'nom',
            'public',
            'id'
        ], $clan->getFillable());
    }

    /*
        J'AI PASSÉ 5 HEURES À ESSAYER DE FAIRE MARCHE LES TESTS UNITAIRES DANS UN AUTRE FICHIER MAIS ÇA MARCHAIT JAMAIS ALORS JE LES FAITS TOUS DANS CE FICHIER - Thomas
    */

    /*
        CATEGORIE CANAL
    */

    #[Test]
    public function peut_creer_une_categorie_de_clan(){
        # On crée un clan
        $clan = Clan::factory()->create();

        # On crée une catégorie de canal
        $categorie = CategorieCanal::factory()->create([
            'clanId' => $clan->id
        ]);

        # On vérifie que les attributs sont bien remplis
        $this->assertNotNull($categorie->id);
    }

    #[Test]
    public function peut_modifier_une_categorie_de_clan(){
        # On crée un clan
        $clan = Clan::factory()->create();

        # On crée une catégorie de canal
        $categorie = CategorieCanal::factory()->create([
            'clanId' => $clan->id
        ]);

        # On modifie la catégorie de canal
        $categorie->update([
            'categorie' => 'Nouvelle Catégorie'
        ]);

        # On vérifie que les modifications ont bien été prises en compte
        $categorie = $categorie->fresh();

        # On vérifie que les attributs sont bien remplis
        $this->assertEquals('Nouvelle Catégorie', $categorie->categorie);
    }

    #[Test]
    public function peut_supprimer_une_categorie_de_clan(){
        # On crée un clan
        $clan = Clan::factory()->create();

        # On crée une catégorie de canal
        $categorie = CategorieCanal::factory()->create([
            'clanId' => $clan->id
        ]);

        # On supprime la catégorie de canal
        $categorieId = $categorie->id;
        $categorie->delete();

        # On vérifie que la catégorie de canal a bien été supprimée
        $this->assertNull(CategorieCanal::find($categorieId));
        $this->assertDatabaseMissing('categories_canal', ['id' => $categorieId]);
    }

    #[Test]
    public function la_categorie_a_une_relation_avec_un_clan(){
        # On crée un clan
        $clan = Clan::factory()->create();

        # On crée une catégorie de canal
        $categorie = CategorieCanal::factory()->create([
            'clanId' => $clan->id
        ]);

        # On vérifie que la relation est bien établie
        $this->assertInstanceOf('App\Models\Clan', $categorie->clan);
        $this->assertEquals($clan->id, $categorie->clan->id);
    }

    #[Test]
    public function la_categorie_du_canal_ne_doit_pas_depasser_cinquante_caracteres(){
        # On vérifie que la catégorie du canal ne dépasse pas 50 caractères
        $this->expectException(\Illuminate\Database\QueryException::class);

        $clan = Clan::factory()->create();
        $categorie = CategorieCanal::factory()->create([
            'clanId' => $clan->id,
            'categorie' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        ]);
    }

    #[Test]
    public function les_categories_de_canaux_ont_les_bons_attributs_completables(){
        # On vérifie que les catégories de canaux ont les bons attributs completables
        $categorie = new CategorieCanal();

        $this->assertEquals([
            'categorie',
            'clanId',
        ], $categorie->getFillable());
    }

    /*
        CANAL
    */

    #[Test]
    public function peut_ajouter_un_canal(){
        # On crée un clan
        $clan = Clan::factory()->create();

        # On crée une catégorie de canal
        $categorie = CategorieCanal::factory()->create([
            'clanId' => $clan->id
        ]);

        # On crée un canal
        $canal = Canal::factory()->create([
            'clanId' => $clan->id,
            'categorieId' => $categorie->id
        ]);

        # On vérifie que les attributs sont bien remplis
        $this->assertNotNull($canal->id);
    }

    #[Test]
    public function peut_modifier_un_canal(){
        # On crée un clan
        $clan = Clan::factory()->create();

        # On crée une catégorie de canal
        $categorie = CategorieCanal::factory()->create([
            'clanId' => $clan->id
        ]);

        # On crée un canal
        $canal = Canal::factory()->create([
            'clanId' => $clan->id,
            'categorieId' => $categorie->id
        ]);

        # On modifie le canal
        $canal->update([
            'titre' => 'nouveau-titre'
        ]);

        # On vérifie que les modifications ont bien été prises en compte
        $canal = $canal->fresh();

        # On vérifie que les attributs sont bien remplis
        $this->assertEquals('nouveau-titre', $canal->titre);
    }

    #[Test]
    public function peut_supprimer_un_canal(){
        # On crée un clan
        $clan = Clan::factory()->create();

        # On crée une catégorie de canal
        $categorie = CategorieCanal::factory()->create([
            'clanId' => $clan->id
        ]);

        # On crée un canal
        $canal = Canal::factory()->create([
            'clanId' => $clan->id,
            'categorieId' => $categorie->id
        ]);

        # On supprime le canal
        $canalId = $canal->id;
        $canal->delete();

        # On vérifie que le canal a bien été supprimé
        $this->assertNull(Canal::find($canalId));
        $this->assertDatabaseMissing('canals', ['id' => $canalId]);
    }

    #[Test]
    public function le_canal_a_une_relation_avec_un_clan(){
        # On crée un clan
        $clan = Clan::factory()->create();

        # On crée une catégorie de canal
        $categorie = CategorieCanal::factory()->create([
            'clanId' => $clan->id
        ]);

        # On crée un canal
        $canal = Canal::factory()->create([
            'clanId' => $clan->id,
            'categorieId' => $categorie->id
        ]);

        # On vérifie que la relation est bien établie
        $this->assertInstanceOf('App\Models\Clan', $canal->clan);
        $this->assertEquals($clan->id, $canal->clan->id);
    }

    #[Test]
    public function le_canal_a_une_relation_avec_une_categorie_de_canal(){
        # On crée un clan
        $clan = Clan::factory()->create();

        # On crée une catégorie de canal
        $categorie = CategorieCanal::factory()->create([
            'clanId' => $clan->id
        ]);

        # On crée un canal
        $canal = Canal::factory()->create([
            'clanId' => $clan->id,
            'categorieId' => $categorie->id
        ]);

        # On vérifie que la relation est bien établie
        $this->assertInstanceOf('App\Models\CategorieCanal', $canal->categorie);
        $this->assertEquals($categorie->id, $canal->categorie->id);
    }

    #[Test]
    public function le_titre_du_canal_ne_doit_pas_depasser_cinquante_caracteres(){
        # On vérifie que le titre du canal ne dépasse pas 50 caractères
        $this->expectException(\Illuminate\Database\QueryException::class);

        $clan = Clan::factory()->create();
        $categorie = CategorieCanal::factory()->create([
            'clanId' => $clan->id
        ]);
        $canal = Canal::factory()->create([
            'clanId' => $clan->id,
            'categorieId' => $categorie->id,
            'titre' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
        ]);
    }

    #[Test]
    public function les_canaux_ont_les_bons_attributs_completables(){
        # On vérifie que les canaux ont les bons attributs completables
        $canal = new Canal();

        $this->assertEquals([
            'titre',
            'clanId',
            'categorieId',
        ], $canal->getFillable());
    }
}