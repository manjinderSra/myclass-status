@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Fees / <span class="text-l text-gray-500">Fees Types</span>
            </h1>
            <div class="flex space-x-2">
                <button id="openCreateFeesTypeModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Add Fees Type +
                </button>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <div class="mb-4 flex items-center">
                <div class="dataTables_length mr-4">
                    <label>
                        Show 
                        <select id="entriesPerPage" class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select> 
                        entries
                    </label>
                </div>
                <div class="dataTables_filter flex-1">
                    <label class="block">
                        <input id="feesTypeSearch" type="search" placeholder="Search fees types..." class="w-full border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </label>
                </div>
            </div>
            
            <table id="feesTypesTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">ID</th>
                        <th class="px-6 py-3 font-semibold">Fees Type</th>
                        <th class="px-6 py-3 font-semibold">Fees Code</th>
                        <th class="px-6 py-3 font-semibold">Fees Group</th>
                        <th class="px-6 py-3 font-semibold">Description</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody id="feesTypesTableBody" class="text-sm text-gray-700 divide-y divide-gray-200">
                    @if(isset($feeTypes) && $feeTypes->count() > 0)
                        @foreach($feeTypes as $feeType)
                            <tr data-id="{{ $feeType->id }}" class="data-row">
                                <td class="px-6 py-4">{{ $feeType->unique_id }}</td>
                                <td class="px-6 py-4">{{ $feeType->name }}</td>
                                <td class="px-6 py-4">{{ $feeType->fees_code }}</td>
                                <td class="px-6 py-4">{{ $feeType->feeGroup->name }}</td>
                                <td class="px-6 py-4">{{ $feeType->description }}</td>
                                <td class="px-6 py-4">
                                    @if($feeType->status)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 flex space-x-3">
                                    <button 
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded editBtn"
                                        data-id="{{ $feeType->id }}"
                                        data-name="{{ $feeType->name }}"
                                        data-description="{{ $feeType->description }}"
                                        data-fee-group-id="{{ $feeType->fee_group_id }}"
                                        data-status="{{ $feeType->status ? 1 : 0 }}">
                                        ✏️ Edit
                                    </button>
                                    {{-- <a href="{{ route('fee-types.destroy', $feeType->id) }}" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded flex items-center">
                                        🗑 Delete
                                    </a> --}}
                                  <button class="deleteFeeType bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded" data-id="{{ $feeType->id }}">
    🗑 Delete
</button>


                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center">No fee types found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            
            <div class="mt-4 flex justify-between items-center">
                <div class="dataTables_info text-gray-600 text-sm">
                    Showing <span id="startEntry">1</span> to <span id="endEntry">5</span> of <span id="totalEntries">0</span> entries
                </div>
                <div id="feesTypePagination" class="dataTables_paginate flex space-x-1">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Fee Type Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-700">Edit Fee Type</h2>
            <button id="closeEditModal" class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>

        <div class="p-6">
           <form id="editForm" action="{{ route('fee-types.client.schoolPanel.feeType.update', $id ?? '') }}" method="POST">
    @csrf
    @method('PUT')

                <input type="hidden" name="id" id="edit_id">

                <label class="block mb-2 text-gray-700">Fee Type Name</label>
                <input type="text" name="edit_name" id="edit_name" class="w-full border rounded px-3 py-2 mb-4" required>

                <label class="block mb-2 text-gray-700">Fee Group</label>
                <select name="edit_fees_group_id" id="edit_fees_group_id" class="w-full border rounded px-3 py-2 mb-4" required>
                    <option value="">Select Fee Group</option>
                    @foreach($feeGroups as $feeGroup)
                        <option value="{{ $feeGroup->id }}">{{ $feeGroup->name }}</option>
                    @endforeach
                </select>

                <label class="block mb-2 text-gray-700">Description</label>
                <textarea name="edit_description" id="edit_description" class="w-full border rounded px-3 py-2 mb-4"></textarea>

                <div class="mb-4 flex items-center justify-between">
                    <label class="font-medium text-gray-700">Status</label>
                    <div class="relative inline-block w-10 mr-2 align-middle select-none">
                        <input type="checkbox" name="edit_status" id="edit_status" value="1" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                        <label for="edit_status" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelEditBtn" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Fees Type Modal -->
<div id="createFeesTypeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Add Fees Type</h2>
        <button type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" id="closeCreateFeesTypeModalX">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <form id="createFeesTypeForm1" action="{{route('fee-types.fee-types.store')}}" method="post">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Name</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Fees Group</label>
                <select name="fee_group_id" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select</option>
                    @foreach($feeGroups as $feeGroup)
                        <option value="{{$feeGroup->id}}">{{$feeGroup->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="mb-6 flex items-center justify-between">
                <label class="font-medium text-gray-700">Status</label>
                <div class="relative inline-block w-10 mr-2 align-middle select-none">
                    <input type="checkbox" name="status" id="createFeesTypeStatus" value="1" checked class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                    <label for="createFeesTypeStatus" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400" id="closeCreateFeesTypeModalBtn">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Add Fees Type</button>
            </div>
        </form>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

<style>
    .toggle-checkbox {
        opacity: 0;
        width: 0;
        height: 0;
    }

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

    .toggle-checkbox:checked + .toggle-label {
        background-color: #2563eb;
    }

    .toggle-checkbox:checked + .toggle-label:after {
        transform: translateX(16px);
    }
    
    .hidden-row {
        display: none;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // === EDIT MODAL FUNCTIONALITY ===
    const editModal = document.getElementById('editModal');
    const closeEditModal = document.getElementById('closeEditModal');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const editForm = document.getElementById('editForm');

    // Open Edit Modal
    document.querySelectorAll('.editBtn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const description = this.dataset.description || '';
            const feeGroupId = this.dataset.feeGroupId;
            const status = this.dataset.status == 1;

            // Set form action URL
            editForm.action = `/school-panel/fees/fee-type/${id}`;

            // Fill form fields
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_fees_group_id').value = feeGroupId;
            document.getElementById('edit_status').checked = status;

            // Show modal
            editModal.classList.remove('hidden');
        });
    });

    // Close modal handlers
    closeEditModal.addEventListener('click', () => editModal.classList.add('hidden'));
    cancelEditBtn.addEventListener('click', () => editModal.classList.add('hidden'));

    // Handle form submission
    editForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(editForm);
    const id = document.getElementById('edit_id').value;
    formData.append('_method', 'PUT');

    fetch(`/school/feeType/school-panel/fees/fee-type/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.message || 'Update failed!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Something went wrong!');
    });
});


    // === CREATE MODAL FUNCTIONALITY ===
    const createModal = document.getElementById('createFeesTypeModal');
    const openCreateBtn = document.getElementById('openCreateFeesTypeModal');
    const closeCreateX = document.getElementById('closeCreateFeesTypeModalX');
    const closeCreateBtn = document.getElementById('closeCreateFeesTypeModalBtn');

    openCreateBtn.addEventListener('click', () => createModal.classList.remove('hidden'));
    closeCreateX.addEventListener('click', () => createModal.classList.add('hidden'));
    closeCreateBtn.addEventListener('click', () => createModal.classList.add('hidden'));

    // === TABLE FUNCTIONALITY ===
    const feesTypesTableBody = document.getElementById('feesTypesTableBody');
    const allRows = Array.from(feesTypesTableBody.getElementsByTagName('tr'));
    const totalEntriesSpan = document.getElementById('totalEntries');
    const startEntrySpan = document.getElementById('startEntry');
    const endEntrySpan = document.getElementById('endEntry');
    const searchInput = document.getElementById('feesTypeSearch');
    const entriesPerPageSelect = document.getElementById('entriesPerPage');
    const feesTypePagination = document.getElementById('feesTypePagination');

    let filteredRows = allRows;
    let currentPage = 1;
    let entriesPerPage = parseInt(entriesPerPageSelect.value);

    function renderTable() {
        const startIndex = (currentPage - 1) * entriesPerPage;
        const endIndex = startIndex + entriesPerPage;

        allRows.forEach(row => row.classList.add('hidden-row'));
        
        filteredRows.slice(startIndex, endIndex).forEach(row => {
            row.classList.remove('hidden-row');
        });

        totalEntriesSpan.textContent = filteredRows.length;
        startEntrySpan.textContent = filteredRows.length > 0 ? startIndex + 1 : 0;
        endEntrySpan.textContent = Math.min(endIndex, filteredRows.length);

        renderPagination();
    }
    
    function renderPagination() {
        const totalPages = Math.ceil(filteredRows.length / entriesPerPage);
        feesTypePagination.innerHTML = '';

        const createButton = (text, page, isCurrent, isDisabled) => {
            const button = document.createElement('button');
            button.textContent = text;
            button.classList.add('border', 'border-gray-300', 'rounded', 'px-3', 'py-1', 'text-sm', 'text-gray-700', 'hover:bg-gray-200', 'cursor-pointer');
            if (isCurrent) {
                button.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                button.disabled = true;
            }
            if (isDisabled) {
                button.classList.add('text-gray-400', 'cursor-not-allowed', 'border-gray-200');
                button.disabled = true;
            }
            button.addEventListener('click', () => {
                currentPage = page;
                renderTable();
            });
            return button;
        };

        feesTypePagination.appendChild(createButton('Previous', currentPage - 1, false, currentPage === 1));

        for (let i = 1; i <= totalPages; i++) {
            feesTypePagination.appendChild(createButton(i, i, i === currentPage, false));
        }

        feesTypePagination.appendChild(createButton('Next', currentPage + 1, false, currentPage === totalPages));
    }

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        filteredRows = allRows.filter(row => {
            const rowText = row.textContent.toLowerCase();
            return rowText.includes(searchTerm);
        });
        currentPage = 1;
        renderTable();
    }

    renderTable();

    searchInput.addEventListener('input', filterTable);
    entriesPerPageSelect.addEventListener('change', (e) => {
        entriesPerPage = parseInt(e.target.value);
        currentPage = 1;
        filterTable();
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const token = '{{ csrf_token() }}';

    document.querySelectorAll('.deleteFeeType').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            if (!confirm('Are you sure you want to delete this fee type?')) return;

            fetch(`/school-panel/fees/fee-type/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    this.closest('tr').remove();
                } else {
                    alert(data.message || 'Failed to delete');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error deleting fee type.');
            });
        });
    });
});
</script>
