<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Get student ID from session
$studentId = \Illuminate\Support\Facades\Session::get('student_id');
if (!$studentId) {
    echo "No student_id found in session.\n";
    exit(1);
}

try {
    $student = \App\Models\Student::findOrFail($studentId);
    echo "Student ID: " . $student->student_id . ", School ID: " . $student->school_id . "\n";
    
    $count = \App\Models\Complaint::where('student_id', $student->student_id)->count();
    echo "Complaints count: " . $count . "\n";
    
    // Create a test complaint
    $complaintData = [
        'school_id' => $student->school_id,
        'student_id' => $student->student_id,
        'complaint_id' => 'TEST-' . time(),
        'nature' => 'Test Issue',
        'description' => 'This is a test complaint to verify functionality',
        'status' => 'Pending'
    ];
    
    $complaint = \App\Models\Complaint::create($complaintData);
    echo "Created test complaint with ID: " . $complaint->id . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 