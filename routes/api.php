<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthСontroller;
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