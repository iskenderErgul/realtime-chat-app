<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $friends = $user->friends()->with('friend')->get();

        return response()->json($friends);
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        $friendId = $request->friend_id;

        $friend = Friend::create([
            'user_id' => $user->id,
            'friend_id' => $friendId,
        ]);

        return response()->json($friend, 201);
    }

    public function destroy($id): JsonResponse
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
