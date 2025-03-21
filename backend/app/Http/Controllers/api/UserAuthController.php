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

            $validated = $request->validated();
            $user = User::with(['roles', 'department'])->where('email', $validated['email'])->first();


            if (!$user || !Hash::check($validated['password'], $user->password)) {
                Log::warning('Invalid login attempt.', ['email' => $validated['email']]);
                return response()->json(['message' => 'Invalid credentials'], 401);
            }


            if ($user->hasRole(['admin', 'super-admin'])) {
                Log::warning('Admin or Super Admin cannot log in.', ['email' => $validated['email']]);
                return response()->json(['message' => 'Admins cannot log in here'], 403);
            }

            $tokenName = 'auth_token';
            $token = isset($validated['remember_me']) && $validated['remember_me']
                ? $user->createToken($tokenName, ['*'], now()->addWeeks(2))->plainTextToken
                : $user->createToken($tokenName)->plainTextToken;

            Log::info('User logged in successfully.');

            return response()->json([
                'token' => $token,
                'user' => $user,
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
