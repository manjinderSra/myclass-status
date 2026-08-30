@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Fees / <span class="text-l text-gray-500">Fees Groups</span>
            </h1>
            <button id="openCreateFeesGroupModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Add Fees Group +
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <table id="feesGroupsTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">ID</th>
                        <th class="px-6 py-3 font-semibold">Fees Group</th>
                        <th class="px-6 py-3 font-semibold">Description</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    <tr data-id="FG80482" data-fees_group="Class 1-III Installment" data-description="The money that you pay to be taught" data-status="Active">
                        <td class="px-6 py-4">FG80482</td>
                        <td class="px-6 py-4">Class 1-III Installment</td>
                        <td class="px-6 py-4">The money that you pay to be taught</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                        </td>
                        <td class="px-6 py-4">
                            <button class="text-indigo-600 hover:text-indigo-900 font-medium editFeesGroupBtn" data-id="FG80482">Edit</button>
                            <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteFeesGroupBtn" data-id="FG80482">Delete</button>
                        </td>
                    </tr>
                    <tr data-id="FG80477" data-fees_group="Discount" data-description="Discount for early payment" data-status="Inactive">
                        <td class="px-6 py-4">FG80477</td>
                        <td class="px-6 py-4">Discount</td>
                        <td class="px-6 py-4">Discount for early payment</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                        </td>
                        <td class="px-6 py-4">
                            <button class="text-indigo-600 hover:text-indigo-900 font-medium editFeesGroupBtn" data-id="FG80477">Edit</button>
                            <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteFeesGroupBtn" data-id="FG80477">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Fees Group Modal --}}
<div id="createFeesGroupModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Add Fees Group</h2>
        <button type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" id="closeCreateFeesGroupModalX">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <form id="createFeesGroupForm">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Fees Group</label>
                <input type="text" name="fees_group" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="mb-6 flex items-center justify-between">
                <label class="font-medium text-gray-700">Status</label>
                <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                    <input type="checkbox" name="status" id="createFeesGroupStatus" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                    <label for="createFeesGroupStatus" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400" id="closeCreateFeesGroupModalBtn">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Add Fees Group</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Fees Group Modal --}}
<div id="editFeesGroupModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Edit Fees Group</h2>
        <button type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" id="closeEditFeesGroupModalX">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <form id="editFeesGroupForm">
            @csrf
            <input type="hidden" name="edit_id" />

            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Fees Group</label>
                <input type="text" name="edit_fees_group" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Description</label>
                <textarea name="edit_description" rows="3" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="mb-6 flex items-center justify-between">
                <label class="font-medium text-gray-700">Status</label>
                <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                    <input type="checkbox" name="edit_status" id="editFeesGroupStatus" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                    <label for="editFeesGroupStatus" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400" id="closeEditFeesGroupModalBtn">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Update Fees Group</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteFeesGroupModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
        <p class="text-gray-700 mb-6">Are you sure you want to delete this fees group?</p>
        <div class="flex justify-end space-x-4">
            <button type="button" id="closeDeleteFeesGroupModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="confirmDeleteFeesGroupBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

{{-- DataTables CDN --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

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

    /* Custom toggle switch styles */
    .toggle-checkbox {
        /* Hide default checkbox */
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-label {
        /* Style the track */
        display: block;
        width: 40px; /* Adjust as needed */
        height: 24px; /* Adjust as needed */
        border-radius: 9999px; /* Fully rounded */
        background-color: #d1d5db; /* Gray-300 */
        cursor: pointer;
        position: relative; /* For the circle */
        transition: background-color 0.2s ease-in-out;
    }

    .toggle-label:after {
        /* Style the circle/handle */
        content: '';
        position: absolute;
        top: 2px; /* Adjust to center vertically */
        left: 2px; /* Adjust to start from left */
        width: 20px; /* Adjust as needed */
        height: 20px; /* Adjust as needed */
        border-radius: 9999px; /* Fully rounded */
        background-color: #ffffff; /* White */
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); /* Small shadow */
        transition: transform 0.2s ease-in-out;
    }

    .toggle-checkbox:checked + .toggle-label {
        background-color: #2563eb; /* Blue-600 */
    }

    .toggle-checkbox:checked + .toggle-label:after {
        transform: translateX(16px); /* Move 40px (width) - 20px (circle width) - 2px (left padding) - 2px (right padding) = 16px */
    }
</style>

<script>
    $(document).ready(function () {
        // Add CSRF token to AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Initialize DataTable for Fees Groups
        const feesGroupsDataTable = $('#feesGroupsTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search fees groups..."
            },
            lengthMenu: [5, 10, 25, 50],
            pageLength: 5,
            dom:
                "<'flex justify-between items-center mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
                "t" +
                "<'flex justify-between items-center mt-4'<'dataTables_info'i><'dataTables_paginate'p>>",
            columnDefs: [
                { "targets": [0], "visible": false } // Hide ID column
            ]
        });

        const createFeesGroupModal = $('#createFeesGroupModal');
        const editFeesGroupModal = $('#editFeesGroupModal');
        const deleteFeesGroupModal = $('#deleteFeesGroupModal');

        // --- Create Fees Group Modal handlers ---
        $('#openCreateFeesGroupModal').click(() => {
            createFeesGroupModal.removeClass('hidden');
            $('#createFeesGroupForm')[0].reset(); // Clear form on open
            $('#createFeesGroupStatus').prop('checked', true); // Default to active
        });

        $('#closeCreateFeesGroupModalX, #closeCreateFeesGroupModalBtn').click(() => {
            createFeesGroupModal.addClass('hidden');
            $('#createFeesGroupForm')[0].reset();
        });

        $('#createFeesGroupForm').submit(function (e) {
            e.preventDefault();
            const feesGroup = $(this).find('input[name="fees_group"]').val();
            const description = $(this).find('textarea[name="description"]').val();
            const status = $('#createFeesGroupStatus').is(':checked') ? 1 : 0;

            // Create form data for submission
            const formData = new FormData();
            formData.append('fees_group', feesGroup);
            formData.append('description', description);
            formData.append('status', status);
            
            // Send AJAX request to create fee group
            $.ajax({
                url: "{{ route('school.feeGroup.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        alert(response.message || "Fee group created successfully");
                        createFeesGroupModal.addClass('hidden');
                        $('#createFeesGroupForm')[0].reset();
                        
                        // Reload data
                        loadFeeGroups();
                    } else {
                        alert(response.message || "Failed to create fee group");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred while creating the fee group";
                    alert(errorMsg);
                    
                    // Show validation errors if any
                    if (xhr.responseJSON?.errors) {
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            alert(errors[field][0]);
                        }
                    }
                }
            });
        });

        // --- Edit Fees Group Modal handlers ---
        // Delegated event for dynamically added rows
        $(document).on('click', '.editFeesGroupBtn', function () {
            const feeGroupId = $(this).data('id');
            
            // Fetch fee group details for editing
            $.ajax({
                url: `/school/feeGroup/${feeGroupId}`,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success && response.feeGroup) {
                        const feeGroup = response.feeGroup;
                        
                        // Populate form fields
                        $('#editFeesGroupForm [name="edit_id"]').val(feeGroup.id);
                        $('#editFeesGroupForm [name="edit_fees_group"]').val(feeGroup.name);
                        $('#editFeesGroupForm [name="edit_description"]').val(feeGroup.description);
                        $('#editFeesGroupStatus').prop('checked', feeGroup.status === 1);
                        
                        // Show modal
                        editFeesGroupModal.removeClass('hidden');
                    } else {
                        alert(response.message || "Failed to fetch fee group data");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred while fetching fee group data";
                    alert(errorMsg);
                }
            });
        });

        $('#closeEditFeesGroupModalX, #closeEditFeesGroupModalBtn').click(() => {
            editFeesGroupModal.addClass('hidden');
            $('#editFeesGroupForm')[0].reset();
        });

        $('#editFeesGroupForm').submit(function (e) {
            e.preventDefault();
            const id = $(this).find('input[name="edit_id"]').val();
            const feesGroup = $(this).find('input[name="edit_fees_group"]').val();
            const description = $(this).find('textarea[name="edit_description"]').val();
            const status = $('#editFeesGroupStatus').is(':checked') ? 1 : 0;

            // Create form data for submission
            const formData = new FormData();
            formData.append('fees_group', feesGroup);
            formData.append('description', description);
            formData.append('status', status);
            formData.append('_method', 'PUT'); // For method spoofing
            
            // Send AJAX request to update fee group
            $.ajax({
                url: `/school/feeGroup/${id}`,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        alert(response.message || "Fee group updated successfully");
                        editFeesGroupModal.addClass('hidden');
                        
                        // Reload data
                        loadFeeGroups();
                    } else {
                        alert(response.message || "Failed to update fee group");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred while updating the fee group";
                    alert(errorMsg);
                    
                    // Show validation errors if any
                    if (xhr.responseJSON?.errors) {
                        const errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            alert(errors[field][0]);
                        }
                    }
                }
            });
        });

        // --- Delete Fees Group Modal handlers ---
        let deleteFeesGroupId = null;
        
        // Delegated event for dynamically added rows
        $(document).on('click', '.deleteFeesGroupBtn', function () {
            deleteFeesGroupId = $(this).data('id');
            deleteFeesGroupModal.removeClass('hidden');
        });

        $('#closeDeleteFeesGroupModal').click(() => deleteFeesGroupModal.addClass('hidden'));

        $('#confirmDeleteFeesGroupBtn').click(function () {
            if (!deleteFeesGroupId) return;
            
            // Send AJAX request to delete fee group
            $.ajax({
                url: `/school/feeGroup/${deleteFeesGroupId}`,
                type: "DELETE",
                success: function(response) {
                    if (response.success) {
                        alert(response.message || "Fee group deleted successfully");
                        deleteFeesGroupModal.addClass('hidden');
                        
                        // Reload data
                        loadFeeGroups();
                    } else {
                        alert(response.message || "Failed to delete fee group");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred while deleting the fee group";
                    alert(errorMsg);
                }
            });
        });
        
        // Function to load fee groups data
        function loadFeeGroups() {
            $.ajax({
                url: "{{ route('school.api.all-fee-groups') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // Clear existing data
                        feesGroupsDataTable.clear();
                        
                        // Add new data
                        if (response.feeGroups && response.feeGroups.length > 0) {
                            response.feeGroups.forEach(function(feeGroup) {
                                const statusValue = feeGroup.status;
                                console.log("Fee Group:", feeGroup.name, "Status:", statusValue, "Type:", typeof statusValue);
                                
                                const statusHtml = statusValue == true || statusValue == 1 || statusValue == "1" ?
                                    `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>` :
                                    `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>`;
                                
                                feesGroupsDataTable.row.add([
                                    feeGroup.id,
                                    feeGroup.name,
                                    feeGroup.description || '',
                                    statusHtml,
                                    `<button class="text-indigo-600 hover:text-indigo-900 font-medium editFeesGroupBtn" data-id="${feeGroup.id}">Edit</button>
                                    <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteFeesGroupBtn" data-id="${feeGroup.id}">Delete</button>`
                                ]).draw(false);
                            });
                        } else {
                            // No data message
                            feesGroupsDataTable.row.add([
                                '',
                                '<div class="text-center text-gray-500 py-4">No fee groups found</div>',
                                '', '', ''
                            ]).draw(false);
                        }
                    } else {
                        alert(response.message || "Failed to load fee groups data");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred while loading fee groups data";
                    alert(errorMsg);
                }
            });
        }
        
        // Initial load of fee groups data
        loadFeeGroups();
    });
</script>