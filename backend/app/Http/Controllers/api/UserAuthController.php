<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Login\UserLoginRequest;


class UserAuthController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        try {
            Log::info('Starting user login.');
            $user = User::with(['roles', 'department'])->where('email', $request['email'])->first();
    
            if (!$user || !Hash::check($request['password'], $user->password)) {
                Log::warning('Invalid login attempt.', ['email' => $request['email']]);
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
    
            if ($user->hasRole(['admin', 'super-admin'])) {
                Log::warning('Admin or Super Admin cannot log in.', ['email' => $request['email']]);
                return response()->json(['message' => 'Admins cannot log in here'], 403);
            }
    
            $tokenName = 'auth_token';
            $rememberMe = isset($request['remember_me']) && $request['remember_me'];
    
            
            $token = $user->createToken($tokenName)->plainTextToken;
    
           
            $expirationTime = $rememberMe ? now()->addDays(5) : now()->addMinutes(60);
            $user->tokens()->latest()->first()->forceFill([
                'expires_at' => $expirationTime,
            ])->save();
    
            Log::info('User logged in successfully.');
    
            return response()->json([
                'token' => $token,
                'user' => $user,
                'expires_at' => $expirationTime,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Login error.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'An error occurred during login'], 500);
        }
    }
    

    public function authUser()
    {
        $user = Auth::user();
        $user->load('roles', 'department');
        return response()->json($user);
    }
}
