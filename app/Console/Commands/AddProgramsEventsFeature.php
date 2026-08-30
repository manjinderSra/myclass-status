<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Feature;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;

class AddProgramsEventsFeature extends Command
{
    protected $signature = 'feature:add-programs-events';
    protected $description = 'Add Programs & Events feature to all plans';

    public function handle()
    {
        // First, create the feature if it doesn't exist
        $feature = Feature::firstOrCreate(
            ['code' => 'programs_events'],
            [
                'name' => 'Programs & Events',
                'description' => 'Allow schools to manage programs and events',
                'feature_group' => 'content',
                'value_type' => 'boolean',
                'is_active' => true
            ]
        );

        $this->info("Feature created: {$feature->name}");

        // Add the feature to all active plans
        $plans = Plan::where('is_active', true)->get();
        $count = 0;

        foreach ($plans as $plan) {
            // Check if the feature is already assigned to the plan
            $exists = DB::table('plan_features')
                ->where('plan_id', $plan->id)
                ->where('feature_id', $feature->id)
                ->exists();

            if (!$exists) {
                DB::table('plan_features')->insert([
                    'plan_id' => $plan->id,
                    'feature_id' => $feature->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $count++;
            }
        }

        $this->info("Feature added to {$count} plans");
        return 0;
    }
} 