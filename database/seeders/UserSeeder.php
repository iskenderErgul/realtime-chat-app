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
                'surname' => 'Ergul',
                'email' => 'iskender1@gmail.com',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mahmut',
                'surname' => 'Ergul',
                'email' => 'mahmut@gmail.com',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ayşe',
                'surname' => 'Yılmaz',
                'email' => 'ayse@gmail.com',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mehmet',
                'surname' => 'Demir',
                'email' => 'mehmet@gmail.com',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Zeynep',
                'surname' => 'Kaya',
                'email' => 'zeynep@gmail.com',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ali',
                'surname' => 'Çelik',
                'email' => 'ali@gmail.com',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fatma',
                'surname' => 'Şahin',
                'email' => 'fatma@gmail.com',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ahmet',
                'surname' => 'Öztürk',
                'email' => 'ahmet@gmail.com',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Elif',
                'surname' => 'Arslan',
                'email' => 'elif@gmail.com',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Burak',
                'surname' => 'Koç',
                'email' => 'burak@gmail.com',
                'password' => Hash::make('12345'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

    }
}
