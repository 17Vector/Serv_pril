<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserAndRole;
use App\DTO\UsersAndRolesCollectionDTO;
use App\Http\Requests\CreateUserAndRoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersAndRolesController extends Controller
{
    public function showListUsersAndRoles(): JsonResponse
    {
        $usersAndRoles = UserAndRole::all();
        return response()->json($usersAndRoles);
    }

    public function specificUserAndRole($user_id): JsonResponse
    {
        $user = User::with('roles')->find($user_id);

        if (!$user)
        {
            return response()->json(['error' => 'Указанный пользователь не найден'], 404);
        }

        $userRolesCollectionDTO = new UsersAndRolesCollectionDTO($user);
        return response()->json($userRolesCollectionDTO);
    }

    public function createUserAndRole($user_id, CreateUserAndRoleRequest $request): JsonResponse
    {
        $newUserAndRoleDTO = $request->getDTO();

        $userAndRole = UserAndRole::where('user_id', $user_id)->where('role_id', $newUserAndRoleDTO -> role_id)->first();

        if($userAndRole) {
            return response()->json(['error' => 'Роль уже обладает указанным разрешением'], 404);
        }

        $user = User::find($user_id);
        if (!$user) {
            return response()->json(['error' => 'Указанный пользователь не найден'], 404);
        }
    
        $role = Role::find($newUserAndRoleDTO->role_id);
        if (!$role) {
            return response()->json(['error' => 'Указанная роль не найдена'], 404);
        }
    
        $userAndRole = new UserAndRole([
            'user_id' => intval($user_id),
            'role_id' => $newUserAndRoleDTO -> role_id,
        ]);
    
        $userAndRole->save();
    
        return response()->json(['message' => 'Роль успешно выдана указанному пользователю'], 201);
    }

    public function hardDeleteUserAndRole($user_id, $role_id): JsonResponse
    {
        $userAndRole = UserAndRole::where('user_id', $user_id)->where('role_id', $role_id)->first();
        if (!$userAndRole)
        {
            return response()->json(['error' => 'Запись с указанными данными не найдена'], 404);
        }
        $userAndRole->forceDelete();
        return response()->json(['message' => 'Роль у пользователя успешно удалена (hard delete)'], 200);
    }

    public function softDeleteUserAndRole($user_id, $role_id): JsonResponse
    {
        $userAndRole = UserAndRole::where('user_id', $user_id)->where('role_id', $role_id)->first();
        if (!$userAndRole)
        {
            return response()->json(['error' => 'Запись с указанными данными не найдена'], 404);
        }

        $userAndRole->delete();

        return response()->json(['message' => 'Роль у пользователя успешно удалена (soft delete)'], 200);
    }

    public function restoreUserAndRole($user_id, $role_id): JsonResponse
    {
        $userAndRole = UserAndRole::withTrashed()->where('user_id', $user_id)->where('role_id', $role_id)->first();

        if ($userAndRole && $userAndRole->trashed())
        {
            $userAndRole->restore();

            return response()->json(['message' => 'Роль пользователя успешно восстановлена'], 200);
        }
        return response()->json(['message' => 'Разрешение не найдено или не было удалено'], 404);
    }
}
