<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentLeave;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Exception;

class StudentLeaveController extends Controller
{
    /**
     * Submit a new leave application
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitLeave(Request $request)
    {
        try {
            $student = $request->user();
            
            // Check if we have a valid student
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authenticated student not found',
                    'error_code' => 'STUDENT_NOT_FOUND'
                ], 404);
            }
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                'reason' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'from_date' => 'required|date|date_format:Y-m-d|after_or_equal:today',
                'to_date' => 'required|date|date_format:Y-m-d|after_or_equal:from_date',
                'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'error_code' => 'VALIDATION_ERROR',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Generate a unique leave ID
            $leaveId = StudentLeave::generateLeaveId();
            
            // Handle file upload if an attachment is provided
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = 'leave_' . time() . '_' . $file->getClientOriginalName();
                $attachmentPath = $file->storeAs('student_leaves', $filename, 'public');
            }
            
            // Create the leave application
            $leave = StudentLeave::create([
                'school_id' => $student->school_id,
                'student_id' => $student->id,
                'leave_id' => $leaveId,
                'reason' => $request->reason,
                'description' => $request->description,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date,
                'status' => 'pending',
                'attachment_path' => $attachmentPath,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Leave application submitted successfully',
                'data' => [
                    'leave_id' => $leave->leave_id,
                    'reason' => $leave->reason,
                    'from_date' => $leave->from_date->format('Y-m-d'),
                    'to_date' => $leave->to_date->format('Y-m-d'),
                    'status' => $leave->status,
                    'created_at' => $leave->created_at->format('Y-m-d H:i:s')
                ]
            ], 201);
            
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Submit leave error: ' . $e->getMessage());
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while submitting the leave application',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
    
    /**
     * Get leave applications submitted by the authenticated student
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myLeaveApplications(Request $request)
    {
        try {
            $student = $request->user();
            
            // Check if we have a valid student
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authenticated student not found',
                    'error_code' => 'STUDENT_NOT_FOUND'
                ], 404);
            }
            
            // Get the student's leave applications
            $leaves = StudentLeave::where('student_id', $student->id)
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->map(function($leave) {
                            return [
                                'id' => $leave->id,
                                'leave_id' => $leave->leave_id,
                                'reason' => $leave->reason,
                                'description' => $leave->description,
                                'from_date' => $leave->from_date->format('Y-m-d'),
                                'to_date' => $leave->to_date->format('Y-m-d'),
                                'days' => $leave->leave_days,
                                'status' => $leave->status,
                                'admin_remarks' => $leave->admin_remarks,
                                'has_attachment' => !empty($leave->attachment_path),
                                'created_at' => $leave->created_at->format('Y-m-d H:i:s'),
                                'processed_at' => $leave->processed_at ? $leave->processed_at->format('Y-m-d H:i:s') : null
                            ];
                        });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'applications_count' => count($leaves),
                    'leave_applications' => $leaves
                ]
            ]);
            
        } catch (Exception $e) {
            // Log the exception
            \Log::error('My leave applications error: ' . $e->getMessage());
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving leave applications',
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
    public function leaveDetails(Request $request, $id)
    {
        try {
            $student = $request->user();
            
            // Check if we have a valid student
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authenticated student not found',
                    'error_code' => 'STUDENT_NOT_FOUND'
                ], 404);
            }
            
            // Find the leave application
            $leave = StudentLeave::where('id', $id)
                        ->where('student_id', $student->id)
                        ->first();
                        
            if (!$leave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave application not found',
                    'error_code' => 'LEAVE_NOT_FOUND'
                ], 404);
            }
            
            // Get attachment URL if exists
            $attachmentUrl = null;
            if ($leave->attachment_path) {
                $attachmentUrl = url('storage/' . $leave->attachment_path);
            }
            
            // Return the leave application details
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $leave->id,
                    'leave_id' => $leave->leave_id,
                    'reason' => $leave->reason,
                    'description' => $leave->description,
                    'from_date' => $leave->from_date->format('Y-m-d'),
                    'to_date' => $leave->to_date->format('Y-m-d'),
                    'days' => $leave->leave_days,
                    'status' => $leave->status,
                    'admin_remarks' => $leave->admin_remarks,
                    'attachment_url' => $attachmentUrl,
                    'created_at' => $leave->created_at->format('Y-m-d H:i:s'),
                    'processed_at' => $leave->processed_at ? $leave->processed_at->format('Y-m-d H:i:s') : null,
                ]
            ]);
            
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Leave details error: ' . $e->getMessage());
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving leave application details',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
}
