<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Statistique;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class StatistiqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Statistique::create([
            'id' => 1,
            'nomStatistique' => 'Poids',
            'score' => 110,
            'user_id' => 1,
            'date' => now(),
        ]);

        Statistique::create([
            'id' => 1,
            'nomStatistique' => 'Streak',
            'score' => 5,
            'user_id' => 1,
            'date' => now(),
        ]);
    }
}
