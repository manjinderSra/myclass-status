<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StudentLeave;
use App\Models\Student;
use App\Models\School;
use Illuminate\Support\Facades\Log;

class StudentLeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schoolId = 5; // Little Flower School (the same school used for complaints)
        
        // Get some students from this school
        $students = Student::where('school_id', $schoolId)->take(3)->get();
        
        if ($students->isEmpty()) {
            $this->command->error('No students found for school ID ' . $schoolId);
            return;
        }
        
        $this->command->info('Creating sample leave applications for ' . $students->count() . ' students');
        
        // For each student, create sample leave applications
        foreach ($students as $index => $student) {
            // Create a pending leave application
            StudentLeave::create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'leave_id' => 'LEAVE-' . date('Ym') . '-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'reason' => 'Medical Leave',
                'description' => 'I am feeling unwell and need to take a leave of absence for medical treatment.',
                'from_date' => now()->addDays(1),
                'to_date' => now()->addDays(3),
                'status' => 'pending',
                'created_at' => now()->subHours(rand(1, 24)),
            ]);
            
            // Create an approved leave application
            StudentLeave::create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'leave_id' => 'LEAVE-' . date('Ym') . '-' . str_pad($index + 4, 4, '0', STR_PAD_LEFT),
                'reason' => 'Family Function',
                'description' => 'I need to attend a family wedding out of town.',
                'from_date' => now()->addDays(7),
                'to_date' => now()->addDays(9),
                'status' => 'approved',
                'admin_remarks' => 'Approved. Please complete any missed assignments upon return.',
                'processed_by' => 1, // Assuming user ID 1 exists
                'processed_at' => now()->subHours(rand(1, 12)),
                'created_at' => now()->subDays(2),
            ]);
            
            // For the first student, also create a rejected leave application
            if ($index === 0) {
                StudentLeave::create([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'leave_id' => 'LEAVE-' . date('Ym') . '-' . str_pad($index + 7, 4, '0', STR_PAD_LEFT),
                    'reason' => 'Personal Trip',
                    'description' => 'I need to go on a personal trip with my family.',
                    'from_date' => now()->addDays(15),
                    'to_date' => now()->addDays(25),
                    'status' => 'rejected',
                    'admin_remarks' => 'The requested leave duration is too long and will impact your studies significantly.',
                    'processed_by' => 1, // Assuming user ID 1 exists
                    'processed_at' => now()->subHours(6),
                    'created_at' => now()->subDays(1),
                ]);
            }
        }
        
        $this->command->info('Sample leave applications created successfully!');
    }
}
