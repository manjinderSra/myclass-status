@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Edit Event: {{ $event->title }}
            </h1>
            <a href="{{ route('school.events.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Events
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

            <form action="{{ route('school.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Event Title <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="title" name="title" value="{{ old('title', $event->title) }}" required>
                            <p class="mt-1 text-xs text-gray-500">Choose a clear, descriptive title for your event</p>
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                            <textarea class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="description" name="description" rows="6" required>{{ old('description', $event->description) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Provide details about what attendees can expect at this event</p>
                        </div>

                        <div class="p-4 border border-blue-100 rounded-md bg-blue-50">
                            <h3 class="font-medium text-blue-800 mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Event Schedule
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">Event Date <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <input type="date" class="w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="event_date" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
                                    </div>
                                </div>
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <input type="text" class="w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="location" name="location" value="{{ old('location', $event->location) }}" required placeholder="Room, building, or address">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <input type="time" class="w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="start_time" name="start_time" value="{{ old('start_time', $event->start_time) }}" required>
                                    </div>
                                </div>
                                <div>
                                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <input type="time" class="w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="end_time" name="end_time" value="{{ old('end_time', $event->end_time) }}">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Optional if it's a single time event</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="organizer" class="block text-sm font-medium text-gray-700 mb-1">Organizer</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input type="text" class="w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="organizer" name="organizer" value="{{ old('organizer', $event->organizer) }}" placeholder="Person or department organizing this event">
                            </div>
                        </div>

                        <div>
                            <label for="program_id" class="block text-sm font-medium text-gray-700 mb-1">Associated Program</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                    </svg>
                                </div>
                                <select class="w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="program_id" name="program_id">
                                    <option value="">-- Select Program (Optional) --</option>
                                    @foreach($programs as $program)
                                        <option value="{{ $program->id }}" {{ old('program_id', $event->program_id) == $program->id ? 'selected' : '' }}>{{ $program->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Connect this event to a specific school program</p>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Event Image</label>
                            <div class="mt-1 flex items-center">
                                <div class="w-full">
                                    @if($event->image_path)
                                        <div class="mb-3">
                                            <p class="text-sm text-gray-500 mb-2">Current Image:</p>
                                            <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="max-h-48 rounded-md">
                                        </div>
                                    @endif
                                    <label class="flex flex-col items-center px-4 py-6 bg-white border border-gray-300 border-dashed rounded-md cursor-pointer hover:bg-gray-50">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-500 text-center">
                                                <span class="font-semibold">Click to upload new image</span> or drag and drop
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
                        </div>
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <label class="flex p-3 border border-gray-300 rounded-md cursor-pointer hover:border-blue-500 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all {{ old('status', $event->status) == 'upcoming' ? 'border-blue-500 bg-blue-50' : '' }}">
                                    <input type="radio" name="status" value="upcoming" class="peer sr-only" {{ old('status', $event->status) == 'upcoming' ? 'checked' : '' }}>
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 border-2 rounded-full border-blue-500 flex items-center justify-center mr-3">
                                            <div class="w-3 h-3 rounded-full bg-blue-500 {{ old('status', $event->status) == 'upcoming' ? '' : 'hidden' }}"></div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium">Upcoming</span>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="flex p-3 border border-gray-300 rounded-md cursor-pointer hover:border-green-500 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all {{ old('status', $event->status) == 'ongoing' ? 'border-green-500 bg-green-50' : '' }}">
                                    <input type="radio" name="status" value="ongoing" class="peer sr-only" {{ old('status', $event->status) == 'ongoing' ? 'checked' : '' }}>
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 border-2 rounded-full border-green-500 flex items-center justify-center mr-3">
                                            <div class="w-3 h-3 rounded-full bg-green-500 {{ old('status', $event->status) == 'ongoing' ? '' : 'hidden' }}"></div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium">Ongoing</span>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="flex p-3 border border-gray-300 rounded-md cursor-pointer hover:border-gray-500 peer-checked:border-gray-500 peer-checked:bg-gray-50 transition-all {{ old('status', $event->status) == 'completed' ? 'border-gray-500 bg-gray-50' : '' }}">
                                    <input type="radio" name="status" value="completed" class="peer sr-only" {{ old('status', $event->status) == 'completed' ? 'checked' : '' }}>
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 border-2 rounded-full border-gray-500 flex items-center justify-center mr-3">
                                            <div class="w-3 h-3 rounded-full bg-gray-500 {{ old('status', $event->status) == 'completed' ? '' : 'hidden' }}"></div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium">Completed</span>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="flex p-3 border border-gray-300 rounded-md cursor-pointer hover:border-red-500 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all {{ old('status', $event->status) == 'cancelled' ? 'border-red-500 bg-red-50' : '' }}">
                                    <input type="radio" name="status" value="cancelled" class="peer sr-only" {{ old('status', $event->status) == 'cancelled' ? 'checked' : '' }}>
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 border-2 rounded-full border-red-500 flex items-center justify-center mr-3">
                                            <div class="w-3 h-3 rounded-full bg-red-500 {{ old('status', $event->status) == 'cancelled' ? '' : 'hidden' }}"></div>
                                        </div>
                                        <div>
                                            <span class="text-sm font-medium">Cancelled</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" name="is_featured" value="1" {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Featured Event</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500">Featured events will be highlighted on the school's homepage</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <a href="{{ route('school.events.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Update Event
                    </button>
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
        
        // Time validation
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        
        endTimeInput.addEventListener('change', function() {
            if (startTimeInput.value && this.value) {
                if (this.value <= startTimeInput.value) {
                    alert('End time must be later than start time');
                    this.value = '';
                }
            }
        });
        
        // Fix for custom radio buttons
        const radioButtons = document.querySelectorAll('input[name="status"]');
        
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                const indicators = document.querySelectorAll('.w-3.h-3.rounded-full');
                
                // Hide all indicators first
                indicators.forEach(el => {
                    el.classList.add('hidden');
                });
                
                // Show the selected indicator
                if (this.checked) {
                    const indicator = this.parentElement.querySelector('.w-3.h-3');
                    indicator.classList.remove('hidden');
                    
                    // Add styling to the parent label
                    const labels = document.querySelectorAll('input[name="status"]').forEach(r => {
                        const label = r.closest('label');
                        if (r === this) {
                            if (r.value === 'upcoming') {
                                label.classList.add('border-blue-500', 'bg-blue-50');
                            } else if (r.value === 'ongoing') {
                                label.classList.add('border-green-500', 'bg-green-50');
                            } else if (r.value === 'completed') {
                                label.classList.add('border-gray-500', 'bg-gray-50');
                            } else if (r.value === 'cancelled') {
                                label.classList.add('border-red-500', 'bg-red-50');
                            }
                        } else {
                            label.classList.remove('border-blue-500', 'bg-blue-50', 'border-green-500', 'bg-green-50', 'border-gray-500', 'bg-gray-50', 'border-red-500', 'bg-red-50');
                            label.classList.add('border-gray-300');
                        }
                    });
                }
            });
        });
    });
</script> 