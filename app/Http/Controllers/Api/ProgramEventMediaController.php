<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolEvent;
use App\Models\SchoolMedia;
use App\Models\SchoolProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProgramEventMediaController extends Controller
{
    /**
     * Get all program images for a school.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function programImages(Request $request)
    {
        try {
            // Get the authenticated user's school_id
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                ], 401);
            }
            
            $schoolId = $user->school_id;
            
            // Get query parameters
            $programId = $request->input('program_id');
            $featured = $request->boolean('featured', false);
            $limit = $request->input('limit', 10);
            
            // Get programs for the school
            $programsQuery = SchoolProgram::where('school_id', $schoolId)
                ->where('status', 'active');
                
            // Filter by specific program if provided
            if ($programId) {
                $programsQuery->where('id', $programId);
            }
            
            // Filter featured programs
            if ($featured) {
                $programsQuery->where('is_featured', true);
            }
            
            $programs = $programsQuery->limit($limit)->get();
            
            $result = [];
            foreach ($programs as $program) {
                // Only include programs with images
                if ($program->image_path) {
                    $result[] = [
                        'id' => $program->id,
                        'title' => $program->title,
                        'description' => $program->description,
                        'coordinator' => $program->coordinator,
                        'image_url' => $program->image_url,
                        'is_featured' => $program->is_featured,
                        'created_at' => $program->created_at->format('Y-m-d H:i:s')
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Program images retrieved successfully',
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error retrieving program images: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving program images',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get all event images for a school.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function eventImages(Request $request)
    {
        try {
            // Get the authenticated user's school_id
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                ], 401);
            }
            
            $schoolId = $user->school_id;
            
            // Get query parameters
            $eventId = $request->input('event_id');
            $programId = $request->input('program_id');
            $featured = $request->boolean('featured', false);
            $status = $request->input('status');
            $limit = $request->input('limit', 10);
            
            // Get events for the school
            $eventsQuery = SchoolEvent::where('school_id', $schoolId);
                
            // Filter by specific event if provided
            if ($eventId) {
                $eventsQuery->where('id', $eventId);
            }
            
            // Filter by program if provided
            if ($programId) {
                $eventsQuery->where('program_id', $programId);
            }
            
            // Filter featured events
            if ($featured) {
                $eventsQuery->where('is_featured', true);
            }
            
            // Filter by status if provided, otherwise include all non-cancelled events
            if ($status) {
                if ($status === 'upcoming') {
                    $eventsQuery->where('status', 'upcoming')
                        ->where('event_date', '>=', now()->toDateString());
                } elseif ($status === 'ongoing') {
                    $eventsQuery->where('status', 'ongoing');
                } elseif ($status === 'completed') {
                    $eventsQuery->where('status', 'completed');
                }
            } else {
                // Default: Include all non-cancelled events
                $eventsQuery->where('status', '!=', 'cancelled');
            }
            
            $events = $eventsQuery->orderBy('event_date', 'asc')
                ->limit($limit)
                ->get();
            
            $result = [];
            foreach ($events as $event) {
                // Include all events, not just those with images
                $result[] = [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'event_date' => $event->event_date->format('Y-m-d'),
                    'location' => $event->location,
                    'image_url' => $event->image_url,
                    'status' => $event->status,
                    'is_featured' => $event->is_featured,
                    'program_id' => $event->program_id,
                    'program_name' => $event->program ? $event->program->title : null,
                ];
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Event images retrieved successfully',
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error retrieving event images: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving event images',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    /**
     * Get a combined gallery of program and event images.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function galleryImages(Request $request)
    {
        try {
            // Get the authenticated user's school_id
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                ], 401);
            }
            
            $schoolId = $user->school_id;
            
            // Get query parameters
            $type = $request->input('type'); // 'program' or 'event'
            $featured = $request->boolean('featured', false);
            $limit = $request->input('limit', 20);
            
            $result = [
                'programs' => [],
                'events' => []
            ];
            
            // If no type specified or type is 'program', get program images
            if (!$type || $type === 'program') {
                $programsQuery = SchoolProgram::where('school_id', $schoolId)
                    ->where('status', 'active')
                    ->whereNotNull('image_path');
                    
                if ($featured) {
                    $programsQuery->where('is_featured', true);
                }
                
                $programs = $programsQuery->limit($limit)->get();
                
                foreach ($programs as $program) {
                    $result['programs'][] = [
                        'id' => $program->id,
                        'title' => $program->title,
                        'type' => 'program',
                        'image_url' => $program->image_url,
                        'is_featured' => $program->is_featured
                    ];
                }
            }
            
            // If no type specified or type is 'event', get event images
            if (!$type || $type === 'event') {
                $eventsQuery = SchoolEvent::where('school_id', $schoolId)
                    ->where('status', '!=', 'cancelled')
                    ->whereNotNull('image_path');
                    
                if ($featured) {
                    $eventsQuery->where('is_featured', true);
                }
                
                $events = $eventsQuery->orderBy('event_date', 'asc')
                    ->limit($limit)
                    ->get();
                
                foreach ($events as $event) {
                    $result['events'][] = [
                        'id' => $event->id,
                        'title' => $event->title,
                        'type' => 'event',
                        'event_date' => $event->event_date->format('Y-m-d'),
                        'image_url' => $event->image_url,
                        'status' => $event->status,
                        'is_featured' => $event->is_featured
                    ];
                }
            }
            
            // Merge and sort by featured status
            $allImages = array_merge($result['programs'], $result['events']);
            usort($allImages, function($a, $b) {
                if ($a['is_featured'] === $b['is_featured']) {
                    return 0;
                }
                return ($a['is_featured'] > $b['is_featured']) ? -1 : 1;
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Gallery images retrieved successfully',
                'data' => [
                    'all' => $allImages,
                    'programs' => $result['programs'],
                    'events' => $result['events']
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error retrieving gallery images: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving gallery images',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Debug method to help troubleshoot event images API issues.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function debugEventImages(Request $request)
    {
        try {
            // Get the authenticated user's school_id
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                ], 401);
            }
            
            $schoolId = $user->school_id;
            
            // Get query parameters
            $eventId = $request->input('event_id');
            $programId = $request->input('program_id');
            $featured = $request->boolean('featured', false);
            $status = $request->input('status', 'upcoming');
            $limit = $request->input('limit', 10);
            
            // Get events for the school
            $eventsQuery = SchoolEvent::where('school_id', $schoolId);
            
            // Get all events regardless of status for debugging
            $allEvents = SchoolEvent::where('school_id', $schoolId)->get();
            
            // Count events with images
            $eventsWithImages = SchoolEvent::where('school_id', $schoolId)
                ->whereNotNull('image_path')
                ->count();
            
            // Get sample events with different statuses
            $upcomingEvents = SchoolEvent::where('school_id', $schoolId)
                ->where('status', 'upcoming')
                ->count();
                
            $ongoingEvents = SchoolEvent::where('school_id', $schoolId)
                ->where('status', 'ongoing')
                ->count();
                
            $completedEvents = SchoolEvent::where('school_id', $schoolId)
                ->where('status', 'completed')
                ->count();
                
            $cancelledEvents = SchoolEvent::where('school_id', $schoolId)
                ->where('status', 'cancelled')
                ->count();
            
            return response()->json([
                'success' => true,
                'debug_info' => [
                    'user_id' => $user->id,
                    'school_id' => $schoolId,
                    'total_events' => $allEvents->count(),
                    'events_with_images' => $eventsWithImages,
                    'events_by_status' => [
                        'upcoming' => $upcomingEvents,
                        'ongoing' => $ongoingEvents,
                        'completed' => $completedEvents,
                        'cancelled' => $cancelledEvents
                    ],
                    'query_parameters' => [
                        'event_id' => $eventId,
                        'program_id' => $programId,
                        'featured' => $featured,
                        'status' => $status,
                        'limit' => $limit
                    ],
                    'sample_events' => $allEvents->take(3)->map(function($event) {
                        return [
                            'id' => $event->id,
                            'title' => $event->title,
                            'status' => $event->status,
                            'event_date' => $event->event_date ? $event->event_date->format('Y-m-d') : null,
                            'has_image' => !empty($event->image_path),
                            'image_path' => $event->image_path,
                            'image_url' => $event->image_url
                        ];
                    })
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in debug event images: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while debugging event images',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
} 