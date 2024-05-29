<?php

namespace App\Http\Controllers;
use App\Models\Permission;
use App\Models\RoleAndPermission;
use App\DTO\PermissionsCollectionDTO;
use App\Http\Requests\CreatePermissionsRequest;
use App\Http\Requests\UpdatePermissionsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    public function showListPermissions(): JsonResponse
    {
        $permissions = Permission::all();
        $permissionsCollection = new PermissionsCollectionDTO ($permissions);
        return response()->json($permissionsCollection);
    }

    public function specificPermission($id): JsonResponse
    {
        $permission = Permission::find($id);

        checkPermission($permission);

        return response()->json($permission, 200);
    }

    public function createPermission(CreatePermissionsRequest $request): JsonResponse
    {
        $newPermissionsDTO = $request -> getDTO();

        $permission = new Permission ([
            'name' => $newPermissionsDTO -> name,
            'description' => $newPermissionsDTO -> description,
        ]);
        $permission -> save();
        return response()->json(['Создание разрешения завершено успешно' => $permission], 201);
    }

    public function updatePermission($id, UpdatePermissionsRequest $request): JsonResponse
    {
        $updatePermissionDTO = $request -> getDTO();

        $permission= Permission::find($id);

        checkPermission($permission);

        $permission->name = $updatePermissionDTO->name;
        $permission->description = $updatePermissionDTO->description;

        $permission->save();

        return response()->json(['message' => 'Разрешение изменено успешно'], 200);
    }

    public function hardDeletePermission($id): JsonResponse
    {
        $permission = Permission::find($id);

        checkPermission($permission);
        
        RoleAndPermission::where('permission_id', $id)->forceDelete();

        $permission->forceDelete();

        return response()->json(['message' => 'Разрешение удалено (hard delete)'], 200);
    }

    public function softDeletePermission($id): JsonResponse
    {
        $permission = Permission::find($id);

        checkPermission($permission);

        RoleAndPermission::where('permission_id', $id)->delete();

        $permission->delete();

        return response()->json(['message' => 'Разрешение удалено (soft delete)'], 200);
    }

    public function restorePermission($id): JsonResponse
    {
        $permission = Permission::withTrashed()->find($id);

        if ($permission && $permission->trashed())
        {
            $permission->restore();

            RoleAndPermission::withTrashed()->where('permission_id', $id)->restore();

            return response()->json(['message' => 'Разрешение успешно восстановлено'], 200);
        }

        return response()->json(['message' => 'Разрешение не найдено или не было удалено'], 404);
    }
}

function checkPermission($permission) {
    if (!$permission)
    {
        return response()->json(['error' => 'Такого разрешения не существует'], 404);
    }
}