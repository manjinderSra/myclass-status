<?php

namespace App\Http\Controllers\Client\SchoolPanel\Peoples;

use App\Http\Controllers\Controller;
use App\Models\AssignFee;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\IssuedBook;
use App\Models\Book;
use App\Models\CollectFee;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Log;
use PDF;

class StudentController extends Controller
{
    /**
     * Get the current school ID from the authenticated user
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


    public function destroy($id)
    {
        $currentSchoolId = $this->getSchoolId();

        if (!$currentSchoolId) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to determine current school.'
            ], 401);
        }

        $student = Student::where('id', $id)
            ->where('school_id', $currentSchoolId)
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        // if you have images / attachments, delete them here...
        if ($student->profile_image) {
            Storage::disk('public')->delete($student->profile_image);
        }

        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully'
        ]);
    }


    private function generateUniqueAdmissionNumber()
    {
        do {
            // Example: ADMYYYY<6_random_chars>
            $admissionNumber = 'ADM' . now()->year . Str::upper(Str::random(6));
        } while (Student::where('admission_number', $admissionNumber)->exists());
        return $admissionNumber;
    }

    private function generateUniqueStudentId()
    {
        do {
            // Example: STU<8_random_chars>
            $studentId = 'STU' . Str::upper(Str::random(8));
        } while (Student::where('student_id', $studentId)->exists());
        return $studentId;
    }

    // public function index()
    // {
    //     $currentSchoolId = $this->getSchoolId();
    //     // dd($currentSchoolId);
    //     if (!$currentSchoolId) {
    //         return redirect()->route('login')->with('error', 'Unable to determine current school. Please login again.');
    //     }

    //     $students = Student::with(['class', 'section'])
    //         ->where('school_id', $currentSchoolId)
    //         ->get();

    //     return view('client.schoolPanel.peoples.students', compact('students'));
    // }

    public function index(Request $request)
    {
        $currentSchoolId = $this->getSchoolId();

        if (!$currentSchoolId) {
            return redirect()->route('login')->with('error', 'Unable to determine current school. Please login again.');
        }

        // Get all classes for dropdown
        $classes = SchoolClass::where('school_id', $currentSchoolId)->get();

        // Filter students with pagination
        $studentsQuery = Student::with(['class', 'section'])
            ->where('school_id', $currentSchoolId);

        if ($request->filled('class_id')) {
            $studentsQuery->where('class_id', $request->class_id);
        }

        // Use paginate instead of get
        $total = $studentsQuery->count();
        $students = $studentsQuery->paginate($total);


        return view('client.schoolPanel.peoples.students', compact('students', 'classes'));
    }


    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $currentSchoolId = $this->getSchoolId();
        if (!$currentSchoolId) {
            return redirect()->route('login')->with('error', 'Unable to determine current school. Please login again.');
        }

        // Get all classes and sections for the dropdowns
        $classes = SchoolClass::where('school_id', $currentSchoolId)->get();
        $sections = \App\Models\Section::where('school_id', $currentSchoolId)->get();

        return view('client.schoolPanel.peoples.student.createStudent', compact('classes', 'sections'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $currentSchoolId = $this->getSchoolId();
        if (!$currentSchoolId) {
            return response()->json(['success' => false, 'message' => 'Unable to determine current school. Please login again.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'academicYear' => 'nullable|string|max:255',
            'admissionDate' => 'nullable|date_format:Y-m-d',
            'admission_number' => 'required|numeric|unique:students,admission_number', // ✅ corrected
            'status' => 'required|string|in:active,inactive',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'class' => [
                'required',
                'string',
                Rule::exists('school_classes', 'name')->where(function ($query) use ($currentSchoolId) {
                    $query->where('school_id', $currentSchoolId);
                })
            ],
            'section' => [
                'required',
                'integer',
                Rule::exists('sections', 'id')->where(function ($query) use ($currentSchoolId) {
                    $query->where('school_id', $currentSchoolId);
                })
            ],
            'gender' => 'required|string|in:male,female,other',
            'dob' => 'nullable|date_format:Y-m-d|before_or_equal:today',
            'bloodGroup' => 'nullable|string|max:10',
            'house' => 'nullable|string|max:50',
            'religion' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:50',
            'primaryContact' => 'required|string|max:20',
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('students', 'email')],
            'roll_number' => 'required|string',
            'aadhaarNumber' => 'nullable|numeric|digits:12',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:4096',
            'motherTongue' => 'nullable|string|max:100',
            'languagesKnown' => 'nullable|string',

            // Parent Information
            'fatherName' => 'nullable|string|max:255',
            'fatherEmail' => 'nullable|email|max:255',
            'fatherPhoneNumber' => 'nullable|string|max:20',
            'fatherOccupation' => 'nullable|string|max:255',
            'father_profile_image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:4096',

            'motherName' => 'nullable|string|max:255',
            'motherEmail' => 'nullable|email|max:255',
            'motherPhoneNumber' => 'nullable|string|max:20',
            'motherOccupation' => 'nullable|string|max:255',
            'mother_profile_image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:4096',

            // Guardian Information
            'guardianType' => 'nullable|string|in:guardians,others',

            // Guardian
            'guardianName' => 'nullable|string|max:255',
            'guardianRelation' => 'nullable|string|max:255',
            'guardianEmail' => 'nullable|email|max:255',
            'guardianPhoneNumber' => 'nullable|string|max:20',
            'guardianOccupation' => 'nullable|string|max:255',
            'guardianAddress' => 'nullable|string',
            'guardian_profile_image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:4096',

            // Others Guardian
            'othersName' => 'nullable|string|max:255',
            'othersRelation' => 'nullable|string|max:255',
            'othersEmail' => 'nullable|email|max:255',
            'othersPhoneNumber' => 'nullable|string|max:20',
            'othersOccupation' => 'nullable|string|max:255',
            'othersAddress' => 'nullable|string',
            'others_profile_image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:4096',

            // Siblings
            'siblingInSameSchool' => 'nullable|string|in:yes,no',
            'siblings' => 'nullable|array',
            'siblings.*.name' => 'nullable|string',
            'siblings.*.roll_number' => 'nullable|string',
            'siblings.*.admissionNo' => 'nullable|string',
            'siblings.*.class' => 'nullable|string',

            // Address
            'currentAddress' => 'nullable|string',
            'permanentAddress' => 'nullable|string',

            // Transport Information
            'transport_enabled' => 'nullable|string|in:true,false',
            'pickup_point_id' => 'nullable|integer|exists:pickup_points,id',

            // Hostel Information
            'hostel_enabled' => 'nullable|string|in:true,false',
            'hostel_id' => 'nullable|integer|exists:hostels,id',
            'room_id' => 'nullable|integer|exists:hostel_rooms,id',

            // Documents
            'medical_condition_document' => 'nullable|file|mimes:pdf|max:4096',
            'transfer_certificate_document' => 'nullable|file|mimes:pdf|max:4096',

            // Medical History
            'medicalConditionStatus' => 'nullable|string|in:good,bad,others',
            'allergies' => 'nullable|string',
            'medications' => 'nullable|string',

            // Previous School Details
            'previousSchoolName' => 'nullable|string|max:255',
            'previousSchoolAddress' => 'nullable|string',

            // Other Details
            'bankName' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'ifscNumber' => 'nullable|string|max:255',
            'otherInformation' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        $studentInput = [];

        // Map general fields (camelCase from request to snake_case for DB)
        $directMapping = [
            'academicYear' => 'academic_year',
            'admission_number'  => 'admission_number',
            'admissionDate' => 'admission_date',
            'status' => 'status',
            'firstName' => 'first_name',
            'lastName' => 'last_name',
            'gender' => 'gender',
            'dob' => 'dob',
            'bloodGroup' => 'blood_group',
            'house' => 'house',
            'religion' => 'religion',
            'category' => 'category',
            'primaryContact' => 'primary_contact',
            'email' => 'email',
            'roll_number' => 'roll_number',
            'aadhaarNumber' => 'aadhaar_number',
            'motherTongue' => 'mother_tongue',

            // Father information
            'fatherName' => 'father_name',
            'fatherEmail' => 'father_email',
            'fatherPhoneNumber' => 'father_phone_number',
            'fatherOccupation' => 'father_occupation',

            // Mother information
            'motherName' => 'mother_name',
            'motherEmail' => 'mother_email',
            'motherPhoneNumber' => 'mother_phone_number',
            'motherOccupation' => 'mother_occupation',

            // Guardian type
            'guardianType' => 'guardian_type',

            // Address information
            'currentAddress' => 'current_address',
            'permanentAddress' => 'permanent_address',

            // Previous school details
            'previousSchoolName' => 'previous_school_name',
            'previousSchoolAddress' => 'previous_school_address',

            // Other details
            'bankName' => 'bank_name',
            'branch' => 'branch',
            'ifscNumber' => 'ifsc_number',
            'otherInformation' => 'other_information',

            // Medical condition
            'medicalConditionStatus' => 'medical_condition_status',
        ];

        foreach ($directMapping as $requestKey => $dbKey) {
            if (isset($validatedData[$requestKey])) {
                $studentInput[$dbKey] = $validatedData[$requestKey];
            }
        }

        // Handle languages known
        if (isset($request->languagesKnown)) {
            $studentInput['languages_known'] = array_filter(explode(',', $request->languagesKnown));
        }

        // Handle allergies
        if (isset($request->allergies)) {
            $studentInput['allergies'] = array_filter(explode(',', $request->allergies));
        }

        // Handle medications
        if (isset($request->medications)) {
            $studentInput['medications'] = array_filter(explode(',', $request->medications));
        }

        // Handle guardian fields based on guardian type
        if (isset($request->guardianType) && $request->guardianType === 'guardians') {
            $studentInput['guardian_name'] = $request->guardianName;
            $studentInput['guardian_relation'] = $request->guardianRelation;
            $studentInput['guardian_email'] = $request->guardianEmail;
            $studentInput['guardian_phone_number'] = $request->guardianPhoneNumber;
            $studentInput['guardian_occupation'] = $request->guardianOccupation;
            $studentInput['guardian_address'] = $request->guardianAddress;
        } elseif (isset($request->guardianType) && $request->guardianType === 'others') {
            $studentInput['guardian_name'] = $request->othersName;
            $studentInput['guardian_relation'] = $request->othersRelation;
            $studentInput['guardian_email'] = $request->othersEmail;
            $studentInput['guardian_phone_number'] = $request->othersPhoneNumber;
            $studentInput['guardian_occupation'] = $request->othersOccupation;
            $studentInput['guardian_address'] = $request->othersAddress;
        }

        // Handle transport
        $studentInput['transport_enabled'] = $request->transport_enabled === 'true';
        if ($studentInput['transport_enabled'] && $request->pickup_point_id) {
            $studentInput['pickup_point_id'] = $request->pickup_point_id;
        }

        // Handle hostel
        $studentInput['hostel_enabled'] = $request->hostel_enabled === 'true';
        if ($studentInput['hostel_enabled']) {
            $studentInput['hostel_id'] = $request->hostel_id;
            $studentInput['room_id'] = $request->room_id;
        }

        // Handle class and section
        $schoolClass = SchoolClass::where('name', $validatedData['class'])
            ->where('school_id', $currentSchoolId)
            ->first();
        // Validation should ensure $schoolClass is found, but a fallback is good practice if Rule::exists was not strict enough or removed
        if (!$schoolClass) {
            return response()->json(['success' => false, 'message' => 'Selected class not found for this school.'], 422);
        }
        $studentInput['class_id'] = $schoolClass->id;
        $studentInput['section_id'] = $validatedData['section']; // section is already an ID from form

        // Set the school_id
        $studentInput['school_id'] = $currentSchoolId;

        // Store profile images
        if ($request->hasFile('profile_image')) {
            $studentInput['profile_image'] = $request->file('profile_image')->store('student_files/' . Str::slug(($validatedData['firstName'] ?? 'student') . ' ' . ($validatedData['lastName'] ?? time()), '_'), 'public');
        }

        if ($request->hasFile('father_profile_image')) {
            $studentInput['father_profile_image'] = $request->file('father_profile_image')->store('student_files/parents', 'public');
        }

        if ($request->hasFile('mother_profile_image')) {
            $studentInput['mother_profile_image'] = $request->file('mother_profile_image')->store('student_files/parents', 'public');
        }

        if ($request->guardianType === 'guardians' && $request->hasFile('guardian_profile_image')) {
            $studentInput['guardian_profile_image'] = $request->file('guardian_profile_image')->store('student_files/guardians', 'public');
        } elseif ($request->guardianType === 'others' && $request->hasFile('others_profile_image')) {
            $studentInput['guardian_profile_image'] = $request->file('others_profile_image')->store('student_files/guardians', 'public');
        }

        // Store documents
        if ($request->hasFile('medical_condition_document')) {
            $studentInput['medical_condition_document'] = $request->file('medical_condition_document')->store('student_files/documents', 'public');
        }

        if ($request->hasFile('transfer_certificate_document')) {
            $studentInput['transfer_certificate_document'] = $request->file('transfer_certificate_document')->store('student_files/documents', 'public');
        }

        // Handle siblings
        if (isset($request->siblingInSameSchool) && $request->siblingInSameSchool === 'yes' && isset($request->siblings)) {
            $studentInput['siblings'] = json_encode($request->siblings);
        }

        // Auto-generate IDs and Password
        // $studentInput['admission_number'] = $this->generateUniqueAdmissionNumber();
        $studentInput['student_id'] = $this->generateUniqueStudentId();
        $studentInput['academic_number'] = $studentInput['admission_number']; // Using admission number as academic number
        $rawPassword = Str::random(10);
        $studentInput['password'] = Hash::make($rawPassword);

        try {
            $student = Student::create($studentInput);

            return response()->json([
                'success' => true,
                'message' => 'Student created successfully!',
                'data' => [
                    // 'admission_number' => $student->admission_number,
                    'student_id' => $student->student_id,
                    'password' => $rawPassword,
                ]
            ], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            \logger()->error('Student Creation DB Error: ' . $e->getMessage() . "\nInput: " . json_encode($studentInput) . "\nSQL: " . $e->getSql() . "\nBindings: " . json_encode($e->getBindings()));
            return response()->json(['success' => false, 'message' => 'Database error during student creation. Please check logs.'], 500);
        } catch (\Exception $e) {
            \logger()->error('Student Creation Failed: ' . $e->getMessage() . "\nInput: " . json_encode($studentInput) . "\nTrace: " . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please check logs.'], 500);
        }
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit($admission_number)
    {
        $currentSchoolId = $this->getSchoolId();
        if (!$currentSchoolId) {
            return redirect()->route('login')->with('error', 'Unable to determine current school. Please login again.');
        }

        // Find the student by admission number instead of ID
        $student = Student::where('admission_number', $admission_number)
            ->where('school_id', $currentSchoolId)
            ->firstOrFail();
        // dd($student);   
        // Get all classes and sections for the dropdowns
        $classes = SchoolClass::where('school_id', $currentSchoolId)->get();
        $sections = \App\Models\Section::where('school_id', $currentSchoolId)->get();

        return view('client.schoolPanel.peoples.student.editStudent', compact('student', 'classes', 'sections'));
    }

    /**
     * Update the specified student in storage.f
     */
    public function update(Request $request, $admission_number)
    {
        $currentSchoolId = $this->getSchoolId();
        if (!$currentSchoolId) {
            return response()->json(['success' => false, 'message' => 'Unable to determine current school. Please login again.'], 401);
        }

        $student = Student::where('admission_number', $admission_number)
            ->where('school_id', $currentSchoolId)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'admission_number' => [
                'required',
                'string',
                Rule::unique('students', 'admission_number')->ignore($student->id)
            ],
            'academic_number' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:255',
            'admission_date' => 'nullable|date_format:Y-m-d',
            'status' => 'required|string|in:active,inactive',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'class_id' => [
                'required',
                'integer',
                Rule::exists('school_classes', 'id')->where(function ($query) use ($currentSchoolId) {
                    $query->where('school_id', $currentSchoolId);
                })
            ],
            'section_id' => [
                'required',
                'integer',
                Rule::exists('sections', 'id')->where(function ($query) use ($currentSchoolId) {
                    $query->where('school_id', $currentSchoolId);
                })
            ],
            'gender' => 'required|string|in:male,female,other',
            'dob' => 'nullable|date_format:Y-m-d|before_or_equal:today',
            'blood_group' => 'nullable|string|max:10',
            'house' => 'nullable|string|max:50',
            'roll_number' => 'required|integer',
            'religion' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:50',
            'aadhaar_number' => 'nullable|numeric|digits:12',
            'primary_contact' => 'required|string|max:20',
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('students', 'email')->ignore($student->id)
            ],
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:4096',
            'mother_tongue' => 'nullable|string|max:100',
            'languages_known' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed.', 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        // Map validated data to DB columns
        $studentInput = [
            'admission_number' => $validatedData['admission_number'],
            'academic_number' => $validatedData['academic_number'] ?? $student->academic_number,
            'academic_year' => $validatedData['academic_year'] ?? null,
            'admission_date' => $validatedData['admission_date'] ?? null,
            'status' => $validatedData['status'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'gender' => $validatedData['gender'],
            'dob' => $validatedData['dob'] ?? null,
            'blood_group' => $validatedData['blood_group'] ?? null,
            'house' => $validatedData['house'] ?? null,
            'roll_number' => $validatedData['roll_number'],
            'religion' => $validatedData['religion'] ?? null,
            'category' => $validatedData['category'] ?? null,
            'aadhaar_number' => $validatedData['aadhaar_number'] ?? null,
            'primary_contact' => $validatedData['primary_contact'],
            'email' => $validatedData['email'],
            'mother_tongue' => $validatedData['mother_tongue'] ?? null,
            'class_id' => $validatedData['class_id'],
            'section_id' => $validatedData['section_id'],
        ];

        if (isset($request->languages_known)) {
            $studentInput['languages_known'] = array_filter(explode(',', $request->languages_known));
        }

        if ($request->hasFile('profile_image')) {
            if ($student->profile_image) {
                Storage::disk('public')->delete($student->profile_image);
            }

            $studentInput['profile_image'] = $request->file('profile_image')->store(
                'student_files/' . Str::slug($validatedData['first_name'] . ' ' . $validatedData['last_name'], '_'),
                'public'
            );
        }

        try {
            $student->update($studentInput);

            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully!',
                'data' => [
                    'id' => $student->id,
                    'admission_number' => $student->admission_number,
                    'student_id' => $student->student_id,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Student Update Failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please check logs.'], 500);
        }
    }


    /**
     * Update a student's password
     */


    public function resetPassword($admission_number)
    {
        try {
            Log::info("Password reset attempt for admission number: {$admission_number}");


            // Find the student by admission_number and school_id
            $student = Student::where('school_id', $this->getSchoolId())
                ->where('student_id', $admission_number)
                ->first();

            if (!$student) {
                Log::error("Student not found with admission number: {$admission_number}");
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            // Use the model method to generate password
            $newPassword = Student::generatePassword(10);

            // Save the hashed password
            $student->password = Hash::make($newPassword);
            $student->save();

            Log::info("Password reset successful for student: {$admission_number}");

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully',
                'password' => $newPassword // plain password to show to admin or user
            ]);
        } catch (\Exception $e) {
            Log::error("Password reset failed for student: {$admission_number}. Error: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password: ' . $e->getMessage()
            ], 500);
        }
    }




    public function updatePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'student_id' => 'required|string',
                'new_password' => 'required|string|min:8'
            ]);

            // Find the student by admission number
            $student = Student::where('admission_number', $validated['student_id'])->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            // Update the password
            $student->password = Hash::make($validated['new_password']);
            $student->save();

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully'
            ]);
        } catch (\Exception $e) {
            \logger()->error('Password Update Failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified student.
     */
    public function show($identifier)
    {
        $currentSchoolId = $this->getSchoolId();
        if (!$currentSchoolId) {
            return redirect()->route('login')
                ->with('error', 'Unable to determine current school. Please login again.');
        }

        // 🔍 Try to find student by ID or admission_number (both supported)
        $student = Student::with(['class', 'section', 'hostel', 'room.roomType'])
            ->where(function ($query) use ($identifier) {
                $query->where('id', $identifier)
                    ->orWhere('admission_number', $identifier);
            })
            ->where('school_id', $currentSchoolId)
            ->firstOrFail();
        // dd($student);
        // ✅ Now student is found whether you passed ID or admission number
        $issuedBooks = IssuedBook::where('student_id', $student->student_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $books = Book::where('school_id', $currentSchoolId)
            ->get()
            ->keyBy('book_id');

        foreach ($issuedBooks as $book) {
            $book->image_path = $books[$book->book_id]->image_path ?? null;
        }

        // ===================== FETCH STUDENT FEES WITH PAYMENTS =====================
        $assignedFees = \App\Models\AssignFee::where('student_id', $student->id)
            ->with(['feeMaster', 'feeGroup', 'feeType'])
            ->get();

        $studentFees = $assignedFees->map(function ($assignedFee) {
            // Get all payments for this assigned fee
            $payments = \App\Models\CollectFee::where('assign_fee_id', $assignedFee->id)->get();

            // Calculate total paid amount from collect_fees table
            $totalPaid = $payments->sum('paid_amount');

            // Get the fee amount from fee_master
            $feeAmount = $assignedFee->feeMaster->amount ?? 0;

            // Calculate remaining balance
            $balance = $feeAmount - $totalPaid;

            // Determine status based on payments
            $status = 'unpaid';
            if ($totalPaid > 0 && $balance > 0) {
                $status = 'pending'; // Partially paid
            } elseif ($balance <= 0) {
                $status = 'paid'; // Fully paid
            }

            return [
                'id' => $assignedFee->id,
                'fee_type' => $assignedFee->feeType->name ?? 'N/A',
                'fee_group' => $assignedFee->feeGroup->name ?? 'N/A',
                'amount' => $feeAmount,
                'due_date' => $assignedFee->feeMaster->due_date ?? null,
                'paid_amount' => $totalPaid,
                'balance' => max(0, $balance), 
                'status' => $status,
                'assign_fee_status' => $assignedFee->status, 
                'payment_count' => $payments->count(),
                'last_payment_date' => $payments->max('collection_date'),
                'payments' => $payments 
            ];
        });


        $totalFees = $studentFees->sum('amount');
        $totalPaid = $studentFees->sum('paid_amount');
        $totalPending = $studentFees->sum('balance');

        $timetableData = [];
        if ($student->class && $student->section) {
            try {
                $timetable = \App\Models\TimeTable::where('school_id', $currentSchoolId)
                    ->where('class_name', $student->class->name)
                    ->where('section_id', $student->section_id)
                    ->first();

                if ($timetable) {
                    $periods = \App\Models\TimeTablePeriod::where('timetable_id', $timetable->id)
                        ->with(['subjectRelation', 'teacherRelation'])
                        ->get();

                    $timetableData = $periods->map(function ($period) {
                        $data = [
                            'id' => $period->id,
                            'day' => $period->day,
                            'start_time' => $period->time_from,
                            'end_time' => $period->time_to,
                            'period_type' => $period->period_type,
                        ];

                        if ($period->period_type === 'regular') {
                            $data['subject'] = $period->subject;
                            $data['teacher'] = $period->teacher;
                            $data['subject_name'] = $period->subject_name;
                            $data['teacher_name'] = $period->teacher_name;
                        } else {
                            $data['name'] = $period->name;
                        }

                        return $data;
                    })->toArray();
                }
            } catch (\Exception $e) {
                Log::error('Error fetching timetable: ' . $e->getMessage());
            }
        }

        $hostelDetails = null;

        if ($student->hostel_enabled && $student->hostel && $student->room) {
            $hostelDetails = [
                'hostel_name' => $student->hostel->name ?? '-',
                'room_number' => $student->room->room_number ?? '-',
                'room_type' => $student->room->roomType->name ?? '-',
                'beds' => $student->room->beds ?? '-',
            ];
        }


        return view('client.schoolPanel.peoples.student.showStudent', compact(
            'student',
            'issuedBooks',
            'timetableData',
            'studentFees',
            'totalFees',
            'totalPaid',
            'totalPending',
            'hostelDetails'
        ));
    }


    /**
     * Download a student document.
     */
    public function generateFeesPdf($identifier)
    {
        $currentSchoolId = $this->getSchoolId();
        if (!$currentSchoolId) {
            return redirect()->route('login')
                ->with('error', 'Unable to determine current school. Please login again.');
        }

        // Find student
        $student = Student::with(['class', 'section'])
            ->where(function ($query) use ($identifier) {
                $query->where('id', $identifier)
                    ->orWhere('admission_number', $identifier);
            })
            ->where('school_id', $currentSchoolId)
            ->firstOrFail();

        // Fetch student fees with payments
        $assignedFees = AssignFee::where('student_id', $student->id)
            ->with(['feeMaster', 'feeGroup', 'feeType'])
            ->get();

        $studentFees = $assignedFees->map(function ($assignedFee) {
            // Get all payments for this assigned fee
            $payments = CollectFee::where('assign_fee_id', $assignedFee->id)->get();

            // Calculate total paid amount
            $totalPaid = $payments->sum('paid_amount');

            // Get the fee amount
            $feeAmount = $assignedFee->feeMaster->amount ?? 0;

            // Calculate balance
            $balance = $feeAmount - $totalPaid;

            // Determine status
            $status = 'unpaid';
            if ($totalPaid > 0 && $balance > 0) {
                $status = 'pending';
            } elseif ($balance <= 0) {
                $status = 'paid';
            }

            return [
                'id' => $assignedFee->id,
                'fee_type' => $assignedFee->feeType->name ?? 'N/A',
                'fee_group' => $assignedFee->feeGroup->name ?? 'N/A',
                'amount' => $feeAmount,
                'due_date' => $assignedFee->feeMaster->due_date ?? null,
                'paid_amount' => $totalPaid,
                'balance' => max(0, $balance),
                'status' => $status,
                'payments' => $payments
            ];
        });

        // Calculate totals
        $totalFees = $studentFees->sum('amount');
        $totalPaid = $studentFees->sum('paid_amount');
        $totalPending = $studentFees->sum('balance');

        // Get school details
        $school = School::find($currentSchoolId);

        // Generate PDF
        $pdf = FacadePdf::loadView('client.schoolPanel.peoples.student.feesPdf', compact(
            'student',
            'studentFees',
            'totalFees',
            'totalPaid',
            'totalPending',
            'school'
        ));

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Download the PDF
        return $pdf->download('student-fees-' . $student->admission_number . '.pdf');
    }

    /**
     * Get student's class information for the timetable
     */
    public function getStudentClass($admission_number)
    {
        try {
            $student = Student::with(['class', 'section'])
                ->where('admission_number', $admission_number)
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found',
                ], 404);
            }

            if (!$student->class) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student has no assigned class',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'class' => [
                    'id' => $student->class->id,
                    'name' => $student->class->name,
                    'section_id' => $student->section_id,
                    'section_name' => $student->section ? $student->section->name : null,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching student class: ' . $e->getMessage(),
            ], 500);
        }
    }
}
