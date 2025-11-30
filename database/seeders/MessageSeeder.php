<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = [
            // Iskender <-> Mahmut conversation
            ['sender_id' => 1, 'receiver_id' => 2, 'message' => 'Selam Mahmut, nasılsın?', 'type' => 'text', 'created_at' => now()->subHours(5)],
            ['sender_id' => 2, 'receiver_id' => 1, 'message' => 'İyiyim abi, sen nasılsın?', 'type' => 'text', 'created_at' => now()->subHours(4)->subMinutes(55)],
            ['sender_id' => 1, 'receiver_id' => 2, 'message' => 'Ben de iyiyim, bugün toplantı var mı?', 'type' => 'text', 'created_at' => now()->subHours(4)->subMinutes(50)],
            ['sender_id' => 2, 'receiver_id' => 1, 'message' => 'Evet saat 14:00\'da', 'type' => 'text', 'created_at' => now()->subHours(4)->subMinutes(45)],
            ['sender_id' => 1, 'receiver_id' => 2, 'message' => 'Tamam, hazırlanıyorum', 'type' => 'text', 'created_at' => now()->subHours(4)->subMinutes(40)],
            
            // Iskender <-> Ayşe conversation
            ['sender_id' => 1, 'receiver_id' => 3, 'message' => 'Merhaba Ayşe, proje nasıl gidiyor?', 'type' => 'text', 'created_at' => now()->subHours(3)],
            ['sender_id' => 3, 'receiver_id' => 1, 'message' => 'Merhaba! Çok iyi ilerliyor, yarın teslim edebilirim', 'type' => 'text', 'created_at' => now()->subHours(2)->subMinutes(55)],
            ['sender_id' => 1, 'receiver_id' => 3, 'message' => 'Harika! Ellerine sağlık', 'type' => 'text', 'created_at' => now()->subHours(2)->subMinutes(50)],
            
            // Mahmut <-> Ayşe conversation
            ['sender_id' => 2, 'receiver_id' => 3, 'message' => 'Ayşe, toplantıya katılacak mısın?', 'type' => 'text', 'created_at' => now()->subHours(2)],
            ['sender_id' => 3, 'receiver_id' => 2, 'message' => 'Evet, kesinlikle katılacağım', 'type' => 'text', 'created_at' => now()->subHours(1)->subMinutes(55)],
            ['sender_id' => 2, 'receiver_id' => 3, 'message' => 'Süper, görüşürüz o zaman', 'type' => 'text', 'created_at' => now()->subHours(1)->subMinutes(50)],
            
            // Mehmet <-> Ali conversation
            ['sender_id' => 4, 'receiver_id' => 6, 'message' => 'Ali, akşam futbol oynayacak mısın?', 'type' => 'text', 'created_at' => now()->subHours(6)],
            ['sender_id' => 6, 'receiver_id' => 4, 'message' => 'Tabii ki! Saat kaçta?', 'type' => 'text', 'created_at' => now()->subHours(5)->subMinutes(50)],
            ['sender_id' => 4, 'receiver_id' => 6, 'message' => '18:00\'da sahada buluşalım', 'type' => 'text', 'created_at' => now()->subHours(5)->subMinutes(45)],
            ['sender_id' => 6, 'receiver_id' => 4, 'message' => 'Tamam, görüşürüz!', 'type' => 'text', 'created_at' => now()->subHours(5)->subMinutes(40)],
            
            // Zeynep <-> Fatma conversation
            ['sender_id' => 5, 'receiver_id' => 7, 'message' => 'Fatma, yarın alışverişe gidelim mi?', 'type' => 'text', 'created_at' => now()->subHours(8)],
            ['sender_id' => 7, 'receiver_id' => 5, 'message' => 'Olur! Saat kaçta müsaitsin?', 'type' => 'text', 'created_at' => now()->subHours(7)->subMinutes(50)],
            ['sender_id' => 5, 'receiver_id' => 7, 'message' => 'Öğleden sonra 15:00 uygun mu?', 'type' => 'text', 'created_at' => now()->subHours(7)->subMinutes(45)],
            ['sender_id' => 7, 'receiver_id' => 5, 'message' => 'Mükemmel! AVM\'de buluşalım', 'type' => 'text', 'created_at' => now()->subHours(7)->subMinutes(40)],
            
            // Ahmet <-> Elif conversation
            ['sender_id' => 8, 'receiver_id' => 9, 'message' => 'Elif, ders notlarını paylaşabilir misin?', 'type' => 'text', 'created_at' => now()->subHours(4)],
            ['sender_id' => 9, 'receiver_id' => 8, 'message' => 'Tabii, hemen gönderiyorum', 'type' => 'text', 'created_at' => now()->subHours(3)->subMinutes(55)],
            ['sender_id' => 8, 'receiver_id' => 9, 'message' => 'Çok teşekkür ederim!', 'type' => 'text', 'created_at' => now()->subHours(3)->subMinutes(50)],
            
            // Elif <-> Burak conversation
            ['sender_id' => 9, 'receiver_id' => 10, 'message' => 'Burak, proje için ne zaman buluşacağız?', 'type' => 'text', 'created_at' => now()->subHours(2)],
            ['sender_id' => 10, 'receiver_id' => 9, 'message' => 'Yarın akşam uygun mu?', 'type' => 'text', 'created_at' => now()->subHours(1)->subMinutes(55)],
            ['sender_id' => 9, 'receiver_id' => 10, 'message' => 'Evet, 19:00\'da kütüphanede buluşalım', 'type' => 'text', 'created_at' => now()->subHours(1)->subMinutes(50)],
            ['sender_id' => 10, 'receiver_id' => 9, 'message' => 'Tamam, görüşürüz!', 'type' => 'text', 'created_at' => now()->subHours(1)->subMinutes(45)],
            
            // Recent messages
            ['sender_id' => 1, 'receiver_id' => 4, 'message' => 'Mehmet, raporu gönderebilir misin?', 'type' => 'text', 'created_at' => now()->subMinutes(30)],
            ['sender_id' => 4, 'receiver_id' => 1, 'message' => 'Hemen gönderiyorum', 'type' => 'text', 'created_at' => now()->subMinutes(25)],
            ['sender_id' => 2, 'receiver_id' => 5, 'message' => 'Zeynep, bugün müsait misin?', 'type' => 'text', 'created_at' => now()->subMinutes(20)],
            ['sender_id' => 5, 'receiver_id' => 2, 'message' => 'Evet, ne için?', 'type' => 'text', 'created_at' => now()->subMinutes(15)],
            ['sender_id' => 2, 'receiver_id' => 5, 'message' => 'Bir konuyu görüşmek istiyorum', 'type' => 'text', 'created_at' => now()->subMinutes(10)],
        ];

        foreach ($messages as $message) {
            DB::table('chat_messages')->insert([
                'sender_id' => $message['sender_id'],
                'receiver_id' => $message['receiver_id'],
                'message' => $message['message'],
                'type' => $message['type'],
                'created_at' => $message['created_at'],
                'updated_at' => $message['created_at'],
            ]);
        }
    }
}
