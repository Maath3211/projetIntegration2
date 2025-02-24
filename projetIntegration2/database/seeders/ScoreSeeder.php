<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScoreSeeder extends Seeder
{
    public function run(): void
    {
        $scores = [];

        // For each of 20 users, create 2 score entries at different dates with random scores under 200
        for ($userId = 1; $userId <= 20; $userId++) {
            
            // First score date: 2025-02-01 plus offset based on user id
            $date1 = Carbon::parse('2025-02-01')->addDays($userId - 1)->format('Y-m-d');
            // Second score date: 2025-03-01 plus offset based on user id
            $date2 = Carbon::parse('2025-03-01')->addDays($userId - 1)->format('Y-m-d');

            $scores[] = [
                'user_id'    => $userId,
                'date'       => $date1,
                'score'      => mt_rand(0, 199),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $scores[] = [
                'user_id'    => $userId,
                'date'       => $date2,
                'score'      => mt_rand(0, 199),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('scores')->insert($scores);
    }
}
