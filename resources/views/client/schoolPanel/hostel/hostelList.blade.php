@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                Hostels / <span class="text-l text-gray-500">Hostel Lists</span>
            </h1>
            <button id="openHostelModal" type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Create Hostel +
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <table id="hostelsTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">S.no</th>
                        <th class="px-6 py-3 font-semibold">Hostel Name</th>
                        <th class="px-6 py-3 font-semibold">Type</th>
                        <th class="px-6 py-3 font-semibold">Address</th>
                        <th class="px-6 py-3 font-semibold">Capacity</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Created At</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($hostels) && count($hostels) > 0)
                        @foreach($hostels as $hostel)
                            <tr data-id="{{ $hostel->id }}">
                                <td class="px-6 py-4">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 capitalize">{{ $hostel->name }}</td>
                                <td class="px-6 py-4">{{ $hostel->type }}</td>
                                <td class="px-6 py-4">{{ $hostel->address }}</td>
                                <td class="px-6 py-4">{{ $hostel->intake }}</td>
                                <td class="px-6 py-4">
                                    <span class="{{ $hostel->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2 rounded-full text-xs font-semibold">
                                        {{ $hostel->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $hostel->created_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4">
                                <button class="editHostelBtn text-indigo-600 hover:text-indigo-900 font-medium" data-id="{{ $hostel->id }}">Edit</button>
                                <button class="deleteHostelBtn text-red-600 hover:text-red-800 font-medium ml-3" data-id="{{ $hostel->id }}">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="7" class="text-center px-6 py-4">No hostels found</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create Hostel Modal --}}
<div id="hostelModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[99999]">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
         <button type="button" id="closeHostelModal" class="absolute top-6 right-10 text-gray-500 hover:text-gray-700 text-2xl font-bold">
            x
        </button>
        <h2 class="text-xl font-semibold mb-4">Create Hostel</h2>
        <form id="hostelForm">
            {{-- CSRF --}}
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="editHostelId">

            <label class="block mb-2 font-medium text-gray-700">Hostel Name</label>
            <input type="text" name="name" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">Hostel Type</label>
            <select name="type" required class="w-full px-3 py-2 border rounded mb-4">
                <option value="">Select Hostel Type</option>
                <option value="Boys">Boys</option>
                <option value="Girls">Girls</option>
                <option value="Co-ed">Other</option>
            </select>

            <label class="block mb-2 font-medium text-gray-700">Hostel Address</label>
            <textarea name="address" required class="w-full px-3 py-2 border rounded mb-4"></textarea>

            <label class="block mb-2 font-medium text-gray-700">Capacity</label>
            <input type="number" name="intake" required class="w-full px-3 py-2 border rounded mb-4" />

            <label class="block mb-2 font-medium text-gray-700">Status</label>
            <select name="status" required class="w-full px-3 py-2 border rounded mb-4">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>

            <label class="block mb-2 font-medium text-gray-700">Description (Optional)</label>
            <textarea name="description" class="w-full px-3 py-2 border rounded mb-4"></textarea>

            <div class="flex justify-end space-x-4">
                <button type="button" id="cancelHostelModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                    Cancel
                </button>
                {{-- ✅ Give Save button an ID & type="button" --}}
                <button type="button" id="saveHostelBtn" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

{{-- Required CSS and JS --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function () {

    console.log("✅ Script Loaded");

    // Toastr config
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: "3000"
    };

    // ✅ CSRF setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    // ✅ Open create modal
    $('#openHostelModal').on('click', function () {
        $('#hostelModal').removeClass('hidden');
        $('#hostelForm')[0].reset();
        $('#editHostelId').val('');
        $('#saveHostelBtn').text('Save').removeClass('updateMode');
    });

    // ✅ Close modal
    $('#closeHostelModal').on('click', function () {
        $('#hostelModal').addClass('hidden');
        $('#saveHostelBtn').text('Save').removeClass('updateMode');
    });
    // ✅ Close modal (Cancel button)
$('#cancelHostelModal').on('click', function () {
    $('#hostelModal').addClass('hidden');
    $('#saveHostelBtn').text('Save').removeClass('updateMode');
});

    // ✅ Create or Update Hostel
    $('#saveHostelBtn').on('click', function (e) {
        e.preventDefault();

        let hostelId = $('#editHostelId').val();
        let formData = $('#hostelForm').serialize();

        // ✅ UPDATE MODE
        if ($(this).hasClass('updateMode')) {

            $.ajax({
                url: "/school/hostel/" + hostelId,
                type: "PUT",
                data: formData,
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#hostelModal').addClass('hidden');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Update failed');
                }
            });

        } else {
            // ✅ CREATE MODE
            $.ajax({
                url: "{{ route('school.hostelList.store') }}",
                type: "POST",
                data: formData,
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#hostelModal').addClass('hidden');
                        setTimeout(() => location.reload(), 800);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr) {
                    toastr.error(xhr.responseJSON?.message || 'Error creating hostel');
                }
            });
        }
    });

    // ✅ DELETE HOSTEL
    $(document).on('click', '.deleteHostelBtn', function () {
        let hostelId = $(this).data('id');

        $.ajax({
            url: "/school/hostel/" + hostelId,
            type: "DELETE",
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('button[data-id="'+hostelId+'"]').closest('tr').remove();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr) {
                toastr.error(xhr.responseJSON?.message || 'Delete failed');
            }
        });
    });

    // ✅ EDIT HOSTEL
// ✅ EDIT HOSTEL
$(document).on('click', '.editHostelBtn', function () {

    let row = $(this).closest('tr');
    let hostelId = $(this).data('id');

    $('#editHostelId').val(hostelId);

    // ✅ Corrected Indexes After Adding S.No
    $('#hostelForm [name="name"]').val(row.find('td:eq(1)').text().trim());
    $('#hostelForm [name="type"]').val(row.find('td:eq(2)').text().trim());
    $('#hostelForm [name="address"]').val(row.find('td:eq(3)').text().trim());
    $('#hostelForm [name="intake"]').val(row.find('td:eq(4)').text().trim());

    // ✅ Status column is now index 5
    let statusText = row.find('td:eq(5) span').text().trim() === 'Active' ? 1 : 0;
    $('#hostelForm [name="status"]').val(statusText);

    $('#hostelModal').removeClass('hidden');
    $('#saveHostelBtn').text('Update').addClass('updateMode');
});


});
</script>

