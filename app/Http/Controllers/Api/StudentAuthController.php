<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class StudentAuthController extends Controller
{
    /**
     * Handle student login and return token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            // Validate request data
            $validated = $request->validate([
                'student_id' => 'required|string',
                'password' => 'required|string',
            ]);
            
            // Find student by student_id
            $student = Student::where('student_id', $validated['student_id'])
                ->with('school')
                ->first();
            
            // Check if student exists and password is correct
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found',
                    'error_code' => 'STUDENT_NOT_FOUND'
                ], 404);
            }
            
            // Check student account status
            if ($student->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Student account is ' . $student->status,
                    'error_code' => 'ACCOUNT_INACTIVE'
                ], 403);
            }
            
            // Verify password
            if (!Hash::check($validated['password'], $student->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid password',
                    'error_code' => 'INVALID_PASSWORD'
                ], 401);
            }
            
            // Check if student has necessary relationships
            if (!$student->class || !$student->section) {
                // Still allow login, but add a warning in response
                $warning = 'Student is not assigned to a class or section';
            }
            
            // Create token with full abilities
            $token = $student->createToken('student-mobile-app')->plainTextToken;
            
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'warning' => $warning ?? null,
                'data' => [
                    'token' => $token,
                    'student_id' => $student->student_id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'email' => $student->email,
                    'admission_number' => $student->admission_number,
                    'class' => $student->class->name ?? null,
                    'section' => $student->section->name ?? null,
                    'profile_image' => $student->profile_image ? asset('storage/' . $student->profile_image) : null,
                    'school_id' => $student->school_id,
                    'academic_year' => $student->academic_year,
                    'school' => [
                        'name' => $student->school->name ?? null,
                        'logo' => $student->school->logo ? asset('storage/' . $student->school->logo) : null,
                        'tagline' => $student->school->tagline ?? null
                    ]
                ]
            ]);
            
        } catch (ValidationException $e) {
            // Validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'error_code' => 'VALIDATION_ERROR',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Student login error: ' . $e->getMessage());
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Logout the student by revoking the token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // Delete the current access token
            $request->user()->currentAccessToken()->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Student logout error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while logging out',
                'error_code' => 'LOGOUT_ERROR'
            ], 500);
        }
    }

    /**
     * Update the student's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
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
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $student = auth('sanctum')->user();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing token.'
            ], 401);
        }

        // Check if current password is correct
        if (!Hash::check($request->current_password, $student->password)) {
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
            $student->password = Hash::make($request->password);
            $student->save();

            // Log the password change
            Log::info('Student password updated', [
                'student_id' => $student->id, 
                'student_name' => $student->name,
                'school_id' => $student->school_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating student password: ' . $e->getMessage(), [
                'student_id' => $student->id,
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