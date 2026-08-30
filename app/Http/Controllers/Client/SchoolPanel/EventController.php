<?php

namespace App\Http\Controllers\Client\SchoolPanel;

use App\Http\Controllers\Controller;
use App\Models\SchoolEvent;
use App\Models\SchoolProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
     
       private function getSchoolId()
    {
        $user = Auth::user();
        $schoolId = null;
        
        if ($user->role === 'school') {
            $school = School::where('admin_id', $user->id)->first();
            if ($school) {
                $schoolId = $school->id;
            }
        } else if ($user->school_id) {
            $schoolId = $user->school_id;
        }
        
        return $schoolId;
    }
     
     
    public function index(Request $request)
    {
        $school = $this->getSchoolId();
        
        $query =SchoolEvent::where('school_id',$this->getSchoolId());
        
        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by program if provided
        if ($request->has('program_id') && $request->program_id) {
            $query->where('program_id', $request->program_id);
        }
        
        $events = $query->latest()->paginate(10);
        $programs = SchoolProgram::where('school_id',$this->getSchoolId())->where('status', 'active')->get();
        
        return view('client.schoolPanel.events.index', compact('events', 'programs'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $school = $this->getSchoolId();
        $programs = SchoolProgram::where('school_id',$this->getSchoolId())->where('status', 'active')->get();
        
        return view('client.schoolPanel.events.create', compact('programs'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable|after:start_time',
            'location' => 'required|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'program_id' => 'nullable|exists:school_programs,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'is_featured' => 'nullable|boolean',
        ]);

        $school = $this->getSchoolId();
        
        // Verify the program belongs to this school if provided
        if ($request->program_id) {
            $program = SchoolProgram::find($request->program_id);
            if ($program->school_id !== $school) {
                return back()->with('error', 'Invalid program selected.');
            }
        }
        
        $event = new SchoolEvent();
        $event->school_id = $school;
        $event->program_id = $request->program_id;
        $event->title = $request->title;
        $event->description = $request->description;
        $event->event_date = $request->event_date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->location = $request->location;
        $event->organizer = $request->organizer;
        $event->status = $request->status;
        $event->is_featured = $request->has('is_featured');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'event_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/events', $imageName);
            $event->image_path = str_replace('public/', '', $path);
        }
        
        $event->save();
        
        return redirect()->route('school.events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified event.
     */
    public function show(SchoolEvent $event)
    {
        // Check if the event belongs to the authenticated user's school
        if ($event->school_id != $this->getSchoolId()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Load the program if associated
        if ($event->program_id) {
            $event->load('program');
        }
        
        return view('client.schoolPanel.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(SchoolEvent $event)
    {
        // Check if the event belongs to the authenticated user's school
        if ($event->school_id != $this->getSchoolId()) {
            abort(403, 'Unauthorized action.');
        }
        
     
        $programs = SchoolProgram::where('school_id',$this->getSchoolId())->where('status', 'active')->get();
        
        return view('client.schoolPanel.events.edit', compact('event', 'programs'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, SchoolEvent $event)
    {
        // Check if the event belongs to the authenticated user's school
        if ($event->school_id != $this->getSchoolId()) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'nullable|after:start_time',
            'location' => 'required|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'program_id' => 'nullable|exists:school_programs,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'is_featured' => 'nullable|boolean',
        ]);
        
        // Verify the program belongs to this school if provided
        if ($request->program_id) {
            $program = SchoolProgram::find($request->program_id);
            if ($program->school_id != $this->getSchoolId()) {
                return back()->with('error', 'Invalid program selected.');
            }
        }
        
        $event->program_id = $request->program_id;
        $event->title = $request->title;
        $event->description = $request->description;
        $event->event_date = $request->event_date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->location = $request->location;
        $event->organizer = $request->organizer;
        $event->status = $request->status;
        $event->is_featured = $request->has('is_featured');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image_path && Storage::exists('public/' . $event->image_path)) {
                Storage::delete('public/' . $event->image_path);
            }
            
            $image = $request->file('image');
            $imageName = 'event_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/events', $imageName);
            $event->image_path = str_replace('public/', '', $path);
        }
        
        $event->save();
        
        return redirect()->route('school.events.index')
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(SchoolEvent $event)
    {
        // Check if the event belongs to the authenticated user's school
        if ($event->school_id !== $this->getSchoolId()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete event image if exists
        if ($event->image_path && Storage::exists('public/' . $event->image_path)) {
            Storage::delete('public/' . $event->image_path);
        }
        
        $event->delete();
        
        return redirect()->route('school.events.index')
            ->with('success', 'Event deleted successfully.');
    }
}
