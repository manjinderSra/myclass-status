@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Role Management / <span class="text-l text-gray-500">Create New Role</span></h1>
            <a href="{{ route('school.rolesAndPermissions') }}" class="px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                Back to Roles
            </a>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg w-full p-6">
            <form action="{{ route('school.roles.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Role Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-3">Permissions</h2>
                    <p class="text-sm text-gray-600 mb-4">Select the permissions to assign to this role. Only features available in your subscription are shown.</p>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        @if($featureGroups->isEmpty())
                            <p class="text-center py-4 text-gray-600">No features available in your current subscription plan.</p>
                        @else
                            <div class="mb-4 flex flex-wrap gap-2">
                                <button type="button" id="selectAllBtn" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-sm">Select All</button>
                                <button type="button" id="deselectAllBtn" class="px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-sm">Deselect All</button>
                                
                                @foreach($featureGroups->keys() as $group)
                                    <button type="button" data-group="{{ $group }}" class="select-group-btn px-3 py-1 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 text-sm">
                                        {{ ucwords(str_replace('_', ' ', $group)) }}
                                    </button>
                                @endforeach
                            </div>
                            
                            @foreach($featureGroups as $group => $features)
                                <div class="mb-5 feature-group" data-group="{{ $group }}">
                                    <div class="flex items-center justify-between bg-gray-100 p-2 rounded cursor-pointer group-header">
                                        <h3 class="font-medium text-gray-700">{{ ucwords(str_replace('_', ' ', $group)) }}</h3>
                                        <div class="flex items-center">
                                            <button type="button" class="px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-xs select-group-btn mr-2" data-group="{{ $group }}">Select All</button>
                                            <svg class="h-5 w-5 text-gray-500 toggle-icon" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <div class="p-3 border border-gray-200 rounded-b-lg group-content">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-2">
                                            @foreach($features as $feature)
                                                <div class="bg-white p-3 rounded-lg border border-gray-200">
                                                    <h4 class="font-medium text-gray-700 mb-2">{{ $feature->name }}</h4>
                                                    <div class="space-y-2">
                                                        @php
                                                            $viewPermission = \App\Models\Permission::where('feature_id', $feature->id)->where('action', 'view')->first();
                                                            $createPermission = \App\Models\Permission::where('feature_id', $feature->id)->where('action', 'create')->first();
                                                            $editPermission = \App\Models\Permission::where('feature_id', $feature->id)->where('action', 'edit')->first();
                                                            $deletePermission = \App\Models\Permission::where('feature_id', $feature->id)->where('action', 'delete')->first();
                                                        @endphp
                                                        
                                                        @if($viewPermission)
                                                            <div class="flex items-center">
                                                                <input type="checkbox" id="view_{{ $feature->id }}" name="permissions[]" value="{{ $viewPermission->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded permission-checkbox" data-group="{{ $group }}">
                                                                <label for="view_{{ $feature->id }}" class="ml-2 block text-sm text-gray-700">View</label>
                                                            </div>
                                                        @endif
                                                        
                                                        @if($createPermission)
                                                            <div class="flex items-center">
                                                                <input type="checkbox" id="create_{{ $feature->id }}" name="permissions[]" value="{{ $createPermission->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded permission-checkbox" data-group="{{ $group }}">
                                                                <label for="create_{{ $feature->id }}" class="ml-2 block text-sm text-gray-700">Create</label>
                                                            </div>
                                                        @endif
                                                        
                                                        @if($editPermission)
                                                            <div class="flex items-center">
                                                                <input type="checkbox" id="edit_{{ $feature->id }}" name="permissions[]" value="{{ $editPermission->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded permission-checkbox" data-group="{{ $group }}">
                                                                <label for="edit_{{ $feature->id }}" class="ml-2 block text-sm text-gray-700">Edit</label>
                                                            </div>
                                                        @endif
                                                        
                                                        @if($deletePermission)
                                                            <div class="flex items-center">
                                                                <input type="checkbox" id="delete_{{ $feature->id }}" name="permissions[]" value="{{ $deletePermission->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded permission-checkbox" data-group="{{ $group }}">
                                                                <label for="delete_{{ $feature->id }}" class="ml-2 block text-sm text-gray-700">Delete</label>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            @error('permissions')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Create Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle groups
        const groupHeaders = document.querySelectorAll('.group-header');
        groupHeaders.forEach(header => {
            header.addEventListener('click', function(e) {
                // Don't toggle if clicking the select button
                if (e.target.classList.contains('select-group-btn') || e.target.closest('.select-group-btn')) {
                    return;
                }
                
                const content = this.nextElementSibling;
                const icon = this.querySelector('.toggle-icon');
                
                if (content.style.display === 'none') {
                    content.style.display = 'block';
                    icon.classList.remove('transform', 'rotate-180');
                } else {
                    content.style.display = 'none';
                    icon.classList.add('transform', 'rotate-180');
                }
            });
        });
        
        // Select all permissions
        document.getElementById('selectAllBtn').addEventListener('click', function() {
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
        });
        
        // Deselect all permissions
        document.getElementById('deselectAllBtn').addEventListener('click', function() {
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
        });
        
        // Select permissions by group
        document.querySelectorAll('.select-group-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const group = this.dataset.group;
                document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`).forEach(checkbox => {
                    checkbox.checked = true;
                });
            });
        });
    });
</script> 