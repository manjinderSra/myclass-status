@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Peoples / <span class="text-l text-gray-500">Teachers</span>
            </h1>
        </div>
        {{-- Add CSRF meta tag for AJAX calls --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        {{-- Modified Header Section --}}
        <div class="bg-white rounded-lg shadow w-full p-6 transition-all duration-300">
            
            <div class="flex items-center justify-between mb-3">
                    <div class="text-xl font-semibold text-gray-800">
                        Teachers Management
                        <p class="text-sm text-gray-500 mt-1">Manage teachers for your school</p>
                    </div>

                {{-- Right section: View Toggles and Add Teacher Button --}}
                <div class="flex items-center space-x-3">
                    <div class="flex bg-white rounded-lg shadow-md p-1">
                        <button id="listViewBtn" class="p-2 rounded-lg bg-blue-600 text-white transition-all duration-200">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                        </button>
                        <button id="gridViewBtn" class="p-2 rounded-lg text-gray-700  transition-all duration-200">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </button>
                    </div>
                    <a href="{{Route('school.createTeacher')}}" id="openCreateTeacherModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Add Teacher +
                    </a>
                </div>
            </div>
        </div>
        {{-- End Modified Header Section --}}

        {{-- List View (Table) --}}
        <div id="teacherTableView" class="bg-white rounded-xl shadow-lg w-full p-6 mt-6 transition-all duration-300">
            <table id="teachersTable" class="min-w-full divide-y divide-gray-200 mt-6 mb-6">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">Employee ID</th>
                        <th class="px-6 py-3 font-semibold">Name</th>
                        <th class="px-6 py-3 font-semibold">Department</th>
                        <th class="px-6 py-3 font-semibold">Designation</th>
                        <th class="px-6 py-3 font-semibold">Gender</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Date of Join</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @if(isset($teachers) && count($teachers) > 0)
                        @foreach($teachers as $teacher)
                        <tr data-employee_id="{{ $teacher->employee_id }}" 
                            data-name="{{ $teacher->first_name }} {{ $teacher->last_name }}" 
                            data-department="{{ $teacher->subject->name ?? 'N/A' }}" 
                            data-designation="{{ $teacher->qualification ?? 'N/A' }}" 
                            data-gender="{{ ucfirst($teacher->gender) }}" 
                            data-status="{{ ucfirst($teacher->status) }}" 
                            data-date_join="{{ $teacher->date_of_joining ? date('d M Y', strtotime($teacher->date_of_joining)) : 'N/A' }}">
                            <td class="px-6 py-4">{{ $teacher->employee_id }}</td>
                            <td class="px-6 py-4 flex items-center">
                                @if($teacher->profile_image)
                                    <img src="{{ asset('storage/' . $teacher->profile_image) }}" alt="{{ $teacher->first_name }} {{ $teacher->last_name }}" class="w-8 h-8 rounded-full mr-2">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-2 text-blue-500 font-bold">
                                        {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                                    </div>
                                @endif
                                {{ $teacher->first_name }} {{ $teacher->last_name }}
                            </td>
                            <td class="px-6 py-4">{{ $teacher->subject->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $teacher->qualification ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ ucfirst($teacher->gender) }}</td>
                            <td class="px-6 py-4">
                                @if(strtolower($teacher->status) == 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $teacher->date_of_joining ? date('d M Y', strtotime($teacher->date_of_joining)) : 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2"> {{-- Flex container for action icons/buttons --}}
                                    {{-- Dropdown Action Button --}}
                                    <div class="relative inline-block text-left">
                                        <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-2 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 options-menu-btn" aria-haspopup="true" aria-expanded="true">
                                            View Actions
                                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 hidden options-menu" style="z-index: 49" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                            <div class="py-1">
                                                <a href="{{ route('school.teachers.show', $teacher->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 viewTeacherBtn" role="menuitem" data-id="{{ $teacher->id }}">
                                                    <div class="flex items-center">
                                                        <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        View Teacher
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="py-1">
                                                <a href="{{ route('school.teachers.edit', $teacher->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 editTeacherBtn" role="menuitem" data-id="{{ $teacher->id }}">
                                                    <div class="flex items-center">
                                                        <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                        Edit
                                                    </div>
                                                </a>
                                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 loginDetailsBtn" role="menuitem" data-id="{{ $teacher->id }}">
                                                    <div class="flex items-center">
                                                        <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                        </svg>
                                                        Login Details
                                                    </div>
                                                </a>
                                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 disableTeacherBtn" role="menuitem" data-id="{{ $teacher->id }}">
                                                    <div class="flex items-center">
                                                        <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                        </svg>
                                                        @if(strtolower($teacher->status) == 'active')
                                                            Disable
                                                        @else
                                                            Enable
                                                        @endif
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="py-1">
                                                <a href="#" class="block px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900 deleteTeacherBtn" role="menuitem" data-id="{{ $teacher->id }}">
                                                    <div class="flex items-center">
                                                        <svg class="mr-3 h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Delete
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">No teachers found. Click "Add Teacher +" to create a new teacher.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Grid View (Cards) --}}
        <div id="teacherGridView" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 hidden mt-6">
            @if(isset($teachers) && count($teachers) > 0)
                @foreach($teachers as $teacher)
                    <div class="bg-white rounded-xl shadow-lg p-6 relative flex flex-col justify-between"
                         data-id="{{ $teacher->id }}"
                         data-employee_id="{{ $teacher->employee_id }}"
                         data-name="{{ $teacher->first_name }} {{ $teacher->last_name }}"
                         data-department="{{ $teacher->subject ?? 'N/A' }}"
                         data-designation="{{ $teacher->qualification ?? 'Teacher' }}"
                         data-status="{{ ucfirst($teacher->status) }}"
                         data-email="{{ $teacher->email ?? 'N/A' }}"
                         data-phone="{{ $teacher->primary_contact ?? 'Not provided' }}">

                        <div class="flex justify-between items-start mb-4">
                            <span class="text-sm font-medium text-gray-700">{{ $teacher->employee_id }}</span>
                            <div class="flex items-center space-x-2">
                                @if(strtolower($teacher->status) == 'active')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                @endif
                                <div class="relative inline-block text-left">
                                    <button type="button" class="inline-flex justify-center items-center p-1 text-gray-500 hover:text-gray-700 options-menu-btn" aria-haspopup="true" aria-expanded="true">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4zm0 6a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                    <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 hidden options-menu" style="z-index: 49" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                        <div class="py-1">
                                            <a href="{{ route('school.teachers.show', $teacher->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 viewTeacherBtn" role="menuitem" data-id="{{ $teacher->id }}">
                                                <div class="flex items-center">
                                                    <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View Teacher
                                                </div>
                                            </a>
                                        </div>
                                        <div class="py-1">
                                            <a href="{{ route('school.teachers.edit', $teacher->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 editTeacherBtn" role="menuitem" data-id="{{ $teacher->id }}">
                                                <div class="flex items-center">
                                                    <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                    Edit
                                                </div>
                                            </a>
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 loginDetailsBtn" role="menuitem" data-id="{{ $teacher->id }}">
                                                <div class="flex items-center">
                                                    <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                    Login Details
                                                </div>
                                            </a>
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 disableTeacherBtn" role="menuitem" data-id="{{ $teacher->id }}">
                                                <div class="flex items-center">
                                                    <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                    </svg>
                                                    @if(strtolower($teacher->status) == 'active')
                                                        Disable
                                                    @else
                                                        Enable
                                                    @endif
                                                </div>
                                            </a>
                                        </div>
                                        <div class="py-1">
                                            <a href="#" class="block px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900 deleteTeacherBtn" role="menuitem" data-id="{{ $teacher->id }}">
                                                <div class="flex items-center">
                                                    <svg class="mr-3 h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center mb-4">
                            @if($teacher->profile_image)
                                <img src="{{ asset('storage/' . $teacher->profile_image) }}" alt="{{ $teacher->first_name }} {{ $teacher->last_name }}" class="w-12 h-12 rounded-full mr-4">
                            @else
                                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4 text-blue-500 font-bold">
                                    {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $teacher->first_name }} {{ $teacher->last_name }}</h3>
                                <p class="text-sm text-gray-600">{{ $teacher->qualification ?? 'Teacher' }}</p>
                            </div>
                        </div>

                        <div class="text-left w-full text-sm text-gray-700 mb-4">
                            <p class="mb-1"><strong>Email:</strong> {{ $teacher->email ?? 'N/A' }}</p>
                            <p><strong>Phone:</strong> {{ $teacher->primary_contact ?? 'Not provided' }}</p>
                        </div>

                        <div class="flex justify-between items-center mt-auto">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $teacher->subject ?? 'No Department' }}</span>
                            <a href="{{ route('school.teachers.show', $teacher->id) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-300 transition viewTeacherBtn" data-id="{{ $teacher->id }}">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-full text-center py-8 text-gray-500">
                    No teachers found. Click "Add Teacher +" to create a new teacher.
                </div>
            @endif
        </div>

        {{-- Delete Confirmation Modal (similar to Fees Group Delete Modal) --}}
        <div id="deleteTeacherModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
                <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
                <p class="text-gray-700 mb-6">Are you sure you want to delete this teacher?</p>
                <div class="flex justify-end space-x-4">
                    <button type="button" id="closeDeleteTeacherModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                    <button id="confirmDeleteTeacherBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
        <div id="newPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg max-w-md w-full p-6 relative shadow-xl">
                <div class="absolute top-4 right-4">
                    <button type="button" id="closeNewPasswordModal" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="text-center mb-6">
                    <div class="bg-blue-100 text-blue-800 w-16 h-16 rounded-full mx-auto flex items-center justify-center mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Password Updated</h2>
                    <p class="text-gray-600 mt-1">A new password has been generated and saved</p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Employee ID</label>
                        <div class="bg-white px-4 py-3 rounded border border-gray-200 font-medium text-gray-800" id="teacherIdDisplay"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <div class="flex">
                            <div class="bg-white px-4 py-3 rounded-l border border-gray-200 font-mono text-xl tracking-wider text-gray-800 flex-grow" id="generatedPasswordDisplay" style="font-family: monospace;"></div>
                            <input type="hidden" id="actualPassword" value="">
                            <button type="button" id="togglePasswordBtn" class="bg-gray-200 px-3 rounded-r border border-l-0 border-gray-200 text-gray-600 hover:bg-gray-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" id="copyPasswordBtn" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                        </svg>
                        Copy Password
                    </button>
                    <button type="button" id="regeneratePasswordBtn" class="flex-1 bg-gray-100 text-gray-800 py-3 rounded-lg hover:bg-gray-200 transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Generate New
                    </button>
                </div>
                
                <div id="passwordUpdateStatus" class="mt-4 text-center hidden">
                    <span class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full">Password updated successfully</span>
                </div>
            </div>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

{{-- DataTables CDN --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<style>
    /* DataTables specific styles */
    div.dataTables_wrapper { width: 100%; }
    .dataTables_length select, .dataTables_filter input {
        @apply border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500;
    }
    .dataTables_filter input { width: 16rem !important; }
    .dataTables_paginate { @apply flex space-x-1 mt-4; }
    .dataTables_paginate a {
        @apply border border-gray-300 rounded px-3 py-1 text-sm text-gray-700 hover:bg-gray-200 cursor-pointer;
    }
    .dataTables_paginate .current { @apply bg-blue-600 text-white border-blue-600; pointer-events: none; }
    .dataTables_paginate .disabled { @apply text-gray-400 cursor-not-allowed border-gray-200; }
    .dataTables_info { @apply text-gray-600 text-sm mt-2; }

    /* Custom toggle switch styles */
    .toggle-checkbox {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-label {
        display: block;
        width: 40px;
        height: 24px;
        border-radius: 9999px;
        background-color: #d1d5db;
        cursor: pointer;
        position: relative;
        transition: background-color 0.2s ease-in-out;
    }

    .toggle-label:after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        border-radius: 9999px;
        background-color: #ffffff;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        transition: transform 0.2s ease-in-out;
    }

    .toggle-checkbox:checked + .toggle-label {
        background-color: #2563eb;
    }

    .toggle-checkbox:checked + .toggle-label:after {
        transform: translateX(16px);
    }
    
    /* Password display styles */
    #generatedPasswordDisplay {
        font-family: monospace;
        letter-spacing: 0.1em;
    }
    
    /* Ensure text-security works across browsers */
    [style*="text-security: disc"] {
        -webkit-text-security: disc;
        -moz-text-security: disc;
        text-security: disc;
    }
</style>

<script>
    $(document).ready(function () {
        // Set up CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        let teachersDataTable;

        // Initialize DataTables
        if ($('#teachersTable').length) {
            teachersDataTable = $('#teachersTable').DataTable({
                "paging": true,
                "searching": true,
                "info": true,
                "responsive": true,
                "columnDefs": [
                    { "orderable": false, "targets": [7] } // Disable sorting for 'Actions' column
                ]
            });
        }
        
        const teacherTableView = $('#teacherTableView');
        const teacherGridView = $('#teacherGridView');
        const listViewBtn = $('#listViewBtn');
        const gridViewBtn = $('#gridViewBtn');

        // --- View Toggling (List/Grid) ---
        listViewBtn.on('click', function() {
            listViewBtn.removeClass('bg-gray-100 text-gray-700').addClass('bg-blue-600 text-white');
            gridViewBtn.removeClass('bg-blue-600 text-white').addClass('bg-gray-100 text-gray-700');
            teacherTableView.removeClass('hidden');
            teacherGridView.addClass('hidden');
            teachersDataTable.columns.adjust().draw(); // Adjust DataTables columns when showing
        });

        gridViewBtn.on('click', function() {
            gridViewBtn.removeClass('bg-gray-100 text-gray-700').addClass('bg-blue-600 text-white');
            listViewBtn.removeClass('bg-blue-600 text-white').addClass('bg-gray-100 text-gray-700');
            teacherGridView.removeClass('hidden');
            teacherTableView.addClass('hidden');
        });

        // --- Dropdown Menu for Actions (Table and Grid) ---
        // Delegated event for opening the dropdown for table rows and grid cards
        $(document).on('click', '.options-menu-btn', function(e) {
            e.stopPropagation(); // Prevent click from bubbling up and closing other menus

            // Close any other open menus
            $('.options-menu').not($(this).next('.options-menu')).addClass('hidden');

            // Toggle the visibility of the current dropdown menu
            $(this).next('.options-menu').toggleClass('hidden');
        });

        // Close dropdowns when clicking outside
        $(document).click(function() {
            $('.options-menu').addClass('hidden');
        });

        // Prevent dropdown from closing when clicking inside it
        $(document).on('click', '.options-menu', function(e) {
            e.stopPropagation();
        });

        const deleteTeacherModal = $('#deleteTeacherModal');
        const closeDeleteTeacherModal = $('#closeDeleteTeacherModal');
        const confirmDeleteTeacherBtn = $('#confirmDeleteTeacherBtn');
        let teacherToDeleteId = null; // To store the ID of the teacher to be deleted

        // Event listener for delete teacher buttons
        $(document).on('click', '.deleteTeacherBtn', function(e) {
            e.preventDefault();
            teacherToDeleteId = $(this).data('id');
            deleteTeacherModal.removeClass('hidden');
        });

        // Close delete teacher modal
        closeDeleteTeacherModal.on('click', function() {
            deleteTeacherModal.addClass('hidden');
            teacherToDeleteId = null; // Clear the stored ID
        });

        // Confirm delete teacher (add your AJAX call here)
        confirmDeleteTeacherBtn.on('click', function() {
            if (teacherToDeleteId) {
                $.ajax({
                    url: "{{ route('school.teachers.destroy', '') }}/" + teacherToDeleteId,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            alert('Teacher deleted successfully');
                            
                            // Remove the row from the table
                            const row = $(`tr[data-employee_id="${teacherToDeleteId}"]`);
                            if (row.length) {
                                teachersDataTable.row(row).remove().draw();
                            }
                            
                            // Remove the card from grid view
                            const card = $(`div[data-employee_id="${teacherToDeleteId}"]`);
                            if (card.length) {
                                card.remove();
                            }
                            
                            // Hide the modal
                            deleteTeacherModal.addClass('hidden');
                            teacherToDeleteId = null;
                        } else {
                            alert('Failed to delete teacher: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred while deleting the teacher');
                        console.error(xhr.responseText);
                    }
                });
            }
        });

        // Event listener for disable teacher buttons
        $(document).on('click', '.disableTeacherBtn', function(e) {
            e.preventDefault();
            const teacherId = $(this).data('id');
            const row = $(this).closest('tr');
            const card = $(`div[data-id="${teacherId}"]`);
            const button = $(this);
            
            // Get current status - try both tr and card elements
            let currentStatus;
            if (row.length) {
                currentStatus = row.data('status') || '';
            } else if (card.length) {
                currentStatus = card.data('status') || '';
            } else {
                // Fallback to the text content of the button
                currentStatus = button.find('div').text().trim() === 'Disable' ? 'Active' : 'Inactive';
            }
            
            const newStatus = currentStatus.toLowerCase() === 'active' ? 'inactive' : 'active';
            const actionText = newStatus === 'active' ? 'Enable' : 'Disable';
            
            if (confirm(`Are you sure you want to ${actionText.toLowerCase()} this teacher?`)) {
                $.ajax({
                    url: "{{ route('school.teachers.toggle-status', '') }}/" + teacherId,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: newStatus
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the button text immediately
                            const buttonText = newStatus === 'active' ? 'Disable' : 'Enable';
                            button.find('div').text(buttonText);
                            
                            // Update status in row or card data attributes
                            if (row.length) {
                                row.data('status', newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                                const statusCell = row.find('td:nth-child(6)');
                                if (statusCell.length) {
                                    if (newStatus === 'active') {
                                        statusCell.html('<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>');
                                    } else {
                                        statusCell.html('<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>');
                                    }
                                }
                            }
                            
                            if (card.length) {
                                card.data('status', newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                                const statusBadge = card.find('.flex.items-center.space-x-2 span');
                                if (statusBadge.length) {
                                    if (newStatus === 'active') {
                                        statusBadge.removeClass('bg-red-100 text-red-800').addClass('bg-green-100 text-green-800').text('Active');
                                    } else {
                                        statusBadge.removeClass('bg-green-100 text-green-800').addClass('bg-red-100 text-red-800').text('Inactive');
                                    }
                                }
                            }
                            
                            // Show success message
                            alert(`Teacher ${actionText.toLowerCase()}d successfully`);
                        } else {
                            alert(`Failed to ${actionText.toLowerCase()} teacher: ${response.message}`);
                        }
                    },
                    error: function(xhr) {
                        alert(`An error occurred while ${actionText.toLowerCase()}ing the teacher`);
                        console.error(xhr.responseText);
                    }
                });
            }
            
            $('.options-menu').addClass('hidden'); // Close the dropdown menu after clicking
        });
    });
    const newPasswordModal = $('#newPasswordModal');
    const teacherIdDisplay = $('#teacherIdDisplay');
    const generatedPasswordDisplay = $('#generatedPasswordDisplay');
    const copyPasswordBtn = $('#copyPasswordBtn');
    const regeneratePasswordBtn = $('#regeneratePasswordBtn');
    const closeNewPasswordModal = $('#closeNewPasswordModal');
    const togglePasswordBtn = $('#togglePasswordBtn');
    const passwordUpdateStatus = $('#passwordUpdateStatus');
    let currentTeacherId = null;
    let passwordVisible = false;

    // Function to open the new password modal
    function openNewPasswordModal(teacherId, password, employeeId) {
        console.log('Opening password modal for teacher ID:', teacherId, 'with initial password:', password, 'and employee ID:', employeeId);
        currentTeacherId = teacherId;
        teacherIdDisplay.text(employeeId || teacherId);
        
        // Store the actual password in the hidden field
        $('#actualPassword').val(password);
        
        // Display asterisks initially
        updatePasswordDisplay(false);
        
        passwordUpdateStatus.addClass('hidden');
        passwordVisible = false;
        newPasswordModal.removeClass('hidden');
    }
    
    // Function to update password display based on visibility
    function updatePasswordDisplay(isVisible) {
        const password = $('#actualPassword').val();
        console.log('Updating password display, visible:', isVisible, 'password length:', password ? password.length : 0);
        
        if (isVisible) {
            generatedPasswordDisplay.text(password);
        } else {
            if (password === 'Loading...') {
                generatedPasswordDisplay.text(password);
            } else {
                // Replace with asterisks of the same length
                generatedPasswordDisplay.text('*'.repeat(password.length));
            }
        }
    }

    // Toggle password visibility
    togglePasswordBtn.on('click', function() {
        passwordVisible = !passwordVisible;
        updatePasswordDisplay(passwordVisible);
        
        if (passwordVisible) {
            // Show "hide" icon
            togglePasswordBtn.html('<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>');
        } else {
            // Show "show" icon
            togglePasswordBtn.html('<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>');
        }
    });

    // Event listener for "Login Details" button (delegated)
    $(document).on('click', '.loginDetailsBtn', function(e) {
        e.preventDefault();
        const teacherId = $(this).data('id'); // This is the database ID
        
        // Get the employee ID from the row or card
        let employeeId;
        const row = $(this).closest('tr');
        const card = $(this).closest('.bg-white.rounded-xl');
        
        if (row.length) {
            employeeId = row.data('employee_id');
        } else if (card.length) {
            employeeId = card.data('employee_id');
        } else {
            employeeId = "N/A";
        }
        
        console.log('Login Details clicked for teacher ID:', teacherId, 'with employee ID:', employeeId);
        
        // First display the modal with loading state
        openNewPasswordModal(teacherId, "Loading...", employeeId);
        
        // Make AJAX call to get or reset password
        $.ajax({
            url: "/school/teachers/" + teacherId + "/reset-password",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                console.log('Password reset response:', response);
                if (response.success) {
                    // Store the password in the hidden field
                    $('#actualPassword').val(response.password);
                    
                    // Update the display based on current visibility setting
                    updatePasswordDisplay(passwordVisible);
                    
                    // Make sure the password is visible for debugging purposes
                    console.log('Password received:', response.password);
                    
                    passwordUpdateStatus.html('<span class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full">Password updated successfully</span>');
                    passwordUpdateStatus.removeClass('hidden');
                    setTimeout(() => {
                        passwordUpdateStatus.addClass('hidden');
                    }, 3000);
                } else {
                    $('#actualPassword').val('Error: ' + response.message);
                    updatePasswordDisplay(true); // Always show error message
                    passwordUpdateStatus.html('<span class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-full">Failed to update password</span>');
                    passwordUpdateStatus.removeClass('hidden');
                }
            },
            error: function(xhr, status, error) {
                console.error('Password reset error:', xhr.responseText);
                console.error('Status:', status);
                console.error('Error:', error);
                $('#actualPassword').val('Error generating password');
                updatePasswordDisplay(true); // Always show error message
                passwordUpdateStatus.html('<span class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-full">Error connecting to server</span>');
                passwordUpdateStatus.removeClass('hidden');
            }
        });
        
        $('.options-menu').addClass('hidden'); // Close the dropdown menu after clicking
    });

    // Event listener for Copy Password button
    copyPasswordBtn.on('click', function() {
        const passwordToCopy = $('#actualPassword').val();
        navigator.clipboard.writeText(passwordToCopy).then(() => {
            // Optional: Provide visual feedback that the password has been copied
            const originalText = copyPasswordBtn.html();
            copyPasswordBtn.html('<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Copied!');
            setTimeout(() => {
                copyPasswordBtn.html(originalText);
            }, 1500);
        }).catch(err => {
            console.error('Failed to copy password: ', err);
            alert('Failed to copy password. Please copy it manually.');
        });
    });

    // Event listener for Regenerate Password button
    regeneratePasswordBtn.on('click', function() {
        // Show loading state
        $('#actualPassword').val("Generating new password...");
        updatePasswordDisplay(true);
        
        // Get the current employee ID from the display
        const displayedEmployeeId = teacherIdDisplay.text();
        
        // Make AJAX call to reset password
        $.ajax({
            url: "/school/teachers/" + currentTeacherId + "/reset-password",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                _token: "{{ csrf_token() }}",
                force_new: true
            },
            success: function(response) {
                console.log('Regenerate password response:', response);
                if (response.success) {
                    // Store the new password in the hidden field
                    $('#actualPassword').val(response.password);
                    
                    // Update the display based on current visibility setting
                    updatePasswordDisplay(passwordVisible);
                    
                    passwordUpdateStatus.html('<span class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full">Password updated successfully</span>');
                    passwordUpdateStatus.removeClass('hidden');
                    setTimeout(() => {
                        passwordUpdateStatus.addClass('hidden');
                    }, 3000);
                } else {
                    $('#actualPassword').val('Error: ' + response.message);
                    updatePasswordDisplay(true);
                    passwordUpdateStatus.html('<span class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-full">Failed to update password</span>');
                    passwordUpdateStatus.removeClass('hidden');
                }
            },
            error: function(xhr, status, error) {
                console.error('Regenerate password error:', xhr.responseText);
                console.error('Status:', status);
                console.error('Error:', error);
                $('#actualPassword').val('Error generating password');
                updatePasswordDisplay(true);
                passwordUpdateStatus.html('<span class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-full">Error connecting to server</span>');
                passwordUpdateStatus.removeClass('hidden');
            }
        });
    });

    // Event listener for Close button on new password modal
    closeNewPasswordModal.on('click', function() {
        newPasswordModal.addClass('hidden');
        currentTeacherId = null;
    });

    // Event listener for Edit Teacher button
    $(document).on('click', '.editTeacherBtn', function(e) {
        e.preventDefault();
        const teacherId = $(this).data('id');
        window.location.href = "/school/teachers/" + teacherId + "/edit";
    });
</script>