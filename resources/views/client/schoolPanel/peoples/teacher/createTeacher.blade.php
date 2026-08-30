@include('client.schoolPanel.layout.header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Add school ID meta tag -->
<meta name="school-id" content="{{ session('school_id') }}">
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Peoples / <span class="text-l text-gray-500">Create Teacher</span>
            </h1>
        </div>
        
        @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
        <button type="button" class="absolute top-0 right-0 px-4 py-3" data-bs-dismiss="alert" aria-label="Close">
            <span class="text-red-700">&times;</span>
        </button>
    </div>
@endif
        
        <form id="teacherForm" action="{{ route('school.teachers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="school_id" value="{{ session('school_id') }}">
            <input type="hidden" name="notes" value="">
        
            <!-- Validation message -->
            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-100 rounded-lg">
                <p class="text-sm text-gray-700">Fields marked with <span class="text-red-500">*</span> are required.</p>
            </div>
        
        <div class="m-2 mb-8  bg-white shadow-l rounded-xl border border-gray-200">
            <h3
                class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-xl rounded-bl-none rounded-br-none border border-blue-50 p-4">
                Personal Information
            </h3>
            <div class="p-6">
                <div class="flex items-start gap-4 mb-6">
                    <div
                        class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                        <img id="profilePreview" src="" alt="Preview"
                            class="hidden w-full h-full object-cover rounded-lg">
                        <svg id="profileIcon" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <input type="file" id="profileInput" name="profile_image" accept="image/*" class="hidden"
                            onchange="previewImage(event, 'profilePreview', 'profileIcon')" />
                        <div class="flex gap-2 mb-2">
                            <button type="button" onclick="document.getElementById('profileInput').click()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
                                Upload
                            </button>
                            <button type="button" onclick="removeProfileImage('profilePreview', 'profileIcon')"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                Remove
                            </button>
                        </div>
                        <p class="text-sm text-gray-500">Upload image size 4MB, Format JPG, PNG, SVG</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 border-gray-200">
                    
                    <div>
                        <label for="firstName" class="block text-sm font-semibold text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                        <input type="text" id="firstName" name="firstName" required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="lastName" class="block text-sm font-semibold text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" id="lastName" name="lastName" required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-semibold text-gray-700 mb-1">Subject</label>
                        <select id="subject_id" name="subject_id"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                            <option value="">Select Subject</option>
                            <!-- Dynamic subjects will be loaded via JavaScript -->
                        </select>
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-semibold text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                        <select id="gender" name="gender" required
                            class="bg-white mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="primaryContact" class="block text-sm font-semibold text-gray-700 mb-1">Primary
                            Contact Number <span class="text-red-500">*</span></label>
                        <input type="tel" id="primaryContact" name="primaryContact" required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email
                            Address </label>
                        <input type="email" id="email" name="email" 
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="bloodGroup" class="block text-sm font-semibold text-gray-700 mb-1">Blood
                            Group</label>
                        <select id="bloodGroup" name="bloodGroup"
                            class="bg-white mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                            <option value="">Select Blood Group</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div>
                        <label for="dateOfJoining" class="block text-sm font-semibold text-gray-700 mb-1">Date of
                            Joining <span class="text-red-500"></span></label>
                        <input type="date" id="dateOfJoining" name="dateOfJoining" 
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="fatherName" class="block text-sm font-semibold text-gray-700 mb-1">Father's Name</label>
                        <input type="text" id="fatherName" name="fatherName"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="motherName" class="block text-sm font-semibold text-gray-700 mb-1">Mother's Name</label>
                        <input type="text" id="motherName" name="motherName"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <!-- Spouse Information -->
<div>
    <label for="spouseType" class="block text-sm font-semibold text-gray-700 mb-1">
        Relationship Type
    </label>
    <select id="spouseType" name="spouse_type"
        onchange="toggleSpouseInput(this)"
        class="bg-white mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm 
               focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base 
               transition duration-150 ease-in-out">
        <option value="">Select Type</option>
        <option value="W/O">W/O (Wife Of)</option>
        <option value="H/O">H/O (Husband Of)</option>
    </select>
</div>

<div id="spouseNameContainer" class="hidden">
    <label for="spouseName" id="spouseNameLabel"
        class="block text-sm font-semibold text-gray-700 mb-1">
        Spouse Name
    </label>
    <input type="text" id="spouseName" name="spouse_name"
        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm 
               focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base 
               transition duration-150 ease-in-out">
</div>

                    <div>
                        <label for="dob" class="block text-sm font-semibold text-gray-700 mb-1">Date of
                            Birth</label>
                        <input type="date" id="dob" name="dob"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="maritalStatus" class="block text-sm font-semibold text-gray-700 mb-1">Marital Status</label>
                        <select id="maritalStatus" name="maritalStatus"
                            class="bg-white mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                            <option value="">Select Status</option>
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                            <option value="divorced">Divorced</option>
                            <option value="widowed">Widowed</option>
                        </select>
                    </div>
                    <div>
                        <label for="languagesKnown" class="block text-sm font-semibold text-gray-700 mb-1">Languages
                            Known</label>
                        <div id="languagesKnownTags"
                            class="mt-1 flex flex-wrap gap-2 p-2 border border-gray-300 rounded-lg shadow-sm focus-within:ring-indigo-500 focus-within:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                            <input type="text" id="languagesKnownInput"
                                class="flex-1 min-w-0 outline-none bg-transparent"
                                placeholder="Add language and press Enter"
                                onkeydown="addTagOnEnter(event, 'languagesKnown', 'languagesKnownTags', 'languagesKnownHidden')" />
                        </div>
                        <input type="hidden" id="languagesKnownHidden" name="languagesKnown" value="" />
                    </div>
                    <div>
                        <label for="qualification" class="block text-sm font-semibold text-gray-700 mb-1">Qualification</label>
                        <input type="text" id="qualification" name="qualification"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="workExperience" class="block text-sm font-semibold text-gray-700 mb-1">Work Experience</label>
                        <input type="text" id="workExperience" name="workExperience"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="previousSchool" class="block text-sm font-semibold text-gray-700 mb-1">Previous School (if Any)</label>
                        <input type="text" id="previousSchool" name="previousSchool"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="previousSchoolAddress" class="block text-sm font-semibold text-gray-700 mb-1">Previous School Address</label>
                        <input type="text" id="previousSchoolAddress" name="previousSchoolAddress"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="previousSchoolPhone" class="block text-sm font-semibold text-gray-700 mb-1">Previous School Phone No</label>
                        <input type="tel" id="previousSchoolPhone" name="previousSchoolPhone"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="panNumber" class="block text-sm font-semibold text-gray-700 mb-1">PAN Number / ID Number</label>
                        <input type="text" id="panNumber" name="panNumber"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                            <option value="">Select Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="onLeave">On Leave</option>
                            <option value="transferred">Transferred</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200">
            <h3
                class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-xl rounded-bl-none rounded-br-none border border-blue-50 p-4">
                Address
            </h3>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="currentAddress" class="block text-sm font-semibold text-gray-700 mb-1">Current
                            Address</label>
                        <textarea id="currentAddress" name="currentAddress" rows="1"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out"></textarea>
                    </div>
                    <div>
                        <label for="permanentAddress" class="block text-sm font-semibold text-gray-700 mb-1">Permanent
                            Address</label>
                        <textarea id="permanentAddress" name="permanentAddress" rows="1"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out"></textarea>
                    </div>
                </div>
            </div>
        </div>
        {{-- Payroll Section --}}
        <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200">
            <h3
                class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-xl rounded-bl-none rounded-br-none border border-blue-50 p-4">
                Payroll
            </h3>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div>
                        <label for="epfNo" class="block text-sm font-semibold text-gray-700 mb-1">EPF No</label>
                        <input type="text" id="epfNo" name="epfNo"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="basicSalary" class="block text-sm font-semibold text-gray-700 mb-1">Basic Salary</label>
                        <input type="number" id="basicSalary" name="basicSalary"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="contractType" class="block text-sm font-semibold text-gray-700 mb-1">Contract Type</label>
                        <select id="contractType" name="contractType"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                            <option value="">Select Contract Type</option>
                            <option value="permanent">Permanent</option>
                            <option value="contract">Contract</option>
                            <option value="partTime">Part Time</option>
                            <option value="probation">Probation</option>
                        </select>
                    </div>
                    <div>
                        <label for="workShift" class="block text-sm font-semibold text-gray-700 mb-1">Work Shift</label>
                        <select id="workShift" name="workShift"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                            <option value="">Select Work Shift</option>
                            <option value="morning">Morning Shift</option>
                            <option value="evening">Evening Shift</option>
                            <option value="night">Night Shift</option>
                            <option value="fullDay">Full Day</option>
                        </select>
                    </div>
                    <div>
                        <label for="workLocation" class="block text-sm font-semibold text-gray-700 mb-1">Work Location</label>
                        <input type="text" id="workLocation" name="workLocation"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="dateOfLeaving" class="block text-sm font-semibold text-gray-700 mb-1">Date of Leaving</label>
                        <input type="date" id="dateOfLeaving" name="dateOfLeaving"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                </div>
            </div>
        </div>

        {{-- Leaves Section --}}
        <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200">
            <h3
                class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-xl rounded-bl-none rounded-br-none border border-blue-50 p-4">
                Leaves
            </h3>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div>
                        <label for="medicalLeaves" class="block text-sm font-semibold text-gray-700 mb-1">Medical Leaves</label>
                        <input type="number" id="medicalLeaves" name="medicalLeaves" min="0"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="casualLeaves" class="block text-sm font-semibold text-gray-700 mb-1">Casual Leaves</label>
                        <input type="number" id="casualLeaves" name="casualLeaves" min="0"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="maternityLeaves" class="block text-sm font-semibold text-gray-700 mb-1">Maternity Leaves</label>
                        <input type="number" id="maternityLeaves" name="maternityLeaves" min="0"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="sickLeaves" class="block text-sm font-semibold text-gray-700 mb-1">Sick Leaves</label>
                        <input type="number" id="sickLeaves" name="sickLeaves" min="0"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                </div>
            </div>
        </div>

        {{-- Other Details Section --}}
        <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200">
            <h3 class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-xl rounded-bl-none rounded-br-none border border-blue-50 p-4 flex items-center">
                Bank Account Details
            </h3>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label for="bankName" class="block text-sm font-semibold text-gray-700 mb-1">Bank Name</label>
                        <input type="text" id="bankName" name="bankName"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="branch" class="block text-sm font-semibold text-gray-700 mb-1">Branch</label>
                        <input type="text" id="branch" name="branch"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="ifscNumber" class="block text-sm font-semibold text-gray-700 mb-1">IFSC Number</label>
                        <input type="text" id="ifscNumber" name="ifscNumber"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                </div>
                <div class="mb-6">
                    <label for="otherInformation" class="block text-sm font-semibold text-gray-700 mb-1">Other Information</label>
                    <textarea id="otherInformation" name="otherInformation" rows="3"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out"></textarea>
                </div>
            </div>
        </div>


        {{-- Transport Information Section --}}
        <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200">
            <h3
                class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-xl rounded-bl-none rounded-br-none border border-blue-50 p-4 flex items-center justify-between">
                Transport Information
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="transport_enabled" value="true" class="sr-only peer" onchange="toggleTransportInfo(this)">
                    <div
                        class="w-11 h-6 bg-gray-200  peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                    </div>
                </label>
            </h3>
            <div class="p-6" id="transportInfoForm">
                <div id="transportFields" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 {{-- Initially hidden, controlled by JS --}}">
                    <div>
                        <label for="pickupPoint" class="block text-sm font-semibold text-gray-700 mb-1">Pickup Point</label>
                        <select id="pickupPoint" name="pickup_point_id" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                            <option value="">Select Pickup Point</option>
                        </select>
                    </div>
                    <div>
                        <label for="routeName" class="block text-sm font-semibold text-gray-700 mb-1">Route</label>
                        <input type="text" id="routeName" name="routeName" readonly
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100 cursor-not-allowed">
                    </div>
                    <div>
                        <label for="vehicleNumber" class="block text-sm font-semibold text-gray-700 mb-1">Vehicle Number</label>
                        <input type="text" id="vehicleNumber" name="vehicleNumber" readonly
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100 cursor-not-allowed">
                    </div>
                </div>
            </div>
        </div>

        {{-- Hostel Information Section --}}
        <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200">
            <h3
                class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-xl rounded-bl-none rounded-br-none border border-blue-50 p-4 flex items-center justify-between">
                Hostel Information
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="hostel_enabled" value="true" class="sr-only peer" onchange="toggleHostelInfo(this)">
                    <div
                        class="w-11 h-6 bg-gray-200   peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                    </div>
                </label>
            </h3>
            <div class="p-6" id="hostelInfoForm">
                <div id="hostelFields" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 hidden">
                    <div>
                        <label for="hostelSelect" class="block text-sm font-semibold text-gray-700 mb-1">Hostel</label>
                        <select id="hostelSelect" name="hostel_id" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                            <option value="">Select Hostel</option>
                        </select>
                    </div>
                    <div>
                        <label for="roomNumberSelect" class="block text-sm font-semibold text-gray-700 mb-1">Room Number</label>
                        <select id="roomNumberSelect" name="room_id" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                            <option value="">Select Room</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Documents Section --}}
        <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200">
            <h3
                class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-xl rounded-bl-none rounded-br-none border border-blue-50 p-4">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3V11.25m6 4.5H18a2.25 2.25 0 0 0-2.25 2.25V21l3-3 3 3v-2.25a2.25 2.25 0 0 0-2.25-2.25Z" />
                    </svg>
                    Documents
                </div>
            </h3>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="medical_condition_document" class="block text-base font-semibold text-gray-700 mb-2">Medical
                            Condition</label>
                        <p class="text-sm text-gray-500 mb-2">Upload image size of 4MB, Accepted Format PDF</p>
                        <input type="file" id="medical_condition_document" name="medical_condition_document" accept=".pdf"
                            class="hidden"
                            onchange="updateFileName(event, 'medicalConditionFileName', 'medicalConditionUploadButton', 'medicalConditionChangeButton')">
                        <div class="flex items-center gap-2">
                            <button type="button" id="medicalConditionUploadButton"
                                onclick="document.getElementById('medical_condition_document').click()"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                </svg>
                                Upload Document
                            </button>
                            <button type="button" id="medicalConditionChangeButton"
                                onclick="document.getElementById('medical_condition_document').click()"
                                class="px-4 py-2 text-sm font-medium text-blue-700 border border-blue-300 rounded-md hover:bg-blue-100 flex items-center hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Change
                            </button>
                            <span id="medicalConditionFileName" class="text-sm text-gray-600"></span>
                        </div>
                    </div>
                    <div>
                        <label for="transfer_certificate_document"
                            class="block text-base font-semibold text-gray-700 mb-2">Upload Transfer
                            Certificate</label>
                        <p class="text-sm text-gray-500 mb-2">Upload image size of 4MB, Accepted Format PDF</p>
                        <input type="file" id="transfer_certificate_document" name="transfer_certificate_document" accept=".pdf"
                            class="hidden"
                            onchange="updateFileName(event, 'transferCertificateFileName', 'transferCertificateUploadButton', 'transferCertificateChangeButton')">
                        <div class="flex items-center gap-2">
                            <button type="button" id="transferCertificateUploadButton"
                                onclick="document.getElementById('transfer_certificate_document').click()"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                </svg>
                                Upload Document
                            </button>
                            <button type="button" id="transferCertificateChangeButton"
                                onclick="document.getElementById('transfer_certificate_document').click()"
                                class="px-4 py-2 text-sm font-medium text-blue-700 border border-blue-300 rounded-md hover:bg-blue-100 flex items-center hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Change
                            </button>
                            <span id="transferCertificateFileName" class="text-sm text-gray-600"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-8">
            <button type="button" class="px-6 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
                Cancel
            </button>
            <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                Add Teacher
            </button>
        </div>
        </form>
    </div>

</div>

@include('client.schoolPanel.layout.footer')

<script>
    let assignedRoutesData = []; // To store the fetched routes data
    let allSubjectsData = []; // To store the fetched subjects data

document.addEventListener('DOMContentLoaded', function () {
    console.log("DOM Content Loaded - Initializing teacher form...");
    
    // Fetch subjects from API with error handling
    fetchSubjects()
        .then(success => {
            console.log("Subject fetch complete, success:", success);
        })
        .catch(error => {
            console.error("Error in subject fetch:", error);
            // Show error message to user
            Swal.fire({
                title: 'Error',
                text: 'Failed to load subjects. Please refresh the page.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    
    // Initial setup for the transport info section visibility
    const transportFields = document.getElementById('transportFields');
    const transportToggleCheckbox = document.querySelector('input[name="transport_enabled"]');

    // Add a hidden field for transport_enabled=false when checkbox is unchecked
    const transportForm = document.getElementById('transportInfoForm');
    const transportHiddenField = document.createElement('input');
    transportHiddenField.type = 'hidden';
    transportHiddenField.name = 'transport_enabled';
    transportHiddenField.value = 'false';
    transportForm.appendChild(transportHiddenField);

    // Function to toggle visibility of transport fields
    window.toggleTransportInfo = function(checkbox) {
        if (checkbox.checked) {
            transportFields.classList.remove('hidden');
            fetchAssignedRoutes(); // Fetch routes when enabled
            transportHiddenField.disabled = true; // Disable the hidden field when checkbox is checked
            checkbox.value = 'true'; // Ensure the checkbox value is 'true'
        } else {
            transportFields.classList.add('hidden');
            // Optionally clear fields when disabled
            document.getElementById('pickupPoint').innerHTML = '<option value="">Select Pickup Point</option>';
            document.getElementById('routeName').value = '';
            document.getElementById('vehicleNumber').value = '';
            transportHiddenField.disabled = false; // Enable the hidden field when checkbox is unchecked
            checkbox.value = 'false'; // Ensure the checkbox value is 'false'
        }
    };

    // If the section is meant to be visible by default or based on some other initial state,
    // you might want to call fetchAssignedRoutes() here as well.
    // For now, it's called when the toggle is switched on.

    const pickupPointSelect = document.getElementById('pickupPoint');
    pickupPointSelect.addEventListener('change', function() {
        const selectedPickupPointId = this.value;
        displayRouteAndVehicle(selectedPickupPointId);
    });
});

async function fetchAssignedRoutes() {
    try {
        console.log("Fetching assigned routes...");
        
        // Use jQuery AJAX instead of fetch
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '{{ route("school.api.assigned-routes") }}',
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log("Routes API response:", data);
                    
                    if (data.success) {
                        assignedRoutesData = data.routes;
                        populatePickupPoints(assignedRoutesData);
                        resolve(true);
                    } else {
                        console.error('Failed to fetch assigned routes:', data.message);
                        resolve(false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error fetching routes:', status, error);
                    console.log('XHR Response:', xhr.responseText);
                    reject(new Error('Failed to fetch routes: ' + error));
                }
            });
        });
    } catch (error) {
        console.error('Error in fetchAssignedRoutes:', error);
        return false;
    }
}

function populatePickupPoints(routes) {
const pickupPointSelect = document.getElementById('pickupPoint');
pickupPointSelect.innerHTML = '<option value="">Select Pickup Point</option>'; // Clear existing options

// Filter routes to include only those with both vehicle and driver assigned
const filteredRoutes = routes.filter(route => route.vehicle !== null && route.driver !== null);

filteredRoutes.forEach(route => {
    if (route.pickup_points && route.pickup_points.length > 0) {
        route.pickup_points.forEach(point => {
            const option = document.createElement('option');
            option.value = point.id; // Use pickup point ID as value
            option.textContent = point.name;
            // Store route_id and vehicle_no directly on the option for easy retrieval
            option.dataset.routeId = route.id;
            option.dataset.routeName = route.route_name;
            option.dataset.vehicleNumber = route.vehicle ? route.vehicle.vehicle_no : 'N/A'; // Vehicle will not be 'N/A' due to filter
            pickupPointSelect.appendChild(option);
        });
    }
});
}

function displayRouteAndVehicle(selectedPickupPointId) {
    const routeNameInput = document.getElementById('routeName');
    const vehicleNumberInput = document.getElementById('vehicleNumber');
    
    routeNameInput.value = ''; // Clear previous values
    vehicleNumberInput.value = '';

    if (!selectedPickupPointId) {
        return; // No pickup point selected
    }

    // Find the selected option element to access its dataset
    const selectedOption = document.querySelector(`#pickupPoint option[value="${selectedPickupPointId}"]`);

    if (selectedOption) {
        routeNameInput.value = selectedOption.dataset.routeName;
        vehicleNumberInput.value = selectedOption.dataset.vehicleNumber;
    }
}

// Function to fetch subjects from the API
async function fetchSubjects() {
    try {
        console.log("Fetching subjects for teacher form...");
        
        // Use jQuery AJAX instead of fetch for better error handling and browser compatibility
        return new Promise((resolve, reject) => {
            $.ajax({
                url: '{{ route("school.api.active-subjects") }}',
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log("Subjects API response:", data);
                    
                    if (data.success) {
                        allSubjectsData = data.subjects;
                        console.log('Fetched subjects:', allSubjectsData);
                        populateSubjectDropdown(allSubjectsData);
                        resolve(true);
                    } else {
                        console.error('Failed to fetch subjects:', data.message);
                        resolve(false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error fetching subjects:', status, error);
                    console.log('XHR Response:', xhr.responseText);
                    reject(new Error('Failed to fetch subjects: ' + error));
                }
            });
        });
    } catch (error) {
        console.error('Error in fetchSubjects:', error);
        return false;
    }
}

// Function to populate the subject dropdown
function populateSubjectDropdown(subjects) {
    console.log("Populating subject dropdown with data:", subjects);
    
    const subjectSelect = document.getElementById('subject_id');
    if (!subjectSelect) {
        console.error("Subject select element not found in the DOM!");
        return;
    }
    
    subjectSelect.innerHTML = '<option value="">Select Subject</option>'; // Clear existing options
    
    if (!subjects || !Array.isArray(subjects) || subjects.length === 0) {
        console.error("No subjects data available to populate dropdown");
        return;
    }
    
    subjects.forEach(subject => {
        if (subject && subject.id && subject.name) {
            const option = document.createElement('option');
            option.value = subject.id;
            option.textContent = subject.name;
            subjectSelect.appendChild(option);
        }
    });
    
    console.log(`Subject dropdown populated with ${subjects.length} options`);
}

    // Form submission handler
    document.addEventListener('DOMContentLoaded', function() {
        const teacherForm = document.getElementById('teacherForm');
        
        // Function to clear validation errors
        function clearValidationErrors() {
            // Remove red borders from all inputs
            document.querySelectorAll('.border-red-500').forEach(el => {
                el.classList.remove('border-red-500');
            });
            
            // Remove all error message paragraphs
            document.querySelectorAll('.text-red-500.text-xs.mt-1').forEach(el => {
                el.remove();
            });
        }
        
        // Function to validate form fields
        function validateForm() {
            let isValid = true;
            clearValidationErrors();
            
            // Get all required fields
            const requiredFields = teacherForm.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    
                    // Add error message below the field
                    const errorElement = document.createElement('p');
                    errorElement.className = 'text-red-500 text-xs mt-1';
                    errorElement.textContent = 'This field is required';
                    
                    // Insert after the field or its parent if it's in a form group
                    const formGroup = field.closest('.form-group') || field.parentNode;
                    formGroup.appendChild(errorElement);
                }
            });
            
            // Validate email format if email is provided
            const emailField = document.getElementById('email');
            if (emailField.value.trim() && !isValidEmail(emailField.value.trim())) {
                isValid = false;
                emailField.classList.add('border-red-500');
                
                // Add error message below the field
                const errorElement = document.createElement('p');
                errorElement.className = 'text-red-500 text-xs mt-1';
                errorElement.textContent = 'Please enter a valid email address';
                
                // Insert after the field or its parent if it's in a form group
                const formGroup = emailField.closest('.form-group') || emailField.parentNode;
                formGroup.appendChild(errorElement);
            }
            
            // Validate phone number format if provided
            const phoneField = document.getElementById('primaryContact');
            if (phoneField.value.trim() && !isValidPhone(phoneField.value.trim())) {
                isValid = false;
                phoneField.classList.add('border-red-500');
                
                // Add error message below the field
                const errorElement = document.createElement('p');
                errorElement.className = 'text-red-500 text-xs mt-1';
                errorElement.textContent = 'Please enter a valid phone number';
                
                // Insert after the field or its parent if it's in a form group
                const formGroup = phoneField.closest('.form-group') || phoneField.parentNode;
                formGroup.appendChild(errorElement);
            }

            // Validate date fields (Date of Joining and Date of Birth)
            const dateOfJoiningField = document.getElementById('dateOfJoining');
            const dobField = document.getElementById('dob');
            
            // Check if date of joining is not in future
            if (dateOfJoiningField.value) {
                const joiningDate = new Date(dateOfJoiningField.value);
                const today = new Date();
                
                if (joiningDate > today) {
                    isValid = false;
                    dateOfJoiningField.classList.add('border-red-500');
                    
                    const errorElement = document.createElement('p');
                    errorElement.className = 'text-red-500 text-xs mt-1';
                    errorElement.textContent = 'Date of joining cannot be in the future';
                    
                    const formGroup = dateOfJoiningField.closest('.form-group') || dateOfJoiningField.parentNode;
                    formGroup.appendChild(errorElement);
                }
            }
            
            // Check if date of birth is valid (not in future and reasonable age)
            if (dobField.value) {
                const birthDate = new Date(dobField.value);
                const today = new Date();
                const minAgeDate = new Date();
                minAgeDate.setFullYear(today.getFullYear() - 18); // Minimum 18 years old
                
                if (birthDate > today) {
                    isValid = false;
                    dobField.classList.add('border-red-500');
                    
                    const errorElement = document.createElement('p');
                    errorElement.className = 'text-red-500 text-xs mt-1';
                    errorElement.textContent = 'Date of birth cannot be in the future';
                    
                    const formGroup = dobField.closest('.form-group') || dobField.parentNode;
                    formGroup.appendChild(errorElement);
                } else if (birthDate > minAgeDate) {
                    // Show warning for teachers younger than 18 (but don't block submission)
                    dobField.classList.add('border-yellow-500');
                    
                    const warningElement = document.createElement('p');
                    warningElement.className = 'text-yellow-500 text-xs mt-1';
                    warningElement.textContent = 'Warning: Teacher appears to be under 18 years old';
                    
                    const formGroup = dobField.closest('.form-group') || dobField.parentNode;
                    formGroup.appendChild(warningElement);
                }
            }
            
            return isValid;
        }
        
        // Helper function to validate email format
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        // Helper function to validate phone format
        function isValidPhone(phone) {
            // Allow digits, spaces, dashes, and parentheses, minimum 10 digits
            const phoneRegex = /^[\d\s\-()]{10,15}$/;
            return phoneRegex.test(phone);
        }
        
        teacherForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Validate form before submission
            if (!validateForm()) {
                // Scroll to the first error
                const firstError = document.querySelector('.border-red-500');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fix the highlighted errors before submitting the form.',
                    confirmButtonText: 'OK'
                });
                
                return false;
            }
            
            // Clear previous error messages and highlighting
            clearValidationErrors();
            
            const formData = new FormData(teacherForm);
            
            // Ensure CSRF token is included
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value;
            
            if (csrfToken) {
                // Check if it's already in the FormData, if not, add it
                if (!formData.has('_token')) {
                    formData.append('_token', csrfToken);
                }
            }
            
            // IMPORTANT: Ensure school_id is included
            const schoolId = document.querySelector('meta[name="school-id"]')?.getAttribute('content');
            if (schoolId) {
                // Always set the school_id, even if it's already in the form
                formData.set('school_id', schoolId);
                console.log('Added school_id:', schoolId);
            } else {
                console.error('Could not find school_id in meta tag');
            }
            
            // Set default values for fields that might be null but are required in the database
            if (!formData.get('contract_type') || formData.get('contract_type') === '') {
                formData.set('contract_type', 'permanent'); // Default value
                console.log('Set default contract_type: permanent');
            }
            
            // Format date fields (just send the date part without time)
            const dateFields = ['dateOfJoining', 'dob', 'dateOfLeaving'];
            dateFields.forEach(field => {
                if (formData.has(field) && formData.get(field)) {
                    // Keep the date format as is - the server will handle it
                    console.log(`${field}: ${formData.get(field)}`);
                }
            });
            
            console.log('Form submission started');
            
            // Debug: Log all form data values
            console.log('FormData contents:');
            for (const pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            // Show loading state with SweetAlert
            Swal.fire({
                title: 'Saving...',
                text: 'Please wait while we save the teacher information',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Show loading state
            const submitButton = teacherForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.textContent;
            submitButton.textContent = 'Saving...';
            submitButton.disabled = true;
            
            fetch(teacherForm.action, {
                method: 'POST',
                body: formData,
                // Do not set any Content-Type header - the browser will set it with the boundary for multipart/form-data
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('Response status:', response.status);
                
                // Check if response is a redirect
                if (response.redirected) {
                    console.log('Redirected to:', response.url);
                    window.location.href = response.url;
                    return { success: false, redirected: true };
                }
                
                return response.text().then(text => {
                    console.log('Raw response:', text);
                    try {
                        // Try to parse as JSON
                        return JSON.parse(text);
                    } catch (e) {
                        // If not valid JSON, return the text with error flag
                        console.error('Invalid JSON response:', text);
                        return { 
                            success: false, 
                            message: 'Server returned invalid response', 
                            html: text 
                        };
                    }
                });
            })
            .then(data => {
                console.log('Response data:', data);
                
                // Close loading indicator
                Swal.close();
                
                // Reset button state regardless of outcome
                submitButton.textContent = originalButtonText;
                submitButton.disabled = false;
                
                if (data.redirected) {
                    // Already handled by the redirect
                    return;
                }
                
                if (data.success) {
                    // Log data for debugging
                    console.log('Success data:', data);
                    console.log('Response structure:', JSON.stringify(data, null, 2));
                    
                    // Try to find the employee_id and password in different possible locations
                    let employeeId = '';
                    let password = '';
                    
                    if (data.data && data.data.employee_id) {
                        employeeId = data.data.employee_id;
                        password = data.data.password || '';
                    } else if (data.teacher && data.teacher.employee_id) {
                        employeeId = data.teacher.employee_id;
                        password = data.teacher.password || data.password || '';
                    } else if (data.employee_id) {
                        employeeId = data.employee_id;
                        password = data.password || '';
                    }
                    
                    console.log('Found Employee ID:', employeeId);
                    console.log('Found Password:', password);
                    
                    // Show success message
                    Swal.fire({
                        title: 'Teacher Added Successfully!',
                        html: `
                            <div class="text-left">
                                <p><strong>Employee ID:</strong> ${employeeId || 'Not provided'}</p>
                                <p><strong>Password:</strong> ${password || 'Not provided'}</p>
                                <p class="mt-3 text-sm text-red-500">Please save this information. The password will not be displayed again.</p>
                                <p class="mt-3 text-sm">This message will close in 20 seconds. Please copy the information.</p>
                            </div>
                        `,
                        icon: 'success',
                        confirmButtonText: 'Continue Now',
                        showCancelButton: true,
                        cancelButtonText: 'Copy to Clipboard',
                        timer: 20000, // 20 seconds auto-close
                        timerProgressBar: true
                    }).then((result) => {
                        if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                            // Redirect to teachers list page
                            window.location.href = '{{ route("school.teachers") }}';
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            // Copy to clipboard
                            const textToCopy = `Teacher ID: ${employeeId || 'Not provided'}\nPassword: ${password || 'Not provided'}`;
                            navigator.clipboard.writeText(textToCopy)
                                .then(() => {
                                    Swal.fire({
                                        title: 'Copied!',
                                        text: 'Credentials copied to clipboard',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.href = '{{ route("school.teachers") }}';
                                    });
                                })
                                .catch(err => {
                                    console.error('Could not copy text: ', err);
                                    // Still redirect
                                    window.location.href = '{{ route("school.teachers") }}';
                                });
                        }
                    });
                } else if (data.errors) {
                    // Show validation errors
                    let errorHtml = '<ul class="text-left">';
                    for (const [field, messages] of Object.entries(data.errors)) {
                        errorHtml += `<li><strong>${field}:</strong> ${messages.join(', ')}</li>`;
                        
                        // Highlight the field with error
                        const fieldElement = document.querySelector(`[name="${field}"]`);
                        if (fieldElement) {
                            fieldElement.classList.add('border-red-500');
                            
                            // Add error message below the field
                            const errorElement = document.createElement('p');
                            errorElement.className = 'text-red-500 text-xs mt-1';
                            errorElement.textContent = messages[0];
                            
                            // Insert after the field or its parent if it's in a form group
                            const formGroup = fieldElement.closest('.form-group') || fieldElement.parentNode;
                            formGroup.appendChild(errorElement);
                            
                            // Scroll to the first error field
                            if (field === Object.keys(data.errors)[0]) {
                                fieldElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        }
                    }
                    errorHtml += '</ul>';
                    
                    Swal.fire({
                        title: 'Validation Error',
                        html: errorHtml,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else if (data.message && data.message.includes('SQLSTATE')) {
                    // Handle SQL errors specifically
                    console.error('SQL Error:', data.message);
                    Swal.fire({
                        title: 'Database Error',
                        html: `
                            <div class="text-left">
                                <p>A database error occurred. Please try again or contact support.</p>
                                <div class="mt-3 p-2 bg-gray-100 text-xs rounded overflow-auto max-h-40">
                                    <code>${data.message}</code>
                                </div>
                            </div>
                        `,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else if (data.html) {
                    // Show HTML error page in a modal
                    Swal.fire({
                        title: 'Server Error',
                        html: '<div class="text-sm">The server returned an error. Please try again or contact support.</div>',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                } else {
                    // Show general error message
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Failed to add teacher',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Close loading indicator
                Swal.close();
                
                submitButton.disabled = false;
                submitButton.textContent = originalButtonText;
                
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while submitting the form. Please try again.',
                    footer: '<a href="#" onclick="document.getElementById(\'errorDetails\').classList.toggle(\'hidden\'); return false;">Show technical details</a>',
                    didOpen: () => {
                        // Add a hidden div with error details
                        const footer = document.querySelector('.swal2-footer');
                        if (footer) {
                            const errorDiv = document.createElement('div');
                            errorDiv.id = 'errorDetails';
                            errorDiv.className = 'hidden mt-3 p-3 bg-gray-100 text-xs overflow-auto max-h-40';
                            errorDiv.textContent = 'Error details: ' + error.message;
                            footer.appendChild(errorDiv);
                        }
                    }
                });
            });
        });
    });

    // Store all classes data globally
    document.addEventListener('DOMContentLoaded', function() {
        const classSelect = document.getElementById('class'); // Assuming your class select has id="class"
        let allClassesData = []; // To store the fetched data globally within this scope

        // Function to fetch classes from the API
        async function fetchClasses() {
            try {
                console.log("Fetching classes...");
                
                // Use jQuery AJAX instead of fetch
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: '{{ route("school.api.active-classes") }}',
                        type: 'GET',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            console.log("Classes API response:", data);
                            
                            if (data.success && data.classes) {
                                allClassesData = data.classes; // Store the fetched data
                                populateClassDropdown(allClassesData); // Populate the class dropdown
                                resolve(true);
                            } else {
                                console.error('API call failed or no classes data:', data.message || 'No classes data found.');
                                resolve(false);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX error fetching classes:', status, error);
                            console.log('XHR Response:', xhr.responseText);
                            reject(new Error('Failed to fetch classes: ' + error));
                        }
                    });
                });
            } catch (error) {
                console.error('Error in fetchClasses:', error);
                return false;
            }
        }

        // Function to populate the Class dropdown with unique class names
        function populateClassDropdown(data) {
            const uniqueClasses = {};
            data.forEach(item => {
                uniqueClasses[item.name] = true;
            });

            classSelect.innerHTML = '<option value="">Select Class</option>'; // Clear existing options
            Object.keys(uniqueClasses).forEach(className => {
                const option = document.createElement('option');
                option.value = className;
                option.textContent = className;
                classSelect.appendChild(option);
            });
        }

        // Initial call to fetch data and populate the class dropdown
        fetchClasses();
    });

    // Existing JavaScript functions (previewImage, removeProfileImage, etc.)
    function previewImage(event, previewId, iconId) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById(previewId);
            const icon = document.getElementById(iconId);
            output.src = reader.result;
            output.classList.remove('hidden');
            icon.classList.add('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function removeProfileImage(previewId, iconId) {
        const output = document.getElementById(previewId);
        const icon = document.getElementById(iconId);
        output.src = "";
        output.classList.add('hidden');
        icon.classList.remove('hidden');
        document.getElementById(previewId.replace('Preview', 'Input')).value = null; // Clear the file input
    }

    // Functionality for updating file names and toggling buttons in the Documents section
    function updateFileName(event, fileNameElementId, uploadButtonId, changeButtonId) {
        const input = event.target;
        const fileNameElement = document.getElementById(fileNameElementId);
        const uploadButton = document.getElementById(uploadButtonId);
        const changeButton = document.getElementById(changeButtonId);

        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            fileNameElement.textContent = fileName;
            uploadButton.classList.add('hidden');
            changeButton.classList.remove('hidden');
        } else {
            fileNameElement.textContent = '';
            uploadButton.classList.remove('hidden');
            changeButton.classList.add('hidden');
        }
    }

    // Transport Information functionality
    function toggleTransportInfo(checkbox) {
        const transportInfoFields = document.getElementById('transportInfoFields');
        if (checkbox.checked) {
            transportInfoFields.style.display = 'block';
        } else {
            transportInfoFields.style.display = 'none';
        }
    }

    // Hostel Information functionality
    function toggleHostelInfo(checkbox) {
        const hostelInfoFields = document.getElementById('hostelInfoFields');
        if (checkbox.checked) {
            hostelInfoFields.style.display = 'block';
        } else {
            hostelInfoFields.style.display = 'none';
        }
    }
    let allHostelRoomsData = []; // To store the fetched hostel rooms data

    document.addEventListener('DOMContentLoaded', function () {
        // Hostel Information Section
        const hostelFields = document.getElementById('hostelFields');
        const hostelSelect = document.getElementById('hostelSelect');
        const roomNumberSelect = document.getElementById('roomNumberSelect');
        const hostelToggleCheckbox = document.querySelector('input[name="hostel_enabled"]');

        // Add a hidden field for hostel_enabled=false when checkbox is unchecked
        const hostelForm = document.getElementById('hostelInfoForm');
        const hostelHiddenField = document.createElement('input');
        hostelHiddenField.type = 'hidden';
        hostelHiddenField.name = 'hostel_enabled';
        hostelHiddenField.value = 'false';
        hostelForm.appendChild(hostelHiddenField);

        window.toggleHostelInfo = function(checkbox) {
            if (checkbox.checked) {
                hostelFields.classList.remove('hidden');
                fetchAllHostelRooms(); // Fetch rooms when enabled
                hostelHiddenField.disabled = true; // Disable the hidden field when checkbox is checked
                checkbox.value = 'true'; // Ensure the checkbox value is 'true'
            } else {
                hostelFields.classList.add('hidden');
                // Optionally clear fields when disabled
                hostelSelect.innerHTML = '<option value="">Select Hostel</option>';
                roomNumberSelect.innerHTML = '<option value="">Select Room</option>';
                hostelHiddenField.disabled = false; // Enable the hidden field when checkbox is unchecked
                checkbox.value = 'false'; // Ensure the checkbox value is 'false'
            }
        };

        hostelSelect.addEventListener('change', function() {
            const selectedHostelId = this.value;
            populateRoomNumbers(selectedHostelId);
        });
    });

    async function fetchAllHostelRooms() {
        try {
            console.log("Fetching hostel rooms...");
            
            // Use jQuery AJAX instead of fetch
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '{{ route("school.api.all-hostel-rooms") }}',
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        console.log("Hostel rooms API response:", data);
                        
                        if (data.success) {
                            allHostelRoomsData = data.hostelRooms;
                            populateHostels(allHostelRoomsData);
                            resolve(true);
                        } else {
                            console.error('Failed to fetch hostel rooms:', data.message);
                            resolve(false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error fetching hostel rooms:', status, error);
                        console.log('XHR Response:', xhr.responseText);
                        reject(new Error('Failed to fetch hostel rooms: ' + error));
                    }
                });
            });
        } catch (error) {
            console.error('Error in fetchAllHostelRooms:', error);
            return false;
        }
    }

    function populateHostels(hostelRooms) {
        const hostelSelect = document.getElementById('hostelSelect');
        hostelSelect.innerHTML = '<option value="">Select Hostel</option>'; // Clear existing options

        const uniqueHostels = new Map(); // Using Map to ensure uniqueness by hostel ID

        hostelRooms.forEach(room => {
            if (room.hostel) {
                if (!uniqueHostels.has(room.hostel.id)) {
                    uniqueHostels.set(room.hostel.id, room.hostel.name);
                }
            }
        });

        uniqueHostels.forEach((name, id) => {
            const option = document.createElement('option');
            option.value = id;
            option.textContent = name;
            hostelSelect.appendChild(option);
        });
    }

    function populateRoomNumbers(hostelId) {
        const roomNumberSelect = document.getElementById('roomNumberSelect');
        roomNumberSelect.innerHTML = '<option value="">Select Room</option>'; // Clear existing options

        if (!hostelId) {
            return; // No hostel selected
        }

        const roomsForSelectedHostel = allHostelRoomsData.filter(room => 
            room.hostel && room.hostel.id == hostelId
        );

        roomsForSelectedHostel.forEach(room => {
            const option = document.createElement('option');
            option.value = room.id; // Use room ID as value
            option.textContent = room.room_number + ' (Beds: ' + room.beds + ', Type: ' + room.room_type.name + ')';
            roomNumberSelect.appendChild(option);
        });
    }
    // Tag Input Functionality
    function addTag(inputId, tagsContainerId, hiddenInputId) {
        const input = document.getElementById(inputId);
        const tagsContainer = document.getElementById(tagsContainerId);
        const hiddenInput = document.getElementById(hiddenInputId);
        const tagText = input.value.trim();

        if (tagText) {
            const tag = document.createElement('span');
            tag.classList.add('inline-flex', 'items-center', 'px-3', 'py-1', 'rounded-full', 'bg-blue-100',
                'text-blue-800', 'text-sm', 'font-medium', 'mr-2', 'mb-2');
            tag.innerHTML = `
                ${tagText}
                <button type="button" class="ml-1 -mr-0.5 h-4 w-4 rounded-full inline-flex items-center justify-center text-blue-600 hover:bg-blue-200 hover:text-blue-800 focus:outline-none focus:bg-blue-200 focus:text-blue-800"
                    onclick="removeTag(this, '${inputId}', '${hiddenInputId}')">
                    <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                        <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" />
                    </svg>
                </button>
            `;
            tagsContainer.insertBefore(tag, input); // Insert tag before the input field
            input.value = ''; // Clear the input field

            updateHiddenInput(tagsContainerId, hiddenInputId);
        }
    }

    function removeTag(button, inputId, hiddenInputId) {
        const tag = button.closest('span');
        const tagsContainer = tag.parentNode;
        tag.remove();
        updateHiddenInput(tagsContainer.id, hiddenInputId);
        // If there are no tags, focus on the input field
        if (tagsContainer.children.length === 1 && tagsContainer.children[0].id === inputId) {
            document.getElementById(inputId).focus();
        }
    }

    function updateHiddenInput(tagsContainerId, hiddenInputId) {
        const tagsContainer = document.getElementById(tagsContainerId);
        const hiddenInput = document.getElementById(hiddenInputId);
        const tags = Array.from(tagsContainer.querySelectorAll('span')).map(tag => tag.firstChild.textContent.trim());
        hiddenInput.value = tags.join(',');
    }

    function addTagOnEnter(event, fieldName, tagsContainerId, hiddenInputId) {
        if (event.key === 'Enter' || event.key === ',') {
            event.preventDefault(); // Prevent form submission or comma from being typed
            addTag(fieldName + 'Input', tagsContainerId, hiddenInputId);
        }
    }

    // Initial calls on page load
    document.addEventListener('DOMContentLoaded', (event) => {
        // Initial state for transport and hostel (hidden by default)
        document.getElementById('transportInfoFields').style.display = 'none';
        document.getElementById('hostelInfoFields').style.display = 'none';

        // Initialize document upload buttons/filenames on page load
        // For Medical Condition
        const medicalConditionInput = document.getElementById('medical_condition_document');
        const medicalConditionFileName = document.getElementById('medicalConditionFileName');
        const medicalConditionUploadButton = document.getElementById('medicalConditionUploadButton');
        const medicalConditionChangeButton = document.getElementById('medicalConditionChangeButton');

        if (medicalConditionInput && medicalConditionFileName) {
            if (medicalConditionInput.files.length > 0 || medicalConditionFileName.textContent.trim() !== '') {
                medicalConditionUploadButton.classList.add('hidden');
                medicalConditionChangeButton.classList.remove('hidden');
            } else {
                medicalConditionUploadButton.classList.remove('hidden');
                medicalConditionChangeButton.classList.add('hidden');
            }
        }

        // For Transfer Certificate
        const transferCertificateInput = document.getElementById('transfer_certificate_document');
        const transferCertificateFileName = document.getElementById('transferCertificateFileName');
        const transferCertificateUploadButton = document.getElementById('transferCertificateUploadButton');
        const transferCertificateChangeButton = document.getElementById('transferCertificateChangeButton');

        if (transferCertificateInput && transferCertificateFileName) {
            if (transferCertificateInput.files.length > 0 || transferCertificateFileName.textContent.trim() !== '') {
                transferCertificateUploadButton.classList.add('hidden');
                transferCertificateChangeButton.classList.remove('hidden');
            } else {
                transferCertificateUploadButton.classList.remove('hidden');
                transferCertificateChangeButton.classList.add('hidden');
            }
        }
    });
    function toggleSpouseInput(select) {
    const container = document.getElementById('spouseNameContainer');
    const label = document.getElementById('spouseNameLabel');
    const input = document.getElementById('spouseName');

    if (select.value === 'W/O') {
        container.classList.remove('hidden');
        label.textContent = 'W/O (Wife Of)';
        input.placeholder = 'Enter husband name';
    } else if (select.value === 'H/O') {
        container.classList.remove('hidden');
        label.textContent = 'H/O (Husband Of)';
        input.placeholder = 'Enter wife name';
    } else {
        container.classList.add('hidden');
        input.value = '';
    }
}

</script>