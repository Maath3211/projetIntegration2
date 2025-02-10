<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('scores')->insert([
            [
                'id' => 1,
                'user_id' => '1',
                'date' => '2025-02-01',
                'score' => 150,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => '2',
                'date' => '2025-02-02',
                'score' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'user_id' => '3',
                'date' => '2025-02-03',
                'score' => 180,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
