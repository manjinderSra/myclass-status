@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

{{-- Add CSRF token meta tag --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="flex h-screen">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Academics / <span class="text-l text-gray-500">Homework</span>
            </h1>
        </div>

        {{-- Header Section for Homework --}}
        <div class="bg-white rounded-lg shadow w-full p-6 transition-all duration-300">
            <div class="flex items-center justify-between mb-3">
                <div class="text-xl font-semibold text-gray-800">
                    Homework Management
                    <p class="text-sm text-gray-500 mt-1">Manage homework assignments for your school</p>
                </div>

                {{-- Right section: Filter Button and Add Homework Button --}}
                {{-- <div class="flex items-center space-x-3">
                    <button id="openFilterHomeworkModal" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V19l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    <button id="openAddHomeworkModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Add Homework +
                    </button>
                </div> --}}
            </div>
        </div>
        {{-- End Header Section --}}

        {{-- Simple Filter Form (for testing) --}}
        <div class="bg-white rounded-lg shadow w-full p-4 mt-4 mb-4">
            <h3 class="text-lg font-semibold mb-3">Quick Filter</h3>
            <form action="{{ route('school.homework.filter') }}" method="GET" class="flex flex-wrap gap-2 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label for="quickFilterClass" class="block text-sm font-medium mb-1">Class</label>
                    <select id="quickFilterClass" name="filterClass" class="w-full border rounded px-3 py-2">
                        <option value="">All Classes</option>
                        @if(isset($classes))
                            @php
                                $uniqueClasses = $classes->unique('name');
                            @endphp
                            @foreach($uniqueClasses as $class)
                                <option value="{{ $class->name }}">{{ $class->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="quickFilterSection" class="block text-sm font-medium mb-1">Section</label>
                    <select id="quickFilterSection" name="filterSection" class="w-full border rounded px-3 py-2">
                        <option value="">All Sections</option>
                        {{-- Will be populated dynamically based on selected class --}}
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="quickFilterDate" class="block text-sm font-medium mb-1">Homework Date</label>
                    <input type="date" id="quickFilterDate" name="filterHomeworkDate" class="w-full border rounded px-3 py-2">
                </div>
                <div class="flex-none">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
                    <a href="{{ route('school.homeWork') }}" class="ml-2 bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">Reset</a>
                </div>
            </form>
        </div>

        {{-- Homework Table --}}
        <div class="bg-white rounded-xl shadow-lg w-full p-6 mt-6 overflow-x-auto">
            <table id="homeworksTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-4 py-2 font-semibold">Class</th>
                        <th class="px-4 py-2 font-semibold">Section</th>
                        <th class="px-4 py-2 font-semibold">Subject</th>
                        <th class="px-4 py-2 font-semibold">Homework Date</th>
                        <th class="px-4 py-2 font-semibold">Submission Date</th>
                        <th class="px-4 py-2 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @if(isset($homeworkData) && count($homeworkData) > 0)
                        @foreach($homeworkData as $homework)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $homework['class_name'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $homework['section'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $homework['subject'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $homework['homework_date'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap">{{ $homework['submission_date'] }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">
                             <a href="{{ route('school.homeWork.sub', $homework['id']) }}"
           class="text-blue-600 hover:text-blue-800 underline">
            View Submissions
        </a>
                        </td>
                    </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                No homework entries found
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Add/Edit Homework Modal (structure similar to the timetable add modal) --}}
        <div id="addEditHomeworkModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg max-w-2xl w-full p-6 relative modal-content">
                <button id="closeAddEditHomeworkModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h2 class="text-xl font-semibold mb-4" id="homeworkModalTitle">Add New Homework</h2>
                <form id="homeworkForm" enctype="multipart/form-data">
                    <input type="hidden" id="homeworkId" name="homework_id" value="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                        <div>
                                    <label for="homeworkClass" class="block text-gray-700 text-sm font-bold mb-2">Class</label>
                                    <select id="homeworkClass" name="class" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                        <option value="">Select Class</option>
                                        {{-- Will be populated dynamically via JavaScript --}}
                                    </select>
                                </div>
                                <div>
                                    <label for="homeworkSection" class="block text-gray-700 text-sm font-bold mb-2">Section</label>
                                    <select id="homeworkSection" name="section" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required disabled>
                                        <option value="">Select Class First</option>
                                        {{-- Will be populated dynamically based on selected class --}}
                                    </select>
                                </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="homeworkSubject" class="block text-gray-700 text-sm font-bold mb-2">Subject</label>
                            <select id="homeworkSubject" name="subject" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required disabled>
                                <option value="">Select Section First</option>
                                {{-- Will be populated dynamically after section is selected --}}
                            </select>
                        </div>
                        <div>
                            <label for="homeworkDate" class="block text-gray-700 text-sm font-bold mb-2">Homework Date</label>
                            <input type="date" id="homeworkDate" name="homework_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="submissionDate" class="block text-gray-700 text-sm font-bold mb-2">Submission Date</label>
                        <input type="date" id="submissionDate" name="submission_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                        <textarea id="description" name="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter homework description" required></textarea>
                    </div>
                    
                    <div class="mb-6">
                        <label for="homeworkImage" class="block text-gray-700 text-sm font-bold mb-2">Image (Optional)</label>
                        <input type="file" id="homeworkImage" name="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" accept="image/*">
                        <p class="text-sm text-gray-500 mt-1">Upload an image related to the homework (JPEG, PNG, GIF, max 2MB)</p>
                        
                        {{-- Image preview for edit mode --}}
                        <div id="currentImageContainer" class="hidden mt-2">
                            <p class="text-sm font-medium mb-1">Current Image:</p>
                            <img id="currentImage" src="" alt="Current Homework Image" class="max-h-40 rounded border border-gray-300">
                            <label class="inline-flex items-center mt-2">
                                <input type="checkbox" id="removeImage" name="remove_image" class="form-checkbox h-4 w-4 text-blue-600">
                                <span class="ml-2 text-sm text-gray-700">Remove current image</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="button" id="cancelAddEditHomeworkBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                        <button type="submit" id="saveHomeworkBtn" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save Homework</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Filter Homework Modal (NEW) --}}
        <div id="filterHomeworkModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg max-w-sm w-full p-6 relative modal-content">
                <button id="closeFilterHomeworkModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h2 class="text-xl font-semibold mb-6">Filter Homework</h2>
                <form id="filterHomeworkForm" action="{{ route('school.homework.filter') }}" method="GET">
                    <div class="mb-4">
                        <label for="filterHomeworkClass" class="block text-gray-700 text-sm font-bold mb-2">Class</label>
                        <select id="filterHomeworkClass" name="filterClass" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select Class</option>
                            @if(isset($classes))
                                @php
                                    $uniqueClasses = $classes->unique('name');
                                @endphp
                                @foreach($uniqueClasses as $class)
                                    <option value="{{ $class->name }}" {{ isset($filters['filterClass']) && $filters['filterClass'] == $class->name ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="filterHomeworkSection" class="block text-gray-700 text-sm font-bold mb-2">Section</label>
                        <select id="filterHomeworkSection" name="filterSection" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select Section</option>
                            @if(isset($classes) && isset($filters['filterClass']))
                                @php
                                    $sections = $classes->where('name', $filters['filterClass'])->pluck('section')->filter();
                                @endphp
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ isset($filters['filterSection']) && $filters['filterSection'] == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="filterHomeworkSubject" class="block text-gray-700 text-sm font-bold mb-2">Subject</label>
                        <select id="filterHomeworkSubject" name="filterSubject" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select Subject</option>
                            @if(isset($subjects))
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ isset($filters['filterSubject']) && $filters['filterSubject'] == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="filterHomeworkDate" class="block text-gray-700 text-sm font-bold mb-2">Homework Date</label>
                        <input type="date" id="filterHomeworkDate" name="filterHomeworkDate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $filters['filterHomeworkDate'] ?? '' }}">
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('school.homeWork') }}" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Reset</a>
                        <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700" onclick="document.getElementById('filterHomeworkForm').submit();">Apply</button>
                    </div>
                </form>
            </div>
        </div>


        {{-- Delete Confirmation Modal (similar to previous ones) --}}
        <div id="deleteHomeworkModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg max-w-md w-full p-6 relative modal-content">
                <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
                <p class="text-gray-700 mb-6">Are you sure you want to delete this homework entry?</p>
                <div class="flex justify-end space-x-4">
                    <button type="button" id="closeDeleteHomeworkModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                    <button id="confirmDeleteHomeworkBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    /* Override DataTables default styles for Tailwind integration */

    /* Container adjustments */
    div.dataTables_wrapper {
        width: 100%;
    }

    /* Length select styling */
    .dataTables_length select {
        @apply border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500;
    }

    /* Search input styling */
    .dataTables_filter input {
        @apply border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500;
        width: 16rem !important;
    }

    /* Pagination buttons container */
    .dataTables_paginate {
        @apply flex space-x-1;
        margin-top: 1rem;
    }

    /* Pagination buttons styling */
    .dataTables_paginate a {
        @apply border border-gray-300 rounded px-3 py-1 text-sm text-gray-700 hover:bg-gray-200 cursor-pointer;
        margin: 0 0.125rem;
        min-width: 2rem;
        text-align: center;
        display: inline-block;
        line-height: 1.5rem;
    }

    /* Active page */
    .dataTables_paginate .current {
        @apply bg-blue-600 text-white border-blue-600;
        pointer-events: none;
    }

    /* Disabled pagination buttons */
    .dataTables_paginate .disabled {
        @apply text-gray-400 cursor-not-allowed border-gray-200;
    }

    /* Info text styling */
    .dataTables_info {
        @apply text-gray-600 text-sm mt-2;
    }
</style>

@include('client.schoolPanel.layout.footer')

{{-- DataTables CSS --}}
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
{{-- DataTables Buttons CSS (for export buttons like Excel, CSV) --}}
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

{{-- Custom Modal Styles --}}
<style>
    /* Fix for modals - simple and effective approach */
    
    
    /* Fix specifically for our modal implementation */
    #addEditHomeworkModal:not(.hidden),
    #filterHomeworkModal:not(.hidden),
    #deleteHomeworkModal:not(.hidden) {
        display: flex !important;
    }
    
    /* Ensure modal content has proper scroll if needed */
    .modal-content {
        max-height: 90vh;
        overflow-y: auto;
    }
    
    /* Ensure buttons have pointer cursor */
    button {
        cursor: pointer;
    }

    /* Ensure modals are on top of everything */
    .z-50 {
        z-index: 9999 !important;
    }
</style>

{{-- jQuery (DataTables dependency) --}}
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- DataTables JS --}}
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
{{-- DataTables Buttons JS
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script> --}}

<script>
    // Global error handler for debugging
    window.onerror = function(message, source, lineno, colno, error) {
        console.error('Global error caught:', {
            message: message,
            source: source,
            lineno: lineno,
            colno: colno,
            error: error
        });
        return false;
    };

    // DOM ready vanilla JS (for modal logic)
    document.addEventListener('DOMContentLoaded', function() {
        // Modal elements
        const addEditHomeworkModal = document.getElementById('addEditHomeworkModal');
        const openAddHomeworkModalBtn = document.getElementById('openAddHomeworkModal');
        const closeAddEditHomeworkModalBtn = document.getElementById('closeAddEditHomeworkModal');
        const cancelAddEditHomeworkBtn = document.getElementById('cancelAddEditHomeworkBtn');
        
        const filterHomeworkModal = document.getElementById('filterHomeworkModal');
        const openFilterHomeworkModalBtn = document.getElementById('openFilterHomeworkModal');
        const closeFilterHomeworkModalBtn = document.getElementById('closeFilterHomeworkModal');
        
        const deleteHomeworkModal = document.getElementById('deleteHomeworkModal');
        const closeDeleteHomeworkModalBtn = document.getElementById('closeDeleteHomeworkModal');
        
        // Vanilla JS Modal handlers
        if (openAddHomeworkModalBtn && addEditHomeworkModal) {
            openAddHomeworkModalBtn.addEventListener('click', function() {
                console.log('Opening add homework modal');
                addEditHomeworkModal.classList.remove('hidden');
                // Reset form
                if (document.getElementById('homeworkForm')) {
                    document.getElementById('homeworkForm').reset();
                }
            });
        }
        
        if (closeAddEditHomeworkModalBtn && addEditHomeworkModal) {
            closeAddEditHomeworkModalBtn.addEventListener('click', function() {
                console.log('Closing add homework modal');
                addEditHomeworkModal.classList.add('hidden');
            });
        }
        
        if (cancelAddEditHomeworkBtn && addEditHomeworkModal) {
            cancelAddEditHomeworkBtn.addEventListener('click', function() {
                console.log('Cancel clicked - closing add homework modal');
                addEditHomeworkModal.classList.add('hidden');
            });
        }
        
        if (openFilterHomeworkModalBtn && filterHomeworkModal) {
            openFilterHomeworkModalBtn.addEventListener('click', function() {
                console.log('Opening filter homework modal');
                filterHomeworkModal.classList.remove('hidden');
            });
        }
        
        if (closeFilterHomeworkModalBtn && filterHomeworkModal) {
            closeFilterHomeworkModalBtn.addEventListener('click', function() {
                console.log('Closing filter homework modal');
                filterHomeworkModal.classList.add('hidden');
            });
        }
        
        if (closeDeleteHomeworkModalBtn && deleteHomeworkModal) {
            closeDeleteHomeworkModalBtn.addEventListener('click', function() {
                console.log('Closing delete homework modal');
                deleteHomeworkModal.classList.add('hidden');
            });
        }
        
        // Add click event listeners to all buttons with class deleteHomeworkBtn
        document.querySelectorAll('.deleteHomeworkBtn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const homeworkId = this.getAttribute('data-id');
                console.log('Delete button clicked for homework ID:', homeworkId);
                // Store homework ID for the delete confirmation
                window.homeworkToDeleteId = homeworkId;
                // Show delete confirmation modal
                if (deleteHomeworkModal) {
                    deleteHomeworkModal.classList.remove('hidden');
                }
            });
        });
        
        // Close modals when clicking outside modal content
        document.querySelectorAll('.fixed').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });
    });

    $(document).ready(function () {
        // Initialize DataTables for Homeworks
        $('#homeworksTable').DataTable({
            "paging": true,      
            "searching": true,   
            "ordering": true,    
            "info": true,        
            "autoWidth": false,  
            "responsive": true, 
           dom:
                "<'flex justify-between items-center mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
                "t" +
                "<'flex justify-between items-center mt-4'<'dataTables_info'i><'dataTables_paginate'p>>",
        });

        // Store all classes and subjects data globally
        let allClassesData = []; // To store the fetched classes data globally within this scope
        let allSubjectsData = []; // To store the fetched subjects data

        // --- Add/Edit Homework Modal Logic ---
        const addEditHomeworkModal = $('#addEditHomeworkModal');
        const openAddHomeworkModal = $('#openAddHomeworkModal');
        const closeAddEditHomeworkModal = $('#closeAddEditHomeworkModal');
        const homeworkModalTitle = $('#homeworkModalTitle');
        const homeworkForm = $('#homeworkForm');
        const saveHomeworkBtn = $('#saveHomeworkBtn');
        const cancelAddEditHomeworkBtn = $('#cancelAddEditHomeworkBtn');
        const homeworkClass = $('#homeworkClass');
        const homeworkSection = $('#homeworkSection');
        const homeworkSubject = $('#homeworkSubject');
        
        // Debug check if elements exist
        console.log('Checking if modal elements exist:');
        console.log('homeworkClass element exists:', homeworkClass.length > 0);
        console.log('homeworkSection element exists:', homeworkSection.length > 0);
        console.log('homeworkSubject element exists:', homeworkSubject.length > 0);

        // Modal management (jQuery version as backup)
        function openModal(modal) {
            modal.removeClass('hidden');
        }

        function closeModal(modal) {
            modal.addClass('hidden');
        }

        openAddHomeworkModal.on('click', function() {
            console.log("jQuery - Opening Add Homework modal");
            
            // Set modal title and button text for adding
            homeworkModalTitle.text('Add New Homework');
            saveHomeworkBtn.text('Save Homework');
            
            // Reset form fields
            homeworkForm[0].reset();
            
            // Check current state of class dropdown before reset
            console.log('Current class dropdown state:');
            console.log('homeworkClass options count:', homeworkClass.find('option').length);
            console.log('homeworkClass first option text:', homeworkClass.find('option:first').text());
            console.log('homeworkClass has class data:', homeworkClass.find('option[value!=""]').length > 0);
            
            // Check the available classes data
            console.log('allClassesData available:', typeof allClassesData !== 'undefined' && Array.isArray(allClassesData));
            if (typeof allClassesData !== 'undefined' && Array.isArray(allClassesData)) {
                console.log('allClassesData length:', allClassesData.length);
            }
            
            // If class dropdown is empty but we have data, populate it now
            if (homeworkClass.find('option').length <= 1 && typeof allClassesData !== 'undefined' && Array.isArray(allClassesData) && allClassesData.length > 0) {
                console.log('Re-populating class dropdown because it was empty');
                populateAddHomeworkClassDropdown(allClassesData);
            }
            
            // Reset and disable section dropdown
            homeworkSection.html('<option value="">Select Class First</option>').prop('disabled', true);
            
            // Reset and disable subject dropdown
            homeworkSubject.html('<option value="">Select Section First</option>').prop('disabled', true);
            
            // Set today's date as default for homework date
            const today = new Date().toISOString().split('T')[0];
            $('#homeworkDate').val(today);
            
            // Set default submission date (7 days from today)
            const submissionDate = new Date();
            submissionDate.setDate(submissionDate.getDate() + 7);
            $('#submissionDate').val(submissionDate.toISOString().split('T')[0]);
            
            // Make sure the modal is displayed
            openModal(addEditHomeworkModal);
            
            // Check dropdown state after opening modal
            setTimeout(function() {
                console.log('Class dropdown state after modal opened:');
                console.log('homeworkClass options count:', homeworkClass.find('option').length);
                console.log('homeworkClass disabled:', homeworkClass.prop('disabled'));
            }, 500);
        });

        closeAddEditHomeworkModal.on('click', function() {
            closeModal(addEditHomeworkModal);
        });

        cancelAddEditHomeworkBtn.on('click', function() {
            closeModal(addEditHomeworkModal);
        });

        homeworkForm.on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            console.log('Homework Data:', Array.from(formData.entries()));
            
                            $.ajax({
                    url: '/school/homework/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                success: function(response) {
                    if (response.success) {
                        alert('Homework saved successfully!');
                        closeModal(addEditHomeworkModal);
                        // Reload the table or add the new entry
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    alert('Error saving homework. Please try again.');
                }
            });
        });

        // --- Edit Homework Logic ---
        $(document).on('click', '.editHomeworkBtn', function(e) {
            e.preventDefault();
            const homeworkId = $(this).data('id');
            homeworkModalTitle.text('Edit Homework');
            saveHomeworkBtn.text('Update Homework');
            
            // Fetch homework data by ID via AJAX
            $.ajax({
                url: '/school/homework/get/' + homeworkId,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success && response.homework) {
                        const homework = response.homework;
                        
                        // Populate form fields
                        $('#homeworkId').val(homework.id);
                        $('#homeworkClass').val(homework.class_name);
                        populateSectionDropdown(homework.class_name, homeworkSection, allClassesData);
                        
                        // Need to wait for section dropdown to populate before setting value
                        setTimeout(function() {
                            $('#homeworkSection').val(homework.section_id);
                            fetchTimetableSubjects(homework.class_name, homework.section_id)
                                .then(subjects => {
                                    populateSubjectDropdownFromTimetable(subjects, homeworkSubject);
                                    // Need to wait for subject dropdown to populate
                                    setTimeout(function() {
                                        $('#homeworkSubject').val(homework.subject_id);
                                    }, 100);
                                });
                        }, 100);
                        
                        $('#homeworkDate').val(homework.homework_date);
                        $('#submissionDate').val(homework.submission_date);
                        $('#description').val(homework.description);
                        
                        // Handle image preview if exists
                        if (homework.image) {
                            $('#currentImageContainer').removeClass('hidden');
                            $('#currentImage').attr('src', homework.image_url);
                        } else {
                            $('#currentImageContainer').addClass('hidden');
                        }
                        
                        openModal(addEditHomeworkModal);
                    } else {
                        alert('Error fetching homework data: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    alert('Error fetching homework data. Please try again.');
                }
            });
        });

        // --- Delete Homework Modal Logic ---
        const deleteHomeworkModal = $('#deleteHomeworkModal');
        const closeDeleteHomeworkModal = $('#closeDeleteHomeworkModal');
        const confirmDeleteHomeworkBtn = $('#confirmDeleteHomeworkBtn');
        let homeworkToDeleteId = null;

        // Delete button click handler - both vanilla JS and jQuery implementation
        // Vanilla JS implementation
        document.querySelectorAll('.deleteHomeworkBtn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                homeworkToDeleteId = this.getAttribute('data-id');
                document.getElementById('deleteHomeworkModal').classList.remove('hidden');
            });
        });
        
        // jQuery implementation as backup
        $(document).on('click', '.deleteHomeworkBtn', function(e) {
            e.preventDefault();
            homeworkToDeleteId = $(this).data('id'); // Get ID from data attribute
            openModal(deleteHomeworkModal);
        });

        closeDeleteHomeworkModal.on('click', function() {
            closeModal(deleteHomeworkModal);
            homeworkToDeleteId = null;
        });

        confirmDeleteHomeworkBtn.on('click', function() {
            if (homeworkToDeleteId) {
                $.ajax({
                    url: '/school/homework/delete/' + homeworkToDeleteId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE'
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Homework deleted successfully!');
                            // Reload the table or remove the row
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', status, error);
                        alert('Error deleting homework. Please try again.');
                    }
                });
                
                closeModal(deleteHomeworkModal);
                homeworkToDeleteId = null;
            }
        });

        // --- Filter Homework Modal Logic ---
        const filterHomeworkModal = $('#filterHomeworkModal');
        const openFilterHomeworkModal = $('#openFilterHomeworkModal');
        const closeFilterHomeworkModal = $('#closeFilterHomeworkModal');
        const filterHomeworkForm = $('#filterHomeworkForm');
        const filterHomeworkClass = $('#filterHomeworkClass');
        const filterHomeworkSection = $('#filterHomeworkSection');
        const resetFilterHomeworkBtn = $('#resetFilterHomeworkBtn');

        openFilterHomeworkModal.on('click', function() {
            openModal(filterHomeworkModal);
        });

        closeFilterHomeworkModal.on('click', function() {
            closeModal(filterHomeworkModal);
        });

        resetFilterHomeworkBtn.on('click', function() {
            filterHomeworkForm[0].reset();
        });

                    filterHomeworkForm.on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            
            // Redirect with query parameters
            window.location.href = '/school/homeWork?' + formData;
        });
        
        // Close modals when clicking outside of modal content
        $(document).on('click', '.fixed', function(e) {
            if($(e.target).hasClass('fixed')) {
                closeModal($(this));
            }
        });

        // Function to fetch classes and sections from the API
        async function fetchClassesAndSections() {
            try {
                console.log("Fetching classes and sections...");
                
                // Show loading indicator in quick filter
                $('#quickFilterClass').prop('disabled', true).html('<option value="">Loading classes...</option>');
                $('#quickFilterSection').prop('disabled', true).html('<option value="">Loading sections...</option>');
                
                // Show loading indicator in filter modal
                $('#filterHomeworkClass').prop('disabled', true).html('<option value="">Loading classes...</option>');
                $('#filterHomeworkSection').prop('disabled', true).html('<option value="">Loading sections...</option>');
                
                // Show loading indicator in Add Homework modal
                $('#homeworkClass').prop('disabled', true).html('<option value="">Loading classes...</option>');
                $('#homeworkSection').prop('disabled', true).html('<option value="">Loading sections...</option>');
                
                // Use jQuery AJAX instead of fetch for better error handling and browser compatibility
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
                                // Store the fetched data
                                allClassesData = data.classes; // Store the fetched data
                                console.log("Classes data stored:", allClassesData);
                                populateClassDropdown(allClassesData); // Populate the filter class dropdown
                                populateAddHomeworkClassDropdown(allClassesData); // Populate the add homework class dropdown
                                
                                // Process URL parameters for filtering
                                const urlParams = new URLSearchParams(window.location.search);
                                
                                // Handle class parameter
                                const classParam = urlParams.get('filterClass');
                                if (classParam) {
                                    console.log(`Found class parameter in URL: ${classParam}`);
                                    
                                    // Set class values in both filter forms
                                    $('#quickFilterClass').val(classParam);
                                    $('#filterHomeworkClass').val(classParam);
                                    
                                    // Populate section dropdowns based on selected class
                                    populateSectionDropdown(classParam, $('#filterHomeworkSection'), allClassesData);
                                    populateSectionDropdown(classParam, $('#quickFilterSection'), allClassesData);
                                    
                                    // Handle section parameter if present
                                    const sectionParam = urlParams.get('filterSection');
                                    if (sectionParam) {
                                        console.log(`Found section parameter in URL: ${sectionParam}`);
                                        $('#filterHomeworkSection').val(sectionParam);
                                        $('#quickFilterSection').val(sectionParam);
                                    }
                                    
                                    // This message helps users understand the current filter
                                    const sectionName = $('#quickFilterSection option:selected').text();
                                    if (sectionParam && sectionName && sectionName !== 'Select Section') {
                                        console.log(`Viewing homework for ${classParam} - ${sectionName}`);
                                    }
                                }
                                
                                // Handle homework date parameter if present
                                const dateParam = urlParams.get('filterHomeworkDate');
                                if (dateParam) {
                                    console.log(`Found date parameter in URL: ${dateParam}`);
                                    $('#quickFilterDate').val(dateParam);
                                    $('#filterHomeworkDate').val(dateParam);
                                }
                                
                                resolve(true);
                            } else {
                                // Handle API error
                                console.error('API call failed or no classes data:', data.message || 'No classes data found.');
                                
                                // Show error message in dropdowns
                                $('#quickFilterClass, #filterHomeworkClass, #homeworkClass').html('<option value="">Error loading classes</option>');
                                $('#quickFilterSection, #filterHomeworkSection, #homeworkSection').html('<option value="">Error loading sections</option>');
                                
                                // Re-enable dropdowns
                                $('#quickFilterClass, #quickFilterSection, #filterHomeworkClass, #filterHomeworkSection, #homeworkClass, #homeworkSection').prop('disabled', false);
                                
                                resolve(false);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX error fetching classes:', status, error);
                            
                            // Show error message in dropdowns
                            $('#quickFilterClass, #filterHomeworkClass, #homeworkClass').html('<option value="">Error loading classes</option>');
                            $('#quickFilterSection, #filterHomeworkSection, #homeworkSection').html('<option value="">Error loading sections</option>');
                            
                            // Re-enable dropdowns
                            $('#quickFilterClass, #quickFilterSection, #filterHomeworkClass, #filterHomeworkSection, #homeworkClass, #homeworkSection').prop('disabled', false);
                            
                            reject(new Error('Failed to fetch classes: ' + error));
                        }
                    });
                });
            } catch (error) {
                console.error('Error in fetchClassesAndSections:', error);
                
                // Show error message in dropdowns
                $('#quickFilterClass, #filterHomeworkClass, #homeworkClass').html('<option value="">Error loading classes</option>');
                $('#quickFilterSection, #filterHomeworkSection, #homeworkSection').html('<option value="">Error loading sections</option>');
                
                // Re-enable dropdowns
                $('#quickFilterClass, #quickFilterSection, #filterHomeworkClass, #filterHomeworkSection, #homeworkClass, #homeworkSection').prop('disabled', false);
                
                return false;
            }
        }

        // Function to fetch subjects from the API
        async function fetchSubjects() {
            try {
                console.log("Fetching subjects...");
                
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

        // Function to fetch timetable subjects based on class and section
        async function fetchTimetableSubjects(className, sectionId) {
            try {
                console.log(`Fetching timetable subjects for class: ${className}, section: ${sectionId}`);
                
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: '{{ route("school.api.timetable-subjects") }}',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            class: className,
                            section: sectionId
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            console.log('Timetable subjects API Response:', data);
                            
                            if (data.success) {
                                const subjects = data.subjects;
                                console.log('Fetched timetable subjects:', subjects);
                                resolve(subjects);
                            } else {
                                console.error('Failed to fetch timetable subjects:', data.message);
                                // Return empty array to show "No subjects available" message
                                resolve([]);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX error fetching timetable subjects:', status, error);
                            console.log('XHR Response:', xhr.responseText);
                            // Return empty array on error
                            resolve([]);
                        }
                    });
                });
            } catch (error) {
                console.error('Error in fetchTimetableSubjects:', error);
                // Return empty array to show "No subjects available" message
                return [];
            }
        }

        // Function to populate the Class dropdown with unique class names
        function populateClassDropdown(data) {
            console.log("Populating class dropdown with data:", data);
            
            if (!data || !Array.isArray(data) || data.length === 0) {
                console.error("No valid class data available for dropdown");
                
                // Set error messages in dropdowns
                filterHomeworkClass.html('<option value="">No classes available</option>');
                $('#quickFilterClass').html('<option value="">No classes available</option>');
                
                return;
            }
            
            const uniqueClasses = {};
            data.forEach(item => {
                if (item && item.name) {
                    uniqueClasses[item.name] = true;
                }
            });
            
            const classCount = Object.keys(uniqueClasses).length;
            console.log(`Found ${classCount} unique classes`);
            
            // Populate filter modal class dropdown
            filterHomeworkClass.html('<option value="">Select Class</option>'); // Clear existing options
            Object.keys(uniqueClasses).forEach(className => {
                const option = document.createElement('option');
                option.value = className;
                option.textContent = className;
                filterHomeworkClass.append(option);
            });
            
            // Populate quick filter class dropdown
            const quickFilterClass = $('#quickFilterClass');
            quickFilterClass.html('<option value="">All Classes</option>'); // Clear existing options
            Object.keys(uniqueClasses).forEach(className => {
                const option = document.createElement('option');
                option.value = className;
                option.textContent = className;
                quickFilterClass.append(option);
            });
            
            // Enable class dropdowns
            filterHomeworkClass.prop('disabled', false);
            quickFilterClass.prop('disabled', false);
            
            console.log("Class dropdowns populated successfully");
        }

        // Function to populate the Section dropdown based on the selected class name
        function populateSectionDropdown(selectedClassName, sectionDropdown, data) {
            sectionDropdown.html('<option value="">Select Section</option>'); // Clear and add default
            sectionDropdown.prop('disabled', true);
            
            if (!selectedClassName) {
                console.log("No class selected, skipping section population");
                return;
            }
            
            console.log("Populating section dropdown for class:", selectedClassName);
            
            if (!data || !Array.isArray(data) || data.length === 0) {
                console.error("No valid class data available for section dropdown");
                sectionDropdown.html('<option value="">No data available</option>');
                return;
            }
            
            // Filter classes by the selected class name
            const filteredSections = data.filter(item => item.name === selectedClassName);
            console.log(`Found ${filteredSections.length} classes with name "${selectedClassName}"`);
            
            if (filteredSections.length === 0) {
                console.log("No sections found for class:", selectedClassName);
                sectionDropdown.html('<option value="">No sections available</option>');
                return;
            }
            
            // Use a Set to ensure unique sections for the selected class if there are duplicates
            const uniqueSectionsForClass = new Set();
            let addedSections = 0;
            
            filteredSections.forEach(item => {
                if (item.section && item.section.id && !uniqueSectionsForClass.has(item.section.id)) {
                    console.log(`Adding section ${item.section.name} (ID: ${item.section.id})`);
                    const option = document.createElement('option');
                    option.value = item.section.id;
                    option.textContent = item.section.name;
                    sectionDropdown.append(option);
                    uniqueSectionsForClass.add(item.section.id);
                    addedSections++;
                }
            });
            
            console.log(`Added ${addedSections} unique sections to dropdown`);
            
            // Enable the dropdown if sections were found
            sectionDropdown.prop('disabled', addedSections === 0);
            
            // Trigger change event to notify other components
            sectionDropdown.trigger('change');
        }

        // Function to populate subject dropdown based on timetable subjects
        function populateSubjectDropdownFromTimetable(subjects, subjectDropdown) {
            console.log(`Populating subject dropdown with ${subjects ? subjects.length : 0} subjects`);
            
            // Clear existing options
            subjectDropdown.html('<option value="">Select Subject</option>');
            
            // Handle the case where no subjects are available
            if (!subjects || subjects.length === 0) {
                console.log('No subjects available for this class/section');
                
                // Add a disabled option indicating no subjects found
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "No subjects available for this class/section";
                option.disabled = true;
                subjectDropdown.append(option);
                
                // Disable the dropdown
                subjectDropdown.prop('disabled', true);
                return;
            }
            
            // Add each subject to the dropdown
            let addedCount = 0;
            subjects.forEach(subject => {
                if (subject && subject.id && subject.name) {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.name;
                    subjectDropdown.append(option);
                    addedCount++;
                }
            });
            
            console.log(`Added ${addedCount} subjects to dropdown`);
            
            // Enable the dropdown if subjects were added
            subjectDropdown.prop('disabled', addedCount === 0);
            
            // If no valid subjects were added despite having data
            if (addedCount === 0 && subjects.length > 0) {
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "Invalid subject data received";
                option.disabled = true;
                subjectDropdown.append(option);
                subjectDropdown.prop('disabled', true);
            }
            
            // Trigger change event to notify other components
            subjectDropdown.trigger('change');
        }

        // Event listener for homework Class dropdown change (in Add Homework modal)
        homeworkClass.on('change', function() {
            const selectedClassName = $(this).val();
            console.log("Add Homework - Class changed to:", selectedClassName);
            
            // Show loading state for section dropdown
            homeworkSection.html('<option value="">Loading sections...</option>').prop('disabled', true);
            
            // Clear the subject dropdown until section is selected
            homeworkSubject.html('<option value="">Select Section First</option>').prop('disabled', true);
            
            if (!selectedClassName) {
                homeworkSection.html('<option value="">Select Class First</option>').prop('disabled', true);
                return;
            }
            
            // Populate the section dropdown based on selected class
            populateSectionDropdown(selectedClassName, homeworkSection, allClassesData);
        });
        
        // Event listener for homework Section dropdown change
        homeworkSection.on('change', function() {
            const selectedSection = $(this).val();
            const selectedClass = homeworkClass.val();
            
            if (!selectedSection) {
                homeworkSubject.html('<option value="">Select Section First</option>').prop('disabled', true);
                return;
            }
            
            if (selectedClass && selectedSection) {
                // Show loading state
                homeworkSubject.prop('disabled', true).html('<option value="">Loading subjects...</option>');
                
                // Fetch and populate subjects for this class and section
                fetchTimetableSubjects(selectedClass, selectedSection)
                    .then(subjects => {
                        populateSubjectDropdownFromTimetable(subjects, homeworkSubject);
                        
                        // Enable subject dropdown if subjects were found
                        const subjectCount = homeworkSubject.find('option').length - 1; // Subtract default option
                        homeworkSubject.prop('disabled', subjectCount <= 0);
                        
                        if (subjectCount <= 0) {
                            homeworkSubject.html('<option value="">No subjects available</option>');
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching subjects:", error);
                        homeworkSubject.html('<option value="">Error loading subjects</option>');
                        homeworkSubject.prop('disabled', true);
                    });
            }
        });

        // Event listener for filter Class dropdown change (in Filter modal)
        filterHomeworkClass.on('change', function() {
            const selectedClassName = $(this).val();
            console.log("Filter modal - Class changed to:", selectedClassName);
            
            // Show loading state and disable section dropdown
            filterHomeworkSection.html('<option value="">Loading sections...</option>').prop('disabled', true);
            
            if (!selectedClassName) {
                filterHomeworkSection.html('<option value="">Select Class First</option>').prop('disabled', true);
                return;
            }
            
            // Populate section dropdown based on selected class
            populateSectionDropdown(selectedClassName, filterHomeworkSection, allClassesData);
        });
        
        // Event listener for quick filter class dropdown change
        $('#quickFilterClass').on('change', function() {
            const selectedClassName = $(this).val();
            console.log("Quick filter - Class changed to:", selectedClassName);
            
            const quickFilterSection = $('#quickFilterSection');
            
            // Show loading state
            quickFilterSection.html('<option value="">Loading sections...</option>').prop('disabled', true);
            
            if (!selectedClassName) {
                quickFilterSection.html('<option value="">All Sections</option>').prop('disabled', false);
                return;
            }
            
            // Populate section dropdown
            populateSectionDropdown(selectedClassName, quickFilterSection, allClassesData);
            
            // Add "All Sections" option at the top after populating
            setTimeout(() => {
                quickFilterSection.prepend('<option value="">All Sections</option>');
                quickFilterSection.prop('disabled', false);
            }, 100);
        });

        // Function to populate the Add Homework Class dropdown with unique class names
        function populateAddHomeworkClassDropdown(data) {
            console.log("Populating add homework class dropdown with data:", data);
            
            if (!data || !Array.isArray(data) || data.length === 0) {
                console.error("No valid class data available for add homework dropdown");
                homeworkClass.html('<option value="">No classes available</option>');
                homeworkClass.prop('disabled', true);
                return;
            }
            
            homeworkClass.html('<option value="">Select Class</option>'); // Clear existing options
            
            // Group by name and use the first ID for each unique name
            const classesByName = {};
            data.forEach(item => {
                if (item && item.name) {
                    if (!classesByName[item.name]) {
                        classesByName[item.name] = true;
                    }
                }
            });
            
            const classCount = Object.keys(classesByName).length;
            console.log(`Found ${classCount} unique classes for add homework dropdown`);
            
            // Add options with name as value and text
            Object.keys(classesByName).forEach(className => {
                const option = document.createElement('option');
                option.value = className; // Use the class name as value
                option.textContent = className;
                homeworkClass.append(option);
            });
            
            // Enable the dropdown
            homeworkClass.prop('disabled', false);
            
            console.log("Add homework class dropdown populated with options:", Object.keys(classesByName));
        }
        
        // Initial calls to fetch data and populate dropdowns
        console.log("Starting to fetch data for homework...");
        
        // First fetch classes and sections
        fetchClassesAndSections()
            .then(classesSuccess => {
                console.log("Classes fetch result:", classesSuccess);
                if (!classesSuccess) {
                    throw new Error("Failed to fetch classes data");
                }
                
                // Then fetch subjects
                return fetchSubjects();
            })
            .then(subjectsSuccess => {
                console.log("Subjects fetch result:", subjectsSuccess);
                if (!subjectsSuccess) {
                    throw new Error("Failed to fetch subjects data");
                }
                
                console.log("All data loaded successfully");
            })
            .catch(error => {
                console.error("Error loading data:", error);
            });
    });
</script>
