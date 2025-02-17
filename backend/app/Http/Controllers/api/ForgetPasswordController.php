<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
           try{
            $validateData= $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);


            $status = Password::sendResetLink($request->only('email'));

            return $status === Password::RESET_LINK_SENT
                        ? response()->json(['message' => __($status)],201)
                        : response()->json(['message' => __($status)], 400);    
            
              
           }
           catch (ValidationException $e) {
            return response()->json(['message' => 'Invalid email', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
        }
    }

    // public function resetPassword(Request $request)
    // {
    //     try {
    //         // Validate input before try-catch
    //         $request->validate([
    //             'email' => 'required|email|exists:users,email',
    //         ]);
    
    //         // Find user
    //         $user = User::where('email', $request->email)->firstOrFail();
    
    //         // Generate verification code
    //         $code = rand(111111, 999999);
    
    //         // Use a transaction to ensure atomicity
    //         DB::beginTransaction();
    
    //         $user->verification_code = $code;
    
    //         if (!$user->save()) {
    //             DB::rollBack();
    //             return response()->json(['message' => 'Something went wrong'], 500);
    //         }
    
    //         // Prepare email data
    //         $emailData = [
    //             'header' => 'Reset Password',
    //             'code' => $code,
    //             'email' => $user->email,
    //             'name' => $user->name,
    //         ];
    
    //         // Send email asynchronously
    //         Mail::to($user->email)->queue(new ResetPasswordMail($emailData));
    
    //         DB::commit();
    //         return response()->json(['message' => 'Code sent to your email'], 201);
            
    //     } catch (ValidationException $e) {
    //         return response()->json(['message' => 'Invalid email', 'errors' => $e->errors()], 422);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
    //     }
    // }
}