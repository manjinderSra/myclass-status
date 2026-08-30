<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SectionController extends Controller
{
    /**
     * Display a listing of the sections.
     */
    public function index()
    {
        try {
            // Get current school based on logged-in user
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return redirect()->back()->with('error', 'School not found');
            }
            
            // Get all sections for this school
            $sections = Section::where('school_id', $schoolId)
                ->orderBy('name')
                ->get();
                
            return view('client.schoolPanel.academics.sections', compact('sections'));
        } catch (\Exception $e) {
            Log::error('Error fetching sections: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return redirect()->back()->with('error', 'Failed to fetch sections: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created section in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:10',
                    Rule::unique('sections')->where(function ($query) {
                        return $query->where('school_id', $this->getSchoolId());
                    }),
                ],
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
            
            // Create new section
            $statusValue = (bool)($request->status == 1 || $request->status === true || $request->status === 'true' || $request->status === '1');
            
            Log::info('Status calculated for create:', [
                'raw_status' => $request->status,
                'calculated_status' => $statusValue
            ]);
            
            $section = Section::create([
                'school_id' => $schoolId,
                'name' => $request->name,
                'status' => $statusValue,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Section created successfully',
                'section' => $section
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating section: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified section.
     */
    public function show(Section $section)
    {
        try {
            // Check if section belongs to current school
            if ($section->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to section'
                ], 403);
            }
            
            return response()->json([
                'success' => true,
                'section' => $section
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing section: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified section in storage.
     */
    public function update(Request $request, Section $section)
    {
        try {
            // Debug logging
            Log::info('Section update request:', [
                'request_data' => $request->all(),
                'is_status_checked' => $request->has('status'),
                'status_value' => $request->status,
                'status_type' => gettype($request->status)
            ]);
            
            // Check if section belongs to current school
            if ($section->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to section'
                ], 403);
            }
            
            // Validate request
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:10',
                    Rule::unique('sections')->ignore($section->id)->where(function ($query) {
                        return $query->where('school_id', $this->getSchoolId());
                    }),
                ],
                'status' => 'nullable|boolean',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            // Update section
            $statusValue = (bool)($request->status == 1 || $request->status === true || $request->status === 'true' || $request->status === '1');
            
            Log::info('Status calculated:', [
                'raw_status' => $request->status,
                'calculated_status' => $statusValue
            ]);
            
            $section->update([
                'name' => $request->name,
                'status' => $statusValue,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Section updated successfully',
                'section' => $section
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating section: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified section from storage.
     */
    public function destroy(Section $section)
    {
        try {
            // Check if section belongs to current school
            if ($section->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to section'
                ], 403);
            }
            
            // Check if section is in use (by classes or other entities)
            if ($section->classes()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Section cannot be deleted because it is being used by one or more classes'
                ], 400);
            }
            
            // Delete section
            $section->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Section deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting section: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete section: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get all active sections for the current school.
     */
    public function getActiveSections()
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            // Get all active sections
            $sections = Section::where('school_id', $schoolId)
                ->where('status', true)
                ->orderBy('name')
                ->get();
                
            return response()->json([
                'success' => true,
                'sections' => $sections
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching active sections: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch active sections: ' . $e->getMessage()
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