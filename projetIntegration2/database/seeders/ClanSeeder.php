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
                'id' => 1,
                'adminId' => 1,
                'image' => 'img/Clans/clan_1_.jpg',
                'nom' => 'Clan Alpha',
                'public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'adminId' => 2,
                'image' => 'img/Clans/clan_2_.jpg',
                'nom' => 'Clan Beta',
                'public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 3,
                'adminId'    => 3,
                'image'      => 'img/Clans/clan_3_.jpg',
                'nom'        => 'Clan Gamma',
                'public'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 4,
                'adminId'    => 4,
                'image' => 'img/Clans/clan_4_.webp',
                'nom'        => 'Clan Delta',
                'public'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 5,
                'adminId'    => 5,
                'image' => 'img/Clans/clan_5_.jpg',
                'nom'        => 'Clan Epsilon',
                'public'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 6,
                'adminId'    => 6,
                'image'      => 'img/Clans/clan_6_.jpg',
                'nom'        => 'Clan Zeta',
                'public'     => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 7,
                'adminId'    => 7,
                'image'      => 'img/Clans/clan_7_.png',
                'nom'        => 'Clan Eta',
                'public'     => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 8,
                'adminId'    => 8,
                'image'      => 'img/Clans/clan_8_.jpg',
                'nom'        => 'Clan Theta',
                'public'     => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 9,
                'adminId'    => 9,
                'image'      => 'img/Clans/clan_9_.jpg',
                'nom'        => 'Clan Iota',
                'public'     => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id'         => 10,
                'adminId'    => 10,
                'image'      => 'img/Clans/clan_10_.jpg',
                'nom'        => 'Clan Kappa',
                'public'     => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
