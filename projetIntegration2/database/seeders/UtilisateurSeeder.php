<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Hash;

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
            'password' =>Hash::make('adminggg')
        ]);
        DB::table('users')->insert([
            'id' => 2,
            'email'=> 'test2',
            'password' =>Hash::make('adminggg')
        ]);
        DB::table('users')->insert([
            'id' => 3,
            'email'=> 'test3',
            'password' =>Hash::make('adminggg')
        ]);
    }
}
