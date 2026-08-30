<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Exception;
use Illuminate\Support\Facades\Validator;

class StudentComplaintController extends Controller
{
    /**
     * Submit a new complaint
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitComplaint(Request $request)
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
                'nature' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'error_code' => 'VALIDATION_ERROR',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Generate a unique complaint ID
            $complaintId = Complaint::generateComplaintId();
            
            // Create the complaint
            $complaint = Complaint::create([
                'school_id' => $student->school_id,
                'student_id' => $student->id,
                'complaint_id' => $complaintId,
                'nature' => $request->nature,
                'description' => $request->description,
                'status' => 'pending'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Complaint submitted successfully',
                'data' => [
                    'complaint_id' => $complaint->complaint_id,
                    'nature' => $complaint->nature,
                    'description' => $complaint->description,
                    'status' => $complaint->status,
                    'created_at' => $complaint->created_at->format('Y-m-d H:i:s')
                ]
            ], 201);
            
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Submit complaint error: ' . $e->getMessage());
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while submitting the complaint',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
    
    /**
     * Get complaints submitted by the authenticated student
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myComplaints(Request $request)
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
            
            // Get the student's complaints
            $complaints = Complaint::where('student_id', $student->id)
                            ->orderBy('created_at', 'desc')
                            ->get()
                            ->map(function($complaint) {
                                return [
                                    'id' => $complaint->id,
                                    'complaint_id' => $complaint->complaint_id,
                                    'nature' => $complaint->nature,
                                    'description' => $complaint->description,
                                    'status' => $complaint->status,
                                    'response' => $complaint->response,
                                    'created_at' => $complaint->created_at->format('Y-m-d H:i:s'),
                                    'resolved_at' => $complaint->resolved_at ? $complaint->resolved_at->format('Y-m-d H:i:s') : null
                                ];
                            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'complaints_count' => count($complaints),
                    'complaints' => $complaints
                ]
            ]);
            
        } catch (Exception $e) {
            // Log the exception
            \Log::error('My complaints error: ' . $e->getMessage());
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving complaints',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
    
    /**
     * Get details of a specific complaint
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function complaintDetails(Request $request, $id)
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
            
            // Find the complaint
            $complaint = Complaint::where('id', $id)
                            ->where('student_id', $student->id)
                            ->first();
                            
            if (!$complaint) {
                return response()->json([
                    'success' => false,
                    'message' => 'Complaint not found',
                    'error_code' => 'COMPLAINT_NOT_FOUND'
                ], 404);
            }
            
            // Return the complaint details
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $complaint->id,
                    'complaint_id' => $complaint->complaint_id,
                    'nature' => $complaint->nature,
                    'description' => $complaint->description,
                    'status' => $complaint->status,
                    'response' => $complaint->response,
                    'created_at' => $complaint->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $complaint->updated_at->format('Y-m-d H:i:s'),
                    'resolved_at' => $complaint->resolved_at ? $complaint->resolved_at->format('Y-m-d H:i:s') : null,
                ]
            ]);
            
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Complaint details error: ' . $e->getMessage());
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving complaint details',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
}
