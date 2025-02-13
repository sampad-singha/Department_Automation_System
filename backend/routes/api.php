<?php

use App\Http\Controllers\Api\UserAuthController;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'userLogin'], function () {
    
    Route::post('/LoginUser',[UserAuthController::class, 'login'])->middleware('throttle:userLogin');
    Route::post('/LogoutUser',[UserAuthController::class, 'UserLogOut'])->middleware('auth:sanctum');
    Route::post('/rest-password',[UserAuthController::class,'changePassword'])->middleware('auth:sanctum');
    
});