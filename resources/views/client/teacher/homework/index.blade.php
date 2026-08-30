@include('client.teacher.layout.master')

{{-- Add CSRF token meta tag --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')


<style>

.main-content {
    margin-left: 0;
    min-height: 0;
    padding-top: 0;
}

</style>

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
                <div class="flex items-center space-x-3">
                    {{-- Filter Button (NEW) --}}
                    <button id="openFilterHomeworkModal" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V19l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    <button id="openAddHomeworkModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        Add Homework +
                    </button>
                </div>
            </div>
        </div>
        {{-- End Header Section --}}


        {{-- Simple Filter Form (for testing) --}}
        <div class="bg-white rounded-lg shadow w-full p-4 mt-4 mb-4">
            <h3 class="text-lg font-semibold mb-3">Quick Filter</h3>
            <form action="{{ route('teacher.homework.filter') }}" method="GET" class="flex flex-wrap gap-2 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label for="quickFilterClass" class="block text-sm font-medium mb-1">Class</label>
                    <select id="quickFilterClass" name="filterClass" class="w-full border rounded px-3 py-2">
                        <option value="">All Classes</option>
                        @foreach($teachingAssignments as $assignment)
                            <option value="{{ $assignment['class_name'] }}">{{ $assignment['class_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="quickFilterSection" class="block text-sm font-medium mb-1">Section</label>
                    <select id="quickFilterSection" name="filterSection" class="w-full border rounded px-3 py-2">
                        <option value="">All Sections</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="quickFilterDate" class="block text-sm font-medium mb-1">Homework Date</label>
                    <input type="date" id="quickFilterDate" name="filterHomeworkDate" class="w-full border rounded px-3 py-2">
                </div>
                <div class="flex-none">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
                    <a href="{{ route('teacher.homework') }}" class="ml-2 bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">Reset</a>
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
                                    <div class="flex items-center space-x-2">
                                        <button class="text-blue-600 hover:text-blue-900 editHomeworkBtn" data-id="{{ $homework['id'] }}">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button class="text-red-600 hover:text-red-900 deleteHomeworkBtn" data-id="{{ $homework['id'] }}">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        
                                         <a href="{{ route('teacher.homeworks.submissions', $homework['id']) }}"
           class="text-blue-600 hover:text-blue-800 underline">
            View Submissions
        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                No homework entries found. Click "Add Homework +" to create your first homework assignment.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Add/Edit Homework Modal --}}
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
                                @foreach($teachingAssignments as $assignment)
                                    <option value="{{ $assignment['class_name'] }}">{{ $assignment['class_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="homeworkSection" class="block text-gray-700 text-sm font-bold mb-2">Section</label>
                            <select id="homeworkSection" name="section" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">Select Class First</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="homeworkSubject" class="block text-gray-700 text-sm font-bold mb-2">Subject</label>
                            <select id="homeworkSubject" name="subject" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">Select Section First</option>
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
                        <input type="file" id="homeworkImage" name="image" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/png,image/webp">
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

        {{-- Filter Homework Modal --}}
        <div id="filterHomeworkModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg max-w-sm w-full p-6 relative modal-content">
                <button id="closeFilterHomeworkModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h2 class="text-xl font-semibold mb-6">Filter Homework</h2>
                <form id="filterHomeworkForm" action="{{ route('teacher.homework.filter') }}" method="GET">
                    <div class="mb-4">
                        <label for="filterHomeworkClass" class="block text-gray-700 text-sm font-bold mb-2">Class</label>
                        <select id="filterHomeworkClass" name="filterClass" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select Class</option>
                            @foreach($teachingAssignments as $assignment)
                                <option value="{{ $assignment['class_name'] }}" {{ isset($filters['filterClass']) && $filters['filterClass'] == $assignment['class_name'] ? 'selected' : '' }}>{{ $assignment['class_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="filterHomeworkSection" class="block text-gray-700 text-sm font-bold mb-2">Section</label>
                        <select id="filterHomeworkSection" name="filterSection" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select Section</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="filterHomeworkSubject" class="block text-gray-700 text-sm font-bold mb-2">Subject</label>
                        <select id="filterHomeworkSubject" name="filterSubject" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select Subject</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="filterHomeworkDate" class="block text-gray-700 text-sm font-bold mb-2">Homework Date</label>
                        <input type="date" id="filterHomeworkDate" name="filterHomeworkDate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $filters['filterHomeworkDate'] ?? '' }}">
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('teacher.homework') }}" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Reset</a>
                        <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Apply</button>
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

    $(document).ready(function() {
        // Store teaching assignments and subjects data
        const teachingAssignments = @json($teachingAssignments);
        const teacherSubjects = @json($subjects);
        
        console.log('Teaching Assignments:', teachingAssignments);
        console.log('Teacher Subjects:', teacherSubjects);
        
        // Create maps for quick lookup
        const classSectionsMap = new Map();
        const classSectionSubjectsMap = new Map();
        
        // Initialize maps
        teachingAssignments.forEach(assignment => {
            if (assignment.sections && assignment.sections.length > 0) {
                classSectionsMap.set(assignment.class_name, assignment.sections);
                
                // Create a map of class-section combinations to subjects
                assignment.sections.forEach(section => {
                    const key = `${assignment.class_name}-${section.id}`;
                    const subjects = teacherSubjects.filter(subject => 
                        subject.class_name === assignment.class_name && 
                        subject.section_id === section.id
                    );
                    classSectionSubjectsMap.set(key, subjects);
                });
            }
        });
        
        // Function to update sections based on selected class
        function updateSections(selectElement) {
            const selectedClass = $(selectElement).val();
            console.log('Selected class:', selectedClass);
            const sections = classSectionsMap.get(selectedClass) || [];
            console.log('Available sections for this class:', sections);
            
            // Get the section select element
            let sectionsSelect;
            if ($(selectElement).attr('id') === 'homeworkClass') {
                sectionsSelect = $('#homeworkSection');
            } else if ($(selectElement).attr('id') === 'filterHomeworkClass') {
                sectionsSelect = $('#filterHomeworkSection');
            } else if ($(selectElement).attr('id') === 'quickFilterClass') {
                sectionsSelect = $('#quickFilterSection');
            }
            
            if (sectionsSelect) {
                // Update sections dropdown
                sectionsSelect.empty().append('<option value="">Select Section</option>');
                
                sections.forEach(section => {
                    sectionsSelect.append(`<option value="${section.id}">${section.name}</option>`);
                });
                
                console.log('Updated sections dropdown:', sectionsSelect.attr('id'), sectionsSelect.html());
            }
        }
        
        // Function to update subjects based on selected class and section
        function updateSubjects(selectElement) {
            let classSelect;
            let subjectsSelect;
            
            if ($(selectElement).attr('id') === 'homeworkSection') {
                classSelect = $('#homeworkClass');
                subjectsSelect = $('#homeworkSubject');
            } else if ($(selectElement).attr('id') === 'filterHomeworkSection') {
                classSelect = $('#filterHomeworkClass');
                subjectsSelect = $('#filterHomeworkSubject');
            }
            
            const selectedClass = classSelect.val();
            const selectedSection = $(selectElement).val();
            
            console.log('Updating subjects for class:', selectedClass, 'section:', selectedSection);
            
            if (selectedClass && selectedSection && subjectsSelect) {
                const key = `${selectedClass}-${selectedSection}`;
                const subjects = classSectionSubjectsMap.get(key) || [];
                console.log('Available subjects for this class-section:', subjects);
                
                // Update subjects dropdown
                subjectsSelect.empty().append('<option value="">Select Subject</option>');
                
                subjects.forEach(subject => {
                    subjectsSelect.append(`<option value="${subject.id}">${subject.name}</option>`);
                });
                
                console.log('Updated subjects dropdown:', subjectsSelect.attr('id'), subjectsSelect.html());
            }
        }
        
        // Initialize and bind events for homework form
        $('#homeworkClass').on('change', function() {
            updateSections(this);
        });
        
        $('#homeworkSection').on('change', function() {
            updateSubjects(this);
        });
        
        // Initialize and bind events for filter form
        $('#filterHomeworkClass').on('change', function() {
            updateSections(this);
        });
        
        $('#filterHomeworkSection').on('change', function() {
            updateSubjects(this);
        });
        
        // Initialize and bind events for quick filter form
        $('#quickFilterClass').on('change', function() {
            updateSections(this);
        });
        
        // Check if there are pre-selected values (for edit mode or after form submission)
        const preSelectedClass = $('#homeworkClass').val();
        if (preSelectedClass) {
            updateSections($('#homeworkClass')[0]);
            
            // Wait a bit for sections to be populated
            setTimeout(() => {
                const preSelectedSection = $('#homeworkSection').val();
                if (preSelectedSection) {
                    updateSubjects($('#homeworkSection')[0]);
                }
            }, 100);
        }
        
        const preSelectedFilterClass = $('#filterHomeworkClass').val();
        if (preSelectedFilterClass) {
            updateSections($('#filterHomeworkClass')[0]);
            
            setTimeout(() => {
                const preSelectedFilterSection = $('#filterHomeworkSection').val();
                if (preSelectedFilterSection) {
                    updateSubjects($('#filterHomeworkSection')[0]);
                }
            }, 100);
        }
        
        const preSelectedQuickFilterClass = $('#quickFilterClass').val();
        if (preSelectedQuickFilterClass) {
            updateSections($('#quickFilterClass')[0]);
        }
        
        // Handle form submission for adding/editing homework
        $('#homeworkForm').on('submit', function(e) {
            e.preventDefault();
            
            console.log('Submitting homework form');
            
            // Create FormData object to handle file uploads
            const formData = new FormData(this);
            
            // Get homework ID (if editing)
            const homeworkId = $('#homeworkId').val();
            
            // Determine URL based on whether we're adding or editing
            let url = '/teacher/homework/store';
            let method = 'POST';
            
            if (homeworkId) {
                url = `/teacher/homework/update/${homeworkId}`;
            }
            
            // Send AJAX request
            $.ajax({
                url: url,
                type: method,
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Homework saved successfully:', response);
                    
                    // Hide modal
                    $('#addEditHomeworkModal').addClass('hidden');
                    
                    // Show success message
                    alert('Homework saved successfully!');
                    
                    // Reload page to show updated data
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error saving homework:', xhr.responseJSON);
                    
                    // Show error message
                    let errorMessage = 'An error occurred while saving homework.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    alert(errorMessage);
                }
            });
        });
        
        // Handle edit button clicks
        $(document).on('click', '.editHomeworkBtn', function() {
            const homeworkId = $(this).data('id');
            console.log('Edit button clicked for homework ID:', homeworkId);
            
            // Reset form
            $('#homeworkForm')[0].reset();
            $('#homeworkModalTitle').text('Edit Homework');
            
            // Show loading state
            $('#saveHomeworkBtn').text('Loading...').prop('disabled', true);
            
            // Fetch homework data
            $.ajax({
                url: `/teacher/homework/get/${homeworkId}`,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Homework data fetched:', response);
                    
                    if (response.success && response.homework) {
                        const homework = response.homework;
                        
                        // Set form values
                        $('#homeworkId').val(homework.id);
                        $('#homeworkClass').val(homework.class_name);
                        updateSections($('#homeworkClass')[0]);
                        
                        // Wait for sections to be populated
                        setTimeout(() => {
                            $('#homeworkSection').val(homework.section_id);
                            updateSubjects($('#homeworkSection')[0]);
                            
                            // Wait for subjects to be populated
                            setTimeout(() => {
                                $('#homeworkSubject').val(homework.subject_id);
                            }, 100);
                        }, 100);
                        
                        $('#homeworkDate').val(homework.homework_date);
                        $('#submissionDate').val(homework.submission_date);
                        $('#description').val(homework.description);
                        
                        // Handle image preview
                        if (homework.image_url) {
                            $('#currentImageContainer').removeClass('hidden');
                            $('#currentImage').attr('src', homework.image_url);
                        } else {
                            $('#currentImageContainer').addClass('hidden');
                        }
                        
                        // Show modal
                        $('#addEditHomeworkModal').removeClass('hidden');
                        
                        // Reset button state
                        $('#saveHomeworkBtn').text('Save Homework').prop('disabled', false);
                    } else {
                        alert('Failed to fetch homework data.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching homework:', xhr.responseJSON);
                    alert('An error occurred while fetching homework data.');
                    $('#saveHomeworkBtn').text('Save Homework').prop('disabled', false);
                }
            });
        });
        
        // Handle delete confirmation
        $('#confirmDeleteHomeworkBtn').on('click', function() {
            const homeworkId = window.homeworkToDeleteId;
            
            if (!homeworkId) {
                $('#deleteHomeworkModal').addClass('hidden');
                return;
            }
            
            console.log('Deleting homework ID:', homeworkId);
            
            // Disable button to prevent multiple clicks
            $(this).text('Deleting...').prop('disabled', true);
            
            // Send delete request
            $.ajax({
                url: `/teacher/homework/delete/${homeworkId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Homework deleted successfully:', response);
                    
                    // Hide modal
                    $('#deleteHomeworkModal').addClass('hidden');
                    
                    // Show success message
                    alert('Homework deleted successfully!');
                    
                    // Reload page to show updated data
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting homework:', xhr.responseJSON);
                    
                    // Show error message
                    let errorMessage = 'An error occurred while deleting homework.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    alert(errorMessage);
                    
                    // Reset button state
                    $('#confirmDeleteHomeworkBtn').text('Delete').prop('disabled', false);
                }
            });
        });
        
        // Initialize DataTable
        $('#homeworksTable').DataTable({
            "pageLength": 10,
            "ordering": true,
            "info": true,
            "searching": true,
            "responsive": true,
            "language": {
                "paginate": {
                    "previous": "&lt;",
                    "next": "&gt;"
                }
            }
        });
    });
</script>