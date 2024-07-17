<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\GroupMessage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $group1 = Group::where('name', 'Grup 1')->first();
        $group2 = Group::where('name', 'Grup 2')->first();

        GroupMessage::create([
            'group_id' => $group1->id,
            'sender_id' => 1, // Örnek olarak bir kullanıcı ID'si
            'message' => 'Grup 1 için bir mesaj.',
            'type' => 'text',
            'file_path' => null,
        ]);

        GroupMessage::create([
            'group_id' => $group2->id,
            'sender_id' => 1, // Örnek olarak bir kullanıcı ID'si
            'message' => 'Grup 2 için bir mesaj.',
            'type' => 'text',
            'file_path' => null,
        ]);

        // Daha fazla mesaj ekleyebilirsiniz
    }
}
