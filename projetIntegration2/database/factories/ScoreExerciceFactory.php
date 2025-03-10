<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Statistiques;
use App\Models\ScoreExercice;

class ScoreExerciceFactory extends Factory
{
 
    public function definition()
    {
        return [
        'statistique_id' => Statistiques::factory(),
        'semaine' => $this->faker->numberBetween(1, 52),
        'score' => $this->faker->numberBetween(0, 100),
        ];
    }
}
