<?php

namespace Database\Factories;

use App\Models\Canal;
use App\Models\CategorieCanal;
use Illuminate\Database\Eloquent\Factories\Factory;

class CanalFactory extends Factory
{
    protected $model = Canal::class;

    public function definition()
    {
        $categorie = CategorieCanal::factory()->create();
        return [
            'titre' => $this->faker->word(),
            'clanId' => $categorie->clanId,
            'categorieId' => $categorie->id,
        ];
    }
}