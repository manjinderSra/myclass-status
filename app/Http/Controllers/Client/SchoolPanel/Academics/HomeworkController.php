<?php

namespace App\Http\Controllers\Client\SchoolPanel\Academics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Homework;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomeworkController extends Controller
{
    /**
     * Display the homework page with listing of homework
     */
    public function index()
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        // Fetch homework data directly in the controller
        try {
            $homeworkData = Homework::where('school_id', $schoolId)
                ->with(['section', 'subject'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'class_name' => $item->class_name,
                        'section' => $item->section ? $item->section->name : 'Unknown',
                        'subject' => $item->subject ? $item->subject->name : 'Unknown',
                        'homework_date' => $item->homework_date->format('Y-m-d'),
                        'submission_date' => $item->submission_date->format('Y-m-d'),
                        'description' => $item->description,
                        'image_path' => $item->image_path ? asset('storage/' . $item->image_path) : null,
                        'created_at' => $item->created_at->format('Y-m-d H:i:s')
                    ];
                });
            
            // Get classes for filter dropdowns
            $classes = SchoolClass::where('school_id', $schoolId)
                ->with('section')
                ->get();
                
            // Get subjects for filter dropdowns
            $subjects = Subject::where('school_id', $schoolId)->get();
            
            return view('client.schoolPanel.academics.homeWork', [
                'homeworkData' => $homeworkData,
                'classes' => $classes,
                'subjects' => $subjects
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching homework data: ' . $e->getMessage());
            return view('client.schoolPanel.academics.homeWork')->with('error', 'Error loading homework data');
        }
    }
    
    /**
     * Store a new homework in the database
     */
    public function store(Request $request)
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return response()->json([
                'success' => false,
                'message' => 'School not found'
            ], 404);
        }
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'class' => 'required|string',
            'section' => 'required|exists:sections,id',
            'subject' => 'required|exists:subjects,id',
            'homework_date' => 'required|date',
            'submission_date' => 'required|date|after_or_equal:homework_date',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            // Upload image if provided
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('homework_images', $imageName, 'public');
            }
            
            // Create new homework
            $homework = new Homework();
            $homework->school_id = $schoolId;
            $homework->class_name = $request->class;
            $homework->section_id = $request->section;
            $homework->subject_id = $request->subject;
            $homework->homework_date = $request->homework_date;
            $homework->submission_date = $request->submission_date;
            $homework->description = $request->description;
            $homework->image_path = $imagePath;
            $homework->created_by = Auth::id();
            $homework->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Homework added successfully',
                'homework' => $homework
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded image if exists
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            
            Log::error('Error adding homework: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding homework: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get homework entries with filters
     */
    public function filter(Request $request)
    {
        Log::info('Filter method called with request data: ', $request->all());
        
        $schoolId = $this->getSchoolId();
        Log::info('School ID: ' . $schoolId);
        
        if (!$schoolId) {
            Log::warning('School not found for filter request');
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        try {
            $query = Homework::where('school_id', $schoolId);
            
            // Apply filters if provided
            if ($request->has('filterClass') && $request->filterClass) {
                Log::info('Filtering by class: ' . $request->filterClass);
                $query->where('class_name', $request->filterClass);
            }
            
            if ($request->has('filterSection') && $request->filterSection) {
                Log::info('Filtering by section: ' . $request->filterSection);
                $query->where('section_id', $request->filterSection);
            }
            
            if ($request->has('filterSubject') && $request->filterSubject) {
                Log::info('Filtering by subject: ' . $request->filterSubject);
                $query->where('subject_id', $request->filterSubject);
            }
            
            if ($request->has('filterHomeworkDate') && $request->filterHomeworkDate) {
                Log::info('Filtering by date: ' . $request->filterHomeworkDate);
                $query->whereDate('homework_date', $request->filterHomeworkDate);
            }
            
            // Get homework with related models
            $homeworkData = $query->with(['section', 'subject'])
                ->orderBy('created_at', 'desc')
                ->get();
                
            Log::info('Found ' . $homeworkData->count() . ' homework entries after filtering');
            
            $homeworkData = $homeworkData->map(function($item) {
                return [
                    'id' => $item->id,
                    'class_name' => $item->class_name,
                    'section' => $item->section ? $item->section->name : 'Unknown',
                    'subject' => $item->subject ? $item->subject->name : 'Unknown',
                    'homework_date' => $item->homework_date->format('Y-m-d'),
                    'submission_date' => $item->submission_date->format('Y-m-d'),
                    'description' => $item->description,
                    'image_path' => $item->image_path ? asset('storage/' . $item->image_path) : null,
                    'created_at' => $item->created_at->format('Y-m-d H:i:s')
                ];
            });
            
            // Get classes for filter dropdowns
            $classes = SchoolClass::where('school_id', $schoolId)
                ->with('section')
                ->get();
                
            // Get subjects for filter dropdowns
            $subjects = Subject::where('school_id', $schoolId)->get();
            
            return view('client.schoolPanel.academics.homeWork', [
                'homeworkData' => $homeworkData,
                'classes' => $classes,
                'subjects' => $subjects,
                'filters' => $request->all()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching filtered homework: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', 'An error occurred while filtering homework: ' . $e->getMessage());
        }
    }
    
    /**
     * Update an existing homework
     */
    public function update(Request $request, $id)
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return response()->json([
                'success' => false,
                'message' => 'School not found'
            ], 404);
        }
        
        // Find the homework
        $homework = Homework::where('school_id', $schoolId)
            ->where('id', $id)
            ->first();
        
        if (!$homework) {
            return response()->json([
                'success' => false,
                'message' => 'Homework not found'
            ], 404);
        }
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'class' => 'required|string',
            'section' => 'required|exists:sections,id',
            'subject' => 'required|exists:subjects,id',
            'homework_date' => 'required|date',
            'submission_date' => 'required|date|after_or_equal:homework_date',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            // Handle image update if provided
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($homework->image_path && Storage::disk('public')->exists($homework->image_path)) {
                    Storage::disk('public')->delete($homework->image_path);
                }
                
                // Upload new image
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('homework_images', $imageName, 'public');
                $homework->image_path = $imagePath;
            }
            
            // Update homework details
            $homework->class_name = $request->class;
            $homework->section_id = $request->section;
            $homework->subject_id = $request->subject;
            $homework->homework_date = $request->homework_date;
            $homework->submission_date = $request->submission_date;
            $homework->description = $request->description;
            $homework->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Homework updated successfully',
                'homework' => $homework
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating homework: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating homework'
            ], 500);
        }
    }
    
    /**
     * Delete a homework entry
     */
    public function destroy($id)
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return response()->json([
                'success' => false,
                'message' => 'School not found'
            ], 404);
        }
        
        // Find the homework
        $homework = Homework::where('school_id', $schoolId)
            ->where('id', $id)
            ->first();
        
        if (!$homework) {
            return response()->json([
                'success' => false,
                'message' => 'Homework not found'
            ], 404);
        }
        
        try {
            // Delete image if exists
            if ($homework->image_path && Storage::disk('public')->exists($homework->image_path)) {
                Storage::disk('public')->delete($homework->image_path);
            }
            
            // Delete homework
            $homework->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Homework deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting homework: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting homework'
            ], 500);
        }
    }
    
    /**
     * Get a single homework item for editing
     */
    public function show($id)
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        // Find the homework
        $homework = Homework::where('school_id', $schoolId)
            ->where('id', $id)
            ->with(['section', 'subject'])
            ->first();
        
        if (!$homework) {
            return redirect()->route('school.homeWork')->with('error', 'Homework not found');
        }
        
        // Format the homework data for the view
        $formattedHomework = [
            'id' => $homework->id,
            'class_name' => $homework->class_name,
            'section_id' => $homework->section_id,
            'section_name' => $homework->section ? $homework->section->name : 'Unknown',
            'subject_id' => $homework->subject_id,
            'subject_name' => $homework->subject ? $homework->subject->name : 'Unknown',
            'homework_date' => $homework->homework_date->format('Y-m-d'),
            'submission_date' => $homework->submission_date->format('Y-m-d'),
            'description' => $homework->description,
            'image_path' => $homework->image_path ? asset('storage/' . $homework->image_path) : null,
        ];
        
        // Get classes for dropdowns
        $classes = SchoolClass::where('school_id', $schoolId)
            ->with('section')
            ->get();
            
        // Get subjects for dropdowns
        $subjects = Subject::where('school_id', $schoolId)->get();
        
        // Get all homework entries for display
        $homeworkData = Homework::where('school_id', $schoolId)
            ->with(['section', 'subject'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'class_name' => $item->class_name,
                    'section' => $item->section ? $item->section->name : 'Unknown',
                    'subject' => $item->subject ? $item->subject->name : 'Unknown',
                    'homework_date' => $item->homework_date->format('Y-m-d'),
                    'submission_date' => $item->submission_date->format('Y-m-d'),
                    'description' => $item->description,
                    'image_path' => $item->image_path ? asset('storage/' . $item->image_path) : null,
                ];
            });
        
        return view('client.schoolPanel.academics.homeWork', [
            'homeworkData' => $homeworkData,
            'classes' => $classes,
            'subjects' => $subjects,
            'editHomework' => $formattedHomework,
            'isEditing' => true
        ]);
    }
    
    /**
     * Get the current school ID
     */
    private function getSchoolId()
    {
        $user = Auth::user();
        
        if (!$user) {
            return null;
        }
        
        // Check if user is associated with a school
        $school = School::where('admin_id', $user->id)->first();
        
        if (!$school) {
            return null;
        }
        
        return $school->id;
    }
}
