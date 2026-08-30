@include('client.schoolPanel.layout.header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="school-id" content="{{ session('school_id') }}">
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Peoples / <span class="text-l text-gray-500">Edit Teacher</span>
            </h1>
            <a href="{{ route('school.teachers') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="ri-arrow-left-line align-middle me-1"></i> Back to Teachers
            </a>
        </div>
        
        {{-- Success and Error Messages --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" class="absolute top-0 right-0 px-4 py-3" data-bs-dismiss="alert" aria-label="Close">
                    <span class="text-red-700">&times;</span>
                </button>
            </div>
        @endif
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" class="absolute top-0 right-0 px-4 py-3" data-bs-dismiss="alert" aria-label="Close">
                    <span class="text-green-700">&times;</span>
                </button>
            </div>
        @endif

        <form id="teacherForm" action="{{ route('school.teachers.update1', $teacher->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- Method spoofing for PUT request --}}
            <input type="hidden" name="school_id" value="{{ session('school_id') }}">
            <input type="hidden" name="notes" value="{{ old('notes', $teacher->notes) }}"> {{-- Pre-fill notes --}}
        
            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-100 rounded-lg">
                <p class="text-sm text-gray-700">Fields marked with <span class="text-red-500">*</span> are required.</p>
            </div>
        
            <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Personal Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                        <input type="text" name="firstName" id="firstName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('firstName', $teacher->first_name) }}" required>
                        @error('firstName')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                        <input type="text" name="lastName" id="lastName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('lastName', $teacher->last_name) }}" required>
                        @error('lastName')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender *</label>
                        <select name="gender" id="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $teacher->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $teacher->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $teacher->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="dob" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth (Optional)</label>
                        <input type="date" name="dob" id="dob" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('dob', $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('Y-m-d') : '') }}">
                        @error('dob')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('email', $teacher->email) }}" required>
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="primaryContact" class="block text-sm font-medium text-gray-700 mb-1">Primary Contact *</label>
                        <input type="text" name="primaryContact" id="primaryContact" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('primaryContact', $teacher->primary_contact) }}" required>
                        @error('primaryContact')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="bloodGroup" class="block text-sm font-medium text-gray-700 mb-1">Blood Group (Optional)</label>
                        <input type="text" name="bloodGroup" id="bloodGroup" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('bloodGroup', $teacher->blood_group) }}">
                        @error('bloodGroup')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="maritalStatus" class="block text-sm font-medium text-gray-700 mb-1">Marital Status (Optional)</label>
                        <input type="text" name="maritalStatus" id="maritalStatus" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('maritalStatus', $teacher->marital_status) }}">
                        @error('maritalStatus')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="fatherName" class="block text-sm font-medium text-gray-700 mb-1">Father's Name (Optional)</label>
                        <input type="text" name="fatherName" id="fatherName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('fatherName', $teacher->father_name) }}">
                        @error('fatherName')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="motherName" class="block text-sm font-medium text-gray-700 mb-1">Mother's Name (Optional)</label>
                        <input type="text" name="motherName" id="motherName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('motherName', $teacher->mother_name) }}">
                        @error('motherName')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="languagesKnown" class="block text-sm font-medium text-gray-700 mb-1">Languages Known (Optional)</label>
                        <input type="text" name="languagesKnown" id="languagesKnown" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('languagesKnown', $teacher->languages_known) }}">
                        @error('languagesKnown')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-1">Profile Image (Optional)</label>
                        @if($teacher->profile_image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($teacher->profile_image) }}" alt="Profile Picture" class="w-24 h-24 object-cover rounded-full">
                                <p class="text-xs text-gray-500 mt-1">Current Profile Image</p>
                            </div>
                        @endif
                        <input type="file" name="profile_image" id="profile_image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2.5 focus:outline-none">
                        <p class="mt-1 text-sm text-gray-500">Accepted formats: JPG, PNG, GIF. Max size: 4MB. (Leave empty to keep current)</p>
                        @error('profile_image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Address Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="currentAddress" class="block text-sm font-medium text-gray-700 mb-1">Current Address (Optional)</label>
                        <textarea name="currentAddress" id="currentAddress" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('currentAddress', $teacher->current_address) }}</textarea>
                        @error('currentAddress')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="permanentAddress" class="block text-sm font-medium text-gray-700 mb-1">Permanent Address (Optional)</label>
                        <textarea name="permanentAddress" id="permanentAddress" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('permanentAddress', $teacher->permanent_address) }}</textarea>
                        @error('permanentAddress')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Employment Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="dateOfJoining" class="block text-sm font-medium text-gray-700 mb-1">Date of Joining (Optional)</label>
                        <input type="date" name="dateOfJoining" id="dateOfJoining" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('dateOfJoining', $teacher->date_of_joining ? \Carbon\Carbon::parse($teacher->date_of_joining)->format('Y-m-d') : '') }}">
                        @error('dateOfJoining')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject (Optional)</label>
                        <input type="text" name="subject_id" id="subject_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('subject_id', $teacher->subject_id) }}">
                        @error('subject_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="qualification" class="block text-sm font-medium text-gray-700 mb-1">Qualification (Optional)</label>
                        <input type="text" name="qualification" id="qualification" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('qualification', $teacher->qualification) }}">
                        @error('qualification')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="workExperience" class="block text-sm font-medium text-gray-700 mb-1">Work Experience (Optional)</label>
                        <input type="text" name="workExperience" id="workExperience" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('workExperience', $teacher->work_experience) }}">
                        @error('workExperience')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="previousSchool" class="block text-sm font-medium text-gray-700 mb-1">Previous School (Optional)</label>
                        <input type="text" name="previousSchool" id="previousSchool" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('previousSchool', $teacher->previous_school) }}">
                        @error('previousSchool')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="previousSchoolAddress" class="block text-sm font-medium text-gray-700 mb-1">Previous School Address (Optional)</label>
                        <input type="text" name="previousSchoolAddress" id="previousSchoolAddress" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('previousSchoolAddress', $teacher->previous_school_address) }}">
                        @error('previousSchoolAddress')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="previousSchoolPhone" class="block text-sm font-medium text-gray-700 mb-1">Previous School Phone (Optional)</label>
                        <input type="text" name="previousSchoolPhone" id="previousSchoolPhone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('previousSchoolPhone', $teacher->previous_school_phone) }}">
                        @error('previousSchoolPhone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="panNumber" class="block text-sm font-medium text-gray-700 mb-1">PAN Number (Optional)</label>
                        <input type="text" name="panNumber" id="panNumber" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('panNumber', $teacher->pan_number) }}">
                        @error('panNumber')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status (Optional)</label>
                        <input type="text" name="status" id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('status', $teacher->status) }}">
                        @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="dateOfLeaving" class="block text-sm font-medium text-gray-700 mb-1">Date of Leaving (Optional)</label>
                        <input type="date" name="dateOfLeaving" id="dateOfLeaving" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('dateOfLeaving', $teacher->date_of_leaving ? \Carbon\Carbon::parse($teacher->date_of_leaving)->format('Y-m-d') : '') }}">
                        @error('dateOfLeaving')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Payroll Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="epfNo" class="block text-sm font-medium text-gray-700 mb-1">EPF No (Optional)</label>
                        <input type="text" name="epfNo" id="epfNo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('epfNo', $teacher->epf_no) }}">
                        @error('epfNo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="basicSalary" class="block text-sm font-medium text-gray-700 mb-1">Basic Salary (Optional)</label>
                        <input type="number" name="basicSalary" id="basicSalary" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('basicSalary', $teacher->basic_salary) }}" step="0.01">
                        @error('basicSalary')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="contractType" class="block text-sm font-medium text-gray-700 mb-1">Contract Type (Optional)</label>
                        <input type="text" name="contractType" id="contractType" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('contractType', $teacher->contract_type) }}">
                        @error('contractType')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="workShift" class="block text-sm font-medium text-gray-700 mb-1">Work Shift (Optional)</label>
                        <input type="text" name="workShift" id="workShift" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('workShift', $teacher->work_shift) }}">
                        @error('workShift')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="workLocation" class="block text-sm font-medium text-gray-700 mb-1">Work Location (Optional)</label>
                        <input type="text" name="workLocation" id="workLocation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('workLocation', $teacher->work_location) }}">
                        @error('workLocation')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Leave Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="medicalLeaves" class="block text-sm font-medium text-gray-700 mb-1">Medical Leaves (Optional)</label>
                        <input type="number" name="medicalLeaves" id="medicalLeaves" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('medicalLeaves', $teacher->medical_leaves) }}" min="0">
                        @error('medicalLeaves')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="casualLeaves" class="block text-sm font-medium text-gray-700 mb-1">Casual Leaves (Optional)</label>
                        <input type="number" name="casualLeaves" id="casualLeaves" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('casualLeaves', $teacher->casual_leaves) }}" min="0">
                        @error('casualLeaves')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="maternityLeaves" class="block text-sm font-medium text-gray-700 mb-1">Maternity Leaves (Optional)</label>
                        <input type="number" name="maternityLeaves" id="maternityLeaves" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('maternityLeaves', $teacher->maternity_leaves) }}" min="0">
                        @error('maternityLeaves')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="sickLeaves" class="block text-sm font-medium text-gray-700 mb-1">Sick Leaves (Optional)</label>
                        <input type="number" name="sickLeaves" id="sickLeaves" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('sickLeaves', $teacher->sick_leaves) }}" min="0">
                        @error('sickLeaves')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Bank Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="bankName" class="block text-sm font-medium text-gray-700 mb-1">Bank Name (Optional)</label>
                        <input type="text" name="bankName" id="bankName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('bankName', $teacher->bank_name) }}">
                        @error('bankName')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="branch" class="block text-sm font-medium text-gray-700 mb-1">Branch (Optional)</label>
                        <input type="text" name="branch" id="branch" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('branch', $teacher->branch) }}">
                        @error('branch')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="ifscNumber" class="block text-sm font-medium text-gray-700 mb-1">IFSC Number (Optional)</label>
                        <input type="text" name="ifscNumber" id="ifscNumber" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('ifscNumber', $teacher->ifsc_number) }}">
                        @error('ifscNumber')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="otherInformation" class="block text-sm font-medium text-gray-700 mb-1">Other Information (Optional)</label>
                        <textarea name="otherInformation" id="otherInformation" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('otherInformation', $teacher->other_information) }}</textarea>
                        @error('otherInformation')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Hostel and Transport Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="transport_enabled" class="block text-sm font-medium text-gray-700 mb-1">Transport Enabled?</label>
                        <select name="transport_enabled" id="transport_enabled" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="false" {{ old('transport_enabled', $teacher->transport_enabled ? 'true' : 'false') == 'false' ? 'selected' : '' }}>No</option>
                            <option value="true" {{ old('transport_enabled', $teacher->transport_enabled ? 'true' : 'false') == 'true' ? 'selected' : '' }}>Yes</option>
                        </select>
                        @error('transport_enabled')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div id="pickup_point_div" style="{{ old('transport_enabled', $teacher->transport_enabled ? 'true' : 'false') == 'true' ? '' : 'display:none;' }}">
                        <label for="pickup_point_id" class="block text-sm font-medium text-gray-700 mb-1">Pickup Point</label>
                        <select name="pickup_point_id" id="pickup_point_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Select Pickup Point</option>
                            {{-- Dynamically load pickup points --}}
                        </select>
                        @error('pickup_point_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="hostel_enabled" class="block text-sm font-medium text-gray-700 mb-1">Hostel Enabled?</label>
                        <select name="hostel_enabled" id="hostel_enabled" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="false" {{ old('hostel_enabled', $teacher->hostel_enabled ? 'true' : 'false') == 'false' ? 'selected' : '' }}>No</option>
                            <option value="true" {{ old('hostel_enabled', $teacher->hostel_enabled ? 'true' : 'false') == 'true' ? 'selected' : '' }}>Yes</option>
                        </select>
                        @error('hostel_enabled')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div id="hostel_details_div" style="{{ old('hostel_enabled', $teacher->hostel_enabled ? 'true' : 'false') == 'true' ? '' : 'display:none;' }}">
                        <div>
                            <label for="hostel_id" class="block text-sm font-medium text-gray-700 mb-1">Hostel</label>
                            <select name="hostel_id" id="hostel_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">Select Hostel</option>
                                {{-- Dynamically load hostels --}}
                            </select>
                            @error('hostel_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="mt-4">
                            <label for="room_id" class="block text-sm font-medium text-gray-700 mb-1">Room</label>
                            <select name="room_id" id="room_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">Select Room</option>
                                {{-- Dynamically load rooms --}}
                            </select>
                            @error('room_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Documents</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="medical_condition_document" class="block text-sm font-medium text-gray-700 mb-1">Medical Condition Document (Optional)</label>
                        @if($teacher->medical_condition_document)
                            <div class="mb-2 flex items-center">
                                <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                                <a href="{{ Storage::url($teacher->medical_condition_document) }}" target="_blank" class="text-blue-600 hover:underline">View Current Document</a>
                            </div>
                        @endif
                        <input type="file" name="medical_condition_document" id="medical_condition_document" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2.5 focus:outline-none">
                        <p class="mt-1 text-sm text-gray-500">Accepted format: PDF. Max size: 4MB. (Leave empty to keep current)</p>
                        @error('medical_condition_document')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="transfer_certificate_document" class="block text-sm font-medium text-gray-700 mb-1">Transfer Certificate Document (Optional)</label>
                        @if($teacher->transfer_certificate_document)
                            <div class="mb-2 flex items-center">
                                <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                                <a href="{{ Storage::url($teacher->transfer_certificate_document) }}" target="_blank" class="text-blue-600 hover:underline">View Current Document</a>
                            </div>
                        @endif
                        <input type="file" name="transfer_certificate_document" id="transfer_certificate_document" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2.5 focus:outline-none">
                        <p class="mt-1 text-sm text-gray-500">Accepted format: PDF. Max size: 4MB. (Leave empty to keep current)</p>
                        @error('transfer_certificate_document')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded" onclick="window.location.href='{{ route('school.teachers.show', $teacher->id) }}'">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update Teacher
                </button>
            </div>
        </form>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Alert dismissal
        const alerts = document.querySelectorAll('[data-bs-dismiss="alert"]');
        alerts.forEach(button => {
            button.addEventListener('click', function() {
                const alert = this.closest('[role="alert"]');
                alert.remove();
            });
        });

        // Profile image preview
        const profileImageInput = document.getElementById('profile_image');
        if (profileImageInput) {
            profileImageInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        let img = profileImageInput.closest('.md:col-span-2').querySelector('img');
                        if (!img) {
                            // Create img tag if it doesn't exist (e.g., if no profile image was initially set)
                            const imgContainer = document.createElement('div');
                            imgContainer.className = 'mb-2';
                            img = document.createElement('img');
                            img.className = 'w-24 h-24 object-cover rounded-full';
                            imgContainer.appendChild(img);
                            profileImageInput.before(imgContainer); // Insert before the input
                        }
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Toggle visibility for Transport details
        const transportEnabledSelect = document.getElementById('transport_enabled');
        const pickupPointDiv = document.getElementById('pickup_point_div');
        
        function toggleTransportFields() {
            if (transportEnabledSelect.value === 'true') {
                pickupPointDiv.style.display = 'block';
                fetchPickupPoints(); // Fetch pickup points when enabled
            } else {
                pickupPointDiv.style.display = 'none';
                document.getElementById('pickup_point_id').innerHTML = '<option value="">Select Pickup Point</option>'; // Clear options
            }
        }
        transportEnabledSelect.addEventListener('change', toggleTransportFields);
        toggleTransportFields(); // Initial call to set state

        // Fetch Pickup Points (client-side dynamic loading)
        function fetchPickupPoints() {
            const schoolId = document.querySelector('meta[name="school-id"]').content;
            const pickupPointSelect = document.getElementById('pickup_point_id');
            // Clear existing options, keep the default
            pickupPointSelect.innerHTML = '<option value="">Select Pickup Point</option>';

            fetch(`/api/school/${schoolId}/pickup-points`) // Adjust this API endpoint as needed
                .then(response => response.json())
                .then(data => {
                    data.forEach(point => {
                        const option = document.createElement('option');
                        option.value = point.id;
                        option.textContent = point.name; // Assuming 'name' is the display field
                        // Set selected if it's the current teacher's pickup_point_id
                        if ("{{ $teacher->pickup_point_id }}" == point.id) {
                            option.selected = true;
                        }
                        pickupPointSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching pickup points:', error));
        }

        // Toggle visibility for Hostel details
        const hostelEnabledSelect = document.getElementById('hostel_enabled');
        const hostelDetailsDiv = document.getElementById('hostel_details_div');

        function toggleHostelFields() {
            if (hostelEnabledSelect.value === 'true') {
                hostelDetailsDiv.style.display = 'block';
                fetchHostels(); // Fetch hostels when enabled
            } else {
                hostelDetailsDiv.style.display = 'none';
                document.getElementById('hostel_id').innerHTML = '<option value="">Select Hostel</option>'; // Clear options
                document.getElementById('room_id').innerHTML = '<option value="">Select Room</option>'; // Clear options
            }
        }
        hostelEnabledSelect.addEventListener('change', toggleHostelFields);
        toggleHostelFields(); // Initial call to set state

        // Fetch Hostels (client-side dynamic loading)
        function fetchHostels() {
            const schoolId = document.querySelector('meta[name="school-id"]').content;
            const hostelSelect = document.getElementById('hostel_id');
            hostelSelect.innerHTML = '<option value="">Select Hostel</option>';

            fetch(`/api/school/${schoolId}/hostels`) // Adjust this API endpoint as needed
                .then(response => response.json())
                .then(data => {
                    data.forEach(hostel => {
                        const option = document.createElement('option');
                        option.value = hostel.id;
                        option.textContent = hostel.name; // Assuming 'name' is the display field
                        if ("{{ $teacher->hostel_id }}" == hostel.id) {
                            option.selected = true;
                        }
                        hostelSelect.appendChild(option);
                    });
                    // If a hostel is pre-selected, fetch rooms for it
                    if ("{{ $teacher->hostel_id }}") {
                        fetchRooms("{{ $teacher->hostel_id }}");
                    }
                })
                .catch(error => console.error('Error fetching hostels:', error));
        }

        // Fetch Rooms based on selected Hostel
        const hostelSelect = document.getElementById('hostel_id');
        hostelSelect.addEventListener('change', function() {
            const selectedHostelId = this.value;
            if (selectedHostelId) {
                fetchRooms(selectedHostelId);
            } else {
                document.getElementById('room_id').innerHTML = '<option value="">Select Room</option>';
            }
        });

        function fetchRooms(hostelId) {
            const roomSelect = document.getElementById('room_id');
            roomSelect.innerHTML = '<option value="">Select Room</option>';

            fetch(`/api/hostels/${hostelId}/rooms`) // Adjust this API endpoint as needed
                .then(response => response.json())
                .then(data => {
                    data.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.id;
                        option.textContent = `Room ${room.room_number} (Capacity: ${room.capacity})`; // Adjust display as needed
                        if ("{{ $teacher->room_id }}" == room.id) {
                            option.selected = true;
                        }
                        roomSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching rooms:', error));
        }

    });
</script>