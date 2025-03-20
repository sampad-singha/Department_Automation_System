<?php

use App\Http\Controllers\api\CourseController;
use App\Http\Controllers\api\CourseSessionController;
use App\Http\Controllers\api\EnrollmentController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\api\NoticeController;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\ForgetPasswordController;
use App\Http\Controllers\Api\ShowNoticeController;

Route::group(['prefix' => 'auth'], function () {

    Route::post('/forget-password',[ForgetPasswordController::class, 'resetPassword']);
    Route::post('/reset-password',[PasswordResetController::class, 'resetPassword'])->name('password.reset');

    Route::post('/login',[UserAuthController::class, 'login']);
        ->middleware('throttle:userLogin');
    Route::post('/logout',[LogoutController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/user',[UserAuthController::class, 'authUser'])->middleware('auth:sanctum');

});

// Courses routes
Route::group(['prefix' => 'courses', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/', [CourseController::class, 'showAll']);
    Route::get('/active', [CourseSessionController::class, 'show']);
    Route::get('/{course_id}', [CourseController::class, 'show']);
    Route::get('/active/enrollments', [EnrollmentController::class, 'showForStudent']);
    Route::get('/active/{course_session_id}', [EnrollmentController::class, 'showForTeacher']);
    Route::post('/active/enrollments/{enrollment}', [EnrollmentController::class, 'update']);
});


Route::get('show-notice',[ShowNoticeController::class,'showAll']);
Route::get('show-notice/{id}',[ShowNoticeController::class,'show']);

