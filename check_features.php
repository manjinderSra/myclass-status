<?php
// Set up Laravel environment
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Import models
use App\Models\Feature;
use App\Models\Plan;
use App\Models\User;
use App\Models\SchoolSubscription;
use App\Helpers\SubscriptionHelper;

// Get all features
echo "All Features:\n";
$features = Feature::all();
foreach ($features as $feature) {
    echo "- {$feature->name} (code: {$feature->code}, group: {$feature->feature_group})\n";
}
echo "\n";

// Get all plans
echo "All Plans:\n";
$plans = Plan::with('features')->get();
foreach ($plans as $plan) {
    echo "Plan: {$plan->name} (ID: {$plan->id})\n";
    echo "  Features:\n";
    foreach ($plan->features as $feature) {
        $allowedValue = $feature->pivot->allowed_value ?? 'null';
        echo "  - {$feature->name} (code: {$feature->code}, allowed_value: {$allowedValue})\n";
    }
    echo "\n";
}

// Get active subscriptions
echo "Active Subscriptions:\n";
$subscriptions = SchoolSubscription::where('status', 'active')
    ->with(['plan', 'school'])
    ->get();

foreach ($subscriptions as $subscription) {
    echo "School: {$subscription->school->name} (ID: {$subscription->school_id})\n";
    echo "  Plan: {$subscription->plan->name}\n";
    echo "  Start: {$subscription->start_date}, End: {$subscription->end_date}\n";
    
    // Check a few key features
    $features = ['attendance', 'examination_management', 'library_management'];
    echo "  Feature Access:\n";
    foreach ($features as $featureCode) {
        $hasAccess = SubscriptionHelper::hasFeature($featureCode, $subscription->school_id);
        echo "  - {$featureCode}: " . ($hasAccess ? 'Yes' : 'No') . "\n";
    }
    echo "\n";
} 