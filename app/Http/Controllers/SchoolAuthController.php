<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class SchoolAuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        return view('client.schoolPanel.auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
    
    
   
    
    
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Log login attempt for debugging
            \Illuminate\Support\Facades\Log::info('Login attempt:', [
                'email' => $request->email,
                'remember' => $request->filled('remember')
            ]);

            // Attempt to log in
            if (Auth::attempt($credentials, $request->filled('remember'))) {
                $user = Auth::user();
                
                // Log successful authentication
                \Illuminate\Support\Facades\Log::info('User authenticated:', [
                    'id' => $user->id,
                    'email' => $user->email,
                    'role' => $user->role
                ]);
                
                // Define valid roles that can access the school panel
                $validRoles = ['school', 'staff', 'teacher', 'administration', 'finance', 'library'];
                
                // Check if user has a valid role for school panel access
                if (!in_array($user->role, $validRoles)) {
                    \Illuminate\Support\Facades\Log::warning('Invalid role for school access:', [
                        'user_id' => $user->id,
                        'role' => $user->role
                    ]);
                    
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'You do not have access to the school panel.',
                    ]);
                }
                
                try {
                    // Ensure school admin/owner has the school role in the role system
                    if ($user->role === 'school') {
                        $school = \App\Models\School::where('admin_id', $user->id)->first();
                        if ($school) {
                            \Illuminate\Support\Facades\Log::info('School found for admin:', [
                                'user_id' => $user->id,
                                'school_id' => $school->id
                            ]);
                            
                            // Get or create the 'school' role
                            $schoolRole = \App\Models\Role::where('name', 'school')
                                ->where('school_id', $school->id)
                                ->first();
                            
                            if (!$schoolRole) {
                                \Illuminate\Support\Facades\Log::info('Creating school role for school:', [
                                    'school_id' => $school->id
                                ]);
                                
                                $schoolRole = \App\Models\Role::create([
                                    'name' => 'school',
                                    'display_name' => 'School Admin',
                                    'description' => 'Full access to all school features',
                                    'school_id' => $school->id,
                                    'is_system_role' => true,
                                ]);
                            }
                            
                            // Assign role if not already assigned
                            if (!$user->roles || !$user->roles->contains($schoolRole)) {
                                \Illuminate\Support\Facades\Log::info('Assigning school role to admin:', [
                                    'user_id' => $user->id,
                                    'role_id' => $schoolRole->id
                                ]);
                                
                                // Make sure the roles relationship is initialized
                                if (!$user->roles) {
                                    $user->roles = collect();
                                }
                                
                                // Use attach instead of assignRoles if available
                                if (method_exists($user, 'roles') && method_exists($user->roles(), 'attach')) {
                                    $user->roles()->attach($schoolRole->id);
                                } else if (method_exists($user, 'assignRoles')) {
                                    $user->assignRoles($schoolRole);
                                }
                            }
                        } else {
                            \Illuminate\Support\Facades\Log::warning('No school found for admin:', [
                                'user_id' => $user->id
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    // Log error but don't prevent login
                    \Illuminate\Support\Facades\Log::error('Error assigning role to school admin: ' . $e->getMessage(), [
                        'exception' => $e,
                        'user_id' => $user->id
                    ]);
                }
                
                $request->session()->regenerate();
                
                // Log successful login and redirect
                \Illuminate\Support\Facades\Log::info('User logged in successfully:', [
                    'id' => $user->id,
                    'email' => $user->email
                ]);
                
                // If there was a plan selection in session, redirect to plan purchase
                if ($request->session()->has('selected_plan_id')) {
                    $planId = $request->session()->get('selected_plan_id');
                    $request->session()->forget('selected_plan_id');
                    return redirect()->route('landing.plan.purchase', $planId);
                }
                
                return redirect()->route('school.dashboard');
            }

            // Log failed login attempt
            \Illuminate\Support\Facades\Log::warning('Failed login attempt:', [
                'email' => $request->email
            ]);

            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput($request->except('password'));
            
        } catch (\Exception $e) {
            // Log unexpected errors
            \Illuminate\Support\Facades\Log::error('Exception during login process: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'email' => 'An error occurred during login. Please try again later.',
            ])->withInput($request->except('password'));
        }
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
       
        
        return redirect()->route('school.login');
    }
    
    /**
     * Redirect to pricing page for signup
     */
    public function redirectToSignup()
    {
        return redirect()->route('landing.pricing')->with('message', 'Please select a plan to sign up for a school account.');
    }
}
