<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\StatThermique;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Statistiques>
 */
class StatThermiqueFactory extends Factory
{
   
    protected $model = StatThermique::class;
    public function definition()
    {
        return [
            'date' => $this->faker->date(),
            'type_activite' => $this->faker->numberBetween(1, 6),
            'user_id' => User::factory(),
        ];
    }
}
