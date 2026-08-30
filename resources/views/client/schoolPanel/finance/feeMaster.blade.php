@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Fees / <span class="text-l text-gray-500">Fees Master</span>
            </h1>
            <button id="openCreateFeesMasterModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Add Fees Master +
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <table id="feesMasterTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">ID</th>
                        <th class="px-6 py-3 font-semibold">Fees Group</th>
                        <th class="px-6 py-3 font-semibold">Fees Type</th>
                        <th class="px-6 py-3 font-semibold">Due Date</th>
                        <th class="px-6 py-3 font-semibold">Amount (₹)</th>
                        <th class="px-6 py-3 font-semibold">Fine Type</th>
                        <th class="px-6 py-3 font-semibold">Fine Amount (₹)</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    <!-- Data will be populated via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Fees Master Modal --}}
<div id="createFeesMasterModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Add Fees Master <span class="text-gray-500 text-base ml-2">2024 - 2025</span></h2>
            <button type="button" class="text-gray-500 hover:text-gray-700" id="closeCreateFeesMasterModalX">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="createFeesMasterForm">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Fees Group</label>
                <select name="fees_group_id" id="createFeesMasterFeesGroupSelect" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select</option>
                    @foreach ($fees_group as $list)
                    <option value="{{ $list->id }}">{{ $list->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Fees Type</label>
                <select name="fees_type_id" id="createFeesMasterFeesTypeSelect" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select</option>
                    @foreach ($fees_type as $list)
                    <option value="{{ $list->id }}">{{ $list->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Due Date</label>
                    <input type="date" name="due_date" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Amount (₹)</label>
                    <input type="number" name="amount" step="0.01" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">Fine Type</label>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="fine_type" value="None" class="form-radio text-blue-600" checked />
                        <span class="ml-2">None</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="fine_type" value="Percentage" class="form-radio text-blue-600" />
                        <span class="ml-2">Percentage</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="fine_type" value="Fixed" class="form-radio text-blue-600" />
                        <span class="ml-2">Fixed</span>
                    </label>
                </div>
            </div>

            <div class="mb-6 hidden" id="createFineAmountContainer">
                <label class="block mb-1 font-medium text-gray-700">Amount (₹)</label>
                <input type="number" name="fine_amount" step="0.01" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter fine amount or percentage" />
            </div>

            <div class="mb-6 flex items-center justify-between">
                <label class="font-medium text-gray-700">Status</label>
                <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                    <input type="checkbox" name="status" id="createFeesMasterStatus" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" checked />
                    <label for="createFeesMasterStatus" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400" id="closeCreateFeesMasterModalBtn">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Add Fees Master</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Fees Master Modal --}}
<div id="editFeesMasterModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Edit Fees Master <span class="text-gray-500 text-base ml-2">2024 - 2025</span></h2>
            <button type="button" class="text-gray-500 hover:text-gray-700" id="closeEditFeesMasterModalX">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="editFeesMasterForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="edit_id" id="edit_id" />

            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Fees Group</label>
                <select name="fee_group_id" id="editFeesMasterFeesGroupSelect" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Select</option>
                @foreach ($fees_group as $list)
                    <option value="{{ $list->id }}">{{ $list->name }}</option>
                @endforeach
            </select>

            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Fees Type</label>
                <select name="fees_type_id" id="editFeesMasterFeesTypeSelect" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    {{-- <option value="">Select</option> --}}
                    <option value="">Select</option>
                    @foreach ($fees_type as $list)
                    <option value="{{ $list->id }}">{{ $list->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Due Date</label>
                    <input type="date" name="due_date" id="editDueDate" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Amount (₹)</label>
                    <input type="number" name="amount" id="editAmount" step="0.01" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">Fine Type</label>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="fine_type" value="None" class="form-radio text-blue-600" />
                        <span class="ml-2">None</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="fine_type" value="Percentage" class="form-radio text-blue-600" />
                        <span class="ml-2">Percentage</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="fine_type" value="Fixed" class="form-radio text-blue-600" />
                        <span class="ml-2">Fixed</span>
                    </label>
                </div>
            </div>

            <div class="mb-6 hidden" id="editFineAmountContainer">
                <label class="block mb-1 font-medium text-gray-700">Amount (₹)</label>
                <input type="number" name="fine_amount" id="editFineAmount" step="0.01" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter fine amount or percentage" />
            </div>

            <div class="mb-6 flex items-center justify-between">
                <label class="font-medium text-gray-700">Status</label>
                <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                    <input type="checkbox" name="status" id="editFeesMasterStatus" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                    <label for="editFeesMasterStatus" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400" id="closeEditFeesMasterModalBtn">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Update Fees Master</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteFeesMasterModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
        <p class="text-gray-700 mb-6">Are you sure you want to delete this fees master entry?</p>
        <div class="flex justify-end space-x-4">
            <button type="button" id="closeDeleteFeesMasterModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="confirmDeleteFeesMasterBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
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

    .toggle-checkbox { opacity: 0; width: 0; height: 0; }
    .toggle-label {
        display: block;
        width: 40px;
        height: 24px;
        border-radius: 9999px;
        background-color: #d1d5db;
        cursor: pointer;
        position: relative;
        transition: background-color 0.2s ease-in-out;
    }
    .toggle-label:after {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 20px;
        height: 20px;
        border-radius: 9999px;
        background-color: #ffffff;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        transition: transform 0.2s ease-in-out;
    }
    .toggle-checkbox:checked + .toggle-label { background-color: #2563eb; }
    .toggle-checkbox:checked + .toggle-label:after { transform: translateX(16px); }
</style>

<script>
$(document).ready(function () {
    // CSRF Token setup for AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Initialize DataTable
    const feesMasterTable = $('#feesMasterTable').DataTable({
        language: { search: "", searchPlaceholder: "Search fees master..." },
        lengthMenu: [5, 10, 25, 50],
        pageLength: 10,
        dom: "<'flex justify-between items-center mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
             "t" +
             "<'flex justify-between items-center mt-4'<'dataTables_info'i><'dataTables_paginate'p>>"
    });

    // Modal references
    const createFeesMasterModal = $('#createFeesMasterModal');
    const editFeesMasterModal = $('#editFeesMasterModal');
    const deleteFeesMasterModal = $('#deleteFeesMasterModal');

    // Global storage for fees groups and types
    let allFeesGroups = [];
    let allFeesTypes = [];

    // ============================================
    // HELPER FUNCTIONS
    // ============================================

    function formatDateForDisplay(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function formatDateForInput(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function getFineAmountDisplay(fineType, fineAmount, mainAmount) {
        if (fineType === 'None') {
            return 'N/A';
        } else if (fineType === 'Fixed') {
            return `₹${parseFloat(fineAmount || 0).toFixed(2)}`;
        } else if (fineType === 'Percentage') {
            const percentageRate = parseFloat(fineAmount || 0);
            const calculatedAmount = (parseFloat(mainAmount || 0) * percentageRate) / 100;
            return `${percentageRate}% (₹${calculatedAmount.toFixed(2)})`;
        }
        return '';
    }

    function handleFineTypeChange(formId, isEdit = false) {
        const prefix = isEdit ? 'edit' : 'create';
        const fineTypeRadios = $(`#${formId} input[name="fine_type"]`);
        const fineAmountContainer = $(`#${prefix}FineAmountContainer`);
        const fineAmountInput = fineAmountContainer.find('input[name="fine_amount"]');

        const selectedFineType = fineTypeRadios.filter(':checked').val();

        if (selectedFineType === 'None') {
            fineAmountContainer.addClass('hidden');
            fineAmountInput.val('');
        } else {
            fineAmountContainer.removeClass('hidden');
        }
    }

    function populateSelect(selectElement, data, selectedId = null) {
        selectElement.empty().append('<option value="">Select</option>');
        data.forEach(item => {
            const option = `<option value="${item.id}">${item.name}</option>`;
            selectElement.append(option);
        });
        if (selectedId) {
            selectElement.val(selectedId);
        }
    }

    // ============================================
    // DATA LOADING FUNCTIONS
    // ============================================

    function loadFeesMasterData() {
        $.ajax({
            url: '{{ route("fee-master.list") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    feesMasterTable.clear();
                    
                    response.data.forEach(function(item) {
                        const statusHtml = item.status == 1
                            ? `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>`
                            : `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>`;

                        let fineTypeHtml;
                        switch (item.fine_type) {
                            case 'None':
                                fineTypeHtml = `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">${item.fine_type}</span>`;
                                break;
                            case 'Percentage':
                                fineTypeHtml = `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">${item.fine_type}</span>`;
                                break;
                            case 'Fixed':
                                fineTypeHtml = `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">${item.fine_type}</span>`;
                                break;
                            default:
                                fineTypeHtml = item.fine_type;
                        }

                        const fineAmountDisplay = getFineAmountDisplay(item.fine_type, item.fine_amount, item.amount);

                        const row = feesMasterTable.row.add([
                            item.uid,
                            item.fees_group_name,
                            item.fees_type_name,
                            formatDateForDisplay(item.due_date),
                            item.amount.toFixed(2),
                            fineTypeHtml,
                            fineAmountDisplay,
                            statusHtml,
                            `<button class="text-indigo-600 hover:text-indigo-900 font-medium editFeesMasterBtn" data-id="${item.id}"></button>
                             <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteFeesMasterBtn" data-id="${item.id}">Delete</button>`
                        ]).draw(false).node();

                        $(row).data('item', item);
                    });
                }
            },
            error: function(xhr) {
                alert('Error loading fees master data');
                console.error(xhr);
            }
        });
    }

    function loadFeesGroups() {
        $.ajax({
            url: '{{ route("fee-groups.index") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    allFeesGroups = response.data;
                    populateSelect($('#createFeesMasterFeesGroupSelect'), allFeesGroups);
                    populateSelect($('#editFeesMasterFeesGroupSelect'), allFeesGroups);
                }
            },
            error: function(xhr) {
                console.error('Error loading fees groups:', xhr);
            }
        });
    }

    function loadFeesTypes() {
        $.ajax({
            url: '{{ route("fee-types.index") }}',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    allFeesTypes = response.data;
                    populateSelect($('#createFeesMasterFeesTypeSelect'), allFeesTypes);
                }
            },
            error: function(xhr) {
                console.error('Error loading fees types:', xhr);
            }
        });
    }

    // ============================================
    // CREATE MODAL HANDLERS
    // ============================================

    $('#openCreateFeesMasterModal').click(() => {
        createFeesMasterModal.removeClass('hidden');
        $('#createFeesMasterForm')[0].reset();
        $('#createFeesMasterStatus').prop('checked', true);
        $('#createFeesMasterForm input[name="fine_type"][value="None"]').prop('checked', true);
        handleFineTypeChange('createFeesMasterForm');
    });

    $('#closeCreateFeesMasterModalX, #closeCreateFeesMasterModalBtn').click(() => {
        createFeesMasterModal.addClass('hidden');
        $('#createFeesMasterForm')[0].reset();
    });

    $('#createFeesMasterForm input[name="fine_type"]').change(function() {
        handleFineTypeChange('createFeesMasterForm');
    });

    $('#createFeesMasterForm').submit(function (e) {
        e.preventDefault();
        
        const formData = {
            fees_group_id: $(this).find('select[name="fees_group_id"]').val(),
            fees_type_id: $(this).find('select[name="fees_type_id"]').val(),
            due_date: $(this).find('input[name="due_date"]').val(),
            amount: $(this).find('input[name="amount"]').val(),
            fine_type: $(this).find('input[name="fine_type"]:checked').val(),
            fine_amount: $(this).find('input[name="fine_amount"]').val() || 0,
            status: $('#createFeesMasterStatus').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: '{{ route("fee-master.store") }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    createFeesMasterModal.addClass('hidden');
                    $('#createFeesMasterForm')[0].reset();
                    loadFeesMasterData();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMsg = 'Validation errors:\n';
                    for (let field in errors) {
                        errorMsg += errors[field].join('\n') + '\n';
                    }
                    alert(errorMsg);
                } else {
                    alert('Error creating fees master');
                }
                console.error(xhr);
            }
        });
    });

    // ============================================
    // EDIT MODAL HANDLERS
    // ============================================

    $('#feesMasterTable tbody').on('click', '.editFeesMasterBtn', async function () {
        const id = $(this).data('id');

        try {
            const res = await fetch(`/school/fee-masters/${id}`);
            const response = await res.json();

            if (!response.success) {
                alert("Failed to fetch fee master details.");
                return;
            }

            const data = response.data;
            alert(data);

            // Set form values - FIXED: Use the correct property names
            $('#edit_id').val(data.id);
            $('#editDueDate').val(formatDateForInput(data.due_date));
            $('#editAmount').val(data.amount);
            $('#editFineAmount').val(data.fine_amount || '');
            
            // Set fine type radio button
            $('#editFeesMasterForm input[name="fine_type"][value="' + data.fine_type + '"]').prop('checked', true);
            
            // Set status toggle
            $('#editFeesMasterStatus').prop('checked', data.status == 1);

            // Populate fees groups and set selected value
            populateSelect($('#editFeesMasterFeesGroupSelect'), allFeesGroups, data.fee_group_id);

            // Load and populate fees types based on selected group
            if (data.fee_group_id) {
                const url = `{{ route('school.api.getFeeTypes', ':groupId') }}`.replace(':groupId', data.fee_group_id);
                const typeRes = await fetch(url);
                const types = await typeRes.json();
                populateSelect($('#editFeesMasterFeesTypeSelect'), types, data.fee_type_id);
            }

            // Handle fine amount field visibility
            handleFineTypeChange('editFeesMasterForm', true);
            
            // Show modal
            editFeesMasterModal.removeClass('hidden');

        } catch (err) {
            console.error("Error loading fee master data:", err);
            alert("Error loading fee master data");
        }
    });

    $('#editFeesMasterFeesGroupSelect').on('change', async function () {
        const groupId = $(this).val();
        const typeSelect = $('#editFeesMasterFeesTypeSelect');
        typeSelect.html('<option value="">Loading...</option>');

        if (!groupId) {
            typeSelect.html('<option value="">Select</option>');
            return;
        }

        try {
            const url = `{{ route('school.api.getFeeTypes', ':groupId') }}`.replace(':groupId', groupId);
            const res = await fetch(url);
            const types = await res.json();
            populateSelect(typeSelect, types);
        } catch (err) {
            console.error("Error fetching fee types:", err);
            typeSelect.html('<option value="">Error loading types</option>');
        }
    });

    $('#closeEditFeesMasterModalX, #closeEditFeesMasterModalBtn').click(() => {
        editFeesMasterModal.addClass('hidden');
        $('#editFeesMasterForm')[0].reset();
    });

    $('#editFeesMasterForm input[name="fine_type"]').change(function() {
        handleFineTypeChange('editFeesMasterForm', true);
    });

    $('#editFeesMasterForm').submit(function (e) {
        e.preventDefault();
        
        const id = $('#edit_id').val();
        const formData = {
            fees_group_id: $(this).find('select[name="fees_group_id"]').val(),
            fees_type_id: $(this).find('select[name="fees_type_id"]').val(),
            due_date: $(this).find('input[name="due_date"]').val(),
            amount: $(this).find('input[name="amount"]').val(),
            fine_type: $(this).find('input[name="fine_type"]:checked').val(),
            fine_amount: $(this).find('input[name="fine_amount"]').val() || 0,
            status: $('#editFeesMasterStatus').is(':checked') ? 1 : 0,
            _method: 'PUT'
        };

        $.ajax({
            url: '{{ route("fee-master.update", "") }}/' + id,
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    editFeesMasterModal.addClass('hidden');
                    $('#editFeesMasterForm')[0].reset();
                    loadFeesMasterData();
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMsg = 'Validation errors:\n';
                    for (let field in errors) {
                        errorMsg += errors[field].join('\n') + '\n';
                    }
                    alert(errorMsg);
                } else {
                    alert('Error updating fees master');
                }
                console.error(xhr);
            }
        });
    });

    // ============================================
    // DELETE MODAL HANDLERS
    // ============================================

    let deleteFeesMasterId = null;

    $('#feesMasterTable tbody').on('click', '.deleteFeesMasterBtn', function () {
        deleteFeesMasterId = $(this).data('id');
        deleteFeesMasterModal.removeClass('hidden');
    });

    $('#closeDeleteFeesMasterModal').click(() => {
        deleteFeesMasterModal.addClass('hidden');
        deleteFeesMasterId = null;
    });

    $('#confirmDeleteFeesMasterBtn').click(function () {
        if (!deleteFeesMasterId) return;

        $.ajax({
            url: '{{ route("fee-master.destroy", "") }}/' + deleteFeesMasterId,
            method: 'DELETE',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    deleteFeesMasterModal.addClass('hidden');
                    deleteFeesMasterId = null;
                    loadFeesMasterData();
                }
            },
            error: function(xhr) {
                alert('Error deleting fees master');
                console.error(xhr);
            }
        });
    });

    // ============================================
    // DYNAMIC FEE TYPE LOADING FOR CREATE MODAL
    // ============================================

    $('#createFeesMasterFeesGroupSelect').on('change', function () {
        const groupId = $(this).val();
        const typeSelect = $('#createFeesMasterFeesTypeSelect');
        typeSelect.html('<option value="">Select</option>');

        if (groupId) {
            const url = `{{ route('school.api.getFeeTypes', ':groupId') }}`.replace(':groupId', groupId);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    typeSelect.html('<option value="">Select</option>');
                    if (data.length > 0) {
                        data.forEach(type => {
                            const option = document.createElement("option");
                            option.value = type.id;
                            option.textContent = type.name;
                            typeSelect[0].appendChild(option);
                        });
                    } else {
                        const option = document.createElement("option");
                        option.textContent = "No Fee Types found";
                        typeSelect[0].appendChild(option);
                    }
                })
                .catch(error => console.error("Error fetching fee types:", error));
        }
    });

    // ============================================
    // INITIAL DATA LOAD
    // ============================================

    loadFeesMasterData();
    loadFeesGroups();
    loadFeesTypes();
});
</script>