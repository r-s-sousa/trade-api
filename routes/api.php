<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\TeamController;

Route::post('/login', [AuthController::class, 'login'])
    ->name('login');
Route::post('/register', [AuthController::class, 'register'])
    ->name('register');

Route::post('/refresh-token', [AuthController::class, 'refreshToken'])
    ->name('refreshToken')
    ->middleware('auth:sanctum');
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth:sanctum');

Route::group([
    'prefix' => 'v1',
    'namespace' => 'App\Http\Controllers\Api\V1',
    'middleware' => ['auth:sanctum']
], function () {
    Route::apiResource('teams', TeamController::class);
});
