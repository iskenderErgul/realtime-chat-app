<?php

namespace App\Http\Controllers;

use App\Events\GroupMessageSent;
use App\Models\Group;
use App\Models\GroupMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupMessageController extends Controller
{

    public function index(): JsonResponse
    {

        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $userId = Auth::id();

        $userGroups = Group::whereHas('members', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();


        $messages = collect();


        foreach ($userGroups as $group) {

            $groupMessages = $group->messages()->with('sender')->orderBy('created_at', 'asc')->get();
            $messages = $messages->merge($groupMessages);
        }

        $messages = $messages->sortBy('created_at');

        return response()->json($messages);
    }

    public function store(Request $request): JsonResponse
    {

        $message = GroupMessage::create([
            'group_id' => $request->group_id,
            'sender_id' => $request->sender_id,
            'message' => $request->message
        ]);

        broadcast(new GroupMessageSent($message))->toOthers();

        return response()->json($message);

    }
}
