<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
            'email'=> 'test@test.com',
            'prenom'=> 'Mathys',
            'nom'=> 'Lessard',
            'imageProfil'=> 'img/Utilisateurs/shrek.jpg',
            'aPropos'=> 'Je suis un ogre',
            'pays'=> 'canada',
            'genre'=> 'homme',
            'dateNaissance'=> Carbon::create('2004', '12', '07'),
            'password' =>Hash::make('adminggg'),
            'created_at' => now(),
        ]);
        DB::table('users')->insert([
            'id' => 2,
            'email'=> 'test2@test.com',
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
            'email'=> 'test3@test.com',
            'prenom'=> 'prenom',
            'nom'=> 'nom',
            'imageProfil'=> 'lienImage',
            'pays'=> 'canada',
            'genre'=> 'homme',
            'dateNaissance'=> Carbon::create('2000', '01', '01'),
            'password' =>Hash::make('adminggg')
        ]);
        DB::table('users')->insert([
            'id' => 4,
            'email' => 'test4@example.com',
            'prenom' => 'Prenom4',
            'nom' => 'Nom4',
            'imageProfil' => 'img/Utilisateurs/default4.jpg',
            'pays' => 'canada',
            'genre' => 'homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 5,
            'email' => 'test5@example.com',
            'prenom' => 'Prenom5',
            'nom' => 'Nom5',
            'imageProfil' => 'img/Utilisateurs/default5.jpg',
            'pays' => 'canada',
            'genre' => 'femme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 6,
            'email' => 'test6@example.com',
            'prenom' => 'Prenom6',
            'nom' => 'Nom6',
            'imageProfil' => 'img/Utilisateurs/default6.jpg',
            'pays' => 'canada',
            'genre' => 'homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 7,
            'email' => 'test7@example.com',
            'prenom' => 'Prenom7',
            'nom' => 'Nom7',
            'imageProfil' => 'img/Utilisateurs/default7.jpg',
            'pays' => 'canada',
            'genre' => 'femme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 8,
            'email' => 'test8@example.com',
            'prenom' => 'Prenom8',
            'nom' => 'Nom8',
            'imageProfil' => 'img/Utilisateurs/default8.jpg',
            'pays' => 'canada',
            'genre' => 'homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 9,
            'email' => 'test9@example.com',
            'prenom' => 'Prenom9',
            'nom' => 'Nom9',
            'imageProfil' => 'img/Utilisateurs/default9.jpg',
            'pays' => 'canada',
            'genre' => 'femme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 10,
            'email' => 'test10@example.com',
            'prenom' => 'Prenom10',
            'nom' => 'Nom10',
            'imageProfil' => 'img/Utilisateurs/default10.jpg',
            'pays' => 'canada',
            'genre' => 'homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 11,
            'email' => 'test11@example.com',
            'prenom' => 'Prenom11',
            'nom' => 'Nom11',
            'imageProfil' => 'img/Utilisateurs/default11.jpg',
            'pays' => 'canada',
            'genre' => 'femme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 12,
            'email' => 'test12@example.com',
            'prenom' => 'Prenom12',
            'nom' => 'Nom12',
            'imageProfil' => 'img/Utilisateurs/default12.jpg',
            'pays' => 'canada',
            'genre' => 'homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 13,
            'email' => 'test13@example.com',
            'prenom' => 'Prenom13',
            'nom' => 'Nom13',
            'imageProfil' => 'img/Utilisateurs/default13.jpg',
            'pays' => 'canada',
            'genre' => 'femme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 14,
            'email' => 'test14@example.com',
            'prenom' => 'Prenom14',
            'nom' => 'Nom14',
            'imageProfil' => 'img/Utilisateurs/default14.jpg',
            'pays' => 'canada',
            'genre' => 'homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 15,
            'email' => 'test15@example.com',
            'prenom' => 'Prenom15',
            'nom' => 'Nom15',
            'imageProfil' => 'img/Utilisateurs/default15.jpg',
            'pays' => 'canada',
            'genre' => 'femme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 16,
            'email' => 'test16@example.com',
            'prenom' => 'Prenom16',
            'nom' => 'Nom16',
            'imageProfil' => 'img/Utilisateurs/default16.jpg',
            'pays' => 'canada',
            'genre' => 'homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 17,
            'email' => 'test17@example.com',
            'prenom' => 'Prenom17',
            'nom' => 'Nom17',
            'imageProfil' => 'img/Utilisateurs/default17.jpg',
            'pays' => 'canada',
            'genre' => 'homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 18,
            'email' => 'test18@example.com',
            'prenom' => 'Prenom18',
            'nom' => 'Nom18',
            'imageProfil' => 'img/Utilisateurs/default18.jpg',
            'pays' => 'canada',
            'genre' => 'homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 19,
            'email' => 'test19@example.com',
            'prenom' => 'Prenom19',
            'nom' => 'Nom19',
            'imageProfil' => 'img/Utilisateurs/default19.jpg',
            'pays' => 'canada',
            'genre' => 'homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 20,
            'email' => 'test20@example.com',
            'prenom' => 'Prenom20',
            'nom' => 'Nom20',
            'imageProfil' => 'img/Utilisateurs/default20.jpg',
            'pays' => 'canada',
            'genre' => 'homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
