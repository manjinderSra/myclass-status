<?php

require __DIR__.'/vendor/autoload.php';

// Initialize Laravel application
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\User;

// Get all schools
$schools = School::all();

echo "=== Schools and Their Subscriptions ===\n\n";

foreach ($schools as $school) {
    $admin = User::find($school->admin_id);
    
    echo "School: {$school->name} (ID: {$school->id})\n";
    echo "Admin: " . ($admin ? $admin->name . " (ID: {$admin->id})" : "No admin assigned") . "\n";
    
    // Get active subscriptions
    $activeSubscription = SchoolSubscription::where('school_id', $school->id)
        ->where('status', 'active')
        ->whereDate('end_date', '>=', now())
        ->with(['plan'])
        ->latest()
        ->first();
    
    if ($activeSubscription) {
        echo "Active Subscription Found:\n";
        echo "  Plan: {$activeSubscription->plan->name}\n";
        echo "  Start Date: {$activeSubscription->start_date}\n";
        echo "  End Date: {$activeSubscription->end_date}\n";
        echo "  Status: {$activeSubscription->status}\n";
    } else {
        // Check if there are any subscriptions at all
        $anySubscription = SchoolSubscription::where('school_id', $school->id)->latest()->first();
        
        if ($anySubscription) {
            echo "No active subscription found, but there is an inactive one:\n";
            echo "  Plan ID: {$anySubscription->plan_id}\n";
            echo "  Start Date: {$anySubscription->start_date}\n";
            echo "  End Date: {$anySubscription->end_date}\n";
            echo "  Status: {$anySubscription->status}\n";
        } else {
            echo "No subscriptions found for this school.\n";
        }
    }
    
    echo "\n-----------------------------------\n\n";
}

echo "=== User-School Relationships ===\n\n";

// Check users with 'school' role
$schoolAdmins = User::where('role', 'school')->get();

foreach ($schoolAdmins as $admin) {
    echo "User: {$admin->name} (ID: {$admin->id})\n";
    
    // Check if user is admin of any school
    $adminOfSchool = School::where('admin_id', $admin->id)->first();
    
    if ($adminOfSchool) {
        echo "Admin of school: {$adminOfSchool->name} (ID: {$adminOfSchool->id})\n";
    } else {
        echo "Not admin of any school.\n";
    }
    
    echo "\n";
} 