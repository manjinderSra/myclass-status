<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\School;
use App\Models\SchoolSubscription;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all plans and schools
        $plans = Plan::all();
        $schools = School::all();
        
        if ($plans->isEmpty()) {
            $this->command->info('No plans found. Creating test plans...');
            
            // Create some test plans if none exist
            $plans = [];
            
            $plans[] = Plan::create([
                'name' => 'Basic Plan',
                'description' => 'Entry level plan for small schools',
                'price' => 29.99,
                'billing_cycle' => 'monthly',
                'max_students' => 100,
                'max_teachers' => 10,
                'max_staff' => 5,
                'is_popular' => false,
                'is_active' => true,
            ]);
            
            $plans[] = Plan::create([
                'name' => 'Standard Plan',
                'description' => 'Medium-sized school plan',
                'price' => 59.99,
                'billing_cycle' => 'monthly',
                'max_students' => 300,
                'max_teachers' => 30,
                'max_staff' => 15,
                'is_popular' => true,
                'is_active' => true,
            ]);
            
            $plans[] = Plan::create([
                'name' => 'Premium Plan',
                'description' => 'Full-featured plan for large schools',
                'price' => 99.99,
                'billing_cycle' => 'monthly',
                'max_students' => 1000,
                'max_teachers' => 100,
                'max_staff' => 50,
                'is_popular' => false,
                'is_active' => true,
            ]);
            
            $plans[] = Plan::create([
                'name' => 'Legacy Plan',
                'description' => 'Old plan no longer offered',
                'price' => 19.99,
                'billing_cycle' => 'monthly',
                'max_students' => 50,
                'max_teachers' => 5,
                'max_staff' => 2,
                'is_popular' => false,
                'is_active' => false,
            ]);
        }
        
        if ($schools->isEmpty()) {
            $this->command->info('No schools found. Creating test schools...');
            
            // Create some test schools
            for ($i = 1; $i <= 10; $i++) {
                School::create([
                    'name' => "Test School {$i}",
                    'admin_name' => "Admin {$i}",
                    'email' => "school{$i}@example.com",
                    'phone' => "123456789{$i}",
                    'address' => "{$i} School Street",
                    'status' => 'active',
                    'registration_date' => Carbon::now(),
                ]);
            }
            
            $schools = School::all();
            
            if ($schools->isEmpty()) {
                $this->command->info('Failed to create schools. Cannot create subscriptions.');
                return;
            }
        }
        
        // Clear existing subscriptions for testing
        SchoolSubscription::truncate();
        
        // Create subscriptions with a clear pattern
        $this->command->info('Creating test subscriptions with a clear pattern...');
        
        // Basic Plan gets 3 active subscriptions
        $basicPlan = $plans->where('name', 'Basic Plan')->first();
        if ($basicPlan) {
            for ($i = 0; $i < 3; $i++) {
                if (isset($schools[$i])) {
                    SchoolSubscription::create([
                        'school_id' => $schools[$i]->id,
                        'plan_id' => $basicPlan->id,
                        'start_date' => Carbon::now()->subDays(30),
                        'end_date' => Carbon::now()->addDays(335),
                        'status' => 'active',
                        'price_paid' => $basicPlan->price,
                        'payment_method' => 'credit_card',
                        'transaction_id' => 'txn_basic_' . uniqid(),
                    ]);
                }
            }
        }
        
        // Standard Plan gets 5 active subscriptions
        $standardPlan = $plans->where('name', 'Standard Plan')->first();
        if ($standardPlan) {
            for ($i = 3; $i < 8; $i++) {
                if (isset($schools[$i])) {
                    SchoolSubscription::create([
                        'school_id' => $schools[$i]->id,
                        'plan_id' => $standardPlan->id,
                        'start_date' => Carbon::now()->subDays(15),
                        'end_date' => Carbon::now()->addDays(350),
                        'status' => 'active',
                        'price_paid' => $standardPlan->price,
                        'payment_method' => 'credit_card',
                        'transaction_id' => 'txn_standard_' . uniqid(),
                    ]);
                }
            }
        }
        
        // Premium Plan gets 2 active subscriptions
        $premiumPlan = $plans->where('name', 'Premium Plan')->first();
        if ($premiumPlan) {
            for ($i = 8; $i < 10; $i++) {
                if (isset($schools[$i])) {
                    SchoolSubscription::create([
                        'school_id' => $schools[$i]->id,
                        'plan_id' => $premiumPlan->id,
                        'start_date' => Carbon::now()->subDays(5),
                        'end_date' => Carbon::now()->addDays(360),
                        'status' => 'active',
                        'price_paid' => $premiumPlan->price,
                        'payment_method' => 'credit_card',
                        'transaction_id' => 'txn_premium_' . uniqid(),
                    ]);
                }
            }
        }
        
        // Legacy Plan gets 1 expired subscription
        $legacyPlan = $plans->where('name', 'Legacy Plan')->first();
        if ($legacyPlan && isset($schools[0])) {
            SchoolSubscription::create([
                'school_id' => $schools[0]->id,
                'plan_id' => $legacyPlan->id,
                'start_date' => Carbon::now()->subDays(400),
                'end_date' => Carbon::now()->subDays(40),
                'status' => 'expired',
                'price_paid' => $legacyPlan->price,
                'payment_method' => 'credit_card',
                'transaction_id' => 'txn_legacy_' . uniqid(),
            ]);
        }
        
        $this->command->info('Test subscriptions created successfully!');
        $this->command->info('Basic Plan: 3 active subscriptions');
        $this->command->info('Standard Plan: 5 active subscriptions');
        $this->command->info('Premium Plan: 2 active subscriptions');
        $this->command->info('Legacy Plan: 1 expired subscription');
    }
} 