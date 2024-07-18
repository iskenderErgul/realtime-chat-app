<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function React\Promise\all;

class GroupController extends Controller
{
    public function index(): JsonResponse
    {

        $groups = Auth::user()->groups()->with('members')->get();
        return response()->json($groups);
    }

    public function store(Request $request): JsonResponse
    {
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

        return response()->json($group, 201);
    }
    public function show($id): JsonResponse
    {
        $group = Group::findOrFail($id);
        return response()->json($group);
    }
    public function update(Request $request, $id): JsonResponse
    {
        $group = Group::findOrFail($id);
        $group->update($request->all());
        return response()->json($group);
    }
    public function addMember(Request $request, $id): JsonResponse
    {
        $friend_id = $request->friend_id;
        $group_id = $id;

        GroupMember::create([
            'group_id' => $group_id,
            'user_id' => $friend_id,
        ]);

        return response()->json(['message' => 'Üye eklendi.']);
    }
    public function removeMember($groupId, $userId): JsonResponse
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
    public function assignAdmin($groupId, $userId): JsonResponse
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
    public function removeAdmin($groupId, $userId): JsonResponse
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
    public function members($groupId): JsonResponse
    {
        $group = Group::findOrFail($groupId);
        $members = $group->members()->withPivot('is_admin')->get();

        return response()->json($members);
    }
}
