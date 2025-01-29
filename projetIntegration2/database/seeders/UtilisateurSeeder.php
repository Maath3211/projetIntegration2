<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Hash;
use Carbon\Carbon;

class UtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => 1,
            'email'=> 'test',
            'prenom'=> 'prenom',
            'nom'=> 'nom',
            'imageProfil'=> 'lienImage',
            'pays'=> 'canada',
            'genre'=> 'homme',
            'dateNaissance'=> Carbon::create('2000', '01', '01'),
            'password' =>Hash::make('adminggg')
        ]);
        DB::table('users')->insert([
            'id' => 2,
            'email'=> 'test2',
            'prenom'=> 'prenom',
            'nom'=> 'nom',
            'imageProfil'=> 'lienImage',
            'pays'=> 'canada',
            'genre'=> 'homme',
            'dateNaissance'=> Carbon::create('2000', '01', '01'),
            'password' =>Hash::make('adminggg')
        ]);
        DB::table('users')->insert([
            'id' => 3,
            'email'=> 'test3',
            'prenom'=> 'prenom',
            'nom'=> 'nom',
            'imageProfil'=> 'lienImage',
            'pays'=> 'canada',
            'genre'=> 'homme',
            'dateNaissance'=> Carbon::create('2000', '01', '01'),
            'password' =>Hash::make('adminggg')
        ]);
    }
}
