<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;

use App\Models\SchoolClass;

use App\Models\Teacher;

use App\Models\Section;



use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SchoolProfileController extends Controller
{
    /**
     * Display the school profile page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Get the school associated with the admin user
        $school = School::where('admin_id', $user->id)->first();
        
        return view('client.schoolPanel.generalSettings.instituteProfile', [
            'school' => $school
        ]);
    }

    /**
     * Update the school profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'website' => 'nullable|string|max:255',
            'about' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Get the authenticated user and their school
        $user = Auth::user();
        $school = School::where('admin_id', $user->id)->first();

        // Handle logo upload if provided
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($school->logo && Storage::exists('public/school_logos/' . $school->logo)) {
                Storage::delete('public/school_logos/' . $school->logo);
            }
            
            // Store the new logo
            $logoName = time() . '.' . $request->logo->extension();
            $request->logo->storeAs('public/school_logos', $logoName);
            $school->logo = $logoName;
        }

        // Update the school information
        $school->name = $validated['name'];
        $school->tagline = $validated['tagline'] ?? null;
        $school->email = $validated['email'];
        $school->phone = $validated['phone'] ?? null;
        $school->address = $validated['address'] ?? null;
        $school->website = $validated['website'] ?? null;
        $school->about = $validated['about'] ?? null;
        
        $school->save();

        return redirect()->route('school.instituteProfile')->with('success', 'Profile updated successfully!');
    }
    
    
    public function assignTeacher(Request $request, $classId)
{
    $request->validate([
        'teacher_id' => 'required|exists:teachers,id'
    ]);

    $class = ClassModel::findOrFail($classId);
    $class->teacher_id = $request->teacher_id;
    $class->save();

    return back()->with('success', 'Class teacher assigned successfully!');
}



public function indexClassTeacher(){


   $user = Auth::user();
        
        // Get the school associated with the admin user
        $school = School::where('admin_id', $user->id)->first();

 // Fetch class-teacher assignments with relationships
 

        $classes = SchoolClass::with('teacher')
                    ->where('school_id', $school->id)
                    ->orderBy('name')
                    ->get();

        $teachers = Teacher::where('school_id', $school->id)->get();
        

        return view('client.schoolPanel.classteacher', compact('classes', 'teachers'));
}





 public function assignClassTeacher(Request $request, $classId)
    {
        
    $request->validate([
        'teacher_id' => 'required|exists:teachers,id',
    ]);

    $class = SchoolClass::findOrFail($classId);

    // Check if teacher is already assigned to another class in the same school
    $alreadyAssigned = SchoolClass::where('school_id', $class->school_id)
        ->where('teacher_id', $request->teacher_id)
        ->where('id', '!=', $class->id)
        ->exists();

    if ($alreadyAssigned) {
        return redirect()->route('admin.classes-teacher')
            ->with('error', 'This teacher is already assigned to another class. Please select a different teacher.');
    }

    $class->teacher_id = $request->teacher_id;
    $class->save();
    
    

        return redirect()->route('admin.classes-teacher')
                         ->with('success', 'Class teacher assigned successfully.');
    }

} 