@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Transport / <span class="text-l text-gray-500">Vehicle Drivers</span>
            </h1>
            <button id="openDriverModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Create Driver +
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <table id="driversTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">Driver ID</th>
                        <th class="px-6 py-3 font-semibold">Driver Name</th>
                        <th class="px-6 py-3 font-semibold">Contact</th>
                        <th class="px-6 py-3 font-semibold">License No</th>
                        <th class="px-6 py-3 font-semibold">Address</th>
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

{{-- Create Driver Modal --}}
<div id="driverModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Create Driver</h2>
        <form id="driverForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="flex flex-col sm:flex-row items-center gap-6 mt-6 mb-6">
                <!-- Profile Image Preview -->
                <div class="flex-shrink-0">
                    <img id="profile-img-preview" src="https://i.pravatar.cc/300"
                        class="rounded-full w-36 h-36 border-4 border-gray-300 shadow-md object-cover" />
                </div>

                <!-- File Input -->
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Profile Photo</label>
                    <input type="file" id="profile-img-upload" name="profile_photo" accept="image/*"
                        class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4
                        file:rounded-lg file:border-0 file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100 transition-all duration-200" />
                    <p class="mt-1 text-xs text-gray-500">Accepted formats: JPG, PNG, Max size: 2MB</p>
                </div>
            </div>

            <label class="block mb-2 font-medium text-gray-700">Driver Name</label>
            <input type="text" name="driver_name" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">Contact Number</label>
            <input type="text" name="contact_number" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">License Number</label>
            <input type="text" name="license_number" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">Address</label>
            <textarea name="address" required class="w-full px-3 py-2 border rounded mb-4"></textarea>

            <label class="flex items-center mb-4">
                <input type="checkbox" name="status" value="1" checked class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded">
                <span class="text-gray-700">Enable this driver</span>
            </label>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeDriverModal"
                    class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Driver Modal --}}
<div id="editDriverModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Edit Driver</h2>
        <form id="editDriverForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" id="edit_driver_id">

            <div class="flex flex-col sm:flex-row items-center gap-6 mt-6 mb-6">
                <!-- Profile Image Preview -->
                <div class="flex-shrink-0">
                    <img id="edit-profile-img-preview" src="https://i.pravatar.cc/300"
                        class="rounded-full w-36 h-36 border-4 border-gray-300 shadow-md object-cover" />
                </div>

                <!-- File Input -->
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Profile Photo</label>
                    <input type="file" id="edit-profile-img-upload" name="profile_photo" accept="image/*"
                        class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4
                        file:rounded-lg file:border-0 file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100 transition-all duration-200" />
                    <p class="mt-1 text-xs text-gray-500">Accepted formats: JPG, PNG, Max size: 2MB</p>
                </div>
            </div>

            <label class="block mb-2 font-medium text-gray-700">Driver Name</label>
            <input type="text" name="driver_name" id="edit_driver_name" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">Contact Number</label>
            <input type="text" name="contact_number" id="edit_contact_number" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">License Number</label>
            <input type="text" name="license_number" id="edit_license_number" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">Address</label>
            <textarea name="address" id="edit_address" required class="w-full px-3 py-2 border rounded mb-4"></textarea>

            <label class="flex items-center mb-4">
                <input type="checkbox" name="status" id="edit_status" value="1" class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded">
                <span class="text-gray-700">Enable this driver</span>
            </label>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeEditDriverModal"
                    class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Driver Modal --}}
<div id="deleteDriverModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
        <p class="text-gray-700 mb-6">Are you sure you want to delete driver <span id="deleteDriverName" class="font-semibold"></span>?</p>
        <form id="deleteDriverForm" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" id="delete_driver_id" name="id">
        </form>
        <div class="flex justify-end space-x-4">
            <button type="button" id="closeDeleteDriverModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="confirmDeleteDriverBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

{{-- DataTables + Script --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
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

    $(document).ready(function() {
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize DataTable
        const driversTable = $('#driversTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search drivers..."
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

        // Load drivers data
        loadDriversData();

        // Modal open/close handlers
        $('#openDriverModal').click(() => $('#driverModal').removeClass('hidden'));
        $('#closeDriverModal').click(() => $('#driverModal').addClass('hidden'));
        $('#closeEditDriverModal').click(() => $('#editDriverModal').addClass('hidden'));
        $('#closeDeleteDriverModal').click(() => $('#deleteDriverModal').addClass('hidden'));

        // Profile image preview for create form
        $('#profile-img-upload').change(function(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#profile-img-preview').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        // Profile image preview for edit form
        $('#edit-profile-img-upload').change(function(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#edit-profile-img-preview').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        // Create driver form submission
        $('#driverForm').submit(function(e) {
            e.preventDefault();
            
            // Create FormData object for file uploads
            const formData = new FormData(this);
            
            // Send AJAX request
            $.ajax({
                url: "{{ route('school.vehicleDriver.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        toastr.success(response.message || "Driver created successfully");
                        
                        // Reset form and close modal
                        $('#driverForm')[0].reset();
                        $('#driverModal').addClass('hidden');
                        
                        // Reload data
                        loadDriversData();
                    } else {
                        toastr.error(response.message || "Failed to create driver");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred while creating the driver";
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

        // Edit driver form submission
        $('#editDriverForm').submit(function(e) {
            e.preventDefault();
            
            // Get driver ID
            const driverId = $('#edit_driver_id').val();
            if (!driverId) {
                toastr.error("Driver ID is missing");
                return;
            }
            
            // Create FormData object for file uploads
            const formData = new FormData(this);
            
            // Send AJAX request
            $.ajax({
                url: "/school/vehicleDriver/" + driverId,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        toastr.success(response.message || "Driver updated successfully");
                        
                        // Close modal
                        $('#editDriverModal').addClass('hidden');
                        
                        // Reload data
                        loadDriversData();
                    } else {
                        toastr.error(response.message || "Failed to update driver");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred while updating the driver";
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

        // Delete driver confirmation
        $(document).on('click', '.deleteDriverBtn', function() {
            const driverId = $(this).data('id');
            const driverName = $(this).data('name');
            
            $('#delete_driver_id').val(driverId);
            $('#deleteDriverName').text(driverName);
            $('#deleteDriverModal').removeClass('hidden');
        });

        // Confirm delete driver
        $('#confirmDeleteDriverBtn').click(function() {
            const driverId = $('#delete_driver_id').val();
            if (!driverId) {
                toastr.error("Driver ID is missing");
                return;
            }
            
            $.ajax({
                url: "/school/vehicleDriver/" + driverId,
                type: "DELETE",
                data: $('#deleteDriverForm').serialize(),
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        toastr.success(response.message || "Driver deleted successfully");
                        
                        // Close modal
                        $('#deleteDriverModal').addClass('hidden');
                        
                        // Reload data
                        loadDriversData();
                    } else {
                        toastr.error(response.message || "Failed to delete driver");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred while deleting the driver";
                    toastr.error(errorMsg);
                }
            });
        });

        // Edit driver button handler
        $(document).on('click', '.editDriverBtn', function() {
            const driverId = $(this).data('id');
            
            // Fetch driver data
            $.ajax({
                url: "/school/vehicleDriver/" + driverId,
                type: "GET",
                success: function(response) {
                    if (response.success) {
                        const driver = response.driver;
                        
                        // Populate form fields
                        $('#edit_driver_id').val(driver.id);
                        $('#edit_driver_name').val(driver.driver_name);
                        $('#edit_contact_number').val(driver.contact_number);
                        $('#edit_license_number').val(driver.license_number);
                        $('#edit_address').val(driver.address);
                        $('#edit_status').prop('checked', driver.status);
                        
                        // Set profile image if exists
                        if (driver.profile_photo) {
                            $('#edit-profile-img-preview').attr('src', '/storage/' + driver.profile_photo);
                        } else {
                            $('#edit-profile-img-preview').attr('src', 'https://i.pravatar.cc/300');
                        }
                        
                        // Show modal
                        $('#editDriverModal').removeClass('hidden');
                    } else {
                        toastr.error(response.message || "Failed to fetch driver data");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred while fetching driver data";
                    toastr.error(errorMsg);
                }
            });
        });

        // Function to load drivers data
        function loadDriversData() {
            $.ajax({
                url: "{{ route('school.api.all-vehicle-drivers') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // Clear existing data
                        driversTable.clear();
                        
                        // Add new data
                        if (response.drivers && response.drivers.length > 0) {
                            response.drivers.forEach(function(driver) {
                                const statusClass = driver.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                const statusText = driver.status ? 'Enabled' : 'Disabled';
                                
                                // Profile image
                                let profileImage = 'https://i.pravatar.cc/40';
                                if (driver.profile_photo) {
                                    profileImage = '/storage/' + driver.profile_photo;
                                }
                                
                                driversTable.row.add([
                                    driver.id,
                                    `<div class="flex items-center gap-3">
                                        <img src="${profileImage}" alt="Profile" class="w-10 h-10 rounded-full object-cover" />
                                        <span>${driver.driver_name}</span>
                                    </div>`,
                                    driver.contact_number,
                                    driver.license_number,
                                    driver.address,
                                    `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">${statusText}</span>`,
                                    `<button class="text-indigo-600 hover:text-indigo-900 font-medium editDriverBtn" data-id="${driver.id}">Edit</button>
                                    <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteDriverBtn" data-id="${driver.id}" data-name="${driver.driver_name}">Delete</button>`
                                ]).draw(false);
                            });
                        } else {
                            // No data message
                            driversTable.row.add([
                                '',
                                '<div class="text-center text-gray-500 py-4">No drivers found</div>',
                                '', '', '', '', ''
                            ]).draw(false);
                        }
                    } else {
                        toastr.error(response.message || "Failed to load drivers data");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred while loading drivers data";
                    toastr.error(errorMsg);
                    
                    // Show empty state
                    driversTable.clear().draw();
                    driversTable.row.add([
                        '',
                        '<div class="text-center text-red-500 py-4">Error loading data. Please try again.</div>',
                        '', '', '', '', ''
                    ]).draw(false);
                }
            });
        }
    });
</script>
