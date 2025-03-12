<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesCanalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories_canal')->insert([
            [
                'clanId' => 1,
                'categorie' => 'Général'
            ],
            [
                'clanId' => 1,
                'categorie' => 'Aide'
            ],
            [
                'clanId' => 1,
                'categorie' => 'Compétition'
            ],
            [
                'clanId' => 2,
                'categorie' => 'Général'
            ],
            [
                'clanId' => 2,
                'categorie' => 'Aide'
            ],
            [
                'clanId' => 2,
                'categorie' => 'Compétition'
            ],
            [
                'clanId' => 3,
                'categorie' => 'Général'
            ],
            [
                'clanId' => 3,
                'categorie' => 'Aide'
            ],
            [
                'clanId' => 3,
                'categorie' => 'Compétition'
            ],
            [
                'clanId' => 4,
                'categorie' => 'Général'
            ],
            [
                'clanId' => 4,
                'categorie' => 'Aide'
            ],
            [
                'clanId' => 4,
                'categorie' => 'Compétition'
            ],
            [
                'clanId' => 5,
                'categorie' => 'Général'
            ],
            [
                'clanId' => 5,
                'categorie' => 'Aide'
            ],
            [
                'clanId' => 5,
                'categorie' => 'Compétition'
            ]
        ]);
    }
}
