<?php

namespace App\Interfaces;

use App\Http\Requests\Users\UpdateUserRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

interface UserRepositoryInterface
{
    public function getAllUsers(): Collection;

    public function getUser($id): JsonResponse;

    public function updateUser(UpdateUserRequest $request, $id);

}
