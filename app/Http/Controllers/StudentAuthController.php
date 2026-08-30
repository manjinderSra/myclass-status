<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StudentAuthController extends Controller
{
    /**
     * Show the student login form
     */
    public function showLogin()
    {
        return view('client.student.auth.login');
    }

    /**
     * Handle student login
     */
    public function login(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
            'password' => 'required|string',
        ]);




        // Find the student by student_id
      $student= Student::whereRaw('LOWER(student_id) = ?', [strtolower($request->student_id)])->first();


        if (!$student) {
            return back()->withErrors([
                'student_id' => 'Student ID not found.',
            ])->withInput($request->except('password'));
        }

        // Check password
        if (!Hash::check($request->password, $student->password)) {
            return back()->withErrors([
                'password' => 'The provided password is incorrect.',
            ])->withInput($request->except('password'));
        }

        // Store student data in session
        Session::put('student_id', $student->id);
        Session::put('student_name', $student->first_name . ' ' . $student->last_name);
        Session::put('student_email', $student->email);
        Session::put('student_profile', $student->profile_image);
        Session::put('student_class', $student->class_id);
        Session::put('student_section', $student->section_id);
        Session::put('student_school', $student->school_id);
        Session::put('logged_in_as', 'student');

        // Generate Sanctum token for API access
        $token = $student->createToken('student-token')->plainTextToken;
        Session::put('api_token', $token);

        return redirect()->route('student.dashboard');
    }

    /**
     * Handle student logout
     */
    public function logout(Request $request)
    {
        Session::forget([
            'student_id',
            'student_name',
            'student_email',
            'student_profile',
            'student_class',
            'student_section',
            'student_school',
            'logged_in_as'
        ]);

        return redirect()->route('student.logout.success');
    }
    
    /**
     * Show the logout success page
     */
    public function showLogoutSuccess()
    {
        return view('client.student.auth.logout-success');
    }
} 