<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test-student {school_id?} {class_id?} {section_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test student account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get parameters or prompt for them
        $schoolId = $this->argument('school_id');
        $classId = $this->argument('class_id');
        $sectionId = $this->argument('section_id');
        
        if (!$schoolId) {
            $schools = School::pluck('name', 'id')->toArray();
            
            if (empty($schools)) {
                $this->error('No schools found. Please create a school first.');
                return 1;
            }
            
            $schoolId = $this->choice('Select a school:', $schools);
        }
        
        if (!$classId) {
            $classes = SchoolClass::where('school_id', $schoolId)->pluck('name', 'id')->toArray();
            
            if (empty($classes)) {
                $this->error('No classes found for this school. Please create a class first.');
                return 1;
            }
            
            $classId = $this->choice('Select a class:', $classes);
        }
        
        if (!$sectionId) {
            $sections = Section::where('school_id', $schoolId)->pluck('name', 'id')->toArray();
            
            if (empty($sections)) {
                $this->error('No sections found for this school. Please create a section first.');
                return 1;
            }
            
            $sectionId = $this->choice('Select a section:', $sections);
        }
        
        // Generate a student ID
        $studentId = Student::generateStudentId();
        
        // Generate a password
        $plainPassword = 'password123'; // Default password for testing
        
        // Create the student
        $student = new Student([
            'academic_year' => date('Y'),
            'admission_number' => 'TEST' . rand(1000, 9999),
            'academic_number' => Student::generateAcademicNumber($schoolId),
            'admission_date' => now(),
            'status' => 'active',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'class_id' => $classId,
            'section_id' => $sectionId,
            'school_id' => $schoolId,
            'gender' => 'male',
            'dob' => now()->subYears(15),
            'email' => 'test.student' . rand(100, 999) . '@example.com',
            'student_id' => $studentId,
            'password' => Hash::make($plainPassword)
        ]);
        
        $student->save();
        
        $this->info('Test student created successfully!');
        $this->table(
            ['Student ID', 'Password', 'Name', 'Email'],
            [[$studentId, $plainPassword, $student->first_name . ' ' . $student->last_name, $student->email]]
        );
        
        return 0;
    }
}
