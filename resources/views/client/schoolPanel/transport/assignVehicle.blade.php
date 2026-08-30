@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Transport / <span class="text-l text-gray-500">Assign Vehicle to Route</span>
            </h1>
            <div class="flex space-x-4">
                <button id="openAssignVehicleModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                    Assign Vehicle to Route +
                </button>
                {{-- <button id="openCreateRouteModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Create New Route +
                </button> --}}
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <table id="routesTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">ID</th>
                        <th class="px-6 py-3 font-semibold">Route Name</th>
                        <th class="px-6 py-3 font-semibold" style="width: 24rem;">Pickup Points</th>
                        <th class="px-6 py-3 font-semibold">Assigned Vehicle</th>
                        <th class="px-6 py-3 font-semibold">Assigned Driver</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    <tr data-id="1"
                        data-route_name="Route A - North"
                        data-pickup_points='[{"name":"School Gate"}, {"name":"Main Road Stop"}]'
                        data-vehicle_id="101"
                        data-vehicle_name="Toyota Innova (MH12AB1234)"
                        data-driver_id="1"
                        data-driver_name="John Doe">
                        <td class="px-6 py-4">1</td>
                        <td class="px-6 py-4">Route A - North</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">School Gate</span>
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Main Road Stop</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">Toyota Innova (MH12AB1234)</td>
                        <td class="px-6 py-4">John Doe</td>
                        <td class="px-6 py-4">
                            <button class="text-indigo-600 hover:text-indigo-900 font-medium editRouteBtn" data-id="1">Edit</button>
                            <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteRouteBtn" data-id="1">Delete</button>
                        </td>
                    </tr>
                    <tr data-id="2"
                        data-route_name="Route B - South"
                        data-pickup_points='[{"name":"City Center"}]'
                        data-vehicle_id="102"
                        data-vehicle_name="Maruti Omni (MH12XY5678)"
                        data-driver_id="2"
                        data-driver_name="Jane Smith">
                        <td class="px-6 py-4">2</td>
                        <td class="px-6 py-4">Route B - South</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">City Center</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">Maruti Omni (MH12XY5678)</td>
                        <td class="px-6 py-4">Jane Smith</td>
                        <td class="px-6 py-4">
                            <button class="text-indigo-600 hover:text-indigo-900 font-medium editRouteBtn" data-id="2">Edit</button>
                            <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteRouteBtn" data-id="2">Delete</button>
                        </td>
                    </tr>
                    <tr data-id="3"
                        data-route_name="Route C - East"
                        data-pickup_points='[{"name":"Park Entrance"}, {"name":"Mall Exit"}]'
                        data-vehicle_id=""
                        data-vehicle_name="Not Assigned"
                        data-driver_id=""
                        data-driver_name="Not Assigned">
                        <td class="px-6 py-4">3</td>
                        <td class="px-6 py-4">Route C - East</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Park Entrance</span>
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Mall Exit</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-red-500">Not Assigned</td>
                        <td class="px-6 py-4 text-red-500">Not Assigned</td>
                        <td class="px-6 py-4">
                            <button class="text-indigo-600 hover:text-indigo-900 font-medium editRouteBtn" data-id="3">Edit</button>
                            <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteRouteBtn" data-id="3">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create Route Modal (UNTOUCHED) --}}
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

            <h3 class="text-lg font-semibold mt-6 mb-2 text-gray-800">Pickup Points</h3>
            <div id="createPickupPointsContainer">
                </div>

            <button type="button" id="addPickupPointBtn" class="mb-4 bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600">
                Add Pickup Point +
            </button>


            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400" id="closeCreateRouteModalBtn">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save Route</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Route Modal (UNTOUCHED) --}}
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
            <input type="hidden" name="edit_id" />

            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Route Name</label>
                <input type="text" name="edit_route_name" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <h3 class="text-lg font-semibold mt-6 mb-2 text-gray-800">Pickup Points</h3>
            <div id="editPickupPointsContainer">
                </div>

            <button type="button" id="addEditPickupPointBtn" class="mb-4 bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600">
                Add Pickup Point +
            </button>

            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400" id="closeEditRouteModalBtn">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Update Route</button>
            </div>
        </form>
    </div>
</div>

{{-- Assign Vehicle to Route Modal (NEW) --}}
<div id="assignVehicleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4" id="assignModalTitle">Assign Vehicle to Route</h2>
        <button type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" id="closeAssignVehicleModalX">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <form id="assignVehicleForm">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Select Route</label>
                <select name="route_id" id="assignRouteSelect" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">-- Select Route --</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Select Driver</label>
                <select name="driver_id" id="assignDriverSelect" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">-- Select Driver --</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Vehicle</label>
                <div id="assignedVehicleInfo" class="px-3 py-2 border rounded bg-gray-50 text-gray-700">
                    No vehicle selected
                </div>
                <input type="hidden" name="vehicle_id" id="hiddenVehicleId">
                <p class="text-xs text-gray-500 mt-1">Vehicle is automatically assigned based on the selected driver</p>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400" id="closeAssignVehicleModalBtn">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-purple-600 text-white hover:bg-purple-700" id="assignSubmitBtn">Assign</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Confirmation Modal (UNTOUCHED) --}}
<div id="deleteRouteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
        <p class="text-gray-700 mb-6">Are you sure you want to delete this route?</p>
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
        // Initialize DataTable for Routes
        const routesDataTable = $('#routesTable').DataTable({
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
        });

        const createRouteModal = $('#createRouteModal');
        const editRouteModal = $('#editRouteModal');
        const assignVehicleModal = $('#assignVehicleModal'); // NEW MODAL
        const deleteRouteModal = $('#deleteRouteModal');

        // Static data for dropdowns (in a real app, these would come from AJAX/backend)
        const allRoutes = [
            { id: 1, name: "Route A - North" },
            { id: 2, name: "Route B - South" },
            { id: 3, name: "Route C - East" },
        ];
        const allDrivers = [
            { id: 1, name: "John Doe" },
            { id: 2, name: "Jane Smith" },
            { id: 3, name: "Thomas" },
        ];
        const allVehicles = [
            { id: 101, name: "Toyota Innova (MH12AB1234)" },
            { id: 102, name: "Maruti Omni (MH12XY5678)" },
            { id: 103, name: "School Bus 1 (DL01CD9999)" },
            { id: 104, name: "Van (KA01FG1234)" },
        ];

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
            // Re-index names if needed, but for simple saving, backend can handle based on submitted array
        });

        $('#createRouteForm').submit(function (e) {
            e.preventDefault();
            const routeName = $(this).find('input[name="route_name"]').val();
            
            // Check if at least one pickup point exists
            if ($('#createPickupPointsContainer .pickup-point-group').length === 0) {
                alert("Please add at least one pickup point.");
                return;
            }
            
            // Prepare form data for submission
            const formData = new FormData(this);
            
            // Clear existing pickup_points that might be in the form
            formData.delete('pickup_points');
            
            // Add pickup points directly as array elements
            $('#createPickupPointsContainer .pickup-point-group').each(function(index) {
                const name = $(this).find('input[name$="[name]"]').val();
                if (name) {
                    formData.append(`pickup_points[${index}][name]`, name);
                }
            });

            // TODO: AJAX POST to create route
            alert(`Create Route:\nName: ${routeName}`);
            
            // In a real app, you would use:
            // $.ajax({
            //     url: "{{ route('school.route.store') }}",
            //     type: "POST",
            //     data: formData,
            //     processData: false,
            //     contentType: false,
            //     success: function(response) { /* Handle success */ },
            //     error: function(xhr) { /* Handle error */ }
            // });
            
            createRouteModal.addClass('hidden');
            this.reset();
            $('#createPickupPointsContainer').empty(); // Clear for next open
            
            // Simulate adding a new row to the table
            const newId = routesDataTable.rows().count() + 1;
            let pickupPointsHtml = '<div class="flex flex-wrap gap-2">';
            $('#createPickupPointsContainer .pickup-point-group').each(function() {
                const name = $(this).find('input[name$="[name]"]').val();
                if (name) {
                    pickupPointsHtml += `<span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">${name}</span>`;
                }
            });
            pickupPointsHtml += '</div>';
            
            routesDataTable.row.add([
                newId,
                routeName,
                pickupPointsHtml,
                '<span class="text-red-500">Not Assigned</span>',
                '<span class="text-red-500">Not Assigned</span>',
                `<button class="text-indigo-600 hover:text-indigo-900 font-medium editRouteBtn" data-id="${newId}">Edit</button>
                 <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteRouteBtn" data-id="${newId}">Delete</button>`
            ]).draw(false).node().dataset.id = newId; // Add data-id to the new row
        });


        // --- Edit Route Modal handlers ---
        // Listen for clicks on the editRouteBtn with proper delegated event handling
        $(document).on('click', '.editRouteBtn', function () {
            const routeId = $(this).data('id');
            const row = $(this).closest('tr'); // Get the current row to access its data attributes

            isEditingAssignment = true;
            $('#assignModalTitle').text('Update Vehicle Assignment');
            $('#assignSubmitBtn').text('Update Assignment');
            $('#assignSubmitBtn').removeClass('bg-blue-600 hover:bg-blue-700').addClass('bg-green-600 hover:bg-green-700');


            // 1. Fetch all routes to populate the #assignRouteSelect dropdown
            $.ajax({
                url: "{{ route('school.api.all-routes') }}", // Make sure this route is defined
                type: "GET",
                dataType: "json",
                success: function(routeResponse) {
                    if (routeResponse.success && routeResponse.routes) {
                        assignRouteSelect.empty().append('<option value="">-- Select Route --</option>');
                        routeResponse.routes.forEach(r => {
                            assignRouteSelect.append(`<option value="${r.id}">${r.route_name}</option>`);
                        });
                        // After populating, set the value and disable for editing
                        assignRouteSelect.val(routeId).prop('disabled', true);

                        // 2. Fetch all drivers to populate the #assignDriverSelect dropdown
                        $.ajax({
                            url: "{{ route('school.api.all-vehicle-drivers') }}", // Make sure this route is defined
                            type: "GET",
                            dataType: "json",
                            success: function(driverResponse) {
                                if (driverResponse.success && driverResponse.drivers) {
                                    assignDriverSelect.empty().append('<option value="">-- Select Driver --</option>');
                                    
                                    const currentDriverId = row.attr('data-driver_id'); // Use .attr() for data-* attributes set by jQuery's .attr() or in HTML
                                    
                                    driverResponse.drivers.forEach(driver => {
                                        if (driver.vehicle) { // Only list drivers with an assigned vehicle
                                            const isSelected = currentDriverId && driver.id == currentDriverId;
                                            assignDriverSelect.append(
                                                `<option value="${driver.id}" 
                                                data-vehicle-id="${driver.vehicle.id}" 
                                                data-vehicle-name="${driver.vehicle.vehicle_model} (${driver.vehicle.registration_no})"
                                                ${isSelected ? 'selected' : ''}>${driver.driver_name}</option>`
                                            );
                                        }
                                    });
                                    
                                    assignDriverSelect.trigger('change'); // Trigger change to update vehicle info display
                                    assignVehicleModal.removeClass('hidden'); // Show the modal
                                } else {
                                    alert(driverResponse.message || "Failed to load drivers for editing.");
                                    assignRouteSelect.prop('disabled', false); // Re-enable route select on error
                                }
                            },
                            error: function(xhr) {
                                alert("Error loading drivers: " + (xhr.responseJSON?.message || xhr.statusText));
                                assignRouteSelect.prop('disabled', false); // Re-enable route select on error
                            }
                        });
                    } else {
                        alert(routeResponse.message || "Failed to load routes for editing.");
                    }
                },
                error: function(xhr) {
                    alert("Error loading routes: " + (xhr.responseJSON?.message || xhr.statusText));
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
            const id = $(this).find('input[name="edit_id"]').val();
            const routeName = $(this).find('input[name="edit_route_name"]').val();
            
            // Check if at least one pickup point exists
            if ($('#editPickupPointsContainer .pickup-point-group').length === 0) {
                alert("Please add at least one pickup point.");
                return;
            }
            
            // Prepare form data for submission
            const formData = new FormData(this);
            
            // Clear existing pickup_points that might be in the form
            formData.delete('pickup_points');
            
            // Add pickup points directly as array elements
            $('#editPickupPointsContainer .pickup-point-group').each(function(index) {
                const name = $(this).find('input[name$="[name]"]').val();
                if (name) {
                    formData.append(`pickup_points[${index}][name]`, name);
                }
            });
            
            // Add method override for PUT if needed
            formData.append('_method', 'PUT');

            // TODO: AJAX POST to update route
            alert(`Update Route:\nID: ${id}\nName: ${routeName}`);
            
            // In a real app, you would use:
            // $.ajax({
            //     url: "/school/route/" + id,
            //     type: "POST",
            //     data: formData,
            //     processData: false,
            //     contentType: false,
            //     success: function(response) { /* Handle success */ },
            //     error: function(xhr) { /* Handle error */ }
            // });
            
            editRouteModal.addClass('hidden');

            // Simulate updating the table row
            const row = routesDataTable.row($(`tr[data-id="${id}"]`));
            const rowData = row.data();
            rowData[1] = routeName; // Update Route Name
            
            // Generate pickup points HTML
            let pickupPointsHtml = '<div class="flex flex-wrap gap-2">';
            $('#editPickupPointsContainer .pickup-point-group').each(function() {
                const name = $(this).find('input[name$="[name]"]').val();
                if (name) {
                    pickupPointsHtml += `<span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">${name}</span>`;
                }
            });
            pickupPointsHtml += '</div>';
            
            rowData[2] = pickupPointsHtml; // Update Pickup Points display

            // Update data-route_name attribute
            $(row.node()).data('route_name', routeName);
            
            row.data(rowData).draw(false);
        });

        // --- Assign Vehicle Modal handlers (NEW) ---
        const assignRouteSelect = $('#assignRouteSelect');
        const assignDriverSelect = $('#assignDriverSelect');
        let isEditingAssignment = false;

        $('#openAssignVehicleModal').click(() => {
            // Reset editing state - this is a new assignment
            isEditingAssignment = false;
            $('#assignModalTitle').text('Assign Vehicle to Route');
            $('#assignSubmitBtn').text('Assign');
            
            // Enable route selection for new assignments
            assignRouteSelect.prop('disabled', false);
            
            // Populate dropdowns each time the modal opens
            assignRouteSelect.empty().append('<option value="">-- Select Route --</option>');
            
            // Fetch routes data from API
            $.ajax({
                url: "{{ route('school.api.all-routes') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success && response.routes) {
                        response.routes.forEach(route => {
                            assignRouteSelect.append(`<option value="${route.id}">${route.route_name}</option>`);
                        });
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "Failed to fetch routes";
                    alert(errorMsg);
                }
            });

            // Fetch drivers data from API
            $.ajax({
                url: "{{ route('school.api.all-vehicle-drivers') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success && response.drivers) {
                        assignDriverSelect.empty().append('<option value="">-- Select Driver --</option>');
                        response.drivers.forEach(driver => {
                            // Only add drivers who have a vehicle assigned
                            if (driver.vehicle) {
                                assignDriverSelect.append(`<option value="${driver.id}" 
                                    data-vehicle-id="${driver.vehicle.id}" 
                                    data-vehicle-name="${driver.vehicle.vehicle_model} (${driver.vehicle.registration_no})">${driver.driver_name}</option>`);
                            }
                        });
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "Failed to fetch drivers";
                    alert(errorMsg);
                }
            });

            assignVehicleModal.removeClass('hidden');
            
            // Reset vehicle info
            $('#assignedVehicleInfo').text('No vehicle selected');
            $('#hiddenVehicleId').val('');
        });

        // Handle driver change to display vehicle info
        $('#assignDriverSelect').change(function() {
            const selectedOption = $(this).find('option:selected');
            
            if (selectedOption.val()) {
                const vehicleId = selectedOption.data('vehicle-id');
                const vehicleName = selectedOption.data('vehicle-name');
                
                if (vehicleId && vehicleName) {
                    $('#assignedVehicleInfo').text(vehicleName);
                    $('#hiddenVehicleId').val(vehicleId);
                } else {
                    $('#assignedVehicleInfo').text('No vehicle assigned to this driver');
                    $('#hiddenVehicleId').val('');
                }
            } else {
                $('#assignedVehicleInfo').text('No vehicle selected');
                $('#hiddenVehicleId').val('');
            }
        });

        $('#closeAssignVehicleModalX, #closeAssignVehicleModalBtn').click(() => {
            assignVehicleModal.addClass('hidden');
            $('#assignVehicleForm')[0].reset();
        });

        $('#assignVehicleForm').submit(function (e) {
            e.preventDefault();
            const routeId = assignRouteSelect.val();
            const routeName = assignRouteSelect.find('option:selected').text();
            const driverId = assignDriverSelect.val();
            const driverName = assignDriverSelect.find('option:selected').text();
            const vehicleId = $('#hiddenVehicleId').val();
            const vehicleName = $('#assignedVehicleInfo').text();

            if (!routeId || !driverId) {
                alert("Please select a route and driver.");
                return;
            }
            
            if (!vehicleId) {
                alert("No vehicle is assigned to the selected driver.");
                return;
            }

            // Send AJAX request to save assignment
            $.ajax({
                url: "{{ route('school.route.assign-vehicle') }}",
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    route_id: routeId,
                    driver_id: driverId,
                    vehicle_id: vehicleId,
                    is_update: isEditingAssignment ? 1 : 0
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        const actionText = isEditingAssignment ? "updated" : "assigned";
                        alert(`Vehicle and driver successfully ${actionText} to route!`);
                        
                        // Close modal and reset form
                        assignVehicleModal.addClass('hidden');
                        $('#assignVehicleForm')[0].reset();
                        $('#assignedVehicleInfo').text('No vehicle selected');
                        
                        // Reload data or update table
                        loadRoutesData();
                    } else {
                        alert(response.message || "Failed to assign vehicle to route");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred during assignment";
                    alert(errorMsg);
                }
            });
        });


        // --- Delete Route Modal handlers ---
        let deleteRouteId = null;
        // Listen for clicks on the deleteRouteBtn (delegated event for dynamically added rows)
        $('#routesTable tbody').on('click', '.deleteRouteBtn', function () {
            deleteRouteId = $(this).data('id');
            deleteRouteModal.removeClass('hidden');
        });

        $('#closeDeleteRouteModal').click(() => deleteRouteModal.addClass('hidden'));

        $('#confirmDeleteRouteBtn').click(function () {
            if (!deleteRouteId) return;
            // TODO: AJAX call to delete route by deleteRouteId
            alert(`Route ID ${deleteRouteId} deleted (backend needed).`);
            deleteRouteModal.addClass('hidden');

            // Simulate removing the row from DataTable
            routesDataTable.row($(`tr[data-id="${deleteRouteId}"]`)).remove().draw(false);

            deleteRouteId = null; // Reset for next use
        });

        // Function to load routes data
        function loadRoutesData() {
            $.ajax({
                url: "{{ route('school.api.assigned-routes') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // Clear existing data
                        routesDataTable.clear();
                        
                        // Add new data
                        if (response.routes && response.routes.length > 0) {
                            response.routes.forEach(function(route) {
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
                                
                                // Get assigned vehicle and driver info
                                const vehicleInfo = route.vehicle ? 
                                    `${route.vehicle.vehicle_model} (${route.vehicle.registration_no})` : 
                                    '<span class="text-red-500">Not Assigned</span>';
                                    
                                const driverInfo = route.driver ? 
                                    route.driver.driver_name : 
                                    '<span class="text-red-500">Not Assigned</span>';
                                
                                // Create a new row with all the necessary data attributes
                                const row = routesDataTable.row.add([
                                    route.id,
                                    route.route_name,
                                    pickupPointsHtml,
                                    vehicleInfo,
                                    driverInfo,
                                    `<button class="text-indigo-600 hover:text-indigo-900 font-medium editRouteBtn" data-id="${route.id}">Edit</button>
                                    <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteRouteBtn" data-id="${route.id}">Delete</button>`
                                ]).draw(false).node();
                                
                                // Store the entire route object as JSON in a data attribute
                                $(row).attr('data-id', route.id);
                                $(row).attr('data-route_name', route.route_name);
                                $(row).attr('data-pickup_points', JSON.stringify(route.pickup_points || []));
                                if (route.vehicle) {
                                    $(row).attr('data-vehicle_id', route.vehicle.id);
                                    $(row).attr('data-vehicle_name', `${route.vehicle.vehicle_model} (${route.vehicle.registration_no})`);
                                }
                                if (route.driver) {
                                    $(row).attr('data-driver_id', route.driver.id);
                                    $(row).attr('data-driver_name', route.driver.driver_name);
                                }
                            });
                        } else {
                            // No data message
                            routesDataTable.row.add([
                                '',
                                '<div class="text-center text-gray-500 py-4">No routes found</div>',
                                '', '', '', ''
                            ]).draw(false);
                        }
                    } else {
                        alert(response.message || "Failed to load routes data");
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "An error occurred while loading routes data";
                    alert(errorMsg);
                }
            });
        }
        
        // Initial load of routes data
        loadRoutesData();
    });
</script>