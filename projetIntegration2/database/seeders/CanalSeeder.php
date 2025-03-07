<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CanalSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('canals')->insert([
            [
                'titre' => 'bienvenue',
                'clanId' => 1,
                'categorieId' => 1
            ],
            [
                'titre' => 'annonces',
                'clanId' => 1,
                'categorieId' => 1
            ],
            [
                'titre' => 'règles-et-information',
                'clanId' => 1,
                'categorieId' => 1
            ],
            [
                'titre' => 'introductions',
                'clanId' => 1,
                'categorieId' => 1
            ],
            [
                'titre' => 'trucs-et-astuces',
                'clanId' => 1,
                'categorieId' => 2
            ],
            [
                'titre' => 'plan entrainement',
                'clanId' => 1,
                'categorieId' => 2
            ],
            [
                'titre' => 'images-progrès',
                'clanId' => 1,
                'categorieId' => 2
            ],
            [
                'titre' => 'nutrition',
                'clanId' => 1,
                'categorieId' => 2
            ],
            [
                'titre' => 'efforts-journaliers',
                'clanId' => 1,
                'categorieId' => 2
            ],
            [
                'titre' => 'zone-de-récupération',
                'clanId' => 1,
                'categorieId' => 2
            ],
            [
                'titre' => 'musculation',
                'clanId' => 1,
                'categorieId' => 2
            ],
            [
                'titre' => 'cardio',
                'clanId' => 1,
                'categorieId' => 2
            ],
            [
                'titre' => 'entraînements-maison',
                'clanId' => 1,
                'categorieId' => 2
            ],
            [
                'titre' => 'discussion',
                'clanId' => 1,
                'categorieId' => 2
            ],
            [
                'titre' => 'défis-hebdomadaires',
                'clanId' => 1,
                'categorieId' => 3
            ],
            [
                'titre' => 'combats-de-clans',
                'clanId' => 1,
                'categorieId' => 3
            ],
            [
                'titre' => 'mur-de-motivation',
                'clanId' => 1,
                'categorieId' => 3
            ],
            [
                'titre' => 'victoires',
                'clanId' => 1,
                'categorieId' => 3
            ],
        ]);
    }
}