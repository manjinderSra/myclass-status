<?php

namespace App\Http\Controllers;
use App\Models\SchoolSubscription;
use App\Models\School;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class SaasAdminController extends Controller
{
    public function __construct()
    {
        // Use auth middleware for all routes except login/register routes
        $this->middleware('auth')->except(['showLogin', 'showRegister', 'login', 'register']);
        
        // Use the EnsureSaasAdmin middleware for protected routes
        $this->middleware('ensure.saasAdmin')->except(['showLogin', 'showRegister', 'login', 'register']);
    }
    
    public function showLogin()
    {
        return view('saasAdmin.auth.login');
    }
    
    public function showRegister()
    {
        return view('saasAdmin.auth.register');
    }
    
   public function showDashboard()
{
    $schools = \App\Models\School::with([
            'admin',
            'subscriptions' => function ($query) {
                $query->where('status', 'active')
                      ->whereDate('end_date', '>=', now())
                      ->latest();
            },
            'subscriptions.plan'
        ])
        ->withCount(['students', 'teachers']) // This adds students_count and teachers_count
        ->get();

    return view('saasAdmin.dashboard.dashboard', compact('schools'));
}

    
    public function showSchools()
    {
        // The middleware will ensure the user is a saasAdmin
        
        // Get all schools with their admins and active subscriptions
        $schools = \App\Models\School::with(['admin', 'subscriptions' => function($query) {
            $query->where('status', 'active')
                  ->whereDate('end_date', '>=', now())
                  ->latest();
        }, 'subscriptions.plan'])
        ->get();
        
        return view('saasAdmin.schools.index', compact('schools'));
    }
    
    public function showPlans()
    {
        // The middleware will ensure the user is a saasAdmin
        
        $plans = \App\Models\Plan::all();
        
        // Count distinct active users (schools) per plan
        $subscriptionCountsSQL = "
            SELECT plan_id, COUNT(DISTINCT school_id) as user_count 
            FROM school_subscriptions 
            WHERE status = 'active' 
            GROUP BY plan_id
        ";
        
        $planCountsRaw = \Illuminate\Support\Facades\DB::select($subscriptionCountsSQL);
        
        // Convert to associative array
        $planCounts = [];
        foreach ($planCountsRaw as $row) {
            $planCounts[$row->plan_id] = $row->user_count;
        }
        
        // Assign the counts to each plan
        foreach ($plans as $plan) {
            $plan->active_subscriptions_count = isset($planCounts[$plan->id]) ? $planCounts[$plan->id] : 0;
        }
        
        $subscriptions = \App\Models\SchoolSubscription::with(['school', 'plan'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('saasAdmin.plans.index', compact('plans', 'subscriptions'));
    }
    
    public function showFeatures()
    {
        // The middleware will ensure the user is a saasAdmin
        
        $features = \App\Models\Feature::all();
        $featureGroups = \App\Models\Feature::select('feature_group')->distinct()->pluck('feature_group');
        return view('saasAdmin.features.index', compact('features', 'featureGroups'));
    }
    
    public function showSubscriptions()
    {
        // The middleware will ensure the user is a saasAdmin
        
        $subscriptions = \App\Models\SchoolSubscription::with(['school', 'plan'])->orderBy('created_at', 'desc')->get();
        return view('saasAdmin.subscriptions.index', compact('subscriptions'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'saasAdmin',
        ]);

        Auth::login($user);

        return redirect()->route('saasAdmin.dashboard');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Log login attempt for debugging
            \Illuminate\Support\Facades\Log::info('SaasAdmin login attempt:', [
                'email' => $request->email
            ]);

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                
                // Check if user has the saasAdmin role
                if ($user->role !== 'saasAdmin') {
                    // Log invalid role attempt
                    \Illuminate\Support\Facades\Log::warning('Invalid role for SaasAdmin access:', [
                        'user_id' => $user->id,
                        'role' => $user->role
                    ]);
                    
                    Auth::logout();
                    
                    // If the user is a school role, suggest they might want the school panel
                    if (in_array($user->role, ['school', 'staff', 'teacher', 'administration', 'finance', 'library'])) {
                        return back()->withErrors([
                            'email' => 'You do not have administrator access. Try the School Panel instead.'
                        ])->with('redirect_suggestion', route('school.login'));
                    }
                    
                    return back()->withErrors([
                        'email' => 'You do not have administrator access.'
                    ]);
                }
                
                // Log successful login
                \Illuminate\Support\Facades\Log::info('SaasAdmin logged in successfully:', [
                    'id' => $user->id,
                    'email' => $user->email
                ]);
                
                $request->session()->regenerate();
                return redirect()->route('saasAdmin.dashboard');
            }

            // Log failed login attempt
            \Illuminate\Support\Facades\Log::warning('Failed SaasAdmin login attempt:', [
                'email' => $request->email
            ]);

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        } catch (\Exception $e) {
            // Log any unexpected errors
            \Illuminate\Support\Facades\Log::error('Exception during SaasAdmin login: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'email' => 'An error occurred during login. Please try again later.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('saasAdmin.login');
    }

    public function addSchool(Request $request)
    {
        $schools = \App\Models\School::all();
        $plans = \App\Models\Plan::all();
        $selectedSchool = null;
        
        if ($request->has('school_id')) {
            $selectedSchool = \App\Models\School::find($request->school_id);
        }
        return view('saasAdmin.schools.addSchool', compact('schools', 'plans', 'selectedSchool'));
    }
    
public function changePlan(Request $request)
{
    $request->validate([
        'plan_id' => 'required|exists:plans,id',
        'billing_cycle' => 'required|in:monthly,yearly',
        'status' => 'required|in:active,pending,cancelled,expired',
        'payment_status' => 'required|in:paid,pending,failed',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'amount_paid' => 'required|numeric',
        'payment_method' => 'nullable|string',
        'notes' => 'nullable|string',

        // For new school case
        'school_id' => 'required',
        'new_school_name' => 'required_if:school_id,new|string|max:255',
        'new_school_email' => 'required_if:school_id,new|email|unique:users,email',
        'new_school_password' => 'required_if:school_id,new|min:8|confirmed',
    ]);

    if ($request->school_id === 'new') {
        $user = User::create([
            'name' => $request->new_school_name,
            'email' => $request->new_school_email,
            'password' => bcrypt($request->new_school_password),
            'role' => 'school',
        ]);

        $school = School::create([
            'name' => $request->new_school_name,
            'email' => $request->new_school_email,
            'status' => 'active',
            'admin_id' => $user->id,
        ]);

        $schoolId = $school->id;
    } else {
        $schoolId = $request->school_id;

        $activeSubscription = SchoolSubscription::where('school_id', $schoolId)
            ->where('status', 'active')
            ->first();

        if ($activeSubscription) {
            $activeSubscription->update([
                'status' => 'cancelled',
                'end_date' => now(),
                'notes' => 'Cancelled due to plan change'
            ]);
        }
    }

    SchoolSubscription::create([
        'school_id' => $schoolId,
        'plan_id' => $request->plan_id,
        'billing_cycle' => $request->billing_cycle,
        'status' => $request->status,
        'payment_status' => $request->payment_status,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'amount_paid' => $request->amount_paid,
        'payment_method' => $request->payment_method ?? 'manual',
        'notes' => $request->notes ?? 'Plan changed by admin'
    ]);

    return redirect()->route('saasAdmin.subscriptions')
        ->with('success', 'Updated Successfully.');
}




  public function editSchoolSubscription(School $school)
    {
        // Eager load the admin and subscriptions relationship for the school
        $school->load('admin', 'subscriptions.plan');

        // Attempt to find the most "relevant" subscription for editing.
        // Priority: active, then any latest by start_date, or null.
        $currentSubscription = $school->subscriptions->where('status', 'active')->first();

        // If no active subscription, try to get the latest one regardless of status
        if (!$currentSubscription && $school->subscriptions->isNotEmpty()) {
            $currentSubscription = $school->subscriptions->sortByDesc('start_date')->first();
        }

        $plans = Plan::all();

        return view('saasAdmin.schools.editSchool', compact('school', 'plans', 'currentSubscription'));
    }
    
    
    public function updateSchoolSubscription(Request $request, School $school)
    {
    
    $request->validate([
            'school_name' => 'required|string|max:255',
           
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'status' => 'required|in:active,pending,cancelled,expired',
            'payment_status' => 'required|in:paid,pending,failed',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date', // Changed from 'after' to 'after_or_equal'
            'amount_paid' => 'required|numeric|min:0', // Added min:0
            'payment_method' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
       
            // Update School details
            $school->update([
                'name' => $request->school_name,
                'email' => $request->admin_email, // Update school's email
                // Update other school fields if any
            ]);

            // Update associated Admin User details
            if ($school->admin) {
                $school->admin->update([
                    'name' => $request->school_name, // You might want a dedicated admin name field in the form
                    'email' => $request->admin_email,
                ]);
            } else {
                // Handle case where a school might not have an admin initially,
                // perhaps create one or log an error.
                // For now, we assume an admin user exists.
            }

            // Find the current main subscription for the school
            // Similar logic as editSchoolSubscription to find the most relevant one
            $currentSubscription = $school->subscriptions->where('status', 'active')->first();
            if (!$currentSubscription && $school->subscriptions->isNotEmpty()) {
                $currentSubscription = $school->subscriptions->sortByDesc('start_date')->first();
            }

            if ($currentSubscription) {
                // Update the existing subscription
                $currentSubscription->update([
                    'plan_id' => $request->plan_id,
                    'billing_cycle' => $request->billing_cycle,
                    'status' => $request->status,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'amount_paid' => $request->amount_paid,
                    'payment_status' => $request->payment_status,
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes ?? 'Subscription updated by admin',
                ]);
            } else {
                // If there's no subscription for this school yet, create a new one
                SchoolSubscription::create([
                    'school_id' => $school->id,
                    'plan_id' => $request->plan_id,
                    'billing_cycle' => $request->billing_cycle,
                    'status' => $request->status,
                    'payment_status' => $request->payment_status,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'amount_paid' => $request->amount_paid,
                    'payment_method' => $request->payment_method ?? 'manual',
                    'notes' => $request->notes ?? 'New subscription created by admin during edit',
                ]);
            }

            return redirect()->route('saasAdmin.schools')->with('success', 'School and subscription updated successfully!');
       
    }

 public function forgetSchoolPassword(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'new_password' => 'required|confirmed|min:8',
    ]);

    User::where('id', $request->user_id)
        ->update(['password' => Hash::make($request->new_password)]);

    return response()->json(['success' => true]);
}

public function updateForgetPassword(Request $request, $schoolId)
{
    $request->validate([
        'new_password' => 'required|confirmed|min:8'
    ]);

    $school = School::findOrFail($schoolId);

    $adminUser = $school->admin;  // school admin user
    $adminUser->password = Hash::make($request->new_password);
    $adminUser->save();

    return redirect()->route('saasAdmin.schools')
                     ->with('success', 'Password updated successfully!');
}



} 