@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Hostels / <span class="text-l text-gray-500">Hostel Rooms</span>
            </h1>
            <div class="flex space-x-2">
                {{-- <button type="button" id="showDemoBtn" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    Show Demo Data
                </button> --}}
            <button type="button" id="openRoomModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Create Room +
            </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <table id="roomsTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">S no.</th>
                        <th class="px-6 py-3 font-semibold">Room No</th>
                        <th class="px-6 py-3 font-semibold">Hostel</th>
                        <th class="px-6 py-3 font-semibold">Room Type</th>
                        <th class="px-6 py-3 font-semibold">Beds</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    <!-- Table content will be loaded dynamically -->
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create Room Modal --}}
<div id="roomModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" style="z-index: 9999;">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Create Room</h2>
        <form id="roomForm" onsubmit="return false;" method="POST">
            @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
            <label class="block mb-2 font-medium text-gray-700">Room No</label>
            <input type="text" name="room_number" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">Hostel</label>
            <select name="hostel_id" required class="w-full px-3 py-2 border rounded mb-4">
                <option value="">Select Hostel</option>
                @foreach($hostels as $hostel)
                    <option value="{{ $hostel->id }}">{{ $hostel->name }}</option>
                @endforeach
            </select>

            <label class="block mb-2 font-medium text-gray-700">Room Type</label>
            <select name="room_type_id" required class="w-full px-3 py-2 border rounded mb-4">
                <option value="">Select Room Type</option>
                @foreach($roomTypes as $roomType)
                    <option value="{{ $roomType->id }}">{{ $roomType->name }} ({{ number_format($roomType->price, 2) }})</option>
                @endforeach
            </select>

            <label class="block mb-2 font-medium text-gray-700">Number of Beds</label>
            <select name="beds" required class="w-full px-3 py-2 border rounded mb-4">
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>

            <label class="block mb-2 font-medium text-gray-700">Description</label>
            <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded mb-4"></textarea>

            <label class="flex items-center mb-4">
                <input type="checkbox" name="status" value="1" checked class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded">
                <span class="text-gray-700">Enable this room</span>
            </label>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeRoomModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="button" id="saveRoomBtn" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Room Modal --}}
<div id="editRoomModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Edit Room</h2>
        <form id="editRoomForm" onsubmit="return false;" method="POST">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" />
            
            <label class="block mb-2 font-medium text-gray-700">Room No</label>
            <input type="text" name="room_number" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">Hostel</label>
            <select name="hostel_id" required class="w-full px-3 py-2 border rounded mb-4">
                <option value="">Select Hostel</option>
                @foreach($hostels as $hostel)
                    <option value="{{ $hostel->id }}">{{ $hostel->name }}</option>
                @endforeach
            </select>

            <label class="block mb-2 font-medium text-gray-700">Room Type</label>
            <select name="room_type_id" required class="w-full px-3 py-2 border rounded mb-4">
                <option value="">Select Room Type</option>
                @foreach($roomTypes as $roomType)
                    <option value="{{ $roomType->id }}">{{ $roomType->name }} ({{ number_format($roomType->price, 2) }})</option>
                @endforeach
            </select>

            <label class="block mb-2 font-medium text-gray-700">Number of Beds</label>
            <select name="beds" required class="w-full px-3 py-2 border rounded mb-4">
                @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>

            <label class="block mb-2 font-medium text-gray-700">Description</label>
            <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded mb-4"></textarea>

            <label class="flex items-center mb-4">
                <input type="checkbox" name="status" value="1" class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded">
                <span class="text-gray-700">Enable this room</span>
            </label>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeEditRoomModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="button" id="updateRoomBtn" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Room Modal --}}
<div id="deleteRoomModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-sm w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
        <p class="text-gray-700 mb-6">Are you sure you want to delete <span id="deleteRoomNumber" class="font-semibold"></span>?</p>
        <form id="deleteRoomForm" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
        </form>
        <div class="flex justify-end space-x-4">
            <button type="button" id="closeDeleteRoomModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400" onclick="$('#deleteRoomModal').addClass('hidden');">Cancel</button>
            <button id="confirmDeleteRoomBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700" onclick="confirmDelete();">Delete</button>
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
    // Global variables
    let roomTable;
    let deleteRoomId;
    
    // Helper function to generate room URLs
    function getRoomUrl(roomId) {
        // Make sure roomId is properly defined
        if (!roomId) {
            console.error("Invalid room ID provided to getRoomUrl:", roomId);
            return '';
        }
        
        // Log the URL we're generating
        const url = "{{ url('/school/hostelRoom') }}/" + roomId;
        console.log("Generated URL for room ID " + roomId + ":", url);
        
        // Return clean URL with proper escaping
        return url.replace(/\s+/g, '');
    }
    
    // Verify jQuery is loaded
    if (typeof jQuery !== 'undefined') {
        console.log("jQuery is loaded, version: " + jQuery.fn.jquery);
    } else {
        console.error("jQuery is NOT loaded!");
        alert("jQuery is not loaded. This may cause the application to malfunction.");
    }

    $(document).ready(function () {
        console.log("Document ready event fired");
        
        // Check if user is authenticated
        const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
        console.log("User authentication status:", isAuthenticated);
        
        // Debug info
        console.log("DEBUG: Current URL:", window.location.href);
        console.log("DEBUG: Using AJAX endpoint:", "{{ route('school.api.all-hostel-rooms') }}");
        
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
        
        // Display a startup message for testing
        toastr.info('Page loaded successfully. JavaScript is working.', 'Ready');
        
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
        
        // Setup modal handlers
        console.log("Setting up modal handlers");
        
        // Create Room button click handler
        $(document).on("click", "#openRoomModal", function() {
            console.log("Create Room button clicked");
            $('#roomForm')[0].reset();
            $('#roomModal').removeClass('hidden');
        });
        
        // Save Room button click handler
        $(document).on("click", "#saveRoomBtn", function() {
            console.log("Save Room button clicked");
            handleRoomFormSubmission();
        });
        
        // Close modal handlers
        $('#closeRoomModal').on("click", function() {
            console.log("Close room modal clicked");
            $('#roomModal').addClass('hidden');
        });
        
        $('#closeEditRoomModal').on("click", function() {
            $('#editRoomModal').addClass('hidden');
        });
        
        $('#closeDeleteRoomModal').on("click", function() {
            $('#deleteRoomModal').addClass('hidden');
        });
        
        // Update Room button click handler
        $(document).on("click", "#updateRoomBtn", function() {
            console.log("Update Room button clicked");
            handleRoomUpdateSubmission();
        });
        
        // Demo data button handler
        $(document).on("click", "#showDemoBtn", function() {
            console.log("Show demo data button clicked");
            toastr.info("Loading demo data...");
            showFallbackData();
        });
        
        // Initialize button events
        initializeButtonEvents();
        
        // Initialize DataTable directly
        try {
            console.log("Initializing DataTable...");
            
            if ($.fn.DataTable.isDataTable('#roomsTable')) {
                console.log("DataTable already initialized, destroying it first");
                $('#roomsTable').DataTable().destroy();
            }
            
            roomTable = $('#roomsTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search rooms...",
                emptyTable: "No rooms found",
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
                processing: true,
                createdRow: function(row, data, dataIndex) {
                    // Add classes to rows for better styling
                    $(row).addClass('hover:bg-gray-50 transition-colors');
                }
        });
            
            console.log("DataTable initialized successfully");
        
        // Initial data load
        refreshRoomTable();
        } catch (error) {
            console.error("Error initializing DataTable:", error);
            console.error("Error details:", error.message, error.stack);
            
            // Fallback to basic HTML table if DataTable fails
            toastr.error("Error initializing table: " + error.message);
            
            // Display error in table
            $('#roomsTable tbody').html(`
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center">
                        <div class="p-4 border border-red-300 rounded bg-red-50 text-red-700">
                            <p class="font-semibold">Error initializing table</p>
                            <p class="text-sm mt-1">${error.message}</p>
                            <p class="text-xs mt-2">Please try refreshing the page or contact support.</p>
                        </div>
                    </td>
                </tr>
            `);
            
            // Try to show fallback data
            setTimeout(showFallbackData, 1000);
        }
    });

    // Helper function to refresh table data
    function refreshRoomTable() {
        console.log("refreshRoomTable called");
        
        // Check if DataTable is initialized
        if ($.fn.DataTable.isDataTable('#roomsTable')) {
            console.log("DataTable is already initialized, clearing data...");
            roomTable.clear().draw();
        } else {
            console.log("DataTable is not initialized yet");
        }
        
        // Show loading indicator directly in the table
        roomTable.clear().draw();
        roomTable.row.add([
            '<i class="fas fa-spinner fa-spin mr-2"></i> Loading...',
            '', '', '', '', ''
        ]).draw();
        
        // Check authentication status
        const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
        console.log("Authentication status check:", isAuthenticated);
        
        if (!isAuthenticated) {
            console.error("User is not authenticated");
            
            // Display login message
            roomTable.clear().draw();
            roomTable.row.add([
                `<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Please <a href="{{ route('school.login') }}" class="font-medium underline">log in</a> to view hostel rooms.
                            </p>
                        </div>
                    </div>
                </div>`,
                '', '', '', '', ''
            ]).draw();
            
            // Show login required alert
            toastr.warning('Please log in to view hostel rooms', 'Authentication Required');
            
            // Show fallback data for development
            setTimeout(function() {
                showFallbackData();
            }, 1000);
            
            return;
        }
        
        // Debug API endpoint
        const apiEndpoint = "{{ route('school.api.all-hostel-rooms') }}";
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        console.log("Making AJAX request to fetch hostel rooms");
        console.log("API Endpoint:", apiEndpoint);
        console.log("CSRF Token available:", !!csrfToken);
        console.log("CSRF Token value:", csrfToken);
        
        // Make the request with proper headers
        $.ajax({
            url: apiEndpoint,
            type: "GET",
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            complete: function(xhr, status) {
                console.log("AJAX request complete with status:", status);
                
                // Check if we got redirected to login page (auth failure)
                if (xhr.status === 302 || (xhr.responseText && xhr.responseText.includes('Redirecting to'))) {
                    console.error("Authentication error - redirect detected");
                    
                    // Clear the table
                    roomTable.clear().draw();
                    
                    // Add authentication message
                    roomTable.row.add([
                        `<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Your session may have expired. Please <a href="{{ route('school.login') }}" class="font-medium underline">log in</a> to view hostel rooms.
                                    </p>
                                </div>
                            </div>
                        </div>`,
                        '', '', '', '', ''
                    ]).draw();
                    
                    // Show login required alert
                    toastr.warning('Please log in to view hostel rooms', 'Session expired');
                    
                    // Fallback to sample data for development
                    setTimeout(function() {
                        showFallbackData();
                    }, 1000);
                    
                    return;
                }
            },
            success: function(response) {
                console.log("AJAX response received:", response);
                
                if (response.success) {
                    try {
                        // Clear the table
                        roomTable.clear().draw();
                        
                        // Add new rows
                        if (response.hostelRooms && response.hostelRooms.length > 0) {
                            console.log("Found " + response.hostelRooms.length + " rooms to display");
                            
                            response.hostelRooms.forEach(function(room) {
                                try {
                                    const statusClass = room.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                    const statusText = room.status ? 'Enabled' : 'Disabled';
                                    
                                    console.log("Processing room:", room.id, room.room_number);
                                    
                                    // Ensure we have all required data and provide defaults if missing
                                    const hostelName = room.hostel ? room.hostel.name : '-';
                                    const roomTypeName = room.room_type ? room.room_type.name : '-';
                                    const beds = room.beds || 1;
                                    const description = room.description || '';
                                    
                                    // Log object structure to debug missing fields
                                    console.log("Room object structure:", {
                                        id: room.id,
                                        room_number: room.room_number,
                                        hostel_id: room.hostel_id,
                                        hostel: room.hostel,
                                        hostel_name: hostelName,
                                        room_type_id: room.room_type_id,
                                        roomType: room.roomType,
                                        room_type_name: roomTypeName,
                                        beds: beds,
                                        description: description,
                                        status: room.status
                                    });
                                    
                                    // Add row directly to DataTable
                                    let rowNumber = roomTable.rows().count() + 1;

                                    roomTable.row.add([
                                        rowNumber,
                                        room.room_number,
                                        hostelName,
                                        roomTypeName,
                                        beds,
                                        `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">${statusText}</span>`,
                                        `<button class="text-indigo-600 hover:text-indigo-900 font-medium editRoomBtn" 
                                            data-id="${room.id}" 
                                            data-hostel-id="${room.hostel_id}"
                                            data-room-type-id="${room.room_type_id}"
                                            data-beds="${beds}"
                                            data-description="${description}"
                                            data-status="${room.status ? 1 : 0}">Edit</button>
                                         <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteRoomBtn" 
                                            data-id="${room.id}">Delete</button>`
                                    ]).draw(false);
                                    
                                } catch (error) {
                                    console.error("Error processing room data:", error, room);
                                }
                            });
                            
                            console.log("Finished adding rows to table");
                        } else {
                            console.log("No rooms found in response");
                            roomTable.row.add([
                                'No rooms found',
                                '', '', '', '', ''
                            ]).draw();
                        }
                        
                        // Reinitialize button events after table refresh
                        initializeButtonEvents();
                        
                        console.log("Table refreshed successfully");
                    } catch (error) {
                        console.error("Error refreshing table:", error);
                        console.error("Error details:", error.message, error.stack);
                        toastr.error("Error refreshing table data: " + error.message);
                        
                        roomTable.clear().draw();
                        roomTable.row.add([
                            `<div class="text-center text-red-500">Error processing data: ${error.message}</div>`,
                            '', '', '', '', ''
                        ]).draw();
                        
                        // Show fallback data on error
                        setTimeout(function() {
                            showFallbackData();
                        }, 1000);
                    }
                } else {
                    console.error("API returned error:", response.message);
                    toastr.error(response.message || "Failed to fetch room data");
                    
                    roomTable.clear().draw();
                    roomTable.row.add([
                        `<div class="text-center text-red-500">Error: ${response.message || "Failed to fetch room data"}</div>`,
                        '', '', '', '', ''
                    ]).draw();
                    
                    // Show fallback data after API error
                    setTimeout(function() {
                        showFallbackData();
                    }, 1000);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                console.error("Status Code:", xhr.status);
                console.error("Response Text:", xhr.responseText);
                
                // Determine error message
                let errorMessage = "Failed to load room data";
                
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse && errorResponse.message) {
                        errorMessage = errorResponse.message;
                    }
                } catch (e) {
                    // Handle parsing error
                    if (xhr.status === 404) {
                        errorMessage = "API endpoint not found (404)";
                    } else if (xhr.status === 500) {
                        errorMessage = "Server error (500)";
                    } else if (xhr.status === 403) {
                        errorMessage = "Access denied (403)";
                    } else if (xhr.status === 401) {
                        errorMessage = "Unauthorized, please login (401)";
                    }
                }
                
                toastr.error(errorMessage);
                
                roomTable.clear().draw();
                roomTable.row.add([
                    `<div class="text-center text-red-500">Error: ${errorMessage}</div>`,
                    '', '', '', '', ''
                ]).draw();
                
                // Show fallback data on error
                setTimeout(function() {
                    showFallbackData();
                }, 1000);
            }
        });
    }
    
    // Initialize button event handlers
    function initializeButtonEvents() {
        console.log("Initializing button events");
        
        // Unbind any existing handlers first to prevent duplicates
        $(document).off('click', '.editRoomBtn');
        $(document).off('click', '.deleteRoomBtn');
        
        // Use event delegation for buttons in DataTable
        $(document).on('click', '.editRoomBtn', function() {
            const id = $(this).data('id');
            console.log("Edit button clicked for room ID:", id);
            
            // Find the row data from the DataTable
            const tableData = roomTable.row($(this).closest('tr')).data();
            if (!tableData) {
                console.error("Could not find row data in DataTable");
                return;
            }
            
            // Get room data from data attributes or from custom storage
            const roomNumber = tableData[1] || '';
            const roomId = $(this).data('id');
            const hostelId = $(this).data('hostel-id');
            const roomTypeId = $(this).data('room-type-id');
            const beds = $(this).data('beds') || 1;
            const description = $(this).data('description') || '';
            const status = $(this).data('status') == 1;
            
            console.log("Edit data:", {
                id: roomId,
                roomNumber,
                hostelId,
                roomTypeId,
                beds,
                description,
                status
            });

            // Populate the edit form
            $('#editRoomForm input[name="id"]').val(roomId);
            $('#editRoomForm input[name="room_number"]').val(roomNumber);
            $('#editRoomForm select[name="hostel_id"]').val(hostelId);
            $('#editRoomForm select[name="room_type_id"]').val(roomTypeId);
            $('#editRoomForm select[name="beds"]').val(beds);
            $('#editRoomForm textarea[name="description"]').val(description);
            $('#editRoomForm input[name="status"]').prop('checked', status);
            
            // Show the modal
            $('#editRoomModal').removeClass('hidden');
        });
        
        // Delete room button handler
        $(document).on('click', '.deleteRoomBtn', function() {
            const id = $(this).data('id');
            console.log("Delete button clicked for room ID:", id);
            console.log("Button data attributes:", $(this).data());
            
            // Debug information
            try {
                const row = $(this).closest('tr');
                const rowIndex = row.index();
                console.log("Row index:", rowIndex);
                console.log("Row HTML:", row.html());
                console.log("Room ID from data attribute:", id);
                
                // Try to get data from DataTable
                let tableData = null;
                if ($.fn.DataTable.isDataTable('#roomsTable')) {
                    try {
                        tableData = roomTable.row(row).data();
                        console.log("DataTable row data:", tableData);
                    } catch (e) {
                        console.error("Error getting DataTable row data:", e);
                    }
                } else {
                    console.warn("DataTable not initialized when trying to get row data");
                }
            } catch (e) {
                console.error("Error in debug code:", e);
            }
            
            // For demo data, offer direct deletion option
            if (id >= 900) { // Demo data IDs start at 900+
                if (confirm("This is demo data. Would you like to test direct deletion instead?")) {
                    directDeleteRoom(id);
                    return;
                }
            }
            
            // Find the row data
            const tableData = roomTable.row($(this).closest('tr')).data();
            if (!tableData) {
                console.error("Could not find row data in DataTable");
                return;
            }
            
            // Get the room number from the first column
            const roomNumber = tableData[1] || 'Unknown';
            const hostelName = tableData[2] || 'Unknown';
            
            // Set data for deletion
            deleteRoomId = id;
            $('#deleteRoomNumber').text(`${roomNumber} (${hostelName})`);
            $('#deleteRoomModal').removeClass('hidden');
        });
        
        console.log("Button events initialized");
    }
    
    // Initial setup of button events
    initializeButtonEvents();

    // Initial data load
    refreshRoomTable();
    
    // For development testing: Display placeholder data if in development mode
    function loadPlaceholderData() {
        console.log("Loading placeholder data for development testing");
        
        const devMode = {{ config('app.env') == 'local' ? 'true' : 'false' }};
        
        if (devMode) {
            // Simulate data for development testing
            const sampleData = [
                {
                    id: 1,
                    room_number: "A101",
                    hostel: { name: "Boys Hostel" },
                    roomType: { name: "Standard" },
                    beds: 2,
                    status: true
                },
                {
                    id: 2,
                    room_number: "B202",
                    hostel: { name: "Girls Hostel" },
                    roomType: { name: "Deluxe" },
                    beds: 1,
                    status: false
                },
                {
                    id: 3,
                    room_number: "C303",
                    hostel: { name: "International Hostel" },
                    roomType: { name: "Premium" },
                    beds: 3,
                    status: true
                }
            ];
            
            console.log("Sample data:", sampleData);
            
            // Clear the table
            const tbody = $('#roomsTable tbody');
            tbody.empty();
            
            // Add sample rows
            sampleData.forEach(function(room) {
                const statusClass = room.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                const statusText = room.status ? 'Enabled' : 'Disabled';
                
                tbody.append(`
                    <tr class="hover:bg-gray-50 transition-colors" 
                        data-id="${room.id}" 
                        data-room_number="${room.room_number}" 
                        data-hostel_name="${room.hostel ? room.hostel.name : ''}"
                        data-room_type_name="${room.roomType ? room.roomType.name : ''}">
                        <td class="px-6 py-4">${room.room_number}</td>
                        <td class="px-6 py-4">${room.hostel ? room.hostel.name : '-'}</td>
                        <td class="px-6 py-4">${room.roomType ? room.roomType.name : '-'}</td>
                        <td class="px-6 py-4">${room.beds}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                ${statusText}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button class="text-indigo-600 hover:text-indigo-900 font-medium editRoomBtn">Edit</button>
                            <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteRoomBtn">Delete</button>
                        </td>
                    </tr>
                `);
            });
            
            // Add note about development mode
            tbody.append(`
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-xs text-gray-500">
                        <div class="p-2 border border-gray-200 rounded bg-gray-50">
                            Development mode: Displaying sample data for UI testing
                        </div>
                    </td>
                </tr>
            `);
            
            // Reinitialize DataTable
            if ($.fn.DataTable.isDataTable('#roomsTable')) {
                roomTable.destroy();
            }
            
            roomTable = $('#roomsTable').DataTable({
                language: {
                    search: "",
                    searchPlaceholder: "Search rooms...",
                    emptyTable: "No rooms found",
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
            
            // Initialize button events
            initializeButtonEvents();
        }
    }

    // Try to load placeholder data if we're in development mode and the AJAX request fails
    setTimeout(function() {
        const tableHasData = $('#roomsTable tbody tr').length > 1;
        const tableShowsError = $('#roomsTable tbody').text().includes('Error');
        
        if (!tableHasData || tableShowsError) {
            loadPlaceholderData();
        }
    }, 5000);

    // Function to handle room form submission manually
    function handleRoomFormSubmission() {
        console.log("Handling room form submission");
        
        // Get form data
        const formData = $('#roomForm').serialize();
        console.log("Form data:", formData);
        
        // Disable save button to prevent double submission
        $('#saveRoomBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        
        // Perform AJAX request
        $.ajax({
            url: "{{ route('school.hostelRoom.store') }}",
            type: "POST",
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                // Actions before sending the request
                console.log("Sending request to create room");
                // Hide modal immediately
                $('#roomModal').addClass('hidden');
            },
            success: function(response) {
                console.log("Success response:", response);
                
                if (response.success) {
                    // Show success notification
                    toastr.success(response.message || 'Room created successfully');
                    
                    console.log("Room created successfully, refreshing table data...");
                    console.log("New room data:", response.hostelRoom);
                    
                    // Reset form
                    $('#roomForm')[0].reset();
                    
                    // Add small delay before refreshing table to ensure server consistency
                    setTimeout(function() {
                        refreshRoomTable();
                    }, 500);
                } else {
                    toastr.error(response.message || 'Failed to create room');
                    console.error("Server returned error:", response);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                console.error("Response Text:", xhr.responseText);
                console.error("Status Code:", xhr.status);
                
                // Handle errors
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
                            toastr.error('You must be logged in as a school admin to create rooms', 'Authentication Error');
                        }
                    } else {
                        toastr.error("An unknown error occurred");
                    }
                } catch (e) {
                    // If we can't parse the JSON, just show the raw response
                    toastr.error("An error occurred while creating the room");
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
                            window.location.href = "{{ route('school.login') }}";
                        }, 2000);
                    }
                }
            },
            complete: function() {
                // Always run this code regardless of success or failure
                console.log("Request completed");
                
                // Re-enable save button
                $('#saveRoomBtn').prop('disabled', false).html('Save');
                
                // Ensure modal is hidden and form is reset
                $('#roomModal').addClass('hidden');
                
                // Check if raw JSON was output and redirect if needed
                setTimeout(function() {
                    if ($('body').find('pre').length > 0) {
                        console.log("Found raw JSON output, redirecting to clean view");
                        window.location.href = "{{ route('school.hostelRooms') }}";
                    }
                }, 300);
            }
        });
    }

    // Function to handle room update form submission
    function handleRoomUpdateSubmission() {
        console.log("Handling room update form submission");
        
        const id = $('#editRoomForm input[name="id"]').val();
        
        // Get form data
        const formData = $('#editRoomForm').serialize();
        console.log("Edit form data:", formData);
        
        // Disable update button to prevent double submission
        $('#updateRoomBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        
        $.ajax({
            url: getRoomUrl(id),
            type: "POST",
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                // Actions before sending the request
                console.log("Sending request to update room");
                
                // Hide modal immediately
                $('#editRoomModal').addClass('hidden');
            },
            success: function(response) {
                console.log("Edit success response:", response);
                
                if (response.success) {
                    // Show success notification
                    toastr.success(response.message || 'Room updated successfully');
                    
                    console.log("Room updated successfully, refreshing table data...");
                    console.log("Updated room data:", response.hostelRoom);
                    
                    // Add small delay before refreshing table to ensure server consistency
                    setTimeout(function() {
                        refreshRoomTable();
                    }, 500);
                } else {
                    toastr.error(response.message || 'Failed to update room');
                    console.error("Server returned error:", response);
                }
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
                    toastr.error("An error occurred while updating the room");
                    
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
                            window.location.href = "{{ route('school.login') }}";
                        }, 2000);
                    }
                }
            },
            complete: function() {
                // Always run this code regardless of success or failure
                console.log("Update request completed");
                
                // Re-enable update button
                $('#updateRoomBtn').prop('disabled', false).html('Update');
                
                // Ensure modal is hidden
                $('#editRoomModal').addClass('hidden');
                
                // Check if raw JSON was output and redirect if needed
                setTimeout(function() {
                    if ($('body').find('pre').length > 0) {
                        console.log("Found raw JSON output, redirecting to clean view");
                        window.location.href = "{{ route('school.hostelRooms') }}";
                    }
                }, 300);
            }
        });
    }

    // Function for the confirm delete button
    function confirmDelete() {
        console.log("confirmDelete function called");
        
        if (!deleteRoomId) {
            console.error("No room ID found for deletion");
            return;
        }
        
        console.log("Deleting room ID:", deleteRoomId);
        
        // Disable button to prevent double deletion
        $('#confirmDeleteRoomBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
        
        // Set the form action URL
        const deleteUrl = getRoomUrl(deleteRoomId);
        $('#deleteRoomForm').attr('action', deleteUrl);
        
        // Log detailed information about the request
        console.log("Delete request details:", {
            url: deleteUrl,
            csrfToken: $('meta[name="csrf-token"]').attr('content'),
            formData: $('#deleteRoomForm').serialize(),
            roomId: deleteRoomId
        });
        
        // Submit the form via AJAX
        $.ajax({
            url: deleteUrl,
            type: 'POST',
            data: $('#deleteRoomForm').serialize(),
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            beforeSend: function() {
                console.log("Sending delete request for room ID:", deleteRoomId);
                console.log("Delete URL:", deleteUrl);
                console.log("Form data:", $('#deleteRoomForm').serialize());
            },
            success: function(response) {
                console.log("Delete response:", response);
                
                // Hide the modal first
                $('#deleteRoomModal').addClass('hidden');
                
                if (response.success) {
                    // Show success notification
                    toastr.success(response.message || 'Room deleted successfully');
                    
                    console.log("Room deleted successfully, refreshing table data...");
                    
                    // Add small delay before refreshing table to ensure server consistency
                    setTimeout(function() {
                        refreshRoomTable();
                    }, 500);
                } else {
                    toastr.error(response.message || 'Failed to delete room');
                    console.error("Server returned error:", response);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                console.error("Response Text:", xhr.responseText);
                console.error("Status Code:", xhr.status);
                console.error("Full Error Details:", {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });
                
                // Hide the modal
                $('#deleteRoomModal').addClass('hidden');
                
                let errorMessage = "An error occurred while deleting the room";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            },
            complete: function() {
                // Always run regardless of success or failure
                console.log("Delete request completed");
                
                // Re-enable button
                $('#confirmDeleteRoomBtn').prop('disabled', false).html('Delete');
                
                // Ensure modal is hidden
                $('#deleteRoomModal').addClass('hidden');
                
                // Return to the main page view if raw JSON is displayed
                setTimeout(function() {
                    if ($('body').find('pre').length > 0) {
                        console.log("Found raw JSON output, redirecting to clean view");
                        window.location.href = "{{ route('school.hostelRooms') }}";
                    }
                }, 300);
            }
        });
    }
    
    // AJAX monitoring
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

    // Function to display fallback data directly for debugging
    function showFallbackData() {
        console.log("SHOWING FALLBACK DATA FOR DEBUGGING");
        
        try {
        // Use hardcoded sample data
        const sampleData = [
            {
                id: 999,
                room_number: "DEBUG-101",
                hostel_id: 1,
                hostel: { name: "Debug Hostel" },
                room_type_id: 1,
                roomType: { name: "Debug Room Type" },
                beds: 2,
                description: "Debug description",
                status: true
            },
            {
                id: 998,
                room_number: "DEBUG-102",
                hostel_id: 1,
                hostel: { name: "Debug Hostel" },
                room_type_id: 1,
                roomType: { name: "Debug Room Type" },
                beds: 3,
                description: "Another debug room",
                status: false
            }
        ];
        
            console.log("Sample data prepared:", sampleData);
            
            // Clear the DataTable and add sample data
            if ($.fn.DataTable.isDataTable('#roomsTable')) {
                console.log("DataTable exists, clearing it first");
                roomTable.clear();
                
                // Add each row to the DataTable
        sampleData.forEach(function(room) {
            const statusClass = room.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            const statusText = room.status ? 'Enabled' : 'Disabled';
            
                    roomTable.row.add([
                        room.room_number,
                        room.hostel.name,
                        room.roomType.name,
                        room.beds,
                        `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">${statusText}</span>`,
                        `<button class="text-indigo-600 hover:text-indigo-900 font-medium editRoomBtn" 
                    data-id="${room.id}" 
                            data-hostel-id="${room.hostel_id}"
                            data-room-type-id="${room.room_type_id}"
                    data-beds="${room.beds}"
                    data-description="${room.description}"
                            data-status="${room.status ? 1 : 0}">Edit</button>
                         <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteRoomBtn" 
                            data-id="${room.id}">Delete</button>`
                    ]);
                });
                
                // Add debug notice as footer
                $('#roomsTable').append(`
                    <tfoot>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-xs text-red-500">
                                <div class="p-2 border border-red-200 rounded bg-red-50">
                                    DEBUG MODE: Showing sample data because the API request failed
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                `);
                
                // Draw the updated table
                roomTable.draw();
            } else {
                console.log("DataTable not initialized, creating it with sample data");
                
                // Transform data for direct HTML insertion
                let tableHtml = '';
                sampleData.forEach(function(room) {
                    const statusClass = room.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                    const statusText = room.status ? 'Enabled' : 'Disabled';
                    
                    tableHtml += `
                        <tr class="hover:bg-gray-50 transition-colors" data-id="${room.id}">
                    <td class="px-6 py-4">${room.room_number}</td>
                    <td class="px-6 py-4">${room.hostel.name}</td>
                    <td class="px-6 py-4">${room.roomType.name}</td>
                    <td class="px-6 py-4">${room.beds}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                            ${statusText}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                                <button class="text-indigo-600 hover:text-indigo-900 font-medium editRoomBtn" 
                                    data-id="${room.id}" 
                                    data-hostel-id="${room.hostel_id}"
                                    data-room-type-id="${room.room_type_id}"
                                    data-beds="${room.beds}"
                                    data-description="${room.description}"
                                    data-status="${room.status ? 1 : 0}">Edit</button>
                                <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteRoomBtn" 
                                    data-id="${room.id}">Delete</button>
                    </td>
                </tr>
                    `;
        });
        
                // Add debug notice
                tableHtml += `
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-xs text-red-500">
                    <div class="p-2 border border-red-200 rounded bg-red-50">
                                DEBUG MODE: Showing sample data because the API request failed
                    </div>
                </td>
            </tr>
                `;
                
                // Insert HTML directly into the table body
                $('#roomsTable tbody').html(tableHtml);
                
                // Initialize DataTable
                roomTable = $('#roomsTable').DataTable({
                    language: {
                        search: "",
                        searchPlaceholder: "Search rooms...",
                        emptyTable: "No rooms found",
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
            }
            
            // Initialize button events after table is set up
            console.log("Reinitializing button events for sample data");
        initializeButtonEvents();
            
            // Show notification
            toastr.info('Showing sample data for demonstration purposes', 'Debug Mode');
        } catch (error) {
            console.error("Error displaying fallback data:", error);
            console.error(error.stack);
            
            // Handle catastrophic failures
            $('#roomsTable tbody').html(`
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center">
                        <div class="p-4 border border-red-300 rounded bg-red-50 text-red-700">
                            <p class="font-semibold">Error loading data</p>
                            <p class="text-sm mt-1">${error.message}</p>
                            <p class="text-xs mt-2">Please try refreshing the page or contact support.</p>
                        </div>
                    </td>
                </tr>
            `);
        }
    }

    // Function to directly delete a room (fallback for testing)
    function directDeleteRoom(roomId) {
        if (!roomId) {
            console.error("No room ID provided for direct deletion");
            return;
        }
        
        console.log("Attempting direct deletion of room ID:", roomId);
        
        // Create a form element
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = getRoomUrl(roomId);
        form.style.display = 'none';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = $('meta[name="csrf-token"]').attr('content');
        form.appendChild(csrfInput);
        
        // Add method spoofing
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // Add form to document, submit it, then remove it
        document.body.appendChild(form);
        form.submit();
        
        // Form will cause a page redirect, so no need to remove it
    }

    // Alternative delete function using fetch API
    function alternativeDeleteRoom(roomId) {
        const deleteUrl = getRoomUrl(roomId);
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        console.log("Using fetch API to delete room:", roomId);
        
        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            console.log("Fetch response status:", response.status);
            return response.json();
        })
        .then(data => {
            console.log("Fetch response data:", data);
            if (data.success) {
                toastr.success(data.message || 'Room deleted successfully');
                refreshRoomTable();
            } else {
                toastr.error(data.message || 'Failed to delete room');
            }
        })
        .catch(error => {
            console.error("Fetch error:", error);
            toastr.error('Error deleting room: ' + error.message);
        });
    }
    
    // Direct form submission for delete
    function directFormDelete(roomId) {
        console.log("directFormDelete called with roomId:", roomId);
        
        const deleteUrl = getRoomUrl(roomId);
        console.log("Delete URL:", deleteUrl);
        
        $('#deleteRoomForm').attr('action', deleteUrl);
        console.log("Form action set to:", $('#deleteRoomForm').attr('action'));
        console.log("Form method:", $('#deleteRoomForm').attr('method'));
        console.log("Form HTML:", $('#deleteRoomForm').html());
        
        // Hide the modal
        $('#deleteRoomModal').addClass('hidden');
        
        try {
            // Try direct form submission
            $('#deleteRoomForm').css('display', 'block');
            $('#deleteRoomForm').submit();
            console.log("Form submitted");
        } catch(e) {
            console.error("Error submitting form:", e);
            // Show error message with toastr instead of alert
            toastr.error("Error submitting form: " + e.message);
        }
    }
    
    // Handle alternative delete button clicks
    $(document).on('click', '#alternativeDeleteBtn', function() {
        if (!deleteRoomId) {
            console.error("No room ID found for deletion");
            return;
        }
        
        $('#deleteRoomModal').addClass('hidden');
        alternativeDeleteRoom(deleteRoomId);
    });
    
    $(document).on('click', '#directDeleteBtn', function() {
        if (!deleteRoomId) {
            console.error("No room ID found for deletion");
            return;
        }
        
        $('#deleteRoomModal').addClass('hidden');
        directFormDelete(deleteRoomId);
    });

    // Manual event binding with vanilla JavaScript (in case jQuery events aren't working)
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Setting up vanilla JS event handlers");
        
        // Add direct click handlers using vanilla JS
        var confirmBtn = document.getElementById('confirmDeleteRoomBtn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                console.log("Vanilla JS - confirmDeleteRoomBtn clicked");
                confirmDelete();
            });
        } else {
            console.error("Could not find confirmDeleteRoomBtn element");
        }
        
        var directBtn = document.getElementById('directDeleteBtn');
        if (directBtn) {
            directBtn.addEventListener('click', function() {
                console.log("Vanilla JS - directDeleteBtn clicked");
                directFormDelete(deleteRoomId);
            });
        } else {
            console.error("Could not find directDeleteBtn element");
        }
        
        var altBtn = document.getElementById('alternativeDeleteBtn');
        if (altBtn) {
            altBtn.addEventListener('click', function() {
                console.log("Vanilla JS - alternativeDeleteBtn clicked");
                alternativeDeleteRoom(deleteRoomId);
            });
        } else {
            console.error("Could not find alternativeDeleteBtn element");
        }
        
        // Add click handler for delete room buttons
        var deleteButtons = document.querySelectorAll('.deleteRoomBtn');
        console.log("Found " + deleteButtons.length + " delete buttons");
        
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                console.log("Vanilla JS - deleteRoomBtn clicked");
                var id = this.getAttribute('data-id');
                console.log("Room ID from attribute:", id);
                
                if (id) {
                    deleteRoomId = id;
                    
                    // Get the room name for display
                    var row = this.closest('tr');
                    var roomNumber = row.cells[0].textContent;
                    var hostelName = row.cells[1].textContent;
                    
                    document.getElementById('deleteRoomNumber').textContent = roomNumber + " (" + hostelName + ")";
                    
                    // Show the modal
                    document.getElementById('deleteRoomModal').classList.remove('hidden');
                }
            });
        });
    });
</script>

