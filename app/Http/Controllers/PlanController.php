<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Plan;
use App\Models\SchoolSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    /**
     * Display a listing of the plans.
     */
    public function index()
    {
        // Debug logs
        \Log::info('PlanController index method called');
        
        $plans = Plan::all();
        \Log::info('Plans retrieved: ' . $plans->count());
        
        // Direct SQL approach with explicit debugging
        $subscriptionCountsSQL = "
            SELECT plan_id, COUNT(*) as user_count 
            FROM school_subscriptions 
            WHERE status = 'active' 
            GROUP BY plan_id
        ";
        
        \Log::info('Executing SQL: ' . $subscriptionCountsSQL);
        $planCountsRaw = DB::select($subscriptionCountsSQL);
        
        // Convert to associative array
        $planCounts = [];
        foreach ($planCountsRaw as $row) {
            $planCounts[$row->plan_id] = $row->user_count;
        }
        
        \Log::info('Plan counts raw: ' . json_encode($planCountsRaw));
        \Log::info('Plan counts array: ' . json_encode($planCounts));
        
        // Assign the counts to each plan
        foreach ($plans as $plan) {
            $count = isset($planCounts[$plan->id]) ? $planCounts[$plan->id] : 0;
            $plan->active_subscriptions_count = $count;
            \Log::info("Plan {$plan->id} ({$plan->name}) active subscriptions: {$plan->active_subscriptions_count}");
        }
        
        $subscriptions = SchoolSubscription::with(['school', 'plan'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        \Log::info('Recent subscriptions: ' . $subscriptions->count());
        
        return view('saasAdmin.plans.index', compact('plans', 'subscriptions'));
    }

    /**
     * Show the form for creating a new plan.
     */
    public function create()
    {
        $features = Feature::where('is_active', true)->get();
        $featureGroups = Feature::select('feature_group')->distinct()->pluck('feature_group');
        
        return view('saasAdmin.plans.create', compact('features', 'featureGroups'));
    }

    /**
     * Store a newly created plan in storage.
     */
    public function store(Request $request)
    {
        // Debug information - log received data 
        \Log::info('Plan creation attempt - Received data:', $request->all());
        
        // TEMPORARY FIX: Basic plan creation without transactions or feature attachments
        try {
            // Just create the plan record first to see if that works
            $plan = new Plan();
            $plan->name = $request->name;
            $plan->description = $request->description ?? '';
            $plan->price = $request->price;
            $plan->billing_cycle = $request->billing_cycle ?? 'monthly';
            $plan->max_students = $request->max_students ?? 0;
            $plan->max_teachers = $request->max_teachers ?? 0;
            $plan->max_staff = $request->max_staff ?? 0;
            $plan->is_popular = $request->has('is_popular');
            $plan->is_active = $request->has('is_active');
            
            // Save without transaction
            $plan->save();
            
            \Log::info('Basic plan saved successfully with ID: ' . $plan->id);
            
            // First attempt success - now add features if available
            if ($request->has('features') && is_array($request->features) && count($request->features) > 0) {
                \Log::info('Attempting to attach features to plan');
                
                // Find the resource limit features
                $maxStudentFeature = Feature::where('code', 'max_students')->first();
                $maxTeacherFeature = Feature::where('code', 'max_teachers')->first();
                $maxStaffFeature = Feature::where('code', 'max_staff')->first();
                
                // Get the selected features
                $selectedFeatures = $request->features;
                
                // Add resource limit features automatically if they exist
                if ($maxStudentFeature && !in_array($maxStudentFeature->id, $selectedFeatures)) {
                    $selectedFeatures[] = $maxStudentFeature->id;
                }
                
                if ($maxTeacherFeature && !in_array($maxTeacherFeature->id, $selectedFeatures)) {
                    $selectedFeatures[] = $maxTeacherFeature->id;
                }
                
                if ($maxStaffFeature && !in_array($maxStaffFeature->id, $selectedFeatures)) {
                    $selectedFeatures[] = $maxStaffFeature->id;
                }
                
                // Prepare feature values to attach
                foreach ($selectedFeatures as $featureId) {
                    try {
                        $value = null;
                        
                        // Set values for resource limit features from the plan fields
                        if ($maxStudentFeature && $featureId == $maxStudentFeature->id) {
                            $value = (string)$request->max_students;
                        } elseif ($maxTeacherFeature && $featureId == $maxTeacherFeature->id) {
                            $value = (string)$request->max_teachers;
                        } elseif ($maxStaffFeature && $featureId == $maxStaffFeature->id) {
                            $value = (string)$request->max_staff;
                        } else {
                            // For other features, use the provided value
                            $value = $request->feature_values[$featureId] ?? null;
                        }
                        
                        // Attach features one by one
                        $plan->features()->attach($featureId, ['allowed_value' => $value]);
                        \Log::info("Attached feature ID {$featureId} with value: " . ($value ?? 'NULL'));
                    } catch (\Exception $e) {
                        \Log::warning("Failed to attach feature ID {$featureId}: " . $e->getMessage());
                        // Continue with other features even if one fails
                    }
                }
            } else {
                \Log::info('No features to attach');
            }
            
            return redirect()->route('saasAdmin.plans')->with('success', 'Plan created successfully.');
        } catch (\Exception $e) {
            \Log::error('Plan creation failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Failed to create plan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified plan.
     */
    public function edit(Plan $plan)
    {
        $features = Feature::where('is_active', true)->get();
        $featureGroups = Feature::select('feature_group')->distinct()->pluck('feature_group');
        $planFeatures = $plan->features->pluck('pivot.allowed_value', 'id')->toArray();
        
        return view('saasAdmin.plans.edit', compact('plan', 'features', 'featureGroups', 'planFeatures'));
    }

    /**
     * Update the specified plan in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        // Debug information - log received data 
        \Log::info('Plan update attempt - Received data:', $request->all());
        
        try {
            // First, update the basic plan record
            $plan->name = $request->name;
            $plan->description = $request->description ?? '';
            $plan->price = $request->price;
            $plan->billing_cycle = $request->billing_cycle ?? 'monthly';
            $plan->max_students = $request->max_students ?? 0;
            $plan->max_teachers = $request->max_teachers ?? 0;
            $plan->max_staff = $request->max_staff ?? 0;
            $plan->is_popular = $request->has('is_popular');
            $plan->is_active = $request->has('is_active');
            
            // Save without transaction
            $plan->save();
            
            \Log::info('Basic plan updated successfully for ID: ' . $plan->id);
            
            // Clear existing features
            $plan->features()->detach();
            \Log::info('Existing features detached from plan');
            
            // Now add features if available
            if ($request->has('features') && is_array($request->features) && count($request->features) > 0) {
                \Log::info('Attempting to attach new features to plan');
                
                // Find the resource limit features
                $maxStudentFeature = Feature::where('code', 'max_students')->first();
                $maxTeacherFeature = Feature::where('code', 'max_teachers')->first();
                $maxStaffFeature = Feature::where('code', 'max_staff')->first();
                
                // Get the selected features
                $selectedFeatures = $request->features;
                
                // Add resource limit features automatically if they exist
                if ($maxStudentFeature && !in_array($maxStudentFeature->id, $selectedFeatures)) {
                    $selectedFeatures[] = $maxStudentFeature->id;
                }
                
                if ($maxTeacherFeature && !in_array($maxTeacherFeature->id, $selectedFeatures)) {
                    $selectedFeatures[] = $maxTeacherFeature->id;
                }
                
                if ($maxStaffFeature && !in_array($maxStaffFeature->id, $selectedFeatures)) {
                    $selectedFeatures[] = $maxStaffFeature->id;
                }
                
                // Attach features with their values
                foreach ($selectedFeatures as $featureId) {
                    try {
                        $value = null;
                        
                        // Set values for resource limit features from the plan fields
                        if ($maxStudentFeature && $featureId == $maxStudentFeature->id) {
                            $value = (string)$request->max_students;
                        } elseif ($maxTeacherFeature && $featureId == $maxTeacherFeature->id) {
                            $value = (string)$request->max_teachers;
                        } elseif ($maxStaffFeature && $featureId == $maxStaffFeature->id) {
                            $value = (string)$request->max_staff;
                        } else {
                            // For other features, use the provided value
                            $value = $request->feature_values[$featureId] ?? null;
                        }
                        
                        // Attach features one by one
                        $plan->features()->attach($featureId, ['allowed_value' => $value]);
                        \Log::info("Attached feature ID {$featureId} with value: " . ($value ?? 'NULL'));
                    } catch (\Exception $e) {
                        \Log::warning("Failed to attach feature ID {$featureId}: " . $e->getMessage());
                        // Continue with other features even if one fails
                    }
                }
            } else {
                \Log::info('No features to attach');
            }
            
            return redirect()->route('saasAdmin.plans')->with('success', 'Plan updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Plan update failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with('error', 'Failed to update plan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified plan from storage.
     */
    public function destroy(Plan $plan)
    {
        // Check if there are any active subscriptions for this plan
        $activeSubscriptions = $plan->subscriptions()->where('status', 'active')->count();
        
        if ($activeSubscriptions > 0) {
            return back()->with('error', 'Cannot delete plan with active subscriptions.');
        }
        
        $plan->delete();
        return redirect()->route('saasAdmin.plans')->with('success', 'Plan deleted successfully.');
    }

    /**
     * Debug endpoint to check plan subscription counts
     */
    public function debugSubscriptionCounts()
    {
        $plans = Plan::all();
        
        // Direct SQL approach to count active users per plan
        $planCounts = DB::table('school_subscriptions')
            ->select('plan_id', DB::raw('COUNT(DISTINCT school_id) as user_count'))
            ->where('status', 'active')
            ->groupBy('plan_id')
            ->pluck('user_count', 'plan_id')
            ->toArray();
        
        // Assign the counts to each plan
        foreach ($plans as $plan) {
            $plan->active_subscriptions_count = $planCounts[$plan->id] ?? 0;
        }
        
        // Also get all subscriptions for debugging
        $subscriptions = SchoolSubscription::with(['school', 'plan'])->get();
        
        return response()->json([
            'plans' => $plans->map(function($plan) {
                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'is_active' => $plan->is_active,
                    'subscription_count' => $plan->active_subscriptions_count
                ];
            }),
            'total_active_subscriptions' => SchoolSubscription::where('status', 'active')->count(),
            'subscriptions' => $subscriptions->map(function($sub) {
                return [
                    'id' => $sub->id,
                    'plan_id' => $sub->plan_id,
                    'plan_name' => $sub->plan->name ?? 'Unknown',
                    'school_id' => $sub->school_id,
                    'school_name' => $sub->school->name ?? 'Unknown',
                    'status' => $sub->status,
                    'dates' => [
                        'start' => $sub->start_date ? $sub->start_date->format('Y-m-d') : null,
                        'end' => $sub->end_date ? $sub->end_date->format('Y-m-d') : null
                    ]
                ];
            })
        ]);
    }
}
