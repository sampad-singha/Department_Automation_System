<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{


    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6|confirmed',
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => bcrypt($password),
                        'remember_token' => Str::random(60),
                    ])->save();
                }
            );

            return $status === Password::PASSWORD_RESET
                ? response()->json(['message' => __($status)], 201)
                : response()->json(['message' => __($status)], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
        }
    }
}
