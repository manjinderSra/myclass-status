<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentLeave;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FixLeavesController extends Controller
{
    /**
     * Display diagnostic information about leave applications
     */
    public function diagnoseLeavesIssue()
    {
        // Get a sample student
        $student = Student::first();
        
        if (!$student) {
            return "No students found in the database.";
        }
        
        // Log student info
        Log::info('Sample student:', [
            'id' => $student->id,
            'student_id' => $student->student_id,
            'name' => $student->first_name . ' ' . $student->last_name
        ]);
        
        // Check existing leave applications for this student
        $leavesByStudentIdField = StudentLeave::where('student_id', $student->student_id)->get();
        $leavesByIdField = StudentLeave::where('student_id', $student->id)->get();
        
        Log::info('Leave applications count:', [
            'using_student_id_field' => $leavesByStudentIdField->count(),
            'using_id_field' => $leavesByIdField->count()
        ]);
        
        // Check schema
        $tableSchema = DB::select("DESCRIBE student_leaves");
        Log::info('Student Leaves Table Schema:', ['schema' => $tableSchema]);
        
        return view('fix-leaves', [
            'student' => $student,
            'leavesByStudentIdField' => $leavesByStudentIdField,
            'leavesByIdField' => $leavesByIdField,
            'tableSchema' => $tableSchema
        ]);
    }
    
    /**
     * Create a test leave application with correct ID
     */
    public function createTestLeave()
    {
        // Get a sample student
        $student = Student::first();
        
        if (!$student) {
            return "No students found in the database.";
        }
        
        try {
            // Create a test leave application with the correct student_id (primary key)
            $leave = StudentLeave::create([
                'school_id' => $student->school_id,
                'student_id' => $student->id, // Using primary key (id)
                'leave_id' => StudentLeave::generateLeaveId(),
                'reason' => 'Test Leave Application',
                'description' => 'This is a test leave application created for diagnostic purposes.',
                'from_date' => now()->addDay(),
                'to_date' => now()->addDays(3),
                'status' => 'pending',
            ]);
            
            Log::info('Test leave application created:', [
                'leave_id' => $leave->leave_id,
                'student_id' => $leave->student_id
            ]);
            
            return "Test leave application created successfully with ID: " . $leave->leave_id;
        } catch (\Exception $e) {
            Log::error('Error creating test leave application:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return "Error creating test leave application: " . $e->getMessage();
        }
    }
}
