<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\Auth\CanteenOwnerController;   
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);

    Route::middleware('canteen')->group(function () {
        Route::apiResource('menu', MenuController::class);
    });

    Route::middleware('admin')->group(function () {
        Route::get('canteen-owner', [CanteenOwnerController::class, 'index']);
        Route::post('canteen-owner', [CanteenOwnerController::class, 'store']);
        Route::put('canteen-owner/{user}', [CanteenOwnerController::class, 'update']);
        Route::delete('canteen-owner/{user}', [CanteenOwnerController::class, 'destroy']);
    });
});
