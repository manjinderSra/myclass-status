@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                Media Details
            </h1>
            <div class="flex space-x-2">
                <a href="{{ route('school.media.edit', $media->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="ri-edit-line align-middle me-1"></i> Edit
                </a>
                <a href="{{ route('school.media.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                    <i class="ri-arrow-left-line align-middle me-1"></i> Back to Gallery
                </a>
            </div>
        </div>

        <!-- Media Display -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Media Preview -->
                <div class="lg:col-span-2">
                    <div class="mb-4">
                        <div class="bg-gray-100 rounded-lg overflow-hidden">
                            @if($media->type == 'photo')
                                <img src="{{ asset('storage/' . $media->file_path) }}" alt="{{ $media->title }}" class="w-full h-auto rounded-lg">
                            @elseif($media->type == 'video')
                                <div class="aspect-w-16 aspect-h-9">
                                    <video controls class="w-full h-full rounded-lg">
                                        <source src="{{ asset('storage/' . $media->file_path) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-2">{{ $media->title }}</h2>
                        <p class="text-gray-500">{{ $media->description }}</p>
                    </div>
                </div>
                
                <!-- Media Info -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Media Information</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Type</p>
                            <div class="flex items-center">
                                @if($media->type == 'photo')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-medium">Photo</span>
                                @elseif($media->type == 'video')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <span class="font-medium">Video</span>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Category</p>
                            <div class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                                {{ ucfirst($media->category) }}
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Status</p>
                            <div class="flex items-center">
                                @if($media->status == 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="w-2 h-2 mr-1 bg-green-500 rounded-full"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <span class="w-2 h-2 mr-1 bg-gray-500 rounded-full"></span>
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Featured</p>
                            <div class="flex items-center">
                                @if($media->is_featured)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                        Featured
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Not Featured
                                    </span>
                                @endif
                                
                                <form action="{{ route('school.media.toggleFeatured', $media->id) }}" method="POST" class="ml-2">
                                    @csrf
                                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                                        {{ $media->is_featured ? 'Remove featured' : 'Make featured' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Added On</p>
                            <p class="font-medium">{{ $media->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Last Updated</p>
                            <p class="font-medium">{{ $media->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex justify-between">
                            <form action="{{ route('school.media.destroy', $media->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this media?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                                    Delete
                                </button>
                            </form>
                            
                            @if($media->type == 'photo')
                                <a href="{{ asset('storage/' . $media->file_path) }}" download class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded">
                                    Download
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer') 