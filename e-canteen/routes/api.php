<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\Auth\CanteenOwnerController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\Users\CanteenController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

use App\Http\Controllers\TransactionController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/transaction', [TransactionController::class, 'create']);
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::get('/canteen', [CanteenController::class, 'index']);
    Route::get('/menu/{canteen}', [CanteenController::class, 'show']);

    Route::middleware('user')->group(function () {
        Route::get('/payment-channel', [CheckoutController::class, 'index']);
        Route::post('/transaction', [TransactionController::class, 'create']);
    });

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
