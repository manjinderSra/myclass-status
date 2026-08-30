@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                Add New Media
            </h1>
            <a href="{{ route('school.media.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                <i class="ri-arrow-left-line align-middle me-1"></i> Back to Gallery
            </a>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" class="absolute top-0 right-0 px-4 py-3" data-bs-dismiss="alert" aria-label="Close">
                    <span class="text-red-700">&times;</span>
                </button>
            </div>
        @endif

        <!-- Upload Form -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <form action="{{ route('school.media.store') }}" method="POST" enctype="multipart/form-data" id="mediaForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" name="title" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="{{ old('title') }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                        <select name="category" id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Select Category</option>
                            <option value="event" {{ old('category') == 'event' ? 'selected' : '' }}>Event</option>
                            <option value="campus" {{ old('category') == 'campus' ? 'selected' : '' }}>Campus</option>
                            <option value="classroom" {{ old('category') == 'classroom' ? 'selected' : '' }}>Classroom</option>
                            <option value="sports" {{ old('category') == 'sports' ? 'selected' : '' }}>Sports</option>
                            <option value="activities" {{ old('category') == 'activities' ? 'selected' : '' }}>Activities</option>
                            <option value="achievements" {{ old('category') == 'achievements' ? 'selected' : '' }}>Achievements</option>
                            <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Media Type *</label>
                    <div class="flex gap-4">
                        <div class="flex items-center">
                            <input type="radio" name="type" id="type_photo" value="photo" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" {{ old('type') == 'photo' ? 'checked' : '' }} checked>
                            <label for="type_photo" class="ml-2 text-sm font-medium text-gray-900">Photo</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" name="type" id="type_video" value="video" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" {{ old('type') == 'video' ? 'checked' : '' }}>
                            <label for="type_video" class="ml-2 text-sm font-medium text-gray-900">Video</label>
                        </div>
                    </div>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6" id="fileUploadSection">
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">File Upload *</label>
                    <input type="file" name="file" id="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2.5 focus:outline-none" required>
                    <p class="mt-1 text-sm text-gray-500" id="photoHelp">
                        Accepted formats: JPG, PNG, GIF. Max size: 10MB.
                    </p>
                    <p class="mt-1 text-sm text-gray-500 hidden" id="videoHelp">
                        Accepted formats: MP4, MOV, AVI. Max size: 50MB.
                    </p>
                    @error('file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6 hidden" id="thumbnailSection">
                    <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">Video Thumbnail (Optional)</label>
                    <input type="file" name="thumbnail" id="thumbnail" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2.5 focus:outline-none">
                    <p class="mt-1 text-sm text-gray-500">
                        Upload a thumbnail image for your video. Accepted formats: JPG, PNG. Max size: 2MB.
                    </p>
                    @error('thumbnail')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" {{ old('is_featured') ? 'checked' : '' }}>
                        <label for="is_featured" class="ml-2 text-sm font-medium text-gray-900">Feature this media (displayed prominently)</label>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2" onclick="window.location.href='{{ route('school.media.index') }}'">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Upload Media
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const photoRadio = document.getElementById('type_photo');
        const videoRadio = document.getElementById('type_video');
        const thumbnailSection = document.getElementById('thumbnailSection');
        const photoHelp = document.getElementById('photoHelp');
        const videoHelp = document.getElementById('videoHelp');
        
        function updateFormFields() {
            if (videoRadio.checked) {
                thumbnailSection.classList.remove('hidden');
                photoHelp.classList.add('hidden');
                videoHelp.classList.remove('hidden');
            } else {
                thumbnailSection.classList.add('hidden');
                photoHelp.classList.remove('hidden');
                videoHelp.classList.add('hidden');
            }
        }
        
        photoRadio.addEventListener('change', updateFormFields);
        videoRadio.addEventListener('change', updateFormFields);
        
        // Initial state
        updateFormFields();
        
        // Alert dismissal
        const alerts = document.querySelectorAll('[data-bs-dismiss="alert"]');
        alerts.forEach(button => {
            button.addEventListener('click', function() {
                const alert = this.closest('[role="alert"]');
                alert.remove();
            });
        });
    });
</script> 