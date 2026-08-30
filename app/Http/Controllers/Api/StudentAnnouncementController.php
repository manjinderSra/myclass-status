<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notice;
use Exception;

class StudentAnnouncementController extends Controller
{
    /**
     * Get all announcements for the authenticated student
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
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
            
            // Get notices for this student's school
            // Filter by recipients to include only notices intended for students
            $notices = Notice::where('school_id', $student->school_id)
                ->where(function($query) {
                    $query->whereJsonContains('recipients', 'Student')
                        ->orWhereNull('recipients');
                })
                ->orderBy('publish_date', 'desc')
                ->get()
                ->map(function($notice) {
                    return [
                        'id' => $notice->id,
                        'title' => $notice->title,
                        'message' => $notice->message,
                        'publish_date' => $notice->publish_date->format('Y-m-d'),
                        'created_at' => $notice->created_at->format('Y-m-d H:i:s'),
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'announcements' => $notices,
                    'count' => $notices->count()
                ]
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch announcements: ' . $e->getMessage(),
                'error_code' => 'ANNOUNCEMENT_FETCH_ERROR'
            ], 500);
        }
    }
} 