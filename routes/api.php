<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth_and_regist_Controller;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {

    Route::post('/login', [Auth_and_regist_Controller::class, 'login']);

    Route::middleware('guest:sanctum')->post('/register', [Auth_and_regist_Controller::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [Auth_and_regist_Controller::class, 'me']);
        Route::post('/out', [Auth_and_regist_Controller::class, 'out']);
        Route::get('/tokens', [Auth_and_regist_Controller::class, 'tokens']);
        Route::post('/out_all', [Auth_and_regist_Controller::class, 'out_all']);
    });
});

Route::prefix('ref/policy')->middleware('auth:sanctum')->group(function () {

    Route::prefix('role')->group(function () {
        Route::get('', [RoleController::class, 'show']);
        Route::get('/{id}', [RoleController::class, 'index']);
        Route::post('', [RoleController::class, 'createRole']);
        Route::put('/{id}', [RoleController::class, 'updateRole']);
        Route::delete('/{id}', [RoleController::class, 'deleteRole']);
        Route::delete('/{id}/soft', [RoleController::class, 'softDelete']);
        Route::post('/{id}/restore', [RoleController::class, 'restoreRole']);
    });

    Route::prefix('permissions')->group(function () {
        Route::get('', [PermissionsController::class, 'show']);
        Route::get('/{id}', [PermissionsController::class, 'index']);
        Route::post('', [PermissionsController::class, 'createPermissions']);
        Route::put('/{id}', [PermissionsController::class, 'updatePermissions']);
        Route::delete('/{id}', [PermissionsController::class, 'deletePermissions']);
        Route::delete('/{id}/soft', [PermissionsController::class, 'softDelete']);
        Route::post('/{id}/restore', [PermissionsController::class, 'restorePermissions']);
    });
    
    Route::prefix('users_and_roles')->group(function () {
        Route::get('', [UsersAndRolesController::class, 'show']);
        Route::get('/{id}', [UsersAndRolesController::class, 'index']);
        Route::post('', [UsersAndRolesController::class, 'create_Users_and_roles']);
        Route::put('/{id}', [UsersAndRolesController::class, 'update_Users_and_roles']);
        Route::delete('/{id}', [UsersAndRolesController::class, 'delete_Users_and_roles']);
        Route::delete('/{id}/soft', [UsersAndRolesController::class, 'softDelete']);
        Route::post('/{id}/restore', [UsersAndRolesController::class, 'restore_Users_and_roles']);
    });
    
    Route::prefix('roles_and_permissions')->group(function () {
        Route::get('', [PermissionsController::class, 'show']);
        Route::get('/{id}', [PermissionsController::class, 'index']);
        Route::post('', [PermissionsController::class, 'create_Roles_and_permissions']);
        Route::put('/{id}', [PermissionsController::class, 'update_Roles_and_permissions']);
        Route::delete('/{id}', [PermissionsController::class, 'delete_Roles_and_permissions']);
        Route::delete('/{id}/soft', [PermissionsController::class, 'softDelete']);
        Route::post('/{id}/restore', [PermissionsController::class, 'restore_Roles_and_permissions']);
    });
    
    Route::prefix('user')->group(function () {
        Route::get('', [UserController::class, 'show']);
        Route::get('/{id}', [UserController::class, 'index']);
        Route::post('', [UserController::class, 'createUser']);
        Route::put('/{id}', [UserController::class, 'updateUser']);
        Route::delete('/{id}', [UserController::class, 'deleteUser']);
        Route::delete('/{id}/soft', [UserController::class, 'softDelete']);
        Route::post('/{id}/restore', [UserController::class, 'restoreUser']);
    });
});