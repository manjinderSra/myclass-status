<?php

namespace App\Http\Controllers\Client\SchoolPanel\Peoples;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Teacher;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    /**
     * Display a listing of the teachers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $schoolId = $this->getSchoolId();
        // dd($schoolId);
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found. Please make sure you are logged in as a school admin.');
        }

        // Load teachers with their subject
        $teachers = Teacher::with('subject')->where('school_id', $schoolId)->get();
        // dd($teachers);

        return view('client.schoolPanel.peoples.teachers', compact('user', 'schoolId', 'teachers'));
    }


    /**
     * Show the form for creating a new teacher.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $schoolId = $this->getSchoolId();

        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found. Please make sure you are logged in as a school admin.');
        }

        // Fetch transport data if transport feature is enabled
        $transportData = null;
        if ($this->checkFeature('transport_management')) {
            $transportData = $this->getTransportData();
        }

        // Fetch hostel data if hostel feature is enabled
        $hostelData = null;
        if ($this->checkFeature('hostel_management')) {
            $hostelData = $this->getHostelData();
        }

        return view('client.schoolPanel.peoples.teacher.createTeacher', compact('user', 'schoolId', 'transportData', 'hostelData'));
    }

    /**
     * Store a newly created teacher in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // Get school_id using the getSchoolId method
        $schoolId = $this->getSchoolId();

        if (!$schoolId) {
            return response()->json([
                'success' => false,
                'message' => 'School not found. Please make sure you are logged in as a school admin.'
            ], 400);
        }

        // Validate the request
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:teachers,email',
            'gender' => 'required|string|in:male,female,other',
            'primaryContact' => 'required|string|max:20',
            'subject_id' => 'nullable|integer|exists:subjects,id',
            'dob' => 'nullable|date',
            'dateOfJoining' => 'nullable|date',
            'bloodGroup' => 'nullable|string|max:10',
            'fatherName' => 'nullable|string|max:255',
            'motherName' => 'nullable|string|max:255',
            'spouse_type' => 'nullable|string|in:W/O,H/O',
            'spouse_name' => 'nullable|string|max:255',
            'maritalStatus' => 'nullable|string|max:50',
            'languagesKnown' => 'nullable|string',
            'qualification' => 'nullable|string|max:255',
            'workExperience' => 'nullable|string|max:255',
            'previousSchool' => 'nullable|string|max:255',
            'previousSchoolAddress' => 'nullable|string|max:255',
            'previousSchoolPhone' => 'nullable|string|max:20',
            'panNumber' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'currentAddress' => 'nullable|string',
            'permanentAddress' => 'nullable|string',
            'epfNo' => 'nullable|string|max:50',
            'basicSalary' => 'nullable|numeric',
            'contractType' => 'nullable|string|max:50',
            'workShift' => 'nullable|string|max:50',
            'workLocation' => 'nullable|string|max:255',
            'dateOfLeaving' => 'nullable|date',
            'medicalLeaves' => 'nullable|integer',
            'casualLeaves' => 'nullable|integer',
            'maternityLeaves' => 'nullable|integer',
            'sickLeaves' => 'nullable|integer',
            'bankName' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'ifscNumber' => 'nullable|string|max:50',
            'otherInformation' => 'nullable|string',
            'transport_enabled' => 'nullable|string|in:true,false',
            'pickup_point_id' => 'nullable|exists:pickup_points,id',
            'hostel_enabled' => 'nullable|string|in:true,false',
            'hostel_id' => 'nullable|exists:hostels,id',
            'room_id' => 'nullable|exists:hostel_rooms,id',
            'profile_image' => 'nullable|image|max:4096',
            'medical_condition_document' => 'nullable|file|mimes:pdf|max:4096',
            'transfer_certificate_document' => 'nullable|file|mimes:pdf|max:4096',
        ]);

        try {
            DB::beginTransaction();

            // Generate a random password
            $password = Str::random(8);

            // Generate an employee ID (you can customize this logic)
            $employeeId = 'T' . date('Y') . rand(1000, 9999);

            // Create the teacher record
            $teacher = new Teacher();
            $teacher->school_id = $schoolId;
            $teacher->employee_id = $employeeId;
            $teacher->first_name = $request->firstName;
            $teacher->last_name = $request->lastName;
            $teacher->email = $request->email;
            $teacher->password = Hash::make($password);
            $teacher->gender = $request->gender;
            $teacher->primary_contact = $request->primaryContact;
            $teacher->subject_id = $request->subject_id;
            $teacher->date_of_birth = $request->dob;
            $teacher->date_of_joining = $request->dateOfJoining;
            $teacher->blood_group = $request->bloodGroup;
            $teacher->father_name = $request->fatherName;
            $teacher->mother_name = $request->motherName;
            $teacher->spouse_type = $request->spouse_type;
            $teacher->spouse_name = $request->spouse_name;
            $teacher->marital_status = $request->maritalStatus;
            $teacher->languages_known = $request->languagesKnown;
            $teacher->qualification = $request->qualification;
            $teacher->work_experience = $request->workExperience;
            $teacher->previous_school = $request->previousSchool;
            $teacher->previous_school_address = $request->previousSchoolAddress;
            $teacher->previous_school_phone = $request->previousSchoolPhone;
            $teacher->pan_number = $request->panNumber;
            $teacher->status = $request->status ?: 'active';
            $teacher->notes = $request->notes;

            // Address information
            $teacher->current_address = $request->currentAddress;
            $teacher->permanent_address = $request->permanentAddress;

            // Payroll information
            $teacher->epf_no = $request->epfNo;
            $teacher->basic_salary = $request->basicSalary;
            $teacher->contract_type = $request->contractType;
            $teacher->work_shift = $request->workShift;
            $teacher->work_location = $request->workLocation;
            $teacher->date_of_leaving = $request->dateOfLeaving;

            // Leave information
            $teacher->medical_leaves = $request->medicalLeaves;
            $teacher->casual_leaves = $request->casualLeaves;
            $teacher->maternity_leaves = $request->maternityLeaves;
            $teacher->sick_leaves = $request->sickLeaves;

            // Bank details
            $teacher->bank_name = $request->bankName;
            $teacher->branch = $request->branch;
            $teacher->ifsc_number = $request->ifscNumber;
            $teacher->other_information = $request->otherInformation;

            // Process transport data if transport is enabled
            $teacher->transport_enabled = $request->transport_enabled === 'true';
            if ($request->transport_enabled === 'true' && $request->pickup_point_id) {
                $teacher->pickup_point_id = $request->pickup_point_id;
            }

            // Process hostel data if hostel is enabled
            $teacher->hostel_enabled = $request->hostel_enabled === 'true';
            if ($request->hostel_enabled === 'true') {
                $teacher->hostel_id = $request->hostel_id;
                $teacher->room_id = $request->room_id;
            }

            // Handle profile image upload if provided
            if ($request->hasFile('profile_image')) {
                $profileImage = $request->file('profile_image');
                $profileImagePath = $profileImage->store('teachers/profile', 'public');
                $teacher->profile_image = $profileImagePath;
            }

            // Handle medical condition document upload if provided
            if ($request->hasFile('medical_condition_document')) {
                $medicalDoc = $request->file('medical_condition_document');
                $medicalDocPath = $medicalDoc->store('teachers/documents', 'public');
                $teacher->medical_condition_document = $medicalDocPath;
            }

            // Handle transfer certificate document upload if provided
            if ($request->hasFile('transfer_certificate_document')) {
                $transferDoc = $request->file('transfer_certificate_document');
                $transferDocPath = $transferDoc->store('teachers/documents', 'public');
                $teacher->transfer_certificate_document = $transferDocPath;
            }

            $teacher->save();

            DB::commit();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Teacher added successfully',
                'data' => [
                    'employee_id' => $employeeId,
                    'password' => $password
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to add teacher: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        // dd($user);
        $schoolId = $this->getSchoolId();

        // Fetch the teacher
        $teacher = Teacher::where('school_id', $schoolId)
            ->where('id', $id)
            ->with(['pickupPoint', 'hostel', 'room', 'subject'])
            ->first();
        // dd($teacher);
        if (!$teacher) {
            return redirect()->route('school.teachers')->with('error', 'Teacher not found');
        }



        return view('client.schoolPanel.peoples.teacher.showTeacher', compact('user', 'schoolId', 'teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $schoolId = $this->getSchoolId();

        // Fetch the teacher
        $teacher = Teacher::where('school_id', $schoolId)
            ->where('id', $id)
            ->with(['pickupPoint', 'hostel', 'room'])
            ->first();

        if (!$teacher) {
            return redirect()->route('school.teachers')->with('error', 'Teacher not found');
        }

        // Fetch transport data if transport feature is enabled
        $transportData = null;
        if ($this->checkFeature('transport_management')) {
            $transportData = $this->getTransportData();
        }

        // Fetch hostel data if hostel feature is enabled
        $hostelData = null;
        if ($this->checkFeature('hostel_management')) {
            $hostelData = $this->getHostelData();
        }


        $subjects = Subject::where('school_id', $schoolId)->get();


        return view('client.schoolPanel.peoples.teacher.editTeacher', compact('subjects', 'user', 'schoolId', 'teacher', 'transportData', 'hostelData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request (similar to store method, but allow the current email)
        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:teachers,email,' . $id,
            'gender' => 'required|string|in:male,female,other',
            'primaryContact' => 'required|string|max:20',
            // Add other validation rules similar to store method
        ]);

        try {
            DB::beginTransaction();

            // Find the teacher
            $teacher = Teacher::where('school_id', $this->getSchoolId())
                ->where('id', $id)
                ->first();

            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher not found'
                ], 404);
            }

            // Update teacher fields (similar to store method)
            $teacher->first_name = $request->firstName;
            $teacher->last_name = $request->lastName;
            $teacher->email = $request->email;
            $teacher->gender = $request->gender;
            $teacher->primary_contact = $request->primaryContact;
            $teacher->subject_id = $request->subject_id;
            // Update other fields similar to store method

            // Process transport data if transport is enabled
            $teacher->transport_enabled = $request->transport_enabled === 'true';
            if ($request->transport_enabled === 'true' && $request->pickup_point_id) {
                $teacher->pickup_point_id = $request->pickup_point_id;
            } else {
                $teacher->pickup_point_id = null;
            }

            // Process hostel data if hostel is enabled
            $teacher->hostel_enabled = $request->hostel_enabled === 'true';
            if ($request->hostel_enabled === 'true') {
                $teacher->hostel_id = $request->hostel_id;
                $teacher->room_id = $request->room_id;
            } else {
                $teacher->hostel_id = null;
                $teacher->room_id = null;
            }

            // Handle file uploads similar to store method

            $teacher->save();

            DB::commit();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Teacher updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to update teacher: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Find the teacher
            $teacher = Teacher::where('school_id', $this->getSchoolId())
                ->where('id', $id)
                ->first();

            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher not found'
                ], 404);
            }

            // Delete uploaded files if they exist
            if ($teacher->profile_image) {
                Storage::disk('public')->delete($teacher->profile_image);
            }

            if ($teacher->medical_condition_document) {
                Storage::disk('public')->delete($teacher->medical_condition_document);
            }

            if ($teacher->transfer_certificate_document) {
                Storage::disk('public')->delete($teacher->transfer_certificate_document);
            }

            // Delete the teacher
            $teacher->delete();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Teacher deleted successfully'
            ]);
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete teacher: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if a feature is enabled for the school
     *
     * @param  string  $featureName
     * @return bool
     */
    private function checkFeature($featureName)
    {
        $schoolId = $this->getSchoolId();

        // In a real app, you'd check if the school has the feature enabled
        // For now, we'll return true to simulate feature availability
        return true;
    }

    /**
     * Get transport data for the form
     *
     * @return array
     */
    private function getTransportData()
    {
        $schoolId = $this->getSchoolId();

        // Fetch pickup points
        $pickupPoints = \App\Models\PickupPoint::whereHas('routeDetail', function ($query) use ($schoolId) {
            $query->where('school_id', $schoolId);
        })->get();

        // Fetch vehicles
        $vehicles = \App\Models\Vehicle::where('school_id', $schoolId)->get();

        // Fetch routes
        $routes = \App\Models\RouteDetail::where('school_id', $schoolId)
            ->with('pickupPoints')
            ->get();

        return [
            'routes' => $routes,
            'vehicles' => $vehicles,
            'pickup_points' => $pickupPoints
        ];
    }

    /**
     * Get hostel data for the form
     *
     * @return array
     */
    private function getHostelData()
    {
        $schoolId = $this->getSchoolId();

        // Fetch hostels
        $hostels = \App\Models\Hostel::where('school_id', $schoolId)->get();

        // Fetch room types
        $roomTypes = \App\Models\HostelRoomType::where('school_id', $schoolId)->get();

        // Fetch rooms
        $rooms = \App\Models\HostelRoom::whereHas('hostel', function ($query) use ($schoolId) {
            $query->where('school_id', $schoolId);
        })->with(['hostel', 'roomType'])->get();

        return [
            'hostels' => $hostels,
            'room_types' => $roomTypes,
            'rooms' => $rooms
        ];
    }

    /**
     * Helper method to get the school ID from the authenticated user
     */
    private function getSchoolId()
    {
        $user = Auth::user();
        $schoolId = null;

        if ($user->role === 'school') {
            $school = School::where('admin_id', $user->id)->first();
            if ($school) {
                $schoolId = $school->id;
            }
        } else if ($user->school_id) {
            $schoolId = $user->school_id;
        }

        return $schoolId;
    }

    /**
     * Reset the teacher's password.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resetPassword($id)
    {
        try {
            \Log::info("Password reset attempt for teacher ID: {$id}");

            // Find the teacher
            $teacher = Teacher::where('school_id', $this->getSchoolId())
                ->where('id', $id)
                ->first();

            if (!$teacher) {
                \Log::error("Teacher not found with ID: {$id}");
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher not found'
                ], 404);
            }

            \Log::info("Teacher found: {$teacher->first_name} {$teacher->last_name}");

            // Generate a new random password
            // Try to use the Teacher model's method, but fall back to a local implementation if not available
            if (method_exists(Teacher::class, 'generatePassword')) {
                $newPassword = Teacher::generatePassword(10);
            } else {
                // Fallback password generation
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
                $newPassword = '';
                $length = 10;

                for ($i = 0; $i < $length; $i++) {
                    $newPassword .= $characters[rand(0, strlen($characters) - 1)];
                }
            }

            \Log::info("Generated password for teacher {$id}: {$newPassword}");

            // Update the teacher's password
            $teacher->password = Hash::make($newPassword);
            $teacher->save();

            \Log::info("Password updated successfully for teacher {$id}");

            // Return success response with the new password
            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully',
                'password' => $newPassword
            ]);
        } catch (\Exception $e) {
            \Log::error("Password reset failed for teacher {$id}: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());

            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the teacher's status (active/inactive).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            // Find the teacher
            $teacher = Teacher::where('school_id', $this->getSchoolId())
                ->where('id', $id)
                ->first();

            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher not found'
                ], 404);
            }

            // Update the teacher's status
            $teacher->status = $request->status;
            $teacher->save();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Teacher status updated successfully'
            ]);
        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to update teacher status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all active teachers for the current school.
     * This API endpoint is used by the timetable feature.
     */
    public function getActiveTeachers()
    {
        try {
            $schoolId = $this->getSchoolId();

            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }

            // Get all active teachers
            $teachers = Teacher::where('school_id', $schoolId)
                ->where('status', 'active')
                ->select('id', 'first_name', 'last_name', 'subject_id')
                ->get()
                ->map(function ($teacher) {
                    return [
                        'id' => $teacher->id,
                        'name' => $teacher->first_name . ' ' . $teacher->last_name,
                        'subject_id' => $teacher->subject_id// Use subject as subject_id for compatibility
                    ];
                });

            return response()->json([
                'success' => true,
                'teachers' => $teachers
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching active teachers: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch active teachers: ' . $e->getMessage()
            ], 500);
        }
    }


    public function updateWeb(Request $request, $id)
    {
        $schoolId = $this->getSchoolId();
        $teacher = Teacher::where('school_id', $schoolId)->findOrFail($id);

        $rules = [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:teachers,email,' . $teacher->id,
            'gender' => 'required|string|in:male,female,other',
            'primaryContact' => 'required|string|max:20|unique:teachers,primary_contact,' . $teacher->id, // Adjusted unique rule for primary_contact
            'subject' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'dateOfJoining' => 'nullable|date',
            'bloodGroup' => 'nullable|string|max:10',
            'fatherName' => 'nullable|string|max:255',
            'motherName' => 'nullable|string|max:255',
            'maritalStatus' => 'nullable|string|max:50',
            'languagesKnown' => 'nullable|string',
            'qualification' => 'nullable|string|max:255',
            'workExperience' => 'nullable|string|max:255',
            'previousSchool' => 'nullable|string|max:255',
            'previousSchoolAddress' => 'nullable|string|max:255',
            'previousSchoolPhone' => 'nullable|string|max:20',
            'panNumber' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50', // Assuming 'status' is a direct column
            'notes' => 'nullable|string',
            'currentAddress' => 'nullable|string',
            'permanentAddress' => 'nullable|string',
            'epfNo' => 'nullable|string|max:50',
            'basicSalary' => 'nullable|numeric',
            'contractType' => 'nullable|string|max:50',
            'workShift' => 'nullable|string|max:50',
            'workLocation' => 'nullable|string|max:255',
            'dateOfLeaving' => 'nullable|date',
            'medicalLeaves' => 'nullable|integer',
            'casualLeaves' => 'nullable|integer',
            'maternityLeaves' => 'nullable|integer',
            'sickLeaves' => 'nullable|integer',
            'bankName' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'ifscNumber' => 'nullable|string|max:50',
            'otherInformation' => 'nullable|string',
            'transport_enabled' => 'nullable|in:true,false',
            'pickup_point_id' => 'nullable|exists:pickup_points,id',
            'hostel_enabled' => 'nullable|in:true,false',
            'hostel_id' => 'nullable|exists:hostels,id',
            'room_id' => 'nullable|exists:hostel_rooms,id',
            'profile_image' => 'nullable|image|max:4096', // Max 4MB
            'medical_condition_document' => 'nullable|file|mimes:pdf|max:4096', // Only PDF, max 4MB
            'transfer_certificate_document' => 'nullable|file|mimes:pdf|max:4096', // Only PDF, max 4MB
        ];

        $request->validate($rules);

        DB::beginTransaction();
        try {
            // Mapping request data to model attributes (snake_case)
            $teacher->first_name = $request->firstName;
            $teacher->last_name = $request->lastName;
            $teacher->email = $request->email;
            $teacher->gender = $request->gender;
            $teacher->primary_contact = $request->primaryContact;
            $teacher->subject_id = $request->subject_id;
            $teacher->date_of_birth = $request->dob;
            $teacher->date_of_joining = $request->dateOfJoining;
            $teacher->blood_group = $request->bloodGroup;
            $teacher->father_name = $request->fatherName;
            $teacher->mother_name = $request->motherName;
            $teacher->marital_status = $request->maritalStatus;
            $teacher->languages_known = $request->languagesKnown;
            $teacher->qualification = $request->qualification;
            $teacher->work_experience = $request->workExperience;
            $teacher->previous_school = $request->previousSchool;
            $teacher->previous_school_address = $request->previousSchoolAddress;
            $teacher->previous_school_phone = $request->previousSchoolPhone;
            $teacher->pan_number = $request->panNumber;
            $teacher->status = $request->status ?: 'active'; // Default to 'active' if not provided
            $teacher->notes = $request->notes;
            $teacher->current_address = $request->currentAddress;
            $teacher->permanent_address = $request->permanentAddress;
            $teacher->epf_no = $request->epfNo;
            $teacher->basic_salary = $request->basicSalary;
            $teacher->contract_type = $request->contractType;
            $teacher->work_shift = $request->workShift;
            $teacher->work_location = $request->workLocation;
            $teacher->date_of_leaving = $request->dateOfLeaving;
            $teacher->medical_leaves = $request->medicalLeaves;
            $teacher->casual_leaves = $request->casualLeaves;
            $teacher->maternity_leaves = $request->maternityLeaves;
            $teacher->sick_leaves = $request->sickLeaves;
            $teacher->bank_name = $request->bankName;
            $teacher->branch = $request->branch;
            $teacher->ifsc_number = $request->ifscNumber;
            $teacher->other_information = $request->otherInformation;

            $teacher->transport_enabled = $request->transport_enabled === 'true';
            $teacher->pickup_point_id = ($request->transport_enabled === 'true' && $request->pickup_point_id) ? $request->pickup_point_id : null;

            $teacher->hostel_enabled = $request->hostel_enabled === 'true';
            $teacher->hostel_id = ($request->hostel_enabled === 'true' && $request->hostel_id) ? $request->hostel_id : null;
            $teacher->room_id = ($request->hostel_enabled === 'true' && $request->room_id) ? $request->room_id : null;

            // Handle file uploads - delete old, store new
            // For profile_image
            if ($request->hasFile('profile_image')) {
                if ($teacher->profile_image && Storage::disk('public')->exists($teacher->profile_image)) {
                    Storage::disk('public')->delete($teacher->profile_image);
                }
                $teacher->profile_image = $request->file('profile_image')->store('teachers/profile', 'public');
            }

            // For medical_condition_document
            if ($request->hasFile('medical_condition_document')) {
                if ($teacher->medical_condition_document && Storage::disk('public')->exists($teacher->medical_condition_document)) {
                    Storage::disk('public')->delete($teacher->medical_condition_document);
                }
                $teacher->medical_condition_document = $request->file('medical_condition_document')->store('teachers/documents', 'public');
            }

            // For transfer_certificate_document
            if ($request->hasFile('transfer_certificate_document')) {
                if ($teacher->transfer_certificate_document && Storage::disk('public')->exists($teacher->transfer_certificate_document)) {
                    Storage::disk('public')->delete($teacher->transfer_certificate_document);
                }
                $teacher->transfer_certificate_document = $request->file('transfer_certificate_document')->store('teachers/documents', 'public');
            }

            $teacher->save();

            DB::commit();
            return redirect()->route('school.teachers', $teacher->id)->with('success', 'Teacher updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating teacher: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the teacher. Please try again. ' . $e->getMessage());
        }
    }
}
