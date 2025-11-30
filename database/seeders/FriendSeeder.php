<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create friendships between users
        $friendships = [
            // Iskender's friends
            ['user_id' => 1, 'friend_id' => 2], // Iskender <-> Mahmut
            ['user_id' => 2, 'friend_id' => 1],
            
            ['user_id' => 1, 'friend_id' => 3], // Iskender <-> Ayşe
            ['user_id' => 3, 'friend_id' => 1],
            
            ['user_id' => 1, 'friend_id' => 4], // Iskender <-> Mehmet
            ['user_id' => 4, 'friend_id' => 1],
            
            ['user_id' => 1, 'friend_id' => 6], // Iskender <-> Ali
            ['user_id' => 6, 'friend_id' => 1],
            
            // Mahmut's friends
            ['user_id' => 2, 'friend_id' => 3], // Mahmut <-> Ayşe
            ['user_id' => 3, 'friend_id' => 2],
            
            ['user_id' => 2, 'friend_id' => 5], // Mahmut <-> Zeynep
            ['user_id' => 5, 'friend_id' => 2],
            
            ['user_id' => 2, 'friend_id' => 7], // Mahmut <-> Fatma
            ['user_id' => 7, 'friend_id' => 2],
            
            // Other friendships
            ['user_id' => 3, 'friend_id' => 4], // Ayşe <-> Mehmet
            ['user_id' => 4, 'friend_id' => 3],
            
            ['user_id' => 3, 'friend_id' => 5], // Ayşe <-> Zeynep
            ['user_id' => 5, 'friend_id' => 3],
            
            ['user_id' => 4, 'friend_id' => 6], // Mehmet <-> Ali
            ['user_id' => 6, 'friend_id' => 4],
            
            ['user_id' => 5, 'friend_id' => 7], // Zeynep <-> Fatma
            ['user_id' => 7, 'friend_id' => 5],
            
            ['user_id' => 6, 'friend_id' => 8], // Ali <-> Ahmet
            ['user_id' => 8, 'friend_id' => 6],
            
            ['user_id' => 7, 'friend_id' => 9], // Fatma <-> Elif
            ['user_id' => 9, 'friend_id' => 7],
            
            ['user_id' => 8, 'friend_id' => 9], // Ahmet <-> Elif
            ['user_id' => 9, 'friend_id' => 8],
            
            ['user_id' => 8, 'friend_id' => 10], // Ahmet <-> Burak
            ['user_id' => 10, 'friend_id' => 8],
            
            ['user_id' => 9, 'friend_id' => 10], // Elif <-> Burak
            ['user_id' => 10, 'friend_id' => 9],
        ];

        foreach ($friendships as $friendship) {
            DB::table('friends')->insert([
                'user_id' => $friendship['user_id'],
                'friend_id' => $friendship['friend_id'],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
