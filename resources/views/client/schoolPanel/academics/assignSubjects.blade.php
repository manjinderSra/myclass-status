@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Academics / <span class="text-l text-gray-500">Assign Subjects</span>
            </h1>
            <button id="openAssignModalBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Assign Subjects +
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <table id="assignedSubjectsTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Class</th>
                        <th class="px-6 py-3 font-semibold">Section</th>
                        <th class="px-6 py-3 font-semibold" style="width: 30rem;">Subjects</th>
                        <th class="px-6 py-3 font-semibold">Last Updated</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @if(isset($assignments) && count($assignments) > 0)
                        @foreach($assignments as $index => $assignment)
                            <tr class="hover:bg-gray-50 transition-colors" data-id="{{ $assignment['id'] }}">
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">{{ $assignment['class_name'] }}</td>
                                <td class="px-6 py-4">{{ $assignment['section_name'] }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($assignment['subjects'] as $subject)
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                {{ $subject->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ $assignment['created_at']->format('Y-m-d') }}</td>
                                <td class="px-6 py-4">
                                    <button class="text-indigo-600 hover:text-indigo-900 font-medium editAssignBtn" 
                                        data-id="{{ $assignment['id'] }}"
                                        data-class="{{ $assignment['class_name'] }}"
                                        data-section="{{ $assignment['section_id'] }}">Edit</button>
                                    <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteAssignBtn" 
                                        data-id="{{ $assignment['id'] }}"
                                        data-class="{{ $assignment['class_name'] }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="px-6 py-4">-</td>
                            <td class="px-6 py-4">-</td>
                            <td class="px-6 py-4">-</td>
                            <td class="px-6 py-4">-</td>
                            <td class="px-6 py-4">-</td>
                            <td class="px-6 py-4">-</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Assign Subjects Modal --}}
<div id="assignSubjectsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-lg w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Assign Subjects</h2>
        <form id="assignSubjectsForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
            <label class="block mb-2 font-medium text-gray-700">Select Class</label>
           <select name="class_id" id="classSelect" required class="w-full px-3 py-2 border rounded mb-4">
    <option value="" disabled selected>Select a class</option>
    @foreach($classes as $class)
        <option value="{{ $class->id }}">
            {{ $class->name }} (Section: {{ $class->section->name }})
        </option>
    @endforeach
</select>


            <label class="block mb-2 font-medium text-gray-700">Subjects</label>
            <div id="subjectsContainer" class="mb-4 border rounded p-4 max-h-60 overflow-y-auto">
                @foreach($subjects as $subject)
                    <div class="mb-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}" class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="ml-2">{{ $subject->name }} ({{ $subject->code }})</span>
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeAssignModalBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Assign</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Assignment Modal --}}
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-lg w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Edit Subject Assignments for <span id="editClassName"></span></h2>
        <form id="editAssignmentForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" id="edit_class_id" name="class_id">
            
            <label class="block mb-2 font-medium text-gray-700">Subjects</label>
            <div id="editSubjectsContainer" class="mb-4 border rounded p-4 max-h-60 overflow-y-auto">
                @foreach($subjects as $subject)
                    <div class="mb-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="subject_ids[]" value="{{ $subject->id }}" class="edit-subject-checkbox form-checkbox h-5 w-5 text-blue-600">
                            <span class="ml-2">{{ $subject->name }} ({{ $subject->code }})</span>
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeEditModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
        <p class="text-gray-700 mb-6">Are you sure you want to remove all subject assignments from class "<span id="deleteClassName" class="font-semibold"></span>"?</p>
        <div class="flex justify-end space-x-4">
            <button type="button" id="closeDeleteModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="confirmDeleteBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

{{-- DataTables CDN --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

{{-- Toastr CDN --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<style>
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
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Direct DOM reference for modal opening/closing
        const openAssignModalBtn = document.getElementById('openAssignModalBtn');
        const assignSubjectsModal = document.getElementById('assignSubjectsModal');
        const closeAssignModalBtn = document.getElementById('closeAssignModalBtn');
        
        // Vanilla JS event handlers as fallback
        if (openAssignModalBtn && assignSubjectsModal) {
            openAssignModalBtn.addEventListener('click', function() {
                console.log("Open modal button clicked");
                assignSubjectsModal.classList.remove('hidden');
                
                // Also reset the form
                const assignForm = document.getElementById('assignSubjectsForm');
                if (assignForm) assignForm.reset();
                
                // Reset checkboxes
                const checkboxes = document.querySelectorAll('input[name="subject_ids[]"]');
                checkboxes.forEach(checkbox => checkbox.checked = false);
            });
        }
        
        if (closeAssignModalBtn && assignSubjectsModal) {
            closeAssignModalBtn.addEventListener('click', function() {
                assignSubjectsModal.classList.add('hidden');
            });
        }
    });

    $(document).ready(function () {
        // Configure Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "opacity": 1
        };
        
        // Add custom CSS to fix Toastr transparency
        $("<style>")
            .prop("type", "text/css")
            .html(`
                #toast-container .toast {
                    opacity: 1 !important;
                }
                #toast-container .toast-success {
                    background-color: rgba(47, 133, 90, 0.95) !important;
                }
                #toast-container .toast-error {
                    background-color: rgba(191, 38, 33, 0.95) !important;
                }
                #toast-container .toast-info {
                    background-color: rgba(21, 115, 178, 0.95) !important;
                }
                #toast-container .toast-warning {
                    background-color: rgba(230, 153, 26, 0.95) !important;
                }
            `)
            .appendTo("head");
        
        // jQuery fallback for modal opening
        $("#openAssignModalBtn").on("click", function() {
            console.log("jQuery modal open clicked");
            $("#assignSubjectsModal").removeClass("hidden");
            $("#assignSubjectsForm")[0].reset();
            $('input[name="subject_ids[]"]').prop('checked', false);
        });
        
        $("#closeAssignModalBtn").on("click", function() {
            $("#assignSubjectsModal").addClass("hidden");
        });
        
        $('#closeEditModal').on('click', function() {
            $('#editModal').addClass('hidden');
        });
        
        $('#closeDeleteModal').on('click', function() {
            $('#deleteModal').addClass('hidden');
        });

        // Initialize DataTable with proper options for empty tables
        let assignmentsTable = $('#assignedSubjectsTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search assignments...",
                emptyTable: "No subject assignments found",
                zeroRecords: "No matching records found"
            },
            columnDefs: [
                { targets: '_all', defaultContent: "-" }
            ],
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
            dom:
                "<'flex justify-between items-center mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
                "t" +
                "<'flex justify-between items-center mt-4'<'dataTables_info'i><'dataTables_paginate'p>>",
        });

        // Edit assignments
        $('.editAssignBtn').on('click', function() {
            const classId = $(this).data('id');
            const className = $(this).data('class');
            
            // Reset form
            $('#editAssignmentForm')[0].reset();
            $('.edit-subject-checkbox').prop('checked', false);
            
            // Set class details
            $('#edit_class_id').val(classId);
            $('#editClassName').text(className);
            
            // Load current subject assignments
            $.ajax({
                url: "/school/class/" + classId + "/subjects",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                success: function(response) {
                    if (response.success) {
                        // Check checkboxes for assigned subjects
                        const assignedSubjectIds = response.subjects.map(s => s.id);
                        $('.edit-subject-checkbox').each(function() {
                            $(this).prop('checked', assignedSubjectIds.includes(parseInt($(this).val())));
                        });
                        
                        // Show modal
                        $('#editModal').removeClass('hidden');
                    } else {
                        toastr.error(response.message || 'Failed to load subject assignments');
                    }
                },
                error: function(xhr) {
                    let errorMessage = "An error occurred";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error("Error: " + errorMessage);
                }
            });
        });

        // Update assignments
        $('#editAssignmentForm').on('submit', function(e) {
            e.preventDefault();
            
            const classId = $('#edit_class_id').val();
            const subjectIds = [];
            
            $('.edit-subject-checkbox:checked').each(function() {
                subjectIds.push($(this).val());
            });
            
            if (subjectIds.length === 0) {
                toastr.error('Please select at least one subject');
                return false;
            }
            
            $.ajax({
                url: "/school/assignSubjects/" + classId,
                type: "POST",
                data: {
                    subject_ids: subjectIds,
                    _token: $('input[name="_token"]').val(),
                    _method: 'PUT'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#editModal').addClass('hidden');
                        
                        // Refresh page to show updated data
                        window.location.reload();
                    } else {
                        toastr.error(response.message || 'Failed to update subject assignments');
                    }
                },
                error: function(xhr) {
                    let errorMessage = "An error occurred";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error("Error: " + errorMessage);
                }
            });
        });

        // Delete assignments
        let deleteClassId = null;
        
        $('.deleteAssignBtn').on('click', function() {
            deleteClassId = $(this).data('id');
            const className = $(this).data('class');
            
            $('#deleteClassName').text(className);
            $('#deleteModal').removeClass('hidden');
        });

        $('#confirmDeleteBtn').on('click', function() {
            if (!deleteClassId) return;
            
            $.ajax({
                url: "/school/assignSubjects/" + deleteClassId,
                type: "POST",
                data: {
                    _token: $('input[name="_token"]').val(),
                    _method: 'DELETE'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#deleteModal').addClass('hidden');
                        
                        // Refresh page to show updated data
                        window.location.reload();
                    } else {
                        toastr.error(response.message || 'Failed to delete subject assignments');
                    }
                },
                error: function(xhr) {
                    let errorMessage = "An error occurred";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error("Error: " + errorMessage);
                }
            });
        });

        // Assign subjects form submission
        $('#assignSubjectsForm').on('submit', function(e) {
            e.preventDefault();
            
            const classId = $('#classSelect').val();
            const subjectIds = [];
            
            $('input[name="subject_ids[]"]:checked').each(function() {
                subjectIds.push($(this).val());
            });
            
            // Improved validation to prevent unwanted alerts
            if (!classId) {
                toastr.error('Please select a class');
                return false;
            }
            
            if (subjectIds.length === 0) {
                toastr.error('Please select at least one subject');
                return false;
            }
            
            $.ajax({
                url: "{{ route('school.assignSubjects.store') }}",
                type: "POST",
                data: {
                    class_id: classId,
                    subject_ids: subjectIds,
                    _token: $('input[name="_token"]').val()
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $("#assignSubjectsModal").addClass("hidden");
                        $('#assignSubjectsForm')[0].reset();
                        // Refresh page to show updated data
                        window.location.reload();
                    } else {
                        toastr.error(response.message || 'An error occurred while assigning subjects');
                    }
                },
                error: function(xhr) {
                    let errorMessage = "An error occurred";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error("Error: " + errorMessage);
                }
            });
        });
    });
</script>
