<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesCanalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories_canal')->insert([
            [
                'clanId' => 1,
                'categorie' => 'testCat'
            ],
            [
                'clanId' => 1,
                'categorie' => 'testCat2'
            ]
        ]);
    }
}
