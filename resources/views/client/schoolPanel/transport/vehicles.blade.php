@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Transport / <span class="text-l text-gray-500">Vehicles</span>
            </h1>
            <button id="openVehicleModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Create Vehicle +
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <table id="vehiclesTable" class="min-w-full divide-y py-6 divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">Vehicle ID</th>
                        <th class="px-6 py-3 font-semibold">Vehicle No</th>
                        <th class="px-6 py-3 font-semibold">Model</th>
                        <th class="px-6 py-3 font-semibold">Made Year</th>
                        <th class="px-6 py-3 font-semibold">Registration No</th>
                        <th class="px-6 py-3 font-semibold">Seat Capacity</th>
                        <th class="px-6 py-3 font-semibold">Driver</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    <!-- Data will be loaded dynamically via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create Vehicle Modal --}}
<div id="vehicleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-lg w-full p-6 relative overflow-y-auto max-h-[90vh]">
        <h2 class="text-xl font-semibold mb-4">Add New Vehicle</h2>
        <button type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" id="closeVehicleModalX">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <form id="vehicleForm">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Vehicle No</label>
                    <input type="text" name="vehicle_no" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Vehicle Model</label>
                    <input type="text" name="vehicle_model" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Made Year</label>
                    <select name="made_year" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Year</option>
                        @php
                            $currentYear = date('Y');
                            for($year = 1990; $year <= $currentYear + 1; $year++) {
                                echo "<option value='{$year}'>{$year}</option>";
                            }
                        @endphp
                    </select>
                </div>
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Registration No</label>
                    <input type="text" name="registration_no" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Chassis No</label>
                    <input type="text" name="chassis_no" placeholder="Enter Chassis No" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Seat Capacity</label>
                    <input type="number" name="seat_capacity" min="1" max="100" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
            </div>
            <div class="mt-4">
                <label class="block mb-1 font-medium text-gray-700">GPS Tracking ID</label>
                <input type="text" name="gps_tracking_id" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <h3 class="text-lg font-semibold mt-6 mb-2 text-gray-800">Driver details</h3>

            <label class="block mb-1 font-medium text-gray-700">Select Driver</label>
            <select name="driver_id" class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select Driver</option>
                @if(isset($drivers))
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->driver_name }}</option>
                    @endforeach
                @endif
            </select>

            <label class="flex items-center mb-4">
                <input type="checkbox" name="status" value="1" checked class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded">
                <span class="text-gray-700">Enable this vehicle</span>
            </label>

            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400" id="closeVehicleModalCancel">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Vehicle Modal --}}
<div id="editVehicleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-lg w-full p-6 relative overflow-y-auto max-h-[90vh]">
        <h2 class="text-xl font-semibold mb-4">Edit Vehicle</h2>
        <button type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" id="closeEditVehicleModalX">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <form id="editVehicleForm">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" id="edit_vehicle_id" name="id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Vehicle No</label>
                    <input type="text" name="vehicle_no" id="edit_vehicle_no" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Vehicle Model</label>
                    <input type="text" name="vehicle_model" id="edit_vehicle_model" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Made Year</label>
                    <select name="made_year" id="edit_made_year" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Year</option>
                        @php
                            $currentYear = date('Y');
                            for($year = 1990; $year <= $currentYear + 1; $year++) {
                                echo "<option value='{$year}'>{$year}</option>";
                            }
                        @endphp
                    </select>
                </div>
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Registration No</label>
                    <input type="text" name="registration_no" id="edit_registration_no" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Chassis No</label>
                    <input type="text" name="chassis_no" id="edit_chassis_no" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Seat Capacity</label>
                    <input type="number" name="seat_capacity" id="edit_seat_capacity" min="1" max="100" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
            </div>
            <div class="mt-4">
                <label class="block mb-1 font-medium text-gray-700">GPS Tracking ID</label>
                <input type="text" name="gps_tracking_id" id="edit_gps_tracking_id" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <h3 class="text-lg font-semibold mt-6 mb-2 text-gray-800">Driver Details</h3>

            <label class="block mb-1 font-medium text-gray-700">Select Driver</label>
            <select name="driver_id" id="edit_driver_id" class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select Driver</option>
                @if(isset($drivers))
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->driver_name }}</option>
                    @endforeach
                @endif
            </select>

            <label class="flex items-center mb-4">
                <input type="checkbox" name="status" id="edit_status" value="1" class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded">
                <span class="text-gray-700">Enable this vehicle</span>
            </label>

            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400" id="closeEditVehicleModalCancel">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteVehicleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
        <p class="text-gray-700 mb-6">Are you sure you want to delete vehicle <span id="deleteVehicleNo" class="font-semibold"></span>?</p>
        <form id="deleteVehicleForm">
            @csrf
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" id="delete_vehicle_id" name="id">
        </form>
        <div class="flex justify-end space-x-4">
            <button type="button" id="closeDeleteVehicleModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="confirmDeleteVehicleBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

{{-- DataTables CDN --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
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
</style>

<script>
$(document).ready(function() {
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
        "hideMethod": "fadeOut"
    };

    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTable
    const vehiclesTable = $('#vehiclesTable').DataTable({
        language: {
            search: "",
            searchPlaceholder: "Search vehicles..."
        },
        lengthMenu: [5, 10, 25, 50],
        pageLength: 5,
        dom: "<'flex justify-between items-center mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
            "t" +
            "<'flex justify-between items-center mt-4'<'dataTables_info'i><'dataTables_paginate'p>>",
        processing: true,
        "columnDefs": [
            { "targets": [0], "visible": false } // Hide ID column
        ]
    });

    // Load vehicles data
    loadVehiclesData();

    // Modal toggles
    const vehicleModal = $('#vehicleModal');
    const editVehicleModal = $('#editVehicleModal');
    const deleteVehicleModal = $('#deleteVehicleModal');

    // Create Vehicle Modal Open/Close
    $('#openVehicleModal').click(() => vehicleModal.removeClass('hidden'));
    $('#closeVehicleModalX').click(() => vehicleModal.addClass('hidden'));
    $('#closeVehicleModalCancel').click(() => vehicleModal.addClass('hidden'));

    // Edit Vehicle Modal Close
    $('#closeEditVehicleModalX').click(() => editVehicleModal.addClass('hidden'));
    $('#closeEditVehicleModalCancel').click(() => editVehicleModal.addClass('hidden'));

    // Delete Modal Close
    $('#closeDeleteVehicleModal').click(() => deleteVehicleModal.addClass('hidden'));

    // Create vehicle form submission
    $('#vehicleForm').submit(function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = $(this).serialize();
        
        // Send AJAX request
        $.ajax({
            url: "{{ route('school.vehicle.store') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Show success message
                    toastr.success(response.message || "Vehicle created successfully");
                    
                    // Reset form and close modal
                    $('#vehicleForm')[0].reset();
                    vehicleModal.addClass('hidden');
                    
                    // Reload data
                    loadVehiclesData();
                } else {
                    toastr.error(response.message || "Failed to create vehicle");
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || "An error occurred while creating the vehicle";
                toastr.error(errorMsg);
                
                // Show validation errors if any
                if (xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;
                    for (const field in errors) {
                        toastr.error(errors[field][0]);
                    }
                }
            }
        });
    });

    // Edit vehicle button handler - Load vehicle data
    $(document).on('click', '.editVehicleBtn', function() {
        const vehicleId = $(this).data('id');
        
        // Fetch vehicle data
        $.ajax({
            url: "/school/vehicle/" + vehicleId,
            type: "GET",
            success: function(response) {
                if (response.success) {
                    const vehicle = response.vehicle;
                    
                    // Populate form fields
                    $('#edit_vehicle_id').val(vehicle.id);
                    $('#edit_vehicle_no').val(vehicle.vehicle_no);
                    $('#edit_vehicle_model').val(vehicle.vehicle_model);
                    $('#edit_made_year').val(vehicle.made_year);
                    $('#edit_registration_no').val(vehicle.registration_no);
                    $('#edit_chassis_no').val(vehicle.chassis_no);
                    $('#edit_seat_capacity').val(vehicle.seat_capacity);
                    $('#edit_gps_tracking_id').val(vehicle.gps_tracking_id);
                    $('#edit_driver_id').val(vehicle.driver_id);
                    $('#edit_status').prop('checked', vehicle.status);
                    
                    // Show modal
                    editVehicleModal.removeClass('hidden');
                } else {
                    toastr.error(response.message || "Failed to fetch vehicle data");
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || "An error occurred while fetching vehicle data";
                toastr.error(errorMsg);
            }
        });
    });

    // Update vehicle form submission
    $('#editVehicleForm').submit(function(e) {
        e.preventDefault();
        
        const vehicleId = $('#edit_vehicle_id').val();
        if (!vehicleId) {
            toastr.error("Vehicle ID is missing");
            return;
        }
        
        // Get form data
        const formData = $(this).serialize();
        
        // Send AJAX request
        $.ajax({
            url: "/school/vehicle/" + vehicleId,
            type: "POST", // FormData will handle the method override
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Show success message
                    toastr.success(response.message || "Vehicle updated successfully");
                    
                    // Close modal
                    editVehicleModal.addClass('hidden');
                    
                    // Reload data
                    loadVehiclesData();
                } else {
                    toastr.error(response.message || "Failed to update vehicle");
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || "An error occurred while updating the vehicle";
                toastr.error(errorMsg);
                
                // Show validation errors if any
                if (xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;
                    for (const field in errors) {
                        toastr.error(errors[field][0]);
                    }
                }
            }
        });
    });

    // Delete vehicle button handler
    $(document).on('click', '.deleteVehicleBtn', function() {
        const vehicleId = $(this).data('id');
        const vehicleNo = $(this).data('vehicle-no');
        
        // Set delete modal values
        $('#delete_vehicle_id').val(vehicleId);
        $('#deleteVehicleNo').text(vehicleNo);
        deleteVehicleModal.removeClass('hidden');
    });

    // Confirm Delete
    $('#confirmDeleteVehicleBtn').click(function() {
        const vehicleId = $('#delete_vehicle_id').val();
        if (!vehicleId) {
            toastr.error("Vehicle ID is missing");
            return;
        }
        
        // Send AJAX request
        $.ajax({
            url: "/school/vehicle/" + vehicleId,
            type: "DELETE",
            data: $('#deleteVehicleForm').serialize(),
            success: function(response) {
                if (response.success) {
                    // Show success message
                    toastr.success(response.message || "Vehicle deleted successfully");
                    
                    // Close modal
                    deleteVehicleModal.addClass('hidden');
                    
                    // Reload data
                    loadVehiclesData();
                } else {
                    toastr.error(response.message || "Failed to delete vehicle");
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || "An error occurred while deleting the vehicle";
                toastr.error(errorMsg);
            }
        });
    });

    // Function to load vehicles data
    function loadVehiclesData() {
        $.ajax({
            url: "{{ route('school.api.all-vehicles') }}",
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    // Clear existing data
                    vehiclesTable.clear();
                    
                    // Add new data
                    if (response.vehicles && response.vehicles.length > 0) {
                        response.vehicles.forEach(function(vehicle) {
                            const statusClass = vehicle.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                            const statusText = vehicle.status ? 'Enabled' : 'Disabled';
                            
                            // Driver info
                            let driverName = 'Not Assigned';
                            if (vehicle.driver) {
                                driverName = vehicle.driver.driver_name;
                            }
                            
                            vehiclesTable.row.add([
                                vehicle.id,
                                vehicle.vehicle_no,
                                vehicle.vehicle_model,
                                vehicle.made_year,
                                vehicle.registration_no,
                                vehicle.seat_capacity,
                                driverName,
                                `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">${statusText}</span>`,
                                `<button class="text-indigo-600 hover:text-indigo-900 font-medium editVehicleBtn" data-id="${vehicle.id}">Edit</button>
                                <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteVehicleBtn" data-id="${vehicle.id}" data-vehicle-no="${vehicle.vehicle_no}">Delete</button>`
                            ]).draw(false);
                        });
                    } else {
                        // No data message
                        vehiclesTable.row.add([
                            '',
                            '<div class="text-center text-gray-500 py-4">No vehicles found</div>',
                            '', '', '', '', '', '', ''
                        ]).draw(false);
                    }
                } else {
                    toastr.error(response.message || "Failed to load vehicles data");
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || "An error occurred while loading vehicles data";
                toastr.error(errorMsg);
                
                // Show empty state
                vehiclesTable.clear().draw();
                vehiclesTable.row.add([
                    '',
                    '<div class="text-center text-red-500 py-4">Error loading data. Please try again.</div>',
                    '', '', '', '', '', '', ''
                ]).draw(false);
            }
        });
    }
});
</script>