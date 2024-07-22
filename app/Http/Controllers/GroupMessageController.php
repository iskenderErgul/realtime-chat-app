<?php

namespace App\Http\Controllers;

use App\Events\GroupMessageSent;
use App\Http\Repositories\GroupRepository;
use App\Http\Requests\Group\SendGroupMessageRequest;
use App\Models\Group;
use App\Models\GroupMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupMessageController extends Controller
{

    protected GroupRepository $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;

    }

    public function getUserGroupMessages(): JsonResponse
    {
        return $this->groupRepository->getUserGroupMessages();
    }

    public function sendGroupMessage(SendGroupMessageRequest $request): JsonResponse
    {
       return $this->groupRepository->sendGroupMessage($request);
    }
}
