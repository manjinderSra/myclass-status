<?php

namespace App\Http\Controllers\Client\SchoolPanel\Academics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\School;
use App\Models\StudentAttendance;
use App\Models\TimeTable;
use App\Models\TimeTablePeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TimeTableController extends Controller
{
    /**
     * Display the timetable view with classes and sections
     */
    public function index(Request $request)
    {
        $schoolId = $this->getSchoolId();

        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }

        // Get all active classes with sections
        $classes = SchoolClass::with(['teacher', 'section'])
            ->where('school_id', $schoolId)
            ->where('status', true)
            ->select('id', 'name', 'teacher_id')
            ->orderBy('name')
            ->get();

        // Get all sections
        $sections = Section::where('school_id', $schoolId)
            ->where('status', true)
            ->select('id', 'name', 'class_id')
            ->orderBy('name')
            ->get();

        // Initialize empty timetables collection
        $timetables = collect();
        // dd($timetables);
        // Determine which class/section to show
        $selectedClassName = $request->input('filterClass') ?? $classes->first()->name ?? null;
        $selectedSectionId = $request->input('filterSection') ?? $classes->first()->section->id ?? null;

        if ($selectedClassName && $selectedSectionId) {
            // Get periods for the selected class/section
            $periods = TimeTablePeriod::with(['subject', 'teacher', 'timetable.section'])
                ->whereHas('timetable', function ($query) use ($schoolId, $selectedClassName, $selectedSectionId) {
                    $query->where('school_id', $schoolId)
                        ->where('class_name', $selectedClassName)
                        ->where('section_id', $selectedSectionId);
                })
                ->orderBy('created_at', 'desc')
                ->orderBy('time_from')
                ->get();
                


            // Get only the most recent set of periods
            if ($periods->isNotEmpty()) {
                $latestDate = $periods->max('created_at');
                $latestPeriods = $periods->filter(function ($period) use ($latestDate) {
                    return $period->created_at->eq($latestDate);
                });

                $timetables = collect([
                    (object)[
                        'class_name' => $selectedClassName,
                        'section_id' => $selectedSectionId,
                        'section' => $latestPeriods->first()->timetable->section,
                        'periods' => $latestPeriods
                    ]
                ]);
            }
        }
        // echo'<pre>';
        //         print_r($timetables);
        //         die();
        return view('client.schoolPanel.academics.timeTable', compact('classes', 'sections', 'timetables'));
    }




    /**
     * Get sections by class ID for AJAX requests
     */
    public function getSectionsByClass($classId)
    {
        $schoolId = $this->getSchoolId();

        if (!$schoolId) {
            return response()->json([
                'success' => false,
                'message' => 'School not found'
            ], 404);
        }

        // Get the class first
        $class = SchoolClass::find($classId);

        if (!$class) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found'
            ], 404);
        }

        // Get sections for the specified class using the new class_id column
        $sections = Section::where('school_id', $schoolId)
            ->where('class_id', $classId)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'sections' => $sections
        ]);
    }

    /**
     * Store a new timetable in the database
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

        // Log request data for debugging
        Log::info('Timetable store request data:', [
            'class_id' => $request->class_id,
            'class_name' => $request->class_name,
            'section_id' => $request->section_id,
            'school_id' => $schoolId
        ]);

        // Validate the request
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:school_classes,id',
            'class_name' => 'required|string',
            'section_id' => 'required|exists:sections,id',
            'periods.*.*.time_from' => 'required|string',
            'periods.*.*.time_to' => 'required|string',
            'periods.*.*.subject' => 'nullable|string',
            'periods.*.*.teacher' => 'nullable|string',
            'periods.*.*.name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
       // ✅ Check if timetable already exists for this class & section
        $existingTimetable = TimeTable::where('school_id', $schoolId)
            ->where('class_name', $request->class_name)
            ->where('section_id', $request->section_id)
            ->first();

        if ($existingTimetable) {
            Log::warning('Duplicate timetable attempt detected', [
                'school_id' => $schoolId,
                'class_name' => $request->class_name,
                'section_id' => $request->section_id,
                'existing_timetable_id' => $existingTimetable->id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'A timetable for this class and section already exists.',
                'existing_timetable_id' => $existingTimetable->id
            ], 409); // HTTP 409 Conflict
        }
            // Log detailed information about the request
            Log::info('Timetable store - raw request data', [
                'all' => $request->all(),
                'class_id' => $request->class_id,
                'class_name' => $request->class_name,
                'section_id' => $request->section_id,
                'periods' => $request->periods
            ]);

            // Create a timetable record
            $timetable = new TimeTable();
            $timetable->school_id = $schoolId;
            $timetable->class_name = $request->class_name;
            $timetable->section_id = $request->section_id;
            // Set start_time and duration to null since we're not using them
            $timetable->start_time = null;
            $timetable->duration = null;
            $timetable->created_by = Auth::id();
            $timetable->save();

            // Save all the periods
            foreach ($request->periods as $day => $dayPeriods) {
                foreach ($dayPeriods as $index => $periodData) {
                    // Handle both regular periods (with subject/teacher) and extra periods (with name)
                    if (!empty($periodData['subject']) && !empty($periodData['teacher'])) {
                        // Regular period
                        $period = new TimeTablePeriod();
                        $period->timetable_id = $timetable->id;
                        $period->day = $day;
                        $period->subject = $periodData['subject']; // Store subject ID
                        $period->teacher = $periodData['teacher']; // Store teacher ID
                        $period->time_from = $periodData['time_from'];
                        $period->time_to = $periodData['time_to'];
                        $period->period_type = 'regular';
                        $period->save();

                        Log::info('Saved regular period', [
                            'period_id' => $period->id,
                            'subject' => $period->subject,
                            'teacher' => $period->teacher
                        ]);
                    } elseif (!empty($periodData['name'])) {
                        // Extra period (break, lunch, etc.)
                        $period = new TimeTablePeriod();
                        $period->timetable_id = $timetable->id;
                        $period->day = $day;
                        $period->name = $periodData['name'];
                        $period->time_from = $periodData['time_from'];
                        $period->time_to = $periodData['time_to'];
                        $period->period_type = 'extra';
                        $period->save();
                    }
                }
            }
            // dd($request->periods);


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Timetable saved successfully',
                'data' => [
                    'timetable_id' => $timetable->id
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to save timetable',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filter timetable based on class and section
     */

    public function filter(Request $request)
    {
        $schoolId = $this->getSchoolId();
        Log::info('--- FILTER METHOD START ---');
        Log::info('School ID:', ['school_id' => $schoolId]);
        Log::info('Request Data:', $request->all());

        if (!$schoolId) {
            Log::warning('School ID not found');
            return response()->json([
                'success' => false,
                'message' => 'School not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'filterClass' => 'required|string',
            'filterSection' => 'required',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find the latest timetable for the specified class and section
            Log::info('Finding timetable...', [
                'class' => $request->filterClass,
                'section' => $request->filterSection,
            ]);

            $timetable = TimeTable::where('school_id', $schoolId)
                ->where('class_name', $request->filterClass)
                ->where('section_id', $request->filterSection)
                ->latest()
                ->first();

            Log::info('Fetched Timetable:', ['timetable' => $timetable ? $timetable->toArray() : null]);

            if (!$timetable) {
                Log::info('No timetable found for given class & section.');
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            // Get all periods for this timetable
            $periods = TimeTablePeriod::where('timetable_id', $timetable->id)
                ->with(['subjectRelation', 'teacherRelation'])
                ->get()
                ->map(function ($period) {
                    $data = [
                        'id' => $period->id,
                        'day' => $period->day,
                        'start_time' => $period->time_from,
                        'end_time' => $period->time_to,
                        'period_type' => $period->period_type,
                    ];

                    if ($period->period_type === 'regular') {
                        $data['subject'] = $period->subject;
                        $data['teacher'] = $period->teacher;
                        $data['subject_name'] = $period->subject_name;
                        $data['teacher_name'] = $period->teacher_name;
                    } else {
                        $data['name'] = $period->name;
                    }

                    Log::debug('Processed Period:', $data);
                    return $data;
                });

            Log::info('All Periods Processed:', ['count' => count($periods)]);
            Log::info('--- FILTER METHOD END SUCCESSFULLY ---');

            return response()->json([
                'success' => true,
                'data' => $periods,
                'timetable' => [
                    'id' => $timetable->id,
                    'class' => $timetable->class_name,
                    'section_id' => $timetable->section_id
                    ]
                        ]);
                        } catch (\Exception $e) {
                            Log::error('Exception in filter():', [
                                'message' => $e->getMessage(),
                                'trace' => $e->getTraceAsString(),
                            ]);

                            return response()->json([
                                'success' => false,
                                'message' => 'Failed to filter timetable',
                                'error' => $e->getMessage()
                            ], 500);
                        }
    }

public function edit($id)
{
    $schoolId = $this->getSchoolId();

    if (!$schoolId) {
        return response()->json([
            'success' => false,
            'message' => 'School not found'
        ], 404);
    }

    try {
        $period = TimeTablePeriod::with(['subjectRelation', 'teacherRelation', 'timetable.section'])
            ->where('id', $id)
            ->first();

        if (!$period) {
            return response()->json([
                'success' => false,
                'message' => 'Timetable entry not found'
            ], 404);
        }

        // Verify that this period belongs to the logged-in school
        $timetable = TimeTable::find($period->timetable_id);
        if (!$timetable || $timetable->school_id !== $schoolId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $period->id,
                'day' => $period->day,
                'time_from' => $period->time_from,
                'time_to' => $period->time_to,
                'period_type' => $period->period_type,
                'subject' => $period->subject,
                'teacher' => $period->teacher,
                'name' => $period->name,
                'class_name' => $timetable->class_name,
                'section_id' => $timetable->section_id,
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch timetable entry',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function update(Request $request, $id)
{
    $schoolId = $this->getSchoolId();

    if (!$schoolId) {
        return response()->json([
            'success' => false,
            'message' => 'School not found'
        ], 404);
    }

    $validator = Validator::make($request->all(), [
        'time_from' => 'required|string',
        'time_to' => 'required|string',
        'subject' => 'nullable|string',
        'teacher' => 'nullable|string',
        'name' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $period = TimeTablePeriod::findOrFail($id);
        $timetable = TimeTable::find($period->timetable_id);

        if (!$timetable || $timetable->school_id !== $schoolId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $period->update([
            'time_from' => $request->time_from,
            'time_to' => $request->time_to,
            'subject' => $request->subject,
            'teacher' => $request->teacher,
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Timetable entry updated successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update timetable entry',
            'error' => $e->getMessage()
        ], 500);
    }
}


    /**
     * Delete a timetable period
     */
public function destroy(Request $request)
{
    $schoolId = $this->getSchoolId();

    if (!$schoolId) {
        return response()->json([
            'success' => false,
            'message' => 'School not found'
        ], 404);
    }

    try {
        // Get class_name and section_id from request
        $className = $request->input('class_name');
        $sectionId = $request->input('section_id');

        if (!$className || !$sectionId) {
            return response()->json([
                'success' => false,
                'message' => 'Class name and section ID are required'
            ], 400);
        }

        // Find all timetables matching class and section
        $timetables = TimeTable::where('school_id', $schoolId)
            ->where('class_name', $className)
            ->where('section_id', $sectionId)
            ->get();

        if ($timetables->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Timetable not found or you do not have permission to delete it'
            ], 403);
        }

        // Get section name for success message
        $section = Section::find($sectionId);
        $sectionName = $section->name ?? 'Unknown';

        // Delete all periods for these timetables
        $timetableIds = $timetables->pluck('id');
        TimeTablePeriod::whereIn('timetable_id', $timetableIds)->delete();

        // Delete all timetables
        TimeTable::where('school_id', $schoolId)
            ->where('class_name', $className)
            ->where('section_id', $sectionId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "Timetable for {$className} - {$sectionName} deleted successfully"
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete timetable',
            'error' => $e->getMessage()
        ], 500);
    }
}



    /**
     * Get subjects from the timetable for a specific class and section
     * Used by the homework page to populate subject dropdown
     */
    public function getSubjectsByClassAndSection(Request $request)
    {
        $schoolId = $this->getSchoolId();

        if (!$schoolId) {
            return response()->json([
                'success' => false,
                'message' => 'School not found'
            ], 404);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'class' => 'required|string',
            'section' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Find the timetable for the specified class and section
            $timetable = TimeTable::where('school_id', $schoolId)
                ->where('class_name', $request->class)
                ->where('section_id', $request->section)
                ->first();

            if (!$timetable) {
                return response()->json([
                    'success' => false,
                    'message' => 'No timetable found for the specified class and section'
                ], 404);
            }

            // Get all periods from this timetable
            $periods = TimeTablePeriod::where('timetable_id', $timetable->id)
                ->where('period_type', 'regular')
                ->whereNotNull('subject')
                ->with('subjectRelation')
                ->get();

            // Use a collection to get distinct subjects
            $subjects = $periods->map(function ($period) {
                // Only return the subject if it has a valid subject relation
                if ($period->subjectRelation) {
                    return [
                        'id' => $period->subject,
                        'name' => $period->subjectRelation->name
                    ];
                }
                return null;
            })
                ->filter() // Remove null values
                ->unique('id') // Get unique subjects by ID
                ->values(); // Re-index array

            // Log the subjects for debugging
            Log::info('Timetable subjects for class: ' . $request->class . ', section: ' . $request->section, [
                'count' => $subjects->count(),
                'subjects' => $subjects->toArray()
            ]);

            return response()->json([
                'success' => true,
                'subjects' => $subjects
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching timetable subjects: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching subjects'
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


    // public function attendanceIndex()
    // {
    //     $schoolId = $this->getSchoolId();

    //     // Fetch all students for the dropdowns in the modals
    //     $students = Student::where('school_id', $schoolId)->orderBy('first_name')->get();

    //     // Fetch attendance records, eager load student details
    //     // You might want to paginate this for large datasets
    //     $attendances = StudentAttendance::where('school_id', $schoolId)->with('student')
    //         ->orderBy('attendance_date', 'desc')
    //         ->get(); // Using get() for simplicity, consider paginate() for large data

    //     // Calculate summary statistics for the overview cards
    //     $totalDays = $attendances->count();
    //     $present = $attendances->where('status', 'present')->count();
    //     $absent = $attendances->where('status', 'absent')->count();
    //     $late = $attendances->where('status', 'late')->count();
    //     $leave = $attendances->where('status', 'leave')->count();

    //     // Pass all necessary data to the Blade view
    //     return view('client.schoolPanel.academics.attendance', compact(
    //         'attendances',
    //         'students', // Pass students for the dropdowns in modals
    //         'totalDays',
    //         'present',
    //         'absent',
    //         'late',
    //         'leave'
    //     ));
    // }

    public function attendanceIndex(Request $request)
    {
        $schoolId = $this->getSchoolId();

        // Fetch filters from request
        $classId = $request->input('class_id');
        $sectionId = $request->input('section_id');
        $attendanceDate = $request->input('attendance_date');

        // Dropdown data
        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $sections = Section::where('school_id', $schoolId)->orderBy('name')->get();

        // Base query
        $query = StudentAttendance::where('school_id', $schoolId)
            ->with(['student.class', 'student.section'])
            ->orderBy('attendance_date', 'desc');

        // Apply filters
        if ($classId) {
            $query->whereHas('student', function ($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        }

        if ($sectionId) {
            $query->whereHas('student', function ($q) use ($sectionId) {
                $q->where('section_id', $sectionId);
            });
        }

        if ($attendanceDate) {
            $query->whereDate('attendance_date', $attendanceDate);
        }

        // Get filtered attendance
        $attendances = $query->get();

        // ✅ Compute stats based on filtered data
        $totalDays = $attendances->count();
        $presentDays = $attendances->where('status', 'present')->count();
        $absentDays = $attendances->where('status', 'absent')->count();
        $lateDays = $attendances->where('status', 'late')->count();
        $leaveDays = $attendances->where('status', 'leave')->count();

        // All students for modal
        $students = Student::where('school_id', $schoolId)->orderBy('first_name')->get();

        return view('client.schoolPanel.academics.attendance', compact(
            'attendances',
            'students',
            'classes',
            'sections',
            'totalDays',
            'presentDays',
            'absentDays',
            'lateDays',
            'leaveDays',
            'classId',
            'sectionId',
            'attendanceDate'
        ));
    }


    // Show attendance list
    public function attendanceIndexTeacher(Request $request)
    {
        $schoolId = $this->getSchoolId();

        $date = $request->get('attendance_date', Carbon::now()->format('Y-m-d'));

        $attendance = DB::select("
        SELECT t.id AS teacher_id,
               t.first_name,
               t.last_name,
               COALESCE(ta.status, 'Not Marked') AS status,
             
               ta.attendance_date
        FROM teachers t
        LEFT JOIN teacher_attendance ta
            ON t.id = ta.teacher_id
            AND ta.attendance_date = ? 
            AND ta.school_id = ?
        WHERE t.school_id = ?     -- ? restrict to one school only
        ORDER BY t.first_name ASC
      ", [$date, $schoolId, $schoolId]);

        // Fetch teachers for filter dropdown
        $teachers = DB::table('teachers')
            ->select('id', DB::raw("CONCAT(first_name, ' ', last_name) as name"))
            ->where('school_id', $schoolId)
            ->orderBy('first_name')
            ->get();

        return view('client.schoolPanel.academics.teacher-attendance', [
            'attendances' => $attendance,
            'date' => $date,
            'teachers' => $teachers
        ]);
    }



    // Show mark attendance form
    public function create()
    {
        $teachers = DB::select("SELECT id, first_name, last_name FROM teachers ORDER BY first_name ASC");
        return view('admin.teacher_attendance.create', compact('teachers'));
    }

    // Store attendance
    public function attendanceStoreTeacher(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|integer|exists:teachers,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:Present,Absent,Late,On Leave',
            'remarks' => 'nullable|string'
        ]);

        DB::statement("
            INSERT INTO teacher_attendance (teacher_id, school_id, attendance_date, status, remarks)
            VALUES (?, 1, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE
                status = VALUES(status),
                remarks = VALUES(remarks),
                updated_at = CURRENT_TIMESTAMP
        ", [
            $validated['teacher_id'],
            $validated['attendance_date'],
            $validated['status'],
            $validated['remarks'] ?? null
        ]);

        return redirect()->route('admin.teacher-attendance.index')
            ->with('success', 'Attendance marked successfully');
    }

    // Edit form
    public function attendanceEndexTeacher($id)
    {
        $attendance = DB::select("SELECT * FROM teacher_attendance WHERE id = ?", [$id]);
        if (!$attendance) {
            return redirect()->back()->with('error', 'Record not found');
        }
        return view('admin.teacher_attendance.edit', ['attendance' => $attendance[0]]);
    }

    // Update record
    public function attendanceUpdateTeacher(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Present,Absent,Late,On Leave',
            'remarks' => 'nullable|string'
        ]);

        DB::update("
            UPDATE teacher_attendance
            SET status = ?, remarks = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ", [$validated['status'], $validated['remarks'] ?? null, $id]);

        return redirect()->route('admin.teacher-attendance.index')
            ->with('success', 'Attendance updated successfully');
    }

    // Delete record
    public function attendanceDestroyTeacher($id)
    {
        DB::delete("DELETE FROM teacher_attendance WHERE id = ?", [$id]);
        return redirect()->route('admin.teacher-attendance.index')
            ->with('success', 'Attendance deleted successfully');
    }


    public function teacherMarkAttendance(Request $request)
    {
        $schoolId = $this->getSchoolId();

        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'date'       => 'required|date',
            'status'     => 'required|in:Present,Absent,On Leave,late',
        ]);

        DB::table('teacher_attendance')
            ->updateOrInsert(
                [
                    'teacher_id' => $request->teacher_id,
                    'attendance_date' => $request->date,
                    'school_id' => $schoolId
                ],
                [
                    'status' => $request->status,
                    'remarks' => $request->remarks ?? null,
                    'updated_at' => now(),
                    'created_at' => now()
                ]
            );

        return redirect()->back()->with('success', 'Attendance marked successfully.');
    }

    //   
    public function teacherMonthlyAttendance(Request $request)
    {
        $schoolId = $this->getSchoolId();

        // Get filters
        $month = $request->get('month', Carbon::now()->format('Y-m')); // format YYYY-MM
        $teacherId = $request->get('teacher_id');

        // Parse month into first & last date
        $startDate = Carbon::parse($month . '-01')->startOfMonth()->format('Y-m-d');
        $endDate   = Carbon::parse($month . '-01')->endOfMonth()->format('Y-m-d');

        // Get all teachers for dropdown
        $teachers = DB::table('teachers')
            ->select('id', DB::raw("CONCAT(first_name,' ',last_name) as name"))
            ->where('school_id', $schoolId)
            ->orderBy('first_name')
            ->get();

        // Build attendance query
        $query = DB::table('teachers as t')
            ->leftJoin('teacher_attendance as ta', function ($join) use ($startDate, $endDate, $schoolId) {
                $join->on('t.id', '=', 'ta.teacher_id')
                    ->whereBetween('ta.attendance_date', [$startDate, $endDate])
                    ->where('ta.school_id', $schoolId);
            })
            ->select(
                't.id as teacher_id',
                DB::raw("CONCAT(t.first_name,' ',t.last_name) as teacher_name"),
                DB::raw("SUM(CASE WHEN ta.status = 'Present' THEN 1 ELSE 0 END) as present_count"),
                DB::raw("SUM(CASE WHEN ta.status = 'Absent' THEN 1 ELSE 0 END) as absent_count"),
                DB::raw("SUM(CASE WHEN ta.status = 'On Leave' THEN 1 ELSE 0 END) as leave_count"),
                DB::raw("SUM(CASE WHEN ta.status = 'Late' THEN 1 ELSE 0 END) as late_count"),
                DB::raw("COUNT(ta.id) as total_marked")
            )
            ->where('t.school_id', $schoolId);

        if ($teacherId) {
            $query->where('t.id', $teacherId);
        }

        $query->groupBy('t.id', 't.first_name', 't.last_name')
            ->orderBy('t.first_name');

        $report = $query->get();

        return view('client.schoolPanel.academics.teacher-attendance-monthly', [
            'report' => $report,
            'month' => $month,
            'teachers' => $teachers,
            'teacherId' => $teacherId
        ]);
    }
    // 

}
