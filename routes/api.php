<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthСontroller;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\RolesAndPermissionsController;
use App\Http\Controllers\UsersAndRolesController;
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

    Route::post('/login', [AuthСontroller::class, 'login']);

    Route::middleware('guest:sanctum')->post('/register', [AuthСontroller::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthСontroller::class, 'me']);
        Route::post('/out', [AuthСontroller::class, 'out']);
        Route::get('/tokens', [AuthСontroller::class, 'tokens']);
        Route::post('/out_all', [AuthСontroller::class, 'out_all']);
    });
});

Route::prefix('ref/policy')->middleware('auth:sanctum')->group(function () {

    Route::prefix('role')->group(function () {
        Route::get('', [RoleController::class, 'showListRoles'])->middleware('check.permission:role-get-list');
        Route::post('', [RoleController::class, 'createRole'])->middleware('check.permission:role-create');

        Route::get('/list', [RolesAndPermissionsController::class, 'showListRolesAndPermissions'])->middleware('check.permission:role_and_permission-get-list');

        Route::prefix('{id}')->group(function () {
            Route::get('', [RoleController::class, 'specificRole'])->middleware('check.permission:role-read');
            Route::put('', [RoleController::class, 'updateRole'])->middleware('check.permission:role-update');
            Route::delete('', [RoleController::class, 'hardDeleteRole'])->middleware('check.permission:role-delete');
            Route::delete('/soft', [RoleController::class, 'softDeleteRole'])->middleware('check.permission:role-delete');
            Route::post('/restore', [RoleController::class, 'restoreRole'])->middleware('check.permission:role-restore');
        });

        Route::prefix('{role_id}/permission')->group(function () {
            Route::get('', [RolesAndPermissionsController::class, 'specificRoleAndPermission'])->middleware('check.permission:role_and_permission-read');
            Route::post('', [RolesAndPermissionsController::class, 'createRoleAndPermission'])->middleware('check.permission:role_and_permission-create');

            Route::prefix('{permission_id}')->group(function () {
                Route::delete('', [RolesAndPermissionsController::class, 'hardDeleteRoleAndPermission'])->middleware('check.permission:role_and_permission-delete');
                Route::delete('/soft', [RolesAndPermissionsController::class, 'softDeleteRoleAndPermission'])->middleware('check.permission:role_and_permission-delete');
                Route::post('/restore', [RolesAndPermissionsController::class, 'restoreRoleAndPermission'])->middleware('check.permission:role_and_permission-restore');
            });
        });
    });

    Route::prefix('permission')->group(function () {
        Route::get('', [PermissionsController::class, 'showListPermissions'])->middleware('check.permission:permission-get-list');
        Route::post('', [PermissionsController::class, 'createPermission'])->middleware('check.permission:permission-create');

        Route::prefix('{id}')->group(function () {
            Route::get('', [PermissionsController::class, 'specificPermission'])->middleware('check.permission:permission-read');
            Route::put('', [PermissionsController::class, 'updatePermission'])->middleware('check.permission:permission-update');
            Route::delete('', [PermissionsController::class, 'hardDeletePermission'])->middleware('check.permission:permission-delete');
            Route::delete('/soft', [PermissionsController::class, 'softDeletePermission'])->middleware('check.permission:permission-delete');
            Route::post('/restore', [PermissionsController::class, 'restorePermission'])->middleware('check.permission:permission-restore');
        });
    });

    Route::prefix('user')->group(function () {
        Route::get('', [UserController::class, 'showListUsers'])->middleware('check.permission:user-get-list');
        Route::post('', [UserController::class, 'createUser'])->middleware('check.permission:user-create');

        Route::get('/list', [UsersAndRolesController::class, 'showListUsersAndRoles'])->middleware('check.permission:user_and_role-get-list');

        Route::prefix('{id}')->group(function () { 
            Route::get('', [UserController::class, 'specificUser'])->middleware('check.permission:user-read');
            Route::put('', [UserController::class, 'updateUser'])->middleware('check.permission:user-update');
            Route::delete('', [UserController::class, 'hardDeleteUser'])->middleware('check.permission:user-delete');
            Route::delete('/soft', [UserController::class, 'softDeleteUser'])->middleware('check.permission:user-delete');
            Route::post('/restore', [UserController::class, 'restoreUser'])->middleware('check.permission:user-restore');
        });

        Route::prefix('{user_id}/role')->group(function () { 
            Route::get('', [UsersAndRolesController::class, 'specificUserAndRole'])->middleware('check.permission:user_and_role-read');
            Route::post('', [UsersAndRolesController::class, 'createUserAndRole'])->middleware('check.permission:user_and_role-create');

            Route::prefix('{role_id}')->group(function () {
                Route::delete('', [UsersAndRolesController::class, 'hardDeleteUserAndRole'])->middleware('check.permission:user_and_role-delete');
                Route::delete('/soft', [UsersAndRolesController::class, 'softDeleteUserAndRole'])->middleware('check.permission:user_and_role-delete');
                Route::post('/restore', [UsersAndRolesController::class, 'restoreUserAndRole'])->middleware('check.permission:user_and_role-restore');
            });
        });
    });
});