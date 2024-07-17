<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Group::create([
            'name' => 'Grup 1',
            'description' => 'Bu grup ile ilgili açıklama',
            'admin_id' => 1,
        ]);

        Group::create([
            'name' => 'Grup 2',
            'description' => 'Başka bir grup açıklaması',
            'admin_id' => 1,
        ]);

    }
}
