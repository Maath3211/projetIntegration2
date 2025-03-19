<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'message' => $this->faker->sentence(),
            'idEnvoyer' => 1,  // Remplace par une valeur dynamique si nécessaire
            'idReceveur' => 2,
            'fichier' => null,
            'created_at' => now(),
        ];
    }
}
