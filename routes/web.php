<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api/auth'], function () {

    Route::post('/login', [Controller::class, 'Infoserver']);
    Route::get('/me', [Controller::class, 'Infodatabase']);
    Route::post('/out', [Controller::class, 'Infoserver']);
    Route::get('/tokens', [Controller::class, 'Infoclient']);
    Route::post('/out_all', [Controller::class, 'Infodatabase']);

    Route::post('/register', [Controller::class, 'Infoclient']);
});
