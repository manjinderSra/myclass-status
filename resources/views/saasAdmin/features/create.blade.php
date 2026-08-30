@include('saasAdmin.layout.header')

<div class="flex h-screen bg-gray-100">
    @include('saasAdmin.layout.sidebar')
    
    <div class="flex-1">
        @include('saasAdmin.layout.topbar')
        
        <main class="p-6 mt-16">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Add New Feature</h1>
                    <a href="{{ route('saasAdmin.features') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Back to Features
                    </a>
                </div>
                
                <form action="{{ route('saasAdmin.features.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Feature Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Feature Code</label>
                            <input type="text" name="code" id="code" value="{{ old('code') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error('code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="feature_group" class="block text-sm font-medium text-gray-700 mb-1">Feature Group</label>
                            <input type="text" list="feature-groups" name="feature_group" id="feature_group" value="{{ old('feature_group') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                            <datalist id="feature-groups">
                                @foreach($featureGroups as $group)
                                    <option value="{{ $group }}">
                                @endforeach
                                <option value="academics">
                                <option value="finance">
                                <option value="hrm">
                                <option value="transport">
                                <option value="hostel">
                                <option value="library">
                                <option value="examinations">
                            </datalist>
                            @error('feature_group')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="value_type" class="block text-sm font-medium text-gray-700 mb-1">Value Type</label>
                            <select name="value_type" id="value_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="boolean" {{ old('value_type') == 'boolean' ? 'selected' : '' }}>Boolean (Yes/No)</option>
                                <option value="number" {{ old('value_type') == 'number' ? 'selected' : '' }}>Number</option>
                                <option value="text" {{ old('value_type') == 'text' ? 'selected' : '' }}>Text</option>
                            </select>
                            @error('value_type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Create Feature</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

@include('saasAdmin.layout.footer') 