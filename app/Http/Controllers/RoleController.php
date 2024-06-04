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
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();

        try {
            $newRoleDTO = $request -> getDTO();

            $role = new Role ([
                'name' => $newRoleDTO -> name,
                'description' => $newRoleDTO -> description,
            ]);
            $role -> save();

            DB::commit();

            return response()->json(['Создание роли завершено успешно' => $role], 201);
        }

        catch(\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при создании роли'], 500);
        }
    }

    public function updateRole($id, UpdateRoleRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $updateRoleDTO = $request -> getDTO();

            $role = Role::find($id);

            checkRole($role);

            $role->name = $updateRoleDTO->name;
            $role->description = $updateRoleDTO->description;

            $role->save();

            DB::commit();

            return response()->json(['message' => 'Роль изменена успешно'], 200);
        }

        catch(\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при обновлении роли'], 500);
        }
    }

    public function hardDeleteRole($id): JsonResponse
    {   
        DB::beginTransaction();

        try {
            $role = Role::find($id);

            checkRole($role);
            
            RoleAndPermission::where('role_id', $id)->forceDelete();
            UserAndRole::where('role_id', $id)->forceDelete();

            $role->forceDelete();

            DB::commit();

            return response()->json(['message' => 'Роль удалена (hard delete)'], 200);
        }

        catch(\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при удалении роли (hard)'], 500);
        }
    }

    public function softDeleteRole($id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $role = Role::find($id);

            checkRole($role);

            RoleAndPermission::where('role_id', $id)->delete();
            UserAndRole::where('role_id', $id)->delete();
            $role->delete();

            DB::commit();

            return response()->json(['message' => 'Роль удалена (soft delete)'], 200);
        }

        catch(\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при удалении роли (soft)'], 500);
        }
    }

    public function restoreRole($id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $role = Role::withTrashed()->find($id);

            if ($role && $role->trashed())
            {
                $role->restore();

                RoleAndPermission::withTrashed()->where('role_id', $id)->restore();
                UserAndRole::withTrashed()->where('role_id', $id)->restore();

                DB::commit();

                return response()->json(['message' => 'Роль успешно восстановлена'], 200);
            }
            DB::commit();
            return response()->json(['message' => 'Роль не найдена или не была удалена'], 404);
        }

        catch(\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при восстановлении роли'], 500);
        }
    }
}

function checkRole($role) {
    if (!$role)
    {
        return response()->json(['error' => 'Такой роли не существует'], 404);
    }
}
