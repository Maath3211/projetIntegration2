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
            'pays'=> 'Canada',
            'genre'=> 'Homme',
            'dateNaissance'=> Carbon::create('2004', '12', '07'),
            'password' =>Hash::make('adminggg'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'email'=> 'test2@test.com',
            'prenom'=> 'Julien',
            'nom'=> 'Lachance',
            'imageProfil'=> 'img/Utilisateurs/shrek.jpg',
            'pays'=> 'Canada',
            'genre'=> 'Homme',
            'dateNaissance'=> Carbon::create('2000', '01', '01'),
            'password' =>Hash::make('adminggg'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 3,
            'email'=> 'test3@test.com',
            'prenom'=> 'Sophie',
            'nom'=> 'Beauchesne',
            'imageProfil'=> 'img/Utilisateurs/shrek.jpg',
            'pays'=> 'Canada',
            'genre'=> 'Femme',
            'dateNaissance'=> Carbon::create('2000', '01', '01'),
            'password' =>Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 4,
            'email' => 'test4@example.com',
            'prenom' => 'Carl',
            'nom' => 'Stravinsky',
            'imageProfil' => 'img/Utilisateurs/default4.jpg',
            'pays' => 'Canada',
            'genre' => 'Homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 5,
            'email' => 'test5@example.com',
            'prenom' => 'Rosalie',
            'nom' => 'Lapointe',
            'imageProfil' => 'img/Utilisateurs/default5.jpg',
            'pays' => 'Canada',
            'genre' => 'Femme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 6,
            'email' => 'test6@example.com',
            'prenom' => 'Hubert',
            'nom' => 'Lemieux',
            'imageProfil' => 'img/Utilisateurs/default6.jpg',
            'pays' => 'Canada',
            'genre' => 'Homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 7,
            'email' => 'test7@example.com',
            'prenom' => 'Talia',
            'nom' => 'Lavoie',
            'imageProfil' => 'img/Utilisateurs/default7.jpg',
            'pays' => 'Canada',
            'genre' => 'Femme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 8,
            'email' => 'test8@example.com',
            'prenom' => 'Stéphane',
            'nom' => 'Lemay',
            'imageProfil' => 'img/Utilisateurs/default8.jpg',
            'pays' => 'Canada',
            'genre' => 'Homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 9,
            'email' => 'test9@example.com',
            'prenom' => 'Patricia',
            'nom' => 'Labarre',
            'imageProfil' => 'img/Utilisateurs/default9.jpg',
            'pays' => 'Canada',
            'genre' => 'Femme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 10,
            'email' => 'test10@example.com',
            'prenom' => 'Jules',
            'nom' => 'Csikos',
            'imageProfil' => 'img/Utilisateurs/default10.jpg',
            'pays' => 'Canada',
            'genre' => 'Homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 11,
            'email' => 'test11@example.com',
            'prenom' => 'Katherine',
            'nom' => 'Larose',
            'imageProfil' => 'img/Utilisateurs/default11.jpg',
            'pays' => 'Canada',
            'genre' => 'Femme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 12,
            'email' => 'test12@example.com',
            'prenom' => 'Francis',
            'nom' => 'St-Pierre',
            'imageProfil' => 'img/Utilisateurs/default12.jpg',
            'pays' => 'Canada',
            'genre' => 'Homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 13,
            'email' => 'test13@example.com',
            'prenom' => 'Marie',
            'nom' => 'Lemieux',
            'imageProfil' => 'img/Utilisateurs/default13.jpg',
            'pays' => 'Canada',
            'genre' => 'Femme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 14,
            'email' => 'test14@example.com',
            'prenom' => 'Benjamin',
            'nom' => 'Lemay',
            'imageProfil' => 'img/Utilisateurs/default14.jpg',
            'pays' => 'Canada',
            'genre' => 'Homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 15,
            'email' => 'test15@example.com',
            'prenom' => 'Sylvie',
            'nom' => 'Laflamme',
            'imageProfil' => 'img/Utilisateurs/default15.jpg',
            'pays' => 'Canada',
            'genre' => 'Femme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 16,
            'email' => 'test16@example.com',
            'prenom' => 'Patrice',
            'nom' => 'Lafaillette',
            'imageProfil' => 'img/Utilisateurs/default16.jpg',
            'pays' => 'Canada',
            'genre' => 'Homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 17,
            'email' => 'test17@example.com',
            'prenom' => 'Malik',
            'nom' => 'Carter',
            'imageProfil' => 'img/Utilisateurs/default17.jpg',
            'pays' => 'Canada',
            'genre' => 'Homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 18,
            'email' => 'test18@example.com',
            'prenom' => 'Sylvain',
            'nom' => 'Lemieux',
            'imageProfil' => 'img/Utilisateurs/default18.jpg',
            'pays' => 'Canada',
            'genre' => 'Homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 19,
            'email' => 'test19@example.com',
            'prenom' => 'Bob',
            'nom' => 'Tremblay',
            'imageProfil' => 'img/Utilisateurs/default19.jpg',
            'pays' => 'Canada',
            'genre' => 'Homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 20,
            'email' => 'test20@example.com',
            'prenom' => 'Rémi',
            'nom' => 'Faucher',
            'imageProfil' => 'img/Utilisateurs/default20.jpg',
            'pays' => 'Canada',
            'genre' => 'Homme',
            'dateNaissance' => Carbon::create('2000', '01', '01'),
            'password' => Hash::make('adminggg'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}