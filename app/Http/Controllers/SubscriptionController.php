<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\School;
use App\Models\SchoolSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the subscriptions.
     */
    public function index()
    {
        $subscriptions = SchoolSubscription::with(['school', 'plan'])->orderBy('created_at', 'desc')->get();
        return view('saasAdmin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Alternative method to display subscriptions (for route compatibility)
     */
    public function showSubscriptions()
    {
        return $this->index();
    }

    /**
     * Show the form for creating a new subscription.
     */
    public function create()
    {
        $schools = School::where('status', 'active')->get();
        $plans = Plan::where('is_active', true)->get();
        return view('saasAdmin.subscriptions.create', compact('schools', 'plans'));
    }

    /**
     * Store a newly created subscription in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price_paid' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();

        try {
            // Check if the school already has an active subscription
            $activeSubscription = SchoolSubscription::where('school_id', $request->school_id)
                ->where('status', 'active')
                ->first();
            
            // If there's an active subscription, mark it as expired
            if ($activeSubscription) {
                $activeSubscription->update(['status' => 'expired']);
            }
            
            // Create the new subscription
            SchoolSubscription::create([
                'school_id' => $request->school_id,
                'plan_id' => $request->plan_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => 'active',
                'price_paid' => $request->price_paid,
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
            ]);

            DB::commit();
            return redirect()->route('saasAdmin.subscriptions')->with('success', 'Subscription created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create subscription: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the subscription details.
     */
    public function show(SchoolSubscription $subscription)
    {
        $subscription->load(['school', 'plan']);
        return view('saasAdmin.subscriptions.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified subscription.
     */
    public function edit(SchoolSubscription $subscription)
    {
        $schools = School::where('status', 'active')->get();
        $plans = Plan::where('is_active', true)->get();
        return view('saasAdmin.subscriptions.edit', compact('subscription', 'schools', 'plans'));
    }

    /**
     * Update the specified subscription in storage.
     */
    public function update(Request $request, SchoolSubscription $subscription)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'school_id' => 'required|exists:schools,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,expired,cancelled,pending',
            'price_paid' => 'nullable|numeric',
            'payment_method' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255'
        ]);

        $subscription->update([
            'plan_id' => $request->plan_id,
            'school_id' => $request->school_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'price_paid' => $request->price_paid,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id
        ]);

        return redirect()->route('saasAdmin.subscriptions')
            ->with('success', 'Subscription updated successfully');
    }

    /**
     * Change the subscription plan for a school
     */
    public function changePlan(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'status' => 'required|in:active,pending,cancelled,expired',
            'payment_status' => 'required|in:paid,pending,failed',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'amount_paid' => 'required|numeric',
        ]);

        // If there's an active subscription, cancel it
        $activeSubscription = SchoolSubscription::where('school_id', $request->school_id)
            ->where('status', 'active')
            ->first();

        if ($activeSubscription) {
            $activeSubscription->update([
                'status' => 'cancelled',
                'end_date' => now(),
                'notes' => 'Cancelled due to plan change'
            ]);
        }

        // Create new subscription
        SchoolSubscription::create([
            'school_id' => $request->school_id,
            'plan_id' => $request->plan_id,
            'billing_cycle' => $request->billing_cycle,
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'amount_paid' => $request->amount_paid,
            'payment_method' => $request->payment_method ?? 'manual',
            'notes' => $request->notes ?? 'Plan changed by SaaS Admin'
        ]);

        return redirect()->route('saasAdmin.subscriptions')
            ->with('success', 'School subscription plan changed successfully');
    }

    /**
     * Cancel the specified subscription.
     */
    public function cancel(SchoolSubscription $subscription)
    {
        $subscription->update(['status' => 'cancelled']);
        return redirect()->route('saasAdmin.subscriptions')->with('success', 'Subscription cancelled successfully.');
    }

    public function showChangePlanForm(Request $request)
    {
        $schools = \App\Models\School::all();
        $plans = \App\Models\Plan::all();
        $selectedSchool = null;
        
        if ($request->has('school_id')) {
            $selectedSchool = \App\Models\School::find($request->school_id);
        }
        return view('saasAdmin.subscriptions.change-plan', compact('schools', 'plans', 'selectedSchool'));
    }
}
