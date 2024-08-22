<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/users', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('/users/login', [\App\Http\Controllers\UserController::class, 'login']);


Route::middleware(\App\Http\Middleware\ApiAuthMiddleware::class)->group(function () {
    Route::get('/users/current', [\App\Http\Controllers\UserController::class, 'get']);
    Route::patch('/users/current', [\App\Http\Controllers\UserController::class, 'update']);
    Route::delete('/users/logout', [\App\Http\Controllers\UserController::class, 'logout']);


    Route::post('/barangs', [\App\Http\Controllers\BarangController::class, 'create']);
    Route::get('/barangs/{id}', [\App\Http\Controllers\BarangController::class, 'get'])->where('id', '[0-9]+');
    Route::put('/barangs/{id}', [\App\Http\Controllers\BarangController::class, 'update'])->where('id', '[0-9]+');
    Route::delete('/barangs/{id}', [\App\Http\Controllers\BarangController::class, 'delete'])->where('id', '[0-9]+');


    Route::post('/barangs/{idBarang}/mutasis', [\App\Http\Controllers\MutasiController::class, 'create'])
        ->where('idBarang', '[0-9]+');
    Route::get('/barangs/{idBarang}/mutasis', [\App\Http\Controllers\MutasiController::class, 'list'])
        ->where('idBarang', '[0-9]+');
    Route::get('/barangs/{idBarang}/mutasis/{idMutasi}', [\App\Http\Controllers\MutasiController::class, 'get'])
        ->where('idBarang', '[0-9]+')
        ->where('idMutasi', '[0-9]+');
    Route::put('/barangs/{idBarang}/mutasis/{idMutasi}', [\App\Http\Controllers\MutasiController::class, 'update'])
        ->where('idBarang', '[0-9]+')
        ->where('idMutasi', '[0-9]+');
    Route::delete('/barangs/{idBarang}/mutasis/{idMutasi}', [\App\Http\Controllers\MutasiController::class, 'delete'])
        ->where('idBarang', '[0-9]+')
        ->where('idMutasi', '[0-9]+');
});

