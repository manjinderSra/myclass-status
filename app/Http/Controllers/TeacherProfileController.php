<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class TeacherProfileController extends Controller
{
    /**
     * Display the teacher profile
     */
    public function index()
    {
        // Get the teacher data from database
        $teacher = Teacher::findOrFail(Session::get('teacher_id'));
        return view('client.teacher.profile.index', compact('teacher'));
    }
    
    /**
     * Update the teacher profile image
     */
    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $teacher = Teacher::findOrFail(Session::get('teacher_id'));
        
        // Delete the old profile image if it exists
        if ($teacher->profile_image && Storage::exists('public/' . $teacher->profile_image)) {
            Storage::delete('public/' . $teacher->profile_image);
        }
        
        // Store the new image
        $path = $request->file('profile_image')->store('profile-images', 'public');
        
        // Update the teacher record
        $teacher->profile_image = $path;
        $teacher->save();
        
        // Update session data
        Session::put('teacher_profile', $path);
        
        return redirect()->route('teacher.profile')->with('success', 'Profile image updated successfully.');
    }
    
    /**
     * Update the teacher password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        
        $teacher = Teacher::findOrFail(Session::get('teacher_id'));
        
        // Check if the current password is correct
        if (!Hash::check($request->current_password, $teacher->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }
        
        // Update the password
        $teacher->password = Hash::make($request->new_password);
        $teacher->save();
        
        return redirect()->route('teacher.profile')->with('success', 'Password updated successfully.');
    }
    
    /**
     * Update the personal details of the teacher
     */
    public function updatePersonalDetails(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'gender' => 'required|string|in:Male,Female,Other',
            'date_of_birth' => 'nullable|date',
            'blood_group' => 'nullable|string|max:10',
            'primary_contact' => 'required|string|max:20',
            'current_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:20',
        ]);
        
        $teacher = Teacher::findOrFail(Session::get('teacher_id'));
        $teacher->update($request->all());
        
        // Update session data
        Session::put('teacher_name', $teacher->first_name . ' ' . $teacher->last_name);
        Session::put('teacher_email', $teacher->email);
        
        return redirect()->route('teacher.profile')->with('success', 'Personal details updated successfully.');
    }
} 