<?php

namespace App\Http\Controllers;
use App\Models\Permission;
use App\Models\RoleAndPermission;
use App\DTO\PermissionsCollectionDTO;
use App\Http\Requests\CreatePermissionsRequest;
use App\Http\Requests\UpdatePermissionsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();

        try {
            $newPermissionsDTO = $request -> getDTO();

            $permission = new Permission ([
                'name' => $newPermissionsDTO -> name,
                'description' => $newPermissionsDTO -> description,
            ]);
            $permission -> save();

            DB::commit();

            return response()->json(['Создание разрешения завершено успешно' => $permission], 201);
        }

        catch(\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при создании разрешения'], 500);
        }
        
    }

    public function updatePermission($id, UpdatePermissionsRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $updatePermissionDTO = $request -> getDTO();

            $permission= Permission::find($id);

            checkPermission($permission);

            $permission->name = $updatePermissionDTO->name;
            $permission->description = $updatePermissionDTO->description;

            $permission->save();

            DB::commit();

            return response()->json(['message' => 'Разрешение изменено успешно'], 200);
        }

        catch(\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при обновлении разрешения'], 500);
        }
    }

    public function hardDeletePermission($id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $permission = Permission::find($id);

            checkPermission($permission);
            
            RoleAndPermission::where('permission_id', $id)->forceDelete();

            $permission->forceDelete();

            DB::commit();

            return response()->json(['message' => 'Разрешение удалено (hard delete)'], 200);
        }

        catch(\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при удалении разрешения (hard)'], 500);
        }
    }

    public function softDeletePermission($id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $permission = Permission::find($id);

            checkPermission($permission);

            RoleAndPermission::where('permission_id', $id)->delete();

            $permission->delete();

            DB::commit();

            return response()->json(['message' => 'Разрешение удалено (soft delete)'], 200);
        }

        catch(\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при удалении разрешения (hard)'], 500);
        }
    }

    public function restorePermission($id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $permission = Permission::withTrashed()->find($id);

            if ($permission && $permission->trashed())
            {
                $permission->restore();
    
                RoleAndPermission::withTrashed()->where('permission_id', $id)->restore();
                
                DB::commit();

                return response()->json(['message' => 'Разрешение успешно восстановлено'], 200);
            }
            DB::commit();
            return response()->json(['message' => 'Разрешение не найдено или не было удалено'], 404);
        }

        catch(\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при удалении разрешения (hard)'], 500);
        }
    }
}

function checkPermission($permission) {
    if (!$permission)
    {
        return response()->json(['error' => 'Такого разрешения не существует'], 404);
    }
}