<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\TimeTable;
use App\Models\TimeTablePeriod;
use App\Models\SchoolClass;
use App\Models\ExamSchedule;
use App\Models\Section;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class TeacherTimetableController extends Controller
{
    /**
     * Display the teacher's timetable
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $teacherId = Session::get('teacher_id');
            if (!$teacherId) {
                return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
            }

            $teacher = Teacher::findOrFail($teacherId);
            
            // Get all timetable periods where this teacher teaches
            $periods = TimeTablePeriod::where('teacher', $teacherId)
                ->with(['timetable', 'subjectRelation', 'timetable.section'])
                ->orderBy('day')
                ->orderBy('time_from')
                ->get();
            
            if ($periods->isEmpty()) {
                return view('client.teacher.timetable.index')->with('error', 'No classes assigned to you in the timetable.');
            }
            
            // Group periods by day
            $groupedPeriods = $periods->groupBy('day');
            
            // Define days order for consistent display
            $daysOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            
            // Today's day
            $today = now()->format('l'); // Returns day name (Monday, Tuesday, etc.)
            
            // Get unique timetables (class-section combinations)
            $classSections = [];
            foreach ($periods as $period) {
                if ($period->timetable) {
                    $key = $period->timetable->class_name . '-' . ($period->timetable->section ? $period->timetable->section->name : 'Unknown');
                    $classSections[$key] = [
                        'class_name' => $period->timetable->class_name,
                        'section_name' => $period->timetable->section ? $period->timetable->section->name : 'Unknown',
                        'timetable_id' => $period->timetable->id
                    ];
                }
            }
            
            return view('client.teacher.timetable.index', compact('groupedPeriods', 'daysOrder', 'today', 'classSections', 'teacher'));
            
        } catch (\Exception $e) {
            Log::error('Error in teacher timetable: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->with('error', 'An error occurred while retrieving your timetable: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the teacher's timetable for a specific class-section
     *
     * @param  int  $timetableId
     * @return \Illuminate\Http\Response
     */
    public function showClassTimetable($timetableId)
    {
        try {
            $teacherId = Session::get('teacher_id');
            if (!$teacherId) {
                return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
            }

            $teacher = Teacher::findOrFail($teacherId);
            
            // Get the timetable
            $timetable = TimeTable::with('section')->findOrFail($timetableId);
            
            // Verify that this teacher teaches in this timetable
            $teachesInTimetable = TimeTablePeriod::where('timetable_id', $timetableId)
                ->where('teacher', $teacherId)
                ->exists();
                
            if (!$teachesInTimetable) {
                return back()->with('error', 'You are not assigned to teach in this class.');
            }
            
            // Get all periods for this timetable
            $periods = TimeTablePeriod::where('timetable_id', $timetableId)
                ->with(['subjectRelation', 'teacherRelation'])
                ->orderBy('day')
                ->orderBy('time_from')
                ->get();
            
            // Group periods by day
            $groupedPeriods = $periods->groupBy('day');
            
            // Define days order for consistent display
            $daysOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            
            // Today's day
            $today = now()->format('l'); // Returns day name (Monday, Tuesday, etc.)
            
            return view('client.teacher.timetable.class', compact('timetable', 'groupedPeriods', 'daysOrder', 'today', 'teacher'));
            
        } catch (\Exception $e) {
            Log::error('Error in teacher class timetable: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->with('error', 'An error occurred while retrieving the class timetable: ' . $e->getMessage());
        }
    }
    
    
    
public function showExamSchedule()
{
    $teacherId = Session::get('teacher_id');
    if (!$teacherId) {
        return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
    }

    $teacher = Teacher::findOrFail($teacherId);

    $schedules = ExamSchedule::whereRaw("JSON_CONTAINS(evaluator_id, '\"$teacher->id\"')")
        ->with(['exam'])
        ->orderBy('exam_date', 'desc')  
        ->orderBy('start_time', 'desc') 
        ->get();
// dd($schedules);
    return view('client.teacher.exams.index', compact('schedules'));
}

    
} 