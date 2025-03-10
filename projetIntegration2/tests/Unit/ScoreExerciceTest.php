<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\ScoreExercice;
    
    class ScoreExerciceTest extends TestCase
    {
        use DatabaseTransactions;

        #[Test]
        public function test_score_exercice_creation()
        {
            $scoreExercice = ScoreExercice::factory()->create();

            $this->assertDatabaseHas('score_exercice', [
                'id' => $scoreExercice->id,
            ]);
        }

        #[Test]
        public function test_score_exercice_belongs_to_statistique()
        {
            $scoreExercice = ScoreExercice::factory()->create();

            $this->assertNotNull($scoreExercice->statistique);
        }

        #[Test]
        public function test_score_exercice_modifier()
        {
            $scoreExercice = ScoreExercice::factory()->create();
            $newScore = 100;

            $scoreExercice->update(['score' => $newScore]);

            $this->assertDatabaseHas('score_exercice', [
                'id' => $scoreExercice->id,
                'score' => $newScore,
            ]);
        }

        #[Test]
        public function test_score_exercice_supprimer()
        {
            $scoreExercice = ScoreExercice::factory()->create();

            $scoreExercice->delete();

            $this->assertDatabaseMissing('score_exercice', [
                'id' => $scoreExercice->id,
            ]);
        }
        #[Test]
        public function test_score_exercice_fillable()
        {
            $fillableAttributes = ['statistique_id', 'semaine', 'score'];
            $scoreExercice = new ScoreExercice();

            foreach ($fillableAttributes as $attribute) {
                $this->assertTrue($scoreExercice->isFillable($attribute), "$attribute is not fillable");
            }
        }
    }

