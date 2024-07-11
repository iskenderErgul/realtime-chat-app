<?php

namespace App\Http\Repositories;

use App\Http\Requests\Users\GetAllUsersRequest;
use App\Http\Requests\Users\GetUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers(): Collection
    {
        return User::all();
    }
    public function getUser($id): JsonResponse
    {
        $user = User::find($id);
        if ($user) {
            return response()->json($user);
        }
        return response()->json(['message' => 'Kullanıcı bulunamadı'], 404);
    }

    public function updateUser(UpdateUserRequest $request, $id)
    {
        return User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email
        ]);
    }

}
