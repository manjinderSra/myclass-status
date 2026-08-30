<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class TeacherAuthController extends Controller
{
    /**
     * Show the teacher login form
     */
    public function showLogin()
    {
        return view('client.teacher.auth.login');
    }

    /**
     * Handle teacher login
     */
    public function login(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find the teacher by employee_id
        $teacher = Teacher::where('employee_id', $request->employee_id)->first();

        if (!$teacher) {
            return back()->withErrors([
                'employee_id' => 'Employee ID not found.',
            ])->withInput($request->except('password'));
        }

        // Check password
        if (!Hash::check($request->password, $teacher->password)) {
            return back()->withErrors([
                'password' => 'The provided password is incorrect.',
            ])->withInput($request->except('password'));
        }

        // Store teacher data in session
        Session::put('teacher_id', $teacher->id);
        Session::put('teacher_name', $teacher->first_name . ' ' . $teacher->last_name);
        Session::put('teacher_email', $teacher->email);
        Session::put('teacher_profile', $teacher->profile_image);
        Session::put('teacher_school', $teacher->school_id);
        Session::put('logged_in_as', 'teacher');

        return redirect()->route('teacher.dashboard');
    }

    /**
     * Handle teacher logout
     */
    public function logout(Request $request)
    {
        Session::forget([
            'teacher_id',
            'teacher_name',
            'teacher_email',
            'teacher_profile',
            'teacher_school',
            'logged_in_as'
        ]);

        return redirect()->route('teacher.logout.success');
    }
    
    /**
     * Show the logout success page
     */
    public function showLogoutSuccess()
    {
        return view('client.teacher.auth.logout-success');
    }
} 