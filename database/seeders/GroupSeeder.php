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
            'name' => 'Proje Ekibi',
            'description' => 'Ana proje geliştirme ekibi',
            'admin_id' => 1,
        ]);

        Group::create([
            'name' => 'Futbol Takımı',
            'description' => 'Haftalık futbol maçları için grup',
            'admin_id' => 4,
        ]);

        Group::create([
            'name' => 'Ders Çalışma Grubu',
            'description' => 'Üniversite dersleri için çalışma grubu',
            'admin_id' => 8,
        ]);

        Group::create([
            'name' => 'Aile',
            'description' => 'Aile üyeleri grubu',
            'admin_id' => 1,
        ]);

        Group::create([
            'name' => 'Arkadaşlar',
            'description' => 'Yakın arkadaşlar grubu',
            'admin_id' => 3,
        ]);

    }
}
