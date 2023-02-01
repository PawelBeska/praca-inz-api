<?php

use App\Http\Controllers\V1\Auth\AuthController;
use App\Http\Controllers\V1\CaptchaController;
use App\Http\Controllers\V1\ProfileController;
use App\Http\Controllers\V1\ServiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/register/activate/{token}', [AuthController::class, 'verifyEmail'])->name('verifyEmail');

    Route::post('/password/forgot', [AuthController::class, 'passwordForgot'])->name('passwordForgot');
    Route::post('/password/reset', [AuthController::class, 'passwordReset'])->name('passwordReset');


    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
Route::middleware('auth:sanctum')->name('dashboard.')->group(function () {
    Route::apiResource('services', ServiceController::class);
    Route::apiResource('profile', ProfileController::class)->only(['index', 'update']);
});


Route::prefix('/captcha/{service}')
    ->middleware('captcha')
    ->name('captcha.')
    ->group(function () {
        Route::post('/generate', [CaptchaController::class, 'generate'])->name('generate');
        Route::post('/verify/{verification}', [CaptchaController::class, 'verify'])->name('verify');
    });
