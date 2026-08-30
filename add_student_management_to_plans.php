<?php

// This script adds the student_management feature to all plans
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Feature;
use App\Models\Plan;

// Get the student_management feature
$feature = Feature::where('code', 'student_management')->first();

if (!$feature) {
    echo "Student management feature not found. Creating it now...\n";
    
    $feature = Feature::create([
        'name' => 'Student Management',
        'code' => 'student_management',
        'description' => 'Manage students and related operations',
        'feature_group' => 'academics',
        'value_type' => 'boolean',
        'is_active' => true,
    ]);
    
    echo "Created Student Management feature with ID: {$feature->id}\n";
} else {
    echo "Found Student Management feature with ID: {$feature->id}\n";
}

// Get all plans
$plans = Plan::all();
echo "Found " . $plans->count() . " plans.\n";

// Add the feature to all plans
$added = 0;

foreach ($plans as $plan) {
    // Check if the plan already has this feature
    $exists = DB::table('plan_features')
        ->where('plan_id', $plan->id)
        ->where('feature_id', $feature->id)
        ->exists();
    
    if (!$exists) {
        DB::table('plan_features')->insert([
            'plan_id' => $plan->id,
            'feature_id' => $feature->id,
            'allowed_value' => 'true',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $added++;
        echo "Added feature to plan: {$plan->name} (ID: {$plan->id})\n";
    } else {
        echo "Plan {$plan->name} already has this feature\n";
    }
}

echo "Completed! Added feature to {$added} plans.\n"; 