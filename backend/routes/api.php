<?php

use App\Http\Controllers\api\CourseController;
use App\Http\Controllers\api\CourseResourceController;
use App\Http\Controllers\api\CourseSessionController;
use App\Http\Controllers\api\EnrollmentController;
use App\Http\Controllers\Api\ForgetPasswordController;
use App\Http\Controllers\api\IdCardController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\ResultController;
use App\Http\Controllers\Api\ShowNoticeController;
use App\Http\Controllers\Api\UserAuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {

    Route::post('/forget-password', [ForgetPasswordController::class, 'resetPassword']);
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.reset');
    Route::post('/login', [UserAuthController::class, 'login'])
        ->middleware('throttle:userLogin');

    Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/user', [UserAuthController::class, 'authUser'])->middleware('auth:sanctum');

});

// Courses routes
Route::group(['prefix' => 'courses', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/active/enrollments', [EnrollmentController::class, 'showForStudent']);
    // More specific route should come first
    Route::get('/active/{course_id}/past-sessions', [CourseSessionController::class, 'showPastSessions']);
    Route::get('/active/{courseSession_id}', [CourseSessionController::class, 'showOne']);

    Route::get('/', [CourseController::class, 'showAll']);
    Route::get('/active', [CourseSessionController::class, 'show']);
    Route::get('/{course_id}', [CourseController::class, 'show']);
    Route::get('/active/enrollments/{courseSession_id}', [EnrollmentController::class, 'showForTeacher']);

    // POST routes
    Route::post('/active/enrollments/updateMarks', [EnrollmentController::class, 'updateMarks']);
    Route::post('/active/enrollments/{enrollment}', [EnrollmentController::class, 'update']);
    Route::post('/active/enroll', [EnrollmentController::class, 'store']);
});


Route::get('show-notice', [ShowNoticeController::class, 'showAll']);
Route::get('show-notice/{id}', [ShowNoticeController::class, 'show']);

Route::group(['prefix' => 'result', 'middleware' => ['auth:sanctum']], function () {
    Route::get('show/{courseId}', [ResultController::class, 'showResult']);
    Route::get('show-full-result/{year}/{semester}', [ResultController::class, 'showFullResult']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/course-resources/upload', [CourseResourceController::class, 'upload'])->middleware('role:teacher');
    Route::get('/course-resources/download/{id}', [CourseResourceController::class, 'download'])->middleware('role:teacher|student');
    Route::get('/course-resources/{course_session_id}', [CourseResourceController::class, 'index']);
    Route::put('/course-resources/{id}', [CourseResourceController::class, 'update']);
    Route::delete('/course-resources/{id}', [CourseResourceController::class, 'destroy']);
});

Route::get('/id-card', [IdCardController::class, 'generateIdCard'])->middleware('auth:sanctum');
Route::get('/id-card/verify/{id}', [IdCardController::class, 'verify'])->name('verify');

