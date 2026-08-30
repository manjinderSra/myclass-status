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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;

// Get the user for school ID 1
$user = User::where('school_id', 1)->where('role', 'school')->first();

if (!$user) {
    die("School admin user not found for school ID 1\n");
}

// Log in as this user
Auth::login($user);

// Test if the hasFeature directive works
$features = [
    'library_management',
    'examination_management', 
    'attendance'
];

echo "Testing hasFeature directive for user: {$user->name} (ID: {$user->id})\n";
echo "School ID: {$user->school_id}\n\n";

foreach ($features as $feature) {
    $hasFeature = SubscriptionHelper::hasFeature($feature);
    echo "Feature: {$feature}\n";
    echo "  Direct check: " . ($hasFeature ? 'Yes' : 'No') . "\n";
    
    // Test the Blade directive
    $testView = "@hasFeature('{$feature}') YES @else NO @endhasFeature";
    $compiled = Blade::compileString($testView);
    $rendered = eval('?>' . $compiled);
    
    echo "  Blade directive: " . $rendered . "\n\n";
}

// Debug authentication
echo "Auth check: " . (Auth::check() ? 'Yes' : 'No') . "\n";
echo "Auth user role: " . Auth::user()->role . "\n";
echo "Auth user school_id: " . Auth::user()->school_id . "\n"; 