<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\School;

try {
    echo "==== Listing Users ====\n\n";
    
    $users = User::orderBy('id')->get();
    
    echo "Total users: " . $users->count() . "\n\n";
    
    echo sprintf("%-5s | %-30s | %-15s | %-10s | %s\n", 
                "ID", "Email", "Role", "School ID", "School Name");
    echo str_repeat("-", 90) . "\n";
    
    foreach ($users as $user) {
        $schoolName = "N/A";
        if ($user->school_id) {
            $school = School::find($user->school_id);
            $schoolName = $school ? $school->name : "Unknown School";
        }
        
        echo sprintf("%-5s | %-30s | %-15s | %-10s | %s\n", 
                    $user->id, 
                    $user->email, 
                    $user->role ?: "N/A", 
                    $user->school_id ?: "null",
                    $schoolName);
    }
    
    echo "\nUsers without a school ID:\n";
    $usersWithoutSchool = $users->filter(function($user) {
        return !$user->school_id;
    });
    
    if ($usersWithoutSchool->count() > 0) {
        foreach ($usersWithoutSchool as $user) {
            echo "ID: {$user->id}, Email: {$user->email}, Role: {$user->role}\n";
        }
    } else {
        echo "All users have a school ID assigned.\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
} 