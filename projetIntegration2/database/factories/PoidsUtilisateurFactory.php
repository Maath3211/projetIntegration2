<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\PoidsUtilisateur;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PoidsUtilisateur>
 */
class PoidsUtilisateurFactory extends Factory
{
  
    public function definition()
    {
        return [
        'semaine' => $this->faker->numberBetween(1, 52),
        'poids' => $this->faker->randomFloat(2, 50, 100),
        'user_id' => User::factory(),
        ];
    }
}
