<?php

namespace App\Http\Controllers\Client\TeacherPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentLeave;
use App\Models\Teacher;
use App\Models\TimeTable;
use App\Models\TimeTablePeriod;
use App\Models\Student;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LeaveApplicationController extends Controller
{
    /**
     * Display a listing of leave applications for students taught by this teacher
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $teacherId = Session::get('teacher_id');
            if (!$teacherId) {
                return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
            }

            $teacher = Teacher::findOrFail($teacherId);
            $schoolId = $teacher->school_id;
            
            // Get status filter if provided
            $status = $request->input('status', null);
            
            // Get all timetable periods where this teacher teaches
            $timetablePeriods = TimeTablePeriod::where('teacher', $teacherId)
                ->with('timetable')
                ->get();
                
            if ($timetablePeriods->isEmpty()) {
                // Create an empty paginator if no classes assigned
                $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                    [], // Empty array for items
                    0,  // Total items
                    10, // Items per page
                    1   // Current page
                );
                $emptyPaginator->withPath(request()->url());
                
                return view('client.teacherPanel.leaveApplications.index', [
                    'leaves' => $emptyPaginator,
                    'status' => $status,
                    'message' => 'You are not assigned to any classes.'
                ]);
            }
            
            // Get unique timetable IDs
            $timetableIds = $timetablePeriods->pluck('timetable_id')->unique();
            
            // Get all timetables for these IDs
            $timetables = TimeTable::whereIn('id', $timetableIds)->get();
            
            // Get all class and section combinations
            $classAndSections = [];
            foreach ($timetables as $timetable) {
                $classAndSections[] = [
                    'class_name' => $timetable->class_name,
                    'section_id' => $timetable->section_id
                ];
            }
            
            // Get all students in these classes and sections
            $studentIds = [];
            foreach ($classAndSections as $classSection) {
                $classStudents = Student::where('school_id', $schoolId)
                    ->whereHas('class', function($query) use ($classSection) {
                        $query->where('name', $classSection['class_name']);
                    })
                    ->where('section_id', $classSection['section_id'])
                    ->pluck('id');
                    
                $studentIds = array_merge($studentIds, $classStudents->toArray());
            }
            
            // Remove duplicates
            $studentIds = array_unique($studentIds);
            
            // If no students found, return empty result
            if (empty($studentIds)) {
                $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                    [], // Empty array for items
                    0,  // Total items
                    10, // Items per page
                    1   // Current page
                );
                $emptyPaginator->withPath(request()->url());
                
                return view('client.teacherPanel.leaveApplications.index', [
                    'leaves' => $emptyPaginator,
                    'status' => $status,
                    'message' => 'No students found in your assigned classes.'
                ]);
            }
            
            // Get leave applications for these students
            $query = StudentLeave::whereIn('student_id', $studentIds)
                        ->where('school_id', $schoolId)
                        ->with(['student']);
            
            // Filter by status if provided
            if ($status) {
                $query->where('status', $status);
            }
            
            $leaves = $query->orderBy('created_at', 'desc')
                            ->paginate(10);
            
            return view('client.teacherPanel.leaveApplications.index', [
                'leaves' => $leaves,
                'status' => $status
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in teacher leave applications: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An error occurred while retrieving leave applications: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified leave application
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $teacherId = Session::get('teacher_id');
            if (!$teacherId) {
                return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
            }

            $teacher = Teacher::findOrFail($teacherId);
            $schoolId = $teacher->school_id;
            
            // Get the leave application
            $leave = StudentLeave::where('id', $id)
                            ->where('school_id', $schoolId)
                            ->with(['student', 'processor'])
                            ->firstOrFail();
            
            // Verify that this teacher teaches the student
            $student = $leave->student;
            
            if (!$student) {
                return back()->with('error', 'Student not found for this leave application.');
            }
            
            // Check if this teacher teaches this student's class
            $teachesThisStudent = TimeTablePeriod::where('teacher', $teacherId)
                ->whereHas('timetable', function($query) use ($student) {
                    $query->where('class_name', $student->class->name ?? '')
                          ->where('section_id', $student->section_id);
                })
                ->exists();
                
            if (!$teachesThisStudent) {
                return back()->with('error', 'You are not authorized to view this leave application.');
            }
            
            return view('client.teacherPanel.leaveApplications.show', [
                'leave' => $leave
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error showing teacher leave application: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->with('error', 'Leave application not found or an error occurred: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the status of a leave application
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $teacherId = Session::get('teacher_id');
            if (!$teacherId) {
                return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
            }

            $teacher = Teacher::findOrFail($teacherId);
            $schoolId = $teacher->school_id;
            
            $request->validate([
                'status' => 'required|in:pending,approved,rejected',
                'admin_remarks' => 'required_if:status,rejected|nullable|string|max:1000',
            ]);
            
            // Get the leave application
            $leave = StudentLeave::where('id', $id)
                            ->where('school_id', $schoolId)
                            ->with('student')
                            ->firstOrFail();
            
            // Verify that this teacher teaches the student
            $student = $leave->student;
            
            if (!$student) {
                return back()->with('error', 'Student not found for this leave application.');
            }
            
            // Check if this teacher teaches this student's class
            $teachesThisStudent = TimeTablePeriod::where('teacher', $teacherId)
                ->whereHas('timetable', function($query) use ($student) {
                    $query->where('class_name', $student->class->name ?? '')
                          ->where('section_id', $student->section_id);
                })
                ->exists();
                
            if (!$teachesThisStudent) {
                return back()->with('error', 'You are not authorized to update this leave application.');
            }
            
            // Update the leave application
            $leave->status = $request->status;
            $leave->admin_remarks = $request->admin_remarks;
            $leave->processed_by = Session::get('teacher_id'); // Using teacher ID directly
            $leave->processed_at = now();
            
            $leave->save();
            
            return redirect()->route('teacher.leaveApplications.show', $leave->id)
                        ->with('success', 'Leave application status updated successfully.');
                        
        } catch (\Exception $e) {
            Log::error('Error updating teacher leave application status: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->with('error', 'An error occurred while updating the leave application status: ' . $e->getMessage());
        }
    }
} 