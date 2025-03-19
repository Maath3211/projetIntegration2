<?php

namespace Database\Factories;

use App\Models\UtilisateurClan;
use Illuminate\Database\Eloquent\Factories\Factory;


class UtilisateurClanFactory extends Factory
{
    protected $model = UtilisateurClan::class;

    public function definition()
    {
        return [
            'message' => $this->faker->sentence(),
            'idEnvoyer' => 1,  // Remplace par une valeur dynamique si nécessaire
            'idClan' => 1,
            'idCanal' => 1,
            'fichier' => null,
            'created_at' => now(),
        ];
    }
}
