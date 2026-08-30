<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolMedia;
use Illuminate\Support\Facades\Log;

class SchoolMediaController extends Controller
{
    /**
     * Get all school media items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            // Get the authenticated student's school_id
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                ], 401);
            }
            
            $schoolId = $user->school_id;
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User does not have an associated school',
                ], 400);
            }
            
            // Get query parameters
            $type = $request->input('type'); // 'photo' or 'video'
            $category = $request->input('category');
            $featured = $request->boolean('featured', false);
            $perPage = $request->input('per_page', 10);
            
            // Build query
            $query = SchoolMedia::where('school_id', $schoolId)
                ->where('status', 'active');
                
            // Apply filters
            if ($type) {
                $query->where('type', $type);
            }
            
            if ($category) {
                $query->where('category', $category);
            }
            
            if ($featured) {
                $query->where('is_featured', true);
            }
            
            // Get results
            $media = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);
                
            // Transform data to include full URLs
            $media->getCollection()->transform(function ($item) {
                $item->file_url = $item->file_url;
                $item->thumbnail_url = $item->thumbnail_url;
                return $item;
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Media retrieved successfully',
                'data' => $media
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error retrieving school media: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving media',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get a specific media item by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
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
            
            // Find the media item
            $media = SchoolMedia::where('school_id', $schoolId)
                ->where('status', 'active')
                ->findOrFail($id);
                
            // Add URLs
            $media->file_url = $media->file_url;
            $media->thumbnail_url = $media->thumbnail_url;
            
            return response()->json([
                'success' => true,
                'message' => 'Media retrieved successfully',
                'data' => $media
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error retrieving media item: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException 
                    ? 'Media not found' 
                    : 'An error occurred while retrieving the media',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }
    
    /**
     * Get all photos for the school.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function photos(Request $request)
    {
        $request->merge(['type' => 'photo']);
        return $this->index($request);
    }
    
    /**
     * Get all videos for the school.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function videos(Request $request)
    {
        $request->merge(['type' => 'video']);
        return $this->index($request);
    }
    
    /**
     * Get featured media for the school.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function featured(Request $request)
    {
        $request->merge(['featured' => true]);
        return $this->index($request);
    }
    
    /**
     * Get available media categories for the school.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories()
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
            
            // Get distinct categories
            $categories = SchoolMedia::where('school_id', $schoolId)
                ->where('status', 'active')
                ->select('category')
                ->distinct()
                ->pluck('category');
                
            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $categories
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error retrieving media categories: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get media related to programs for the authenticated user's school.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function programMedia(Request $request)
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
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User does not have an associated school',
                ], 400);
            }
            
            // Get program ID if provided
            $programId = $request->input('program_id');
            $perPage = $request->input('per_page', 10);
            
            // Get programs for the school
            $programsQuery = \App\Models\SchoolProgram::where('school_id', $schoolId)
                ->where('status', 'active');
                
            // Filter by specific program if provided
            if ($programId) {
                $programsQuery->where('id', $programId);
            }
            
            $programs = $programsQuery->get();
            
            $result = [];
            foreach ($programs as $program) {
                $programData = [
                    'id' => $program->id,
                    'title' => $program->title,
                    'description' => $program->description,
                    'image_url' => $program->image_url,
                    'media' => []
                ];
                
                // Get media related to this program (based on category matching program title)
                $media = SchoolMedia::where('school_id', $schoolId)
                    ->where('status', 'active')
                    ->where(function($query) use ($program) {
                        $query->where('category', 'program_' . $program->id)
                            ->orWhere('category', 'program')
                            ->orWhere('category', 'programs')
                            ->orWhere('category', $program->title);
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                foreach ($media as $item) {
                    $programData['media'][] = [
                        'id' => $item->id,
                        'title' => $item->title,
                        'description' => $item->description,
                        'type' => $item->type,
                        'file_url' => $item->file_url,
                        'thumbnail_url' => $item->thumbnail_url,
                        'created_at' => $item->created_at->format('Y-m-d H:i:s')
                    ];
                }
                
                $result[] = $programData;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Program media retrieved successfully',
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error retrieving program media: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving program media',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get media related to events for the authenticated user's school.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function eventMedia(Request $request)
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
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User does not have an associated school',
                ], 400);
            }
            
            // Get event ID if provided
            $eventId = $request->input('event_id');
            $perPage = $request->input('per_page', 10);
            
            // Get events for the school
            $eventsQuery = \App\Models\SchoolEvent::where('school_id', $schoolId)
                ->where('status', '!=', 'cancelled');
                
            // Filter by specific event if provided
            if ($eventId) {
                $eventsQuery->where('id', $eventId);
            }
            
            $events = $eventsQuery->get();
            
            $result = [];
            foreach ($events as $event) {
                $eventData = [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'event_date' => $event->event_date->format('Y-m-d'),
                    'image_url' => $event->image_url,
                    'media' => []
                ];
                
                // Get media related to this event (based on category matching event title or ID)
                $media = SchoolMedia::where('school_id', $schoolId)
                    ->where('status', 'active')
                    ->where(function($query) use ($event) {
                        $query->where('category', 'event_' . $event->id)
                            ->orWhere('category', 'event')
                            ->orWhere('category', 'events')
                            ->orWhere('category', $event->title);
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
                foreach ($media as $item) {
                    $eventData['media'][] = [
                        'id' => $item->id,
                        'title' => $item->title,
                        'description' => $item->description,
                        'type' => $item->type,
                        'file_url' => $item->file_url,
                        'thumbnail_url' => $item->thumbnail_url,
                        'created_at' => $item->created_at->format('Y-m-d H:i:s')
                    ];
                }
                
                $result[] = $eventData;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Event media retrieved successfully',
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error retrieving event media: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving event media',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get program and event gallery for the authenticated user's school.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function gallery(Request $request)
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
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User does not have an associated school',
                ], 400);
            }
            
            // Get query parameters
            $type = $request->input('type'); // 'photo' or 'video'
            $category = $request->input('category');
            $featured = $request->boolean('featured', false);
            $perPage = $request->input('per_page', 20);
            
            // Get all media related to programs and events
            $query = SchoolMedia::where('school_id', $schoolId)
                ->where('status', 'active')
                ->where(function($query) {
                    $query->where('category', 'like', 'program%')
                        ->orWhere('category', 'like', 'event%')
                        ->orWhere('category', 'programs')
                        ->orWhere('category', 'events');
                });
                
            // Apply filters
            if ($type) {
                $query->where('type', $type);
            }
            
            if ($category) {
                $query->where('category', $category);
            }
            
            if ($featured) {
                $query->where('is_featured', true);
            }
            
            // Get results
            $media = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);
                
            // Transform data to include full URLs
            $media->getCollection()->transform(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'type' => $item->type,
                    'category' => $item->category,
                    'file_url' => $item->file_url,
                    'thumbnail_url' => $item->thumbnail_url,
                    'is_featured' => $item->is_featured,
                    'created_at' => $item->created_at->format('Y-m-d H:i:s')
                ];
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Gallery retrieved successfully',
                'data' => $media
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error retrieving gallery: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the gallery',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 