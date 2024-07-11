<?php

namespace App\Http\Controllers;

use App\Http\Repositories\UserRepository;
use App\Http\Requests\Users\UpdateUserRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

    }

    public function getAllUsers(): Collection
    {
        return $this->userRepository->getAllUsers();

    }
    public function getUser( $id): JsonResponse
    {
        return $this->userRepository->getUser($id);

    }

    public function updateUser(UpdateUserRequest $request, $id): JsonResponse
    {
        $user = $this->userRepository->updateUser($request,$id);
        return response()->json($user);

    }

}
