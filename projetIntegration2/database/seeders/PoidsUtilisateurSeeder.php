<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PoidsUtilisateur;

class PoidsUtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PoidsUtilisateur::insert([
            ['id' => 1,'user_id' => 1, 'semaine' => 1, 'poids' => 152],
            ['id' => 2,'user_id' => 1, 'semaine' => 2, 'poids' => 157],
            ['id' => 3,'user_id' => 1, 'semaine' => 3, 'poids' => 145],
            ['id' => 4,'user_id' => 1, 'semaine' => 4, 'poids' => 140],
            ['id' => 5,'user_id' => 1, 'semaine' => 5, 'poids' => 143],
            ['id' => 6,'user_id' => 1, 'semaine' => 6, 'poids' => 136],
            ['id' => 7,'user_id' => 2, 'semaine' => 1, 'poids' => 152],
            ['id' => 8,'user_id' => 3, 'semaine' => 2, 'poids' => 157],
            ['id' => 9,'user_id' => 4, 'semaine' => 3, 'poids' => 145],
            ['id' => 10,'user_id' => 5, 'semaine' => 4, 'poids' => 140],
            ['id' => 11,'user_id' => 6, 'semaine' => 5, 'poids' => 143],
            ['id' => 12,'user_id' => 7, 'semaine' => 6, 'poids' => 136],
            ['id' => 13,'user_id' => 8, 'semaine' => 1, 'poids' => 152],
            ['id' => 14,'user_id' => 9, 'semaine' => 2, 'poids' => 157],
            ['id' => 15,'user_id' => 10, 'semaine' => 3, 'poids' => 145],
            ['id' => 16,'user_id' => 11, 'semaine' => 4, 'poids' => 140],
            ['id' => 17,'user_id' => 12, 'semaine' => 5, 'poids' => 143],
            ['id' => 18,'user_id' => 13, 'semaine' => 6, 'poids' => 136],
            ['id' => 19,'user_id' => 14, 'semaine' => 3, 'poids' => 145],
            ['id' => 20,'user_id' => 15, 'semaine' => 4, 'poids' => 140],
            ['id' => 21,'user_id' => 16, 'semaine' => 5, 'poids' => 143],
            ['id' => 22,'user_id' => 17, 'semaine' => 6, 'poids' => 136],
            ['id' => 23,'user_id' => 18, 'semaine' => 1, 'poids' => 152],
            ['id' => 24,'user_id' => 19, 'semaine' => 2, 'poids' => 157],
            ['id' => 25,'user_id' => 20, 'semaine' => 3, 'poids' => 145],
            ['id' => 26,'user_id' => 11, 'semaine' => 4, 'poids' => 140],
            ['id' => 27,'user_id' => 12, 'semaine' => 5, 'poids' => 143],
            ['id' => 28,'user_id' => 13, 'semaine' => 6, 'poids' => 136],
        ]);

    }
}
