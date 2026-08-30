<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        try {
            $studentId = Session::get('student_id');
            $student = Student::findOrFail($studentId);
            
            // Get current month's date range
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            
            // Get attendance records for current month
            $attendanceRecords = StudentAttendance::where('student_id', $studentId)
                ->whereBetween('attendance_date', [$startDate, $endDate])
                ->orderBy('attendance_date')
                ->get();
            
            // Calculate statistics
            $totalDays = $attendanceRecords->count();
            $present = $attendanceRecords->where('status', 'present')->count();
            $absent = $attendanceRecords->where('status', 'absent')->count();
            $late = $attendanceRecords->where('status', 'late')->count();
            $leave = $attendanceRecords->where('status', 'leave')->count();
            
            $attendancePercentage = $totalDays > 0 
                ? round((($present + ($late * 0.5)) / $totalDays) * 100, 2) 
                : 0;
            
            return view('client.student.attendance.index', compact(
                'student',
                'attendanceRecords',
                'totalDays',
                'present',
                'absent',
                'late',
                'leave',
                'attendancePercentage',
                'startDate',
                'endDate'
            ));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error fetching attendance records: ' . $e->getMessage());
        }
    }
    
    public function getAttendanceData(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);
            
            $studentId = Session::get('student_id');
            
            // Get attendance records for selected date range
            $attendanceRecords = StudentAttendance::where('student_id', $studentId)
                ->whereBetween('attendance_date', [$request->start_date, $request->end_date])
                ->orderBy('attendance_date')
                ->get();
            
            // Calculate statistics
            $totalDays = $attendanceRecords->count();
            $present = $attendanceRecords->where('status', 'present')->count();
            $absent = $attendanceRecords->where('status', 'absent')->count();
            $late = $attendanceRecords->where('status', 'late')->count();
            $leave = $attendanceRecords->where('status', 'leave')->count();
            
            $attendancePercentage = $totalDays > 0 
                ? round((($present + ($late * 0.5)) / $totalDays) * 100, 2) 
                : 0;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'records' => $attendanceRecords,
                    'statistics' => [
                        'total_days' => $totalDays,
                        'present' => $present,
                        'absent' => $absent,
                        'late' => $late,
                        'leave' => $leave,
                        'attendance_percentage' => $attendancePercentage
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching attendance data: ' . $e->getMessage()
            ], 500);
        }
    }
} 