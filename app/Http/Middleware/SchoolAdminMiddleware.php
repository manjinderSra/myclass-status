<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\School;

class SchoolAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('school.login')
                ->with('error', 'You must be logged in to access this page.');
        }
        
        // Get the authenticated user
        $user = Auth::user();
        
        // Check if the user is a school admin by checking if they have an administered school
        $school = School::where('admin_id', $user->id)->first();
        
        if (!$school) {
            return redirect()->route('school.dashboard')
                ->with('error', 'You do not have permission to access this resource.');
        }
        
        // Set the school in the request for use in controllers
        $request->merge(['school' => $school]);
        
        return $next($request);
    }
}
