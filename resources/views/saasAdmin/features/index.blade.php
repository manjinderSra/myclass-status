@include('saasAdmin.layout.header')

<div class="flex h-screen bg-gray-100">
    @include('saasAdmin.layout.sidebar')
    
    <div class="flex-1 overflow-auto">
        @include('saasAdmin.layout.topbar')
        
        <main class="p-6 mt-16">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Plan Features</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('saasAdmin.features.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Add New Feature
                        </a>
                        <button id="runMigrationBtn" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                            Add Default Features
                        </button>
                    </div>
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
                
                <!-- Features by Group -->
                <div class="mb-4">
                    <div class="flex items-center space-x-2 mb-4">
                        <input type="text" id="featureSearch" placeholder="Search features..." class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <select id="featureGroupFilter" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Groups</option>
                            @foreach($featureGroups as $group)
                                <option value="{{ $group }}">{{ ucwords(str_replace('_', ' ', $group)) }}</option>
                            @endforeach
                        </select>
                    </div>
                
                    @foreach($featureGroups as $group)
                    <div class="feature-group mb-8" data-group="{{ $group }}">
                        <h2 class="text-lg font-medium text-gray-700 mb-3 flex items-center">
                            <span class="w-3 h-3 rounded-full bg-blue-500 mr-2"></span>
                            {{ ucwords(str_replace('_', ' ', $group)) }}
                        </h2>
                        
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $groupFeatures = $features->where('feature_group', $group);
                                    @endphp
                                    
                                    @forelse($groupFeatures as $feature)
                                        <tr class="feature-row" data-name="{{ strtolower($feature->name) }}" data-code="{{ strtolower($feature->code) }}">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $feature->name }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($feature->description, 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <code class="text-sm bg-gray-100 text-pink-600 rounded px-1 py-0.5">{{ $feature->code }}</code>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($feature->value_type == 'boolean')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Yes/No</span>
                                                @elseif($feature->value_type == 'number')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Number</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Text</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($feature->is_active)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('saasAdmin.features.edit', $feature) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    <form action="{{ route('saasAdmin.features.destroy', $feature) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-2" onclick="return confirm('Are you sure you want to delete this feature?')">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No features found in this group</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>
</div>

@include('saasAdmin.layout.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Feature search functionality
        const searchInput = document.getElementById('featureSearch');
        const groupFilter = document.getElementById('featureGroupFilter');
        const featureRows = document.querySelectorAll('.feature-row');
        const featureGroups = document.querySelectorAll('.feature-group');
        
        function filterFeatures() {
            const searchTerm = searchInput.value.toLowerCase();
            const groupFilter = document.getElementById('featureGroupFilter').value;
            
            featureGroups.forEach(group => {
                const groupName = group.dataset.group;
                let hasVisibleRows = false;
                
                if (groupFilter && groupName !== groupFilter) {
                    group.style.display = 'none';
                    return;
                }
                
                const rows = group.querySelectorAll('.feature-row');
                
                rows.forEach(row => {
                    const name = row.dataset.name;
                    const code = row.dataset.code;
                    
                    if (name.includes(searchTerm) || code.includes(searchTerm)) {
                        row.style.display = '';
                        hasVisibleRows = true;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                group.style.display = hasVisibleRows || !searchTerm ? '' : 'none';
            });
        }
        
        searchInput.addEventListener('input', filterFeatures);
        groupFilter.addEventListener('change', filterFeatures);
        
        // Add Default Features button
        document.getElementById('runMigrationBtn').addEventListener('click', function() {
            if (confirm('This will add all default features to the database. Continue?')) {
                fetch('/saasAdmin/features/add-defaults', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Default features added successfully!');
                        window.location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding default features.');
                });
            }
        });
    });
</script> 