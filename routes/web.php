<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    $response = [
        'success' => false,
        'data'    => null,
        'message' => 'Token is required or invalid',
    ];
    return response()->json($response, 401);
})->name('login');
