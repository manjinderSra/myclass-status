@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')


<div class="flex">
    
    @include('client.schoolPanel.layout.sidebar')
    
    <div id="profile-view">
        <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">General Settings / <span class="text-l text-gray-500"> Institute Profile</span></h1>
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            
            <div
                class="bg-white rounded-xl shadow-1.5xl max-w-6xl w-full p-8 transition-all duration-300 animate-fade-in">
                
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/3 text-center mb-8 md:mb-0">
                        @if($school->logo)
                            <img src="{{ asset('storage/school_logos/' . $school->logo) }}" alt="School Logo" class="rounded-full w-48 h-48 mx-auto mb-4 border-4 border-gray-900 object-cover transition-transform duration-300 hover:scale-105">
                        @else
                            <img src="{{ asset('assets/images/default-school-logo.png') }}" alt="Default School Logo" class="rounded-full w-48 h-48 mx-auto mb-4 border-4 border-gray-900 transition-transform duration-300 hover:scale-105">
                        @endif
                        <h1 class="text-2xl font-bold text-black mb-2">{{ $school->name }}</h1>
                        <p class="text-gray-600">{{ $school->tagline ?? 'Add your school tagline' }}</p>
                        <button id="edit-profile-btn"
                            class="mt-4 bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors duration-300">Edit
                            Profile</button>

                    </div>
                    <div class="md:w-2/3 md:pl-8">
                        <h2 class="text-xl font-semibold text-black mb-4">About Us</h2>
                        <p class="text-gray-700 mb-6">
                            {{ $school->about ?? 'No information available. Click "Edit Profile" to add information about your institute.' }}
                        </p>
                        <h2 class="text-xl font-semibold text-black mb-4">Contact Information</h2>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-900"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                                {{ $school->email }}
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-900"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                </svg>
                                {{ $school->phone ?? 'No phone number added' }}
                            </li>
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-900"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $school->address ?? 'No address added' }}
                            </li>
                            @if($school->website)
                            <li class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-900"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.93-10.62a6 6 0 01-7.86 7.86A7.964 7.964 0 0010 18a8 8 0 003.93-10.62zM10 2c.34 0 .67.03 1 .08a6.003 6.003 0 00-7.9 7.9A8.01 8.01 0 0110 2zm0 3a5 5 0 100 10A5 5 0 0010 5z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $school->website }}
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <style>
                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(-10px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .animate-fade-in {
                    animation: fadeIn 0.5s ease-out forwards;
                }
            </style>

            <script>
                // You can remove or update this section based on whether you're using dark mode
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.remove('dark');
                }
            </script>
        </div>
    </div>
    <div id="profile-edit" class="hidden flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-100">
    <div class="bg-white rounded-xl shadow-1.5xl max-w-6xl w-full p-8 transition-all duration-300 animate-fade-in">
        <div class="flex flex-col md:flex-row">
            <div class="md:w-1/3 text-center mb-8 md:mb-0">
                <div class="mb-4">
                    <h3 class="text-xl font-semibold text-black mb-4">School Logo</h3>
                    
                    @if($school->logo)
                        <img id="profile-img-preview" src="{{ asset('storage/school_logos/' . $school->logo) }}" class="mt-4 rounded-full w-40 h-40 mx-auto border-4 border-gray-900 object-cover" />
                    @else
                        <img id="profile-img-preview" src="{{ asset('assets/images/default-school-logo.png') }}" class="mt-4 rounded-full w-40 h-40 mx-auto border-4 border-gray-900" />
                    @endif
                </div>
            </div>
            <div class="md:w-2/3 md:pl-8">
                <form id="edit-profile-form" action="{{ route('school.instituteProfile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">School Logo</label>
                        <input type="file" id="logo" name="logo" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">Recommended size: 300x300 pixels</p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">School Name*</label>
                        <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg" value="{{ $school->name }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Tagline</label>
                        <input type="text" name="tagline" class="w-full px-4 py-2 border rounded-lg" value="{{ $school->tagline ?? '' }}" placeholder="Empowering Minds, Transforming Lives">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Email*</label>
                        <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg" value="{{ $school->email }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Phone</label>
                        <input type="text" name="phone" class="w-full px-4 py-2 border rounded-lg" value="{{ $school->phone ?? '' }}">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Address</label>
                        <input type="text" name="address" class="w-full px-4 py-2 border rounded-lg" value="{{ $school->address ?? '' }}">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Website</label>
                        <input type="text" name="website" class="w-full px-4 py-2 border rounded-lg" value="{{ $school->website ?? '' }}" placeholder="www.yourschool.com">
                    </div>

                    <!-- About Me -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">About Us</label>
                        <textarea name="about" class="w-full px-4 py-2 border rounded-lg" rows="4">{{ $school->about ?? '' }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Write a brief description about your school, its mission, vision and values.</p>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800">Save Changes</button>
                        <button type="button" onclick="toggleEdit(false)" class="bg-gray-300 text-black px-4 py-2 rounded-lg hover:bg-gray-400">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script>
        document.getElementById('edit-profile-btn').addEventListener('click', () => {
            toggleEdit(true);
        });

        function toggleEdit(showEdit = true) {
            const view = document.getElementById('profile-view');
            const edit = document.getElementById('profile-edit');
            if (showEdit) {
                view.classList.add('hidden');
                edit.classList.remove('hidden');
            } else {
                view.classList.remove('hidden');
                edit.classList.add('hidden');
            }
        }

        document.getElementById('logo').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profile-img-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

</div>

@include('client.schoolPanel.layout.footer')
