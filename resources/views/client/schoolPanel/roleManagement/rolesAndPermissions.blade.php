@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Role Management / <span class="text-l text-gray-500">Roles and Permissions</span></h1>
            <a href="{{ route('school.roles.create') }}" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Create New Role
            </a>
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
            @if($roles->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-600 mb-4">No roles have been created yet.</p>
                    <a href="{{ route('school.roles.create') }}" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">Create your first role</a>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                            <th class="px-6 py-3 font-semibold">#</th>
                            <th class="px-6 py-3 font-semibold">Role</th>
                            <th class="px-6 py-3 font-semibold">Description</th>
                            <th class="px-6 py-3 font-semibold">Permissions</th>
                            <th class="px-6 py-3 font-semibold">Users</th>
                            <th class="px-6 py-3 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                        @foreach($roles as $index => $role)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-medium">{{ $role->name }}</td>
                            <td class="px-6 py-4">{{ Str::limit($role->description, 50) }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $role->permissions_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $role->users_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="{{ route('school.roles.show', $role) }}" class="text-blue-600 hover:text-blue-900 font-medium">View</a>
                                    
                                    @if(!$role->is_system_role)
                                        <a href="{{ route('school.roles.edit', $role) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                                        
                                        <form action="{{ route('school.roles.destroy', $role) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<div id="permissionsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-2xl w-full p-6 overflow-y-auto max-h-[90vh]">
        <h2 class="text-xl font-semibold mb-4">Role Permissions</h2>
        <div id="permissionsContainer" class="grid grid-cols-4 gap-4 text-sm"></div>

        <div class="flex justify-end mt-6">
            <button id="closePermissionsModal" class="px-4 py-2 rounded bg-gray-400 text-white hover:bg-gray-500">Close</button>
        </div>
    </div>
</div>

<div id="createRoleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-xl w-full p-6">
        <h2 class="text-xl font-semibold mb-4">Create New Role</h2>
        <input type="text" id="newRoleName" placeholder="Enter role name" class="border border-gray-300 p-2 rounded-md w-full mb-4">
        <div class="mb-4">
            <h3 class="font-medium mb-2">Assign Permissions:</h3>
            <div id="newRolePermissionsContainer" class="grid grid-cols-3 gap-3 text-sm max-h-60 overflow-y-auto pr-2">
                </div>
        </div>
        <div class="flex justify-end mt-6">
            <button id="cancelCreateRole" class="px-4 py-2 rounded bg-gray-400 text-white hover:bg-gray-500 mr-2">Cancel</button>
            <button id="saveNewRole" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save Role</button>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')
<script>
    const allFeatures = Array.from({ length: 50 }, (_, i) => `Feature ${i + 1}`);
    const schoolRoleFeatures = allFeatures.slice(0, 30); // School role has first 30 features

    function renderPermissions(role) {
        const container = document.getElementById('permissionsContainer');
        container.innerHTML = "";

        allFeatures.forEach(feature => {
            const isSchool = role.toLowerCase() === "school";
            const isChecked = isSchool && schoolRoleFeatures.includes(feature);
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.checked = isChecked;
            checkbox.disabled = isSchool && isChecked;
            checkbox.className = "mr-2";

            const label = document.createElement('label');
            label.className = "flex items-center";
            label.appendChild(checkbox);
            label.appendChild(document.createTextNode(feature));

            container.appendChild(label);
        });

        document.getElementById('permissionsModal').classList.remove('hidden');
    }

    document.querySelectorAll('.viewPermissionsBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const role = this.getAttribute('data-role');
            renderPermissions(role);
        });
    });

    document.getElementById('closePermissionsModal').addEventListener('click', function () {
        document.getElementById('permissionsModal').classList.add('hidden');
    });

    // --- New Role Creation Logic ---
    const createRoleBtn = document.getElementById('createRoleBtn');
    const createRoleModal = document.getElementById('createRoleModal');
    const cancelCreateRole = document.getElementById('cancelCreateRole');
    const saveNewRole = document.getElementById('saveNewRole');
    const newRoleNameInput = document.getElementById('newRoleName');
    const newRolePermissionsContainer = document.getElementById('newRolePermissionsContainer');

    createRoleBtn.addEventListener('click', function() {
        newRoleNameInput.value = ''; // Clear previous input
        newRolePermissionsContainer.innerHTML = ''; // Clear previous permissions

        // Populate all features as unchecked for new role
        allFeatures.forEach(feature => {
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.value = feature;
            checkbox.className = "mr-2 new-role-permission-checkbox";

            const label = document.createElement('label');
            label.className = "flex items-center";
            label.appendChild(checkbox);
            label.appendChild(document.createTextNode(feature));

            newRolePermissionsContainer.appendChild(label);
        });

        createRoleModal.classList.remove('hidden');
    });

    cancelCreateRole.addEventListener('click', function() {
        createRoleModal.classList.add('hidden');
    });

    saveNewRole.addEventListener('click', function() {
        const roleName = newRoleNameInput.value.trim();
        if (!roleName) {
            alert('Role name cannot be empty!');
            return;
        }

        const selectedPermissions = Array.from(document.querySelectorAll('.new-role-permission-checkbox:checked'))
                                       .map(checkbox => checkbox.value);

        console.log(`Creating new role: ${roleName}`);
        console.log(`Permissions: ${selectedPermissions.join(', ')}`);
        alert(`Role "${roleName}" created with ${selectedPermissions.length} permissions.`);
        createRoleModal.classList.add('hidden');
    });
</script>