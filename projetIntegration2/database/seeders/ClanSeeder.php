<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('clans')->insert([
            [
                'id'         => 1,
                'adminId'    => 1,
                'nom'        => 'Clan Alpha',
                'public'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 2,
                'adminId'    => 2,
                'nom'        => 'Clan Beta',
                'public'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 3,
                'adminId'    => 3,
                'nom'        => 'Clan Gamma',
                'public'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 4,
                'adminId'    => 4,
                'nom'        => 'Clan Delta',
                'public'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 5,
                'adminId'    => 5,
                'nom'        => 'Clan Epsilon',
                'public'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
