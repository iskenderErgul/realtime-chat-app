<?php

namespace App\Http\Controllers;

use App\Http\Repositories\GroupRepository;
use App\Http\Requests\Group\CreateGroupRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class GroupController extends Controller
{

    protected GroupRepository $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;

    }

    public function getAllGroupsWithMembers(): JsonResponse
    {
        return $this->groupRepository->getAllGroupsWithMembers();

    }
    public function createGroup(CreateGroupRequest $request): JsonResponse
    {
        $group = $this->groupRepository->createGroup($request);
        return response()->json($group, 201);
    }
    public function getGroup($id): JsonResponse
    {
        $group = $this->groupRepository->getGroup($id);
        return response()->json($group);
    }
    public function updateGroup(UpdateGroupRequest $request, $id): JsonResponse
    {
        $group = $this->groupRepository->updateGroup($request,$id);
        return response()->json($group);
    }
    public function destroyGroup($id): void
    {
            $this->groupRepository->destroyGroup($id);
    }



}
