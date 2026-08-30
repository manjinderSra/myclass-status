@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Peoples / <span class="text-l text-gray-500">Create Students</span>
            </h1>
        </div>
        
        <form id="studentForm" action="{{ route('school.students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
        
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
                        <label for="academicYear" class="block text-sm font-semibold text-gray-700 mb-1">Academic
                            Year</label>
                        <input type="text" id="academicYear" name="academicYear"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out"
                            placeholder="June 2024/25">
                    </div>
                    <div>
                        <label for="admissionDate" class="block text-sm font-semibold text-gray-700 mb-1">Admission
                            Date</label>
                        <input type="date" id="admissionDate" name="admissionDate"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                        <select id="status" name="status"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                            <option value="">Select Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="firstName" class="block text-sm font-semibold text-gray-700 mb-1">First Name</label>
                        <input type="text" id="firstName" name="firstName"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="lastName" class="block text-sm font-semibold text-gray-700 mb-1">Last Name</label>
                        <input type="text" id="lastName" name="lastName"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="class" class="block text-sm font-semibold text-gray-700 mb-1">Class</label>
                        <select id="class" name="class"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->name }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="section" class="block text-sm font-semibold text-gray-700 mb-1">Section</label>
                        <select id="section" name="section"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-semibold text-gray-700 mb-1">Gender</label>
                        <select id="gender" name="gender"
                            class="bg-white mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="dob" class="block text-sm font-semibold text-gray-700 mb-1">Date of
                            Birth</label>
                        <input type="date" id="dob" name="dob"
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
                    {{-- <div>
                        <label for="house" class="block text-sm font-semibold text-gray-700 mb-1">House</label>
                        <select id="house" name="house"
                            class="bg-white mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                            <option value="">Select House</option>
                            <option value="red">Red House</option>
                            <option value="blue">Blue House</option>
                            <option value="green">Green House</option>
                            <option value="yellow">Yellow House</option>
                        </select>
                    </div> --}}
                    <div>
                        <label for="religion" class="block text-sm font-semibold text-gray-700 mb-1">Religion</label>
                        <select id="religion" name="religion"
                            class="bg-white mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                            <option value="">Select Religion</option>
                            <option value="christianity">Christianity</option>
                            <option value="islam">Islam</option>
                            <option value="hinduism">Hinduism</option>
                            <option value="buddhism">Buddhism</option>
                            <option value="sikhism">Sikhism</option>
                            <option value="judaism">Judaism</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
                            <input type="text" id="category" name="category"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">

                        {{-- <select id="category" name="category"
                            class="bg-white mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                            <option value="">Select Category</option>
                            <option value="general">General</option>
                            <option value="obc">OBC</option>
                            <option value="sc">SC</option>
                            <option value="st">ST</option>
                            <option value="ews">EWS</option>
                        </select> --}}
                    </div>
                    <div>
                        <label for="primaryContact" class="block text-sm font-semibold text-gray-700 mb-1">Primary
                            Contact Number</label>
                        <input type="tel" id="primaryContact" name="primaryContact"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email
                            Address</label>
                        <input type="email" id="email" name="email"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="roll_number" class="block text-sm font-semibold text-gray-700 mb-1">Roll
                            Number <span class="text-red">*</span></label>
                        <input type="number" id="roll_number" name="roll_number"
                                required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="admission_number" class="block text-sm font-semibold text-gray-700 mb-1">
                            Admission Number <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="admission_number" name="admission_number" required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm 
                            focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base 
                            transition duration-150 ease-in-out"
                            placeholder="Enter Admission Number">
                    </div>

                    <div>
                        <label for="aadhaarNumber" class="block text-sm font-semibold text-gray-700 mb-1">Aadhaar
                            Number</label>
                        <input type="number" id="aadhaarNumber" name="aadhaarNumber"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    
                    <div>
                        <label for="motherTongue" class="block text-sm font-semibold text-gray-700 mb-1">Mother
                            Tongue</label>
                        <input type="text" id="motherTongue" name="motherTongue"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
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
                </div>
            </div>
        </div>



        <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200">
            <h2
                class="text-xl font-semibold mb-8 text-gray-900 bg-blue-50 rounded-xl rounded-bl-none rounded-br-none border border-blue-50 p-4">
                Parents & Guardian Information
            </h2>
            <div class="p-6">
                <div id="fatherMotherField">
                    {{-- Father Details --}}
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Father Details <span class="text-red-500">*</span></label>
                    
                    <div class="flex items-start gap-4 mb-6">
                        
                        <div
                            class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                            <img id="fatherProfilePreview" src="" alt="Preview"
                                class="hidden w-full h-full object-cover rounded-lg">
                            <svg id="fatherProfileIcon" xmlns="http://www.w3.org/2000/svg"
                                class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <input type="file" id="fatherProfileInput" name="father_profile_image" accept="image/*" class="hidden"
                                onchange="previewImage(event, 'fatherProfilePreview', 'fatherProfileIcon')" />
                            <div class="flex gap-2 mb-2">
                                <button type="button" onclick="document.getElementById('fatherProfileInput').click()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
                                    Upload
                                </button>
                                <button type="button"
                                    onclick="removeProfileImage('fatherProfilePreview', 'fatherProfileIcon')"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                    Remove
                                </button>
                            </div>
                            <p class="text-sm text-gray-500">Upload image size 4MB, Format JPG, PNG, SVG</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 border-b pb-6 border-gray-200">
                        <div>
                            <label for="fatherName" class="block text-sm font-semibold text-gray-700 mb-1">Father
                                Name </label>
                            <input type="text" id="fatherName" name="fatherName" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                        </div>
                        <div>
                            <label for="fatherEmail"
                                class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                            <input type="email" id="fatherEmail" name="fatherEmail"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                        </div>
                        <div>
                            <label for="fatherPhoneNumber"
                                class="block text-sm font-semibold text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                            <input type="tel" id="fatherPhoneNumber" name="fatherPhoneNumber" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                        </div>
                        <div>
                            <label for="fatherOccupation"
                                class="block text-sm font-semibold text-gray-700 mb-1">Father Occupation</label>
                            <input type="text" id="fatherOccupation" name="fatherOccupation"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                        </div>
                    </div>

                    {{-- Mother Details --}}
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mother Details <span class="text-red-500">*</span></label>
                    <div class="flex items-start gap-4 mb-6">
                        
                        <div
                            class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                            <img id="motherProfilePreview" src="" alt="Preview"
                                class="hidden w-full h-full object-cover rounded-lg">
                            <svg id="motherProfileIcon" xmlns="http://www.w3.org/2000/svg"
                                class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <input type="file" id="motherProfileInput" name="mother_profile_image" accept="image/*" class="hidden"
                                onchange="previewImage(event, 'motherProfilePreview', 'motherProfileIcon')" />
                            <div class="flex gap-2 mb-2">
                                <button type="button" onclick="document.getElementById('motherProfileInput').click()"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
                                    Upload
                                </button>
                                <button type="button"
                                    onclick="removeProfileImage('motherProfilePreview', 'motherProfileIcon')"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                    Remove
                                </button>
                            </div>
                            <p class="text-sm text-gray-500">Upload image size 4MB, Format JPG, PNG, SVG</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-2 pb-6 border-gray-200">
                        
                        <div>
                            <label for="motherName" class="block text-sm font-semibold text-gray-700 mb-1">Mother
                                Name </label>
                            <input type="text" id="motherName" name="motherName" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                        </div>
                        <div>
                            <label for="motherEmail"
                                class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                            <input type="email" id="motherEmail" name="motherEmail"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                        </div>
                        <div>
                            <label for="motherPhoneNumber"
                                class="block text-sm font-semibold text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                            <input type="tel" id="motherPhoneNumber" name="motherPhoneNumber" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                        </div>
                        <div>
                            <label for="motherOccupation"
                                class="block text-sm font-semibold text-gray-700 mb-1">Mother Occupation</label>
                            <input type="text" id="motherOccupation" name="motherOccupation"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                        </div>
                    </div>
                </div>
               <div class="mb-6">
    <label class="block text-sm font-semibold text-gray-700 mb-2">Guardian Type</label>
    <div class="flex items-center space-x-4">
        <label class="inline-flex items-center">
            <input type="radio" name="guardianType" value="guardians" class="form-radio text-blue-600"
                onchange="toggleGuardianFields('guardians')">
            <span class="ml-2 text-gray-700">Guardian</span>
        </label>
        <label class="inline-flex items-center">
            <input type="radio" name="guardianType" value="others" class="form-radio text-blue-600"
                onchange="toggleGuardianFields('others')">
            <span class="ml-2 text-gray-700">Others</span>
        </label>
    </div>
</div>

                
{{-- Others Guardian Fields (Updated - Removed required attributes and red asterisks) --}}
<div id="othersFields" class="hidden">
    <div class="flex items-start gap-4 mb-6">
        <div class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
            <img id="othersProfilePreview" src="" alt="Preview" class="hidden w-full h-full object-cover rounded-lg">
            <svg id="othersProfileIcon" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </div>
        <div>
            <input type="file" id="othersProfileInput" name="others_profile_image" accept="image/*" class="hidden"
                onchange="previewImage(event, 'othersProfilePreview', 'othersProfileIcon')" />
            <div class="flex gap-2 mb-2">
                <button type="button" onclick="document.getElementById('othersProfileInput').click()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
                    Upload
                </button>
                <button type="button" onclick="removeProfileImage('othersProfilePreview', 'othersProfileIcon')"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Remove
                </button>
            </div>
            <p class="text-sm text-gray-500">Upload image size 4MB, Format JPG, PNG, SVG</p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-2 pb-6 border-gray-200">
        <div>
            <label for="othersName" class="block text-sm font-semibold text-gray-700 mb-1">Name</label>
            <input type="text" id="othersName" name="othersName"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
        </div>
        <div>
            <label for="othersRelation" class="block text-sm font-semibold text-gray-700 mb-1">Relation</label>
            <input type="text" id="othersRelation" name="othersRelation"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
        </div>
        <div>
            <label for="othersEmail" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
            <input type="email" id="othersEmail" name="othersEmail"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
        </div>
        <div>
            <label for="othersPhoneNumber" class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
            <input type="tel" id="othersPhoneNumber" name="othersPhoneNumber"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
        </div>
        <div>
            <label for="othersOccupation" class="block text-sm font-semibold text-gray-700 mb-1">Occupation</label>
            <input type="text" id="othersOccupation" name="othersOccupation"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
        </div>
        <div>
            <label for="othersAddress" class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
            <input type="text" id="othersAddress" name="othersAddress"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
        </div>
    </div>
</div>

{{-- Guardian Fields (Updated - Removed required attributes and red asterisks) --}}
<div id="guardiansField" class="hidden">
    <div class="flex items-start gap-4 mb-6">
        <div class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
            <img id="guardianProfilePreview" src="" alt="Preview" class="hidden w-full h-full object-cover rounded-lg">
            <svg id="guardianProfileIcon" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </div>
        <div>
            <input type="file" id="guardianProfileInput" name="guardian_profile_image" accept="image/*" class="hidden"
                onchange="previewImage(event, 'guardianProfilePreview', 'guardianProfileIcon')" />
            <div class="flex gap-2 mb-2">
                <button type="button" onclick="document.getElementById('guardianProfileInput').click()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
                    Upload
                </button>
                <button type="button" onclick="removeProfileImage('guardianProfilePreview', 'guardianProfileIcon')"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Remove
                </button>
            </div>
            <p class="text-sm text-gray-500">Upload image size 4MB, Format JPG, PNG, SVG</p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-2 pb-6 border-gray-200">
        <div>
            <label for="guardianName" class="block text-sm font-semibold text-gray-700 mb-1">Guardian Name</label>
            <input type="text" id="guardianName" name="guardianName"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
        </div>
        <div>
            <label for="guardianRelation" class="block text-sm font-semibold text-gray-700 mb-1">Relation</label>
            <input type="text" id="guardianRelation" name="guardianRelation"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
        </div>
        <div>
            <label for="guardianEmail" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
            <input type="email" id="guardianEmail" name="guardianEmail"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
        </div>
        <div>
            <label for="guardianPhoneNumber" class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
            <input type="tel" id="guardianPhoneNumber" name="guardianPhoneNumber"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
        </div>
        <div>
            <label for="guardianOccupation" class="block text-sm font-semibold text-gray-700 mb-1">Occupation</label>
            <input type="text" id="guardianOccupation" name="guardianOccupation"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
        </div>
        <div>
            <label for="guardianAddress" class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
            <input type="text" id="guardianAddress" name="guardianAddress"
                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
        </div>
    </div>
</div>
        

        {{-- Sibling Section --}}
        <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200">
            <h2
                class="text-xl font-semibold mb-8 text-gray-900 bg-blue-50 rounded-xl rounded-bl-none rounded-br-none border border-blue-50 p-4">
                Siblings
            </h2>
            <div class="p-6">
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Is Sibling studying in same
                        school?</label>
                    <div class="flex items-center space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="siblingInSameSchool" value="yes"
                                class="form-radio text-blue-600" onchange="toggleSiblingInfo(true)">
                            <span class="ml-2 text-gray-700">Yes</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="siblingInSameSchool" value="no"
                                class="form-radio text-blue-600" onchange="toggleSiblingInfo(false)" checked>
                            <span class="ml-2 text-gray-700">No</span>
                        </label>
                    </div>
                </div>

                <div id="siblingInfoContainer" class="hidden">
                    <div id="siblingEntries">
                        {{-- Sibling entry template will be added here by JavaScript --}}
                    </div>
                    <button type="button" onclick="addSiblingEntry()"
                        class="mt-6 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Add New
                    </button>
                </div>
            </div>
        </div>

        {{-- Address Section --}}
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
                        <label for="medicalCondition" class="block text-base font-semibold text-gray-700 mb-2">Medical
                            Condition</label>
                        <p class="text-sm text-gray-500 mb-2">Upload image size of 4MB, Accepted Format PDF</p>
                        <input type="file" id="medicalCondition" name="medical_condition_document" class="hidden"
                            onchange="updateFileName(event, 'medicalConditionFileName', 'medicalConditionUploadButton', 'medicalConditionChangeButton')">
                        <div class="flex items-center gap-2">
                            <button type="button" id="medicalConditionUploadButton"
                                onclick="document.getElementById('medicalCondition').click()"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                </svg>
                                Upload Document
                            </button>
                            <button type="button" id="medicalConditionChangeButton"
                                onclick="document.getElementById('medicalCondition').click()"
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
                        <label for="transferCertificate"
                            class="block text-base font-semibold text-gray-700 mb-2">Upload Transfer
                            Certificate</label>
                        <p class="text-sm text-gray-500 mb-2">Upload image size of 4MB, Accepted Format PDF</p>
                        <input type="file" id="transferCertificate" name="transfer_certificate_document" class="hidden"
                            onchange="updateFileName(event, 'transferCertificateFileName', 'transferCertificateUploadButton', 'transferCertificateChangeButton')">
                        <div class="flex items-center gap-2">
                            <button type="button" id="transferCertificateUploadButton"
                                onclick="document.getElementById('transferCertificate').click()"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                </svg>
                                Upload Document
                            </button>
                            <button type="button" id="transferCertificateChangeButton"
                                onclick="document.getElementById('transferCertificate').click()"
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

                {{-- Medical History Section --}}
        <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200">
            <h2 class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-xl rounded-bl-none rounded-br-none border border-blue-50 p-4">
                Medical History
            </h2>
            <div class="p-6">
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Medical Condition of a Student</label>
                    <div class="flex items-center space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="medicalConditionStatus" value="good" class="form-radio text-blue-600" checked>
                            <span class="ml-2 text-gray-700">Good</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="medicalConditionStatus" value="bad" class="form-radio text-blue-600">
                            <span class="ml-2 text-gray-700">Bad</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="medicalConditionStatus" value="others" class="form-radio text-blue-600">
                            <span class="ml-2 text-gray-700">Others</span>
                        </label>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="allergies" class="block text-sm font-semibold text-gray-700 mb-1">Allergies</label>
                    <div id="allergiesTags" class="mt-1 flex flex-wrap gap-2 p-2 border border-gray-300 rounded-lg shadow-sm focus-within:ring-indigo-500 focus-within:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                        <input type="text" id="allergiesInput"
                            class="flex-1 min-w-0 outline-none bg-transparent"
                            placeholder="Add allergy and press Enter"
                            onkeydown="addTagOnEnter(event, 'allergies', 'allergiesTags', 'allergiesHidden')" />
                    </div>
                    <input type="hidden" id="allergiesHidden" name="allergies" value="" />
                </div>

                <div class="mb-6">
                    <label for="medications" class="block text-sm font-semibold text-gray-700 mb-1">Medications</label>
                    <div id="medicationsTags" class="mt-1 flex flex-wrap gap-2 p-2 border border-gray-300 rounded-lg shadow-sm focus-within:ring-indigo-500 focus-within:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                        <input type="text" id="medicationsInput"
                            class="flex-1 min-w-0 outline-none bg-transparent"
                            placeholder="Add medication and press Enter"
                            onkeydown="addTagOnEnter(event, 'medications', 'medicationsTags', 'medicationsHidden')" />
                    </div>
                    <input type="hidden" id="medicationsHidden" name="medications" value="" />
                </div>
            </div>
        </div>

        {{-- Previous School Details Section --}}
        <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200">
            <h3 class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-xl rounded-bl-none rounded-br-none border border-blue-50 p-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.5m0 0h-3.75M8.25 16.5L13.5 9m4.5 4.5L19.5 14a2.25 2.25 0 0 0 2.25-2.25v-2.25m-18 0V4.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 4.5v2.25m-18 0h-.75V7.5M8.25 7.5h.75m0 0H14.25m0 0V4.5m-5.25 3H7.5" />
                </svg>
                Previous School Details
            </h3>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="previousSchoolName" class="block text-sm font-semibold text-gray-700 mb-1">School Name</label>
                        <input type="text" id="previousSchoolName" name="previousSchoolName"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                    <div>
                        <label for="previousSchoolAddress" class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
                        <input type="text" id="previousSchoolAddress" name="previousSchoolAddress"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                    </div>
                </div>
            </div>
        </div>

        {{-- Other Details Section --}}
        <div class="m-2 mb-8 bg-white shadow-l rounded-xl border border-gray-200">
            <h3 class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-xl rounded-bl-none rounded-br-none border border-blue-50 p-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                </svg>
                Other Details
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
        
        <div class="flex justify-end gap-4 mt-8">
            <button type="button" class="px-6 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
            Cancel
        </button>
        <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
            Add Student
        </button>
    </div>

    </div>

</div>
</form>
@include('client.schoolPanel.layout.footer')

<script>
    // Form submission handler
    document.addEventListener('DOMContentLoaded', function() {
        const studentForm = document.getElementById('studentForm');
        
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
        
        studentForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            // Clear previous error messages and highlighting
            clearValidationErrors();
            
            const formData = new FormData(studentForm);
            console.log('Form submission started');
            
            // Show loading state
            const submitButton = studentForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.textContent;
            submitButton.textContent = 'Saving...';
            submitButton.disabled = true;
            
            fetch(studentForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                redirect: 'follow'
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', Object.fromEntries([...response.headers]));
                
                // Check if response is a redirect
                if (response.redirected) {
                    console.log('Redirected to:', response.url);
                    window.location.href = response.url;
                    return { success: false, redirected: true };
                }
                
                if (!response.ok && response.status !== 422) {
                    throw new Error(`Server error: ${response.status}`);
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
                
                // Reset button state regardless of outcome
                submitButton.textContent = originalButtonText;
                submitButton.disabled = false;
                
                if (data.redirected) {
                    // Already handled by the redirect
                    return;
                }
                
                if (data.success) {
                    // Show success message with both admission number and password
                    Swal.fire({
                        title: 'Student Added Successfully!',
                        html: `
                            <div class="text-left">
                                <p><strong>Admission Number:</strong> ${data.data.admission_number}</p>
                                <p><strong>Student ID:</strong> ${data.data.student_id}</p>
                                <p><strong>Password:</strong> ${data.data.password}</p>
                                <p class="mt-3 text-sm text-red-500">Please save this information. The password will not be displayed again.</p>
                            </div>
                        `,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to students list page
                            window.location.href = '{{ route("school.students") }}';
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
                        }
                    }
                    errorHtml += '</ul>';
                    
                    Swal.fire({
                        title: 'Validation Error',
                        html: errorHtml,
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
                        text: data.message || 'Failed to add student',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitButton.disabled = false;
                submitButton.textContent = originalButtonText;
                
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while submitting the form. Please try again with a different email address.',
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
        const classSelect = document.getElementById('class');
        const sectionSelect = document.getElementById('section');
        let allClassesData = []; // To store the fetched data globally within this scope
        
        // Store initial section options to restore them when needed
        const initialSectionOptions = Array.from(sectionSelect.options).map(option => {
            return {
                value: option.value,
                text: option.text
            };
        });

        // Function to fetch classes and sections from the API
        async function fetchClassesAndSections() {
            try {
                const response = await fetch('{{ route("school.api.active-classes") }}');
                const data = await response.json();
                if (data.success && data.classes) {
                    allClassesData = data.classes; // Store the fetched data
                } else {
                    console.error('API call failed or no classes data:', data.message || 'No classes data found.');
                }
            } catch (error) {
                console.error('Error fetching classes and sections:', error);
            }
        }

        // Function to populate the Section dropdown based on the selected class name
        function populateSectionDropdown(selectedClassName) {
            if (!selectedClassName) {
                // If no class is selected, restore the original server-rendered sections
                sectionSelect.innerHTML = ''; // Clear current options
                initialSectionOptions.forEach(option => {
                    const newOption = document.createElement('option');
                    newOption.value = option.value;
                    newOption.textContent = option.text;
                    sectionSelect.appendChild(newOption);
                });
                return;
            }
            
            // If class is selected but we don't have the API data yet, keep original options
            if (!allClassesData.length) {
                return;
            }
            
            // If we have API data, filter sections for the selected class
            sectionSelect.innerHTML = '<option value="">Select Section</option>'; // Clear and add default
            const filteredSections = allClassesData.filter(item => item.name === selectedClassName);
            // Use a Set to ensure unique sections for the selected class if there are duplicates
            const uniqueSectionsForClass = new Set();
            filteredSections.forEach(item => {
                if (item.section && !uniqueSectionsForClass.has(item.section.id)) {
                    const option = document.createElement('option');
                    option.value = item.section.id;
                    option.textContent = item.section.name;
                    sectionSelect.appendChild(option);
                    uniqueSectionsForClass.add(item.section.id);
                }
            });
        }

        // Initial call to fetch data for future use
        fetchClassesAndSections();

        // Event listener for when a class is selected
        classSelect.addEventListener('change', function() {
            // When class changes, populate sections based on the selected class name
            populateSectionDropdown(this.value);
        });
    });

    // Event listener for Class dropdown change
    

    // Existing JavaScript functions (previewImage, removeProfileImage, toggleGuardianFields, etc.)
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

    // Guardian functionality
   function toggleGuardianFields(guardianType) {
    document.getElementById('othersFields').classList.add('hidden');
    document.getElementById('guardiansField').classList.add('hidden');

    if (guardianType === 'others') {
        document.getElementById('othersFields').classList.remove('hidden');
    } else if (guardianType === 'guardians') {
        document.getElementById('guardiansField').classList.remove('hidden');
    }
}

    // Sibling functionality
    let siblingCounter = 0;

    function addSiblingEntry() {
        siblingCounter++;
        const siblingEntries = document.getElementById('siblingEntries');
        const newEntry = document.createElement('div');
        newEntry.classList.add('grid', 'grid-cols-1', 'md:grid-cols-4', 'gap-6', 'mb-4', 'items-end', 'border-b',
            'pb-4', 'border-gray-100');
        newEntry.innerHTML = `
            <div>
                <label for="siblingName_${siblingCounter}" class="block text-sm font-semibold text-gray-700 mb-1">Name</label>
                <select id="siblingName_${siblingCounter}" name="siblings[${siblingCounter}][name]"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                    <option value="">Select</option>
                    {{-- Populate with actual student names from your database --}}
                    <option value="student1">Student One</option>
                    <option value="student2">Student Two</option>
                </select>
            </div>
            <div>
                <label for="siblingRollNo_${siblingCounter}" class="block text-sm font-semibold text-gray-700 mb-1">Roll No</label>
                <select id="siblingRollNo_${siblingCounter}" name="siblings[${siblingCounter}][rollNo]"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                    <option value="">Select</option>
                    {{-- Populate with actual student roll numbers from your database --}}
                    <option value="R001">R001</option>
                    <option value="R002">R002</option>
                </select>
            </div>
            <div>
                <label for="siblingAdmissionNo_${siblingCounter}" class="block text-sm font-semibold text-gray-700 mb-1">Admission No</label>
                <select id="siblingAdmissionNo_${siblingCounter}" name="siblings[${siblingCounter}][admissionNo]"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                    <option value="">Select</option>
                    {{-- Populate with actual student admission numbers from your database --}}
                    <option value="A001">A001</option>
                    <option value="A002">A002</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <div>
                    <label for="siblingClass_${siblingCounter}" class="block text-sm font-semibold text-gray-700 mb-1">Class</label>
                    <select id="siblingClass_${siblingCounter}" name="siblings[${siblingCounter}][class]"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white">
                        <option value="">Select</option>
                        <option value="1">1st Grade</option>
                        <option value="2">2nd Grade</option>
                        <option value="3">3rd Grade</option>
                        <option value="4">4th Grade</option>
                        <option value="5">5th Grade</option>
                    </select>
                </div>
                <button type="button" onclick="removeSiblingEntry(this)"
                    class="p-2 text-red-600 hover:text-red-800 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        `;
        siblingEntries.appendChild(newEntry);
    }

    function removeSiblingEntry(button) {
        button.closest('.grid').remove();
    }

    function toggleSiblingInfo(show) {
        const siblingInfoContainer = document.getElementById('siblingInfoContainer');
        if (show) {
            siblingInfoContainer.classList.remove('hidden');
            // Add an initial sibling entry if none exist
            if (siblingCounter === 0) {
                addSiblingEntry();
            }
        } else {
            siblingInfoContainer.classList.add('hidden');
            // Optionally clear existing sibling entries when "No" is selected
            document.getElementById('siblingEntries').innerHTML = '';
            siblingCounter = 0;
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

    // Functionality for updating file names and toggling buttons in the Documents section
    function updateFileName(event, fileNameId, uploadButtonId, changeButtonId) {
        const fileNameSpan = document.getElementById(fileNameId);
        const uploadButton = document.getElementById(uploadButtonId);
        const changeButton = document.getElementById(changeButtonId);

        if (event.target.files.length > 0) {
            fileNameSpan.textContent = event.target.files[0].name;
            uploadButton.classList.add('hidden');
            changeButton.classList.remove('hidden');
        } else {
            fileNameSpan.textContent = ''; // Clear if no file selected
            uploadButton.classList.remove('hidden');
            changeButton.classList.add('hidden');
        }
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
        // Initialize Guardian fields visibility based on default checked radio
        // Ensure one is checked by default or adjust this logic if not
        const defaultGuardianTypeRadio = document.querySelector('input[name="guardianType"]:checked');
        if (defaultGuardianTypeRadio) {
            toggleGuardianFields(defaultGuardianTypeRadio.value);
        }

        // Initialize Sibling fields visibility
        toggleSiblingInfo(document.querySelector('input[name="siblingInSameSchool"]:checked').value === 'yes');
        // Initial state for transport and hostel (hidden by default)
        document.getElementById('transportInfoFields').style.display = 'none';
        document.getElementById('hostelInfoFields').style.display = 'none';

        // Initialize document upload buttons/filenames on page load
        // For Medical Condition
        const medicalConditionInput = document.getElementById('medicalCondition');
        const medicalConditionFileName = document.getElementById('medicalConditionFileName');
        const medicalConditionUploadButton = document.getElementById('medicalConditionUploadButton');
        const medicalConditionChangeButton = document.getElementById('medicalConditionChangeButton');

        if (medicalConditionInput.files.length > 0 || medicalConditionFileName.textContent.trim() !== '') {
            medicalConditionUploadButton.classList.add('hidden');
            medicalConditionChangeButton.classList.remove('hidden');
        } else {
            medicalConditionUploadButton.classList.remove('hidden');
            medicalConditionChangeButton.classList.add('hidden');
        }

        // For Transfer Certificate
        const transferCertificateInput = document.getElementById('transferCertificate');
        const transferCertificateFileName = document.getElementById('transferCertificateFileName');
        const transferCertificateUploadButton = document.getElementById('transferCertificateUploadButton');
        const transferCertificateChangeButton = document.getElementById('transferCertificateChangeButton');

        if (transferCertificateInput.files.length > 0 || transferCertificateFileName.textContent.trim() !==
            '') {
            transferCertificateUploadButton.classList.add('hidden');
            transferCertificateChangeButton.classList.remove('hidden');
        } else {
            transferCertificateUploadButton.classList.remove('hidden');
            transferCertificateChangeButton.classList.add('hidden');
        }
    });
    
</script> 

<script>
    let assignedRoutesData = []; // To store the fetched routes data

    document.addEventListener('DOMContentLoaded', function () {
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
            // Use a relative URL with the route name
            const response = await fetch('{{ route("school.api.assigned-routes") }}');
            const data = await response.json();
            
            if (data.success) {
                assignedRoutesData = data.routes;
                populatePickupPoints(assignedRoutesData);
            } else {
                console.error('Failed to fetch assigned routes:', data.message);
            }
        } catch (error) {
            console.error('Error fetching assigned routes:', error);
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
</script>
<script>
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
            // Use a relative URL with the route name
            const response = await fetch('{{ route("school.api.all-hostel-rooms") }}');
            const data = await response.json();

            if (data.success) {
                allHostelRoomsData = data.hostelRooms;
                populateHostels(allHostelRoomsData);
            } else {
                console.error('Failed to fetch hostel rooms:', data.message);
            }
        } catch (error) {
            console.error('Error fetching hostel rooms:', error);
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
</script>

</div>
</div>