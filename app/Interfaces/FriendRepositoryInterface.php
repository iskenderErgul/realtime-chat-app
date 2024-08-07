<?php

namespace App\Interfaces;
use App\Http\Requests\Friend\CreateFriendRequest;
use Illuminate\Http\JsonResponse;
interface FriendRepositoryInterface
{
    public function getFriends(): JsonResponse;
    public function getMessagedFriends(): JsonResponse;
    public function createFriend(CreateFriendRequest $request): JsonResponse;

    public function deleteFriend($id): JsonResponse;
}
