@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Peoples / <span class="text-l text-gray-500">Students</span>
            </h1>
        </div>
        {{-- Add CSRF meta tag for AJAX calls --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        {{-- Modified Header Section --}}
        <div class="bg-white rounded-lg shadow w-full p-6 transition-all duration-300">
            
            <div class="flex items-center justify-between mb-3">
                    <div class="text-xl font-semibold text-gray-800">
                        Students Management
                        <p class="text-sm text-gray-500 mt-1">Manage students for your school</p>
                    </div>

                {{-- Right section: View Toggles and Add Student Button --}}
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
                    <a href="{{Route('school.createStudent')}}" id="openCreateStudentModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Add Student +
                    </a>
                </div>
            </div>
        </div>
        {{-- End Modified Header Section --}}

        {{-- List View (Table) --}}
        <div id="studentTableView" class="bg-white rounded-xl shadow-lg w-full p-6 mt-6 transition-all duration-300">
            
            {{-- Enhanced Filter Section with Reset Button --}}
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <!-- Filter Form -->
                <form method="GET" id="filterForm" class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <label for="class_filter" class="text-sm font-medium text-gray-700 whitespace-nowrap">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter by Class:
                        </label>
                        <select name="class_id" id="class_filter" 
                            class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm min-w-[200px] transition">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" 
                                    {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Filter Button -->
                    {{-- <button type="submit" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 text-sm font-medium shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Apply Filter
                    </button> --}}
                    
                    <!-- Reset Button (only shows when filter is active) -->
                    @if(request('class_id'))
                    <a href="{{ route('school.students') }}" 
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200 text-sm font-medium shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset
                    </a>
                    @endif
                </form>
                
                <!-- Results Count -->
                <div class="text-sm text-gray-600 flex items-center gap-2">
                    <span class="font-semibold text-gray-800">{{ $students->total() }}</span> 
                    <span>student(s) found</span>
                    @if(request('class_id'))
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full font-medium">
                            Filtered
                        </span>
                    @endif
                </div>
            </div>

            <table id="studentsTable" class="min-w-full divide-y divide-gray-200 mt-6 mb-6">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">Admission No</th>
                        <th class="px-6 py-3 font-semibold">Student Id</th>
                        <th class="px-6 py-3 font-semibold">Name</th>
                        <th class="px-6 py-3 font-semibold">Class</th>
                        <th class="px-6 py-3 font-semibold">Gender</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Date of Join</th>
                        <th class="px-6 py-3 font-semibold">DOB</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @forelse($students as $student)
                    <tr data-admission_no="{{ $student->admission_number }}" data-roll_no="{{ $student->student_id }}" data-name="{{ $student->first_name }} {{ $student->last_name }}" data-class="{{ optional($student->class)->name ?? 'No Class' }}" data-gender="{{ ucfirst($student->gender) }}" data-status="{{ ucfirst($student->status) }}" data-date_join="{{ $student->admission_date ? date('d M Y', strtotime($student->admission_date)) : 'N/A' }}">
                        <td class="px-6 py-4">{{ $student->admission_number }}</td>
                        <td class="px-6 py-4">{{ $student->student_id }}</td>
                        <td class="px-6 py-4 flex items-center">
                            @if($student->profile_image)
                                <img src="{{ asset('storage/' . $student->profile_image) }}" alt="{{ $student->first_name }} {{ $student->last_name }}" class="w-8 h-8 rounded-full mr-2">
                            @else
                                <div class="w-8 h-8 rounded-full bg-blue-200 flex items-center justify-center mr-2 text-blue-800 font-bold">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </div>
                            @endif
                            {{ $student->first_name }} {{ $student->last_name }}
                        </td>
                        <td class="px-6 py-4">{{ optional($student->class)->name ?? 'No Class' }}</td>
                        <td class="px-6 py-4">{{ ucfirst($student->gender) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $student->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($student->status) }}</span>
                        </td>
                        <td class="px-6 py-4">{{ $student->admission_date ? date('d M Y', strtotime($student->admission_date)) : 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $student->dob ? date('d M Y', strtotime($student->dob)) : 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <div class="relative inline-block text-left">
                                    <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-2 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 options-menu-btn" aria-haspopup="true" aria-expanded="true">
                                        View Actions
                                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 hidden options-menu" style="z-index: 49" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                        <div class="py-1">
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 viewStudentBtn" role="menuitem" data-id="{{ $student->admission_number }}">
                                                <div class="flex items-center">
                                                    <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View Student
                                                </div>
                                            </a>
                                        </div>
                                        <div class="py-1">
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 editStudentBtn" role="menuitem" data-id="{{ $student->admission_number }}">
                                                <div class="flex items-center">
                                                    <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                    Edit
                                                </div>
                                            </a>
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 loginDetailsBtn" role="menuitem" data-id="{{ $student->student_id }}">
                                                <div class="flex items-center">
                                                    <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                    Login Details
                                                </div>
                                            </a>
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 disableStudentBtn" role="menuitem" data-id="{{ $student->admission_number }}">
                                                <div class="flex items-center">
                                                    <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                    </svg>
                                                    Disable
                                                </div>
                                            </a>
                                        </div>
                                        <div class="py-1">
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 promoteStudentBtn" role="menuitem" data-id="{{ $student->admission_number }}">
                                                <div class="flex items-center">
                                                    <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                    </svg>
                                                    Promote Student
                                                </div>
                                            </a>
                                            <a href="#" class="block px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900 deleteStudentBtn" role="menuitem" data-id="{{ $student->id}}">
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
                    @empty
                    {{-- <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">No students found</td>
                    </tr> --}}
                    @endforelse
                </tbody>
            </table>
            
            {{-- Pagination --}}
            {{-- <div class="mt-4">
                {{ $students->links() }}
            </div> --}}
        </div>

        {{-- Grid View (Cards) --}}
        <div id="studentGridView" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 hidden mt-6">

        </div>

        {{-- Delete Confirmation Modal --}}
        <div id="deleteStudentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
                <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
                <p class="text-gray-700 mb-6">Are you sure you want to delete this student?</p>
                <div class="flex justify-end space-x-4">
                    <button type="button" id="closeDeleteStudentModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                    <button id="confirmDeleteStudentBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>

        {{-- New Password Modal --}}
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Student ID</label>
                        <div class="bg-white px-4 py-3 rounded border border-gray-200 font-medium text-gray-800" id="studentIdDisplay"></div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <div class="flex">
                            <div class="bg-white px-4 py-3 rounded-l border border-gray-200 font-mono text-xl tracking-wider text-gray-800 flex-grow" id="generatedPasswordDisplay"></div>
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
</style>

<script>
    $(document).ready(function () {
        let studentsDataTable;

        // Initialize DataTables
        if ($('#studentsTable').length) {
            studentsDataTable = $('#studentsTable').DataTable({
                "paging": true,
                "searching": true,
                "info": true,
                "responsive": true,
                "columnDefs": [
                    { "orderable": false, "targets": [8] }
                ]
            });
        }
        
        const studentTableView = $('#studentTableView');
        const studentGridView = $('#studentGridView');
        const listViewBtn = $('#listViewBtn');
        const gridViewBtn = $('#gridViewBtn');

        // Function to populate grid view
        function populateGridView() {
            studentGridView.empty();
            
            $('#studentsTable tbody tr').each(function() {
                if ($(this).find('td').length === 1) return;
                
                const admission_no = $(this).data('admission_no');
                const roll_no = $(this).data('roll_no');
                const name = $(this).data('name');
                const studentClass = $(this).data('class');
                const gender = $(this).data('gender');
                const status = $(this).data('status');
                const date_join = $(this).data('date_join');
                
                let profileImg = $(this).find('td:nth-child(3)').find('img, div').clone().prop('outerHTML');
                
                const cardHtml = `
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden" data-admission_no="${admission_no}">
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    ${profileImg}
                                    <div class="ml-3">
                                        <h3 class="text-lg font-semibold text-gray-800">${name}</h3>
                                        <p class="text-sm text-blue-600">${admission_no}</p>
                                    </div>
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${status.toLowerCase() === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${status}</span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div>
                                    <p class="text-xs text-gray-500">Student ID</p>
                                    <p class="text-sm font-medium text-gray-800">${roll_no}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Class</p>
                                    <p class="text-sm font-medium text-gray-800">${studentClass}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Gender</p>
                                    <p class="text-sm font-medium text-gray-800">${gender}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Joined</p>
                                    <p class="text-sm font-medium text-gray-800">${date_join}</p>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div>
                                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium viewStudentBtn" data-id="${admission_no}">
                                        View Details
                                    </button>
                                </div>
                                <div class="relative inline-block text-left">
                                    <button type="button" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-2 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 options-menu-btn" aria-haspopup="true" aria-expanded="true">
                                        Actions
                                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 hidden options-menu" style="z-index: 49" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                        <div class="py-1">
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 viewStudentBtn" role="menuitem" data-id="${admission_no}">
                                                <div class="flex items-center">
                                                    <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View Student
                                                </div>
                                            </a>
                                        </div>
                                        <div class="py-1">
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 editStudentBtn" role="menuitem" data-id="${admission_no}">
                                                <div class="flex items-center">
                                                    <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                    Edit
                                                </div>
                                            </a>
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 loginDetailsBtn" role="menuitem" data-id="${admission_no}">
                                                <div class="flex items-center">
                                                    <svg class="mr-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                    Login Details
                                                </div>
                                            </a>
                                        </div>
                                        <div class="py-1">
                                            <a href="#" class="block px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900 deleteStudentBtn" role="menuitem" data-id="${admission_no}">
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
                    </div>
                `;
                
                studentGridView.append(cardHtml);
            });
        }

        // View Toggling (List/Grid)
        listViewBtn.on('click', function() {
            listViewBtn.removeClass('bg-gray-100 text-gray-700').addClass('bg-blue-600 text-white');
            gridViewBtn.removeClass('bg-blue-600 text-white').addClass('bg-gray-100 text-gray-700');
            studentTableView.removeClass('hidden');
            studentGridView.addClass('hidden');
            studentsDataTable.columns.adjust().draw();
        });

        gridViewBtn.on('click', function() {
            gridViewBtn.removeClass('bg-gray-100 text-gray-700').addClass('bg-blue-600 text-white');
            listViewBtn.removeClass('bg-blue-600 text-white').addClass('bg-gray-100 text-gray-700');
            populateGridView();
            studentGridView.removeClass('hidden');
            studentTableView.addClass('hidden');
        });

        // Dropdown Menu for Actions
        $(document).on('click', '.options-menu-btn', function(e) {
            e.stopPropagation();
            $('.options-menu').not($(this).next('.options-menu')).addClass('hidden');
            $(this).next('.options-menu').toggleClass('hidden');
        });

        $(document).click(function() {
            $('.options-menu').addClass('hidden');
        });

        $(document).on('click', '.options-menu', function(e) {
            e.stopPropagation();
        });

        const deleteStudentModal = $('#deleteStudentModal');
        const closeDeleteStudentModal = $('#closeDeleteStudentModal');
        const confirmDeleteStudentBtn = $('#confirmDeleteStudentBtn');
        let studentToDeleteId = null;

        $(document).on('click', '.deleteStudentBtn', function(e) {
            e.preventDefault();
            studentToDeleteId = $(this).data('id');
            deleteStudentModal.removeClass('hidden');
        });

        closeDeleteStudentModal.on('click', function() {
            deleteStudentModal.addClass('hidden');
            studentToDeleteId = null;
        });

        // Password modal elements
        const newPasswordModal = $('#newPasswordModal');
        const studentIdDisplay = $('#studentIdDisplay');
        const generatedPasswordDisplay = $('#generatedPasswordDisplay');
        const copyPasswordBtn = $('#copyPasswordBtn');
        const regeneratePasswordBtn = $('#regeneratePasswordBtn');
        const closeNewPasswordModal = $('#closeNewPasswordModal');
        const togglePasswordBtn = $('#togglePasswordBtn');
        const passwordUpdateStatus = $('#passwordUpdateStatus');
        let currentStudentId = null;
        let passwordVisible = false;

        function openNewPasswordModal(studentId, password) {
            currentStudentId = studentId;
            studentIdDisplay.text(studentId);
            generatedPasswordDisplay.text(password);
            passwordUpdateStatus.addClass('hidden');
            passwordVisible = false;
            generatedPasswordDisplay.css('text-security', 'disc');
            generatedPasswordDisplay.css('-webkit-text-security', 'disc');
            newPasswordModal.removeClass('hidden');
        }

        togglePasswordBtn.on('click', function() {
            passwordVisible = !passwordVisible;
            if (passwordVisible) {
                generatedPasswordDisplay.css('text-security', 'none');
                generatedPasswordDisplay.css('-webkit-text-security', 'none');
                togglePasswordBtn.html('<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>');
            } else {
                generatedPasswordDisplay.css('text-security', 'disc');
                generatedPasswordDisplay.css('-webkit-text-security', 'disc');
                togglePasswordBtn.html('<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>');
            }
        });

        $(document).on('click', '.loginDetailsBtn', function(e) {
            e.preventDefault();
            const studentId = $(this).data('id');
            openNewPasswordModal(studentId, "Loading...");
            
            $.ajax({
                url: "/school/students/" + studentId + "/reset-password",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        generatedPasswordDisplay.text(response.password);
                        passwordUpdateStatus.html('<span class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full">Password updated successfully</span>');
                        passwordUpdateStatus.removeClass('hidden');
                        setTimeout(() => {
                            passwordUpdateStatus.addClass('hidden');
                        }, 3000);
                    } else {
                        generatedPasswordDisplay.text('Error: ' + response.message);
                        passwordUpdateStatus.html('<span class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-full">Failed to update password</span>');
                        passwordUpdateStatus.removeClass('hidden');
                    }
                },
                error: function(xhr) {
                    generatedPasswordDisplay.text('Error generating password');
                    passwordUpdateStatus.html('<span class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-full">Error connecting to server</span>');
                    passwordUpdateStatus.removeClass('hidden');
                    console.error(xhr.responseText);
                }
            });
            
            $('.options-menu').addClass('hidden');
        });

        copyPasswordBtn.on('click', function() {
            const passwordToCopy = generatedPasswordDisplay.text();
            navigator.clipboard.writeText(passwordToCopy).then(() => {
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

        regeneratePasswordBtn.on('click', function() {
            generatedPasswordDisplay.text("Generating new password...");
            
            $.ajax({
                url: "/school/students/" + currentStudentId + "/reset-password",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        generatedPasswordDisplay.text(response.password);
                        passwordUpdateStatus.html('<span class="px-3 py-1 text-sm bg-green-100 text-green-800 rounded-full">Password updated successfully</span>');
                        passwordUpdateStatus.removeClass('hidden');
                        setTimeout(() => {
                            passwordUpdateStatus.addClass('hidden');
                        }, 3000);
                    } else {
                        generatedPasswordDisplay.text('Error: ' + response.message);
                        passwordUpdateStatus.html('<span class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-full">Failed to update password</span>');
                        passwordUpdateStatus.removeClass('hidden');
                    }
                },
                error: function(xhr) {
                    generatedPasswordDisplay.text('Error generating password');
                    passwordUpdateStatus.html('<span class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-full">Error connecting to server</span>');
                    passwordUpdateStatus.removeClass('hidden');
                    console.error(xhr.responseText);
                }
            });
        });

        closeNewPasswordModal.on('click', function() {
            newPasswordModal.addClass('hidden');
            currentStudentId = null;
        });

        $(document).on('click', '.editStudentBtn', function(e) {
            e.preventDefault();
            const studentId = $(this).data('id');
            window.location.href = "/school/peoples/students/" + studentId + "/edit";
        });

        $(document).on('click', '.viewStudentBtn', function(e) {
            e.preventDefault();
            const studentId = $(this).data('id');
            window.location.href = "/school/peoples/students/" + studentId + "/show";
        });

        confirmDeleteStudentBtn.on('click', function() {
            if (!studentToDeleteId) return;

            $.ajax({
                url: `/school/peoples/students/${studentToDeleteId}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        const row = $(`tr[data-student_id="${studentToDeleteId}"]`);
                        if (row.length) studentsDataTable.row(row).remove().draw();

                        const card = $(`div[data-student_id="${studentToDeleteId}"]`);
                        if (card.length) card.remove();

                        deleteStudentModal.addClass('hidden');
                        studentToDeleteId = null;
                        window.location.href = '/school/peoples/students';
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error deleting student');
                    console.error(xhr.responseText);
                }
            });
        });

        // Auto-submit filter on dropdown change (optional)
        $('#class_filter').on('change', function() {
            $('#filterForm').submit();
        });
    });
</script>
