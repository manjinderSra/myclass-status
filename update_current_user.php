<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

try {
    echo "==== Updating Current User ====\n\n";
    
    // Prompt for user input
    echo "Enter the email address of the user you want to update: ";
    $email = trim(fgets(STDIN));
    
    $user = User::where('email', $email)->first();
    
    if (!$user) {
        echo "No user found with email: $email\n";
        
        echo "\nAvailable users (showing first 10):\n";
        $allUsers = User::limit(10)->get();
        foreach ($allUsers as $u) {
            echo "ID: {$u->id}, Email: {$u->email}, Role: {$u->role}, School ID: {$u->school_id}\n";
        }
        
        exit(1);
    }
    
    echo "Found user:\n";
    echo "ID: {$user->id}, Email: {$user->email}, Role: {$user->role}, School ID: " . ($user->school_id ?: 'null') . "\n\n";
    
    echo "Enter the school ID to set (or press Enter to use 5, which has complaints): ";
    $schoolId = trim(fgets(STDIN));
    
    if (empty($schoolId)) {
        $schoolId = 5; // Default to school ID 5 which has complaints
    }
    
    // Update the user
    $user->school_id = $schoolId;
    $user->save();
    
    echo "\nUser updated successfully!\n";
    echo "New school ID: {$user->school_id}\n";
    echo "\nTry logging in again with this user.\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
} 