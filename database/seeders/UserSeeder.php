<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Iskender',
                'email' => 'iskender1@gmail.com',
                'password' => Hash::make('12345'),
            ],
            [
                'name' => 'Mahmut',
                'email' => 'mahmur@gmail.com',
                'password' => Hash::make('12345'),
            ],
            [
                'name' => 'Ayşe',
                'email' => 'ayse@gmail.com',
                'password' => Hash::make('12345'),
            ],
            [
                'name' => 'Fatma',
                'email' => 'fatma@gmail.com',
                'password' => Hash::make('12345'),
            ],
            [
                'name' => 'Ahmet',
                'email' => 'ahmet@gmail.com',
                'password' => Hash::make('12345'),
            ],
            [
                'name' => 'Mehmet',
                'email' => 'mehmet@gmail.com',
                'password' => Hash::make('12345'),
            ],
        ]);

    }
}
