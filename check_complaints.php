<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Complaint;
use App\Models\School;
use App\Models\Student;
use App\Models\User;

try {
    echo "==== Complaint Box Debugging ====\n\n";
    
    // 1. Check if there are any complaints in the database
    $complaintsCount = Complaint::count();
    echo "Total complaints in database: {$complaintsCount}\n";
    
    if ($complaintsCount > 0) {
        $complaints = Complaint::with(['school', 'student'])->get();
        
        echo "\nList of complaints:\n";
        echo "--------------------------------------------------------------------------------\n";
        echo sprintf("%-5s | %-15s | %-15s | %-15s | %-10s | %s\n", 
                    "ID", "Complaint ID", "School ID", "Student ID", "Status", "Nature");
        echo "--------------------------------------------------------------------------------\n";
        
        foreach ($complaints as $complaint) {
            echo sprintf("%-5s | %-15s | %-15s | %-15s | %-10s | %s\n", 
                        $complaint->id, 
                        $complaint->complaint_id, 
                        $complaint->school_id,
                        $complaint->student_id,
                        $complaint->status,
                        $complaint->nature);
        }
        echo "--------------------------------------------------------------------------------\n\n";
        
        // 2. Check if the schools and students exist
        $schoolIds = $complaints->pluck('school_id')->unique();
        $studentIds = $complaints->pluck('student_id')->unique();
        
        echo "Checking schools:\n";
        foreach ($schoolIds as $schoolId) {
            $school = School::find($schoolId);
            if ($school) {
                echo "School ID {$schoolId} exists: {$school->name}\n";
                
                // Check if there are any users with this school_id
                $users = User::where('school_id', $schoolId)->get();
                echo "  - Found " . $users->count() . " users with this school_id\n";
                if ($users->count() > 0) {
                    foreach ($users->take(3) as $user) {
                        echo "    * User ID: {$user->id}, Email: {$user->email}, Role: {$user->role}\n";
                    }
                    if ($users->count() > 3) {
                        echo "    * ... and " . ($users->count() - 3) . " more users\n";
                    }
                }
            } else {
                echo "WARNING: School ID {$schoolId} DOES NOT EXIST!\n";
            }
        }
        
        echo "\nChecking students:\n";
        foreach ($studentIds as $studentId) {
            $student = Student::find($studentId);
            if ($student) {
                echo "Student ID {$studentId} exists: {$student->first_name} {$student->last_name}\n";
            } else {
                echo "WARNING: Student ID {$studentId} DOES NOT EXIST!\n";
            }
        }
    } else {
        echo "\nNo complaints found in the database.\n";
    }
    
    // 3. Check session data
    echo "\nChecking user sessions...\n";
    
    // Look for authenticated users
    $sessions = \Illuminate\Support\Facades\DB::table('sessions')->get();
    echo "Total sessions: " . $sessions->count() . "\n";
    
    foreach ($sessions as $session) {
        if (isset($session->payload)) {
            $payload = unserialize(base64_decode($session->payload));
            if (isset($payload['auth']) && isset($payload['auth']['school_panel'])) {
                $userId = $payload['auth']['school_panel'];
                echo "Found session for user ID: {$userId}\n";
                
                // Check if user exists
                $user = User::find($userId);
                if ($user) {
                    echo "  - User exists: {$user->name}, Email: {$user->email}, School ID: {$user->school_id}\n";
                    
                    // Check if any complaints exist for this school
                    $schoolComplaints = Complaint::where('school_id', $user->school_id)->count();
                    echo "  - Complaints for school ID {$user->school_id}: {$schoolComplaints}\n";
                } else {
                    echo "  - WARNING: User ID {$userId} DOES NOT EXIST!\n";
                }
            }
        }
    }
    
    echo "\n==== Debugging Complete ====\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
} 