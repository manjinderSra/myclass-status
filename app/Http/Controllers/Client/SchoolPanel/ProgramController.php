<?php

namespace App\Http\Controllers\Client\SchoolPanel;

use App\Http\Controllers\Controller;
use App\Models\SchoolProgram;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class ProgramController extends Controller
{
    /**
     * Display a listing of the programs.
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




    public function index()
    {
        $school = auth()->user()->school;
        $programs = SchoolProgram::where('school_id', $this->getSchoolId())->latest()->paginate(10);

        return view('client.schoolPanel.programs.index', compact('programs'));
    }

    /**
     * Show the form for creating a new program.
     */
    public function create()
    {
        return view('client.schoolPanel.programs.create');
    }

    /**
     * Store a newly created program in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'coordinator' => 'nullable|string|max:255',
            'coordinator_contact' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'is_featured' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $school = auth()->user()->school;

        $program = new SchoolProgram();
        $program->school_id = $this->getSchoolId();
        $program->title = $request->title;
        $program->description = $request->description;
        $program->coordinator = $request->coordinator;
        $program->coordinator_contact = $request->coordinator_contact;
        $program->status = $request->status;
        $program->is_featured = $request->has('is_featured');
        $program->start_date = $request->start_date;
        $program->end_date = $request->end_date;


        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'program_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/programs', $imageName);
            $program->image_path = str_replace('public/', '', $path);
        }

        $program->save();

        return redirect()->route('school.programs.index')
            ->with('success', 'Program created successfully.');
    }

    /**
     * Display the specified program.
     */
    public function show(SchoolProgram $program)
    {
        // Check if the program belongs to the authenticated user's school
        if ($program->school_id != $this->getSchoolId()) {
            abort(403, 'Unauthorized action.');
        }

        // Load related events
        $program->load('events');

        return view('client.schoolPanel.programs.show', compact('program'));
    }

    /**
     * Show the form for editing the specified program.
     */
    public function edit(SchoolProgram $program)
    {
        // Check if the program belongs to the authenticated user's school
        if ($program->school_id != $this->getSchoolId()) {
            abort(403, 'Unauthorized action.');
        }

        return view('client.schoolPanel.programs.edit', compact('program'));
    }

    /**
     * Update the specified program in storage.
     */
    public function update(Request $request, SchoolProgram $program)
    {
        // Check if the program belongs to the authenticated user's school
        if ($program->school_id != $this->getSchoolId()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'coordinator' => 'nullable|string|max:255',
            'coordinator_contact' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
            'is_featured' => 'nullable|boolean',
        ]);

        $program->title = $request->title;
        $program->description = $request->description;
        $program->coordinator = $request->coordinator;
        $program->coordinator_contact = $request->coordinator_contact;
        $program->status = $request->status;
        $program->is_featured = $request->has('is_featured');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($program->image_path && Storage::exists('public/' . $program->image_path)) {
                Storage::delete('public/' . $program->image_path);
            }

            $image = $request->file('image');
            $imageName = 'program_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/programs', $imageName);
            $program->image_path = str_replace('public/', '', $path);
        }

        $program->save();

        return redirect()->route('school.programs.index')
            ->with('success', 'Program updated successfully.');
    }

    /**
     * Remove the specified program from storage.
     */
    public function destroy(SchoolProgram $program)
    {
        // Check if the program belongs to the authenticated user's school
        if ($program->school_id != $this->getSchoolId()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete program image if exists
        if ($program->image_path && Storage::exists('public/' . $program->image_path)) {
            Storage::delete('public/' . $program->image_path);
        }

        $program->delete();

        return redirect()->route('school.programs.index')
            ->with('success', 'Program deleted successfully.');
    }
}
