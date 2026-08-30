<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\School;

class SchoolAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return redirect()->route('school.login')
                    ->with('message', 'Please log in to access the school panel.');
            }
            
            $user = Auth::user();
            $validRoles = ['school', 'staff', 'teacher', 'administration', 'finance', 'library'];
            
            // Log user information for debugging
            \Illuminate\Support\Facades\Log::info('SchoolAuth middleware check for user:', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'school_id' => $user->school_id
            ]);
            
            // Check if user has a valid role for school panel access
            if (!in_array($user->role, $validRoles)) {
                \Illuminate\Support\Facades\Log::warning('Invalid role for school panel:', ['role' => $user->role]);
                Auth::logout();
                return redirect()->route('school.login')
                    ->with('error', 'You do not have access to the school panel.');
            }
            
            // For school admin role
            if ($user->role === 'school') {
                // Check if the user is associated with a school as an admin
                $school = School::where('admin_id', Auth::id())->first();
                if (!$school) {
                    \Illuminate\Support\Facades\Log::warning('School admin not associated with any school:', ['user_id' => $user->id]);
                    Auth::logout();
                    return redirect()->route('school.login')
                        ->with('error', 'Your account is not associated with any school.');
                }
                
                // Continue with school admin
                \Illuminate\Support\Facades\Log::info('School admin authenticated successfully:', [
                    'user_id' => $user->id, 
                    'school_id' => $school->id
                ]);
            } 
            // For staff roles
            else {
                // Check if the user belongs to a school
                if (!$user->school_id) {
                    \Illuminate\Support\Facades\Log::warning('Staff user not associated with any school:', ['user_id' => $user->id]);
                    Auth::logout();
                    return redirect()->route('school.login')
                        ->with('error', 'Your account is not associated with any school.');
                }
                
                // Check if the user is active
                if (isset($user->is_active) && !$user->is_active) {
                    \Illuminate\Support\Facades\Log::warning('Inactive user attempted login:', ['user_id' => $user->id]);
                    Auth::logout();
                    return redirect()->route('school.login')
                        ->with('error', 'Your account has been deactivated. Please contact your school administrator.');
                }
                
                // Continue with staff user
                \Illuminate\Support\Facades\Log::info('Staff user authenticated successfully:', [
                    'user_id' => $user->id, 
                    'school_id' => $user->school_id
                ]);
            }
            
            return $next($request);
            
        } catch (\Exception $e) {
            // Log any exceptions
            \Illuminate\Support\Facades\Log::error('Exception in SchoolAuth middleware:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Logout user if there's an error
            if (Auth::check()) {
                Auth::logout();
            }
            
            return redirect()->route('school.login')
                ->with('error', 'An error occurred while checking your access. Please try again later.');
        }
    }
}
