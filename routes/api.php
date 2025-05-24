<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/fetch-user', [AuthController::class, 'show']);
    Route::post('/logout', [AuthController::class, 'logOut']);
    Route::put('/update-profile', [AuthController::class, 'updateProfile']);
    Route::get('/show-profile', [AuthController::class, 'show']);
    Route::delete('/delete-account', [AuthController::class, 'destroy']);

    Route::get('/contacts', [ContactController::class, 'index']);
    Route::post('/save-contacts', [ContactController::class, 'store']);
    Route::get('/contacts/search', [ContactController::class, 'search']);
});

