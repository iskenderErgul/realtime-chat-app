<?php

namespace App\Http\Repositories;

use App\Http\Requests\Friend\CreateFriendRequest;
use App\Interfaces\FriendRepositoryInterface;
use App\Models\ChatMessage;
use App\Models\Friend;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FriendRepository  implements FriendRepositoryInterface
{
    public function getFriends(): JsonResponse
    {
        $user = Auth::user();
        $friends = $user->friends()->with('friend')->get();

        return response()->json($friends);
    }

    public function getMessagedFriends(): JsonResponse
    {
        $user = Auth::user();

        // Tüm arkadaşları al
        $allFriends = Friend::where('user_id', $user->id)
            ->with('friend')
            ->get();

        // Tüm kullanıcı ID'lerini al
        $friendIds = $allFriends->pluck('friend_id');

        // Mesajlaşma geçmişi olan arkadaşları al
        $messagedFriends = ChatMessage::where(function ($query) use ($user, $friendIds) {
            $query->whereIn('sender_id', $friendIds)
                ->orWhereIn('receiver_id', $friendIds);
        })->distinct('sender_id', 'receiver_id')->get(['sender_id', 'receiver_id']);

        $messagedFriendIds = $messagedFriends->pluck('sender_id')->merge($messagedFriends->pluck('receiver_id'))->unique();

        // Tüm arkadaşları ve mesaj geçmişi olanları belirle
        foreach ($allFriends as $friend) {
            $lastMessage = null;
            if ($messagedFriendIds->contains($friend->friend_id)) {
                $lastMessage = ChatMessage::where(function ($query) use ($user, $friend) {
                    $query->where('sender_id', $user->id)
                        ->where('receiver_id', $friend->friend_id);
                })->orWhere(function ($query) use ($user, $friend) {
                    $query->where('receiver_id', $user->id)
                        ->where('sender_id', $friend->friend_id);
                })->latest('created_at')->first();
            }

            $friend->last_message = $lastMessage ? $lastMessage->message : 'Henüz mesaj yok';
            $friend->last_message_sender_id = $lastMessage ? $lastMessage->sender_id : null;
        }

        return response()->json($allFriends);
    }

    public function createFriend(CreateFriendRequest $request): JsonResponse
    {
        $user = Auth::user();
        $friendId = $request->friend_id;

        $friend = Friend::create([
            'user_id' => $user->id,
            'friend_id' => $friendId,
        ]);

        return response()->json($friend, 201);
    }

    public function deleteFriend($id): JsonResponse
    {
        $user = Auth::user();
        $friend = Friend::where('user_id', $user->id)->where('friend_id', $id)->first();

        if ($friend) {
            $friend->delete();
            return response()->json(null, 204);
        }

        return response()->json(['error' => 'Friend not found'], 404);
    }
}
