@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Academics / <span class="text-l text-gray-500">Classes</span>
            </h1>
            <button id="openClassModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Create Class +
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
            <table id="classesTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Class Name</th>
                        <th class="px-6 py-3 font-semibold">Section</th>
                        <th class="px-6 py-3 font-semibold">Total Capacity</th>
                        <th class="px-6 py-3 font-semibold">Capacity Left</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @foreach($classes as $index => $class)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">{{ $class->name }}</td>
                        <td class="px-6 py-4">{{ $class->section ? $class->section->name : 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $class->total_capacity }}</td>
                       <td class="px-6 py-4">
    @php
        $enrolledCount = \App\Models\Student::where('school_id', $class->school_id)
            ->where('class_id', $class->id)
            ->where('section_id', $class->section_id)
            ->count();
        $remaining = $class->total_capacity - $enrolledCount;
    @endphp
    {{ $remaining }}
</td>
                        <td class="px-6 py-4">
                            <span class="bg-{{ $class->status ? 'green' : 'red' }}-100 text-{{ $class->status ? 'green' : 'red' }}-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $class->status ? 'Enabled' : 'Disabled' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                        
                        
                            <button class="text-indigo-600 hover:text-indigo-900 font-medium editClassBtn" 
                                data-id="{{ $class->id }}" 
                                data-name="{{ $class->name }}" 
                                data-section="{{ $class->section_id }}"
                                data-capacity="{{ $class->total_capacity }}" 
                                data-status="{{ $class->status }}">Edit</button>
                          @if($class->section_id)
    <a href="{{ route('school.class.students', [$class->school_id, $class->id, $class->section_id]) }}" 
       class="text-indigo-600 hover:text-indigo-800 font-medium ml-3">
       Show Students
    </a>
@else
    <span class="text-gray-400 ml-3">No Section</span>
@endif

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create Class Modal --}}
<div id="classModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Create Class</h2>
        <form id="classForm">
            @csrf
            <label class="block mb-2 font-medium text-gray-700">Class Name</label>
            <input type="text" name="name" required placeholder="Enter Class Name" class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />

            <label class="block mb-2 font-medium text-gray-700">Section</label>
            <select name="section_id" class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Select Section (Optional) --</option>
                @foreach($sections as $section)
                <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>

            <label class="block mb-2 font-medium text-gray-700">Total Capacity</label>
            <input type="number" name="total_capacity" required placeholder="30" min="1" max="999" class="w-full px-3 py-2 border rounded mb-4" />

            <label class="flex items-center mb-4">
                <input type="checkbox" name="status" class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded" checked>
                <span class="text-gray-700">Enable this class</span>
            </label>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeClassModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Class Modal --}}
<div id="editClassModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Edit Class</h2>
        <form id="editClassForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="edit_id" />
            <label class="block mb-2 font-medium text-gray-700">Class Name</label>
            <input type="text" id="edit_name" name="name" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">Section</label>
            <select id="edit_section_id" name="section_id" class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Select Section (Optional) --</option>
                @foreach($sections as $section)
                <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>

            <label class="block mb-2 font-medium text-gray-700">Total Capacity</label>
            <input type="number" id="edit_total_capacity" name="total_capacity" required min="1" max="999" class="w-full px-3 py-2 border rounded mb-4" />

            <label class="flex items-center mb-4">
                <input type="checkbox" id="edit_status" name="status" class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded">
                <span class="text-gray-700">Enable this class</span>
            </label>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeEditClassModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Class Modal --}}
<div id="deleteClassModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
        <p class="text-gray-700 mb-6">Are you sure you want to delete class "<span id="deleteClassName" class="font-semibold"></span>"? This action cannot be undone.</p>
        <div class="flex justify-end space-x-4">
            <button type="button" id="closeDeleteClassModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="confirmDeleteClassBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
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
        let classesTable = $('#classesTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search classes..."
            },
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
            dom:
                "<'flex justify-between items-center mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
                "t" +
                "<'flex justify-between items-center mt-4'<'dataTables_info'i><'dataTables_paginate'p>>",
        });

        // Open/close modals
        $('#openClassModal').click(() => {
            $('#classForm')[0].reset();
            $('#classModal').removeClass('hidden');
        });
        $('#closeClassModal').click(() => $('#classModal').addClass('hidden'));
        $('#closeEditClassModal').click(() => $('#editClassModal').addClass('hidden'));
        $('#closeDeleteClassModal').click(() => $('#deleteClassModal').addClass('hidden'));

        // Create class
        $('#classForm').submit(function (e) {
            e.preventDefault();
            
            const isChecked = $(this).find('input[name="status"]').is(':checked');
            
            const formData = {
                name: $(this).find('input[name="name"]').val().trim(),
                section_id: $(this).find('select[name="section_id"]').val() || null,
                total_capacity: $(this).find('input[name="total_capacity"]').val(),
                status: isChecked ? 1 : 0,
                _token: $('meta[name="csrf-token"]').attr('content')
            };
            
            console.log('Creating class with data:', formData);
            
            $.ajax({
                url: "{{ route('school.class.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Show toast message and close modal
                        toastr.success(response.message);
                        $('#classModal').addClass('hidden');
                        
                        // Reset form
                        $('#classForm')[0].reset();
                        
                        // Add new row to DataTable
                        addRowToTable(response.class);
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

        // Edit class
        $('.editClassBtn').click(function () {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const sectionId = $(this).data('section') || '';
            const capacity = $(this).data('capacity');
            const status = $(this).data('status');

            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_section_id').val(sectionId);
            $('#edit_total_capacity').val(capacity);
            $('#edit_status').prop('checked', status == 1);

            $('#editClassModal').removeClass('hidden');
        });

        // Update class
        $('#editClassForm').submit(function (e) {
            e.preventDefault();
            
            const id = $('#edit_id').val();
            const name = $('#edit_name').val().trim();
            const sectionId = $('#edit_section_id').val() || null;
            const capacity = $('#edit_total_capacity').val();
            const isChecked = $('#edit_status').is(':checked');
            
            const formData = {
                name: name,
                section_id: sectionId,
                total_capacity: capacity,
                status: isChecked ? 1 : 0,
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'PUT'
            };
            
            console.log('Updating class with data:', formData);
            
            $.ajax({
                url: `/school/class/${id}`,
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Show toast message and close modal
                        toastr.success(response.message);
                        $('#editClassModal').addClass('hidden');
                        
                        // Update row in DataTable
                        updateRowInTable(response.class);
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

        // Delete class
        let deleteClassId = null;
        let deleteClassBtnRow = null;
        
        $('.deleteClassBtn').click(function () {
            deleteClassId = $(this).data('id');
            const className = $(this).data('name');
            deleteClassBtnRow = $(this).closest('tr');
            $('#deleteClassName').text(className);
            $('#deleteClassModal').removeClass('hidden');
        });

        $('#confirmDeleteClassBtn').click(function () {
            if (!deleteClassId) return;
            
            $.ajax({
                url: `/school/class/${deleteClassId}`,
                type: "DELETE",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Show toast message and close modal
                        toastr.success(response.message);
                        $('#deleteClassModal').addClass('hidden');
                        
                        // Remove row from DataTable
                        removeRowFromTable(deleteClassId);
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
        function addRowToTable(classData) {
            // Create the status badge HTML
            const statusBadge = `
                <span class="bg-${classData.status ? 'green' : 'red'}-100 text-${classData.status ? 'green' : 'red'}-800 text-xs font-medium px-2.5 py-0.5 rounded">
                    ${classData.status ? 'Enabled' : 'Disabled'}
                </span>
            `;
            
            // Get section name
            const sectionName = classData.section ? classData.section.name : 'N/A';
            
            // Format the created date
            const createdDate = new Date(classData.created_at);
            const formattedDate = createdDate.toISOString().split('T')[0]; // YYYY-MM-DD format
            
            // Calculate a dummy remaining capacity for now
            const remainingCapacity = classData.total_capacity - Math.floor(Math.random() * classData.total_capacity);
            
            // Add the new row to the DataTable
            const rowNode = classesTable.row.add([
                '', // Index will be auto-assigned
                classData.name,
                sectionName,
                classData.total_capacity,
                remainingCapacity,
                statusBadge,
                `<button class="text-indigo-600 hover:text-indigo-900 font-medium editClassBtn" 
                    data-id="${classData.id}" 
                    data-name="${classData.name}" 
                    data-section="${classData.section_id || ''}" 
                    data-capacity="${classData.total_capacity}" 
                    data-status="${classData.status}">Edit</button>
                <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteClassBtn" 
                    data-id="${classData.id}" 
                    data-name="${classData.name}">Delete</button>`
            ]).draw().node();
            
            // Add event listeners to the new buttons
            $(rowNode).find('.editClassBtn').click(function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const sectionId = $(this).data('section') || '';
                const capacity = $(this).data('capacity');
                const status = $(this).data('status');
                
                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_section_id').val(sectionId);
                $('#edit_total_capacity').val(capacity);
                $('#edit_status').prop('checked', status == 1);
                
                $('#editClassModal').removeClass('hidden');
            });
            
            $(rowNode).find('.deleteClassBtn').click(function() {
                deleteClassId = $(this).data('id');
                const className = $(this).data('name');
                deleteClassBtnRow = $(this).closest('tr');
                $('#deleteClassName').text(className);
                $('#deleteClassModal').removeClass('hidden');
            });
        }
        
        // Function to update a row in the DataTable
        function updateRowInTable(classData) {
            // Find the row with the matching ID
            const rows = classesTable.rows().nodes();
            for (let i = 0; i < rows.length; i++) {
                const row = $(rows[i]);
                const editBtn = row.find('.editClassBtn');
                if (editBtn.data('id') == classData.id) {
                    // Get section name
                    const sectionName = classData.section ? classData.section.name : 'N/A';
                    
                    // Update the class name cell
                    classesTable.cell(row, 1).data(classData.name).draw(false);
                    
                    // Update the section cell
                    classesTable.cell(row, 2).data(sectionName).draw(false);
                    
                    // Update the total capacity cell
                    classesTable.cell(row, 3).data(classData.total_capacity).draw(false);
                    
                    // Update the status cell
                    const statusBadge = `
                        <span class="bg-${classData.status ? 'green' : 'red'}-100 text-${classData.status ? 'green' : 'red'}-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            ${classData.status ? 'Enabled' : 'Disabled'}
                        </span>
                    `;
                    classesTable.cell(row, 5).data(statusBadge).draw(false);
                    
                    // Update the data attributes of the edit button
                    editBtn.data('name', classData.name);
                    editBtn.data('section', classData.section_id || '');
                    editBtn.data('capacity', classData.total_capacity);
                    editBtn.data('status', classData.status);
                    
                    // Update the data attributes of the delete button
                    row.find('.deleteClassBtn').data('name', classData.name);
                    
                    break;
                }
            }
        }
        
        // Function to remove a row from the DataTable
        function removeRowFromTable(classId) {
            const rows = classesTable.rows().nodes();
            for (let i = 0; i < rows.length; i++) {
                const row = $(rows[i]);
                const editBtn = row.find('.editClassBtn');
                if (editBtn.data('id') == classId) {
                    classesTable.row(row).remove().draw(false);
                    break;
                }
            }
        }
    });
</script>
