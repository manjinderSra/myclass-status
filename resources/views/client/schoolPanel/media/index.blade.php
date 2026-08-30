@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                Media Gallery
            </h1>
            <a href="{{ route('school.media.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="ri-add-line align-middle me-1"></i> Add Media
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" class="absolute top-0 right-0 px-4 py-3" data-bs-dismiss="alert" aria-label="Close">
                    <span class="text-green-700">&times;</span>
                </button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" class="absolute top-0 right-0 px-4 py-3" data-bs-dismiss="alert" aria-label="Close">
                    <span class="text-red-700">&times;</span>
                </button>
            </div>
        @endif

        <!-- Filter Options -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <form action="{{ route('school.media.index') }}" method="GET" class="flex flex-wrap items-center space-x-4">
                <div class="mb-2 sm:mb-0">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select id="type" name="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="all" {{ $type == 'all' ? 'selected' : '' }}>All Media</option>
                        <option value="photo" {{ $type == 'photo' ? 'selected' : '' }}>Photos Only</option>
                        <option value="video" {{ $type == 'video' ? 'selected' : '' }}>Videos Only</option>
                    </select>
                </div>
                
                <div class="mb-2 sm:mb-0">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select id="category" name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mt-5">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                        Filter
                    </button>
                    <a href="{{ route('school.media.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded ml-2">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Media Grid -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            @if($media->isEmpty())
                <div class="text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900">No media found</h3>
                    <p class="mt-1 text-gray-500">Get started by adding some photos or videos.</p>
                    <a href="{{ route('school.media.create') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Media
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($media as $item)
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition overflow-hidden">
                            <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
                                @if($item->type == 'photo')
                                    <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                @elseif($item->type == 'video')
                                    <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                                        @if($item->thumbnail_path)
                                            <img src="{{ asset('storage/' . $item->thumbnail_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="text-white text-xl">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="bg-black bg-opacity-50 rounded-full p-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($item->is_featured)
                                    <div class="absolute top-2 right-2">
                                        <span class="bg-yellow-400 text-yellow-800 text-xs font-medium px-2 py-1 rounded">Featured</span>
                                    </div>
                                @endif
                                
                                <div class="absolute top-2 left-2">
                                    <span class="bg-blue-500 text-white text-xs font-medium px-2 py-1 rounded">{{ ucfirst($item->type) }}</span>
                                </div>
                            </div>
                            
                            <div class="p-4">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-lg font-medium text-gray-900 truncate">{{ $item->title }}</h3>
                                    <span class="text-xs text-gray-500">{{ $item->created_at->format('M d, Y') }}</span>
                                </div>
                                <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $item->description }}</p>
                                <div class="mt-2">
                                    <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">{{ ucfirst($item->category) }}</span>
                                </div>
                                
                                <div class="mt-4 flex justify-between">
                                    <a href="{{ route('school.media.show', $item->id) }}" class="text-blue-600 hover:text-blue-800">
                                        View
                                    </a>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('school.media.edit', $item->id) }}" class="text-gray-600 hover:text-gray-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('school.media.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-6">
                    {{ $media->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('[data-bs-dismiss="alert"]');
        alerts.forEach(button => {
            button.addEventListener('click', function() {
                const alert = this.closest('[role="alert"]');
                alert.remove();
            });
        });
    });
</script> 