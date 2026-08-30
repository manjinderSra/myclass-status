<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\Student;
use App\Models\Complaint;
use Illuminate\Support\Facades\Hash;

try {
    echo "Script started: Creating test student and complaint\n";
    
    // First, check if we have any schools
    $school = School::first();
    
    if (!$school) {
        echo "Creating a test school...\n";
        $school = new School();
        $school->name = "Test School";
        $school->email = "testschool@example.com";
        $school->phone = "1234567890";
        $school->address = "123 Test Street";
        $school->status = "active";
        $school->registration_date = now();
        $school->save();
        echo "Test school created with ID: " . $school->id . "\n";
    } else {
        echo "Using existing school with ID: " . $school->id . "\n";
    }
    
    // Check if we have any students in this school
    $student = Student::where('school_id', $school->id)->first();
    
    if (!$student) {
        echo "Creating a test student...\n";
        $student = new Student();
        $student->first_name = "Test";
        $student->last_name = "Student";
        $student->school_id = $school->id;
        $student->student_id = Student::generateStudentId();
        $student->academic_number = Student::generateAcademicNumber($school->id);
        $student->admission_number = "ADM-" . rand(10000, 99999);
        $student->admission_date = now();
        $student->email = "teststudent@example.com";
        $student->password = Hash::make("password");
        $student->gender = "male";
        $student->dob = now()->subYears(15);
        $student->status = "active";
        $student->save();
        echo "Test student created with ID: " . $student->id . "\n";
    } else {
        echo "Using existing student with ID: " . $student->id . "\n";
    }
    
    // Create a test complaint
    echo "Creating a test complaint...\n";
    $complaint = new Complaint();
    $complaint->school_id = $school->id;
    $complaint->student_id = $student->id;
    $complaint->complaint_id = Complaint::generateComplaintId();
    $complaint->nature = "Test Complaint";
    $complaint->description = "This is a test complaint created for debugging purposes.";
    $complaint->status = "pending";
    $complaint->save();
    
    echo "Test complaint created with ID: " . $complaint->id . "\n";
    echo "Script completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
} 