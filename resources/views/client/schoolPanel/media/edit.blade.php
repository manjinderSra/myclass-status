@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                Edit Media ({{ $media->title }})
            </h1>
            <a href="{{ route('school.media.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="ri-arrow-left-line align-middle me-1"></i> Back to Gallery
            </a>
        </div>

        {{-- Success and Error Messages --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" class="absolute top-0 right-0 px-4 py-3" data-bs-dismiss="alert" aria-label="Close">
                    <span class="text-red-700">&times;</span>
                </button>
            </div>
        @endif
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" class="absolute top-0 right-0 px-4 py-3" data-bs-dismiss="alert" aria-label="Close">
                    <span class="text-green-700">&times;</span>
                </button>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-6">
            <form action="{{ route('school.media.update', $media->id) }}" method="POST" enctype="multipart/form-data" id="mediaForm">
                @csrf
                @method('PUT') {{-- Method spoofing for PUT request --}}
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" name="title" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('title', $media->title) }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                        <select name="category" id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Select Category</option>
                            <option value="event" {{ old('category', $media->category) == 'event' ? 'selected' : '' }}>Event</option>
                            <option value="campus" {{ old('category', $media->category) == 'campus' ? 'selected' : '' }}>Campus</option>
                            <option value="classroom" {{ old('category', $media->category) == 'classroom' ? 'selected' : '' }}>Classroom</option>
                            <option value="sports" {{ old('category', $media->category) == 'sports' ? 'selected' : '' }}>Sports</option>
                            <option value="activities" {{ old('category', $media->category) == 'activities' ? 'selected' : '' }}>Activities</option>
                            <option value="achievements" {{ old('category', $media->category) == 'achievements' ? 'selected' : '' }}>Achievements</option>
                            <option value="general" {{ old('category', $media->category) == 'general' ? 'selected' : '' }}>General</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('description', $media->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Display current media file/thumbnail --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Media</label>
                    @if($media->file_path)
                        @if($media->type === 'photo')
                            <img src="{{ Storage::url($media->file_path) }}" alt="{{ $media->title }}" class="max-w-xs h-auto rounded-lg shadow-md mb-2">
                            <p class="text-sm text-gray-500">Current Photo</p>
                        @elseif($media->type === 'video')
                            <video controls class="max-w-xs h-auto rounded-lg shadow-md mb-2">
                                <source src="{{ Storage::url($media->file_path) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <p class="text-sm text-gray-500">Current Video</p>
                        @endif
                    @else
                        <p class="text-sm text-gray-500">No current media file.</p>
                    @endif

                    @if($media->thumbnail_path && $media->type === 'video')
                        <img src="{{ Storage::url($media->thumbnail_path) }}" alt="Video Thumbnail" class="max-w-xs h-auto rounded-lg shadow-md mt-4 mb-2">
                        <p class="text-sm text-gray-500">Current Video Thumbnail</p>
                    @endif
                </div>

                {{-- Option to upload new file/thumbnail --}}
                <div class="mb-6" id="fileUploadSection">
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Upload New File (Optional)</label>
                    <input type="file" name="file" id="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2.5 focus:outline-none">
                    @if($media->type === 'photo')
                        <p class="mt-1 text-sm text-gray-500" id="photoHelp">
                            Accepted formats: JPG, PNG, GIF. Max size: 10MB. (Leave empty to keep current file)
                        </p>
                    @elseif($media->type === 'video')
                        <p class="mt-1 text-sm text-gray-500" id="videoHelp">
                            Accepted formats: MP4, MOV, AVI. Max size: 50MB. (Leave empty to keep current file)
                        </p>
                    @endif
                    @error('file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                @if($media->type === 'video')
                <div class="mb-6" id="thumbnailSection">
                    <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">Upload New Video Thumbnail (Optional)</label>
                    <input type="file" name="thumbnail" id="thumbnail" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2.5 focus:outline-none">
                    <p class="mt-1 text-sm text-gray-500">
                        Upload a thumbnail image for your video. Accepted formats: JPG, PNG. Max size: 2MB. (Leave empty to keep current thumbnail)
                    </p>
                    @error('thumbnail')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif
                
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" {{ old('is_featured', $media->is_featured) ? 'checked' : '' }}>
                        <label for="is_featured" class="ml-2 text-sm font-medium text-gray-900">Feature this media (displayed prominently)</label>
                    </div>
                </div>

                {{-- Status Field (Added for editing) --}}
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select name="status" id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        <option value="active" {{ old('status', $media->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $media->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2" onclick="window.location.href='{{ route('school.media.index') }}'">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update Media
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Alert dismissal logic
        const alerts = document.querySelectorAll('[data-bs-dismiss="alert"]');
        alerts.forEach(button => {
            button.addEventListener('click', function() {
                const alert = this.closest('[role="alert"]');
                alert.remove();
            });
        });
    });
</script>