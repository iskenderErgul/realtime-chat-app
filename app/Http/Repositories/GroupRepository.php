<?php

namespace App\Http\Repositories;

use App\Events\GroupMessageSent;
use App\Http\Requests\Group\AddGroupMemberRequest;
use App\Http\Requests\Group\CreateGroupRequest;
use App\Http\Requests\Group\SendGroupMessageRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
use App\Interfaces\GroupRepositoryInterface;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GroupRepository implements GroupRepositoryInterface
{
    public function getAllGroupsWithMembers(): JsonResponse
    {
        $groups = Auth::user()->groups()->with('members')->get();
        return response()->json($groups);
    }

    public function createGroup(CreateGroupRequest $request){
        $group = Group::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),

        ]);

        $group->members()->attach([
            Auth::id() => ['is_admin' => true, 'created_at' => now(), 'updated_at' => now()]
        ]);

        if ($request->has('members')) {
            foreach ($request->input('members') as $memberId) {

                GroupMember::create([
                    'group_id' => $group->id,
                    'user_id' => $memberId,
                    'is_admin' => false
                ]);
            }
        }

        return $group;
    }

    public  function getGroup($id) {
        return $group = Group::findOrFail($id);
    }

    public function updateGroup(UpdateGroupRequest $request, $id) {
        $group = Group::findOrFail($id);
        $group->update($request->all());
        return $group;
    }

    public function destroyGroup(){

    }

    public function addMember(AddGroupMemberRequest $request, $id): JsonResponse
    {
        $friend_id = $request->friend_id;
        $group_id = $id;

        GroupMember::create([
            'group_id' => $group_id,
            'user_id' => $friend_id,
        ]);

        return response()->json(['message' => 'Üye eklendi.']);
    }

    public function  removeMember($groupId, $userId): JsonResponse
    {

        $adminCount = GroupMember::where('group_id', $groupId)
            ->where('is_admin', true)
            ->count();

        $groupMember = GroupMember::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->firstOrFail();

        if ($groupMember->is_admin) {

            if ($adminCount > 1) {
                $groupMember->delete();
                return response()->json(['message' => 'Yönetici olarak gruptan çıktınız.']);
            } else {

                return response()->json(['message' => 'Bu grupta başka bir yönetici kalmadı. Yeni bir yönetici atayarak çıkabilirsiniz.'], 403);
            }
        }

        $groupMember->delete();
        return response()->json(['message' => 'Üye çıkarıldı.']);
    }

    public function assignAdmin($groupId,$userId): JsonResponse
    {
        $isAdmin = GroupMember::where('user_id',Auth::user()->id)
            ->where('is_admin',true)
            ->exists();

        if($isAdmin){
            GroupMember::where('group_id',$groupId)
                ->where('user_id',$userId)
                ->update([
                    'is_admin' => true
                ]);

            return response()->json(['message' => 'Yönetici atandı.'],200);

        }else{
            return response()->json(['message' => 'Yetkisiz erişim.'], 403);
        }
    }

    public function removeAdmin($groupId,$userId): JsonResponse
    {
        $authUser = Auth::user();


        if ($authUser->id != $userId) {
            $isAdmin = GroupMember::where('user_id', $authUser->id)
                ->where('is_admin', true)
                ->exists();

            if ($isAdmin) {
                GroupMember::where('group_id', $groupId)
                    ->where('user_id', $userId)
                    ->update([
                        'is_admin' => false
                    ]);

                return response()->json(['message' => 'Kullanıcı Yöneticilikten Çıkarıldı.'], 200);
            } else {
                return response()->json(['message' => 'Yetkisiz erişim.'], 403);
            }
        } else {
            return response()->json(['message' => 'Kendinizi yöneticilikten çıkaramazsınız.'], 403);
        }
    }

    public function getMembers($groupId): JsonResponse
    {
        $group = Group::findOrFail($groupId);
        $members = $group->members()->withPivot('is_admin')->get();

        return response()->json($members);
    }


    public function getUserGroupMessages($groupId): JsonResponse
    {

        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $userId = Auth::id();

        $group = Group::whereHas('members', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('id', $groupId)->first();

        if (!$group) {
            return response()->json(['error' => 'Group not found or user is not a member'], 404);
        }

        $groupMessages = $group->messages()->with('sender')->orderBy('created_at', 'asc')->get();

        return response()->json($groupMessages);
    }

    public function sendGroupMessage(SendGroupMessageRequest $request): JsonResponse
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
