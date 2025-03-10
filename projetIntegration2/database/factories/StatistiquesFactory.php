<?php

namespace Database\Factories;

use App\Models\Statistiques;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StatistiquesFactory extends Factory

{

    protected $model = Statistiques::class;



    public function definition()

    {

        return [

            'nomStatistique' => $this->faker->word,

            'score' => $this->faker->numberBetween(1, 100),

            'date' => $this->faker->dateTime,

            'user_id' => User::factory(),

        ];

    }

}
