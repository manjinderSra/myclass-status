<?php

namespace App\Http\Controllers\Client\SchoolPanel\HelpSupport;

use App\Http\Controllers\Controller;
use App\Models\HelpTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HelpTopicController extends Controller
{
    private function getSchoolId()
    {
        if (Auth::check()) {
            // Get the school from the request or find it by admin_id
            $school = request()->school ?? \App\Models\School::where('admin_id', Auth::id())->first();
            
            if ($school) {
                return $school->id;
            }
        }
        return null;
    }

    /**
     * Display a listing of help topics.
     */
    public function index()
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        // Get help topics for this school
        $helpTopics = HelpTopic::forSchool($schoolId)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($topic) {
                return [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'description' => $topic->description,
                    'status' => $topic->status,
                    'created_at' => $topic->created_at->format('j M Y'),
                    'view_count' => $topic->view_count
                ];
            });
        
        return view('client.schoolPanel.helpSupport.helpTopics.index', [
            'helpTopics' => $helpTopics
        ]);
    }

    /**
     * Show the form for creating a new help topic.
     */
    public function create()
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        $categories = [
            'Getting Started',
            'Teachers',
            'Students',
            'Classes',
            'Attendance',
            'Grading'
        ];
        
        return view('client.schoolPanel.helpSupport.helpTopics.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created help topic.
     */
    public function store(Request $request)
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'description' => 'required|string|max:500',
            'content' => 'required|string',
            'status' => 'required|in:Draft,Published,Archived',
            'icon' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Create a new help topic
        HelpTopic::create([
            'school_id' => $schoolId,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category' => $request->category,
            'description' => $request->description,
            'content' => $request->content,
            'icon' => $request->icon,
            'status' => $request->status,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
        ]);
        
        return redirect()->route('school.helpTopics.index')
            ->with('success', 'Help topic created successfully!');
    }

    /**
     * Show the form for editing a help topic.
     */
    public function edit($id)
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        // Find the help topic
        $topic = HelpTopic::forSchool($schoolId)->findOrFail($id);
        
        $helpTopic = [
            'id' => $topic->id,
            'title' => $topic->title,
            'category' => $topic->category,
            'description' => $topic->description,
            'content' => $topic->content,
            'icon' => $topic->icon,
            'status' => $topic->status,
            'created_at' => $topic->created_at->format('j M Y')
        ];
        
        $categories = [
            'Getting Started',
            'Teachers',
            'Students',
            'Classes',
            'Attendance',
            'Grading'
        ];
        
        return view('client.schoolPanel.helpSupport.helpTopics.edit', [
            'helpTopic' => $helpTopic,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified help topic.
     */
    public function update(Request $request, $id)
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'description' => 'required|string|max:500',
            'content' => 'required|string',
            'status' => 'required|in:Draft,Published,Archived',
            'icon' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Find and update the help topic
        $topic = HelpTopic::forSchool($schoolId)->findOrFail($id);
        
        $topic->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'category' => $request->category,
            'description' => $request->description,
            'content' => $request->content,
            'icon' => $request->icon,
            'status' => $request->status,
            'updated_by' => Auth::id()
        ]);
        
        return redirect()->route('school.helpTopics.index')
            ->with('success', 'Help topic updated successfully!');
    }

    /**
     * Remove the specified help topic.
     */
    public function destroy($id)
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        // Find and delete the help topic
        $topic = HelpTopic::forSchool($schoolId)->findOrFail($id);
        $topic->delete();
        
        return redirect()->route('school.helpTopics.index')
            ->with('success', 'Help topic deleted successfully!');
    }
} 