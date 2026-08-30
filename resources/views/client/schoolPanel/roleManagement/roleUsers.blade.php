@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">Role Management / <span class="text-l text-gray-500">Users</span></h1>
            <button id="openUserModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Create User +
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

        <div class="bg-white rounded-xl shadow-lg w-full p-6">
            <table id="usersTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">#</th>
                        <th class="px-6 py-3 font-semibold">Name</th>
                        <th class="px-6 py-3 font-semibold">Email</th>
                        <th class="px-6 py-3 font-semibold">Role</th>
                        <th class="px-6 py-3 font-semibold">Created</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @foreach($users as $index => $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">{{ $user->roles->first() ? $user->roles->first()->name : 'No Role' }}</td>
                        <td class="px-6 py-4">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900 font-medium resetPasswordBtn" data-user-id="{{ $user->id }}">Reset Password</button>
                            <button class="text-red-600 hover:text-red-900 font-medium deleteUserBtn" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">Delete</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-lg w-full p-6">
        <h2 class="text-xl font-semibold mb-4">Create User</h2>
        <form id="userForm">
            @csrf
            <label class="block mb-2 font-medium text-gray-700">First Name</label>
            <input type="text" name="first_name" required class="w-full px-3 py-2 border rounded mb-4">

            <label class="block mb-2 font-medium text-gray-700">Last Name</label>
            <input type="text" name="last_name" required class="w-full px-3 py-2 border rounded mb-4">

            <label class="block mb-2 font-medium text-gray-700">Email</label>
            <input type="email" name="email" required class="w-full px-3 py-2 border rounded mb-4">

            <label class="block mb-2 font-medium text-gray-700">Role</label>
            <select name="role_id" required class="w-full px-3 py-2 border rounded mb-4">
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeUserModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Password Modal -->
<div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h2 class="text-xl font-semibold mb-4">User Password</h2>
        <p class="mb-4 text-gray-700">A new password has been generated for this user:</p>
        <input id="passwordField" type="text" readonly class="w-full px-3 py-2 border rounded mb-4 font-mono">
        <div class="flex justify-end space-x-4">
            <button onclick="copyPassword()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Copy</button>
            <button id="closePasswordModal" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Close</button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h2 class="text-xl font-semibold mb-4">Confirm Deletion</h2>
        <p class="mb-6 text-gray-700">Are you sure you want to delete <span id="deleteUserName" class="font-semibold"></span>? This action cannot be undone.</p>
        <div class="flex justify-end space-x-4">
            <button id="cancelDelete" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="confirmDelete" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        // DataTable
        $('#usersTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search users..."
            },
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
            dom:
                "<'flex justify-between items-center mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
                "t" +
                "<'flex justify-between items-center mt-4'<'dataTables_info'i><'dataTables_paginate'p>>",
        });

        // Open/close modals
        $('#openUserModal').click(() => $('#userModal').removeClass('hidden'));
        $('#closeUserModal').click(() => $('#userModal').addClass('hidden'));
        $('#closePasswordModal').click(() => $('#passwordModal').addClass('hidden'));
        $('#cancelDelete').click(() => $('#deleteModal').addClass('hidden'));

        // Create user form submission
        $('#userForm').submit(function (e) {
            e.preventDefault();
            
            $.ajax({
                url: "{{ route('school.users.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        // Show password
                        $('#passwordField').val(response.password);
                        $('#userModal').addClass('hidden');
                        $('#passwordModal').removeClass('hidden');
                        
                        // Reset form
                        $('#userForm')[0].reset();
                        
                        // Reload page to show new user
                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function(xhr) {
                    let errorMessage = "An error occurred";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    alert("Error: " + errorMessage);
                }
            });
        });

        // Reset password functionality
        $('.resetPasswordBtn').click(function() {
            const userId = $(this).data('user-id');
            
            $.ajax({
                url: `/school/users/${userId}/reset-password`,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        // Show the new password
                        $('#passwordField').val(response.password);
                        $('#passwordModal').removeClass('hidden');
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function(xhr) {
                    let errorMessage = "An error occurred";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    alert("Error: " + errorMessage);
                }
            });
        });

        // Delete user functionality
        $('.deleteUserBtn').click(function() {
            const userId = $(this).data('user-id');
            const userName = $(this).data('user-name');
            
            $('#deleteUserName').text(userName);
            $('#confirmDelete').data('user-id', userId);
            $('#deleteModal').removeClass('hidden');
        });

        $('#confirmDelete').click(function() {
            const userId = $(this).data('user-id');
            
            $.ajax({
                url: `/school/users/${userId}`,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        $('#deleteModal').addClass('hidden');
                        // Reload page to update user list
                        window.location.reload();
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function(xhr) {
                    let errorMessage = "An error occurred";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    alert("Error: " + errorMessage);
                }
            });
        });
    });

    function copyPassword() {
        const copyText = document.getElementById("passwordField");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        alert("Password copied to clipboard");
    }
</script>
