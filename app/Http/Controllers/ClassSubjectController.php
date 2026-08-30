<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ClassSubjectController extends Controller
{
    /**
     * Display a listing of class-subject assignments.
     */
    public function index()
    {
        try {
            // Get current school based on logged-in user
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return redirect()->back()->with('error', 'School not found');
            }
            
            // Get all classes for this school
            $classes = SchoolClass::where('school_id', $schoolId)
                ->with(['section', 'subjects'])
                ->where('status', true)
                ->orderBy('name')
                ->get();
                
            // Get active subjects for subject assignment dropdown
            $subjects = Subject::where('school_id', $schoolId)
                ->where('status', true)
                ->orderBy('name')
                ->get();
                
            // Get active sections for dropdown
            $sections = Section::where('school_id', $schoolId)
                ->where('status', true)
                ->orderBy('name')
                ->get();
            
            // Transform data for easy display in the view
            $assignments = [];
            foreach ($classes as $class) {
                if (count($class->subjects) > 0) {
                    $assignments[] = [
                        'id' => $class->id,
                        'class_name' => $class->name,
                        'section_name' => $class->section ? $class->section->name : 'N/A',
                        'section_id' => $class->section_id,
                        'subjects' => $class->subjects,
                        'created_at' => $class->created_at
                    ];
                }
            }
            
            return view('client.schoolPanel.academics.assignSubjects', compact('assignments', 'classes', 'subjects', 'sections'));
        } catch (\Exception $e) {
            Log::error('Error fetching class-subject assignments: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return redirect()->back()->with('error', 'Failed to fetch class-subject assignments: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created assignment in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'class_id' => 'required|exists:school_classes,id',
                'subject_ids' => 'required|array',
                'subject_ids.*' => 'exists:subjects,id',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            // Check if class belongs to this school
            $class = SchoolClass::where('id', $request->class_id)
                ->where('school_id', $schoolId)
                ->first();
                
            if (!$class) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class not found or does not belong to this school'
                ], 404);
            }
            
            // Check if subjects belong to this school
            $subjectCount = Subject::whereIn('id', $request->subject_ids)
                ->where('school_id', $schoolId)
                ->count();
                
            if ($subjectCount != count($request->subject_ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some subjects do not belong to this school'
                ], 403);
            }
            
            // Attach the subjects to the class
            $class->subjects()->syncWithoutDetaching($request->subject_ids);
            
            // Get the updated class with subjects
            $class->load(['section', 'subjects']);
            
            return response()->json([
                'success' => true,
                'message' => 'Subjects assigned to class successfully',
                'assignment' => [
                    'id' => $class->id,
                    'class_name' => $class->name,
                    'section_name' => $class->section ? $class->section->name : 'N/A',
                    'section_id' => $class->section_id,
                    'subjects' => $class->subjects,
                    'created_at' => $class->created_at
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error assigning subjects to class: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign subjects to class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the class-subject assignments.
     */
    public function update(Request $request, $classId)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'subject_ids' => 'required|array',
                'subject_ids.*' => 'exists:subjects,id',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            $schoolId = $this->getSchoolId();
            
            // Check if class belongs to this school
            $class = SchoolClass::where('id', $classId)
                ->where('school_id', $schoolId)
                ->first();
                
            if (!$class) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class not found or does not belong to this school'
                ], 404);
            }
            
            // Check if subjects belong to this school
            $subjectCount = Subject::whereIn('id', $request->subject_ids)
                ->where('school_id', $schoolId)
                ->count();
                
            if ($subjectCount != count($request->subject_ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some subjects do not belong to this school'
                ], 403);
            }
            
            // Sync the subjects (this will replace existing assignments)
            $class->subjects()->sync($request->subject_ids);
            
            // Get the updated class with subjects
            $class->load(['section', 'subjects']);
            
            return response()->json([
                'success' => true,
                'message' => 'Subject assignments updated successfully',
                'assignment' => [
                    'id' => $class->id,
                    'class_name' => $class->name,
                    'section_name' => $class->section ? $class->section->name : 'N/A',
                    'section_id' => $class->section_id,
                    'subjects' => $class->subjects,
                    'created_at' => $class->created_at
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating subject assignments: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update subject assignments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete all subject assignments for a class.
     */
    public function destroy($classId)
    {
        try {
            $schoolId = $this->getSchoolId();
            
            // Check if class belongs to this school
            $class = SchoolClass::where('id', $classId)
                ->where('school_id', $schoolId)
                ->first();
                
            if (!$class) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class not found or does not belong to this school'
                ], 404);
            }
            
            // Detach all subjects from this class
            $class->subjects()->detach();
            
            return response()->json([
                'success' => true,
                'message' => 'Subject assignments removed successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing subject assignments: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove subject assignments: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get subjects assigned to a specific class.
     */
    public function getClassSubjects($classId)
    {
        try {
            $schoolId = $this->getSchoolId();
            
            // Check if class belongs to this school
            $class = SchoolClass::where('id', $classId)
                ->where('school_id', $schoolId)
                ->with(['section', 'subjects'])
                ->first();
                
            if (!$class) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class not found or does not belong to this school'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'class_name' => $class->name,
                'section_name' => $class->section ? $class->section->name : 'N/A',
                'subjects' => $class->subjects
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching class subjects: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch class subjects: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Helper method to get current school ID.
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
} 