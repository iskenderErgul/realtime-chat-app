<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Önce grupları ve kullanıcıları alalım
        $group1 = Group::where('name', 'Grup 1')->first();
        $group2 = Group::where('name', 'Grup 2')->first();
        $user1 = User::where('email', 'iskender1@gmail.com')->first();

        GroupMember::create([
            'group_id' => $group1->id,
            'user_id' => $user1->id,
        ]);

        GroupMember::create([
            'group_id' => $group2->id,
            'user_id' => $user1->id,
        ]);


    }
}
