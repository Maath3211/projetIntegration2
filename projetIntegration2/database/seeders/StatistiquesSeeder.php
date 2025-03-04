<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Statistiques;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class StatistiquesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Statistiques::create([
            'id' => 1,
            'nomStatistique' => 'Poids',
            'score' => 110,
            'user_id' => 1,
            'date' => now(),
        ]);

        Statistiques::create([
            'id' => 2,
            'nomStatistique' => 'Streak',
            'score' => 5,
            'user_id' => 1,
            'date' => now(),
        ]);

        Statistiques::create([
            'id' => 3,
            'nomStatistique' => 'FoisGym',
            'score' => 503,
            'user_id' => 1,
            'date' => now(),
        ]);
        
        Statistiques::create([
            'id' => 4,
            'nomStatistique' => 'test',
            'score' => 40,
            'user_id' => 1,
            'date' => now(),
        ]);
    }
}
