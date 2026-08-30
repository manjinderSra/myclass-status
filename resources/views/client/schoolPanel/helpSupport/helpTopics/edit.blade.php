@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Edit Help Topic</h1>
                <p class="text-sm text-gray-600 mt-1">Update an existing help article</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('school.helpTopics.index') }}" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Help Topics
                </a>
            </div>
        </div>
        
        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form action="{{ route('school.helpTopics.update', $helpTopic['id']) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="p-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('title') border-red-300 @enderror" value="{{ old('title', $helpTopic['title']) }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                        <select id="category" name="category" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('category') border-red-300 @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ old('category', $helpTopic['category']) == $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('description') border-red-300 @enderror" required>{{ old('description', $helpTopic['description']) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Brief summary of the help topic (max 500 characters)</p>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content <span class="text-red-500">*</span></label>
                        <textarea id="content" name="content" rows="10" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('content') border-red-300 @enderror" required>{{ old('content', $helpTopic['content']) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">You can use HTML for formatting</p>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('status') border-red-300 @enderror" required>
                            <option value="Draft" {{ old('status', $helpTopic['status']) == 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Published" {{ old('status', $helpTopic['status']) == 'Published' ? 'selected' : '' }}>Published</option>
                            <option value="Archived" {{ old('status', $helpTopic['status']) == 'Archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 flex items-center justify-between">
                    <form action="{{ route('school.helpTopics.destroy', $helpTopic['id']) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this help topic? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Delete Topic
                        </button>
                    </form>
                    
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Update Help Topic
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer') 