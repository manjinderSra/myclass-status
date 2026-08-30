@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Fees / <span class="text-l text-gray-500">Assign Fees</span>
            </h1>
            <button id="openAssignFeesModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Assign New Fees +
            </button>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Assigned Fees Table --}}
        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Assigned Fees Overview</h2>
            
            <div class="overflow-x-auto">
                <table id="assignedFeesOverviewTable" class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                            <th class="px-6 py-3 font-semibold">S. No.</th>
                            <th class="px-6 py-3 font-semibold">Fees Group</th>
                            <th class="px-6 py-3 font-semibold">Fees Type</th>
                            <th class="px-6 py-3 font-semibold">Student</th>
                            <th class="px-6 py-3 font-semibold">Class</th>
                            <th class="px-6 py-3 font-semibold">Section</th>
                            <th class="px-6 py-3 font-semibold">Amount ($)</th>
                            <th class="px-6 py-3 font-semibold">Gender</th>
                            <th class="px-6 py-3 font-semibold">Category</th>
                            <th class="px-6 py-3 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($assignFees as $key => $assign)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">{{ $key + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $assign->feeGroup->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $assign->feeType->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $assign->student ? $assign->student->first_name . ' ' . $assign->student->last_name : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $assign->student->class->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $assign->student->section->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                    ${{ number_format($assign->feeMaster->amount ?? 0, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $assign->student->gender ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                    {{ $assign->student->category ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm whitespace-nowrap">
                                    <button onclick="editAssignFee({{ $assign->id }})" 
                                           class="text-blue-600 hover:text-blue-800 mr-3 font-medium">Edit</button>
                                    <button onclick="deleteAssignFee({{ $assign->id }})" 
                                           class="text-red-600 hover:text-red-800 font-medium">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-4 text-center text-gray-500 text-sm">
                                    No fee assignments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Assign New Fees Modal --}}
<div id="assignFeesModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[9998]">
    <div class="bg-white rounded-lg max-w-6xl w-full p-6 relative h-[90vh] flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Assign New Fees</h2>
            <button type="button" class="text-gray-500 hover:text-gray-700" id="closeAssignFeesModalX">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="mb-4 flex justify-end">
            <button id="openFilterModalBtn" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 transition flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                </svg>
                <span>Filter</span>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto mb-4 space-y-6">
            {{-- Fees Type Table --}}
            <div>
                <h3 class="text-lg font-semibold mb-2">List of Fees Type</h3>
                <div class="bg-gray-50 rounded-lg p-4 overflow-auto border">
                    <table id="feesTypeListTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr class="text-gray-700 text-left text-xs uppercase">
                                <th class="p-3">
                                    <input type="checkbox" id="selectAllFeesTypes" class="form-checkbox h-4 w-4 text-blue-600 rounded" />
                                </th>
                                <th class="px-3 py-2 font-semibold">Fees Type</th>
                                <th class="px-3 py-2 font-semibold">Fees Group</th>
                                <th class="px-3 py-2 font-semibold">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-200 bg-white">
                            @foreach($feeMasters as $fee)
                                <tr data-fees_id="{{ $fee->id }}"
                                    data-fee_group_id="{{ $fee->fee_group_id }}" 
                                    data-fee_type_id="{{ $fee->fee_type_id }}"
                                    class="hover:bg-gray-50">
                                    <td class="p-3">
                                        <input type="checkbox" class="fees-type-checkbox form-checkbox h-4 w-4 text-blue-600 rounded">
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">{{ $fee->feeType->name ?? 'N/A' }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap">{{ $fee->feeGroup->name ?? 'N/A' }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap">${{ number_format($fee->amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Student Details Table --}}
            <div>
                <h3 class="text-lg font-semibold mb-2">Student Details</h3>
                <div class="bg-gray-50 rounded-lg p-4 overflow-auto border">
                    <table id="studentDetailsTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr class="text-gray-700 text-left text-xs uppercase">
                                <th class="p-3">
                                    <input type="checkbox" id="selectAllStudents" class="form-checkbox h-4 w-4 text-blue-600 rounded" />
                                </th>
                                <th class="px-3 py-2 font-semibold">Admission Number</th>
                                <th class="px-3 py-2 font-semibold">Student</th>
                                <th class="px-3 py-2 font-semibold">Roll Number</th>
                                <th class="px-3 py-2 font-semibold">Class</th>
                                <th class="px-3 py-2 font-semibold">Section</th>
                                <th class="px-3 py-2 font-semibold">Gender</th>
                                <th class="px-3 py-2 font-semibold">Student Category</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-200 bg-white">
                            @foreach($students as $student)
                                <tr data-student_id="{{ $student->id }}" class="hover:bg-gray-50">
                                    <td class="p-3">
                                        <input type="checkbox" class="student-checkbox form-checkbox h-4 w-4 text-blue-600 rounded">
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-blue-600 hover:underline cursor-pointer">
                                        {{ $student->admission_number }}
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap flex items-center">
                                        <img src="{{ $student->photo ?? 'https://i.pravatar.cc/24' }}" 
                                             class="h-6 w-6 rounded-full mr-2 object-cover">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">{{ $student->roll_number ?? 'N/A' }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap">{{ $student->class->name ?? 'N/A' }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap">{{ $student->section->name ?? 'N/A' }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap">{{ $student->gender ?? 'N/A' }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap">{{ $student->category ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Selection Summary --}}
        <div id="selectionSummary" class="bg-blue-500 text-white p-3 rounded-lg text-center text-sm mb-4">
            Selected <span id="selectedFeesCount">0</span> Fees Type, <span id="selectedStudentsCount">0</span> Students
        </div>

        <div class="flex justify-end space-x-4">
            <button type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 transition" id="closeAssignFeesModalBtn">Cancel</button>
            <button type="button" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 transition" id="addAssignFeesBtn">Add Fees</button>
        </div>
    </div>
</div>

{{-- Filter Modal --}}
<div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[9999]">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Apply Filters</h2>
            <button type="button" class="text-gray-500 hover:text-gray-700" id="closeFilterModalX">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="space-y-4">
            <div>
                <label for="filterFeesGroup" class="block text-sm font-medium text-gray-700">Fees Group</label>
                <select id="filterFeesGroup" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">Select</option>
                    @foreach($feeGroups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="filterFeesType" class="block text-sm font-medium text-gray-700">Fees Type</label>
                <select id="filterFeesType" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">Select</option>
                    @foreach($feeTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="filterClass" class="block text-sm font-medium text-gray-700">Class</label>
                <select id="filterClass" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">Select</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="filterSection" class="block text-sm font-medium text-gray-700">Section</label>
                <select id="filterSection" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">Select</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="filterGender" class="block text-sm font-medium text-gray-700">Gender</label>
                <select id="filterGender" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div>
                <label for="filterCategory" class="block text-sm font-medium text-gray-700">Student Category</label>
                <select id="filterCategory" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">Select</option>
                    @php
                        $categories = $students->pluck('category')->unique()->filter();
                    @endphp
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-end space-x-4 mt-6">
            <button type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400 transition" id="resetFiltersBtn">Reset</button>
            <button type="button" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 transition" id="applyFiltersBtn">Apply Filters</button>
        </div>
    </div>
</div>

{{-- Edit Assigned Fee Modal --}}
<div id="editAssignFeeModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden z-[10000]">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Edit Assigned Fee</h2>

        <form id="editAssignFeeForm">
            <input type="hidden" id="edit_assign_id">

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Student</label>
                <input type="text" id="edit_student_name" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Fee Group</label>
                <input type="text" id="edit_fee_group" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Fee Type</label>
                <input type="text" id="edit_fee_type" class="w-full border rounded px-3 py-2 bg-gray-100" readonly>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                <input type="number" id="edit_amount" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="cancelEditFee" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

{{-- DataTables and jQuery --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

{{-- Enhanced DataTables Styling --}}
<style>
    /* DataTables Wrapper */
    div.dataTables_wrapper { 
        width: 100%; 
        margin: 0 auto;
    }

    /* Search and Length Controls */
    .dataTables_length,
    .dataTables_filter {
        margin-bottom: 1rem;
    }

    .dataTables_length select, 
    .dataTables_filter input {
        @apply border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
        transition: all 0.2s ease;
    }

    .dataTables_filter input { 
        width: 20rem !important;
        background-color: #f9fafb;
    }

    .dataTables_filter input:focus {
        background-color: white;
    }

    /* Labels */
    .dataTables_length label,
    .dataTables_filter label {
        @apply text-gray-700 text-sm font-medium;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Pagination */
    .dataTables_paginate { 
        @apply flex items-center gap-1 mt-4; 
    }

    .dataTables_paginate a {
        @apply border border-gray-300 rounded-lg px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 cursor-pointer transition-all duration-200;
        min-width: 2.5rem;
        text-align: center;
    }

    .dataTables_paginate .current { 
        @apply bg-blue-600 text-white border-blue-600 hover:bg-blue-700 hover:border-blue-700; 
        pointer-events: auto; 
    }

    .dataTables_paginate .disabled { 
        @apply text-gray-400 cursor-not-allowed border-gray-200 hover:bg-transparent hover:border-gray-200 hover:text-gray-400; 
    }

    /* Info Text */
    .dataTables_info { 
        @apply text-gray-600 text-sm mt-2 font-medium; 
    }

    /* Table Headers */
    table.dataTable thead th {
        @apply border-b-2 border-gray-200 bg-gray-100;
        position: relative;
    }

    table.dataTable thead th:hover {
        background-color: #f3f4f6;
    }

    /* Sorting Icons */
    table.dataTable thead .sorting:before,
    table.dataTable thead .sorting_asc:before,
    table.dataTable thead .sorting_desc:before,
    table.dataTable thead .sorting:after,
    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_desc:after {
        opacity: 0.3;
        font-size: 0.8em;
    }

    table.dataTable thead .sorting_asc:after,
    table.dataTable thead .sorting_desc:after {
        opacity: 1;
    }

    /* Table Body */
    table.dataTable tbody tr {
        transition: background-color 0.2s ease;
    }

    table.dataTable tbody tr:hover {
        @apply bg-blue-50;
    }

    /* No Footer Border */
    table.dataTable.no-footer {
        @apply border-b border-gray-200;
    }

    /* Responsive Table Container */
    .dataTables_scroll {
        overflow-x: auto;
    }

    /* Loading State */
    .dataTables_processing {
        @apply bg-white border border-gray-300 rounded-lg shadow-lg;
        padding: 1rem 2rem;
        font-weight: 500;
        color: #4b5563;
    }
</style>

<script>
    $(document).ready(function () {
        // Initialize DataTables with enhanced configuration
        $('#assignedFeesOverviewTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search assigned fees...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                },
                emptyTable: "No fee assignments found"
            },
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            pageLength: 10,
            dom: "<'flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
                 "<'overflow-x-auto't>" +
                 "<'flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mt-4'<'dataTables_info'i><'dataTables_paginate'p>>",
            columnDefs: [
                { orderable: true, targets: [0, 1, 2, 3, 4, 5, 6, 7, 8] },
                { orderable: false, targets: [9] },
                { className: "text-center", targets: [0] }
            ],
            order: [[0, 'asc']],
            responsive: true,
            processing: true,
            stateSave: false
        });

        // Modal elements
        const assignFeesModal = $('#assignFeesModal');
        const filterModal = $('#filterModal');
        const selectedFeesCountSpan = $('#selectedFeesCount');
        const selectedStudentsCountSpan = $('#selectedStudentsCount');

        // Modal Open/Close
        $('#openAssignFeesModal').click(() => {
            assignFeesModal.removeClass('hidden');
            resetAllFilters();
            updateSelectionSummary();
        });

        $('#closeAssignFeesModalX, #closeAssignFeesModalBtn').click(() => {
            assignFeesModal.addClass('hidden');
        });

        $('#openFilterModalBtn').click(() => {
            filterModal.removeClass('hidden');
        });

        $('#closeFilterModalX').click(() => {
            filterModal.addClass('hidden');
        });

        // Checkbox selection logic
        $('#selectAllFeesTypes').change(function () {
            $('.fees-type-checkbox').prop('checked', this.checked);
            updateSelectionSummary();
        });

        $('#selectAllStudents').change(function () {
            $('.student-checkbox').prop('checked', this.checked);
            updateSelectionSummary();
        });

        $(document).on('change', '.fees-type-checkbox', function () {
            const totalFees = $('.fees-type-checkbox').length;
            const checkedFees = $('.fees-type-checkbox:checked').length;
            $('#selectAllFeesTypes').prop('checked', totalFees === checkedFees);
            updateSelectionSummary();
        });

        $(document).on('change', '.student-checkbox', function () {
            const totalStudents = $('.student-checkbox').length;
            const checkedStudents = $('.student-checkbox:checked').length;
            $('#selectAllStudents').prop('checked', totalStudents === checkedStudents);
            updateSelectionSummary();
        });

        function updateSelectionSummary() {
            const selectedFees = $('.fees-type-checkbox:checked').length;
            const selectedStudents = $('.student-checkbox:checked').length;
            selectedFeesCountSpan.text(selectedFees);
            selectedStudentsCountSpan.text(selectedStudents);
        }

        // ===== CASCADING FILTERS =====
        
        // Cascading filter: Fee Group -> Fee Type
        $('#filterFeesGroup').change(function() {
            const selectedGroupId = $(this).val();
            const feeTypeDropdown = $('#filterFeesType');
            
            // Clear the fee type dropdown
            feeTypeDropdown.empty().append('<option value="">Select</option>');
            
            if (selectedGroupId) {
                // Fetch fee masters for the selected group
                $.get("{{ route('school.assignFee.filterFeeMasters') }}", {
                    fee_group_id: selectedGroupId
                }, function(feeMasters) {
                    // Extract unique fee types from the filtered fee masters
                    const uniqueFeeTypes = new Map();
                    
                    feeMasters.forEach(fee => {
                        if (fee.fee_type && fee.fee_type.id && fee.fee_type.name) {
                            uniqueFeeTypes.set(fee.fee_type.id, fee.fee_type.name);
                        }
                    });
                    
                    // Populate the fee type dropdown with only relevant types
                    uniqueFeeTypes.forEach((name, id) => {
                        feeTypeDropdown.append(`<option value="${id}">${name}</option>`);
                    });
                    
                    // Show message if no fee types found for this group
                    if (uniqueFeeTypes.size === 0) {
                        feeTypeDropdown.append('<option value="" disabled>No fee types available for this group</option>');
                    }
                }).fail(function() {
                    alert('Failed to load fee types for the selected group.');
                });
            } else {
                // If no group selected, show all fee types
                @foreach($feeTypes as $type)
                    feeTypeDropdown.append('<option value="{{ $type->id }}">{{ $type->name }}</option>');
                @endforeach
            }
        });

        // Cascading filter: Class -> Section
        $('#filterClass').change(function() {
            const selectedClassId = $(this).val();
            const sectionDropdown = $('#filterSection');
            
            // Clear the section dropdown
            sectionDropdown.empty().append('<option value="">Select</option>');
            
            if (selectedClassId) {
                // Fetch students for the selected class
                $.get("{{ route('school.assignFee.filterStudents') }}", {
                    class_id: selectedClassId
                }, function(students) {
                    // Extract unique sections from the filtered students
                    const uniqueSections = new Map();
                    
                    students.forEach(student => {
                        if (student.section && student.section.id && student.section.name) {
                            uniqueSections.set(student.section.id, student.section.name);
                        }
                    });
                    
                    // Populate the section dropdown with only relevant sections
                    uniqueSections.forEach((name, id) => {
                        sectionDropdown.append(`<option value="${id}">${name}</option>`);
                    });
                    
                    // Show message if no sections found for this class
                    if (uniqueSections.size === 0) {
                        sectionDropdown.append('<option value="" disabled>No sections available for this class</option>');
                    }
                }).fail(function() {
                    alert('Failed to load sections for the selected class.');
                });
            } else {
                // If no class selected, show all sections
                @foreach($sections as $section)
                    sectionDropdown.append('<option value="{{ $section->id }}">{{ $section->name }}</option>');
                @endforeach
            }
        });

        // ===== IMPROVED APPLY FILTERS FUNCTION =====
        function applyFilters() {
            const feeGroupId = $('#filterFeesGroup').val();
            const feeTypeId = $('#filterFeesType').val();
            const classId = $('#filterClass').val();
            const sectionId = $('#filterSection').val();
            const gender = $('#filterGender').val();
            const category = $('#filterCategory').val();

            // Show loading state
            $('#applyFiltersBtn').prop('disabled', true).html('<span class="animate-spin inline-block">⟳</span> Applying...');

            // Use Promise.all to wait for both AJAX calls to complete
            const feePromise = $.get("{{ route('school.assignFee.filterFeeMasters') }}", {
                fee_group_id: feeGroupId,
                fee_type_id: feeTypeId
            });

            const studentPromise = $.get("{{ route('school.assignFee.filterStudents') }}", {
                class_id: classId,
                section_id: sectionId,
                gender: gender,
                category: category
            });

            // Wait for both requests to complete
            Promise.all([feePromise, studentPromise])
                .then(([feeMasters, students]) => {
                    // Filter fee masters
                    $('#feesTypeListTable tbody').empty();
                    if (feeMasters.length === 0) {
                        $('#feesTypeListTable tbody').append(`
                            <tr>
                                <td colspan="4" class="px-3 py-4 text-center text-gray-500">
                                    No fee types found matching the selected filters
                                </td>
                            </tr>
                        `);
                    } else {
                        feeMasters.forEach(fee => {
                            $('#feesTypeListTable tbody').append(`
                                <tr data-fees_id="${fee.id}" data-fee_group_id="${fee.fee_group_id}" data-fee_type_id="${fee.fee_type_id}" class="hover:bg-gray-50">
                                    <td class="p-3">
                                        <input type="checkbox" class="fees-type-checkbox form-checkbox h-4 w-4 text-blue-600 rounded">
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">${fee.fee_type?.name || 'N/A'}</td>
                                    <td class="px-3 py-2 whitespace-nowrap">${fee.fee_group?.name || 'N/A'}</td>
                                    <td class="px-3 py-2 whitespace-nowrap">${parseFloat(fee.amount).toFixed(2)}</td>
                                </tr>
                            `);
                        });
                    }

                    // Filter students
                    $('#studentDetailsTable tbody').empty();
                    if (students.length === 0) {
                        $('#studentDetailsTable tbody').append(`
                            <tr>
                                <td colspan="8" class="px-3 py-4 text-center text-gray-500">
                                    No students found matching the selected filters
                                </td>
                            </tr>
                        `);
                    } else {
                        students.forEach(student => {
                            $('#studentDetailsTable tbody').append(`
                                <tr data-student_id="${student.id}" class="hover:bg-gray-50">
                                    <td class="p-3">
                                        <input type="checkbox" class="student-checkbox form-checkbox h-4 w-4 text-blue-600 rounded">
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-blue-600 hover:underline cursor-pointer">
                                        ${student.admission_number}
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap flex items-center">
                                        <img src="${student.photo || 'https://i.pravatar.cc/24'}" class="h-6 w-6 rounded-full mr-2 object-cover">
                                        ${student.first_name} ${student.last_name}
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">${student.roll_number || 'N/A'}</td>
                                    <td class="px-3 py-2 whitespace-nowrap">${student.class?.name || 'N/A'}</td>
                                    <td class="px-3 py-2 whitespace-nowrap">${student.section?.name || 'N/A'}</td>
                                    <td class="px-3 py-2 whitespace-nowrap">${student.gender || 'N/A'}</td>
                                    <td class="px-3 py-2 whitespace-nowrap">${student.category || 'N/A'}</td>
                                </tr>
                            `);
                        });
                    }

                    // Update selection summary and close modal
                    updateSelectionSummary();
                    filterModal.addClass('hidden');
                    
                    // Reset button state
                    $('#applyFiltersBtn').prop('disabled', false).text('Apply Filters');
                })
                .catch(error => {
                    console.error('Filter error:', error);
                    alert('Failed to apply filters. Please try again.');
                    $('#applyFiltersBtn').prop('disabled', false).text('Apply Filters');
                });
        }

        function resetAllFilters() {
            // Reset all filter values
            $('#filterFeesGroup, #filterFeesType, #filterClass, #filterSection, #filterGender, #filterCategory').val('');
            
            // Reset Fee Type dropdown to show all types
            const feeTypeDropdown = $('#filterFeesType');
            feeTypeDropdown.empty().append('<option value="">Select</option>');
            @foreach($feeTypes as $type)
                feeTypeDropdown.append('<option value="{{ $type->id }}">{{ $type->name }}</option>');
            @endforeach
            
            // Reset Section dropdown to show all sections
            const sectionDropdown = $('#filterSection');
            sectionDropdown.empty().append('<option value="">Select</option>');
            @foreach($sections as $section)
                sectionDropdown.append('<option value="{{ $section->id }}">{{ $section->name }}</option>');
            @endforeach
            
            // Reset checkboxes
            $('.fees-type-checkbox, .student-checkbox').prop('checked', false);
            $('#selectAllFeesTypes, #selectAllStudents').prop('checked', false);
            updateSelectionSummary();
            
            // Reload original data by refreshing the modal content
            $('#feesTypeListTable tbody').empty();
            @foreach($feeMasters as $fee)
                $('#feesTypeListTable tbody').append(`
                    <tr data-fees_id="{{ $fee->id }}" data-fee_group_id="{{ $fee->fee_group_id }}" data-fee_type_id="{{ $fee->fee_type_id }}" class="hover:bg-gray-50">
                        <td class="p-3">
                            <input type="checkbox" class="fees-type-checkbox form-checkbox h-4 w-4 text-blue-600 rounded">
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $fee->feeType->name ?? 'N/A' }}</td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $fee->feeGroup->name ?? 'N/A' }}</td>
                        <td class="px-3 py-2 whitespace-nowrap">${{ number_format($fee->amount, 2) }}</td>
                    </tr>
                `);
            @endforeach

            $('#studentDetailsTable tbody').empty();
            @foreach($students as $student)
                $('#studentDetailsTable tbody').append(`
                    <tr data-student_id="{{ $student->id }}" class="hover:bg-gray-50">
                        <td class="p-3">
                            <input type="checkbox" class="student-checkbox form-checkbox h-4 w-4 text-blue-600 rounded">
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-blue-600 hover:underline cursor-pointer">
                            {{ $student->admission_number }}
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap flex items-center">
                            <img src="{{ $student->photo ?? 'https://i.pravatar.cc/24' }}" class="h-6 w-6 rounded-full mr-2 object-cover">
                            {{ $student->first_name }} {{ $student->last_name }}
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $student->roll_number ?? 'N/A' }}</td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $student->class->name ?? 'N/A' }}</td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $student->section->name ?? 'N/A' }}</td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $student->gender ?? 'N/A' }}</td>
                        <td class="px-3 py-2 whitespace-nowrap">{{ $student->category ?? 'N/A' }}</td>
                    </tr>
                `);
            @endforeach
        }

        $('#applyFiltersBtn').click(applyFilters);
        $('#resetFiltersBtn').click(resetAllFilters);

        // Add Fees Button Logic
        $('#addAssignFeesBtn').click(function () {
            const selectedFees = [];
            $('.fees-type-checkbox:checked').each(function () {
                const tr = $(this).closest('tr');
                selectedFees.push({
                    fee_master_id: tr.data('fees_id'),
                    fee_group_id: tr.data('fee_group_id'),
                    fee_type_id: tr.data('fee_type_id')
                });
            });

            const selectedStudents = [];
            $('.student-checkbox:checked').each(function () {
                const tr = $(this).closest('tr');
                selectedStudents.push({
                    student_id: tr.data('student_id')
                });
            });

            if (selectedFees.length === 0 || selectedStudents.length === 0) {
                alert('Please select at least one fees type and one student.');
                return;
            }

            const fees_data = [];
            selectedFees.forEach(fee => {
                selectedStudents.forEach(student => {
                    fees_data.push({
                        fee_group_id: fee.fee_group_id,
                        fee_type_id: fee.fee_type_id,
                        fee_master_id: fee.fee_master_id,
                        student_id: student.student_id
                    });
                });
            });

            console.log('Sending fees_data:', fees_data);

            $.ajax({
                url: "{{ route('school.assignFee.store') }}",
                type: "POST",
                data: JSON.stringify({ fees_data }),
                contentType: "application/json",
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                beforeSend: function () {
                    $('#addAssignFeesBtn').prop('disabled', true).html('<span class="animate-spin">⟳</span> Assigning...');
                },
                success: function (response) {
                    $('#addAssignFeesBtn').prop('disabled', false).text('Add Fees');
                    if (response.success) {
                        alert(response.message);
                        $('#assignFeesModal').addClass('hidden');
                        location.reload();
                    } else {
                        alert(response.message || 'An unknown error occurred.');
                    }
                },
                error: function (xhr) {
                    $('#addAssignFeesBtn').prop('disabled', false).text('Add Fees');
                    console.error(xhr.responseText);
                    let errorMessage = 'Failed to assign fees.';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch (e) {
                        // Use default error message
                    }
                    alert(errorMessage);
                }
            });
        });
    });

    // Edit and Delete functions
    function editAssignFee(id) {
        $.get("{{ url('school/assign-fees/edit') }}/" + id, function(data) {
            console.log('Edit data:', data);

            $('#edit_assign_id').val(data.id);
            $('#edit_student_name').val(data.student ? data.student.first_name + ' ' + data.student.last_name : 'N/A');
            $('#edit_fee_group').val(data.fee_group ? data.fee_group.name : 'N/A');
            $('#edit_fee_type').val(data.fee_type ? data.fee_type.name : 'N/A');
            $('#edit_amount').val(data.fee_master ? data.fee_master.amount : '');

            // Save hidden IDs for update
            $('#editAssignFeeForm').data('fee_group_id', data.fee_group_id);
            $('#editAssignFeeForm').data('fee_type_id', data.fee_type_id);
            $('#editAssignFeeForm').data('fee_master_id', data.fee_master_id);
            $('#editAssignFeeForm').data('student_id', data.student_id);

            $('#editAssignFeeModal').removeClass('hidden');
        }).fail(function(xhr) {
            console.error(xhr.responseText);
            alert('Failed to fetch assigned fee details.');
        });
    }

    // Handle Update
    $('#editAssignFeeForm').on('submit', function(e) {
        e.preventDefault();

        const id = $('#edit_assign_id').val();
        const fee_group_id = $(this).data('fee_group_id');
        const fee_type_id = $(this).data('fee_type_id');
        const fee_master_id = $(this).data('fee_master_id');
        const student_id = $(this).data('student_id');
        const amount = $('#edit_amount').val();

        $.ajax({
            url: "{{ url('school/assign-fees') }}/" + id,
            type: "PUT",
            data: {
                fee_group_id,
                fee_type_id,
                fee_master_id,
                student_id,
                amount,
                _token: "{{ csrf_token() }}"
            },
            beforeSend: function() {
                $('#editAssignFeeForm button[type=submit]').text('Updating...').prop('disabled', true);
            },
            success: function(response) {
                $('#editAssignFeeForm button[type=submit]').text('Update').prop('disabled', false);

                if (response.success) {
                    alert(response.message);
                    $('#editAssignFeeModal').addClass('hidden');
                    location.reload();
                } else {
                    alert(response.message || 'Error updating fee.');
                }
            },
            error: function(xhr) {
                $('#editAssignFeeForm button[type=submit]').text('Update').prop('disabled', false);
                console.error(xhr.responseText);
                alert('Failed to update assigned fee. Please check console for details.');
            }
        });
    });

    // Cancel Modal
    $('#cancelEditFee').on('click', function() {
        $('#editAssignFeeModal').addClass('hidden');
    });

    function deleteAssignFee(id) {
        if (confirm('Are you sure you want to delete this assignment?')) {
            $.ajax({
                url: "{{ route('school.assignFee.destroy', '') }}/" + id,
                type: "DELETE",
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr) {
                    alert('Failed to delete assignment.');
                    console.error(xhr.responseText);
                }
            });
        }
    }
</script>