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

    public function createGroup(CreateGroupRequest $request): Group
    {
        $group = Group::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        $this->addMemberToGroup($group->id, Auth::id(), true);

        if ($request->has('members')) {
            foreach ($request->input('members') as $memberId) {
                $this->addMemberToGroup($group->id, $memberId);
            }
        }

        return $group;
    }

    private function addMemberToGroup(int $groupId, int $userId, bool $isAdmin = false): void
    {
        GroupMember::create([
            'group_id' => $groupId,
            'user_id' => $userId,
            'is_admin' => $isAdmin,
        ]);
    }

    public function getGroup(int $id): Group
    {
        return Group::findOrFail($id);
    }

    public function updateGroup(UpdateGroupRequest $request, int $id): Group
    {
        $group = $this->getGroup($id);
        $group->update($request->all());
        return $group;
    }

    public function destroyGroup(int $id): JsonResponse
    {
        $group = Group::findOrFail($id);
        GroupMember::where('group_id', $id)->delete();
        $group->delete();
        return response()->json(['message' => 'Grup başarıyla silindi.']);
    }

    public function addMember(AddGroupMemberRequest $request, int $id): JsonResponse
    {
        $this->addMemberToGroup($id, $request->friend_id);
        return response()->json(['message' => 'Member added.']);
    }

    public function removeMember(int $groupId, int $userId): JsonResponse
    {
        $groupMember = GroupMember::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->firstOrFail();

        if ($groupMember->is_admin && $this->adminCount($groupId) <= 1) {
            return response()->json(['message' => 'Cannot remove the last admin.'], 403);
        }

        $groupMember->delete();
        return response()->json(['message' => $groupMember->is_admin ? 'Admin removed.' : 'Member removed.']);
    }

    private function adminCount(int $groupId): int
    {
        return GroupMember::where('group_id', $groupId)
            ->where('is_admin', true)
            ->count();
    }

    public function assignAdmin(int $groupId, int $userId): JsonResponse
    {
        if (!$this->isCurrentUserAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        GroupMember::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->update(['is_admin' => true]);

        return response()->json(['message' => 'Admin assigned.']);
    }

    public function removeAdmin(int $groupId, int $userId): JsonResponse
    {
        if ($this->isCurrentUser($userId)) {
            return response()->json(['message' => 'Kendinizi yönetici olarak kaldıramazsınız.'], 403);
        }

        if (!$this->isCurrentUserAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        GroupMember::where('group_id', $groupId)
            ->where('user_id', $userId)
            ->update(['is_admin' => false]);

        return response()->json(['message' => 'Admin rolü kaldırıldı.']);
    }

    private function isCurrentUserAdmin(): bool
    {
        return GroupMember::where('user_id', Auth::id())
            ->where('is_admin', true)
            ->exists();
    }

    private function isCurrentUser(int $userId): bool
    {
        return Auth::id() === $userId;
    }

    public function getMembers(int $groupId): JsonResponse
    {
        $members = Group::findOrFail($groupId)->members()->withPivot('is_admin')->get();
        return response()->json($members);
    }

    public function getUserGroupMessages(int $groupId): JsonResponse
    {
        if (!Auth::check() || !$this->isUserInGroup(Auth::id(), $groupId)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = Group::findOrFail($groupId)
            ->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    private function isUserInGroup(int $userId, int $groupId): bool
    {
        return Group::whereHas('members', function ($query) use ($userId, $groupId) {
            $query->where('user_id', $userId)
                ->where('group_id', $groupId);
        })->exists();
    }

    public function sendGroupMessage(SendGroupMessageRequest $request): JsonResponse
    {
        $message = GroupMessage::create([
            'group_id' => $request->group_id,
            'sender_id' => $request->sender_id,
            'message' => $request->message,
        ]);

        broadcast(new GroupMessageSent($message))->toOthers();

        return response()->json($message);
    }
}
