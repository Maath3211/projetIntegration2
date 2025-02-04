<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClanUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('clan_users')->insert([
            [
                'id' => 1,
                'clan_id' => 1,  // Assume Clan Alpha has ID 1
                'user_id' => 1,   // Assume User1 has ID 1
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'clan_id' => 1,  // Assume Clan Alpha has ID 1
                'user_id' => 2,   // Assume User2 has ID 2
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'clan_id' => 2,  // Assume Clan Beta has ID 2
                'user_id' => 3,   // Assume User3 has ID 3
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more users and clans as needed
        ]);
    }
}
