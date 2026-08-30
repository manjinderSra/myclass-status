@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Role Management / <span class="text-l text-gray-500">Role Details</span></h1>
            <div class="flex space-x-2">
                <a href="{{ route('school.rolesAndPermissions') }}" class="px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                    Back to Roles
                </a>
                @if(!$role->is_system_role)
                    <a href="{{ route('school.roles.edit', $role) }}" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Edit Role
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Role Information -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Role Information</h2>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="text-lg font-medium">{{ $role->name }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Description</p>
                    <p>{{ $role->description ?: 'No description provided' }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Created</p>
                    <p>{{ $role->created_at->format('M d, Y') }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Last Updated</p>
                    <p>{{ $role->updated_at->format('M d, Y') }}</p>
                </div>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-500">System Role</p>
                    <p>{{ $role->is_system_role ? 'Yes' : 'No' }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500">Assigned Users</p>
                    <p class="text-lg font-medium">{{ $role->users->count() }}</p>
                </div>
            </div>
            
            <!-- Role Permissions -->
            <div class="bg-white rounded-xl shadow-lg p-6 lg:col-span-2">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Role Permissions ({{ $role->permissions->count() }})</h2>
                
                @if($role->permissions->isEmpty())
                    <div class="text-center py-6">
                        <p class="text-gray-600 mb-2">This role has no permissions assigned.</p>
                        @if(!$role->is_system_role)
                            <a href="{{ route('school.roles.edit', $role) }}" class="text-blue-600 hover:underline">Assign permissions</a>
                        @endif
                    </div>
                @else
                    <div>
                        @foreach($groupedPermissions as $group => $permissions)
                            <div class="mb-5">
                                <h3 class="font-medium text-gray-700 mb-2 pb-1 border-b">{{ ucwords(str_replace('_', ' ', $group)) }}</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                    @foreach($permissions->groupBy(function($permission) { return $permission->feature->name; }) as $featureName => $featurePermissions)
                                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                            <h4 class="font-medium text-gray-700 mb-2">{{ $featureName }}</h4>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($featurePermissions as $permission)
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                                                        {{ ucfirst($permission->action) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Users with this role -->
            <div class="bg-white rounded-xl shadow-lg p-6 lg:col-span-3">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Users with this role ({{ $role->users->count() }})</h2>
                
                @if($role->users->isEmpty())
                    <div class="text-center py-6">
                        <p class="text-gray-600">No users have been assigned this role yet.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                                    <th class="px-6 py-3 font-semibold">Name</th>
                                    <th class="px-6 py-3 font-semibold">Email</th>
                                    <th class="px-6 py-3 font-semibold">Role Assigned On</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                                @foreach($role->users as $user)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                                        <td class="px-6 py-4">{{ $user->email }}</td>
                                        <td class="px-6 py-4">{{ $user->pivot->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer') 