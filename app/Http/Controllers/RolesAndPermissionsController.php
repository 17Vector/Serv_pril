<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\RoleAndPermission;
use App\DTO\RolesAndPermissionsCollectionDTO;
use App\Http\Requests\CreateRolesAndPermissionsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RolesAndPermissionsController extends Controller
{
    public function showListRolesAndPermissions(): JsonResponse
    {
        $rolesAndPermissions = RoleAndPermission::all();
        return response()->json($rolesAndPermissions);
    }

    public function specificRoleAndPermission($role_id): JsonResponse
    {
        $role = Role::with(['permissions' => function ($query) {
            $query->whereNull('role_and_permissions.deleted_at');
        }])->find($role_id);

        if (!$role)
        {
            return response()->json(['error' => 'Указанная роль не найдена'], 404);
        }

        $rolePermissionsCollectionDTO = new RolesAndPermissionsCollectionDTO($role);
        return response()->json($rolePermissionsCollectionDTO);
    }

    public function createRoleAndPermission($role_id, CreateRolesAndPermissionsRequest $request): JsonResponse
    {
        $newRoleAndPermissionDTO = $request->getDTO();

        $roleAndPermission = RoleAndPermission::where('role_id', $role_id)->where('permission_id', $newRoleAndPermissionDTO -> permission_id)->first();

        if($roleAndPermission) {
            return response()->json(['error' => 'Роль уже обладает указанным разрешением'], 404);
        }

        $role = Role::find($role_id);
        if (!$role) {
            return response()->json(['error' => 'Указанная роль не найдена'], 404);
        }
    
        $permission = Permission::find($newRoleAndPermissionDTO->permission_id);
        if (!$permission) {
            return response()->json(['error' => 'Указанное разрешение не найдено'], 404);
        }
    
        $roleAndPermission = new RoleAndPermission([
            'role_id' => intval($role_id),
            'permission_id' => $permission -> id,
        ]);
    
        $roleAndPermission->save();
    
        return response()->json(['message' => 'Разрешение успешно выдано указанной роли'], 201);
    }

    public function hardDeleteRoleAndPermission($role_id, $permission_id): JsonResponse
    {
        $roleAndPermission = RoleAndPermission::where('role_id', $role_id)->where('permission_id', $permission_id)->first();
        if (!$roleAndPermission)
        {
            return response()->json(['error' => 'Запись с указанными данными не найдена'], 404);
        }
        $roleAndPermission->forceDelete();
        return response()->json(['message' => 'Разрешение у роли успешно удалено (hard delete)'], 200);
    }

    public function softDeleteRoleAndPermission($role_id, $permission_id): JsonResponse
    {
        $roleAndPermission = RoleAndPermission::where('role_id', $role_id)->where('permission_id', $permission_id)->first();
        if (!$roleAndPermission)
        {
            return response()->json(['error' => 'Запись с указанными данными не найдена'], 404);
        }

        $roleAndPermission->delete();

        return response()->json(['message' => 'Разрешение удалено (soft delete)'], 200);
    }

    public function restoreRoleAndPermission($role_id, $permission_id): JsonResponse
    {
        $roleAndPermission = RoleAndPermission::withTrashed()->where('role_id', $role_id)->where('permission_id', $permission_id)->first();

        if ($roleAndPermission && $roleAndPermission->trashed())
        {
            $roleAndPermission->restore();

            return response()->json(['message' => 'Разрешение роли успешно восстановлено'], 200);
        }
        return response()->json(['message' => 'Разрешение не найдено или не было удалено'], 404);
    }
}
