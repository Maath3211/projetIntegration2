<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Statistiques;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UtilisateurSeeder::class);
        $this->call(ClanSeeder::class);
        $this->call(ScoreSeeder::class);
        $this->call(ClanUserSeeder::class);
        $this->call(StatistiquesSeeder::class);
    }
}
