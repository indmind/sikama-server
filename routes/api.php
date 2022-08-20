<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CustomerOrderController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UserPositionController;
use App\Http\Controllers\Api\V1\VendorController;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => '/v1'], function () {
    Route::group(['prefix' => '/auth'], function () {
        Route::post('/google-sign-in', [AuthController::class, 'googleSignIn']);
        Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });

    Route::group(['prefix' => '/user', 'middleware' => 'auth:sanctum'], function () {
        Route::post('/position/update', [UserController::class, 'updatePosition']);
    });

    Route::group(['prefix' => '/positions', 'middleware' => 'auth:sanctum'], function () {
        Route::get('/{user}', [UserPositionController::class, 'getUserPosition']);
    });

    Route::group(['prefix' => '/vendors', 'middleware' => 'auth:sanctum'], function () {
        Route::get('/nearest', [VendorController::class, 'nearest']);
        Route::get('/{vendor}', [VendorController::class, 'show']);
    });

    Route::group(['prefix' => '/orders', 'middleware' => 'auth:sanctum'], function () {
        Route::get('/', [CustomerOrderController::class, 'index']);
        Route::post('/', [CustomerOrderController::class, 'order']);
        Route::post('/{order}/cancel', [CustomerOrderController::class, 'cancel']);
    });
});
