@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Academics / <span class="text-l text-gray-500">Subjects</span>
            </h1>
            <button id="openSubjectModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Create Subject +
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
            <table id="subjectsTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Subject Name</th>
                        <th class="px-6 py-3 font-semibold">Subject Code</th>
                        <th class="px-6 py-3 font-semibold">Description</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @foreach($subjects as $index => $subject)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">{{ $subject->name }}</td>
                        <td class="px-6 py-4">{{ $subject->code }}</td>
                        <td class="px-6 py-4">{{ Str::limit($subject->description, 50) }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-{{ $subject->status ? 'green' : 'red' }}-100 text-{{ $subject->status ? 'green' : 'red' }}-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $subject->status ? 'Enabled' : 'Disabled' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button class="text-indigo-600 hover:text-indigo-900 font-medium editSubjectBtn" 
                                data-id="{{ $subject->id }}" 
                                data-name="{{ $subject->name }}" 
                                data-code="{{ $subject->code }}"
                                data-description="{{ $subject->description }}" 
                                data-status="{{ $subject->status }}">Edit</button>
                            <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteSubjectBtn" 
                                data-id="{{ $subject->id }}" 
                                data-name="{{ $subject->name }}">Delete</button>
                            <button class="text-blue-600 hover:text-blue-800 font-medium ml-3 assignClassesBtn" 
                                data-id="{{ $subject->id }}" 
                                data-name="{{ $subject->name }}">Assign Classes</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create Subject Modal --}}
<div id="subjectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Create Subject</h2>
        <form id="subjectForm">
            @csrf
            <label class="block mb-2 font-medium text-gray-700">Subject Name</label>
            <input type="text" name="name" required placeholder="Enter Subject Name" class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />

            <label class="block mb-2 font-medium text-gray-700">Subject Code</label>
            <input type="text" name="code" required placeholder="e.g. MTH101" class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />

            <label class="block mb-2 font-medium text-gray-700">Description (Optional)</label>
            <textarea name="description" rows="3" placeholder="Brief description of this subject" class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

            <label class="flex items-center mb-4">
                <input type="checkbox" name="status" class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                <span class="text-gray-700">Enable this subject</span>
            </label>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeSubjectModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Subject Modal --}}
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Edit Subject</h2>
        <form id="editForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="edit_id" />
            <label class="block mb-2 font-medium text-gray-700">Subject Name</label>
            <input type="text" id="edit_name" name="name" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">Subject Code</label>
            <input type="text" id="edit_code" name="code" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">Description (Optional)</label>
            <textarea id="edit_description" name="description" rows="3" class="w-full px-3 py-2 border rounded mb-4"></textarea>

            <label class="flex items-center mb-4">
                <input type="checkbox" id="edit_status" name="status" class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded">
                <span class="text-gray-700">Enable this subject</span>
            </label>

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
        <p class="text-gray-700 mb-6">Are you sure you want to delete subject "<span id="deleteSubjectName" class="font-semibold"></span>"? This action cannot be undone.</p>
        <div class="flex justify-end space-x-4">
            <button type="button" id="closeDeleteModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="confirmDeleteBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

{{-- Assign Classes Modal --}}
<div id="assignClassesModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Assign "<span id="assignSubjectName" class="font-semibold"></span>" to Classes</h2>
        <form id="assignClassesForm">
            @csrf
            <input type="hidden" id="assign_subject_id" name="subject_id" />
            
            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">Select Classes</label>
                <div class="max-h-60 overflow-y-auto border rounded p-3">
                    @foreach($classes as $class)
                    <div class="mb-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="class_ids[]" value="{{ $class->id }}" class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="ml-2">{{ $class->name }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeAssignClassesModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Assign</button>
            </div>
        </form>
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
        
        // Initialize DataTable
        let subjectsTable = $('#subjectsTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search subjects..."
            },
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
            dom:
                "<'flex justify-between items-center mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
                "t" +
                "<'flex justify-between items-center mt-4'<'dataTables_info'i><'dataTables_paginate'p>>",
        });

        // Open/close modals
        $('#openSubjectModal').click(() => {
            $('#subjectForm')[0].reset();
            $('#subjectModal').removeClass('hidden');
        });
        $('#closeSubjectModal').click(() => $('#subjectModal').addClass('hidden'));
        $('#closeEditModal').click(() => $('#editModal').addClass('hidden'));
        $('#closeDeleteModal').click(() => $('#deleteModal').addClass('hidden'));
        $('#closeAssignClassesModal').click(() => $('#assignClassesModal').addClass('hidden'));

        // Create subject
        $('#subjectForm').submit(function (e) {
            e.preventDefault();
            
            const isChecked = $(this).find('input[name="status"]').is(':checked');
            
            const formData = {
                name: $(this).find('input[name="name"]').val().trim(),
                code: $(this).find('input[name="code"]').val().trim(),
                description: $(this).find('textarea[name="description"]').val().trim(),
                status: isChecked ? 1 : 0,
                _token: $('meta[name="csrf-token"]').attr('content')
            };
            
            console.log('Creating subject with data:', formData);
            
            $.ajax({
                url: "{{ route('school.subjects.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Show toast message and close modal
                        toastr.success(response.message);
                        $('#subjectModal').addClass('hidden');
                        
                        // Reset form
                        $('#subjectForm')[0].reset();
                        
                        // Add new row to DataTable
                        addRowToTable(response.subject);
                    } else {
                        toastr.error(response.message);
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

        // Edit subject
        $('.editSubjectBtn').click(function () {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const code = $(this).data('code');
            const description = $(this).data('description') || '';
            const status = $(this).data('status');

            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_code').val(code);
            $('#edit_description').val(description);
            $('#edit_status').prop('checked', status == 1);

            $('#editModal').removeClass('hidden');
        });

        // Update subject
        $('#editForm').submit(function (e) {
            e.preventDefault();
            
            const id = $('#edit_id').val();
            const name = $('#edit_name').val().trim();
            const code = $('#edit_code').val().trim();
            const description = $('#edit_description').val().trim();
            const isChecked = $('#edit_status').is(':checked');
            
            const formData = {
                name: name,
                code: code,
                description: description,
                status: isChecked ? 1 : 0,
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'PUT'
            };
            
            console.log('Updating subject with data:', formData);
            
            $.ajax({
                url: `/school/subjects/${id}`,
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Show toast message and close modal
                        toastr.success(response.message);
                        $('#editModal').addClass('hidden');
                        
                        // Update row in DataTable
                        updateRowInTable(response.subject);
                    } else {
                        toastr.error(response.message);
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

        // Delete subject
        let deleteSubjectId = null;
        
        $('.deleteSubjectBtn').click(function () {
            deleteSubjectId = $(this).data('id');
            const subjectName = $(this).data('name');
            $('#deleteSubjectName').text(subjectName);
            $('#deleteModal').removeClass('hidden');
        });

        $('#confirmDeleteBtn').click(function () {
            if (!deleteSubjectId) return;
            
            $.ajax({
                url: `/school/subjects/${deleteSubjectId}`,
                type: "DELETE",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Show toast message and close modal
                        toastr.success(response.message);
                        $('#deleteModal').addClass('hidden');
                        
                        // Remove row from DataTable
                        removeRowFromTable(deleteSubjectId);
                    } else {
                        toastr.error(response.message);
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
        
        // Assign Classes to Subject
        $('.assignClassesBtn').click(function() {
            const subjectId = $(this).data('id');
            const subjectName = $(this).data('name');
            
            // Reset form
            $('#assignClassesForm')[0].reset();
            
            // Set subject ID and name
            $('#assign_subject_id').val(subjectId);
            $('#assignSubjectName').text(subjectName);
            
            // Load existing class assignments
            $.ajax({
                url: `/school/subjects/${subjectId}`,
                type: "GET",
                success: function(response) {
                    if (response.success) {
                        // Check boxes for assigned classes
                        const assignedClassIds = response.subject.classes.map(c => c.id);
                        $('input[name="class_ids[]"]').each(function() {
                            $(this).prop('checked', assignedClassIds.includes(parseInt($(this).val())));
                        });
                        
                        // Show modal
                        $('#assignClassesModal').removeClass('hidden');
                    } else {
                        toastr.error(response.message);
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
        
        // Submit class assignments
        $('#assignClassesForm').submit(function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            $.ajax({
                url: "{{ route('school.subjects.assign-to-class') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Show toast message and close modal
                        toastr.success(response.message);
                        $('#assignClassesModal').addClass('hidden');
                    } else {
                        toastr.error(response.message);
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
        
        // Function to add a new row to the DataTable
        function addRowToTable(subjectData) {
            // Create the status badge HTML
            const statusBadge = `
                <span class="bg-${subjectData.status ? 'green' : 'red'}-100 text-${subjectData.status ? 'green' : 'red'}-800 text-xs font-medium px-2.5 py-0.5 rounded">
                    ${subjectData.status ? 'Enabled' : 'Disabled'}
                </span>
            `;
            
            // Truncate description if it's too long
            const shortDescription = subjectData.description ? 
                (subjectData.description.length > 50 ? subjectData.description.substring(0, 50) + '...' : subjectData.description) : 
                '';
            
            // Add the new row to the DataTable
            const rowNode = subjectsTable.row.add([
                '', // Index will be auto-assigned
                subjectData.name,
                subjectData.code,
                shortDescription,
                statusBadge,
                `<button class="text-indigo-600 hover:text-indigo-900 font-medium editSubjectBtn" 
                    data-id="${subjectData.id}" 
                    data-name="${subjectData.name}" 
                    data-code="${subjectData.code}" 
                    data-description="${subjectData.description || ''}" 
                    data-status="${subjectData.status}">Edit</button>
                <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteSubjectBtn" 
                    data-id="${subjectData.id}" 
                    data-name="${subjectData.name}">Delete</button>
                <button class="text-blue-600 hover:text-blue-800 font-medium ml-3 assignClassesBtn" 
                    data-id="${subjectData.id}" 
                    data-name="${subjectData.name}">Assign Classes</button>`
            ]).draw().node();
            
            // Add event listeners to the new buttons
            $(rowNode).find('.editSubjectBtn').click(function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const code = $(this).data('code');
                const description = $(this).data('description') || '';
                const status = $(this).data('status');
                
                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_code').val(code);
                $('#edit_description').val(description);
                $('#edit_status').prop('checked', status == 1);
                
                $('#editModal').removeClass('hidden');
            });
            
            $(rowNode).find('.deleteSubjectBtn').click(function() {
                deleteSubjectId = $(this).data('id');
                const subjectName = $(this).data('name');
                $('#deleteSubjectName').text(subjectName);
                $('#deleteModal').removeClass('hidden');
            });
            
            $(rowNode).find('.assignClassesBtn').click(function() {
                const subjectId = $(this).data('id');
                const subjectName = $(this).data('name');
                
                // Reset form
                $('#assignClassesForm')[0].reset();
                
                // Set subject ID and name
                $('#assign_subject_id').val(subjectId);
                $('#assignSubjectName').text(subjectName);
                
                // Load existing class assignments
                $.ajax({
                    url: `/school/subjects/${subjectId}`,
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            // Check boxes for assigned classes
                            const assignedClassIds = response.subject.classes.map(c => c.id);
                            $('input[name="class_ids[]"]').each(function() {
                                $(this).prop('checked', assignedClassIds.includes(parseInt($(this).val())));
                            });
                            
                            // Show modal
                            $('#assignClassesModal').removeClass('hidden');
                        } else {
                            toastr.error(response.message);
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
        }
        
        // Function to update a row in the DataTable
        function updateRowInTable(subjectData) {
            // Find the row with the matching ID
            const rows = subjectsTable.rows().nodes();
            for (let i = 0; i < rows.length; i++) {
                const row = $(rows[i]);
                const editBtn = row.find('.editSubjectBtn');
                if (editBtn.data('id') == subjectData.id) {
                    // Create the status badge HTML
                    const statusBadge = `
                        <span class="bg-${subjectData.status ? 'green' : 'red'}-100 text-${subjectData.status ? 'green' : 'red'}-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            ${subjectData.status ? 'Enabled' : 'Disabled'}
                        </span>
                    `;
                    
                    // Truncate description if it's too long
                    const shortDescription = subjectData.description ? 
                        (subjectData.description.length > 50 ? subjectData.description.substring(0, 50) + '...' : subjectData.description) : 
                        '';
                    
                    // Update the subject cells
                    subjectsTable.cell(row, 1).data(subjectData.name).draw(false);
                    subjectsTable.cell(row, 2).data(subjectData.code).draw(false);
                    subjectsTable.cell(row, 3).data(shortDescription).draw(false);
                    subjectsTable.cell(row, 4).data(statusBadge).draw(false);
                    
                    // Update the data attributes of the edit button
                    editBtn.data('name', subjectData.name);
                    editBtn.data('code', subjectData.code);
                    editBtn.data('description', subjectData.description || '');
                    editBtn.data('status', subjectData.status);
                    
                    // Update the data attributes of the delete button
                    row.find('.deleteSubjectBtn').data('name', subjectData.name);
                    
                    // Update the data attributes of the assign classes button
                    row.find('.assignClassesBtn').data('name', subjectData.name);
                    
                    break;
                }
            }
        }
        
        // Function to remove a row from the DataTable
        function removeRowFromTable(subjectId) {
            const rows = subjectsTable.rows().nodes();
            for (let i = 0; i < rows.length; i++) {
                const row = $(rows[i]);
                const editBtn = row.find('.editSubjectBtn');
                if (editBtn.data('id') == subjectId) {
                    subjectsTable.row(row).remove().draw(false);
                    break;
                }
            }
        }
    });
</script>
