@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Edit Program
            </h1>
            <a href="{{ route('school.programs.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Programs
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                    <p class="font-bold">Please fix the following errors:</p>
                    <ul class="list-disc list-inside mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('school.programs.update', $program->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Program Title <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="title" name="title" value="{{ old('title', $program->title) }}" required>
                            <p class="mt-1 text-xs text-gray-500">Choose a descriptive title for your program</p>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                            <textarea class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="description" name="description" rows="6" required>{{ old('description', $program->description) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Provide detailed information about this program</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="coordinator" class="block text-sm font-medium text-gray-700 mb-1">Program Coordinator</label>
                                <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="coordinator" name="coordinator" value="{{ old('coordinator', $program->coordinator) }}">
                            </div>
                            <div>
                                <label for="coordinator_contact" class="block text-sm font-medium text-gray-700 mb-1">Coordinator Contact</label>
                                <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="coordinator_contact" name="coordinator_contact" value="{{ old('coordinator_contact', $program->coordinator_contact) }}">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Program Image</label>
                            @if($program->image_path)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $program->image_path) }}" alt="{{ $program->title }}" class="max-h-48 rounded-md">
                                </div>
                            @endif
                            <div class="mt-1 flex items-center">
                                <div class="w-full">
                                    <label class="flex flex-col items-center px-4 py-6 bg-white border border-gray-300 border-dashed rounded-md cursor-pointer hover:bg-gray-50">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-500 text-center">
                                                <span class="font-semibold">Click to upload</span> or drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                        </div>
                                        <input id="image" name="image" type="file" class="hidden" accept="image/*" />
                                    </label>
                                </div>
                            </div>
                            <div class="mt-2">
                                <img id="image-preview" src="#" alt="Preview" class="hidden mt-2 max-h-48 rounded-md">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Leave empty to keep the current image.</p>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="status" name="status" required>
                                <option value="active" {{ old('status', $program->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $program->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" name="is_featured" value="1" {{ old('is_featured', $program->is_featured) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Featured Program</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500">Featured programs will be highlighted on the school's homepage and mobile app.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <a href="{{ route('school.programs.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Update Program</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                imagePreview.classList.add('hidden');
            }
        });
    });
</script> 