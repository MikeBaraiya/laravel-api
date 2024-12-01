<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\UserController;

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'The requested URL was not found on the server.',
    ], 404);
});

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    // User routes
    Route::get('users', [UserController::class, 'index']);
    Route::post('user', [UserController::class, 'store']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::delete('user/{id}', [UserController::class, 'destroy']);

    // Order routes
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('confirmed-orders', [OrderController::class, 'confirmedOrders']);
    Route::post('order', [OrderController::class, 'store']);
    Route::get('order/{id}', [OrderController::class, 'show']);
    Route::put('order/{id}', [OrderController::class, 'update']);
    Route::delete('order/{id}', [OrderController::class, 'destroy']);

    // Conform Order route
    Route::patch('order/{id}/confirm', [OrderController::class, 'confirmOrder']);

    // Logout route
    Route::post('logout', [AuthController::class, 'logout']);
});

