@include('client.schoolPanel.layout.header')

{{-- DataTables CSS - Load in header --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />

@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                General Settings / <span class="text-l text-gray-500">Rules and Regulations</span>
            </h1>
            <div class="space-x-4">
                <button onclick="openCategoryModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Create Category
                </button>
                <button onclick="openRuleModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Create Rule
                </button>
            </div>
        </div>

        {{-- Flash Message --}}
        <div id="flashMessage" class="mb-4 hidden">
            <div class="p-4 rounded-md border"></div>
        </div>

        {{-- Rules Table --}}
        <div class="bg-white rounded-xl shadow-lg max-w-full w-full p-6 transition-all duration-300">
            <table id="rulesTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Category</th>
                        <th class="px-6 py-3 font-semibold">Rule Title</th>
                        <th class="px-6 py-3 font-semibold">Description</th>
                        <th class="px-6 py-3 font-semibold">Created At</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @forelse($rules as $index => $rule)
                    <tr class="hover:bg-gray-50 transition-colors" data-rule-id="{{ $rule->id }}">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">{{ $rule->category->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $rule->title }}</td>
                        <td class="px-6 py-4">{{ Str::limit($rule->description, 50) }}</td>
                        <td class="px-6 py-4">{{ $rule->created_at->format('Y-m-d') }}</td>
                       <td class="px-6 py-4">
                            <button class="text-indigo-600 hover:text-indigo-900 font-medium editRuleBtn"
                                    data-id="{{ $rule->id }}"
                                    data-category="{{ $rule->rule_category_id }}"
                                    data-title="{{ $rule->title }}"
                                    data-description="{{ $rule->description }}">
                                Edit
                            </button>
                            <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteRuleBtn"
                                    data-id="{{ $rule->id }}">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No rules added yet. Click "Create Rule" to add your first rule.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create Category Modal --}}
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative mx-4">
        <h2 class="text-xl font-semibold mb-4">Create Category</h2>
        <form id="categoryForm" method="POST" action="{{ route('school.rulesAndRegulations.storeCategory') }}">
            @csrf
            <div id="categoryFormErrors" class="mb-4 hidden">
                <div class="bg-red-50 border-l-4 border-red-500 p-4 text-red-700"></div>
            </div>
            
            <label class="block mb-2 font-medium text-gray-700">Category Name</label>
            <input type="text" name="name" required 
                   class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            
            <label class="block mb-2 font-medium text-gray-700">Description (Optional)</label>
            <textarea name="description" rows="3"
                      class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeCategoryModal()" 
                        class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Create/Edit Rule Modal --}}
<div id="ruleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg max-w-lg w-full p-6 relative mx-4">
        <h2 id="ruleModalTitle" class="text-xl font-semibold mb-4">Create Rule</h2>
        <form id="ruleForm" method="POST" action="{{ route('school.rulesAndRegulations.storeRule') }}">
            @csrf
            <input type="hidden" name="rule_id" id="ruleId">
            <input type="hidden" name="_method" id="ruleMethod" value="POST">
            
            <div id="ruleFormErrors" class="mb-4 hidden">
                <div class="bg-red-50 border-l-4 border-red-500 p-4 text-red-700"></div>
            </div>
            
            <label class="block mb-2 font-medium text-gray-700">Select Category</label>
            <select name="rule_category_id" id="ruleCategorySelect" required 
                    class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            <label class="block mb-2 font-medium text-gray-700">Rule Title</label>
            <input type="text" name="title" id="ruleTitle" required 
                   class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />

            <label class="block mb-2 font-medium text-gray-700">Description</label>
            <textarea name="description" id="ruleDescription" rows="4" required 
                      class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeRuleModal()" 
                        class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative mx-4">
        <h2 class="text-xl font-semibold mb-4">Confirm Deletion</h2>
        <p class="mb-6">Are you sure you want to delete this rule? This action cannot be undone.</p>
        
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeDeleteModal()" 
                        class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                    Cancel
                </button>
                <button id="confirmDelete" type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>


{{-- Custom Styles --}}
<style>
    /* DataTables Wrapper */
    div.dataTables_wrapper {
        width: 100%;
    }

    /* Length Select */
    .dataTables_length select {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        margin: 0 0.5rem;
    }

    .dataTables_length select:focus {
        outline: none;
        ring: 2px;
        ring-color: #3b82f6;
    }

    /* Search Input */
    .dataTables_filter input {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        width: 16rem !important;
        margin-left: 0.5rem;
    }

    .dataTables_filter input:focus {
        outline: none;
        ring: 2px;
        ring-color: #3b82f6;
    }

    /* Pagination Container */
    .dataTables_paginate {
        display: flex;
        gap: 0.25rem;
        margin-top: 1rem;
    }

    /* Pagination Buttons */
    .dataTables_paginate a {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        color: #374151;
        margin: 0 0.125rem;
        min-width: 2rem;
        text-align: center;
        display: inline-block;
        line-height: 1.5rem;
        cursor: pointer;
    }

    .dataTables_paginate a:hover {
        background-color: #e5e7eb;
    }

    /* Active Page */
    .dataTables_paginate .current {
        background-color: #2563eb;
        color: white;
        border-color: #2563eb;
        pointer-events: none;
    }

    /* Disabled Buttons */
    .dataTables_paginate .disabled {
        color: #9ca3af;
        cursor: not-allowed;
        border-color: #e5e7eb;
    }

    /* Info Text */
    .dataTables_info {
        color: #4b5563;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
</style>

{{-- Scripts - Load before footer --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    let table;
    let deleteRuleId = null;

    $(document).ready(function () {
        console.log("Document ready - initializing");

        // Initialize DataTable
        table = $('#rulesTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search rules..."
            },
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
            order: [[4, 'desc']], 
            dom: "<'flex justify-between items-center mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
                 "t" +
                 "<'flex justify-between items-center mt-4'<'dataTables_info'i><'dataTables_paginate'p>>",
        });

        // Edit Rule Button
        $('#rulesTable tbody').on('click', '.editRuleBtn', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const id = $(this).data('id');
            const categoryId = $(this).data('category');
            const title = $(this).data('title');
            const description = $(this).data('description');

            console.log("Edit rule clicked for ID:", id);

            $('#ruleId').val(id);
            $('#ruleCategorySelect').val(categoryId);
            $('#ruleTitle').val(title);
            $('#ruleDescription').val(description);

            $('#ruleForm').attr('action', `/school/rulesAndRegulations/rule/${id}`);
            $('#ruleMethod').val('PUT');
            $('#ruleModalTitle').text('Edit Rule');

            openRuleModal();
        });

        // Delete Rule Button
        $('#rulesTable tbody').on('click', '.deleteRuleBtn', function (e) {
            e.preventDefault();
            e.stopPropagation();

            deleteRuleId = $(this).data('id');
            console.log("Delete rule clicked for ID:", deleteRuleId);

            $('#deleteForm').attr('action', `/school/rulesAndRegulations/rule/${deleteRuleId}`);

            openDeleteModal();
        });

        // Debug form submission
        $('#ruleForm').on('submit', function () {
            console.log('Rule Form submitting...');
        });
    });

    // Modal Functions
    function openCategoryModal() {
        $('#categoryModal').removeClass('hidden').addClass('flex');
    }

    function closeCategoryModal() {
        $('#categoryModal').removeClass('flex').addClass('hidden');
        $('#categoryForm')[0].reset();
    }

    function openRuleModal() {
        $('#ruleModal').removeClass('hidden').addClass('flex');
    }

    function closeRuleModal() {
        $('#ruleModal').removeClass('flex').addClass('hidden');

        $('#ruleForm')[0].reset();
        $('#ruleForm').attr('action', '{{ route("school.rulesAndRegulations.storeRule") }}');
        $('#ruleMethod').val('POST');
        $('#ruleId').val('');
        $('#ruleModalTitle').text('Create Rule');
    }

    function openDeleteModal() {
        $('#deleteModal').removeClass('hidden').addClass('flex');
    }

    function closeDeleteModal() {
        $('#deleteModal').removeClass('flex').addClass('hidden');
        deleteRuleId = null;
    }

    // Flash Message
    function showFlashMessage(message, type = 'success') {
        const flashMessage = $('#flashMessage');
        const alertDiv = flashMessage.find('div');

        alertDiv.removeClass('bg-green-50 border-green-500 text-green-700 bg-red-50 border-red-500 text-red-700');

        if (type === 'success') {
            alertDiv.addClass('bg-green-50 border-l-4 border-green-500 text-green-700');
        } else {
            alertDiv.addClass('bg-red-50 border-l-4 border-red-500 text-red-700');
        }

        alertDiv.text(message);
        flashMessage.removeClass('hidden');

        setTimeout(() => {
            flashMessage.addClass('hidden');
        }, 5000);
    }

    // Close modals on background click
    window.onclick = function (event) {
        if (event.target.id === 'categoryModal') closeCategoryModal();
        if (event.target.id === 'ruleModal') closeRuleModal();
        if (event.target.id === 'deleteModal') closeDeleteModal();
    }
</script>


{{-- @include('client.schoolPanel.layout.footer') --}}
