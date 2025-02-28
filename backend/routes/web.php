<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\NoticeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('notice/approve/{id}', [NoticeController::class, 'approveNotice'])->name('notice.approve');
