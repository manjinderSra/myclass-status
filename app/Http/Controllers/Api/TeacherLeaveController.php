<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentLeave;
use App\Models\Teacher;
use App\Models\TimeTable;
use App\Models\TimeTablePeriod;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Exception;

class TeacherLeaveController extends Controller
{
    /**
     * Get all leave applications from students taught by this teacher
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeaveApplications(Request $request)
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
            
            // Get status filter if provided
            $status = $request->input('status');
            
            // Get all timetable periods where this teacher teaches
            $timetablePeriods = TimeTablePeriod::where('teacher', $teacher->id)
                ->with('timetable')
                ->get();
                
            if ($timetablePeriods->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No classes assigned to this teacher',
                    'data' => [
                        'leaves' => []
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
            $studentIds = [];
            foreach ($classAndSections as $classSection) {
                $classStudents = Student::where('school_id', $teacher->school_id)
                    ->whereHas('class', function($query) use ($classSection) {
                        $query->where('name', $classSection['class_name']);
                    })
                    ->where('section_id', $classSection['section_id'])
                    ->pluck('id');
                    
                $studentIds = array_merge($studentIds, $classStudents->toArray());
            }
            
            // Remove duplicates
            $studentIds = array_unique($studentIds);
            
            // If no students found, return empty result
            if (empty($studentIds)) {
                return response()->json([
                    'success' => true,
                    'message' => 'No students found in assigned classes',
                    'data' => [
                        'leaves' => []
                    ]
                ]);
            }
            
            // Get leave applications for these students
            $query = StudentLeave::whereIn('student_id', $studentIds)
                        ->where('school_id', $teacher->school_id)
                        ->with(['student']);
            
            // Filter by status if provided
            if ($status) {
                $query->where('status', $status);
            }
            
            $leaves = $query->orderBy('created_at', 'desc')
                            ->get()
                            ->map(function($leave) {
                                return [
                                    'id' => $leave->id,
                                    'leave_id' => $leave->leave_id,
                                    'student' => [
                                        'id' => $leave->student->id,
                                        'student_id' => $leave->student->student_id,
                                        'name' => $leave->student->first_name . ' ' . $leave->student->last_name,
                                        'class' => $leave->student->class->name ?? null,
                                        'section' => $leave->student->section->name ?? null,
                                        'profile_image' => $leave->student->profile_image ? url('storage/' . $leave->student->profile_image) : null
                                    ],
                                    'reason' => $leave->reason,
                                    'description' => $leave->description,
                                    'from_date' => $leave->from_date->format('Y-m-d'),
                                    'to_date' => $leave->to_date->format('Y-m-d'),
                                    'days' => $leave->leave_days,
                                    'status' => $leave->status,
                                    'created_at' => $leave->created_at->format('Y-m-d H:i:s')
                                ];
                            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'leaves_count' => $leaves->count(),
                    'leaves' => $leaves
                ]
            ]);
            
        } catch (Exception $e) {
            \Log::error('Error in getLeaveApplications: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching leave applications',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
    
    /**
     * Get details of a specific leave application
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeaveDetails(Request $request, $id)
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
            
            // Get the leave application
            $leave = StudentLeave::where('id', $id)
                        ->where('school_id', $teacher->school_id)
                        ->with(['student', 'processor'])
                        ->first();
                        
            if (!$leave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave application not found',
                    'error_code' => 'LEAVE_NOT_FOUND'
                ], 404);
            }
            
            // Verify that this teacher teaches the student
            $student = $leave->student;
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found for this leave application',
                    'error_code' => 'STUDENT_NOT_FOUND'
                ], 404);
            }
            
            // Check if this teacher teaches this student's class
            $teachesThisStudent = TimeTablePeriod::where('teacher', $teacher->id)
                ->whereHas('timetable', function($query) use ($student) {
                    $query->where('class_name', $student->class->name ?? '')
                          ->where('section_id', $student->section_id);
                })
                ->exists();
                
            if (!$teachesThisStudent) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to view this leave application',
                    'error_code' => 'UNAUTHORIZED_ACCESS'
                ], 403);
            }
            
            // Get attachment URL if exists
            $attachmentUrl = null;
            if ($leave->attachment_path) {
                $attachmentUrl = url('storage/' . $leave->attachment_path);
            }
            
            // Return leave application details
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $leave->id,
                    'leave_id' => $leave->leave_id,
                    'student' => [
                        'id' => $student->id,
                        'student_id' => $student->student_id,
                        'name' => $student->first_name . ' ' . $student->last_name,
                        'class' => $student->class->name ?? null,
                        'section' => $student->section->name ?? null,
                        'email' => $student->email,
                        'profile_image' => $student->profile_image ? url('storage/' . $student->profile_image) : null
                    ],
                    'reason' => $leave->reason,
                    'description' => $leave->description,
                    'from_date' => $leave->from_date->format('Y-m-d'),
                    'to_date' => $leave->to_date->format('Y-m-d'),
                    'days' => $leave->leave_days,
                    'status' => $leave->status,
                    'attachment_url' => $attachmentUrl,
                    'admin_remarks' => $leave->admin_remarks,
                    'processed_by' => $leave->processor ? $leave->processor->name : null,
                    'processed_at' => $leave->processed_at ? $leave->processed_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $leave->created_at->format('Y-m-d H:i:s')
                ]
            ]);
            
        } catch (Exception $e) {
            \Log::error('Error in getLeaveDetails: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching leave application details',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
    
    /**
     * Update the status of a leave application
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLeaveStatus(Request $request, $id)
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
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,approved,rejected',
                'admin_remarks' => 'required_if:status,rejected|nullable|string|max:1000',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'error_code' => 'VALIDATION_ERROR',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Get the leave application
            $leave = StudentLeave::where('id', $id)
                        ->where('school_id', $teacher->school_id)
                        ->with('student')
                        ->first();
                        
            if (!$leave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave application not found',
                    'error_code' => 'LEAVE_NOT_FOUND'
                ], 404);
            }
            
            // Verify that this teacher teaches the student
            $student = $leave->student;
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found for this leave application',
                    'error_code' => 'STUDENT_NOT_FOUND'
                ], 404);
            }
            
            // Check if this teacher teaches this student's class
            $teachesThisStudent = TimeTablePeriod::where('teacher', $teacher->id)
                ->whereHas('timetable', function($query) use ($student) {
                    $query->where('class_name', $student->class->name ?? '')
                          ->where('section_id', $student->section_id);
                })
                ->exists();
                
            if (!$teachesThisStudent) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to update this leave application',
                    'error_code' => 'UNAUTHORIZED_ACCESS'
                ], 403);
            }
            
            // Update the leave application
            $leave->status = $request->status;
            $leave->admin_remarks = $request->admin_remarks;
            $leave->processed_by = $user->id;
            $leave->processed_at = now();
            
            $leave->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Leave application status updated successfully',
                'data' => [
                    'id' => $leave->id,
                    'leave_id' => $leave->leave_id,
                    'status' => $leave->status,
                    'admin_remarks' => $leave->admin_remarks,
                    'processed_at' => $leave->processed_at->format('Y-m-d H:i:s')
                ]
            ]);
            
        } catch (Exception $e) {
            \Log::error('Error in updateLeaveStatus: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating leave application status',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
} 