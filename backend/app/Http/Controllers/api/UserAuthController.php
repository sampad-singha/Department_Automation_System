<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Login\ChangePassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Login\UserLoginRequest;

class UserAuthController extends Controller
{
    public function login(UserLoginRequest $request)
{
    try {
        Log::info('Starting user login.');

        $validated = $request->validated();

        // Find user by email
        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            Log::warning('Invalid login attempt.', ['email' => $validated['email']]);
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info('User logged in successfully.', ['user_id' => $user->id]);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'token' => $token,
        ], 200);
    } catch (\Throwable $e) {
        Log::error('Login error.', ['error' => $e->getMessage()]);
        return response()->json(['message' => 'An error occurred during login'], 500);
    }
}


    public function logout(Request $request)
    {
        try {
            Log::info('Starting user logout.');

            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }

            // Revoke all tokens
            $user->tokens()->delete();

            Log::info('User logged out successfully.', ['user_id' => $user->id]);

            return response()->json(['message' => 'User logged out successfully'], 200);
        } catch (\Throwable $e) {
            Log::error('Logout error.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'An error occurred during logout'], 500);
        }
    }

    public function resetPassword(ChangePassword $request)
    {
        try {
            Log::info('Starting password reset.');

            $validated = $request->validated();

            $user = Auth::user();

            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json(['message' => 'Incorrect current password'], 400);
            }

            if ($validated['current_password'] === $validated['new_password']) {
                return response()->json(['message' => 'New password must be different'], 400);
            }

            $user->password = Hash::make($validated['new_password']);
            $user->save();

            Log::info('Password reset successfully.', ['user_id' => $user->id]);

            return response()->json(['message' => 'Password changed successfully'], 200);
        } catch (\Throwable $e) {
            Log::error('Password reset error.', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'An error occurred while resetting password'], 500);
        }
    }

}
