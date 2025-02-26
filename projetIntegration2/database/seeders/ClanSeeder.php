<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('clans')->insert([
            [
                'id' => 1,
                'adminId' => '1',
                'image' => 'img/Clans/clan_1_.png',
                'nom' => 'Clan Alpha',
                'public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'adminId' => '2',
                'image' => 'img/Clans/default.jpg',
                'nom' => 'Clan Beta',
                'public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'adminId' => '3',
                'image' => 'img/Clans/default.jpg',
                'nom' => 'Clan Gamma',
                'public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
