@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Academics / <span class="text-l text-gray-500">Sections</span>
            </h1>
            <button id="openSectionModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Create Section +
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
            <table id="sectionsTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Section Name</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Created At</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @foreach($sections as $index => $section)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">{{ $section->name }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-{{ $section->status ? 'green' : 'red' }}-100 text-{{ $section->status ? 'green' : 'red' }}-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $section->status ? 'Enabled' : 'Disabled' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $section->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">
                            <button class="text-indigo-600 hover:text-indigo-900 font-medium editSectionBtn" 
                                data-id="{{ $section->id }}" 
                                data-name="{{ $section->name }}" 
                                data-status="{{ $section->status }}">Edit</button>
                            <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteSectionBtn" 
                                data-id="{{ $section->id }}" 
                                data-name="{{ $section->name }}">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create Section Modal --}}
<div id="sectionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Create Section</h2>
        <form id="sectionForm">
            @csrf
            <label class="block mb-2 font-medium text-gray-700">Section Name</label>
            <input type="text" name="name" required placeholder="A, B, C..." class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />

            <label class="flex items-center mb-4">
                <input type="checkbox" name="status" class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="text-gray-700">Enable this section</span>
            </label>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeSectionModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Section Modal --}}
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Edit Section</h2>
        <form id="editForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_id" name="edit_id" />
            <label class="block mb-2 font-medium text-gray-700">Section Name</label>
            <input type="text" id="edit_name" name="name" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="flex items-center mb-4">
                <input type="checkbox" id="edit_status" name="status" class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded">
                <span class="text-gray-700">Enable this section</span>
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
        <p class="text-gray-700 mb-6">Are you sure you want to delete section "<span id="deleteSectionName" class="font-semibold"></span>"? This action cannot be undone.</p>
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
        let sectionsTable = $('#sectionsTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search sections..."
            },
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
            dom:
                "<'flex justify-between items-center mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
                "t" +
                "<'flex justify-between items-center mt-4'<'dataTables_info'i><'dataTables_paginate'p>>",
        });

        // Open/close modals
        $('#openSectionModal').click(() => {
            $('#sectionForm')[0].reset();
            $('#sectionModal').removeClass('hidden');
        });
        $('#closeSectionModal').click(() => $('#sectionModal').addClass('hidden'));
        $('#closeEditModal').click(() => $('#editModal').addClass('hidden'));
        $('#closeDeleteModal').click(() => $('#deleteModal').addClass('hidden'));

        // Create section
        $('#sectionForm').submit(function (e) {
            e.preventDefault();
            
            const isChecked = $(this).find('input[name="status"]').is(':checked');
            
            const formData = {
                name: $(this).find('input[name="name"]').val().trim(),
                status: isChecked ? 1 : 0,
                _token: $('meta[name="csrf-token"]').attr('content')
            };
            
            console.log('Creating section with data:', formData);
            
            $.ajax({
                url: "{{ route('school.sections.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Show toast message and close modal
                        toastr.success(response.message);
                        $('#sectionModal').addClass('hidden');
                        
                        // Reset form
                        $('#sectionForm')[0].reset();
                        
                        // Add new row to DataTable
                        addRowToTable(response.section);
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

        // Edit section
        $('.editSectionBtn').click(function () {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const status = $(this).data('status');

            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_status').prop('checked', status == 1);

            $('#editModal').removeClass('hidden');
        });

        // Update section
        $('#editForm').submit(function (e) {
            e.preventDefault();
            
            const id = $('#edit_id').val();
            const name = $('#edit_name').val().trim();
            const isChecked = $('#edit_status').is(':checked');
            
            const formData = {
                name: name,
                status: isChecked ? 1 : 0,
                _token: $('meta[name="csrf-token"]').attr('content'),
                _method: 'PUT'
            };
            
            console.log('Sending form data:', formData);
            
            $.ajax({
                url: `/school/sections/${id}`,
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Show toast message and close modal
                        toastr.success(response.message);
                        $('#editModal').addClass('hidden');
                        
                        // Update row in DataTable
                        updateRowInTable(response.section);
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

        // Delete section
        let deleteId = null;
        let deleteBtnRow = null;
        
        $('.deleteSectionBtn').click(function () {
            deleteId = $(this).data('id');
            const sectionName = $(this).data('name');
            deleteBtnRow = $(this).closest('tr');
            $('#deleteSectionName').text(sectionName);
            $('#deleteModal').removeClass('hidden');
        });

        $('#confirmDeleteBtn').click(function () {
            if (!deleteId) return;
            
            $.ajax({
                url: `/school/sections/${deleteId}`,
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
                        removeRowFromTable(deleteId);
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
        function addRowToTable(section) {
            // Create the status badge HTML
            const statusBadge = `
                <span class="bg-${section.status ? 'green' : 'red'}-100 text-${section.status ? 'green' : 'red'}-800 text-xs font-medium px-2.5 py-0.5 rounded">
                    ${section.status ? 'Enabled' : 'Disabled'}
                </span>
            `;
            
            // Format the created date
            const createdDate = new Date(section.created_at);
            const formattedDate = createdDate.toISOString().split('T')[0]; // YYYY-MM-DD format
            
            // Add the new row to the DataTable
            const rowNode = sectionsTable.row.add([
                '', // Index will be auto-assigned
                section.name,
                statusBadge,
                formattedDate,
                `<button class="text-indigo-600 hover:text-indigo-900 font-medium editSectionBtn" 
                    data-id="${section.id}" 
                    data-name="${section.name}" 
                    data-status="${section.status}">Edit</button>
                <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteSectionBtn" 
                    data-id="${section.id}" 
                    data-name="${section.name}">Delete</button>`
            ]).draw().node();
            
            // Add event listeners to the new buttons
            $(rowNode).find('.editSectionBtn').click(function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const status = $(this).data('status');
                
                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_status').prop('checked', status == 1);
                
                $('#editModal').removeClass('hidden');
            });
            
            $(rowNode).find('.deleteSectionBtn').click(function() {
                deleteId = $(this).data('id');
                const sectionName = $(this).data('name');
                deleteBtnRow = $(this).closest('tr');
                $('#deleteSectionName').text(sectionName);
                $('#deleteModal').removeClass('hidden');
            });
        }
        
        // Function to update a row in the DataTable
        function updateRowInTable(section) {
            // Find the row with the matching ID
            const rows = sectionsTable.rows().nodes();
            for (let i = 0; i < rows.length; i++) {
                const row = $(rows[i]);
                const editBtn = row.find('.editSectionBtn');
                if (editBtn.data('id') == section.id) {
                    // Update the section name cell
                    sectionsTable.cell(row, 1).data(section.name).draw(false);
                    
                    // Update the status cell
                    const statusBadge = `
                        <span class="bg-${section.status ? 'green' : 'red'}-100 text-${section.status ? 'green' : 'red'}-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            ${section.status ? 'Enabled' : 'Disabled'}
                        </span>
                    `;
                    sectionsTable.cell(row, 2).data(statusBadge).draw(false);
                    
                    // Update the data attributes of the edit button
                    editBtn.data('name', section.name);
                    editBtn.data('status', section.status);
                    
                    // Update the data attributes of the delete button
                    row.find('.deleteSectionBtn').data('name', section.name);
                    
                    break;
                }
            }
        }
        
        // Function to remove a row from the DataTable
        function removeRowFromTable(sectionId) {
            const rows = sectionsTable.rows().nodes();
            for (let i = 0; i < rows.length; i++) {
                const row = $(rows[i]);
                const editBtn = row.find('.editSectionBtn');
                if (editBtn.data('id') == sectionId) {
                    sectionsTable.row(row).remove().draw(false);
                    break;
                }
            }
        }
    });
</script>
