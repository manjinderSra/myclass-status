<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function getAttendance(Request $request)
    {
        try {
            $student = $request->user()->load('school');
            
            // Check if we have a valid student
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authenticated student not found',
                    'error_code' => 'STUDENT_NOT_FOUND'
                ], 404);
            }

            // Validate date parameters if provided
            if ($request->has(['start_date', 'end_date'])) {
                $validator = Validator::make($request->all(), [
                    'start_date' => 'required|date|before_or_equal:end_date',
                    'end_date' => 'required|date|after_or_equal:start_date'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid date range',
                        'errors' => $validator->errors()
                    ], 422);
                }

                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate = Carbon::parse($request->end_date)->endOfDay();
            } else {
                // Default to current month if no dates provided
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
            }
            
            // Get attendance records
            $attendanceRecords = StudentAttendance::where('student_id', $student->id)
                ->whereBetween('attendance_date', [$startDate, $endDate])
                ->orderBy('attendance_date', 'asc')
                ->get();
            
            // Calculate statistics
            $totalDays = $attendanceRecords->count();
            $present = $attendanceRecords->where('status', 'present')->count();
            $absent = $attendanceRecords->where('status', 'absent')->count();
            $late = $attendanceRecords->where('status', 'late')->count();
            
            // Calculate attendance percentage
            $attendancePercentage = $totalDays > 0 
                ? round((($present + ($late * 0.5)) / $totalDays) * 100, 2) 
                : 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'date_range' => [
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d')
                    ],
                    'total_days' => $totalDays,
                    'present' => $present,
                    'absent' => $absent,
                    'late' => $late,
                    'percentage' => $attendancePercentage . '%',
                    'records' => $attendanceRecords->map(function($record) {
                        return [
                            'date' => Carbon::parse($record->attendance_date)->format('Y-m-d'),
                            'status' => $record->status,
                            'remarks' => $record->remarks
                        ];
                    })
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching attendance records: ' . $e->getMessage()
            ], 500);
        }
    }
} 