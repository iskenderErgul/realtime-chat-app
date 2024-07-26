<?php

namespace App\Interfaces;

use App\Http\Requests\Group\AddGroupMemberRequest;
use App\Http\Requests\Group\CreateGroupRequest;
use App\Http\Requests\Group\SendGroupMessageRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
use App\Models\Group;
use Illuminate\Http\JsonResponse;

interface GroupRepositoryInterface
{
    /**
     * Retrieve all groups with their members.
     *
     * @return JsonResponse
     */
    public function getAllGroupsWithMembers(): JsonResponse;

    /**
     * Create a new group.
     *
     * @param CreateGroupRequest $request
     * @return Group
     */
    public function createGroup(CreateGroupRequest $request): Group;

    /**
     * Retrieve a group by its ID.
     *
     * @param int $id
     * @return Group
     */
    public function getGroup(int $id): Group;

    /**
     * Update a group's information.
     *
     * @param UpdateGroupRequest $request
     * @param int $id
     * @return Group
     */
    public function updateGroup(UpdateGroupRequest $request, int $id): Group;

    /**
     * Delete a group by its ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroyGroup(int $id): JsonResponse;

    /**
     * Add a member to a group.
     *
     * @param AddGroupMemberRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function addMember(AddGroupMemberRequest $request, int $id): JsonResponse;

    /**
     * Remove a member from a group.
     *
     * @param int $groupId
     * @param int $userId
     * @return JsonResponse
     */
    public function removeMember(int $groupId, int $userId): JsonResponse;

    /**
     * Assign admin role to a user in a group.
     *
     * @param int $groupId
     * @param int $userId
     * @return JsonResponse
     */
    public function assignAdmin(int $groupId, int $userId): JsonResponse;

    /**
     * Remove admin role from a user in a group.
     *
     * @param int $groupId
     * @param int $userId
     * @return JsonResponse
     */
    public function removeAdmin(int $groupId, int $userId): JsonResponse;

    /**
     * Retrieve members of a group.
     *
     * @param int $groupId
     * @return JsonResponse
     */
    public function getMembers(int $groupId): JsonResponse;

    /**
     * Retrieve messages of a group.
     *
     * @param int $groupId
     * @return JsonResponse
     */
    public function getUserGroupMessages(int $groupId): JsonResponse;

    /**
     * Send a message in a group.
     *
     * @param SendGroupMessageRequest $request
     * @return JsonResponse
     */
    public function sendGroupMessage(SendGroupMessageRequest $request): JsonResponse;
}
