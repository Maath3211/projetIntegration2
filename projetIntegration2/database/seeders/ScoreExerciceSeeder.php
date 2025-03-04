<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ScoreExercice;

class ScoreExerciceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ScoreExercice::insert([
            ['id' => 1, 'statistique_id' => 4, 'semaine' => 1, 'score' => 75],
            ['id' => 2, 'statistique_id' => 4, 'semaine' => 2, 'score' => 80],
            ['id' => 3, 'statistique_id' => 4, 'semaine' => 3, 'score' => 78],
            ['id' => 4, 'statistique_id' => 4, 'semaine' => 4, 'score' => 82],
            ['id' => 5, 'statistique_id' => 4, 'semaine' => 5, 'score' => 79],
            ['id' => 6, 'statistique_id' => 4, 'semaine' => 6, 'score' => 81],
        ]);
    }
}
