<?php

use App\Http\Controllers\Api\V1\Auth\AuthenticationController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

Route::middleware('auth.jwt')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/refresh-token',[AuthenticationController::class, 'refreshToken']);
});
