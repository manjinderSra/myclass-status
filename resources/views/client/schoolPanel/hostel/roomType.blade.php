@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                Hostels / <span class="text-l text-gray-500">Room Types</span>
            </h1>
            <button id="openRoomModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Create Room Type +
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
            <table id="roomsTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">S no.</th>
                        <th class="px-6 py-3 font-semibold">Room Type</th>
                        <th class="px-6 py-3 font-semibold">Description</th>
                        <th class="px-6 py-3 font-semibold">Price</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Created At</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @if(isset($roomTypes) && count($roomTypes) > 0)
                        @foreach($roomTypes as $room)
                            <tr class="hover:bg-gray-50 transition-colors" 
                            
                                data-id="{{ $room->id }}" data-type="{{ $room->name }}" 
                                data-description="{{ $room->description ?? '' }}" 
                                data-price="{{ $room->price ?? 0 }}"
                                data-status="{{ $room->status }}">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>

                                <td class="px-6 py-4">{{ $room->name }}</td>
                                <td class="px-6 py-4">{{ $room->description }}</td>
                                <td class="px-6 py-4">{{ $room->price }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $room->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $room->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $room->created_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4">
                                    <button class="text-indigo-600 hover:text-indigo-900 font-medium editRoomBtn">Edit</button>
                                    <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteRoomBtn">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center">No room types found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create Room Type Modal --}}
<div id="roomModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Create Room Type</h2>
        <form id="roomForm" method="POST" action="{{ route('school.roomType.store') }}">
            @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
            <label class="block mb-2 font-medium text-gray-700">Room Type Name</label>
            <input type="text" name="name" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">Description (Optional)</label>
            <textarea name="description" class="w-full px-3 py-2 border rounded mb-4"></textarea>
            
            <label class="block mb-2 font-medium text-gray-700">Price</label>
            <input type="number" name="price" required min="0" step="0.01" class="w-full px-3 py-2 border rounded mb-4" />
            
            <label class="flex items-center mb-4">
                <input type="checkbox" name="status" value="1" class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <span class="text-gray-700">Active</span>
            </label>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeRoomModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Room Type Modal --}}
<div id="editRoomModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Edit Room Type</h2>
        <form id="editRoomForm" method="POST">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" />
            
            <label class="block mb-2 font-medium text-gray-700">Room Type Name</label>
            <input type="text" name="name" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">Description (Optional)</label>
            <textarea name="description" class="w-full px-3 py-2 border rounded mb-4"></textarea>
            
            <label class="block mb-2 font-medium text-gray-700">Price</label>
            <input type="number" name="price" required min="0" step="0.01" class="w-full px-3 py-2 border rounded mb-4" />
            
            <label class="block mb-2 font-medium text-gray-700">Status</label>
            <div class="flex items-center mb-4">
                <input type="checkbox" name="status" value="1" class="w-5 h-5 text-blue-600 rounded" />
                <span class="ml-2">Active</span>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeEditRoomModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Room Type Modal --}}
<div id="deleteRoomModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
        <p class="text-gray-700 mb-6">Are you sure you want to delete <span id="deleteRoomName" class="font-semibold"></span>?</p>
        <div class="flex justify-end space-x-4">
            <button type="button" id="closeDeleteRoomModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="confirmDeleteRoomBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

{{-- DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

{{-- Toastr --}}
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
            "timeOut": "3000",
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
        let roomTable = $('#roomsTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search room types...",
                emptyTable: "No room types found",
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

        // Helper function to refresh table data
        function refreshRoomTable() {
            // Show loading indicator
            $('#roomsTable tbody').html('<tr><td colspan="6" class="text-center py-4"><i class="fas fa-spinner fa-spin mr-2"></i> Loading...</td></tr>');
            
            $.ajax({
                url: "{{ route('school.api.all-room-types') }}",
                type: "GET",
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        try {
                            // Get current page info before destroying table
                            let currentPage = 0;
                            if ($.fn.DataTable.isDataTable('#roomsTable')) {
                                currentPage = roomTable.page();
                                roomTable.destroy();
                            }
                            
                            // Clear the table
                            const tbody = $('#roomsTable tbody');
                            tbody.empty();
                            
                            // Add new rows
                            if (response.roomTypes && response.roomTypes.length > 0) {
                                response.roomTypes.forEach(function(room) {
                                    const statusClass = room.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                    const statusText = room.status ? 'Active' : 'Inactive';
                                    
                                    // Format date
                                    const createdDate = new Date(room.created_at);
                                    const formattedDate = createdDate.toISOString().split('T')[0]; // YYYY-MM-DD
                                    
                                    console.log("Room data:", room); // Debug room data
                                    let rowNumber = tbody.children().length + 1;

                                    tbody.append(`
                                        <tr class="hover:bg-gray-50 transition-colors" 
                                            data-id="${room.id}" 
                                            data-name="${room.name}" 
                                            data-description="${room.description || ''}"
                                            data-price="${room.price || 0}"
                                            data-status="${room.status ? 1 : 0}">
                                            <td class="px-6 py-4">${rowNumber}</td>
                                            <td class="px-6 py-4">${room.name}</td>
                                            <td class="px-6 py-4">${room.description || '-'}</td>
                                            <td class="px-6 py-4">₹ ${room.price}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                                    ${statusText}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">${formattedDate}</td>
                                            <td class="px-6 py-4">
                                                <button class="text-indigo-600 hover:text-indigo-900 font-medium editRoomBtn">Edit</button>
                                                <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteRoomBtn">Delete</button>
                                            </td>
                                        </tr>
                                    `);
                                });
                            } else {
                                tbody.append('<tr><td colspan="6" class="px-6 py-4 text-center">No room types found</td></tr>');
                            }
                            
                            // Reinitialize DataTable
                            roomTable = $('#roomsTable').DataTable({
                                language: {
                                    search: "",
                                    searchPlaceholder: "Search room types...",
                                    emptyTable: "No room types found",
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
                            
                            // Go back to previous page if possible
                            if (currentPage && roomTable.page.info().pages > currentPage) {
                                roomTable.page(currentPage).draw('page');
                            }
                            
                            // Reinitialize button events
                            initializeButtonEvents();
                            
                            console.log("Table refreshed successfully");
                        } catch (error) {
                            console.error("Error refreshing table:", error);
                            toastr.error("Error refreshing table data");
                        }
                    } else {
                        toastr.error(response.message || "Failed to fetch room type data");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    toastr.error("Failed to refresh table data");
                    $('#roomsTable tbody').html('<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Error loading data</td></tr>');
                }
            });
        }
        
        // Add let deleteRoomId declaration
        let deleteRoomId;
        
        // Initialize button event handlers
        function initializeButtonEvents() {
            // Edit Room Type Button click event
            $('.editRoomBtn').off('click').on('click', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');
                const name = row.data('name');
                const description = row.data('description');
                const price = row.data('price');
                const status = row.data('status');

                console.log("Edit button clicked for:", {id, name, description, price, status});

                $('#editRoomForm input[name="id"]').val(id);
                $('#editRoomForm input[name="name"]').val(name);
                $('#editRoomForm textarea[name="description"]').val(description);
                $('#editRoomForm input[name="price"]').val(price);
                $('#editRoomForm input[name="status"]').prop('checked', status == 1);
                
                // Set the form action
                $('#editRoomForm').attr('action', `/school/roomType/${id}`);

                $('#editRoomModal').removeClass('hidden');
            });
            
            // Delete Room Type Button click event
            $('.deleteRoomBtn').off('click').on('click', function() {
                const row = $(this).closest('tr');
                const id = row.data('id');
                const name = row.data('name');
                
                deleteRoomId = id;
                $('#deleteRoomName').text(name);
                $('#deleteRoomModal').removeClass('hidden');
            });
        }
        
        // Initial setup of button events
        initializeButtonEvents();

        // Initial data load
        refreshRoomTable();
        
        // Debug what's happening
        console.log("Debug: Document ready function executed");
        console.log("Debug: openRoomModal element exists?", document.getElementById('openRoomModal') !== null);
        console.log("Debug: roomModal element exists?", document.getElementById('roomModal') !== null);
        
        // Multiple approaches to ensure the button works
        
        // Approach 1: Direct DOM event listener with setTimeout to ensure DOM is fully loaded
        setTimeout(function() {
            const openButton = document.getElementById('openRoomModal');
            const closeButton = document.getElementById('closeRoomModal');
            const modal = document.getElementById('roomModal');
            
            console.log("Debug: After timeout - Elements found?", {
                openButton: openButton !== null,
                closeButton: closeButton !== null,
                modal: modal !== null
            });
            
            if (openButton && modal) {
                openButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log("Debug: Open button clicked via direct event listener");
                    modal.classList.remove('hidden');
                });
            }
            
            if (closeButton && modal) {
                closeButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log("Debug: Close button clicked via direct event listener");
                    modal.classList.add('hidden');
                });
            }
        }, 500);
        
        // Approach 2: Redefine the jQuery click handlers
        $('#openRoomModal').off('click').on('click', function(e) {
            e.preventDefault();
            console.log("Debug: Open button clicked via jQuery");
            $('#roomModal').removeClass('hidden');
            $('#roomForm')[0].reset();
        });
        
        $('#closeRoomModal').off('click').on('click', function(e) {
            e.preventDefault();
            console.log("Debug: Close button clicked via jQuery");
            $('#roomModal').addClass('hidden');
        });
        
        // Approach 3: Use document delegation for dynamically added elements
        $(document).on('click', '#openRoomModal', function(e) {
            e.preventDefault();
            console.log("Debug: Open button clicked via document delegation");
            $('#roomModal').removeClass('hidden');
            $('#roomForm')[0].reset();
        });
        
        $(document).on('click', '#closeRoomModal', function(e) {
            e.preventDefault();
            console.log("Debug: Close button clicked via document delegation");
            $('#roomModal').addClass('hidden');
        });

        // Create room type form submission
        $('#roomForm').submit(function(e) {
            e.preventDefault();
            console.log("Form submitted");
            
            // Disable submit button to prevent double submission
            $('#roomForm button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
            
            const formData = new FormData(this);
            console.log("Form data:", formData);
            
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    console.log("Success response:", response);
                    if (response.success) {
                        toastr.success(response.message || 'Room type created successfully');
                        $('#roomModal').addClass('hidden');
                        $('#roomForm')[0].reset();
                        
                        // Refresh table data
                        refreshRoomTable();
                    } else {
                        toastr.error(response.message || 'Failed to create room type');
                        console.error("Server returned error:", response);
                    }
                    // Re-enable submit button
                    $('#roomForm button[type="submit"]').prop('disabled', false).html('Save');
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    console.error("Response Text:", xhr.responseText);
                    console.error("Status Code:", xhr.status);
                    
                    try {
                        // Try to parse error response
                        const errorResponse = JSON.parse(xhr.responseText);
                        console.log("Parsed error response:", errorResponse);
                        
                        if (errorResponse.errors) {
                            // Display each validation error
                            Object.keys(errorResponse.errors).forEach(field => {
                                const errorMsg = errorResponse.errors[field][0];
                                toastr.error(`${field}: ${errorMsg}`);
                                console.error(`Validation error for ${field}: ${errorMsg}`);
                            });
                        } else if (errorResponse.message) {
                            toastr.error(errorResponse.message);
                            console.error("Error message:", errorResponse.message);
                            
                            // Special case for school not found error
                            if (errorResponse.message.includes('School not found')) {
                                toastr.error('You must be logged in as a school admin to create room types', 'Authentication Error');
                            }
                        } else {
                            toastr.error("An unknown error occurred");
                        }
                    } catch (e) {
                        // If we can't parse the JSON, just show the raw response
                        toastr.error("An error occurred while creating the room type");
                        console.error("Error parsing response:", e);
                        
                        // Special handling for specific HTTP status codes
                        if (xhr.status === 404) {
                            toastr.error("Route not found. Please check your application routes.", "404 Not Found");
                        } else if (xhr.status === 500) {
                            toastr.error("Server error occurred. Please check the server logs.", "500 Server Error");
                        } else if (xhr.status === 403) {
                            toastr.error("You don't have permission to perform this action.", "403 Forbidden");
                        } else if (xhr.status === 401) {
                            toastr.error("You must be logged in to perform this action.", "401 Unauthorized");
                            // Redirect to login page after a delay
                            setTimeout(function() {
                                window.location.href = "{{ route('login') }}";
                            }, 2000);
                        }
                    }
                    
                    // Re-enable submit button
                    $('#roomForm button[type="submit"]').prop('disabled', false).html('Save');
                }
            });
        });
        
        $('#closeEditRoomModal').click(function() {
            $('#editRoomModal').addClass('hidden');
        });

        // Update room type form submission
        $('#editRoomForm').submit(function(e) {
            e.preventDefault();
            console.log("Edit form submitted");
            
            const id = $('input[name="id"]').val();
            
            // Set the form action dynamically
            $(this).attr('action', `/school/roomType/${id}`);
            
            // Disable submit button to prevent double submission
            $('#editRoomForm button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
            
            const formData = new FormData(this);
            console.log("Edit form data:", formData);
            
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    console.log("Edit success response:", response);
                    if (response.success) {
                        toastr.success(response.message || 'Room type updated successfully');
                        $('#editRoomModal').addClass('hidden');
                        
                        // Refresh table data
                        refreshRoomTable();
                    } else {
                        toastr.error(response.message || 'Failed to update room type');
                        console.error("Server returned error:", response);
                    }
                    // Re-enable submit button
                    $('#editRoomForm button[type="submit"]').prop('disabled', false).html('Update');
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    console.error("Response Text:", xhr.responseText);
                    console.error("Status Code:", xhr.status);
                    
                    try {
                        // Try to parse error response
                        const errorResponse = JSON.parse(xhr.responseText);
                        if (errorResponse.errors) {
                            // Display each validation error
                            Object.keys(errorResponse.errors).forEach(field => {
                                const errorMsg = errorResponse.errors[field][0];
                                toastr.error(`${field}: ${errorMsg}`);
                            });
                        } else if (errorResponse.message) {
                            toastr.error(errorResponse.message);
                        } else {
                            toastr.error("An unknown error occurred");
                        }
                    } catch (e) {
                        toastr.error("An error occurred while updating the room type");
                    }
                    
                    // Re-enable submit button
                    $('#editRoomForm button[type="submit"]').prop('disabled', false).html('Update');
                }
            });
        });
        
        $('#closeDeleteRoomModal').click(function() {
            $('#deleteRoomModal').addClass('hidden');
        });

        // Confirm delete room type
        $('#confirmDeleteRoomBtn').click(function() {
            if (!deleteRoomId) return;
            
            // Disable button to prevent double deletion
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = $('input[name="_token"]').val();
            form.appendChild(csrfToken);
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            const formData = new FormData(form);
            
            $.ajax({
                url: `/school/roomType/${deleteRoomId}`,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    console.log("Delete response:", response);
                    if (response.success) {
                        toastr.success(response.message || 'Room type deleted successfully');
                        $('#deleteRoomModal').addClass('hidden');
                        
                        // Refresh table data
                        refreshRoomTable();
                    } else {
                        toastr.error(response.message || 'Failed to delete room type');
                        console.error("Server returned error:", response);
                    }
                    // Re-enable button
                    $('#confirmDeleteRoomBtn').prop('disabled', false).html('Delete');
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    console.error("Response Text:", xhr.responseText);
                    
                    let errorMessage = "An error occurred while deleting the room type";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error(errorMessage);
                    
                    // Re-enable button
                    $('#confirmDeleteRoomBtn').prop('disabled', false).html('Delete');
                }
            });
        });
    });
</script>

{{-- Inline script as final fallback --}}
<script>
    // Fallback solution that runs after page load
    window.addEventListener('load', function() {
        console.log("Debug: Window load event fired");
        
        // Direct click handlers as fallback
        document.querySelectorAll('#openRoomModal').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                console.log("Debug: Open button clicked via window.onload handler");
                document.querySelectorAll('#roomModal').forEach(function(modal) {
                    modal.classList.remove('hidden');
                });
            });
        });
        
        document.querySelectorAll('#closeRoomModal').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                console.log("Debug: Close button clicked via window.onload handler");
                document.querySelectorAll('#roomModal').forEach(function(modal) {
                    modal.classList.add('hidden');
                });
            });
        });
    });
</script>

{{-- Debugging Helper --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM fully loaded and parsed");
    
    // Add event listeners to all form elements in the create modal
    const formInputs = document.querySelectorAll('#roomForm input, #roomForm textarea, #roomForm select');
    formInputs.forEach(input => {
        console.log("Found form element:", input.name);
        input.addEventListener('change', function() {
            console.log(`Form element ${input.name} changed to:`, input.value);
        });
    });
    
    // Intercept form submission to examine data
    const form = document.getElementById('roomForm');
    if (form) {
        console.log("Found roomForm");
        form.addEventListener('submit', function(e) {
            // Don't prevent default - let the jQuery handler work
            
            // Log all form values
            const formData = new FormData(form);
            console.log("Form submission intercepted. Form data:");
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            // Log other relevant information
            console.log("CSRF token:", document.querySelector('input[name="_token"]').value);
            console.log("Form action URL:", "{{ route('school.roomType.store') }}");
        });
    }
});
</script>

<script>
    // Add additional AJAX monitoring
    $(document).ajaxSend(function(event, jqxhr, settings) {
        console.log("AJAX request being sent:", {
            url: settings.url,
            type: settings.type,
            data: settings.data
        });
    });

    $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
        console.error("AJAX request failed:", {
            url: settings.url,
            type: settings.type,
            status: jqxhr.status,
            statusText: jqxhr.statusText,
            responseText: jqxhr.responseText
        });
    });

    $(document).ajaxComplete(function(event, jqxhr, settings) {
        console.log("AJAX request completed:", {
            url: settings.url,
            type: settings.type,
            status: jqxhr.status,
            statusText: jqxhr.statusText
        });
    });
</script>
