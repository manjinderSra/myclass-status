<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Student;
use App\Models\TimeTable;
use App\Models\TimeTablePeriod;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TeacherAuthController extends Controller
{
    /**
     * Handle teacher login via API and return a bearer token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function login(Request $request)
{
    try {
        // Validate input
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Find the teacher by employee_id
        $teacher = Teacher::with('subject')
            ->where('employee_id', $request->employee_id)
            ->first();

        if (!$teacher || !Hash::check($request->password, $teacher->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
                'error_code' => 'INVALID_CREDENTIALS',
            ], 401);
        }

        // Find or create corresponding user for this teacher
        $user = User::where('email', $teacher->email)->first();

        if (!$user) {
            $user = User::create([
                'name'       => trim($teacher->first_name . ' ' . $teacher->last_name),
                'email'      => $teacher->email,
                'password'   => $teacher->password,
                'role'       => 'teacher',
                'school_id'  => $teacher->school_id,
            ]);

            // If teacher model has 'user_id' field, link them
            if (Schema::hasColumn('teachers', 'user_id')) {
                $teacher->update(['user_id' => $user->id]);
            }
        }

        // Create API token
        $token = $user->createToken('teacher_auth_token')->plainTextToken;

        // Get school name
        $school = \App\Models\School::find($teacher->school_id);
        $schoolName = $school ? $school->name : null;

        // Subject info
        $subjectInfo = null;
        if ($teacher->subject) {
            $subjectInfo = [
                'id' => $teacher->subject->id ?? $teacher->subject_id ?? null,
                'name' => $teacher->subject->name ?? (string) $teacher->subject,
            ];
        }

        // Prepare response
        $teacherInfo = [
            'id'            => $teacher->id,
            'name'          => trim($teacher->first_name . ' ' . $teacher->last_name),
            'email'         => $teacher->email,
            'employee_id'   => $teacher->employee_id,
            'school_id'     => $teacher->school_id,
            'school_name'   => $schoolName,
            'profile_image' => $teacher->profile_image ? url('storage/' . $teacher->profile_image) : null,
            'subject'       => $subjectInfo,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token'   => $token,
            'teacher' => $teacherInfo,
        ]);

    } catch (ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors'  => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        Log::error('Teacher login error: ' . $e->getMessage(), [
            'employee_id' => $request->employee_id ?? 'not provided',
            'trace'       => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'An error occurred during login',
            'error_code' => 'SERVER_ERROR',
        ], 500);
    }
}

    
    /**
     * Get the authenticated teacher's profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                    'error_code' => 'UNAUTHORIZED'
                ], 401);
            }
            
            // If user has teacher role, get the teacher details
         
            
            // Find the teacher by email
            $teacher = Teacher::where('email', $user->email)
                ->with(['school', 'subject'])
                ->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher profile not found',
                    'error_code' => 'PROFILE_NOT_FOUND'
                ], 404);
            }
            
            // Get school name
            $schoolName = null;
            if ($teacher->school) {
                $schoolName = $teacher->school->name;
            } else {
                // If relationship doesn't work, try fetching directly
                $school = \App\Models\School::find($teacher->school_id);
                $schoolName = $school ? $school->name : null;
            }
            
            // Get subject information
            $subjectInfo = null;
            if ($teacher->subject_id && $teacher->subject) {
                $subjectInfo = [
                    'id' => $teacher->subject_id,
                    'name' => $teacher->subject->name
                ];
            } else if ($teacher->subject) {
                // Check if subject is a numeric ID
                if (is_numeric($teacher->subject)) {
                    // Try to find the subject by ID
                    $subjectModel = \App\Models\Subject::find($teacher->subject);
                    if ($subjectModel) {
                        $subjectInfo = [
                            'id' => $subjectModel->id,
                            'name' => $subjectModel->name
                        ];
                    } else {
                        // Fallback to just using the subject value
                        $subjectInfo = $teacher->subject;
                    }
                } else {
                    // If it's not numeric, use as is
                    $subjectInfo = $teacher->subject;
                }
            }
            
            // Prepare teacher profile
            $teacherProfile = [
                'id' => $teacher->id,
                'name' => $teacher->first_name . ' ' . $teacher->last_name,
                'email' => $teacher->email,
                'employee_id' => $teacher->employee_id,
                'school_id' => $teacher->school_id,
                'school_name' => $schoolName,
                'profile_image' => $teacher->profile_image ? url('storage/' . $teacher->profile_image) : null,
                'subject' => $subjectInfo,
                'gender' => $teacher->gender,
                'primary_contact' => $teacher->primary_contact,
                'date_of_joining' => $teacher->date_of_joining ? $teacher->date_of_joining->format('Y-m-d') : null,
                'qualification' => $teacher->qualification,
                'status' => $teacher->status
            ];
            
            return response()->json([
                'success' => true,
                'teacher' => $teacherProfile
            ]);
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Illuminate\Support\Facades\Log::error('Teacher profile API error: ' . $e->getMessage(), [
                'user_id' => $request->user() ? $request->user()->id : 'not authenticated',
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the profile',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
    
    /**
     * Get all students that the authenticated teacher teaches
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function teachingStudents(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                    'error_code' => 'UNAUTHORIZED'
                ], 401);
            }
            
      
            
            // Find the teacher by email
            $teacher = Teacher::where('email', $user->email)->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher profile not found',
                    'error_code' => 'PROFILE_NOT_FOUND'
                ], 404);
            }
            
            // Get all timetable periods where this teacher teaches
            $timetablePeriods = TimeTablePeriod::where('teacher', $teacher->id)
                ->with('timetable')
                ->get();
                
            if ($timetablePeriods->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No students assigned to this teacher',
                    'data' => [
                        'students' => []
                    ]
                ]);
            }
            
            // Get unique timetable IDs
            $timetableIds = $timetablePeriods->pluck('timetable_id')->unique();
            
            // Get all timetables for these IDs
            $timetables = TimeTable::whereIn('id', $timetableIds)->get();
            
            // Get all class and section combinations
            $classAndSections = [];
            foreach ($timetables as $timetable) {
                $classAndSections[] = [
                    'class_name' => $timetable->class_name,
                    'section_id' => $timetable->section_id
                ];
            }
            
            // Get all students in these classes and sections
            $students = collect();
            foreach ($classAndSections as $classSection) {
                $classStudents = Student::where('school_id', $teacher->school_id)
                    ->whereHas('class', function($query) use ($classSection) {
                        $query->where('name', $classSection['class_name']);
                    })
                    ->where('section_id', $classSection['section_id'])
                    ->where('status', 'active')
                    ->with(['class', 'section'])
                    ->get();
                    
                $students = $students->merge($classStudents);
            }

            
            $formattedStudents = $students->unique('id')->map(function($student) {
                return [
                    'id' => $student->id,
                    'student_id' => $student->student_id,
                    'admission_number' => $student->admission_number,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'email' => $student->email,
                    'gender' => $student->gender,
                    'class' => $student->class->name ?? null,
                    'section' => $student->section->name ?? null,
                    'profile_image' => $student->profile_image ? url('storage/' . $student->profile_image) : null,
                    'blood_group' => $student->blood_group,
                    'dob' => $student->dob ? $student->dob->format('Y-m-d') : null,
                    'primary_contact' => $student->primary_contact,
                    'father_name' => $student->father_name,
                    'mother_name' => $student->mother_name
                ];
            })->values();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'teacher' => [
                        'id' => $teacher->id,
                        'name' => $teacher->first_name . ' ' . $teacher->last_name,
                        'subject' => $teacher->subject
                    ],
                    'students_count' => $formattedStudents->count(),
                    'students' => $formattedStudents
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in teachingStudents: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching students',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
    
    /**
     * Logout the teacher by revoking the token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                    'error_code' => 'UNAUTHORIZED'
                ], 401);
            }
            
            // Revoke all of the user's tokens
            $user->tokens()->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during logout',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Change the authenticated teacher's password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                    'error_code' => 'UNAUTHORIZED'
                ], 401);
            }
            
        
            
            // Validate request data
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|different:current_password',
                'new_password_confirmation' => 'required|string|same:new_password',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                    'error_code' => 'VALIDATION_ERROR'
                ], 422);
            }
            
            // Find the teacher by email
            $teacher = Teacher::where('email', $user->email)->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher profile not found',
                    'error_code' => 'PROFILE_NOT_FOUND'
                ], 404);
            }
            
            // Check if the current password is correct
            if (!Hash::check($request->current_password, $teacher->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                    'error_code' => 'INVALID_CURRENT_PASSWORD'
                ], 422);
            }
            
            // Update the teacher's password
            $teacher->password = Hash::make($request->new_password);
            $teacher->save();
            
            // Also update the user's password if it exists
            if ($user) {
                $user->password = Hash::make($request->new_password);
                $user->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully'
            ]);
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Illuminate\Support\Facades\Log::error('Teacher change password API error: ' . $e->getMessage(), [
                'user_id' => $request->user() ? $request->user()->id : 'not authenticated',
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while changing the password',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
} 