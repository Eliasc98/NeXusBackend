<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/register', [AuthController::class, 'register']);

Route::middleware('api')->prefix('api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/user', [AuthController::class, 'getUser']);
        Route::post('/logout', [AuthController::class, 'logOut']);
        Route::put('/update-profile', [AuthController::class, 'updateProfile']);
        Route::get('/show-profile', [AuthController::class, 'show']);
        Route::delete('/delete-account', [AuthController::class, 'destroy']);

        Route::get('/contacts', [ContactController::class, 'index']);
        Route::post('/contacts', [ContactController::class, 'store']);
        Route::get('/contacts/search', [ContactController::class, 'search']);
    });
});

