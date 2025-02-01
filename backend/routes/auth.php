<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', function (){
    return response()->json([
        'message' => 'Login successful',]);
    });
