@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Academic /<span class="text-l text-gray-500"> Examinations</span> / <span class="text-m¸ text-gray-500">Exam List</span>
            </h1>
            <button id="openExamModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Add Exam +
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <table id="examsTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">Exam ID</th>
                        <th class="px-6 py-3 font-semibold">Exam Name</th>
                        <th class="px-6 py-3 font-semibold">Exam Date</th>
                        <th class="px-6 py-3 font-semibold">Start Time</th>
                        <th class="px-6 py-3 font-semibold">End Time</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    {{-- Sample Exam Data --}}
                    <tr class="hover:bg-gray-50 transition-colors" data-id="E140523" data-name="Semester Exam" data-date="2024-07-18" data-start="09:30 AM" data-end="11:30 AM" data-status="Active">
                        <td class="px-6 py-4">E140523</td>
                        <td class="px-6 py-4">Semester Exam</td>
                        <td class="px-6 py-4">18 Jul 2024</td>
                        <td class="px-6 py-4">09:30 AM</td>
                        <td class="px-6 py-4">11:30 AM</td>
                        <td class="px-6 py-4">
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Active</span>
                        </td>
                        <td class="px-6 py-4">
                            <button class="text-indigo-600 hover:text-indigo-900 font-medium editExamBtn" data-id="E140523">Edit</button>
                            <button class="text-red-600 hover:text-red-800 font-medium ml-3 deleteExamBtn" data-id="E140523">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Exam Modal --}}
<div id="examModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Add Exam</h2>
        <form id="examForm">
            @csrf
            <label class="block mb-2 font-medium text-gray-700">Exam Name</label>
            <input type="text" name="exam_name" required class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />

            <label class="block mb-2 font-medium text-gray-700">Exam Date</label>
            <input type="date" name="exam_date" required class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />

            <label class="block mb-2 font-medium text-gray-700">Start Time</label>
            <input type="time" name="start_time" required class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />

            <label class="block mb-2 font-medium text-gray-700">End Time</label>
            <input type="time" name="end_time" required class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />

            <label class="flex items-center mb-4">
                <input type="checkbox" name="status" class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                <span class="text-gray-700">Set as Active</span>
            </label>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeExamModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Add Exam</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Exam Modal --}}
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Edit Exam</h2>
        <form id="editForm">
            @csrf
            <input type="hidden" name="edit_id" />
            <label class="block mb-2 font-medium text-gray-700">Exam Name</label>
            <input type="text" name="edit_exam_name" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">Exam Date</label>
            <input type="date" name="edit_exam_date" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">Start Time</label>
            <input type="time" name="edit_start_time" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">End Time</label>
            <input type="time" name="edit_end_time" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="flex items-center mb-4">
                <input type="checkbox" name="edit_status" class="mr-2 w-5 h-5 text-blue-600 border-gray-300 rounded">
                <span class="text-gray-700">Set as Active</span>
            </label>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeEditModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
        <p class="text-gray-700 mb-6">Are you sure you want to delete this exam?</p>
        <div class="flex justify-end space-x-4">
            <button type="button" id="closeDeleteModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="confirmDeleteBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
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
        $('#examsTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search exams..."
            },
            lengthMenu: [5, 10, 25, 50],
            pageLength: 5,
            dom:
                "<'flex justify-between items-center mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
                "t" +
                "<'flex justify-between items-center mt-4'<'dataTables_info'i><'dataTables_paginate'p>>",
        });

        $('#openExamModal').click(() => $('#examModal').removeClass('hidden'));
        $('#closeExamModal').click(() => $('#examModal').addClass('hidden'));

        $('#examForm').submit(function (e) {
            e.preventDefault();
            const name = $('input[name="exam_name"]').val().trim();
            const date = $('input[name="exam_date"]').val().trim();
            const startTime = $('input[name="start_time"]').val().trim();
            const endTime = $('input[name="end_time"]').val().trim();
            const status = $('input[name="status"]').is(':checked') ? 'Active' : 'Inactive';

            if (!name || !date || !startTime || !endTime) {
                alert("Please fill out all fields.");
                return;
            }

            alert(`Exam "${name}" on ${date} from ${startTime} to ${endTime} with status "${status}" added (backend needed).`);
            $('#examModal').addClass('hidden');
            this.reset();
        });

        $('.editExamBtn').click(function () {
            const row = $(this).closest('tr');
            const id = row.data('id');
            const name = row.data('name');
            const date = row.data('date');
            const startTime = row.data('start');
            const endTime = row.data('end');
            const status = row.data('status') === 'Active';

            $('#editForm input[name="edit_id"]').val(id);
            $('#editForm input[name="edit_exam_name"]').val(name);
            $('#editForm input[name="edit_exam_date"]').val(date);
            $('#editForm input[name="edit_start_time"]').val(startTime);
            $('#editForm input[name="edit_end_time"]').val(endTime);
            $('#editForm input[name="edit_status"]').prop('checked', status);

            $('#editModal').removeClass('hidden');
        });

        $('#closeEditModal').click(() => $('#editModal').addClass('hidden'));

        $('#editForm').submit(function (e) {
            e.preventDefault();
            const id = $('input[name="edit_id"]').val();
            const name = $('input[name="edit_exam_name"]').val().trim();
            const date = $('input[name="edit_exam_date"]').val().trim();
            const startTime = $('input[name="edit_start_time"]').val().trim();
            const endTime = $('input[name="edit_end_time"]').val().trim();
            const status = $('input[name="edit_status"]').is(':checked') ? 'Active' : 'Inactive';

            alert(`Updated Exam ID ${id} to "${name}" on ${date} from ${startTime} to ${endTime} with status "${status}" (backend needed).`);
            $('#editModal').addClass('hidden');
            this.reset();
        });

        let deleteId = null;
        $('.deleteExamBtn').click(function () {
            deleteId = $(this).data('id');
            $('#deleteModal').removeClass('hidden');
        });

        $('#closeDeleteModal').click(() => $('#deleteModal').addClass('hidden'));

        $('#confirmDeleteBtn').click(function () {
            alert(`Exam ID ${deleteId} deleted (backend needed).`);
            $('#deleteModal').addClass('hidden');
        });
    });
</script>