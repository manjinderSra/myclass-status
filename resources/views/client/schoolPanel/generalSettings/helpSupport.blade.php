@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                General Settings / <span class="text-l text-gray-500">Help and Support</span>
            </h1>
            <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition" data-bs-toggle="modal" data-bs-target="#editHelpSupportModal">
                <i class="ri-edit-box-line align-middle me-1"></i> Edit Information
            </button>
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-lg p-6 transition-all duration-300">
                <h5 class="font-semibold mb-3 text-gray-800">Address</h5>
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-blue-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-700">{{ $helpSupport->address ?? 'No address provided' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 transition-all duration-300">
                <h5 class="font-semibold mb-3 text-gray-800">Email / Website</h5>
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-blue-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <div>
                        <p class="mb-1 text-gray-700">{{ $helpSupport->email ?? 'No email provided' }}</p>
                        <p class="text-gray-700">{{ $helpSupport->website ?? 'No website provided' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 transition-all duration-300">
                <h5 class="font-semibold mb-3 text-gray-800">Working Hours</h5>
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-blue-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        @if($helpSupport && $helpSupport->working_hours_start && $helpSupport->working_hours_end)
                            <p class="mb-1 text-gray-700">{{ $helpSupport->working_hours_start }} to {{ $helpSupport->working_hours_end }}</p>
                        @else
                            <p class="mb-1 text-gray-700">No working hours provided</p>
                        @endif
                        <p class="text-gray-700">{{ $helpSupport->working_days ?? 'No working days provided' }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 transition-all duration-300">
                <h5 class="font-semibold mb-3 text-gray-800">Phone Number</h5>
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-blue-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-700">{{ $helpSupport->phone_numbers ?? 'No phone numbers provided' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Help Support Modal -->
<div id="editHelpSupportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" aria-labelledby="editHelpSupportModalLabel" aria-hidden="true">
    <div class="bg-white rounded-lg max-w-lg w-full p-6 relative">
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-xl font-semibold" id="editHelpSupportModalLabel">Edit Help and Support Information</h5>
            <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ route('school.helpSupport.update') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="address" name="address" rows="3">{{ $helpSupport->address ?? '' }}</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="email" name="email" value="{{ $helpSupport->email ?? '' }}">
                </div>
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                    <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="website" name="website" value="{{ $helpSupport->website ?? '' }}">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="working_hours_start" class="block text-sm font-medium text-gray-700 mb-1">Working Hours (Start)</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="working_hours_start" name="working_hours_start" value="{{ $helpSupport->working_hours_start ?? '' }}" placeholder="e.g. 9am">
                </div>
                <div>
                    <label for="working_hours_end" class="block text-sm font-medium text-gray-700 mb-1">Working Hours (End)</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="working_hours_end" name="working_hours_end" value="{{ $helpSupport->working_hours_end ?? '' }}" placeholder="e.g. 5pm">
                </div>
                <div>
                    <label for="working_days" class="block text-sm font-medium text-gray-700 mb-1">Working Days</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="working_days" name="working_days" value="{{ $helpSupport->working_days ?? '' }}" placeholder="e.g. Monday to Friday">
                </div>
            </div>
            <div class="mb-4">
                <label for="phone_numbers" class="block text-sm font-medium text-gray-700 mb-1">Phone Numbers</label>
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="phone_numbers" name="phone_numbers" rows="2" placeholder="Enter multiple phone numbers separated by commas">{{ $helpSupport->phone_numbers ?? '' }}</textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize modal functionality
        const openModalBtn = document.querySelector('[data-bs-target="#editHelpSupportModal"]');
        const closeModalBtns = document.querySelectorAll('[data-bs-dismiss="modal"]');
        const modal = document.getElementById('editHelpSupportModal');
        
        if (openModalBtn && modal) {
            openModalBtn.addEventListener('click', function() {
                modal.classList.remove('hidden');
            });
        }
        
        if (closeModalBtns.length > 0 && modal) {
            closeModalBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    modal.classList.add('hidden');
                });
            });
        }
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script> 