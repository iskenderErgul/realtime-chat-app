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
        // Proje Ekibi (Group 1) - Iskender, Mahmut, Ayşe, Mehmet
        $members = [
            ['group_id' => 1, 'user_id' => 1],
            ['group_id' => 1, 'user_id' => 2],
            ['group_id' => 1, 'user_id' => 3],
            ['group_id' => 1, 'user_id' => 4],
        ];

        // Futbol Takımı (Group 2) - Mehmet, Ali, Ahmet, Burak
        $members = array_merge($members, [
            ['group_id' => 2, 'user_id' => 4],
            ['group_id' => 2, 'user_id' => 6],
            ['group_id' => 2, 'user_id' => 8],
            ['group_id' => 2, 'user_id' => 10],
        ]);

        // Ders Çalışma Grubu (Group 3) - Ahmet, Elif, Burak, Zeynep
        $members = array_merge($members, [
            ['group_id' => 3, 'user_id' => 8],
            ['group_id' => 3, 'user_id' => 9],
            ['group_id' => 3, 'user_id' => 10],
            ['group_id' => 3, 'user_id' => 5],
        ]);

        // Aile (Group 4) - Iskender, Mahmut
        $members = array_merge($members, [
            ['group_id' => 4, 'user_id' => 1],
            ['group_id' => 4, 'user_id' => 2],
        ]);

        // Arkadaşlar (Group 5) - Ayşe, Zeynep, Fatma, Elif
        $members = array_merge($members, [
            ['group_id' => 5, 'user_id' => 3],
            ['group_id' => 5, 'user_id' => 5],
            ['group_id' => 5, 'user_id' => 7],
            ['group_id' => 5, 'user_id' => 9],
        ]);

        foreach ($members as $member) {
            GroupMember::create($member);
        }
    }
}
