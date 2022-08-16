<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
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
});
