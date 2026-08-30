@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Peoples / <span class="text-l text-gray-500">Edit Student</span>
            </h1>
        </div>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <form id="studentForm"
              action="{{ route('school.students.update', $student->admission_number) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- PERSONAL INFORMATION -->
            <div class="m-2 mb-8 bg-white shadow-lg rounded-xl border border-gray-200">
                <h3 class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-t-xl border border-blue-50 p-4">
                    Personal Information
                </h3>
                <div class="p-6">
                    <!-- Profile Image -->
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                            <img id="profilePreview"
                                 src="{{ $student->profile_image ? asset('storage/' . $student->profile_image) : '' }}"
                                 alt="Preview"
                                 class="{{ $student->profile_image ? '' : 'hidden' }} w-full h-full object-cover rounded-lg">
                            <svg id="profileIcon"
                                 xmlns="http://www.w3.org/2000/svg"
                                 class="{{ $student->profile_image ? 'hidden' : '' }} h-8 w-8 text-gray-400"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <input type="file" id="profileInput" name="profile_image" accept="image/*"
                                   class="hidden"
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
                            <p class="text-sm text-gray-500">Max 4MB, JPG, PNG, SVG</p>
                        </div>
                    </div>

                    <!-- Grid Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Academic Year</label>
                            <input type="text" name="academic_year"
                                   value="{{ $student->academic_year }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Admission Number</label>
                            <input type="text" name="admission_number"
                                   value="{{ $student->admission_number }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 ">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Student ID</label>
                            <input type="text" name="student_id"
                                   value="{{ $student->student_id }}"
                                   readonly
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Academic Number</label>
                            <input type="text" name="academic_number"
                                   value="{{ $student->academic_number }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Admission Date</label>
                            <input type="date" name="admission_date"
                                   value="{{ $student->admission_date ? $student->admission_date->format('Y-m-d') : '' }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                            <select name="status" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                <option value="">Select Status</option>
                                <option value="active" {{ $student->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $student->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">First Name</label>
                            <input type="text" name="first_name"
                                   value="{{ $student->first_name }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Last Name</label>
                            <input type="text" name="last_name"
                                   value="{{ $student->last_name }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Class</label>
                            <select name="class_id" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                <option value="">Select Class</option>
                                @foreach($classes ?? [] as $class)
                                    <option value="{{ $class->id }}" {{ $student->class_id == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Section</label>
                            <select name="section_id" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                <option value="">Select Section</option>
                                @foreach($sections ?? [] as $section)
                                    <option value="{{ $section->id }}" {{ $student->section_id == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Roll Number</label>
                            <input type="number" name="roll_number"
                                   value="{{ $student->roll_number }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Gender</label>
                            <select name="gender" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                <option value="">Select Gender</option>
                                <option value="male" {{ $student->gender == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $student->gender == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ $student->gender == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Date of Birth</label>
                            <input type="date" name="dob"
                                   value="{{ $student->dob ? $student->dob->format('Y-m-d') : '' }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Blood Group</label>
                            <select name="blood_group" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                <option value="">Select Blood Group</option>
                                <option value="A+" {{ $student->blood_group == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ $student->blood_group == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ $student->blood_group == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ $student->blood_group == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ $student->blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ $student->blood_group == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ $student->blood_group == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ $student->blood_group == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">House</label>
                            <select name="house" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                <option value="">Select House</option>
                                <option value="red" {{ $student->house == 'red' ? 'selected' : '' }}>Red House</option>
                                <option value="blue" {{ $student->house == 'blue' ? 'selected' : '' }}>Blue House</option>
                                <option value="green" {{ $student->house == 'green' ? 'selected' : '' }}>Green House</option>
                                <option value="yellow" {{ $student->house == 'yellow' ? 'selected' : '' }}>Yellow House</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Religion</label>
                            <select name="religion" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                <option value="">Select Religion</option>
                                <option value="christianity" {{ $student->religion == 'christianity' ? 'selected' : '' }}>Christianity</option>
                                <option value="islam" {{ $student->religion == 'islam' ? 'selected' : '' }}>Islam</option>
                                <option value="hinduism" {{ $student->religion == 'hinduism' ? 'selected' : '' }}>Hinduism</option>
                                <option value="buddhism" {{ $student->religion == 'buddhism' ? 'selected' : '' }}>Buddhism</option>
                                <option value="sikhism" {{ $student->religion == 'sikhism' ? 'selected' : '' }}>Sikhism</option>
                                <option value="judaism" {{ $student->religion == 'judaism' ? 'selected' : '' }}>Judaism</option>
                                <option value="other" {{ $student->religion == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
                                    <input type="text" id="category" name="category"value="{{ old('category', $student->category) }}"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Primary Contact</label>
                            <input type="tel" name="primary_contact"
                                   value="{{ $student->primary_contact }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                            <input type="email" name="email"
                                   value="{{ $student->email }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mother Tongue</label>
                            <input type="text" name="mother_tongue"
                                   value="{{ $student->mother_tongue }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Aadhaar Number</label>
                            <input type="text" name="aadhaar_number"
                                   value="{{ $student->aadhaar_number }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        {{-- <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                            <input type="tel" name="phone"
                                   value="{{ $student->phone }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div> --}}
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Languages Known</label>
                            <div id="languagesKnownTags"
                                 class="mt-1 flex flex-wrap gap-2 p-2 border border-gray-300 rounded-lg shadow-sm focus-within:ring-indigo-500 focus-within:border-indigo-500 bg-white">
                                <input type="text" id="languagesKnownInput"
                                       class="flex-1 min-w-0 outline-none bg-transparent"
                                       placeholder="Add language and press Enter"
                                       onkeydown="addTagOnEnter(event, 'languagesKnown', 'languagesKnownTags', 'languagesKnownHidden')" />
                            </div>
                            <input type="hidden" id="languagesKnownHidden" name="languages_known"
                                   value="{{ is_array($student->languages_known) ? implode(',', $student->languages_known) : ($student->languages_known ?? '') }}" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- PARENTS & GUARDIAN INFORMATION -->
            <div class="m-2 mb-8 bg-white shadow-lg rounded-xl border border-gray-200">
                <h2 class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-t-xl border border-blue-50 p-4">
                    Parents & Guardian Information
                </h2>
                <div class="p-6">
                    <!-- FATHER DETAILS -->
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Father Details</label>
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                            <img id="fatherProfilePreview"
                                 src="{{ $student->father_profile_image ? asset('storage/' . $student->father_profile_image) : '' }}"
                                 class="{{ $student->father_profile_image ? '' : 'hidden' }} w-full h-full object-cover rounded-lg"
                                 alt="Preview">
                            <svg id="fatherProfileIcon"
                                 xmlns="http://www.w3.org/2000/svg"
                                 class="{{ $student->father_profile_image ? 'hidden' : '' }} h-8 w-8 text-gray-400"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <input type="file" id="fatherProfileInput" name="father_profile_image" accept="image/*"
                                   class="hidden"
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
                            <p class="text-sm text-gray-500">Max 4MB</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6 pb-6 border-b">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Name</label>
                            <input type="text" name="father_name"
                                   value="{{ $student->father_name }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                            <input type="email" name="father_email"
                                   value="{{ $student->father_email }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" name="father_phone_number"
                                   value="{{ $student->father_phone_number }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Occupation</label>
                            <input type="text" name="father_occupation"
                                   value="{{ $student->father_occupation }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- MOTHER DETAILS -->
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mother Details</label>
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                            <img id="motherProfilePreview"
                                 src="{{ $student->mother_profile_image ? asset('storage/' . $student->mother_profile_image) : '' }}"
                                 class="{{ $student->mother_profile_image ? '' : 'hidden' }} w-full h-full object-cover rounded-lg"
                                 alt="Preview">
                            <svg id="motherProfileIcon"
                                 xmlns="http://www.w3.org/2000/svg"
                                 class="{{ $student->mother_profile_image ? 'hidden' : '' }} h-8 w-8 text-gray-400"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <div>
                            <input type="file" id="motherProfileInput" name="mother_profile_image" accept="image/*"
                                   class="hidden"
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
                            <p class="text-sm text-gray-500">Max 4MB</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6 pb-6 border-b">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Name</label>
                            <input type="text" name="mother_name"
                                   value="{{ $student->mother_name }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                            <input type="email" name="mother_email"
                                   value="{{ $student->mother_email }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" name="mother_phone_number"
                                   value="{{ $student->mother_phone_number }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Occupation</label>
                            <input type="text" name="mother_occupation"
                                   value="{{ $student->mother_occupation }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- GUARDIAN TYPE -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Guardian Type</label>
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="guardian_type" value="father"
                                       class="form-radio text-blue-600" {{ $student->guardian_type == 'father' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Father</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="guardian_type" value="mother"
                                       class="form-radio text-blue-600" {{ $student->guardian_type == 'mother' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Mother</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="guardian_type" value="other"
                                       class="form-radio text-blue-600" {{ $student->guardian_type == 'other' ? 'checked' : '' }}
                                       onchange="toggleGuardianFields()">
                                <span class="ml-2 text-gray-700">Other Guardian</span>
                            </label>
                        </div>
                    </div>

                    <!-- OTHER GUARDIAN FIELDS -->
                    <div id="otherGuardianSection" class="hidden">
                        <div class="flex items-start gap-4 mb-6">
                            <div class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                                <img id="guardianProfilePreview"
                                     src="{{ $student->guardian_profile_image ? asset('storage/' . $student->guardian_profile_image) : '' }}"
                                     class="{{ $student->guardian_profile_image ? '' : 'hidden' }} w-full h-full object-cover rounded-lg"
                                     alt="Preview">
                                <svg id="guardianProfileIcon"
                                     xmlns="http://www.w3.org/2000/svg"
                                     class="{{ $student->guardian_profile_image ? 'hidden' : '' }} h-8 w-8 text-gray-400"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div>
                                <input type="file" id="guardianProfileInput" name="guardian_profile_image" accept="image/*"
                                       class="hidden"
                                       onchange="previewImage(event, 'guardianProfilePreview', 'guardianProfileIcon')" />
                                <div class="flex gap-2 mb-2">
                                    <button type="button" onclick="document.getElementById('guardianProfileInput').click()"
                                            class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
                                        Upload
                                    </button>
                                    <button type="button"
                                            onclick="removeProfileImage('guardianProfilePreview', 'guardianProfileIcon')"
                                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6 pb-6 border-b">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Guardian Name</label>
                                <input type="text" name="guardian_name"
                                       value="{{ $student->guardian_name }}"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Relation</label>
                                <input type="text" name="guardian_relation"
                                       value="{{ $student->guardian_relation }}"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                                <input type="email" name="guardian_email"
                                       value="{{ $student->guardian_email }}"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" name="guardian_phone_number"
                                       value="{{ $student->guardian_phone_number }}"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Occupation</label>
                                <input type="text" name="guardian_occupation"
                                       value="{{ $student->guardian_occupation }}"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
                                <input type="text" name="guardian_address"
                                       value="{{ $student->guardian_address }}"
                                       class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ADDRESS INFORMATION -->
            <div class="m-2 mb-8 bg-white shadow-lg rounded-xl border border-gray-200">
                <h3 class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-t-xl border border-blue-50 p-4">
                    Address
                </h3>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Current Address</label>
                            <textarea name="current_address" rows="3"
                                      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ $student->current_address }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Permanent Address</label>
                            <textarea name="permanent_address" rows="3"
                                      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ $student->permanent_address }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
                            <textarea name="address" rows="3"
                                      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ $student->address }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TRANSPORT INFORMATION -->
            <div class="m-2 mb-8 bg-white shadow-lg rounded-xl border border-gray-200">
                <h3 class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-t-xl border border-blue-50 p-4 flex items-center justify-between">
                    Transport Information
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="transport_enabled"
                               value="1" {{ $student->transport_enabled ? 'checked' : '' }}
                               class="sr-only peer" onchange="toggleTransportInfo(this)">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-blue-300 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                        </div>
                    </label>
                </h3>
                <div class="p-6" id="transportInfoForm">
                    <div id="transportFields" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 {{ !$student->transport_enabled ? 'hidden' : '' }}">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Pickup Point</label>
                            <select name="pickup_point_id"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                <option value="">Select Pickup Point</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- HOSTEL INFORMATION -->
            <div class="m-2 mb-8 bg-white shadow-lg rounded-xl border border-gray-200">
                <h3 class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-t-xl border border-blue-50 p-4 flex items-center justify-between">
                    Hostel Information
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="hostel_enabled"
                               value="1" {{ $student->hostel_enabled ? 'checked' : '' }}
                               class="sr-only peer" onchange="toggleHostelInfo(this)">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-blue-300 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                        </div>
                    </label>
                </h3>
                <div class="p-6" id="hostelInfoForm">
                    <div id="hostelFields" class="grid grid-cols-1 md:grid-cols-2 gap-6 {{ !$student->hostel_enabled ? 'hidden' : '' }}">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Hostel</label>
                            <select name="hostel_id"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                <option value="">Select Hostel</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Room Number</label>
                            <select name="room_id"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                <option value="">Select Room</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DOCUMENTS SECTION -->
            <div class="m-2 mb-8 bg-white shadow-lg rounded-xl border border-gray-200">
                <h3 class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-t-xl border border-blue-50 p-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3V11.25m6 4.5H18a2.25 2.25 0 0 0-2.25 2.25V21l3-3 3 3v-2.25a2.25 2.25 0 0 0-2.25-2.25Z" />
                    </svg>
                    Documents
                </h3>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-base font-semibold text-gray-700 mb-2">Medical Condition</label>
                            <p class="text-sm text-gray-500 mb-2">Max 4MB, PDF format</p>
                            <input type="file" id="medicalCondition" name="medical_condition_document"
                                   accept="application/pdf" class="hidden"
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
                                    Upload
                                </button>
                                <span id="medicalConditionFileName" class="text-sm text-gray-600"></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-base font-semibold text-gray-700 mb-2">Transfer Certificate</label>
                            <p class="text-sm text-gray-500 mb-2">Max 4MB, PDF format</p>
                            <input type="file" id="transferCertificate" name="transfer_certificate_document"
                                   accept="application/pdf" class="hidden"
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
                                    Upload
                                </button>
                                <span id="transferCertificateFileName" class="text-sm text-gray-600"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MEDICAL HISTORY -->
            <div class="m-2 mb-8 bg-white shadow-lg rounded-xl border border-gray-200">
                <h2 class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-t-xl border border-blue-50 p-4">
                    Medical History
                </h2>
                <div class="p-6">
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Medical Condition Status</label>
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="medical_condition_status" value="good"
                                       class="form-radio text-blue-600" {{ $student->medical_condition_status == 'good' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Good</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="medical_condition_status" value="bad"
                                       class="form-radio text-blue-600" {{ $student->medical_condition_status == 'bad' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Bad</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="medical_condition_status" value="others"
                                       class="form-radio text-blue-600" {{ $student->medical_condition_status == 'others' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Others</span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Allergies</label>
                        <div id="allergiesTags"
                             class="mt-1 flex flex-wrap gap-2 p-2 border border-gray-300 rounded-lg shadow-sm focus-within:ring-indigo-500 focus-within:border-indigo-500 bg-white">
                            <input type="text" id="allergiesInput"
                                   class="flex-1 min-w-0 outline-none bg-transparent"
                                   placeholder="Add allergy and press Enter"
                                   onkeydown="addTagOnEnter(event, 'allergies', 'allergiesTags', 'allergiesHidden')" />
                        </div>
                        <input type="hidden" id="allergiesHidden" name="allergies"
                               value="{{ is_array($student->allergies) ? implode(',', $student->allergies) : ($student->allergies ?? '') }}" />
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Medications</label>
                        <div id="medicationsTags"
                             class="mt-1 flex flex-wrap gap-2 p-2 border border-gray-300 rounded-lg shadow-sm focus-within:ring-indigo-500 focus-within:border-indigo-500 bg-white">
                            <input type="text" id="medicationsInput"
                                   class="flex-1 min-w-0 outline-none bg-transparent"
                                   placeholder="Add medication and press Enter"
                                   onkeydown="addTagOnEnter(event, 'medications', 'medicationsTags', 'medicationsHidden')" />
                        </div>
                        <input type="hidden" id="medicationsHidden" name="medications"
                               value="{{ is_array($student->medications) ? implode(',', $student->medications) : ($student->medications ?? '') }}" />
                    </div>
                </div>
            </div>

            <!-- SIBLINGS SECTION -->
            <div class="m-2 mb-8 bg-white shadow-lg rounded-xl border border-gray-200">
                <h2 class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-t-xl border border-blue-50 p-4">
                    Siblings
                </h2>
                <div class="p-6">
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Siblings Information</label>
                        <textarea name="siblings" rows="3"
                                  class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ is_array($student->siblings) ? implode(', ', $student->siblings) : ($student->siblings ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- PREVIOUS SCHOOL DETAILS -->
            <div class="m-2 mb-8 bg-white shadow-lg rounded-xl border border-gray-200">
                <h3 class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-t-xl border border-blue-50 p-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8.25 21v-4.5m0 0h-3.75M8.25 16.5L13.5 9m4.5 4.5L19.5 14a2.25 2.25 0 0 0 2.25-2.25v-2.25m-18 0V4.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 4.5v2.25m-18 0h-.75V7.5M8.25 7.5h.75m0 0H14.25m0 0V4.5m-5.25 3H7.5" />
                    </svg>
                    Previous School Details
                </h3>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">School Name</label>
                            <input type="text" name="previous_school_name"
                                   value="{{ $student->previous_school_name }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
                            <input type="text" name="previous_school_address"
                                   value="{{ $student->previous_school_address }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- BANK & OTHER DETAILS -->
            <div class="m-2 mb-8 bg-white shadow-lg rounded-xl border border-gray-200">
                <h3 class="text-xl font-semibold mb-3 text-gray-900 bg-blue-50 rounded-t-xl border border-blue-50 p-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-6 h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                    </svg>
                    Bank & Other Details
                </h3>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Bank Name</label>
                            <input type="text" name="bank_name"
                                   value="{{ $student->bank_name }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Branch</label>
                            <input type="text" name="branch"
                                   value="{{ $student->branch }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">IFSC Number</label>
                            <input type="text" name="ifsc_number"
                                   value="{{ $student->ifsc_number }}"
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Other Information</label>
                        <textarea name="other_information" rows="3"
                                  class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ $student->other_information }}</textarea>
                    </div>
                </div>
            </div>

            <!-- SUBMIT BUTTONS -->
            <div class="fixed bottom-8 right-8">
                <div class="bg-white rounded-lg shadow-lg p-4 flex space-x-4">
                    <button type="button" onclick="window.location.href='{{ route('school.students') }}'"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 rounded-lg text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update Student
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeLanguageTags();
        initializeAllergiesTags();
        initializeMedicationsTags();
        setupFormSubmission();
        toggleGuardianFields();
    });

    function initializeLanguageTags() {
        const value = document.getElementById('languagesKnownHidden').value;
        if (value && value.trim()) {
            const tags = value.split(',').filter(t => t.trim());
            const container = document.getElementById('languagesKnownTags');
            tags.forEach(tag => {
                const elem = createTagElement(tag.trim(), 'languagesKnown', 'languagesKnownTags', 'languagesKnownHidden');
                container.insertBefore(elem, document.getElementById('languagesKnownInput'));
            });
        }
    }

    function initializeAllergiesTags() {
        const value = document.getElementById('allergiesHidden').value;
        if (value && value.trim()) {
            const tags = value.split(',').filter(t => t.trim());
            const container = document.getElementById('allergiesTags');
            tags.forEach(tag => {
                const elem = createTagElement(tag.trim(), 'allergies', 'allergiesTags', 'allergiesHidden');
                container.insertBefore(elem, document.getElementById('allergiesInput'));
            });
        }
    }

    function initializeMedicationsTags() {
        const value = document.getElementById('medicationsHidden').value;
        if (value && value.trim()) {
            const tags = value.split(',').filter(t => t.trim());
            const container = document.getElementById('medicationsTags');
            tags.forEach(tag => {
                const elem = createTagElement(tag.trim(), 'medications', 'medicationsTags', 'medicationsHidden');
                container.insertBefore(elem, document.getElementById('medicationsInput'));
            });
        }
    }

    function createTagElement(text, fieldName, containerId, hiddenFieldId) {
        const tag = document.createElement('div');
        tag.className = 'flex items-center bg-blue-100 text-blue-800 px-2 py-1 rounded';
        tag.innerHTML = `<span>${text}</span><button type="button" class="ml-1 text-blue-500 hover:text-blue-700" onclick="removeTag(this, '${fieldName}', '${containerId}', '${hiddenFieldId}')">&times;</button>`;
        return tag;
    }

    function addTagOnEnter(event, fieldName, containerId, hiddenFieldId) {
        if (event.key === 'Enter') {
            event.preventDefault();
            const input = event.target;
            const text = input.value.trim();
            if (text) {
                const container = document.getElementById(containerId);
                const tag = createTagElement(text, fieldName, containerId, hiddenFieldId);
                container.insertBefore(tag, input);
                input.value = '';
                updateHiddenField(fieldName, containerId, hiddenFieldId);
            }
        }
    }

    function removeTag(button, fieldName, containerId, hiddenFieldId) {
        button.parentElement.remove();
        updateHiddenField(fieldName, containerId, hiddenFieldId);
    }

    function updateHiddenField(fieldName, containerId, hiddenFieldId) {
        const container = document.getElementById(containerId);
        const tags = container.querySelectorAll('div.flex.items-center span');
        const values = Array.from(tags).map(tag => tag.textContent.trim());
        document.getElementById(hiddenFieldId).value = values.join(',');
    }

    function previewImage(event, previewId, iconId) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(previewId);
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                document.getElementById(iconId).classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function removeProfileImage(previewId, iconId) {
        document.getElementById(previewId).src = '';
        document.getElementById(previewId).classList.add('hidden');
        document.getElementById(iconId).classList.remove('hidden');
    }

    function toggleTransportInfo(checkbox) {
        document.getElementById('transportFields').classList.toggle('hidden', !checkbox.checked);
    }

    function toggleHostelInfo(checkbox) {
        document.getElementById('hostelFields').classList.toggle('hidden', !checkbox.checked);
    }

    function toggleGuardianFields() {
        const guardianType = document.querySelector('input[name="guardian_type"]:checked').value;
        document.getElementById('otherGuardianSection').classList.toggle('hidden', guardianType !== 'other');
    }

    function updateFileName(event, fileNameId, uploadBtnId, changeBtnId) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById(fileNameId).textContent = file.name;
            document.getElementById(uploadBtnId).classList.add('hidden');
            document.getElementById(changeBtnId).classList.remove('hidden');
        }
    }

    function setupFormSubmission() {
        document.getElementById('studentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('_method', 'PUT');

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Student updated successfully!');
                    window.location.href = '{{ route('school.students') }}';
                } else {
                    alert('Error: ' + (data.message || 'Failed to update student.'));
                    console.error('Validation errors:', data.errors);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An unexpected error occurred. Please try again.');
            });
        });
    }
</script>

