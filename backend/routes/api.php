<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\ChangePasswordController;
use App\Http\Controllers\Api\ForgetPasswordController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\PasswordResetController;

Route::group(['prefix' => 'authUser'], function () {

    Route::post('/forget-password',[ForgetPasswordController::class, 'resetPassword']);
    Route::post('/reset-password',[PasswordResetController::class, 'resetPassword'])->name('password.reset');
    
    Route::post('/login',[UserAuthController::class, 'login'])->middleware('throttle:userLogin');
    Route::post('/logout',[LogoutController::class, 'logout'])->middleware('auth:sanctum');
    
    
});
