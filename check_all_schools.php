<?php
// Set up Laravel environment
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Import models
use App\Models\Feature;
use App\Models\Plan;
use App\Models\User;
use App\Models\School;
use App\Models\SchoolSubscription;
use App\Helpers\SubscriptionHelper;

echo "===== ALL SCHOOLS WITH ADMINS =====\n";
$schools = School::all();
foreach ($schools as $school) {
    echo "School: {$school->name} (ID: {$school->id})\n";
    echo "  Admin ID: " . ($school->admin_id ?? 'None') . "\n";
    
    if ($school->admin_id) {
        $admin = User::find($school->admin_id);
        echo "  Admin: " . ($admin ? $admin->name : 'Not found') . "\n";
        echo "  Admin Email: " . ($admin ? $admin->email : 'N/A') . "\n";
        echo "  Admin Role: " . ($admin ? $admin->role : 'N/A') . "\n";
    }
    
    // Check active subscription
    $subscription = SchoolSubscription::where('school_id', $school->id)
        ->where('status', 'active')
        ->whereDate('end_date', '>=', now())
        ->with('plan')
        ->latest()
        ->first();
    
    if ($subscription) {
        echo "  Active Plan: {$subscription->plan->name}\n";
        
        // Test key features
        $testFeatures = ['library_management', 'examination_management', 'attendance'];
        echo "  Feature Access:\n";
        foreach ($testFeatures as $feature) {
            $hasAccess = SubscriptionHelper::hasFeature($feature, $school->id);
            echo "    - {$feature}: " . ($hasAccess ? 'Yes' : 'No') . "\n";
        }
    } else {
        echo "  No active subscription\n";
    }
    
    echo "\n";
}

// Test with a real user session
echo "===== TESTING WITH REAL USER SESSION =====\n";
// Try to get an admin for each school
foreach ($schools as $school) {
    if (!$school->admin_id) continue;
    
    $admin = User::find($school->admin_id);
    if (!$admin) continue;
    
    echo "Testing with admin: {$admin->name} (School: {$school->name})\n";
    
    // Log in as this admin
    Auth::login($admin);
    
    // Test key features
    $testFeatures = ['library_management', 'examination_management', 'attendance'];
    echo "  Feature Access when logged in:\n";
    foreach ($testFeatures as $feature) {
        $hasAccess = SubscriptionHelper::hasFeature($feature);
        echo "    - {$feature}: " . ($hasAccess ? 'Yes' : 'No') . "\n";
    }
    
    // Log out
    Auth::logout();
    echo "\n";
} 