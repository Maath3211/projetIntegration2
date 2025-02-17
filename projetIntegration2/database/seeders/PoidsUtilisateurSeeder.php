<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PoidsUtilisateur;

class PoidsUtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PoidsUtilisateur::insert([
            ['user_id' => 1, 'semaine' => 1, 'poids' => 152],
            ['user_id' => 1, 'semaine' => 2, 'poids' => 157],
            ['user_id' => 1, 'semaine' => 3, 'poids' => 145],
            ['user_id' => 1, 'semaine' => 4, 'poids' => 140],
            ['user_id' => 1, 'semaine' => 5, 'poids' => 143],
            ['user_id' => 1, 'semaine' => 6, 'poids' => 136],
        ]);
    }
}
