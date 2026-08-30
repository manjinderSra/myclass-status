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

// List all schools
echo "All Schools:\n";
$schools = School::all();
foreach ($schools as $school) {
    echo "- {$school->name} (ID: {$school->id})\n";
}
echo "\n";

// Get school ID from command line argument or prompt
$schoolId = $argv[1] ?? null;
if (!$schoolId) {
    echo "Enter School ID to check: ";
    $schoolId = trim(fgets(STDIN));
}

// Get school
$school = School::find($schoolId);
if (!$school) {
    die("School not found with ID {$schoolId}\n");
}

echo "Checking feature access for school: {$school->name}\n\n";

// Get subscription
$subscription = SchoolSubscription::where('school_id', $schoolId)
    ->where('status', 'active')
    ->whereDate('end_date', '>=', now())
    ->with('plan')
    ->latest()
    ->first();

if (!$subscription) {
    die("No active subscription found for this school.\n");
}

echo "Active Subscription:\n";
echo "  Plan: {$subscription->plan->name} (ID: {$subscription->plan_id})\n";
echo "  Start: {$subscription->start_date}, End: {$subscription->end_date}\n\n";

// Check all features
echo "Feature Access:\n";
$features = Feature::all();
foreach ($features as $feature) {
    $hasAccess = SubscriptionHelper::hasFeature($feature->code, $schoolId);
    echo "  - {$feature->name} (code: {$feature->code}): " . ($hasAccess ? 'Yes' : 'No') . "\n";
}

// Double-check plan features directly
echo "\nPlan Features (Direct Check):\n";
$planFeatures = Plan::find($subscription->plan_id)->features;
foreach ($planFeatures as $feature) {
    $allowedValue = $feature->pivot->allowed_value ?? 'null';
    echo "  - {$feature->name} (code: {$feature->code}, allowed_value: {$allowedValue})\n";
} 