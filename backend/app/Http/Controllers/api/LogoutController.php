<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        try {
            Log::info('Starting user logout.');
    
            $user = Auth::user(); 
            if (!$user) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }
            // Delete only current token
            $request->user()->currentAccessToken()->delete();
    
            Log::info('User logged out successfully.');
    
            return response()->json(['message' => 'User logged out successfully'], 200);
        } catch (\Throwable $e) {
            Log::error('Logout error.', ['error' => $e->getMessage(), 'stack' => $e->getTraceAsString()]);
            return response()->json(['message' => 'An error occurred during logout', 'error' => $e->getMessage()], 500);
        }
    }
}
