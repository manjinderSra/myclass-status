<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Feature;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the pricing page with all available plans.
     */
    public function pricing()
    {
        $plans = Plan::where('is_active', true)->get();
        return view('landing.pricing', compact('plans'));
    }

    /**
     * Display detailed information about a specific plan.
     */
    public function planDetails($id)
    {
        $plan = Plan::with('features')->findOrFail($id);
        
        // Group features by category
        $featureGroups = [];
        foreach ($plan->features as $feature) {
            if (!isset($featureGroups[$feature->feature_group])) {
                $featureGroups[$feature->feature_group] = [];
            }
            $featureGroups[$feature->feature_group][] = $feature;
        }
        
        return view('landing.plan-details', compact('plan', 'featureGroups'));
    }

    /**
     * Display the plan purchase page.
     * Requires authentication.
     */
    public function planPurchase($id)
    {
        // Check if user is authenticated, if not redirect to login
        if (!auth()->check()) {
            // Store plan ID in session to redirect back after login
            session(['selected_plan_id' => $id]);
            return redirect()->route('school.login')->with('message', 'Please log in to continue with your subscription.');
        }
        
        // Check if the authenticated user is a school admin
        if (auth()->user()->role !== 'school') {
            return redirect()->route('school.login')
                ->with('error', 'You need a school administrator account to purchase a subscription.');
        }
        
        $plan = Plan::with('features')->findOrFail($id);
        
        // Group features by category
        $featureGroups = [];
        foreach ($plan->features as $feature) {
            if (!isset($featureGroups[$feature->feature_group])) {
                $featureGroups[$feature->feature_group] = [];
            }
            $featureGroups[$feature->feature_group][] = $feature;
        }
        
        return view('landing.plan-purchase', compact('plan', 'featureGroups'));
    }

    /**
     * Process the school registration and subscription.
     */
    public function registerSchool(Request $request, $planId)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        // Find the plan
        $plan = Plan::findOrFail($planId);

        // Create transaction or process payment here
        // ...

        // Create user with schoolAdmin role
        $user = \App\Models\User::create([
            'name' => $request->school_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'school',
        ]);

        // Create school
        $school = \App\Models\School::create([
            'name' => $request->school_name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'status' => 'active',
            'admin_id' => $user->id,
        ]);

        // Create subscription
        $subscription = \App\Models\SchoolSubscription::create([
            'school_id' => $school->id,
            'plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(), // Default to monthly, can be changed based on plan
            'status' => 'active',
            'price_paid' => $plan->price,
            'payment_method' => 'online', // or other methods
        ]);

        // Log in the user
        auth()->login($user);

        return redirect()->route('school.dashboard')->with('success', 'Welcome to My Classes Status! Your school has been registered successfully.');
    }
}
