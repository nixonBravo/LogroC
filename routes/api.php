<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogrocController;
use App\Http\Controllers\Personas;
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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::controller(LogrocController::class)->group(function () {
        Route::get('all', 'all');
        Route::get('cantones', 'cantones');
        Route::get('recintos', 'recintos');
        Route::put('updateRecinto/{id}', 'updateRecinto');
        Route::delete('parroquia/{id}', 'parroquia');
    });
});


Route::post('auth/register', [AuthController::class, 'createUser']);
Route::post('auth/login', [AuthController::class, 'loginUser']);


