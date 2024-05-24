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