@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto bg-gray-50">
        <div class="px-6 py-24">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Teacher Details</h1>
                    <div class="flex items-center text-sm text-gray-500 mt-1">
                        <a href="{{ route('school.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                        <span class="mx-2">/</span>
                        <a href="{{ route('school.teachers') }}" class="hover:text-blue-600">Teachers</a>
                        <span class="mx-2">/</span>
                        <span>Teacher Details</span>
                    </div>
                </div>
                <div class="flex space-x-2">
                  
                    <a href="{{ route('school.teachers.edit', $teacher->id) }}" class="bg-blue-600 hover:bg-blue-700 rounded-lg px-4 py-2 flex items-center text-white">
                        <i class="fas fa-edit mr-2"></i> Edit Teacher
                    </a>
                </div>
            </div>

            {{-- This div now holds the activeTab state and controls the content below --}}
            <div x-data="{ activeTab: 'teacherDetails' }">
                

                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-4">
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                            <div class="p-5 flex">
                                <div class="flex justify-center mb-4">
                                    <div class="relative">
                                        @if($teacher->profile_image)
                                            <img src="{{ asset('storage/' . $teacher->profile_image) }}" alt="{{ $teacher->first_name }}'s Photo" class="w-32 h-32 rounded-lg object-cover">
                                        @else
                                            <div class="w-32 h-32 rounded-lg bg-blue-100 flex items-center justify-center text-blue-500 text-2xl font-bold">
                                                {{ strtoupper(substr($teacher->first_name, 0, 1) . substr($teacher->last_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-start mt-6 p-3">
                                    <div class="top-2 right-2 bg-{{ $teacher->status === 'active' ? 'green' : 'red' }}-100 mb-3 rounded-xl px-2 py-1 text-sm text-{{ $teacher->status === 'active' ? 'green' : 'red' }}-600 flex items-center">
                                        <span class="w-2 h-2 bg-{{ $teacher->status === 'active' ? 'green' : 'red' }}-500 rounded-full mr-1"></span>
                                        <span>{{ ucfirst($teacher->status) }}</span>
                                    </div>
                                    <h2 class="text-xl font-semibold text-gray-800">{{ $teacher->first_name }} {{ $teacher->last_name }}</h2>
                                    <p class="text-blue-600 font-medium">{{ $teacher->employee_id }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                            <div class="p-5">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4">Professional Information</h3>

                                <div class="border border-gray-100 rounded-lg p-4 mb-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        @if($teacher->qualification)
                                        <div>
                                            <p class="text-gray-600 text-sm">Highest Qualification</p>
                                            <p class="text-gray-800 font-medium">{{ $teacher->qualification }}</p>
                                        </div>
                                        @endif
                                        @if($teacher->work_experience)
                                        <div>
                                            <p class="text-gray-600 text-sm">Work Experience</p>
                                            <p class="text-gray-800 font-medium">{{ $teacher->work_experience }}</p>
                                        </div>
                                        @endif
                                        @if($teacher->subject)
                                        <div>
                                            <p class="text-gray-600 text-sm">Teaching Subject</p>
                                            <p class="text-gray-800 font-medium">{{ $teacher->subject->name }}</p>
                                        </div>
                                        @endif
                                        @if($teacher->specialization)
                                        <div>
                                            <p class="text-gray-600 text-sm">Specialization</p>
                                            <p class="text-gray-800 font-medium">{{ $teacher->specialization }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                @if($teacher->certifications)
                                <div class="border border-gray-100 rounded-lg p-4">
                                    <p class="text-gray-600 text-sm mb-2">Certifications</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(explode(',', $teacher->certifications) as $certification)
                                        <span class="inline-block bg-blue-50 text-blue-800 text-sm px-3 py-1 rounded-full">{{ trim($certification) }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                            <div class="p-5">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Subject</span>
                                        <span class="text-gray-800">{{ $teacher->subject->name ?? 'Not Assigned' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Gender</span>
                                        <span class="text-gray-800">{{ ucfirst($teacher->gender) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Date Of Birth</span>
                                        <span class="text-gray-800">{{ $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('d M Y') : 'Not provided' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Date Of Joining</span>
                                        <span class="text-gray-800">{{ $teacher->date_of_joining ? \Carbon\Carbon::parse($teacher->date_of_joining)->format('d M Y') : 'Not provided' }}</span>
                                    </div>
                                    @if($teacher->blood_group)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Blood Group</span>
                                        <span class="text-gray-800">{{ $teacher->blood_group }}</span>
                                    </div>
                                    @endif
                                    @if($teacher->marital_status)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Marital Status</span>
                                        <span class="text-gray-800">{{ ucfirst($teacher->marital_status) }}</span>
                                    </div>
                                    @endif
                                    @if($teacher->languages_known)
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Languages</span>
                                        <div>
                                            @foreach(explode(',', $teacher->languages_known) as $language)
                                            <span class="inline-block bg-gray-100 text-gray-800 text-xs px-3 py-1 rounded-full">{{ trim($language) }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                            <div class="p-5">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4">Primary Contact Info</h3>
                                <div class="space-y-5">
                                    @if($teacher->primary_contact)
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 bg-gray-100 rounded-full p-2 flex items-center justify-center text-gray-600 mr-3">
                                            <svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M13.5 2C13.5 2 15.8335 2.21213 18.8033 5.18198C21.7731 8.15183 21.9853 10.4853 21.9853 10.4853" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M14.207 5.53564C14.207 5.53564 15.197 5.81849 16.6819 7.30341C18.1668 8.78834 18.4497 9.77829 18.4497 9.77829" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                                                <path opacity="0.5" d="M15.1007 15.0272L14.5569 14.5107L15.1007 15.0272ZM15.5562 14.5477L16.1 15.0642H16.1L15.5562 14.5477ZM17.9728 14.2123L17.5987 14.8623H17.5987L17.9728 14.2123ZM19.8833 15.312L19.5092 15.962L19.8833 15.312ZM20.4217 18.7584L20.9655 19.2749L20.4217 18.7584ZM19.0011 20.254L18.4573 19.7375L19.0011 20.254ZM17.6763 20.9631L17.7499 21.7095L17.6763 20.9631ZM7.81536 16.4752L8.35915 15.9587L7.81536 16.4752ZM3.00289 6.96594L2.25397 7.00613L2.25397 7.00613L3.00289 6.96594ZM9.47752 8.50311L10.0213 9.01963H10.0213L9.47752 8.50311ZM9.63424 5.6931L10.2466 5.26012L9.63424 5.6931ZM8.37326 3.90961L7.76086 4.3426V4.3426L8.37326 3.90961ZM5.26145 3.60864L5.80524 4.12516L5.26145 3.60864ZM3.69185 5.26114L3.14806 4.74462L3.14806 4.74462L3.69185 5.26114ZM11.0631 13.0559L11.6069 12.5394L11.0631 13.0559ZM15.6445 15.5437L16.1 15.0642L15.0124 14.0312L14.5569 14.5107L15.6445 15.5437ZM17.5987 14.8623L19.5092 15.962L20.2575 14.662L18.347 13.5623L17.5987 14.8623ZM19.8779 18.2419L18.4573 19.7375L19.5449 20.7705L20.9655 19.2749L19.8779 18.2419ZM17.6026 20.2167C16.1676 20.3584 12.4233 20.2375 8.35915 15.9587L7.27157 16.9917C11.7009 21.655 15.9261 21.8895 17.7499 21.7095L17.6026 20.2167ZM8.35915 15.9587C4.48303 11.8778 3.83285 8.43556 3.75181 6.92574L2.25397 7.00613C2.35322 8.85536 3.1384 12.6403 7.27157 16.9917L8.35915 15.9587ZM9.7345 9.32159L10.0213 9.01963L8.93372 7.9866L8.64691 8.28856L9.7345 9.32159ZM10.2466 5.26012L8.98565 3.47663L7.76086 4.3426L9.02185 6.12608L10.2466 5.26012ZM4.71766 3.09213L3.14806 4.74462L4.23564 5.77765L5.80524 4.12516L4.71766 3.09213ZM9.1907 8.80507C8.64691 8.28856 8.64622 8.28929 8.64552 8.29002C8.64528 8.29028 8.64458 8.29102 8.64411 8.29152C8.64316 8.29254 8.64219 8.29357 8.64121 8.29463C8.63924 8.29675 8.6372 8.29896 8.6351 8.30127C8.63091 8.30588 8.62646 8.31087 8.62178 8.31625C8.61243 8.32701 8.60215 8.33931 8.59116 8.3532C8.56918 8.38098 8.54431 8.41512 8.51822 8.45588C8.46591 8.53764 8.40917 8.64531 8.36112 8.78033C8.26342 9.0549 8.21018 9.4185 8.27671 9.87257C8.40742 10.7647 8.99198 11.9644 10.5193 13.5724L11.6069 12.5394C10.1793 11.0363 9.82761 10.1106 9.76086 9.65511C9.72866 9.43536 9.76138 9.31957 9.77432 9.28321C9.78159 9.26277 9.78635 9.25709 9.78169 9.26437C9.77944 9.26789 9.77494 9.27451 9.76738 9.28407C9.76359 9.28885 9.75904 9.29437 9.7536 9.30063C9.75088 9.30375 9.74793 9.30706 9.74476 9.31056C9.74317 9.31231 9.74152 9.3141 9.73981 9.31594C9.73896 9.31686 9.73809 9.31779 9.7372 9.31873C9.73676 9.3192 9.73608 9.31992 9.73586 9.32015C9.73518 9.32087 9.7345 9.32159 9.1907 8.80507ZM10.5193 13.5724C12.0422 15.1757 13.1923 15.806 14.0698 15.9485C14.5201 16.0216 14.8846 15.9632 15.1606 15.8544C15.2955 15.8012 15.4022 15.7387 15.4823 15.6819C15.5223 15.6535 15.5556 15.6266 15.5824 15.6031C15.5959 15.5913 15.6077 15.5803 15.618 15.5703C15.6232 15.5654 15.628 15.5606 15.6324 15.5562C15.6346 15.554 15.6367 15.5518 15.6387 15.5497C15.6397 15.5487 15.6407 15.5477 15.6417 15.5467C15.6422 15.5462 15.6429 15.5454 15.6431 15.5452C15.6438 15.5444 15.6445 15.5437 15.1007 15.0272C14.5569 14.5107 14.5576 14.51 14.5583 14.5093C14.5585 14.509 14.5592 14.5083 14.5596 14.5078C14.5605 14.5069 14.5614 14.506 14.5623 14.5051C14.5641 14.5033 14.5658 14.5015 14.5674 14.4998C14.5708 14.4965 14.574 14.4933 14.577 14.4904C14.583 14.4846 14.5885 14.4796 14.5933 14.4754C14.6028 14.467 14.6099 14.4616 14.6145 14.4584C14.6239 14.4517 14.6229 14.454 14.6102 14.459C14.5909 14.4666 14.5 14.4987 14.3103 14.4679C13.9077 14.4025 13.0391 14.0472 11.6069 12.5394L10.5193 13.5724ZM8.98565 3.47663C7.97206 2.04305 5.94384 1.80119 4.71766 3.09213L5.80524 4.12516C6.32808 3.57471 7.24851 3.61795 7.76086 4.3426L8.98565 3.47663ZM3.75181 6.92574C3.73038 6.52644 3.90425 6.12654 4.23564 5.77765L3.14806 4.74462C2.61221 5.30877 2.20493 6.09246 2.25397 7.00613L3.75181 6.92574ZM18.4573 19.7375C18.1783 20.0313 17.8864 20.1887 17.6026 20.2167L17.7499 21.7095C18.497 21.6357 19.1016 21.2373 19.5449 20.7705L18.4573 19.7375ZM10.0213 9.01963C10.9889 8.00095 11.0574 6.40678 10.2466 5.26012L9.02185 6.12608C9.44399 6.72315 9.37926 7.51753 8.93372 7.9866L10.0213 9.01963ZM19.5092 15.962C20.33 16.4345 20.4907 17.5968 19.8779 18.2419L20.9655 19.2749C22.2704 17.901 21.8904 15.6019 20.2575 14.662L19.5092 15.962ZM16.1 15.0642C16.4854 14.6584 17.086 14.5672 17.5987 14.8623L18.347 13.5623C17.2485 12.93 15.8861 13.1113 15.0124 14.0312L16.1 15.0642Z" fill="#1C274C"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 text-sm">Phone Number</p>
                                            <p class="text-gray-800 font-medium">{{ $teacher->primary_contact }}</p>
                                        </div>
                                    </div>
                                    @endif
                                    @if($teacher->email)
                                    <div class="flex items-start">
                                        <div class="w-10 h-10 p-2 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 mr-3">
                                            <svg width="800px" height="800px" viewBox="-0.5 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                                                <g id="SVGRepo_iconCarrier"> <path d="M22.0098 12.39V7.39001C22.0098 6.32915 21.5883 5.31167 20.8382 4.56152C20.0881 3.81138 19.0706 3.39001 18.0098 3.39001H6.00977C4.9489 3.39001 3.93148 3.81138 3.18134 4.56152C2.43119 5.31167 2.00977 6.32915 2.00977 7.39001V17.39C2.00977 18.4509 2.43119 19.4682 3.18134 20.2184C3.93148 20.9685 4.9489 21.39 6.00977 21.39H12.0098" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path d="M21.209 5.41992C15.599 16.0599 8.39906 16.0499 2.78906 5.41992" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path d="M15.0098 18.39H23.0098" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path d="M20.0098 15.39L23.0098 18.39L20.0098 21.39" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </g>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 text-sm">Email Address</p>
                                            <p class="text-gray-800 font-medium">{{ $teacher->email }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="flex border-b" x-data="{ activeTab: 'hostel' }">
                                <button @click="activeTab = 'hostel'" 
                                        :class="{ 'text-blue-600 border-b-2 border-blue-600 font-medium': activeTab === 'hostel', 'text-gray-600': activeTab !== 'hostel' }"
                                        class="flex-1 py-3 text-center">Hostel</button>
                                <button @click="activeTab = 'transportation'" 
                                        :class="{ 'text-blue-600 border-b-2 border-blue-600 font-medium': activeTab === 'transportation', 'text-gray-600': activeTab !== 'transportation' }"
                                        class="flex-1 py-3 text-center">Transportation</button>
                            </div>
                            <div class="p-5">
                                <div x-show="activeTab === 'hostel'">
                                    @if($teacher->hostel_enabled && $teacher->hostel_id)
                                    <div class="space-y-4">
                                        <div class="flex items-start">
                                            <div class="w-10 h-10 p-2 bg-gray-100 rounded-xl flex items-center justify-center text-gray-600 mr-3">
                                                <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <defs> <style>.cls-1{fill:none;stroke:#000000;stroke-linejoin:round;stroke-width:2px;}</style> </defs> <title></title> <g data-name="Layer 5" id="Layer_5"> <rect class="cls-1" height="6" width="48" x="14" y="50"></rect> <polyline class="cls-1" points="60 56 60 62 56 62 56 56"></polyline> <polyline class="cls-1" points="20 56 20 62 16 62 16 56"></polyline> <path class="cls-1" d="M60,50V46a2,2,0,0,0-2-2H18a2,2,0,0,0-2,2v4"></path> <path class="cls-1" d="M54,44V39a1,1,0,0,0-1-1H41a1,1,0,0,0-1,1v5"></path> <path class="cls-1" d="M36,44V39a1,1,0,0,0-1-1H23a1,1,0,0,0-1,1v5"></path> <path class="cls-1" d="M58,44V36a4,4,0,0,0-4-4H22a4,4,0,0,0-4,4v8"></path> <path class="cls-1" d="M33.42,32A18,18,0,1,0,18,37.89"></path> <line class="cls-1" x1="20" x2="20" y1="2" y2="7"></line> <line class="cls-1" x1="2" x2="7" y1="20" y2="20"></line> <line class="cls-1" x1="33" x2="38" y1="20" y2="20"></line> <polyline class="cls-1" points="29 20 20 20 20 13"></polyline> <polyline class="cls-1" points="41 20 48 20 42 26 49 26"></polyline> <polyline class="cls-1" points="62 16 55 16 61 10 54 10"></polyline> </g> </g></svg>
                                            </div>
                                            <div>
                                                <p class="text-gray-600 text-sm">Hostel Room</p>
                                                <p class="text-gray-800 font-medium">Room No: {{ $teacher->room_id }}</p>
                                                @if($teacher->hostel)
                                                <p class="text-gray-600 text-sm mt-2">Hostel</p>
                                                <p class="text-gray-800 font-medium">{{ $teacher->hostel->name ?? 'Not specified' }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="flex justify-center items-center p-4 text-gray-500">
                                        <p>No hostel assignment</p>
                                    </div>
                                    @endif
                                </div>
                                <div x-show="activeTab === 'transportation'" x-cloak>
                                    @if($teacher->transport_enabled && $teacher->pickup_point_id)
                                    <div class="space-y-4">
                                        <div class="flex items-start">
                                            <div class="w-10 h-10 p-2 bg-gray-100 rounded-xl flex items-center justify-center text-gray-600 mr-3">
                                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8 17H16M8 17C8 18.1046 7.10457 19 6 19C4.89543 19 4 18.1046 4 17M8 17C8 15.8954 7.10457 15 6 15C4.89543 15 4 15.8954 4 17M16 17C16 18.1046 16.8954 19 18 19C19.1046 19 20 18.1046 20 17M16 17C16 15.8954 16.8954 15 18 15C19.1046 15 20 15.8954 20 17M10 5V11M14 5V11M4 9H20M4 17H3V13.5C3 12.6716 3.67157 12 4.5 12H19.5C20.3284 12 21 12.6716 21 13.5V17H20M12 5H19.5C20.3284 5 21 5.67157 21 6.5V9M12 5H4.5C3.67157 5 3 5.67157 3 6.5V9" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-gray-600 text-sm">Pickup Point</p>
                                                <p class="text-gray-800 font-medium">Pickup ID: {{ $teacher->pickup_point_id }}</p>
                                                @if($teacher->pickupPoint)
                                                <p class="text-gray-600 text-sm mt-2">Location</p>
                                                <p class="text-gray-800 font-medium">{{ $teacher->pickupPoint->location ?? 'Not specified' }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="flex justify-center items-center p-4 text-gray-500">
                                        <p>No transportation assignment</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-8">
                        <div x-show="activeTab === 'teacherDetails'" class="space-y-6">
                            <div class="grid grid-cols-2 gap-6 mb-6">
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                    <div class="p-5">
                                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Documents</h3>

                                        @if($teacher->resume_document)
                                        <div class="flex justify-between items-center mb-5 px-4 py-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center">
                                                <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded mr-3">PDF</span>
                                                <span class="text-gray-800">Resume/CV</span>
                                            </div>
                                            <a href="{{ route('school.peoples.teachers.document', ['id' => $teacher->employee_id, 'document_type' => 'resume']) }}" class="bg-gray-800 h-8 w-8 text-white p-2 rounded-lg">
                                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path opacity="0.5" d="M3 15C3 17.8284 3 19.2426 3.87868 20.1213C4.75736 21 6.17157 21 9 21H15C17.8284 21 19.2426 21 20.1213 20.1213C21 19.2426 21 17.8284 21 15" stroke="#feffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 3V16M12 16L16 11.625M12 16L8 11.625" stroke="#feffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                            </a>
                                        </div>
                                        @else
                                        <div class="flex justify-between items-center mb-5 px-4 py-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center">
                                                <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded mr-3">PDF</span>
                                                <span class="text-gray-800">Resume/CV</span>
                                            </div>
                                            <span class="text-xs text-gray-500">Not available</span>
                                        </div>
                                        @endif

                                        @if($teacher->certificates_document)
                                        <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center">
                                                <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded mr-3">PDF</span>
                                                <span class="text-gray-800">Certificates</span>
                                            </div>
                                            <a href="{{ route('school.peoples.teachers.document', ['id' => $teacher->employee_id, 'document_type' => 'certificates']) }}" class="bg-gray-800 h-8 w-8 text-white p-2 rounded-lg">
                                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path opacity="0.5" d="M3 15C3 17.8284 3 19.2426 3.87868 20.1213C4.75736 21 6.17157 21 9 21H15C17.8284 21 19.2426 21 20.1213 20.1213C21 19.2426 21 17.8284 21 15" stroke="#feffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 3V16M12 16L16 11.625M12 16L8 11.625" stroke="#feffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                            </a>
                                        </div>
                                        @else
                                        <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center">
                                                <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded mr-3">PDF</span>
                                                <span class="text-gray-800">Certificates</span>
                                            </div>
                                            <span class="text-xs text-gray-500">Not available</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                    <div class="p-5">
                                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Address</h3>

                                        @if($teacher->current_address)
                                        <div class="mb-5">
                                            <div class="flex items-center mb-2">
                                                <div class="w-8 h-8 bg-gray-100 rounded-xl p-2 flex items-center justify-center text-gray-500 mr-2">
                                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 21C15.5 17.4 19 14.1764 19 10.2C19 6.22355 15.866 3 12 3C8.13401 3 5 6.22355 5 10.2C5 14.1764 8.5 17.4 12 21Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 12C13.1046 12 14 11.1046 14 10C14 8.89543 13.1046 8 12 8C10.8954 8 10 8.89543 10 10C10 11.1046 10.8954 12 12 12Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                                </div>
                                                <h4 class="font-medium text-gray-700">Current Address</h4>
                                            </div>
                                            <p class="text-gray-700 ml-10">{{ $teacher->current_address }}</p>
                                        </div>
                                        @endif

                                        @if($teacher->permanent_address)
                                        <div>
                                            <div class="flex items-center mb-2">
                                                <div class="w-8 h-8 bg-gray-100 rounded-xl p-2 flex items-center justify-center text-gray-500 mr-2">
                                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 21C15.5 17.4 19 14.1764 19 10.2C19 6.22355 15.866 3 12 3C8.13401 3 5 6.22355 5 10.2C5 14.1764 8.5 17.4 12 21Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 12C13.1046 12 14 11.1046 14 10C14 8.89543 13.1046 8 12 8C10.8954 8 10 8.89543 10 10C10 11.1046 10.8954 12 12 12Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                                </div>
                                                <h4 class="font-medium text-gray-700">Permanent Address</h4>
                                            </div>
                                            <p class="text-gray-700 ml-10">{{ $teacher->permanent_address }}</p>
                                        </div>
                                        @endif
                                        
                                        @if(!$teacher->current_address && !$teacher->permanent_address)
                                        <div class="flex justify-center items-center p-4 text-gray-500">
                                            <p>No address information available</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                                <div class="p-5">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Previous Employment</h3>
                                    @if($teacher->previous_employer || $teacher->previous_position)
                                    <div class="grid grid-cols-2 gap-6">
                                        @if($teacher->previous_employer)
                                        <div>
                                            <p class="text-gray-600 text-sm">Previous Employer</p>
                                            <p class="text-gray-800 font-medium">{{ $teacher->previous_employer }}</p>
                                        </div>
                                        @endif
                                        @if($teacher->previous_position)
                                        <div>
                                            <p class="text-gray-600 text-sm">Position Held</p>
                                            <p class="text-gray-800 font-medium">{{ $teacher->previous_position }}</p>
                                        </div>
                                        @endif
                                        @if($teacher->previous_employment_period)
                                        <div>
                                            <p class="text-gray-600 text-sm">Employment Period</p>
                                            <p class="text-gray-800 font-medium">{{ $teacher->previous_employment_period }}</p>
                                        </div>
                                        @endif
                                    </div>
                                    @else
                                    <div class="flex justify-center items-center p-4 text-gray-500">
                                        <p>No previous employment information available</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6 mb-6">
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                    <div class="p-5">
                                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Bank Details</h3>
                                        @if($teacher->bank_name || $teacher->branch || $teacher->account_number)
                                        <table class="w-full">
                                            <tbody>
                                            @if($teacher->bank_name)
                                            <tr>
                                                <td class="py-2 text-gray-600">Bank Name</td>
                                                <td class="py-2 text-right text-gray-800 font-medium">{{ $teacher->bank_name }}</td>
                                            </tr>
                                            @endif
                                            @if($teacher->account_number)
                                            <tr>
                                                <td class="py-2 text-gray-600">Account Number</td>
                                                <td class="py-2 text-right text-gray-800 font-medium">{{ $teacher->account_number }}</td>
                                            </tr>
                                            @endif
                                            @if($teacher->branch)
                                            <tr>
                                                <td class="py-2 text-gray-600">Branch</td>
                                                <td class="py-2 text-right text-gray-800 font-medium">{{ $teacher->branch }}</td>
                                            </tr>
                                            @endif
                                            @if($teacher->ifsc_code)
                                            <tr>
                                                <td class="py-2 text-gray-600">IFSC Code</td>
                                                <td class="py-2 text-right text-gray-800 font-medium">{{ $teacher->ifsc_code }}</td>
                                            </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                        @else
                                        <div class="flex justify-center items-center p-4 text-gray-500">
                                            <p>No bank details available</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                    <div class="p-5">
                                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Emergency Contact</h3>
                                        @if($teacher->emergency_contact_name || $teacher->emergency_contact_phone)
                                        <div class="space-y-4">
                                            @if($teacher->emergency_contact_name)
                                            <div>
                                                <p class="text-gray-600 text-sm">Contact Name</p>
                                                <p class="text-gray-800 font-medium">{{ $teacher->emergency_contact_name }}</p>
                                            </div>
                                            @endif
                                            @if($teacher->emergency_contact_phone)
                                            <div>
                                                <p class="text-gray-600 text-sm">Contact Phone</p>
                                                <p class="text-gray-800 font-medium">{{ $teacher->emergency_contact_phone }}</p>
                                            </div>
                                            @endif
                                            @if($teacher->emergency_contact_relation)
                                            <div>
                                                <p class="text-gray-600 text-sm">Relationship</p>
                                                <p class="text-gray-800 font-medium">{{ $teacher->emergency_contact_relation }}</p>
                                            </div>
                                            @endif
                                        </div>
                                        @else
                                        <div class="flex justify-center items-center p-4 text-gray-500">
                                            <p>No emergency contact details available</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                <div class="p-5">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Additional Information</h3>
                                    @if($teacher->additional_info)
                                        <p class="text-gray-700">{{ $teacher->additional_info }}</p>
                                    @else
                                        <p class="text-gray-700">No additional information available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                       
        </div>
    </div>
</div>
@include('client.schoolPanel.layout.footer')