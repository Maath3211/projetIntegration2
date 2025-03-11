<?php

namespace Database\Factories;

use App\Models\CategorieCanal;
use App\Models\Clan;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategorieCanalFactory extends Factory
{
    protected $model = CategorieCanal::class;

    public function definition()
    {
        return [
            'categorie' => $this->faker->word(),
            'clanId' => Clan::factory(),
        ];
    }
}