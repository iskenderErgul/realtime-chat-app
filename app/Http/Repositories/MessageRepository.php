<?php

namespace App\Http\Repositories;
use App\Http\Requests\Messages\ClearMessagesRequest;
use App\Http\Requests\Messages\GetMessagesRequest;
use App\Http\Requests\Messages\SendMessagesRequest;
use App\Interfaces\MessageRepositoryInterface;
use App\Models\ChatMessage;
use Illuminate\Database\Eloquent\Collection;


class MessageRepository implements MessageRepositoryInterface
{

    public function getMessages(GetMessagesRequest $request): Collection|array
    {
        $userId = $request->id;
        return ChatMessage::query()
            ->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->orderBy('id', 'asc')
            ->get();
    }

    public function sendMessage(SendMessagesRequest $request)
    {
        return ChatMessage::create([
            "sender_id" => $request->sender_id,
            "receiver_id" => $request->receiver_id,
            "message" => $request->message
        ]);
    }

    public function clearChat(ClearMessagesRequest $request): string
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

        return  'Chat cleared successfully';
    }
}
