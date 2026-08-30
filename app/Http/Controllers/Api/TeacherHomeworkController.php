<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Homework;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\HomeworkSubmission;

use App\Models\Student;
use App\Models\User;
use App\Models\TimeTablePeriod;
use App\Models\TimeTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class TeacherHomeworkController extends Controller
{
    /**
     * Get all homework for the authenticated teacher
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function index(Request $request)
{
    try {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $schoolId = $user->school_id;

        // Get teacher details
        $teacher = Teacher::where('email', $user->email)->first();
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found'
            ], 404);
        }

        // Get teaching assignments (classes + sections)
        $teachingAssignments = $this->getTeacherClassesAndSections($teacher->id, $schoolId);

        // Fetch all homework for teacher
        $homeworks = Homework::where('school_id', $schoolId)
            ->where('teacher_id', $teacher->id)
            ->with([
                'section:id,name',
                'subject:id,name',
                'creator:id,name'
            ])
            ->get();

        // ? Group by class -> subject -> homework
        $groupedData = $homeworks->groupBy('class_name')->map(function ($classGroup) {
            return $classGroup->groupBy('subject.name')->map(function ($subjectGroup) {
                return $subjectGroup->map(function ($hw) {
                    return [
                        'id' => $hw->id,
                        'homework_date' => $hw->homework_date,
                        'submission_date' => $hw->submission_date,
                        'description' => $hw->description,
                        'image_url' => $hw->image_path ? asset('storage/' . $hw->image_path) : null,
                        'section' => $hw->section?->name,
                        'created_by' => $hw->creator?->name,
                        'created_at' => $hw->created_at->toDateTimeString(),
                    ];
                })->values();
            });
        });

        return response()->json([
            'success' => true,
            'data' => [
                'grouped_homework' => $groupedData,
                'teaching_assignments' => $teachingAssignments,
                'teacher' => [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'subject_id' => $teacher->subject_id,
                    'subject_name' => $teacher->subject,
                ]
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Error fetching teacher homework: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching homework data',
            'error' => $e->getMessage()
        ], 500);
    }
}

    
    /**
     * Store a new homework
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Get authenticated user from Sanctum or from header
            $user = auth('sanctum')->user();
            $teacherId = null;
            $schoolId = null;
            
            if ($user) {
                // If authenticated via Sanctum
                $schoolId = $user->school_id;
                $teacherId = $user->id;
            } else if ($request->header('X-Teacher-ID')) {
                // If using session-based auth with header
                $teacherId = $request->header('X-Teacher-ID');
                $teacher = Teacher::findOrFail($teacherId);
                $schoolId = $teacher->school_id;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
            
            // Get teacher details
            $teacher = Teacher::findOrFail($teacherId);
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher not found'
                ], 404);
            }
            
            // Get the teacher's subject
            $subjectId = $teacher->subject_id;
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                'class' => 'required|string',
                'section' => 'required|exists:sections,id',
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
            
            // Verify that the teacher teaches in this class and section
            $teachingAssignments = $this->getTeacherClassesAndSections($teacher->id, $schoolId);
            $canTeachInClass = false;
            
            foreach ($teachingAssignments as $assignment) {
                if ($assignment['class_name'] === $request->class && 
                    in_array($request->section, array_column($assignment['sections'], 'id'))) {
                    $canTeachInClass = true;
                    break;
                }
            }
            
            if (!$canTeachInClass) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not assigned to teach in this class and section'
                ], 403);
            }
            
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
            $homework->subject_id = $subjectId; // Use teacher's subject
            $homework->homework_date = $request->homework_date;
            $homework->submission_date = $request->submission_date;
            $homework->description = $request->description;
            $homework->image_path = $imagePath;
            $homework->created_by = $teacherId;
            $homework->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Homework added successfully',
                'homework' => [
                    'id' => $homework->id,
                    'class_name' => $homework->class_name,
                    'section_id' => $homework->section_id,
                    'subject_id' => $homework->subject_id,
                    'homework_date' => $homework->homework_date->format('Y-m-d'),
                    'submission_date' => $homework->submission_date->format('Y-m-d'),
                    'description' => $homework->description,
                    'image_url' => $homework->image_path ? asset('storage/' . $homework->image_path) : null,
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded image if exists
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            
            Log::error('Error adding teacher homework: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding homework',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update an existing homework
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Get authenticated user from Sanctum or from header
            $user = auth('sanctum')->user();
            $teacherId = null;
            $schoolId = null;
            
            if ($user) {
                // If authenticated via Sanctum
                $schoolId = $user->school_id;
                $teacherId = $user->id;
            } else if ($request->header('X-Teacher-ID')) {
                // If using session-based auth with header
                $teacherId = $request->header('X-Teacher-ID');
                $teacher = Teacher::findOrFail($teacherId);
                $schoolId = $teacher->school_id;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
            
            // Get teacher details
            $teacher = Teacher::findOrFail($teacherId);
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher not found'
                ], 404);
            }
            
            // Get the teacher's subject
            $subjectId = $teacher->subject_id;
            
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
            
            // Check if the teacher created this homework or is allowed to edit it
            if ($homework->created_by != $teacherId) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to update this homework'
                ], 403);
            }
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                'class' => 'required|string',
                'section' => 'required|exists:sections,id',
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
            
            // Verify that the teacher teaches in this class and section
            $teachingAssignments = $this->getTeacherClassesAndSections($teacher->id, $schoolId);
            $canTeachInClass = false;
            
            foreach ($teachingAssignments as $assignment) {
                if ($assignment['class_name'] === $request->class && 
                    in_array($request->section, array_column($assignment['sections'], 'id'))) {
                    $canTeachInClass = true;
                    break;
                }
            }
            
            if (!$canTeachInClass) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not assigned to teach in this class and section'
                ], 403);
            }
            
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
            $homework->homework_date = $request->homework_date;
            $homework->submission_date = $request->submission_date;
            $homework->description = $request->description;
            $homework->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Homework updated successfully',
                'homework' => [
                    'id' => $homework->id,
                    'class_name' => $homework->class_name,
                    'section_id' => $homework->section_id,
                    'subject_id' => $homework->subject_id,
                    'homework_date' => $homework->homework_date->format('Y-m-d'),
                    'submission_date' => $homework->submission_date->format('Y-m-d'),
                    'description' => $homework->description,
                    'image_url' => $homework->image_path ? asset('storage/' . $homework->image_path) : null,
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating teacher homework: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating homework',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete a homework
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Get authenticated user from Sanctum or from header
            $user = auth('sanctum')->user();
            $teacherId = null;
            $schoolId = null;
            
            if ($user) {
                // If authenticated via Sanctum
                $schoolId = $user->school_id;
                $teacherId = $user->id;
            } else if (request()->header('X-Teacher-ID')) {
                // If using session-based auth with header
                $teacherId = request()->header('X-Teacher-ID');
                $teacher = Teacher::findOrFail($teacherId);
                $schoolId = $teacher->school_id;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
            
            // Find the homework
            $homework = Homework::where('id', $id)
                ->where('school_id', $schoolId)
                ->first();
            
            if (!$homework) {
                return response()->json([
                    'success' => false,
                    'message' => 'Homework not found'
                ], 404);
            }
            
            // Verify that the teacher created this homework
            if ($homework->created_by != $teacherId) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to delete this homework'
                ], 403);
            }
            
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
            Log::error('Error deleting teacher homework: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting homework',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get a specific homework
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // Get authenticated user from Sanctum or from header
            $user = auth('sanctum')->user();
            $teacherId = null;
            $schoolId = null;
            
            if ($user) {
                // If authenticated via Sanctum
                $schoolId = $user->school_id;
                $teacherId = $user->id;
            } else if (request()->header('X-Teacher-ID')) {
                // If using session-based auth with header
                $teacherId = request()->header('X-Teacher-ID');
                $teacher = Teacher::findOrFail($teacherId);
                $schoolId = $teacher->school_id;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
            
            // Get teacher details
            $teacher = Teacher::findOrFail($teacherId);
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher not found'
                ], 404);
            }
            
            // Get the teacher's subject
            $subjectId = $teacher->subject_id;
            
            // Find the homework
            $homework = Homework::where('id', $id)
                ->where('school_id', $schoolId)
                ->with(['section', 'subject', 'creator'])
                ->first();
            
            if (!$homework) {
                return response()->json([
                    'success' => false,
                    'message' => 'Homework not found'
                ], 404);
            }
            
            // Format the homework data
            $homeworkData = [
                'id' => $homework->id,
                'class_name' => $homework->class_name,
                'section' => [
                    'id' => $homework->section_id,
                    'name' => $homework->section ? $homework->section->name : 'Unknown'
                ],
                'subject' => [
                    'id' => $homework->subject_id,
                    'name' => $homework->subject ? $homework->subject->name : 'Unknown'
                ],
                'homework_date' => $homework->homework_date->format('Y-m-d'),
                'submission_date' => $homework->submission_date->format('Y-m-d'),
                'description' => $homework->description,
                'image_url' => $homework->image_path ? asset('storage/' . $homework->image_path) : null,
                'created_by' => $homework->creator ? $homework->creator->name : 'Unknown',
                'created_at' => $homework->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $homework->updated_at->format('Y-m-d H:i:s')
            ];
            
            return response()->json([
                'success' => true,
                'data' => $homeworkData
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching teacher homework: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching homework',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get the classes and sections where the teacher teaches
     *
     * @param  int  $teacherId
     * @param  int  $schoolId
     * @return array
     */
   private function getTeacherClassesAndSections($teacherId, $schoolId)
    {
        // Use Query Builder to join necessary tables
        $timetablesData = DB::table('time_tables AS t')
            ->select(
                't.class_name AS class_name', // Select class_name directly from time_tables
                's.id AS section_id',
                's.name AS section_name'
            )
            // Join timetables with time_table_periods to filter by teacher
            ->join('time_table_periods AS ttp', 'ttp.timetable_id', '=', 't.id')
            // Left join sections to get section details
            ->leftJoin('sections AS s', 't.section_id', '=', 's.id')
            // Filter by teacher ID from time_table_periods
            ->where('ttp.teacher', $teacherId)
            // Filter by school ID from timetables table
            ->where('t.school_id', $schoolId)
            // Group by timetable ID, class_name, and section details to ensure distinct rows
            ->groupBy('t.id', 't.class_name', 's.id', 's.name')
            ->get();

        $processedClasses = [];

        foreach ($timetablesData as $data) {
            // No need to check for $data->class_id as class_name is directly on timetable
            $className = $data->class_name;

            // Initialize class entry if not already present
            if (!isset($processedClasses[$className])) {
                // Since there's no school_classes table joined for class_id,
                // you'll either need to fetch it separately or rely on just the class_name.
                // If school_class_id is absolutely needed, you'd have to find a way to map class_name to class_id.
                // For now, we'll omit class_id as it's not directly available here.
                $processedClasses[$className] = [
                    'class_name' => $className,
                    'sections' => []
                ];
            }

            // Add section if it exists and is not already added to this class's sections
            if (!empty($data->section_id)) {
                $section = [
                    'id' => $data->section_id,
                    'name' => $data->section_name
                ];

                // Check for duplicates before adding section to the class
                $sectionExists = false;
                foreach ($processedClasses[$className]['sections'] as $existingSection) {
                    if ($existingSection['id'] === $section['id']) {
                        $sectionExists = true;
                        break;
                    }
                }

                if (!$sectionExists) {
                    $processedClasses[$className]['sections'][] = $section;
                }
            }
        }

        // Convert the associative array to a numerically indexed array if desired
        $classesAndSections = array_values($processedClasses);

        return $classesAndSections;
    }
    
    
    
    
    


public function submissions($homeworkId)
{
    try {
        // Get the homework record to know class & section
        $homework = Homework::findOrFail($homeworkId);

        // Get all students in that class + section
        $students = Student::where('class_id', $homework->class_id ?? null)
            ->where('section_id', $homework->section_id ?? null)
            ->get();

        // Get all submissions for this homework
        $submissions = HomeworkSubmission::where('homework_id', $homeworkId)
            ->with('student:id,first_name,last_name,email,class_id,section_id')
            ->get();

        // Separate submitted and pending students
        $submittedStudentIds = $submissions->pluck('student_id')->toArray();

        $submitted = $submissions->map(function ($sub) {
            return [
                'submission_id' => $sub->id,
                'student_id' => $sub->student_id,
                'student_name' => $sub->student ? ($sub->student->first_name . ' ' . $sub->student->last_name) : null,
                'email' => $sub->student?->email,
                'file_url' => $sub->file_path ? asset('storage/' . $sub->file_path) : null,
                'submitted_at' => $sub->submitted_at ? $sub->submitted_at : null,
            ];
        });

        $pending = $students->whereNotIn('id', $submittedStudentIds)
            ->map(function ($student) {
                return [
                    'student_id' => $student->id,
                    'student_name' => $student->first_name . ' ' . $student->last_name,
                    'email' => $student->email,
                    'submitted' => false,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'homework_id' => $homeworkId,
            'class_id' => $homework->class_id,
            'section_id' => $homework->section_id,
            'submitted_count' => $submitted->count(),
            'pending_count' => $pending->count(),
            'data' => [
                'submitted' => $submitted,
                'pending' => $pending,
            ]
        ]);
    } catch (\Exception $e) {
        Log::error('Error fetching homework submissions: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch homework submissions',
            'error' => $e->getMessage()
        ], 500);
    }
}



public function showSubmissionsAdmin(Homework $homework)
{
    $students = Student::where('class_id', $homework->class_id)
        ->where('section_id', $homework->section_id)
        ->get();

    $submissions = HomeworkSubmission::where('homework_id', $homework->id)->get();

    return view('teacher.homeworks.submissions', compact('homework', 'students', 'submissions'));
}

    
    
    
} 