<?php

namespace App\Http\Controllers;

use App\Http\Repositories\FriendRepository;
use App\Http\Requests\Friend\CreateFriendRequest;
use App\Models\Friend;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{


    protected FriendRepository $friendRepository;


    public function __construct(FriendRepository $friendRepository)
    {
        $this->friendRepository=$friendRepository;
    }

    public function getFriends(): JsonResponse
    {
        return $this->friendRepository->getFriends();
    }

    public function createFriend(CreateFriendRequest $request): JsonResponse
    {
        return  $this->friendRepository->createFriend($request);
    }

    public function deleteFriend($id): JsonResponse
    {
        return $this->friendRepository->deleteFriend($id);
    }
}
