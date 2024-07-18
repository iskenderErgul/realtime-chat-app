<?php

namespace App\Http\Controllers;

use App\Http\Repositories\GroupRepository;
use App\Http\Requests\Group\AddGroupMemberRequest;
use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupMemberController extends Controller
{
    protected GroupRepository $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;

    }

    public function addMember(AddGroupMemberRequest $request, $id): JsonResponse
    {
        return $this->groupRepository->addMember($request, $id);

    }
    public function removeMember($groupId, $userId): JsonResponse
    {
        return  $this->groupRepository->removeMember($groupId,$userId);


    }
    public function assignAdmin($groupId, $userId): JsonResponse
    {

        return  $this->groupRepository->assignAdmin($groupId,$userId);


    }
    public function removeAdmin($groupId, $userId): JsonResponse
    {
        return $this->groupRepository->removeAdmin($groupId,$userId);


    }
    public function getMembers($groupId): JsonResponse
    {
        return  $this->groupRepository->getMembers($groupId);
    }
}
