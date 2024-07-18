<?php

namespace App\Interfaces;

use App\Http\Requests\Group\AddGroupMemberRequest;
use App\Http\Requests\Group\CreateGroupRequest;
use App\Http\Requests\Group\SendGroupMessageRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
use Illuminate\Http\JsonResponse;

interface GroupRepositoryInterface
{
    public function getAllGroupsWithMembers(): JsonResponse;

    public function createGroup(CreateGroupRequest $request);

    public function getGroup($id);

    public function updateGroup(UpdateGroupRequest $request, $id);

    public function destroyGroup();

    public function addMember(AddGroupMemberRequest $request, $id): JsonResponse;

    public function removeMember($groupId, $userId): JsonResponse;

    public function assignAdmin($groupId, $userId): JsonResponse;

    public function removeAdmin($groupId, $userId): JsonResponse;

    public function getMembers($groupId): JsonResponse;

    public function getUserGroupMessages(): JsonResponse;

    public function sendGroupMessage(SendGroupMessageRequest $request): JsonResponse;
}
