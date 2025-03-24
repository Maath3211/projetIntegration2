<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'prenom' => $this->faker->firstName,
            'nom' => $this->faker->lastName,
            'imageProfil' => $this->faker->imageUrl,
            'pays' => $this->faker->country,
            'aPropos' => $this->faker->sentence,
            'genre' => $this->faker->randomElement(['male', 'female']),
            'dateNaissance' => $this->faker->date,
            'codeVerification' => Str::random(10),
            'password' => Hash::make('password'), // default password
            'remember_token' => Str::random(10),
        ];
    }
}