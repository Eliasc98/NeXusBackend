<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/php-version', function () {
    return phpversion();
});


Route::post('/register-user', [AuthController::class, 'register']);


