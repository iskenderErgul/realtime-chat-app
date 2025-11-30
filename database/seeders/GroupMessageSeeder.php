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
        $messages = [
            // Proje Ekibi (Group 1)
            ['group_id' => 1, 'sender_id' => 1, 'message' => 'Arkadaşlar, bugün toplantı var mı?', 'type' => 'text', 'created_at' => now()->subHours(3)],
            ['group_id' => 1, 'sender_id' => 2, 'message' => 'Evet, saat 14:00\'da', 'type' => 'text', 'created_at' => now()->subHours(2)->subMinutes(55)],
            ['group_id' => 1, 'sender_id' => 3, 'message' => 'Ben de katılacağım', 'type' => 'text', 'created_at' => now()->subHours(2)->subMinutes(50)],
            ['group_id' => 1, 'sender_id' => 4, 'message' => 'Proje raporunu hazırladım, paylaşıyorum', 'type' => 'text', 'created_at' => now()->subHours(2)->subMinutes(45)],
            ['group_id' => 1, 'sender_id' => 1, 'message' => 'Harika! Teşekkürler Mehmet', 'type' => 'text', 'created_at' => now()->subHours(2)->subMinutes(40)],

            // Futbol Takımı (Group 2)
            ['group_id' => 2, 'sender_id' => 4, 'message' => 'Bugün maç var mı?', 'type' => 'text', 'created_at' => now()->subHours(5)],
            ['group_id' => 2, 'sender_id' => 6, 'message' => 'Evet, akşam 18:00\'da sahada buluşalım', 'type' => 'text', 'created_at' => now()->subHours(4)->subMinutes(55)],
            ['group_id' => 2, 'sender_id' => 8, 'message' => 'Ben de geliyorum!', 'type' => 'text', 'created_at' => now()->subHours(4)->subMinutes(50)],
            ['group_id' => 2, 'sender_id' => 10, 'message' => 'Topları ben getireyim mi?', 'type' => 'text', 'created_at' => now()->subHours(4)->subMinutes(45)],
            ['group_id' => 2, 'sender_id' => 6, 'message' => 'Olur, teşekkürler Burak', 'type' => 'text', 'created_at' => now()->subHours(4)->subMinutes(40)],

            // Ders Çalışma Grubu (Group 3)
            ['group_id' => 3, 'sender_id' => 8, 'message' => 'Yarın sınav var, beraber çalışalım mı?', 'type' => 'text', 'created_at' => now()->subHours(6)],
            ['group_id' => 3, 'sender_id' => 9, 'message' => 'Olur! Kütüphanede buluşalım', 'type' => 'text', 'created_at' => now()->subHours(5)->subMinutes(55)],
            ['group_id' => 3, 'sender_id' => 10, 'message' => 'Ben de geliyorum, saat kaçta?', 'type' => 'text', 'created_at' => now()->subHours(5)->subMinutes(50)],
            ['group_id' => 3, 'sender_id' => 5, 'message' => '15:00 uygun mu?', 'type' => 'text', 'created_at' => now()->subHours(5)->subMinutes(45)],
            ['group_id' => 3, 'sender_id' => 8, 'message' => 'Mükemmel, görüşürüz!', 'type' => 'text', 'created_at' => now()->subHours(5)->subMinutes(40)],

            // Aile (Group 4)
            ['group_id' => 4, 'sender_id' => 1, 'message' => 'Mahmut, akşam yemeğine gelecek misin?', 'type' => 'text', 'created_at' => now()->subHours(4)],
            ['group_id' => 4, 'sender_id' => 2, 'message' => 'Evet abi, saat kaçta?', 'type' => 'text', 'created_at' => now()->subHours(3)->subMinutes(55)],
            ['group_id' => 4, 'sender_id' => 1, 'message' => '19:00\'da evdeyiz', 'type' => 'text', 'created_at' => now()->subHours(3)->subMinutes(50)],
            ['group_id' => 4, 'sender_id' => 2, 'message' => 'Tamam, görüşürüz', 'type' => 'text', 'created_at' => now()->subHours(3)->subMinutes(45)],

            // Arkadaşlar (Group 5)
            ['group_id' => 5, 'sender_id' => 3, 'message' => 'Kızlar, hafta sonu ne yapıyoruz?', 'type' => 'text', 'created_at' => now()->subHours(7)],
            ['group_id' => 5, 'sender_id' => 5, 'message' => 'Alışverişe gidelim mi?', 'type' => 'text', 'created_at' => now()->subHours(6)->subMinutes(55)],
            ['group_id' => 5, 'sender_id' => 7, 'message' => 'Süper fikir! Ben de geliyorum', 'type' => 'text', 'created_at' => now()->subHours(6)->subMinutes(50)],
            ['group_id' => 5, 'sender_id' => 9, 'message' => 'Cumartesi uygun mu?', 'type' => 'text', 'created_at' => now()->subHours(6)->subMinutes(45)],
            ['group_id' => 5, 'sender_id' => 3, 'message' => 'Mükemmel! 14:00\'da AVM\'de buluşalım', 'type' => 'text', 'created_at' => now()->subHours(6)->subMinutes(40)],
        ];

        foreach ($messages as $message) {
            GroupMessage::create([
                'group_id' => $message['group_id'],
                'sender_id' => $message['sender_id'],
                'message' => $message['message'],
                'type' => $message['type'],
                'file_path' => null,
                'created_at' => $message['created_at'],
                'updated_at' => $message['created_at'],
            ]);
        }
    }
}
