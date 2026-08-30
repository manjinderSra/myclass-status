<?php

require __DIR__.'/vendor/autoload.php';

// Initialize Laravel application
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SchoolSubscription;
use App\Models\School;

// Get all schools
$schools = School::all();

echo "=== Fixing Subscriptions ===\n\n";

foreach ($schools as $school) {
    echo "Processing school: {$school->name} (ID: {$school->id})\n";
    
    // Get latest subscription for the school
    $subscription = SchoolSubscription::where('school_id', $school->id)
        ->latest()
        ->first();
    
    if ($subscription) {
        echo "  Found subscription (ID: {$subscription->id})\n";
        
        // Update subscription to be active and extend end date
        $subscription->status = 'active';
        $subscription->end_date = now()->addMonth(); // Extend by one month
        $subscription->save();
        
        echo "  Updated subscription:\n";
        echo "    Status: {$subscription->status}\n";
        echo "    End Date: {$subscription->end_date}\n";
    } else {
        echo "  No subscription found for this school.\n";
    }
    
    echo "\n";
}

echo "Done!\n"; 