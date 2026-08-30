<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserPasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing token.'
            ], 401);
        }

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.',
                'errors' => [
                    'current_password' => ['The provided password does not match our records.']
                ]
            ], 422);
        }

        try {
            // Update the password
            $user->password = Hash::make($request->password);
            $user->save();

            // Log the password change
            Log::info('User password updated', [
                'user_id' => $user->id, 
                'user_email' => $user->email,
                'user_type' => class_basename($user)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating password: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating your password.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 