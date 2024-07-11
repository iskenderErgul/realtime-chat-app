<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function getMessages(Request $request): JsonResponse
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

    public function sendMessage(Request $request): JsonResponse
    {

        $message = ChatMessage::create([
            "sender_id" => $request->sender_id,
            "receiver_id" => $request->receiver_id,
            "message" => $request->message
        ]);

        return response()->json($message);
    }

    public function clearChat(Request $request): JsonResponse
    {
        $senderId = $request->input('sender_id');
        $receiverId = $request->input('receiver_id');

        ChatMessage::where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->orWhere(function ($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $receiverId)
                    ->where('receiver_id', $senderId);
            })
            ->delete();

        return response()->json(['message' => 'Chat cleared successfully']);
    }

}
