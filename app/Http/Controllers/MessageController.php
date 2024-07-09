<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function getMessages(Request $request)
    {



        $friendId = $request->friendId;
        $currentUser = $request->input('currentUser');
        $userId = $currentUser['id'];

        $messages = ChatMessage::query()
            ->where(function ($query) use ($userId, $friendId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', $friendId);
            })
            ->orWhere(function ($query) use ($userId, $friendId) {
                $query->where('sender_id', $friendId)
                    ->where('receiver_id', $userId);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('id', 'asc')
            ->get();



        return response()->json($messages);
    }
}
