<?php

namespace App\Http\Controllers;
use App\Models\RoleAndPermission;
use App\Models\Role;
use App\Models\UserAndRole;
use App\DTO\RoleCollectionDTO;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function showListRoles(): JsonResponse
    {
        $roles = Role::all();
        $roleCollection = new RoleCollectionDTO ($roles);
        return response()->json($roleCollection);
    }

    public function specificRole($id): JsonResponse
    {
        $role = Role::find($id);

        checkRole($role);

        return response()->json($role, 200);
    }

    public function createRole(CreateRoleRequest $request): JsonResponse
    {
        $newRoleDTO = $request -> getDTO();

        $role = new Role ([
            'name' => $newRoleDTO -> name,
            'description' => $newRoleDTO -> description,
        ]);
        $role -> save();
        return response()->json(['Создание роли завершено успешно' => $role], 201);
    }

    public function updateRole($id, UpdateRoleRequest $request): JsonResponse
    {
        $updateRoleDTO = $request -> getDTO();

        $role = Role::find($id);

        checkRole($role);

        $role->name = $updateRoleDTO->name;
        $role->description = $updateRoleDTO->description;

        $role->save();

        return response()->json(['message' => 'Роль изменена успешно'], 200);
    }

    public function hardDeleteRole($id): JsonResponse
    {
        $role = Role::find($id);

        checkRole($role);
        
        RoleAndPermission::where('role_id', $id)->forceDelete();
        UserAndRole::where('role_id', $id)->forceDelete();

        $role->forceDelete();

        return response()->json(['message' => 'Роль удалена (hard delete)'], 200);
    }

    public function softDeleteRole($id): JsonResponse
    {
        $role = Role::find($id);

        checkRole($role);

        RoleAndPermission::where('role_id', $id)->delete();
        UserAndRole::where('role_id', $id)->delete();
        $role->delete();

        return response()->json(['message' => 'Роль удалена (soft delete)'], 200);
    }

    public function restoreRole($id): JsonResponse
    {
        $role = Role::withTrashed()->find($id);

        if ($role && $role->trashed())
        {
            $role->restore();

            RoleAndPermission::withTrashed()->where('role_id', $id)->restore();
            UserAndRole::withTrashed()->where('role_id', $id)->restore();

            return response()->json(['message' => 'Роль успешно восстановлена'], 200);
        }

        return response()->json(['message' => 'Роль не найдена или не была удалена'], 404);
    }
}

function checkRole($role) {
    if (!$role)
    {
        return response()->json(['error' => 'Такой роли не существует'], 404);
    }
}
