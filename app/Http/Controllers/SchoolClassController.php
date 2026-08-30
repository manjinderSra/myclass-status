<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\School;
use App\Models\Student;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the classes.
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
                ->with('section')
                ->orderBy('name')
                ->get();
                
            // Get active sections for dropdown
            $sections = Section::where('school_id', $schoolId)
                ->where('status', true)
                ->orderBy('name')
                ->get();
                
            return view('client.schoolPanel.academics.class', compact('classes', 'sections'));
        } catch (\Exception $e) {
            Log::error('Error fetching classes: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return redirect()->back()->with('error', 'Failed to fetch classes: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created class in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('school_classes')->where(function ($query) use ($request) {
                        return $query->where('school_id', $this->getSchoolId())
                                     ->where('section_id', $request->section_id);
                    }),
                ],
                'total_capacity' => 'required|integer|min:1|max:999',
                'section_id' => 'nullable|exists:sections,id',
                'status' => 'nullable|boolean',
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
            
            // If section_id is provided, check if it belongs to the same school
            if ($request->section_id) {
                $section = Section::find($request->section_id);
                if (!$section || $section->school_id != $schoolId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid section selected'
                    ], 400);
                }
            }
            
            // Create new class
            $statusValue = (bool)($request->status == 1 || $request->status === true || $request->status === 'true' || $request->status === '1');
            
            $class = SchoolClass::create([
                'school_id' => $schoolId,
                'name' => $request->name,
                'total_capacity' => $request->total_capacity,
                'section_id' => $request->section_id,
                'status' => $statusValue,
            ]);
            
            // Load section relationship for response
            if ($class->section_id) {
                $class->load('section');
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Class created successfully',
                'class' => $class
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating class: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified class.
     */
    public function show(SchoolClass $class)
    {
        // dd($class);
        try {
            // Check if class belongs to current school
            if ($class->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to class'
                ], 403);
            }
            
            // Load section relationship
            $class->load('section');
            
            return response()->json([
                'success' => true,
                'class' => $class
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing class: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified class in storage.
     */
    public function update(Request $request, SchoolClass $class)
    {
        try {
            // Check if class belongs to current school
            if ($class->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to class'
                ], 403);
            }
            
            // Validate request
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('school_classes')->ignore($class->id)->where(function ($query) use ($request) {
                        return $query->where('school_id', $this->getSchoolId())
                                     ->where('section_id', $request->section_id);
                    }),
                ],
                'total_capacity' => 'required|integer|min:1|max:999',
                'section_id' => 'nullable|exists:sections,id',
                'status' => 'nullable|boolean',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            // If section_id is provided, check if it belongs to the same school
            if ($request->section_id) {
                $section = Section::find($request->section_id);
                if (!$section || $section->school_id != $this->getSchoolId()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid section selected'
                    ], 400);
                }
            }
            
            // Update class
            $statusValue = (bool)($request->status == 1 || $request->status === true || $request->status === 'true' || $request->status === '1');
            
            $class->update([
                'name' => $request->name,
                'total_capacity' => $request->total_capacity,
                'section_id' => $request->section_id,
                'status' => $statusValue,
            ]);
            
            // Load section relationship for response
            if ($class->section_id) {
                $class->load('section');
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Class updated successfully',
                'class' => $class
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating class: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified class from storage.
     */
    public function destroy(SchoolClass $class)
    {
        try {
            // Check if class belongs to current school
            if ($class->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to class'
                ], 403);
            }
            
            // TODO: Check if class is in use by students or other entities
            // For now, just allow deletion
            
            // Delete class
            $class->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Class deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting class: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete class: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get all active classes for the current school.
     */
    public function getActiveClasses()
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            // Get all active classes
            $classes = SchoolClass::where('school_id', $schoolId)
                ->where('status', true)
                ->with('section')
                ->orderBy('name')
                ->get();
                
            return response()->json([
                'success' => true,
                'classes' => $classes
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching active classes: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch active classes: ' . $e->getMessage()
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
    
    public function showStudents($school_id, $class_id, $section_id)
    {
        // Fetch the class with validation
        $class = SchoolClass::where('school_id', $school_id)
            ->where('id', $class_id)
            ->where('section_id', $section_id)
            ->firstOrFail();

        // Fetch students in this class + section
        $students = Student::where('school_id', $school_id)
            ->where('class_id', $class_id)
            ->where('section_id', $section_id)
            ->orderByRaw('CAST(roll_number AS UNSIGNED)')
            // ->orderBy('last_name')
            ->get();

        // Return view with students
        return view('client.schoolPanel.academics.students', [
            'class' => $class,
            'students' => $students
        ]);
    }
    
    
} 