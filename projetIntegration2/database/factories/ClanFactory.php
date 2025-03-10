<?php

namespace Database\Factories;

use App\Models\Clan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClanFactory extends Factory
{
    protected $model = Clan::class;

    public function definition()
    {
        return [
            'adminId' => User::factory(),
            'image' => $this->faker->imageUrl(640, 480, 'clan', true, 'default.jpg'),
            'nom' => $this->faker->company(),
            'public' => $this->faker->boolean(),
        ];
    }
}