<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class TeacherAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if teacher is logged in
        if (!Session::has('teacher_id') || Session::get('logged_in_as') !== 'teacher') {
            return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
        }
        
        return $next($request);
    }
} 