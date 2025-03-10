<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use App\Models\Objectif;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ObjectifTest extends TestCase
{
    use DatabaseTransactions;

        #[Test]
        public function  test_objectif_creation()
        {
            $user = User::factory()->create();

            $objectif = Objectif::create([
                'titre' => 'Nouvel objectif',
                'description' => 'Objectif description',
                'completer' => false,
                'user_id' => $user->id,
            ]);

            $this->assertDatabaseHas('objectifs', [
                'titre' => 'Nouvel objectif',
                'description' => 'Objectif description',
                'completer' => false,
                'user_id' => $user->id,
            ]);
        }

        #[Test]
        public function test_objectif_modifier()
        {
            $user = User::factory()->create();
            $objectif = Objectif::factory()->create(['user_id' => $user->id]);

            $objectif->update([
                'titre' => 'Modifier Objectif',
                'description' => 'Modifier description',
                'completer' => true,
            ]);

            $this->assertDatabaseHas('objectifs', [
                'id' => $objectif->id,
                'titre' => 'Modifier Objectif',
                'description' => 'Modifier description',
                'completer' => true,
            ]);
        }

        #[Test]
        public function  test_objectif_supprimer()
        {
            $user = User::factory()->create();
            $objectif = Objectif::factory()->create(['user_id' => $user->id]);

            $objectifId = $objectif->id;
            $objectif->delete();

            $this->assertDatabaseMissing('objectifs', [
                'id' => $objectifId,
            ]);
        }

        #[Test]
        public function test_objectif_belongs_to_a_user()
        {
            $user = User::factory()->create();
            $objectif = Objectif::factory()->create(['user_id' => $user->id]);

            $this->assertInstanceOf(User::class, $objectif->user);
            $this->assertEquals($user->id, $objectif->user->id);
        }
        #[Test]
        public function test_objectif_fillable()
        {
            $user = User::factory()->create();

            $fillableAttributes = [
                'titre' => 'Test Fillable',
                'description' => 'Test description',
                'completer' => false,
                'user_id' => $user->id,
            ];

            $objectif = new Objectif($fillableAttributes);

            foreach ($fillableAttributes as $key => $value) {
                $this->assertEquals($value, $objectif->$key);
            }
        }
    }

