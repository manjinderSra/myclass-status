<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\TimeTable;
use App\Models\ExamSchedule;
use App\Models\TimeTablePeriod;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TeacherTimetableController extends Controller
{
    /**
     * Get the complete timetable for the authenticated teacher
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTimetable(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                    'error_code' => 'UNAUTHORIZED'
                ], 401);
            }
            
            // If user has teacher role, get the teacher details
       
            
            // Find the teacher by email
            $teacher = Teacher::where('email', $user->email)->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher profile not found',
                    'error_code' => 'PROFILE_NOT_FOUND'
                ], 404);
            }
            
            // Get all timetable periods where this teacher teaches
            $periods = TimeTablePeriod::where('teacher', $teacher->id)
                ->with(['timetable', 'subjectRelation', 'timetable.section'])
                ->orderBy('day')
                ->orderBy('time_from')
                ->get();
            
            if ($periods->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No classes assigned to this teacher',
                    'data' => [
                        'timetable' => []
                    ]
                ]);
            }
            
            // Group periods by day
            $dayMap = [
                'Monday' => [],
                'Tuesday' => [],
                'Wednesday' => [],
                'Thursday' => [],
                'Friday' => [],
                'Saturday' => [],
                'Sunday' => []
            ];
            
            foreach ($periods as $period) {
                if (!$period->timetable) {
                    continue;
                }
                
                $dayMap[$period->day][] = [
                    'id' => $period->id,
                    'start_time' => $period->time_from,
                    'end_time' => $period->time_to,
                    'period_type' => $period->period_type,
                    'subject' => $period->subjectRelation ? [
                        'id' => $period->subjectRelation->id,
                        'name' => $period->subjectRelation->name
                    ] : null,
                    'class' => [
                        'name' => $period->timetable->class_name,
                        'section' => $period->timetable->section ? $period->timetable->section->name : 'Unknown'
                    ]
                ];
            }
            
            // Remove empty days
            foreach ($dayMap as $day => $dayPeriods) {
                if (empty($dayPeriods)) {
                    unset($dayMap[$day]);
                }
            }
            
            // Get unique timetables (class-section combinations)
            $classSections = [];
            foreach ($periods as $period) {
                if ($period->timetable) {
                    $key = $period->timetable->class_name . '-' . ($period->timetable->section ? $period->timetable->section->name : 'Unknown');
                    if (!isset($classSections[$key])) {
                        $classSections[$key] = [
                            'class_name' => $period->timetable->class_name,
                            'section_name' => $period->timetable->section ? $period->timetable->section->name : 'Unknown',
                            'timetable_id' => $period->timetable->id
                        ];
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'timetable' => $dayMap,
                    'classes' => array_values($classSections)
                ]
            ]);
            
        } catch (Exception $e) {
            \Log::error('Error in teacher timetable API: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the timetable',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
    
    /**
     * Get today's timetable for the authenticated teacher
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
  public function getWeeklyTimetable(Request $request)
{
    try {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing token.',
                'error_code' => 'UNAUTHORIZED'
            ], 401);
        }

        // Get teacher details
        $teacher = Teacher::where('email', $user->email)->first();
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher profile not found',
                'error_code' => 'PROFILE_NOT_FOUND'
            ], 404);
        }

        // Get start and end of current week
        $startOfWeek = now()->startOfWeek(); // Monday
        $endOfWeek = now()->endOfWeek();     // Sunday

        $weeklyData = [];

        // Loop through each day of the week
        for ($date = $startOfWeek; $date->lte($endOfWeek); $date->addDay()) {
            $dayName = $date->format('l');

            // Fetch timetable for this teacher and day
            $periods = TimeTablePeriod::where('teacher', $teacher->id)
                ->where('day', $dayName)
                ->with(['timetable', 'subjectRelation', 'timetable.section'])
                ->orderBy('time_from')
                ->get();

            $formattedPeriods = [];
            foreach ($periods as $period) {
                if (!$period->timetable) continue;

                $formattedPeriods[] = [
                    'id' => $period->id,
                    'start_time' => $period->time_from,
                    'end_time' => $period->time_to,
                    'period_type' => $period->period_type,
                    'subject' => $period->subjectRelation ? [
                        'id' => $period->subjectRelation->id,
                        'name' => $period->subjectRelation->name
                    ] : null,
                    'class' => [
                        'name' => $period->timetable->class_name,
                        'section' => $period->timetable->section ? $period->timetable->section->name : 'Unknown'
                    ]
                ];
            }

            $weeklyData[] = [
                'day' => $dayName,
                'date' => $date->format('Y-m-d'),
                'periods' => $formattedPeriods
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $weeklyData
        ]);

    } catch (Exception $e) {
        \Log::error('Error in teacher weekly timetable API: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching weekly timetable',
            'error_code' => 'SERVER_ERROR'
        ], 500);
    }
}

    
    
    
    
    
    public function getTodayTimetable(Request $request)
{
    try {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing token.',
                'error_code' => 'UNAUTHORIZED'
            ], 401);
        }

        // Get teacher details
        $teacher = Teacher::where('email', $user->email)->first();
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher profile not found',
                'error_code' => 'PROFILE_NOT_FOUND'
            ], 404);
        }

        // Get today's day name (e.g., Monday)
        $todayDayName = now()->format('l');
        $todayDate = now()->format('Y-m-d');

        // Fetch timetable for this teacher for today
        $periods = TimeTablePeriod::where('teacher', $teacher->id)
            ->where('day', $todayDayName)
            ->with(['timetable', 'subjectRelation', 'timetable.section'])
            ->orderBy('time_from')
            ->get();

        $formattedPeriods = [];
        foreach ($periods as $period) {
            if (!$period->timetable) continue;

            $formattedPeriods[] = [
                'id' => $period->id,
                'start_time' => $period->time_from,
                'end_time' => $period->time_to,
                'period_type' => $period->period_type,
                'subject' => $period->subjectRelation ? [
                    'id' => $period->subjectRelation->id,
                    'name' => $period->subjectRelation->name
                ] : null,
                'class' => [
                'id'=>$period->timetable->class_id,
                'school_id'=>$period->timetable->school_id,
                    'name' => $period->timetable->class_name,
                    'section' => $period->timetable->section ? $period->timetable->section->name : 'Unknown'
                ]
            ];
            
        }

        return response()->json([
            'success' => true,
            'data' => [
                'day' => $todayDayName,
                'date' => $todayDate,
                'periods' => $formattedPeriods
            ]
        ]);

    } catch (Exception $e) {
        \Log::error('Error in teacher today timetable API: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching today\'s timetable',
            'error_code' => 'SERVER_ERROR'
        ], 500);
    }
}




public function getStudentsByClassId(Request $request, $className, $schoolId)
{
    // Authenticate user
    $user = auth('sanctum')->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Invalid or missing token.',
            'error_code' => 'UNAUTHORIZED'
        ], 401);
    }

    // Validate that class exists
    $classExists = DB::select("
        SELECT *
        FROM school_classes 
        WHERE name = ? AND school_id = ?
    ", [$className, $schoolId]);

    if (empty($classExists)) {
        return response()->json([
            'success' => false,
            'message' => 'Class not found',
            'error_code' => 'CLASS_NOT_FOUND'
        ], 404);
    }

    // Validate time_table_periods_id
    $timeTablePeriodId = $request->query('id');
    if (!$timeTablePeriodId) {
        return response()->json([
            'success' => false,
            'message' => 'time_table_periods_id is required',
            'error_code' => 'PERIOD_ID_REQUIRED'
        ], 400);
    }

    $today = now()->format('Y-m-d');

    // Fetch students with today's attendance status for the given period
    $students = DB::select("
        SELECT 
            s.id, 
            s.first_name, 
            s.last_name, 
            s.roll_number, 
            s.email,
            COALESCE(sa.status, 'Not Marked') AS attendance_status
        FROM students s
        LEFT JOIN student_attendance sa 
            ON sa.student_id = s.id
            AND sa.attendance_date = ?
            AND sa.school_id = ?
            AND sa.class_name = ?
            AND sa.time_table_periods_id = ?
        WHERE s.class_id = ?
        ORDER BY s.first_name ASC
    ", [$today, $schoolId, $className, $timeTablePeriodId, $classExists[0]->id]);

    return response()->json([
        'success' => true,
        'class' => $classExists[0],
        'date' => $today,
        'time_table_periods_id' => $timeTablePeriodId,
        'students' => $students
    ]);
}


public function getStudentsByClassId1(Request $request, $classId,)
{
    try {
        // Authenticate user
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid or missing token.',
                'error_code' => 'UNAUTHORIZED'
            ], 401);
        }

        // Validate that class exists
        $classExists = DB::select("SELECT id, name FROM classes WHERE id = ?", [$classId]);
        if (empty($classExists)) {
            return response()->json([
                'success' => false,
                'message' => 'Class not found',
                'error_code' => 'CLASS_NOT_FOUND'
            ], 404);
        }

        $today = now()->format('Y-m-d');

        // Fetch students with attendance status for today
        $students = DB::select("
            SELECT 
                s.id, 
                s.name, 
                s.roll_number, 
                s.email,
                CASE 
                    WHEN a.status IS NULL THEN 'Not Marked'
                    ELSE a.status
                END AS attendance_status
            FROM students s
            LEFT JOIN attendance a 
                ON a.student_id = s.id 
                AND a.class_id = s.class_id 
                AND a.date = ?
            WHERE s.class_id = ?
            ORDER BY s.name ASC
        ", [$today, $classId]);

        return response()->json([
            'success' => true,
            'class' => $classExists[0],
            'date' => $today,
            'students' => $students
        ]);

    } catch (\Exception $e) {
        \Log::error('Error in getStudentsByClassId (SQL): ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching students',
            'error_code' => 'SERVER_ERROR'
        ], 500);
    }
}

    
    
    
    
    /**
     * Get timetable for a specific class-section that the teacher teaches
     *
     * @param Request $request
     * @param int $timetableId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClassTimetable(Request $request, $timetableId)
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                    'error_code' => 'UNAUTHORIZED'
                ], 401);
            }
            
      
            
            // Find the teacher by email
            $teacher = Teacher::where('email', $user->email)->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher profile not found',
                    'error_code' => 'PROFILE_NOT_FOUND'
                ], 404);
            }
            
            // Get the timetable
            $timetable = TimeTable::with('section')->find($timetableId);
            
            if (!$timetable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Timetable not found',
                    'error_code' => 'TIMETABLE_NOT_FOUND'
                ], 404);
            }
            
            // Verify that this teacher teaches in this timetable
            $teachesInTimetable = TimeTablePeriod::where('timetable_id', $timetableId)
                ->where('teacher', $teacher->id)
                ->exists();
                
            if (!$teachesInTimetable) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not assigned to teach in this class',
                    'error_code' => 'NOT_AUTHORIZED'
                ], 403);
            }
            
            // Get all periods for this timetable
            $periods = TimeTablePeriod::where('timetable_id', $timetableId)
                ->with(['subjectRelation', 'teacherRelation'])
                ->orderBy('day')
                ->orderBy('time_from')
                ->get();
            
            // Group periods by day
            $dayMap = [
                'Monday' => [],
                'Tuesday' => [],
                'Wednesday' => [],
                'Thursday' => [],
                'Friday' => [],
                'Saturday' => [],
                'Sunday' => []
            ];
            
            foreach ($periods as $period) {
                $dayMap[$period->day][] = [
                    'id' => $period->id,
                    'start_time' => $period->time_from,
                    'end_time' => $period->time_to,
                    'period_type' => $period->period_type,
                    'subject' => $period->subjectRelation ? [
                        'id' => $period->subjectRelation->id,
                        'name' => $period->subjectRelation->name
                    ] : ($period->period_type === 'regular' ? null : $period->name),
                    'teacher' => $period->teacherRelation ? [
                        'id' => $period->teacherRelation->id,
                        'name' => $period->teacherRelation->first_name . ' ' . $period->teacherRelation->last_name,
                        'is_you' => $period->teacher == $teacher->id
                    ] : null
                ];
            }
            
            // Remove empty days
            foreach ($dayMap as $day => $dayPeriods) {
                if (empty($dayPeriods)) {
                    unset($dayMap[$day]);
                }
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'class' => $timetable->class_name,
                    'section' => $timetable->section ? $timetable->section->name : 'Unknown',
                    'timetable' => $dayMap
                ]
            ]);
            
        } catch (Exception $e) {
            \Log::error('Error in teacher class timetable API: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the class timetable',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
    
  public function markAttendance(Request $request)
{
    // Authenticate user
    $user = auth('sanctum')->user();
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Invalid or missing token.',
            'error_code' => 'UNAUTHORIZED'
        ], 401);
    }

    // Validate input
    $validated = $request->validate([
        'school_id' => 'required|integer|exists:schools,id',
        'class_name' => 'required|string',
        'section_id' => 'required|integer|exists:sections,id',
        'time_table_periods_id' => 'required|integer|exists:time_table_periods,id',
        'attendance_date' => 'nullable|date', // defaults to today
        'students' => 'required|array',
        'students.*.student_id' => 'required|integer|exists:students,id',
        'students.*.status' => 'required|in:present,absent,late,half_day,leave',
        'students.*.remarks' => 'nullable|string'
    ]);

    $schoolId = $validated['school_id'];
    $className = $validated['class_name'];
    $sectionId = $validated['section_id'];
    $periodId = $validated['time_table_periods_id'];
    $attendanceDate = $validated['attendance_date'] ?? now()->format('Y-m-d');

    // Ensure teacher_id exists in teachers table
    $teacherId = DB::table('teachers')->where('id', $user->id)->exists() ? $user->id : null;

    DB::beginTransaction();
    try {
        foreach ($validated['students'] as $student) {
            DB::table('student_attendance')->updateOrInsert(
                [
                    // Keys for matching existing record
                    'school_id' => $schoolId,
                    'student_id' => $student['student_id'],
                    'attendance_date' => $attendanceDate,
                    'class_name' => $className,
                    'section_id' => $sectionId,
                    'time_table_periods_id' => $periodId,
                ],
                [
                    // Values to insert or update
                    'status' => $student['status'],
                    'remarks' => $student['remarks'] ?? null,
                    'teacher_id' => $teacherId,
                    'created_by' => $user->id,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully',
            'date' => $attendanceDate
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Failed to mark attendance',
            'error' => $e->getMessage()
        ], 500);
    }
}








public function indexTeacherExam()
{
    $user = auth('sanctum')->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Invalid or missing token.',
            'error_code' => 'UNAUTHORIZED'
        ], 401);
    }

    // Find the teacher by email
    $teacher = Teacher::where('email', $user->email)->first();

    if (!$teacher) {
        return response()->json([
            'success' => false,
            'message' => 'Teacher profile not found',
            'error_code' => 'PROFILE_NOT_FOUND'
        ], 404);
    }

    // Fetch all exam schedules where this teacher is an evaluator
    $schedules = ExamSchedule::whereRaw("JSON_CONTAINS(evaluator_id, '\"$teacher->id\"')")
        ->with(['exam'])
        ->get();

    // Group exams by exam type (Mid-Term, Final-Term, etc.)
    $groupedExams = $schedules->groupBy(function ($schedule) {
        return $schedule->exam->name ?? 'Other';
    });

    // Format grouped exams
    $response = $groupedExams->map(function ($exams, $type) {
        return [
            'exam_type' => $type,
            'exams' => $exams->map(function ($schedule) {
                return [
                    'id'         => $schedule->id,
                    'exam_name'  => $schedule->exam->name ?? 'N/A',
                    'class'      => $schedule->class ?? 'N/A',
                    'section'    => $schedule->section ?? 'N/A',
                    'subject'    => $schedule->subject,
                    'exam_date'  => $schedule->exam_date,
                    'start_time' => $schedule->start_time,
                    'end_time'   => $schedule->end_time,
                    'room_no'    => json_decode($schedule->room_no, true),
                ];
            }),
        ];
    })->values();

    return response()->json([
        'status' => true,
        'teacher_id' => $teacher->id,
        'total_exam_types' => $groupedExams->count(),
        'grouped_exams' => $response,
    ]);
}

} 