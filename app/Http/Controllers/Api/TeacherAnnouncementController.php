<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notice;
use Exception;

class TeacherAnnouncementController extends Controller
{
    /**
     * Get all announcements for the authenticated teacher
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
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
            
            // If user has teacher role, proceed
           
            
            // Get notices for this teacher's school
            // Filter by recipients to include only notices intended for teachers
            $notices = Notice::where('school_id', $user->school_id)
                ->where(function($query) {
                    $query->whereJsonContains('recipients', 'Teacher')
                        ->orWhereNull('recipients');
                })
                ->with('creator:id,name') // Join with users table to get creator name
                ->orderBy('publish_date', 'desc')
                ->get()
                ->map(function($notice) {
                    return [
                        'id' => $notice->id,
                        'title' => $notice->title,
                        'message' => $notice->message,
                        'publish_date' => $notice->publish_date->format('Y-m-d'),
                        'created_by' => $notice->creator ? $notice->creator->name : 'Admin',
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
            // Log the error for debugging
            \Illuminate\Support\Facades\Log::error('Teacher announcements API error: ' . $e->getMessage(), [
                'user_id' => $request->user() ? $request->user()->id : 'not authenticated',
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching announcements',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get a specific announcement details
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
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
            
            // If user has teacher role, proceed
            if ($user->role !== 'teacher') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. User is not a teacher.',
                    'error_code' => 'NOT_A_TEACHER'
                ], 403);
            }
            
            // Get the specific notice
            $notice = Notice::where('id', $id)
                ->where('school_id', $user->school_id)
                ->where(function($query) {
                    $query->whereJsonContains('recipients', 'Teacher')
                        ->orWhereNull('recipients');
                })
                ->with('creator:id,name')
                ->first();
            
            if (!$notice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Announcement not found or not accessible',
                    'error_code' => 'ANNOUNCEMENT_NOT_FOUND'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $notice->id,
                    'title' => $notice->title,
                    'message' => $notice->message,
                    'publish_date' => $notice->publish_date->format('Y-m-d'),
                    'created_by' => $notice->creator ? $notice->creator->name : 'Admin',
                    'created_at' => $notice->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $notice->updated_at->format('Y-m-d H:i:s'),
                ]
            ]);
            
        } catch (Exception $e) {
            // Log the error for debugging
            \Illuminate\Support\Facades\Log::error('Teacher announcement details API error: ' . $e->getMessage(), [
                'user_id' => $request->user() ? $request->user()->id : 'not authenticated',
                'announcement_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching announcement details',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
} 