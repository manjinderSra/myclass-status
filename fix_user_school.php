<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Complaint;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\DB;

try {
    echo "==== Fixing User School Association ====\n\n";
    
    // 1. Find schools with complaints
    $schoolsWithComplaints = Complaint::select('school_id')->distinct()->get()->pluck('school_id');
    
    echo "Schools with complaints: " . implode(', ', $schoolsWithComplaints->toArray()) . "\n";
    
    if ($schoolsWithComplaints->isEmpty()) {
        echo "No schools with complaints found!\n";
        exit(1);
    }
    
    $targetSchoolId = $schoolsWithComplaints->first();
    echo "Using school ID: $targetSchoolId\n";
    
    // 2. Find the school
    $school = School::find($targetSchoolId);
    if (!$school) {
        echo "School not found with ID: $targetSchoolId\n";
        exit(1);
    }
    echo "School name: {$school->name}\n";
    
    // 3. Find active sessions to identify logged-in users
    $activeSessions = [];
    try {
        // Check if sessions table exists
        $hasSessionsTable = DB::getSchemaBuilder()->hasTable('sessions');
        if ($hasSessionsTable) {
            $sessions = DB::table('sessions')->get();
            echo "Found " . $sessions->count() . " sessions\n";
            
            foreach ($sessions as $session) {
                if (isset($session->payload)) {
                    $payload = unserialize(base64_decode($session->payload));
                    if (isset($payload['auth']) && isset($payload['auth']['school_panel'])) {
                        $userId = $payload['auth']['school_panel'];
                        $activeSessions[] = $userId;
                        echo "Found active session for user ID: $userId\n";
                    }
                }
            }
        } else {
            echo "Sessions table does not exist, skipping session check\n";
        }
    } catch (\Exception $e) {
        echo "Error checking sessions: " . $e->getMessage() . "\n";
    }
    
    // 4. Identify users to update - either use active session users or get admin users
    $usersToUpdate = [];
    
    if (!empty($activeSessions)) {
        $usersToUpdate = User::whereIn('id', $activeSessions)->get();
    } else {
        // If no active sessions, find admin users (try to be specific to avoid mass updates)
        $usersToUpdate = User::where(function ($query) {
            $query->whereNull('school_id')
                  ->orWhere('school_id', 0);
        })
        ->where(function ($query) {
            $query->where('role', 'admin')
                  ->orWhere('role', 'school_admin')
                  ->orWhere('email', 'like', '%admin%');
        })
        ->limit(5) // Limit to avoid mass updates
        ->get();
    }
    
    echo "\nUsers to update: " . $usersToUpdate->count() . "\n";
    
    if ($usersToUpdate->isEmpty()) {
        echo "No users found to update!\n";
        echo "Let's look for any user without a school_id:\n";
        
        $anyUsers = User::whereNull('school_id')->orWhere('school_id', 0)->limit(5)->get();
        
        if ($anyUsers->isEmpty()) {
            echo "No users without school_id found either.\n";
            
            // As a last resort, show all users so manual updates can be made
            echo "\nAll users in the system (showing first 10):\n";
            $allUsers = User::limit(10)->get();
            foreach ($allUsers as $user) {
                echo "ID: {$user->id}, Email: {$user->email}, Role: {$user->role}, School ID: {$user->school_id}\n";
            }
            
            exit(1);
        }
        
        $usersToUpdate = $anyUsers;
    }
    
    // 5. Update the users
    echo "\nUpdating users:\n";
    foreach ($usersToUpdate as $user) {
        echo "Updating user ID: {$user->id}, Email: {$user->email}, Role: {$user->role}\n";
        echo "  Previous school_id: " . ($user->school_id ?: 'null') . "\n";
        
        $user->school_id = $targetSchoolId;
        $user->save();
        
        echo "  New school_id: {$user->school_id}\n";
    }
    
    echo "\nDone! Try logging in again - users should now be associated with school ID: $targetSchoolId\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
} 