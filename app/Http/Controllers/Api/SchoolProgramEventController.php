<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolEvent;
use App\Models\SchoolProgram;
use Illuminate\Http\Request;

class SchoolProgramEventController extends Controller
{
    /**
     * Get all active programs for a school.
     */
    public function getPrograms(Request $request, $schoolId)
    {
        $school = School::findOrFail($schoolId);
        
        $programs = $school->programs()
            ->active()
            ->when($request->has('featured') && $request->featured == 1, function($query) {
                return $query->featured();
            })
            ->latest()
            ->get()
            ->map(function($program) {
                return [
                    'id' => $program->id,
                    'title' => $program->title,
                    'description' => $program->description,
                    'coordinator' => $program->coordinator,
                    'coordinator_contact' => $program->coordinator_contact,
                    'image_url' => $program->image_url,
                    'is_featured' => $program->is_featured,
                    'created_at' => $program->created_at->format('Y-m-d H:i:s'),
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $programs,
        ]);
    }
    
    /**
     * Get a specific program with its events.
     */
    public function getProgramDetails($schoolId, $programId)
    {
        $school = School::findOrFail($schoolId);
        
        $program = $school->programs()
            ->where('id', $programId)
            ->firstOrFail();
        
        $events = $program->events()
            ->where('status', '!=', 'cancelled')
            ->orderBy('event_date', 'asc')
            ->get()
            ->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'event_date' => $event->event_date,
                    'start_time' => $event->start_time,
                    'end_time' => $event->end_time ?? null,
                    'location' => $event->location,
                    'organizer' => $event->organizer,
                    'image_url' => $event->image_url,
                    'status' => $event->status,
                    'is_featured' => $event->is_featured,
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => [
                'program' => [
                    'id' => $program->id,
                    'title' => $program->title,
                    'description' => $program->description,
                    'coordinator' => $program->coordinator,
                    'coordinator_contact' => $program->coordinator_contact,
                    'image_url' => $program->image_url,
                    'is_featured' => $program->is_featured,
                    'created_at' => $program->created_at->format('Y-m-d H:i:s'),
                ],
                'events' => $events,
            ],
        ]);
    }
    
    /**
     * Get all events for a school.
     */
    public function getEvents(Request $request, $schoolId)
    {
        $school = School::findOrFail($schoolId);
        
        $query = $school->events();
        
        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'upcoming') {
                $query->upcoming();
            } elseif ($request->status === 'ongoing') {
                $query->ongoing();
            } elseif ($request->status === 'completed') {
                $query->completed();
            } elseif ($request->status === 'cancelled') {
                $query->where('status', 'cancelled');
            }
        } else {
            // Default to upcoming and ongoing events
            $query->where(function($q) {
                $q->where('status', 'upcoming')
                  ->orWhere('status', 'ongoing');
            })->where('event_date', '>=', now()->toDateString());
        }
        
        // Filter by program if provided
        if ($request->has('program_id') && $request->program_id) {
            $query->where('program_id', $request->program_id);
        }
        
        // Filter featured events
        if ($request->has('featured') && $request->featured == 1) {
            $query->featured();
        }
        
        $events = $query->orderBy('event_date', 'asc')
            ->get()
            ->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'event_date' => $event->event_date->format('Y-m-d'),
                    'start_time' => $event->start_time->format('H:i'),
                    'end_time' => $event->end_time ? $event->end_time->format('H:i') : null,
                    'location' => $event->location,
                    'organizer' => $event->organizer,
                    'image_url' => $event->image_url,
                    'status' => $event->status,
                    'is_featured' => $event->is_featured,
                    'program_id' => $event->program_id,
                    'program_name' => $event->program ? $event->program->title : null,
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $events,
        ]);
    }
    
    /**
     * Get a specific event details.
     */
    public function getEventDetails($schoolId, $eventId)
    {
        $school = School::findOrFail($schoolId);
        
        $event = $school->events()
            ->where('id', $eventId)
            ->with('program')
            ->firstOrFail();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'event_date' => $event->event_date->format('Y-m-d'),
                'start_time' => $event->start_time->format('H:i'),
                'end_time' => $event->end_time ? $event->end_time->format('H:i') : null,
                'location' => $event->location,
                'organizer' => $event->organizer,
                'image_url' => $event->image_url,
                'status' => $event->status,
                'is_featured' => $event->is_featured,
                'program' => $event->program ? [
                    'id' => $event->program->id,
                    'title' => $event->program->title,
                ] : null,
            ],
        ]);
    }
}
