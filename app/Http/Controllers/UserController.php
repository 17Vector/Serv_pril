<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAndRole;
use App\DTO\UserCollectionDTO;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showListUsers(): JsonResponse
    {
        $users = User::all();
        $usersCollection = new UserCollectionDTO ($users);
        return response()->json($usersCollection);
    }

    public function specificUser($id): JsonResponse
    {
        $user = User::find($id);

        checkUser($user);

        return response()->json($user, 200);
    }

    public function createUser(CreateUserRequest $request): JsonResponse
    {
        $newUserDTO = $request -> getDTO();

        $user = new User([
            'username' => $newUserDTO->username,
            'email' => $newUserDTO->email,
            'password' => $newUserDTO->password,
            'birthday' => $newUserDTO->birthday,
        ]);

        $user -> save();
        return response()->json(['Пользователь успешно создан' => $user], 201);
    }

    public function updateUser($id, UpdateUserRequest $request): JsonResponse
    {
        $updateUserDTO = $request -> getDTO();

        $user= User::find($id);

        checkUser($user);

        $user->username = $updateUserDTO->username;
        $user->email = $updateUserDTO->email;
        $user->password = $updateUserDTO->password;
        $user->birthday = $updateUserDTO->birthday;

        $user->save();

        return response()->json(['message' => 'Данные пользователя успешно изменены'], 200);
    }

    public function hardDeleteUser($id): JsonResponse
    {
        $user = User::find($id);

        checkUser($user);
        
        //UserAndRole::where('user_id', $id)->forceDelete();

        $user->forceDelete();

        return response()->json(['message' => 'Пользователь удален (hard delete)'], 200);
    }

    public function softDeleteUser($id): JsonResponse
    {
        $user = User::find($id);

        checkUser($user);

       // UserAndRole::where('user_id', $id)->delete();

        $user->delete();

        return response()->json(['message' => 'Пользователь удален (soft delete)'], 200);
    }

    public function restoreUser($id): JsonResponse
    {
        $user = User::withTrashed()->find($id);

        if ($user && $user->trashed())
        {
            $user->restore();

            //UserAndRole::withTrashed()->where('user_id', $id)->restore();

            return response()->json(['message' => 'Данные пользователя успешно восстановлены'], 200);
        }

        return response()->json(['message' => 'Данные пользователя не найдены или не были удалены'], 404);
    }
}

function checkUser($user) {
    if (!$user)
    {
        return response()->json(['error' => 'Пользователя не существует'], 404);
    }
}
