<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('product')->group(function () {
            Route::get('/', [ProductController::class, 'getAll']);
            Route::post('store', [ProductController::class, 'store']);
            Route::get('find/{id}', [ProductController::class, 'find']);
            Route::put('update/{id}', [ProductController::class, 'update']);
            Route::delete('delete/{id}', [ProductController::class, 'delete']);
        });

        Route::post('logout', [AuthController::class, 'logout']);
    });
});
