<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    /**
     * Display a listing of subjects.
     */
    public function index()
    {
        try {
            // Get current school based on logged-in user
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return redirect()->back()->with('error', 'School not found');
            }
            
            // Get all subjects for this school
            $subjects = Subject::where('school_id', $schoolId)
                ->orderBy('name')
                ->get();
                
            // Get active classes for subject assignments
            $classes = SchoolClass::where('school_id', $schoolId)
                ->where('status', true)
                ->orderBy('name')
                ->get();
                
            return view('client.schoolPanel.academics.subjects', compact('subjects', 'classes'));
        } catch (\Exception $e) {
            Log::error('Error fetching subjects: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return redirect()->back()->with('error', 'Failed to fetch subjects: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created subject in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'code' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('subjects')->where(function ($query) {
                        return $query->where('school_id', $this->getSchoolId());
                    }),
                ],
                'description' => 'nullable|string|max:1000',
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
            
            // Create new subject
            $statusValue = (bool)($request->status == 1 || $request->status === true || $request->status === 'true' || $request->status === '1');
            
            $subject = Subject::create([
                'school_id' => $schoolId,
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
                'status' => $statusValue,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Subject created successfully',
                'subject' => $subject
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating subject: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create subject: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified subject.
     */
    public function show(Subject $subject)
    {
        try {
            // Check if subject belongs to current school
            if ($subject->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to subject'
                ], 403);
            }
            
            // Load relationships
            $subject->load('classes');
            
            return response()->json([
                'success' => true,
                'subject' => $subject
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing subject: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch subject: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified subject in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        try {
            // Check if subject belongs to current school
            if ($subject->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to subject'
                ], 403);
            }
            
            // Validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'code' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('subjects')->ignore($subject->id)->where(function ($query) {
                        return $query->where('school_id', $this->getSchoolId());
                    }),
                ],
                'description' => 'nullable|string|max:1000',
                'status' => 'nullable|boolean',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            // Update subject
            $statusValue = (bool)($request->status == 1 || $request->status === true || $request->status === 'true' || $request->status === '1');
            
            $subject->update([
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
                'status' => $statusValue,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Subject updated successfully',
                'subject' => $subject
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating subject: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update subject: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified subject from storage.
     */
    public function destroy(Subject $subject)
    {
        try {
            // Check if subject belongs to current school
            if ($subject->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to subject'
                ], 403);
            }
            
            // Delete subject (will automatically detach from classes and teachers due to pivot table cascades)
            $subject->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Subject deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting subject: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete subject: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get all active subjects for the current school.
     */
    public function getActiveSubjects()
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            // Get all active subjects
            $subjects = Subject::where('school_id', $schoolId)
                ->where('status', true)
                ->orderBy('name')
                ->get();
                
            return response()->json([
                'success' => true,
                'subjects' => $subjects
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching active subjects: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch active subjects: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Assign subjects to classes.
     */
    public function assignToClass(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'subject_id' => 'required|exists:subjects,id',
                'class_ids' => 'required|array',
                'class_ids.*' => 'exists:school_classes,id',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            $schoolId = $this->getSchoolId();
            
            // Check if subject belongs to this school
            $subject = Subject::where('id', $request->subject_id)
                ->where('school_id', $schoolId)
                ->first();
                
            if (!$subject) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subject not found or does not belong to this school'
                ], 404);
            }
            
            // Check if all classes belong to this school
            $classCount = SchoolClass::whereIn('id', $request->class_ids)
                ->where('school_id', $schoolId)
                ->count();
                
            if ($classCount != count($request->class_ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some classes do not belong to this school'
                ], 403);
            }
            
            // Sync the classes
            $subject->classes()->sync($request->class_ids);
            
            return response()->json([
                'success' => true,
                'message' => 'Subject assigned to classes successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error assigning subject to classes: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign subject to classes: ' . $e->getMessage()
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