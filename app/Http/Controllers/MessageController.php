<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function getMessages(Request $request)
    {

        $userId = $request->id;
        $messages = ChatMessage::query()
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver']) // İlişkili kullanıcı bilgilerini de alıyoruz
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($messages);
    }
}
