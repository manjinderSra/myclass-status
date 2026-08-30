@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Role Management / <span class="text-l text-gray-500">Edit Role</span></h1>
            <div class="flex space-x-2">
                <a href="{{ route('school.rolesAndPermissions') }}" class="px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                    Back to Roles
                </a>
                <a href="{{ route('school.roles.show', $role) }}" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    View Role
                </a>
            </div>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg w-full p-6">
            <form action="{{ route('school.roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Role Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $role->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-3">Permissions</h2>
                    <p class="text-sm text-gray-600 mb-4">Update permissions for this role. Only features available in your subscription are shown.</p>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        @if($featureGroups->isEmpty())
                            <p class="text-center py-4 text-gray-600">No features available in your current subscription plan.</p>
                        @else
                            @foreach($featureGroups as $group => $features)
                                <div class="mb-5">
                                    <h3 class="font-medium text-gray-700 mb-2 pb-1 border-b">{{ ucwords(str_replace('_', ' ', $group)) }}</h3>
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
                                                            <input type="checkbox" id="view_{{ $feature->id }}" name="permissions[]" value="{{ $viewPermission->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ in_array($viewPermission->id, $rolePermissionIds) ? 'checked' : '' }}>
                                                            <label for="view_{{ $feature->id }}" class="ml-2 block text-sm text-gray-700">View</label>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($createPermission)
                                                        <div class="flex items-center">
                                                            <input type="checkbox" id="create_{{ $feature->id }}" name="permissions[]" value="{{ $createPermission->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ in_array($createPermission->id, $rolePermissionIds) ? 'checked' : '' }}>
                                                            <label for="create_{{ $feature->id }}" class="ml-2 block text-sm text-gray-700">Create</label>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($editPermission)
                                                        <div class="flex items-center">
                                                            <input type="checkbox" id="edit_{{ $feature->id }}" name="permissions[]" value="{{ $editPermission->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ in_array($editPermission->id, $rolePermissionIds) ? 'checked' : '' }}>
                                                            <label for="edit_{{ $feature->id }}" class="ml-2 block text-sm text-gray-700">Edit</label>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($deletePermission)
                                                        <div class="flex items-center">
                                                            <input type="checkbox" id="delete_{{ $feature->id }}" name="permissions[]" value="{{ $deletePermission->id }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ in_array($deletePermission->id, $rolePermissionIds) ? 'checked' : '' }}>
                                                            <label for="delete_{{ $feature->id }}" class="ml-2 block text-sm text-gray-700">Delete</label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
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
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Update Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer') 