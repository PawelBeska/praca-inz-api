<?php

use App\Http\Controllers\V1\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/register/activate/{token}', [AuthController::class, 'verifyEmail']);

    Route::post('/password/forgot-password', [AuthController::class, 'passwordForgot']);
    Route::post('/password/reset', [AuthController::class, 'passwordReset']);


    Route::middleware(['auth:api'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    });
});
