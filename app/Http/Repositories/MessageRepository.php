<?php

namespace App\Http\Repositories;
use App\Events\MessageSent;
use App\Helpers\FileUploadHelper;
use App\Helpers\MessageTypeHelper;
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

        $messageData = [
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
        ];

        if ($request->filled('message') || $request->hasFile('file')) {
            $messageData['message'] = $request->message;
            if ($request->hasFile('file')) {
                $filePath = FileUploadHelper::upload($request->file('file'));
                $messageData['file_path'] = $filePath;
            }
            $messageData['type'] = MessageTypeHelper::determineMessageType($request->message, $request->file('file'));
        }

        $message = ChatMessage::create($messageData);

        broadcast(new MessageSent($message))->toOthers();

        return $message;
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
