<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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
