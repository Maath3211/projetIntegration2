<?php

namespace Database\Factories;

use App\Models\Profil;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProfilFactory extends Factory
{
    protected $model = Profil::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'prenom' => $this->faker->firstName,
            'nom' => $this->faker->lastName,
            'imageProfil' => $this->faker->imageUrl,
            'pays' => $this->faker->country,
            'genre' => $this->faker->randomElement(['male', 'female']),
            'dateNaissance' => $this->faker->date,
            'codeVerification' => Str::random(10),
            'password' => bcrypt('password'), // default password
            'remember_token' => Str::random(10),
        ];
    }
}