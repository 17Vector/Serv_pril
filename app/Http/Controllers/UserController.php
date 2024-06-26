<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\UserAndRole;
use App\DTO\UserCollectionDTO;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();

        try {
            $newUserDTO = $request -> getDTO();

            $user = new User([
                'username' => $newUserDTO->username,
                'email' => $newUserDTO->email,
                'password' => $newUserDTO->password,
                'birthday' => $newUserDTO->birthday,
            ]);
    
            $user -> save();
            DB::commit();

            $role_id = Role::where('name', 'User')->first()->id;
    
            $userAndRole = new UserAndRole([
                'user_id' => $user -> id,
                'role_id' => $role_id,
            ]);
            $userAndRole -> save();
    
            return response()->json(['Пользователь успешно создан' => $user], 201);
        }

        catch(\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Произошла ошибка при создании пользователя'], 500);
        }
    }

    public function updateUser($id, UpdateUserRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $updateUserDTO = $request -> getDTO();

            $user= User::find($id);

            checkUser($user);

            $user->username = $updateUserDTO->username;
            $user->email = $updateUserDTO->email;
            $user->password = $updateUserDTO->password;
            $user->birthday = $updateUserDTO->birthday;

            $user->save();

            DB::commit();

            return response()->json(['message' => 'Данные пользователя успешно изменены'], 200);
        }

        catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Произошла ошибка при изменении данных пользователя'], 500);
        }
    }

    public function hardDeleteUser($id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $user = User::find($id);

            checkUser($user);
            
            UserAndRole::where('user_id', $id)->forceDelete();

            $user->forceDelete();

            DB::commit();

            return response()->json(['message' => 'Пользователь удален (hard delete)'], 200);
        }

        catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при удалении пользователя (hard)'], 500);
        }
    }

    public function softDeleteUser($id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $user = User::find($id);

            checkUser($user);

            UserAndRole::where('user_id', $id)->delete();

            $user->delete();

            DB::commit();

            return response()->json(['message' => 'Пользователь удален (soft delete)'], 200);
        }

        catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при удалении пользователя (soft)'], 500);
        }        
    }

    public function restoreUser($id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $user = User::withTrashed()->find($id);

            if ($user && $user->trashed())
            {
                $user->restore();

                UserAndRole::withTrashed()->where('user_id', $id)->restore();

                DB::commit();

                return response()->json(['message' => 'Данные пользователя успешно восстановлены'], 200);
            }
            DB::commit();

            return response()->json(['message' => 'Данные пользователя не найдены или не были удалены'], 404);
        }

        catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при восстановлении данных пользователя'], 500);
        } 
    }
}

function checkUser($user) {
    if (!$user)
    {
        DB::commit();
        return response()->json(['error' => 'Пользователя не существует'], 404);
    }
}
