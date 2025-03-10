<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Clan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class ClanTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function peut_creer_un_clan(){
        $clan = Clan::factory()->create();

        $this->assertNotNull($clan->id);
        $this->assertNotEmpty($clan->nom);
        \Log::info($clan->nom);
        $this->assertNotNull($clan->public);
        $this->assertNotNull($clan->image);
        $this->assertNotNull($clan->adminId);
    }

    #[Test]
    public function peut_modifier_details_un_clan(){
        $clan = Clan::factory()->create();
        
        $clan->update([
            'nom' => 'Nouveau Nom',
            'public' => false,
        ]);

        $clan = $clan->fresh();

        $this->assertEquals('Nouveau Nom', $clan->nom);
        $this->assertSame(0, $clan->public);
    }

    #[Test]
    public function peut_supprimer_un_clan(){
        $clan = Clan::factory()->create();

        $clanId = $clan->id;
        $clan->delete();

        $this->assertNull(Clan::find($clanId));
        $this->assertDatabaseMissing('clans', ['id' => $clanId]);
    }

    #[Test]
    public function a_une_relation_n_a_n_avec_les_utilisateurs(){
        $clan = Clan::factory()->create();
        $users = User::factory()->count(3)->create();

        $clan->utilisateurs()->attach($users->pluck('id'));

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $clan->utilisateurs);
        $this->assertCount(3, $clan->utilisateurs);
        $this->assertTrue($clan->utilisateurs->contains($users->first()));
    }

    #[Test]
    public function a_les_bons_attributs_completables(){
        $clan = new Clan();

        $this->assertEquals([
            'adminId',
            'image',
            'nom',
            'public',
            'id'
        ], $clan->getFillable());
    }
}