<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;
use App\Models\Homework;
use App\Models\Subject;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\TimeTable;
use App\Models\HomeworkSubmission;


use App\Models\TimeTablePeriod;

class TeacherHomeworkController extends Controller
{
    /**
     * Display the homework management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get teacher ID from session
        $teacherId = Session::get('teacher_id');

        if (!$teacherId) {
            return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
        }

        try {
            // Get teacher details
            $teacher = Teacher::find($teacherId);

            if (!$teacher) {
                return redirect()->route('teacher.login')->with('error', 'Teacher not found.');
            }

            // Get homework data for this teacher
            $homeworkData = $this->getHomeworkData($teacherId);

            // Get teaching assignments from timetable
            $teachingAssignments = $this->getTeachingAssignments($teacherId);

            // Get subjects for this teacher from timetable
            $subjects = $this->getTeacherSubjects($teacherId);

            // Pass the data to the view
            return view('client.teacher.homework.index', [
                'teachingAssignments' => $teachingAssignments,
                'homeworkData' => $homeworkData,
                'subjects' => $subjects
            ]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error fetching teacher homework data: ' . $e->getMessage());

            // Return view with empty data
            return view('client.teacher.homework.index', [
                'teachingAssignments' => [],
                'homeworkData' => [],
                'subjects' => []
            ]);
        }
    }

    /**
     * Filter homework based on provided criteria.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        // Get teacher ID from session
        $teacherId = Session::get('teacher_id');

        if (!$teacherId) {
            return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
        }

        try {
            // Get teacher details
            $teacher = Teacher::find($teacherId);

            if (!$teacher) {
                return redirect()->route('teacher.login')->with('error', 'Teacher not found.');
            }

            // Get filtered homework data
            $homeworkData = $this->getHomeworkData($teacherId, $request);

            // Get teaching assignments from timetable
            $teachingAssignments = $this->getTeachingAssignments($teacherId);

            // Get subjects for this teacher from timetable
            $subjects = $this->getTeacherSubjects($teacherId);

            // Pass the filtered data to the view
            return view('client.teacher.homework.index', [
                'teachingAssignments' => $teachingAssignments,
                'homeworkData' => $homeworkData,
                'subjects' => $subjects,
                'filters' => $request->all() // Pass back the filters to maintain state
            ]);
        } catch (\Exception $e) {
            // Log the error and redirect back
            Log::error('Error filtering teacher homework: ' . $e->getMessage());
            return redirect()->route('teacher.homework')
                ->with('error', 'An error occurred while filtering homework.');
        }
    }

    /**
     * Store a new homework assignment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $teacherId = Session::get('teacher_id');

        if (!$teacherId) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found'
            ], 404);
        }

        // Get teacher details
        $teacher = Teacher::find($teacherId);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found'
            ], 404);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'class' => 'required|string',
            'section' => 'required',
            'subject' => 'required',
            'homework_date' => 'required|date',
            'submission_date' => 'required|date|after_or_equal:homework_date',
            'description' => 'required|string',
            'image' => 'nullable|file|mimetypes:application/pdf,application/x-pdf,application/octet-stream,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/png,image/webp',

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
            $homework->school_id = $teacher->school_id;
            $homework->class_name = $request->class;
            $homework->section_id = $request->section;
            $homework->subject_id = $request->subject;
            $homework->teacher_id = $teacherId;
            $homework->homework_date = $request->homework_date;
            $homework->submission_date = $request->submission_date;
            $homework->description = $request->description;
            $homework->image_path = $imagePath;
            $homework->created_by = null;
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
     * Get homework data for editing.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get($id)
    {
        $teacherId = Session::get('teacher_id');

        if (!$teacherId) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found'
            ], 404);
        }

        try {
            // Get homework by ID and ensure it belongs to this teacher
            $homework = Homework::where('id', $id)
                ->where('teacher_id', $teacherId)
                ->with(['section', 'subject'])
                ->first();

            if (!$homework) {
                return response()->json([
                    'success' => false,
                    'message' => 'Homework not found'
                ], 404);
            }

            // Format homework data for response
            $homeworkData = [
                'id' => $homework->id,
                'class_name' => $homework->class_name,
                'section_id' => $homework->section_id,
                'section_name' => $homework->section ? $homework->section->name : 'Unknown',
                'subject_id' => $homework->subject_id,
                'subject_name' => $homework->subject ? $homework->subject->name : 'Unknown',
                'homework_date' => $homework->homework_date->format('Y-m-d'),
                'submission_date' => $homework->submission_date->format('Y-m-d'),
                'description' => $homework->description,
                'image' => $homework->image_path,
                'image_url' => $homework->image_path ? asset('storage/' . $homework->image_path) : null
            ];

            return response()->json([
                'success' => true,
                'homework' => $homeworkData
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching homework: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching homework'
            ], 500);
        }
    }

    /**
     * Update an existing homework.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $teacherId = Session::get('teacher_id');

        if (!$teacherId) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found'
            ], 404);
        }

        // Find the homework and ensure it belongs to this teacher
        $homework = Homework::where('id', $id)
            ->where('teacher_id', $teacherId)
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
            'section' => 'required',
            'subject' => 'required',
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
            } else if ($request->has('remove_image') && $request->remove_image) {
                // Remove image if requested
                if ($homework->image_path && Storage::disk('public')->exists($homework->image_path)) {
                    Storage::disk('public')->delete($homework->image_path);
                }
                $homework->image_path = null;
            }

            // Update homework details
            $homework->class_name = $request->class;
            $homework->section_id = $request->section;
            $homework->subject_id = $request->subject;
            $homework->homework_date = $request->homework_date;
            $homework->submission_date = $request->submission_date;
            $homework->description = $request->description;
            // Don't modify created_by
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
     * Delete a homework assignment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $teacherId = Session::get('teacher_id');

        if (!$teacherId) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found'
            ], 404);
        }

        // Find the homework and ensure it belongs to this teacher
        $homework = Homework::where('id', $id)
            ->where('teacher_id', $teacherId)
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
     * Helper method to get homework data for a teacher.
     *
     * @param  int  $teacherId
     * @param  \Illuminate\Http\Request|null  $request
     * @return array
     */
    private function getHomeworkData($teacherId, $request = null)
    {
        try {
            // Get teacher details to get school_id
            $teacher = Teacher::find($teacherId);

            if (!$teacher) {
                Log::error('Teacher not found with ID: ' . $teacherId);
                return [];
            }

            Log::info('Fetching homework data for teacher ID: ' . $teacherId . ' in school ID: ' . $teacher->school_id);

            // Start query
            $query = Homework::where('teacher_id', $teacherId);

            // Apply filters if provided
            if ($request) {
                if ($request->filled('filterClass')) {
                    $query->where('class_name', $request->filterClass);
                }

                if ($request->filled('filterSection')) {
                    $query->where('section_id', $request->filterSection);
                }

                if ($request->filled('filterHomeworkDate')) {
                    $query->whereDate('homework_date', $request->filterHomeworkDate);
                }

                if ($request->filled('filterSubject')) {
                    $query->where('subject_id', $request->filterSubject);
                }
            }

            // Get homework with related models
            $homeworkItems = $query->with(['section', 'subject'])
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('Found ' . $homeworkItems->count() . ' homework items');

            // Create dummy data if no homework found (for testing)
            if ($homeworkItems->count() == 0) {
                Log::info('No homework found, returning empty array');
                return [];
            }

            $homeworkData = $homeworkItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'class_name' => $item->class_name,
                    'section' => $item->section ? $item->section->name : 'Unknown',
                    'subject' => $item->subject ? $item->subject->name : 'Unknown',
                    'homework_date' => $item->homework_date->format('Y-m-d'),
                    'submission_date' => $item->submission_date->format('Y-m-d'),
                    'description' => $item->description,
                    'image_path' => $item->image_path ? asset('storage/' . $item->image_path) : null
                ];
            });

            Log::info('Homework data processed: ' . json_encode($homeworkData));
            return $homeworkData;
        } catch (\Exception $e) {
            Log::error('Error getting homework data: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Helper method to get teaching assignments for a teacher from timetable.
     *
     * @param  int  $teacherId
     * @return array
     */
    private function getTeachingAssignments($teacherId)
    {
        try {
            // Get all timetable periods where this teacher teaches
            $periods = TimeTablePeriod::where('teacher', $teacherId)
                ->with(['timetable', 'timetable.section'])
                ->get();
// dd($periods);
            Log::info('Teacher ID: ' . $teacherId . ', Found ' . $periods->count() . ' periods');

            // Group by class and section
            $assignments = [];
            foreach ($periods as $period) {
                Log::info('Period ID: ' . $period->id . ', Timetable: ' . ($period->timetable ? $period->timetable->id : 'null') .
                    ', Section: ' . ($period->timetable && $period->timetable->section ? $period->timetable->section->id : 'null'));

                if ($period->timetable && $period->timetable->section) {
                    $key = $period->timetable->class_name;
                    if (!isset($assignments[$key])) {
                        $assignments[$key] = [
                            'class_name' => $period->timetable->class_name,
                            'sections' => []
                        ];
                    }

                    // Check if section already exists
                    $sectionExists = false;
                    foreach ($assignments[$key]['sections'] as $section) {
                        if ($section['id'] === $period->timetable->section->id) {
                            $sectionExists = true;
                            break;
                        }
                    }

                    // Add section if it doesn't exist
                    if (!$sectionExists) {
                        $assignments[$key]['sections'][] = [
                            'id' => $period->timetable->section->id,
                            'name' => $period->timetable->section->name
                        ];
                    }
                }
            }

            // Sort sections by name
            foreach ($assignments as &$assignment) {
                usort($assignment['sections'], function ($a, $b) {
                    return strcmp($a['name'], $b['name']);
                });
            }

            $result = array_values($assignments);
            // dd($result);
            Log::info('Teaching assignments: ' . json_encode($result));
            return $result;
        } catch (\Exception $e) {
            Log::error('Error getting teaching assignments: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Helper method to get subjects for a teacher from timetable.
     *
     * @param  int  $teacherId
     * @return array
     */
   private function getTeacherSubjects($teacherId)
{
    try {
        $periods = TimeTablePeriod::where('teacher', $teacherId)
            ->with(['subjectRelation', 'timetable', 'timetable.section'])
            ->get();

        $subjects = [];

        foreach ($periods as $period) {
            if ($period->subjectRelation && $period->timetable && $period->timetable->section) {

                // FIX: unique by subject + class + section
                $key = $period->subjectRelation->id . '-' .
                       $period->timetable->class_name . '-' .
                       $period->timetable->section->id;

                if (!isset($subjects[$key])) {
                    $subjects[$key] = [
                        'id' => $period->subjectRelation->id,
                        'name' => $period->subjectRelation->name,
                        'class_name' => $period->timetable->class_name,
                        'section_id' => $period->timetable->section->id,
                        'section_name' => $period->timetable->section->name
                    ];
                }
            }
        }

        return array_values($subjects);

    } catch (\Exception $e) {
        return [];
    }
}




    public function showSubmissions($homeworkId)
    {
        $homework = Homework::findOrFail($homeworkId);

        // Get all students for that class/section
        $students = Student::where('school_id', $homework->school_id)
            ->where('section_id', $homework->section_id)
            ->whereHas('class', fn($q) => $q->where('name', $homework->class_name))
            ->get();

        $submissions = HomeworkSubmission::where('homework_id', $homeworkId)
            ->with('student')
            ->get();

        $submittedStudentIds = $submissions->pluck('student_id');

        $submitted = $submissions->map(function ($sub) {
            return [
                'student_id' => $sub->student_id,
                'student_name' => $sub->student->first_name . ' ' . $sub->student->last_name,
                'email' => $sub->student->email,
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
                ];
            });

        return view('client.teacher.homework.students', compact('submitted', 'pending'));
    }

    public function showSubmissionsAdmin($homeworkId)
    {
        // Get the homework
        $homework = Homework::findOrFail($homeworkId);

        // Get all students for the homework's class & section
        $students = Student::where('school_id', $homework->school_id)
            ->where('section_id', $homework->section_id)
            ->whereHas('class', fn($q) => $q->where('name', $homework->class_name))
            ->get();

        // Get submissions with student relationship
        $submissions = HomeworkSubmission::where('homework_id', $homework->id)
            ->with('student')
            ->get();

        // Map submissions into an array keyed by student_id for easy lookup
        $submittedMap = $submissions->mapWithKeys(function ($sub) {
            return [
                $sub->student_id => [
                    'file_url'     => $sub->file_path ? asset('storage/' . $sub->file_path) : null,
                    'submitted_at' => $sub->submitted_at,
                ]
            ];
        });

        // Merge students with submission status
        $studentList = $students->map(function ($student) use ($submittedMap) {
            $submission = $submittedMap->get($student->id);

            return [
                'id'           => $student->id,
                'name'         => $student->first_name . ' ' . $student->last_name,
                'email'        => $student->email,
                'roll_number'  => $student->roll_number ?? '-',
                'submitted'    => $submission ? true : false,
                'file_url'     => $submission['file_url'] ?? null,
                'submitted_at' => $submission['submitted_at'] ?? null,
            ];
        });



        return view('client.schoolPanel.academics.homeworkdetail', compact('homework', 'studentList'));
    }
}
