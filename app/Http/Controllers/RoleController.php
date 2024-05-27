<?php

namespace App\Http\Controllers;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function show(): JsonResponse
    {
        return response()->json([Role::all()->tokens->pluck('token')]);
    }

    public function index($id): JsonResponse
    {
        
    }

    public function createRole(CreateRoleRequest $request): JsonResponse
    {
        
    }

    public function updateRole(UpdateRoleRequest $request): JsonResponse
    {
        
    }

    public function deleteRole($id): JsonResponse
    {
        
    }

    public function softDelete($id): JsonResponse
    {
        
    }

    public function restoreRole($id): JsonResponse
    {
        
    }
}
