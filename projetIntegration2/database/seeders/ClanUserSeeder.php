<?php

namespace Database\Seeders;

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
                'id'         => 1,
                'clan_id'    => ((1 * 3) % 5) + 1,  // 4
                'user_id'    => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 2,
                'clan_id'    => ((2 * 3) % 5) + 1,  // 2
                'user_id'    => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 3,
                'clan_id'    => ((3 * 3) % 5) + 1,  // 5
                'user_id'    => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 4,
                'clan_id'    => ((4 * 3) % 5) + 1,  // 3
                'user_id'    => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 5,
                'clan_id'    => ((5 * 3) % 5) + 1,  // 1
                'user_id'    => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 6,
                'clan_id'    => ((6 * 3) % 5) + 1,  // 4
                'user_id'    => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 7,
                'clan_id'    => ((7 * 3) % 5) + 1,  // 2
                'user_id'    => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 8,
                'clan_id'    => ((8 * 3) % 5) + 1,  // 5
                'user_id'    => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 9,
                'clan_id'    => ((9 * 3) % 5) + 1,  // 3
                'user_id'    => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 10,
                'clan_id'    => ((10 * 3) % 5) + 1, // 1
                'user_id'    => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 11,
                'clan_id'    => ((11 * 3) % 5) + 1, // 4
                'user_id'    => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 12,
                'clan_id'    => ((12 * 3) % 5) + 1, // 2
                'user_id'    => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 13,
                'clan_id'    => ((13 * 3) % 5) + 1, // 5
                'user_id'    => 13,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 14,
                'clan_id'    => ((14 * 3) % 5) + 1, // 3
                'user_id'    => 14,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 15,
                'clan_id'    => ((15 * 3) % 5) + 1, // 1
                'user_id'    => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 16,
                'clan_id'    => ((16 * 3) % 5) + 1, // 4
                'user_id'    => 16,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 17,
                'clan_id'    => ((17 * 3) % 5) + 1, // 2
                'user_id'    => 17,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 18,
                'clan_id'    => ((18 * 3) % 5) + 1, // 5
                'user_id'    => 18,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 19,
                'clan_id'    => ((19 * 3) % 5) + 1, // 3
                'user_id'    => 19,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 20,
                'clan_id'    => ((20 * 3) % 5) + 1, // 1
                'user_id'    => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
