<?php

namespace Database\Factories;

use App\Models\Clan;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClanFactory extends Factory
{
    protected $model = Clan::class;

    public function definition()
    {
        return [
            'adminId' => User::factory(),
            'image' => 'default.jpg',
            'nom' => $this->faker->word(),
            'public' => $this->faker->boolean(),
        ];
    }
}