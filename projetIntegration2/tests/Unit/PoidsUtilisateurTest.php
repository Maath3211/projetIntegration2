<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\PoidsUtilisateur;


    class PoidsUtilisateurTest extends TestCase
    {
        use DatabaseTransactions;

        public function test_poids_utilisateur_creation()
        {
            $user = User::factory()->create();
            $poidsUtilisateur = PoidsUtilisateur::factory()->create([
                'semaine' => 1,
                'poids' => 70,
                'user_id' => $user->id,
            ]);

            $this->assertDatabaseHas('poids_utilisateur', [
                'semaine' => 1,
                'poids' => 70,
                'user_id' => $user->id,
            ]);
        }

        public function test_poids_utilisateur_belongs_to_user()
        {
            $user = User::factory()->create();
            $poidsUtilisateur = PoidsUtilisateur::factory()->create([
                'user_id' => $user->id,
            ]);

            $this->assertInstanceOf(User::class, $poidsUtilisateur->user);
            $this->assertEquals($user->id, $poidsUtilisateur->user->id);
        }

        public function test_poids_utilisateur_modifier()
        {
            $poidsUtilisateur = PoidsUtilisateur::factory()->create([
                'semaine' => 1,
                'poids' => 70,
            ]);

            $poidsUtilisateur->update(['poids' => 75]);

            $this->assertDatabaseHas('poids_utilisateur', [
                'semaine' => 1,
                'poids' => 75,
            ]);
        }

        public function test_poids_utilisateur_supprimer()
        {
            $poidsUtilisateur = PoidsUtilisateur::factory()->create();

            $poidsUtilisateur->delete();

            $this->assertDatabaseMissing('poids_utilisateur', [
                'id' => $poidsUtilisateur->id,
            ]);
        }

        public function test_poids_utilisateur_fillable()
        {
            $poidsUtilisateur = new PoidsUtilisateur();

            $fillable = ['semaine' => 1, 'poids' => 70, 'user_id' => 1];
            $poidsUtilisateur->fill($fillable);

            $this->assertEquals(1, $poidsUtilisateur->semaine);
            $this->assertEquals(70, $poidsUtilisateur->poids);
            $this->assertEquals(1, $poidsUtilisateur->user_id);
        }
    }