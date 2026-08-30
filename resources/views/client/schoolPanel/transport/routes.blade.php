@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Transport / <span class="text-l text-gray-500">Routes</span>
            </h1>
            <button id="openCreateRouteModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Create New Route +
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <table id="routesTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">ID</th>
                        <th class="px-6 py-3 font-semibold">Route Name</th>
                        <th class="px-6 py-3 font-semibold" style="width: 40rem;">Pickup Points</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    {{-- Data will be loaded dynamically via AJAX --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create Route Modal --}}
<div id="createRouteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-lg w-full p-6 relative overflow-y-auto max-h-[90vh]">
        <h2 class="text-xl font-semibold mb-4">Create New Route</h2>
        <button type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" id="closeCreateRouteModalX">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <form id="createRouteForm">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Route Name</label>
                <input type="text" name="route_name" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            
            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Description</label>
                <textarea name="description" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2"></textarea>
            </div>

            <h3 class="text-lg font-semibold mt-6 mb-2 text-gray-800">Pickup Points</h3>
            <div id="createPickupPointsContainer">
                </div>

            <button type="button" id="addPickupPointBtn" class="mt-2 mb-4 bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600">
                Add Pickup Point +
            </button>

            <label class="flex items-center mb-4">
                <input type="checkbox" name="status" value="1" checked class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded">
                <span class="text-gray-700">Enable this route</span>
            </label>

            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400" id="closeCreateRouteModalBtn">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save Route</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Route Modal --}}
<div id="editRouteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-lg w-full p-6 relative overflow-y-auto max-h-[90vh]">
        <h2 class="text-xl font-semibold mb-4">Edit Route</h2>
        <button type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" id="closeEditRouteModalX">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <form id="editRouteForm">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" id="edit_route_id" name="id">

            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Route Name</label>
                <input type="text" name="route_name" id="edit_route_name" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            
            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Description</label>
                <textarea name="description" id="edit_description" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" rows="2"></textarea>
            </div>

            <h3 class="text-lg font-semibold mt-6 mb-2 text-gray-800">Pickup Points</h3>
            <div id="editPickupPointsContainer">
                </div>

            <button type="button" id="addEditPickupPointBtn" class="mt-2 mb-4 bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600">
                Add Pickup Point +
            </button>

            <label class="flex items-center mb-4">
                <input type="checkbox" name="status" id="edit_status" value="1" class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded">
                <span class="text-gray-700">Enable this route</span>
            </label>

            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400" id="closeEditRouteModalBtn">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Update Route</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteRouteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
        <p class="text-gray-700 mb-6">Are you sure you want to delete route <span id="deleteRouteName" class="font-semibold"></span>?</p>
        <form id="deleteRouteForm">
            @csrf
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" id="delete_route_id" name="id">
        </form>
        <div class="flex justify-end space-x-4">
            <button type="button" id="closeDeleteRouteModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="confirmDeleteRouteBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
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
            "hideMethod": "fadeOut"
        };
        
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Initialize DataTable for Routes
        const routesTable = $('#routesTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search routes..."
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
        
        // Load routes data
        loadRoutesData();

        const createRouteModal = $('#createRouteModal');
        const editRouteModal = $('#editRouteModal');
        const deleteRouteModal = $('#deleteRouteModal');

        // Function to create pickup point input fields
        function createPickupPointFields(containerId, point = null) {
            const container = $(`#${containerId}`);
            const index = container.children('.pickup-point-group').length; // Get current count for unique names

            const group = $(`
                <div class="pickup-point-group border p-3 rounded mb-3 bg-gray-50 relative">
                    <h4 class="font-medium text-gray-700 mb-2">Pickup Point ${index + 1}</h4>
                    <button type="button" class="remove-pickup-point absolute top-2 right-2 text-red-500 hover:text-red-700">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <div class="mb-2">
                        <label class="block mb-1 text-sm font-medium text-gray-600">Name</label>
                        <input type="text" name="pickup_points[${index}][name]" value="${point ? point.name : ''}" required class="w-full px-3 py-2 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., School Gate" />
                    </div>
                </div>
            `);
            container.append(group);
        }

        // --- Create Route Modal handlers ---
        $('#openCreateRouteModal').click(() => {
            createRouteModal.removeClass('hidden');
            $('#createRouteForm')[0].reset(); // Clear form on open
            $('#createPickupPointsContainer').empty(); // Clear pickup points
            createPickupPointFields('createPickupPointsContainer'); // Add one empty field
        });

        $('#closeCreateRouteModalX, #closeCreateRouteModalBtn').click(() => {
            createRouteModal.addClass('hidden');
            $('#createRouteForm')[0].reset();
            $('#createPickupPointsContainer').empty();
        });

        $('#addPickupPointBtn').click(() => createPickupPointFields('createPickupPointsContainer'));

        // Handle remove pickup point button click (delegated event for dynamic elements)
        $('#createPickupPointsContainer, #editPickupPointsContainer').on('click', '.remove-pickup-point', function() {
            $(this).closest('.pickup-point-group').remove();
        });

        $('#createRouteForm').submit(function (e) {
            e.preventDefault();
            
            // Check if at least one pickup point exists
            if ($('#createPickupPointsContainer .pickup-point-group').length === 0) {
                toastr.error("Please add at least one pickup point.");
                return;
            }
            
            // Prepare form data for submission
            const formData = new FormData(this);
            
            // Remove any existing pickup_points field that might be in the form
            formData.delete('pickup_points');
            
            // Add pickup points directly as array elements
            $('#createPickupPointsContainer .pickup-point-group').each(function(index) {
                const name = $(this).find('input[name$="[name]"]').val();
                if (name) {
                    formData.append(`pickup_points[${index}][name]`, name);
                }
            });
            
            // Send AJAX request
            $.ajax({
                url: "{{ route('school.route.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        toastr.success(response.message || "Route created successfully");
                        
                        // Reset form and close modal
                        $('#createRouteForm')[0].reset();
                        $('#createPickupPointsContainer').empty();
                        createRouteModal.addClass('hidden');
                        
                        // Reload data
                        loadRoutesData();
                    } else {
                        toastr.error(response.message || "Failed to create route");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred while creating the route";
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


        // --- Edit Route Modal handlers ---
        $(document).on('click', '.editRouteBtn', function() {
            const routeId = $(this).data('id');
            
            // Fetch route data
            $.ajax({
                url: "/school/route/" + routeId,
                type: "GET",
                success: function(response) {
                    if (response.success) {
                        const route = response.route;
                        
                        // Populate form fields
                        $('#edit_route_id').val(route.id);
                        $('#edit_route_name').val(route.route_name);
                        $('#edit_description').val(route.description);
                        $('#edit_status').prop('checked', route.status);
                        
                        // Clear previous pickup points and load new ones
                        $('#editPickupPointsContainer').empty();
                        if (route.pickup_points && route.pickup_points.length > 0) {
                            route.pickup_points.forEach(point => createPickupPointFields('editPickupPointsContainer', point));
                        } else {
                            // Add a default empty one if none exist
                            createPickupPointFields('editPickupPointsContainer');
                        }
                        
                        // Show modal
                        editRouteModal.removeClass('hidden');
                    } else {
                        toastr.error(response.message || "Failed to fetch route data");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred while fetching route data";
                    toastr.error(errorMsg);
                }
            });
        });

        $('#closeEditRouteModalX, #closeEditRouteModalBtn').click(() => {
            editRouteModal.addClass('hidden');
            $('#editRouteForm')[0].reset();
            $('#editPickupPointsContainer').empty();
        });

        $('#addEditPickupPointBtn').click(() => createPickupPointFields('editPickupPointsContainer'));

        $('#editRouteForm').submit(function (e) {
            e.preventDefault();
            
            const routeId = $('#edit_route_id').val();
            if (!routeId) {
                toastr.error("Route ID is missing");
                return;
            }
            
            // Check if at least one pickup point exists
            if ($('#editPickupPointsContainer .pickup-point-group').length === 0) {
                toastr.error("Please add at least one pickup point.");
                return;
            }
            
            // Prepare form data for submission
            const formData = new FormData(this);
            
            // Remove any existing pickup_points field that might be in the form
            formData.delete('pickup_points');
            
            // Add pickup points directly as array elements
            $('#editPickupPointsContainer .pickup-point-group').each(function(index) {
                const name = $(this).find('input[name$="[name]"]').val();
                if (name) {
                    formData.append(`pickup_points[${index}][name]`, name);
                }
            });
            
            // Add method override for PUT
            formData.append('_method', 'PUT');
            
            // Send AJAX request
            $.ajax({
                url: "/school/route/" + routeId,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        toastr.success(response.message || "Route updated successfully");
                        
                        // Close modal
                        editRouteModal.addClass('hidden');
                        
                        // Reload data
                        loadRoutesData();
                    } else {
                        toastr.error(response.message || "Failed to update route");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred while updating the route";
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

        // --- Delete Route Modal handlers ---
        $(document).on('click', '.deleteRouteBtn', function() {
            const routeId = $(this).data('id');
            const routeName = $(this).data('route-name');
            
            // Set delete modal values
            $('#delete_route_id').val(routeId);
            $('#deleteRouteName').text(routeName);
            deleteRouteModal.removeClass('hidden');
        });

        $('#closeDeleteRouteModal').click(() => deleteRouteModal.addClass('hidden'));

        $('#confirmDeleteRouteBtn').click(function () {
            const routeId = $('#delete_route_id').val();
            if (!routeId) {
                toastr.error("Route ID is missing");
                return;
            }
            
            // Send AJAX request
            $.ajax({
                url: "/school/route/" + routeId,
                type: "DELETE",
                data: $('#deleteRouteForm').serialize(),
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        toastr.success(response.message || "Route deleted successfully");
                        
                        // Close modal
                        deleteRouteModal.addClass('hidden');
                        
                        // Reload data
                        loadRoutesData();
                    } else {
                        toastr.error(response.message || "Failed to delete route");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred while deleting the route";
                    toastr.error(errorMsg);
                }
            });
        });
        
        // Function to load routes data
        function loadRoutesData() {
            $.ajax({
                url: "{{ route('school.api.all-routes') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // Clear existing data
                        routesTable.clear();
                        
                        // Add new data
                        if (response.routes && response.routes.length > 0) {
                            response.routes.forEach(function(route) {
                                const statusClass = route.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                const statusText = route.status ? 'Enabled' : 'Disabled';
                                
                                // Create pickup points badges
                                let pickupPointsHtml = '<div class="flex flex-wrap gap-2">';
                                if (route.pickup_points && route.pickup_points.length > 0) {
                                    route.pickup_points.forEach(function(point) {
                                        pickupPointsHtml += `<span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">${point.name}</span>`;
                                    });
                                } else {
                                    pickupPointsHtml += '<span class="text-gray-500">No pickup points</span>';
                                }
                                pickupPointsHtml += '</div>';
                                
                                routesTable.row.add([
                                    route.id,
                                    route.route_name,
                                    pickupPointsHtml,
                                    `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">${statusText}</span>`,
                                    `<button class="text-indigo-600 hover:text-indigo-900 font-medium editRouteBtn" data-id="${route.id}">Edit</button>
                                    <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteRouteBtn" data-id="${route.id}" data-route-name="${route.route_name}">Delete</button>`
                                ]).draw(false);
                            });
                        } else {
                            // No data message
                            routesTable.row.add([
                                '',
                                '<div class="text-center text-gray-500 py-4">No routes found</div>',
                                '', '', ''
                            ]).draw(false);
                        }
                    } else {
                        toastr.error(response.message || "Failed to load routes data");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred while loading routes data";
                    toastr.error(errorMsg);
                    
                    // Show empty state
                    routesTable.clear().draw();
                    routesTable.row.add([
                        '',
                        '<div class="text-center text-red-500 py-4">Error loading data. Please try again.</div>',
                        '', '', ''
                    ]).draw(false);
                }
            });
        }
    });
</script>