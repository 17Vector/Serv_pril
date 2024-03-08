<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InfoController;

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

Route::group(['prefix' => '/info'], function () {

    Route::get('/server', [InfoController::class, 'Infoserver']);
    Route::get('/client', [InfoController::class, 'Infoclient']);
    Route::get('/database', [InfoController::class, 'Infodatabase']);

});