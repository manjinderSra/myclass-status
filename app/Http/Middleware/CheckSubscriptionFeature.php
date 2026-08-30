<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SchoolSubscription;
use App\Models\School;

class CheckSubscriptionFeature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $featureCode
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $featureCode)
    {
        try {
            $user = Auth::user();
            
            // Log information for debugging
            \Illuminate\Support\Facades\Log::info('CheckSubscriptionFeature middleware:', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'feature' => $featureCode
            ]);
            
            // Allow SaaS admin to access all features
            if ($user->role === 'saasAdmin') {
                return $next($request);
            }
            
            // Get the school - either where the user is admin or where they belong to
            $schoolId = null;
            
            // For school admin users
            if ($user->role === 'school') {
                $school = School::where('admin_id', $user->id)->first();
                if ($school) {
                    $schoolId = $school->id;
                }
            } 
            // For staff/teacher users
            else if ($user->school_id) {
                $schoolId = $user->school_id;
            }
            
            if (!$schoolId) {
                \Illuminate\Support\Facades\Log::warning('No school found for user in feature check:', [
                    'user_id' => $user->id,
                    'role' => $user->role
                ]);
                
                if ($request->ajax()) {
                    return response()->json(['error' => 'Your account is not associated with any school.'], 403);
                }
                
                return redirect()->route('school.login')
                    ->with('error', 'Your account is not associated with any school.');
            }
            
            // Get active subscription for the school
            $activeSubscription = SchoolSubscription::where('school_id', $schoolId)
                ->where('status', 'active')
                ->whereDate('end_date', '>=', now())
                ->with(['plan.features'])
                ->latest()
                ->first();
            
            // Log subscription info
            if ($activeSubscription) {
                \Illuminate\Support\Facades\Log::info('Active subscription found:', [
                    'subscription_id' => $activeSubscription->id,
                    'plan' => $activeSubscription->plan->name,
                    'school_id' => $schoolId
                ]);
            } else {
                \Illuminate\Support\Facades\Log::info('No active subscription found for school:', [
                    'school_id' => $schoolId
                ]);
            }
            
            if ($activeSubscription) {
                // Check if the plan has the required feature
                $hasFeature = $activeSubscription->plan->features
                    ->where('code', $featureCode)
                    ->first();
                
                if ($hasFeature) {
                    \Illuminate\Support\Facades\Log::info('Feature access granted:', [
                        'feature' => $featureCode,
                        'user_id' => $user->id
                    ]);
                    
                    // Check if the feature is value-based (number or text) and has an allowed value
                    if ($hasFeature->value_type !== 'boolean') {
                        // Store the allowed value in the request for possible limit checking
                        $request->attributes->set('feature_value', $hasFeature->pivot->allowed_value);
                    }
                    
                    return $next($request);
                } else {
                    \Illuminate\Support\Facades\Log::info('Feature not included in plan:', [
                        'feature' => $featureCode,
                        'plan' => $activeSubscription->plan->name
                    ]);
                }
            }
            
            // No active subscription or feature not included in plan
            if ($request->ajax()) {
                return response()->json(['error' => 'Your subscription plan does not include access to this feature.'], 403);
            }
            
            \Illuminate\Support\Facades\Log::warning('Feature access denied:', [
                'feature' => $featureCode,
                'user_id' => $user->id,
                'reason' => $activeSubscription ? 'Feature not in plan' : 'No active subscription'
            ]);
            
            return redirect()->route('school.dashboard')
                ->with('error', 'Your subscription plan does not include access to this feature. Please upgrade your plan.');
        } catch (\Exception $e) {
            // Log any exceptions
            \Illuminate\Support\Facades\Log::error('Exception in CheckSubscriptionFeature middleware:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json(['error' => 'An error occurred while checking feature access.'], 500);
            }
            
            return redirect()->route('school.dashboard')
                ->with('error', 'An error occurred while checking feature access. Please try again later.');
        }
    }
} 