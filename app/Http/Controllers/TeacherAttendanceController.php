<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Section;
use App\Models\TimeTable;
use App\Models\TimeTablePeriod;

class TeacherAttendanceController extends Controller
{
    /**
     * Display the attendance management page.
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
            
            // Get teaching assignments from timetable
            $teachingAssignments = $this->getTeachingAssignments($teacherId);
            
            // Get today's date
            $today = now()->format('Y-m-d');
            
            // Pass the data to the view
            return view('client.teacher.attendance.index', [
                'teachingAssignments' => $teachingAssignments,
                'today' => $today
            ]);
            
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error fetching teacher attendance data: ' . $e->getMessage());
            
            // Return view with empty data
            return view('client.teacher.attendance.index', [
                'teachingAssignments' => [],
                'today' => now()->format('Y-m-d')
            ]);
        }
    }
    
    /**
     * Get students for a specific class and section.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getStudents(Request $request)
    {
        $teacherId = Session::get('teacher_id');
        
        if (!$teacherId) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found'
            ], 404);
        }
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'class_name' => 'required|string',
            'section_id' => 'required|integer',
            'date' => 'required|date'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            // Get teacher details
            $teacher = Teacher::find($teacherId);
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher not found'
                ], 404);
            }
            
            // Check if teacher teaches this class-section
            $teaches = TimeTablePeriod::where('teacher', $teacherId)
                ->whereHas('timetable', function($query) use ($request) {
                    $query->where('class_name', $request->class_name)
                        ->where('section_id', $request->section_id);
                })
                ->exists();
            
            if (!$teaches) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not assigned to teach this class and section'
                ], 403);
            }

            // Get class ID from school_classes table
            $classId = DB::table('school_classes')
                ->where('name', $request->class_name)
                ->where('school_id', $teacher->school_id)
                ->value('id');

            if (!$classId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class not found'
                ], 404);
            }
            
            // Get students for this class and section
            $students = Student::where('school_id', $teacher->school_id)
                ->where('class_id', $classId)
                ->where('section_id', $request->section_id)
                ->orderBy('roll_number')
                ->get()
                ->map(function($student) use ($request) {
                    // Check if attendance already exists for this student on this date
                    $attendance = StudentAttendance::where('student_id', $student->id)
                        ->where('attendance_date', $request->date)
                        ->first();
                    
                    return [
                        'id' => $student->id,
                        'name' => $student->first_name . ' ' . $student->last_name,
                        'roll_number' => $student->roll_number,
                        'attendance' => $attendance ? [
                            'id' => $attendance->id,
                            'status' => $attendance->status,
                            'remarks' => $attendance->remarks
                        ] : null
                    ];
                });
            
            // Get section name
            $section = Section::find($request->section_id);
            $sectionName = $section ? $section->name : '';
            
            return response()->json([
                'success' => true,
                'students' => $students,
                'class_name' => $request->class_name,
                'section_name' => $sectionName,
                'date' => $request->date,
                'has_existing_attendance' => $students->contains(function($student) {
                    return $student['attendance'] !== null;
                })
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting students for attendance: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while getting students: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Save attendance for students.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveAttendance(Request $request)
    {
        $teacherId = Session::get('teacher_id');
        
        if (!$teacherId) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found'
            ], 404);
        }
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'class_name' => 'required|string',
            'section_id' => 'required|integer',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|integer|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent,late,half_day,leave',
            'attendance.*.remarks' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            // Get teacher details
            $teacher = Teacher::find($teacherId);
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher not found'
                ], 404);
            }
            
            // Begin transaction
            DB::beginTransaction();
            
            // Process each student's attendance
            foreach ($request->attendance as $attendanceData) {
                // Check if attendance already exists for this student on this date
                $attendance = StudentAttendance::where('student_id', $attendanceData['student_id'])
                    ->where('attendance_date', $request->date)
                    ->first();
                
                if ($attendance) {
                    // Update existing attendance
                    $attendance->status = $attendanceData['status'];
                    $attendance->remarks = $attendanceData['remarks'] ?? null;
                    $attendance->teacher_id = $teacherId;
                    $attendance->save();
                } else {
                    // Create new attendance record
                    StudentAttendance::create([
                        'school_id' => $teacher->school_id,
                        'student_id' => $attendanceData['student_id'],
                        'class_name' => $request->class_name,
                        'section_id' => $request->section_id,
                        'attendance_date' => $request->date,
                        'status' => $attendanceData['status'],
                        'remarks' => $attendanceData['remarks'] ?? null,
                        'teacher_id' => $teacherId
                    ]);
                }
            }
            
            // Commit transaction
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Attendance saved successfully'
            ]);
            
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();
            
            Log::error('Error saving attendance: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving attendance: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Show attendance report page.
     *
     * @return \Illuminate\Http\Response
     */
    public function report()
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
            
            // Get teaching assignments from timetable
            $teachingAssignments = $this->getTeachingAssignments($teacherId);
            
            // Pass the data to the view
            return view('client.teacher.attendance.report', [
                'teachingAssignments' => $teachingAssignments
            ]);
            
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error fetching teacher attendance report data: ' . $e->getMessage());
            
            // Return view with empty data
            return view('client.teacher.attendance.report', [
                'teachingAssignments' => []
            ]);
        }
    }
    
    /**
     * Get attendance report data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getReportData(Request $request)
    {
        $teacherId = Session::get('teacher_id');
        
        if (!$teacherId) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found'
            ], 404);
        }
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'class_name' => 'required|string',
            'section_id' => 'required|integer',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            // Get teacher details
            $teacher = Teacher::find($teacherId);
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher not found'
                ], 404);
            }

            // Get class ID from school_classes table
            $classId = DB::table('school_classes')
                ->where('name', $request->class_name)
                ->where('school_id', $teacher->school_id)
                ->value('id');

            if (!$classId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class not found'
                ], 404);
            }
            
            // Get students for this class and section
            $students = Student::where('school_id', $teacher->school_id)
                ->where('class_id', $classId)
                ->where('section_id', $request->section_id)
                ->orderBy('roll_number')
                ->get();
            
            // Get attendance data for the date range
            $attendanceData = StudentAttendance::where('school_id', $teacher->school_id)
                ->where('class_name', $request->class_name)
                ->where('section_id', $request->section_id)
                ->whereBetween('attendance_date', [$request->from_date, $request->to_date])
                ->get();
            
            // Get all dates in the range
            $dates = [];
            $currentDate = new \DateTime($request->from_date);
            $endDate = new \DateTime($request->to_date);
            
            while ($currentDate <= $endDate) {
                $dates[] = $currentDate->format('Y-m-d');
                $currentDate->modify('+1 day');
            }
            
            // Prepare report data
            $reportData = $students->map(function($student) use ($attendanceData, $dates) {
                $studentAttendance = [];
                
                foreach ($dates as $date) {
                    $attendance = $attendanceData->first(function($item) use ($student, $date) {
                        return $item->student_id == $student->id && $item->attendance_date->format('Y-m-d') == $date;
                    });
                    
                    $studentAttendance[$date] = $attendance ? $attendance->status : 'N/A';
                }
                
                // Calculate statistics
                $totalDays = count($dates);
                $present = count(array_filter($studentAttendance, function($status) { return $status == 'present'; }));
                $absent = count(array_filter($studentAttendance, function($status) { return $status == 'absent'; }));
                $late = count(array_filter($studentAttendance, function($status) { return $status == 'late'; }));
                $halfDay = count(array_filter($studentAttendance, function($status) { return $status == 'half_day'; }));
                $leave = count(array_filter($studentAttendance, function($status) { return $status == 'leave'; }));
                $notMarked = count(array_filter($studentAttendance, function($status) { return $status == 'N/A'; }));
                
                $attendancePercentage = $totalDays > 0 ? 
                    round((($present + ($halfDay * 0.5) + $late) / ($totalDays - $notMarked)) * 100, 2) : 0;
                
                return [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'roll_number' => $student->roll_number,
                    'attendance' => $studentAttendance,
                    'statistics' => [
                        'total_days' => $totalDays,
                        'present' => $present,
                        'absent' => $absent,
                        'late' => $late,
                        'half_day' => $halfDay,
                        'leave' => $leave,
                        'not_marked' => $notMarked,
                        'attendance_percentage' => $attendancePercentage
                    ]
                ];
            });
            
            // Get section name
            $section = Section::find($request->section_id);
            $sectionName = $section ? $section->name : '';
            
            return response()->json([
                'success' => true,
                'report_data' => $reportData,
                'dates' => $dates,
                'class_name' => $request->class_name,
                'section_name' => $sectionName,
                'from_date' => $request->from_date,
                'to_date' => $request->to_date
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting attendance report data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while getting attendance report: ' . $e->getMessage()
            ], 500);
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
            
            // Group by class and section
            $assignments = [];
            foreach ($periods as $period) {
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
                usort($assignment['sections'], function($a, $b) {
                    return strcmp($a['name'], $b['name']);
                });
            }
            
            return array_values($assignments);
            
        } catch (\Exception $e) {
            Log::error('Error getting teaching assignments: ' . $e->getMessage());
            return [];
        }
    }
} 