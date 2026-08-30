@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto bg-gray-50">
        <div class="px-6 py-24">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Student Details</h1>
                    <div class="flex items-center text-sm text-gray-500 mt-1">
                        <a href="{{ route('school.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                        <span class="mx-2">/</span>
                        <a href="{{ route('school.students') }}" class="hover:text-blue-600">Students</a>
                        <span class="mx-2">/</span>
                        <span>Student Details</span>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="bg-gray-100 hover:bg-gray-200 rounded-lg px-4 py-2 flex items-center text-gray-700">
                        <i class="fas fa-lock mr-2"></i> Login Details
                    </button>
                    <a href="{{ route('school.students.edit', $student->admission_number) }}" class="bg-blue-600 hover:bg-blue-700 rounded-lg px-4 py-2 flex items-center text-white">
                        <i class="fas fa-edit mr-2"></i> Edit Student
                    </a>
                </div>
            </div>

            {{-- This div now holds the activeTab state and controls the content below --}}
            <div x-data="{ activeTab: 'studentDetails' }">
                <div class="bg-white rounded-lg overflow-hidden mb-6">
                    <div class="flex border-b">
                        <a href="#" @click.prevent="activeTab = 'studentDetails'"
                           :class="{ 'text-blue-600 border-blue-600': activeTab === 'studentDetails', 'text-gray-700 hover:text-blue-600': activeTab !== 'studentDetails' }"
                           class="px-4 py-3 border-b-2 font-medium flex items-center">
                            <i class="fas fa-user mr-2"></i> Student Details
                        </a>
                        <a href="#" @click.prevent="activeTab = 'timetable'"
                           :class="{ 'text-blue-600 border-blue-600': activeTab === 'timetable', 'text-gray-700 hover:text-blue-600': activeTab !== 'timetable' }"
                           class="px-4 py-3 border-b-2 font-medium flex items-center">
                            <i class="fas fa-calendar mr-2"></i> Time Table
                        </a>
                        <a href="#" @click.prevent="activeTab = 'leaveAttendance'"
                           :class="{ 'text-blue-600 border-blue-600': activeTab === 'leaveAttendance', 'text-gray-700 hover:text-blue-600': activeTab !== 'leaveAttendance' }"
                           class="px-4 py-3 border-b-2 font-medium flex items-center">
                            <i class="fas fa-clipboard-check mr-2"></i> Leave & Attendance
                        </a>
                        <a href="#" @click.prevent="activeTab = 'fees'"
                           :class="{ 'text-blue-600 border-blue-600': activeTab === 'fees', 'text-gray-700 hover:text-blue-600': activeTab !== 'fees' }"
                           class="px-4 py-3 border-b-2 font-medium flex items-center">
                            <i class="fas fa-money-bill mr-2"></i> Fees
                        </a>
                        <a href="#" @click.prevent="activeTab = 'examResults'"
                           :class="{ 'text-blue-600 border-blue-600': activeTab === 'examResults', 'text-gray-700 hover:text-blue-600': activeTab !== 'examResults' }"
                           class="px-4 py-3 border-b-2 font-medium flex items-center">
                            <i class="fas fa-file-alt mr-2"></i> Exam & Results
                        </a>
                        <a href="#" @click.prevent="activeTab = 'library'"
                           :class="{ 'text-blue-600 border-blue-600': activeTab === 'library', 'text-gray-700 hover:text-blue-600': activeTab !== 'library' }"
                           class="px-4 py-3 border-b-2 font-medium flex items-center">
                            <i class="fas fa-book mr-2"></i> Library
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-4">
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                            <div class="p-5 flex">
                                <div class="flex justify-center mb-4">
                                    <div class="relative">
                                        @if($student->profile_image)
                                            <img src="{{ asset('storage/' . $student->profile_image) }}" alt="{{ $student->first_name }}'s Photo" class="w-32 h-32 rounded-lg object-cover">
                                        @else
                                            <div class="w-32 h-32 rounded-lg bg-blue-100 flex items-center justify-center text-blue-500 text-2xl font-bold">
                                                {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-start mt-6 p-3">
                                    <div class="top-2 right-2 bg-{{ $student->status === 'active' ? 'green' : 'red' }}-100 mb-3 rounded-xl px-2 py-1 text-sm text-{{ $student->status === 'active' ? 'green' : 'red' }}-600 flex items-center">
                                        <span class="w-2 h-2 bg-{{ $student->status === 'active' ? 'green' : 'red' }}-500 rounded-full mr-1"></span>
                                        <span>{{ ucfirst($student->status) }}</span>
                                    </div>
                                    <h2 class="text-xl font-semibold text-gray-800">{{ $student->first_name }} {{ $student->last_name }}</h2>
                                    <p class="text-blue-600 font-medium">{{ $student->admission_number }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                            <div class="p-5">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h3>
                                <div class="space-y-4">
                                    @if($student->roll_number)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Roll No</span>
                                        <span class="text-gray-800">{{ $student->roll_number }}</span>
                                    </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Class</span>
                                        <span class="text-gray-800">{{ $student->class->name ?? 'Not Assigned' }} {{ $student->section->name ?? '' }}</span>
                                    </div>
                                   
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Aadhaar Number</span>
                                        <span class="text-gray-800">{{ $student->aadhaar_number ?? 'Not Provided' }}</span>
                                    </div>
                                    
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Gender</span>
                                        <span class="text-gray-800">{{ ucfirst($student->gender) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Date Of Birth</span>
                                        <span class="text-gray-800">{{ $student->dob ? $student->dob->format('d M Y') : 'Not provided' }}</span>
                                    </div>
                                    @if($student->blood_group)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Blood Group</span>
                                        <span class="text-gray-800">{{ $student->blood_group }}</span>
                                    </div>
                                    @endif
                                    @if($student->house)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">House</span>
                                        <span class="text-gray-800">{{ $student->house }}</span>
                                    </div>
                                    @endif
                                    @if($student->religion)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Religion</span>
                                        <span class="text-gray-800">{{ $student->religion }}</span>
                                    </div>
                                    @endif
                                    @if($student->category)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Category</span>
                                        <span class="text-gray-800">{{ $student->category }}</span>
                                    </div>
                                    @endif
                                    @if($student->mother_tongue)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Mother tongue</span>
                                        <span class="text-gray-800">{{ $student->mother_tongue }}</span>
                                    </div>
                                    @endif
                                    @if($student->languages_known && count($student->languages_known) > 0)
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Language</span>
                                        <div>
                                            @foreach($student->languages_known as $language)
                                            <span class="inline-block bg-gray-100 text-gray-800 text-xs px-3 py-1 rounded-full">{{ $language }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <a href="{{ route('school.collectFee') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg mt-6 text-center">
                                    Add Fees
                                </a>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                            <div class="p-5">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4">Primary Contact Info</h3>
                                <div class="space-y-5">
                                    @if($student->primary_contact)
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
                                            <p class="text-gray-800 font-medium">{{ $student->primary_contact }}</p>
                                        </div>
                                    </div>
                                    @endif
                                    @if($student->email)
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
                                            <p class="text-gray-800 font-medium">{{ $student->email }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                            <div class="p-5">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4">Sibling Information</h3>
                                <div class="space-y-4">
                                    {{-- This would typically come from a siblings relationship that hasn't been implemented yet --}}
                                    <div class="flex justify-center items-center p-4 text-gray-500">
                                        <p>No sibling information available</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
<div class="flex border-b">
                                <button @click="sideTab = 'hostel'" 
                                        :class="{ 'text-blue-600 border-b-2 border-blue-600 font-medium': activeTab === 'hostel', 'text-gray-600': activeTab !== 'hostel' }"
                                        class="flex-1 py-3 text-center">Hostel</button>
                                <button @click="activeTab = 'transportation'" 
                                        :class="{ 'text-blue-600 border-b-2 border-blue-600 font-medium': activeTab === 'transportation', 'text-gray-600': activeTab !== 'transportation' }"
                                        class="flex-1 py-3 text-center">Transportation</button>
                            </div>
                            <div class="p-5">
<div x-show="activeTab === 'studentDetails'">

    @if($hostelDetails)

        {{-- Header --}}
        <div class="flex items-start mb-4">
            <div class="w-10 h-10 p-2 bg-gray-100 rounded-xl flex items-center justify-center text-gray-600 mr-3">
                <!-- icon -->
            </div>
            <div>
                <p class="text-gray-600 text-sm">Hostel Room</p>
            </div>
        </div>

        {{-- Hostel Card --}}
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
            <div class="p-5">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Hostel Details</h3>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Hostel</span>
                        <span class="text-gray-800">{{ $hostelDetails['hostel_name'] }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Room Number</span>
                        <span class="text-gray-800">{{ $hostelDetails['room_number'] }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Room Type</span>
                        <span class="text-gray-800">{{ $hostelDetails['room_type'] }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Beds</span>
                        <span class="text-gray-800">{{ $hostelDetails['beds'] }}</span>
                    </div>
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
                                    @if($student->transport_enabled && $student->pickup_point_id)
                                    <div class="space-y-4">
                                        <div class="flex items-start">
                                            <div class="w-10 h-10 p-2 bg-gray-100 rounded-xl flex items-center justify-center text-gray-600 mr-3">
                                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8 17H16M8 17C8 18.1046 7.10457 19 6 19C4.89543 19 4 18.1046 4 17M8 17C8 15.8954 7.10457 15 6 15C4.89543 15 4 15.8954 4 17M16 17C16 18.1046 16.8954 19 18 19C19.1046 19 20 18.1046 20 17M16 17C16 15.8954 16.8954 15 18 15C19.1046 15 20 15.8954 20 17M10 5V11M14 5V11M4 9H20M4 17H3V13.5C3 12.6716 3.67157 12 4.5 12H19.5C20.3284 12 21 12.6716 21 13.5V17H20M12 5H19.5C20.3284 5 21 5.67157 21 6.5V9M12 5H4.5C3.67157 5 3 5.67157 3 6.5V9" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-gray-600 text-sm">Pickup Point</p>
                                                <p class="text-gray-800 font-medium">Pickup ID: {{ $student->pickup_point_id }}</p>
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
                        <div x-show="activeTab === 'studentDetails'" class="space-y-6">
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                <div class="p-5">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Parents Information</h3>

                                    @if($student->father_name)
                                    <div class="border border-gray-100 rounded-lg p-4 mb-4">
                                        <div class="flex items-start">
                                            @if($student->father_profile_image)
                                            <img src="{{ asset('storage/' . $student->father_profile_image) }}" alt="Father" class="w-16 h-16 rounded-lg object-cover">
                                            @else
                                            <div class="w-16 h-16 rounded-lg bg-blue-100 flex items-center justify-center text-blue-500 text-xl font-bold">
                                                {{ $student->father_name ? strtoupper(substr($student->father_name, 0, 1)) : 'F' }}
                                            </div>
                                            @endif
                                            <div class="flex-grow ml-4">
                                                <div class="flex justify-between items-center">
                                                    <div>
                                                        <h4 class="font-semibold text-lg text-gray-800">{{ $student->father_name }}</h4>
                                                        <p class="text-blue-600">Father</p>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-10 flex-grow mx-8">
                                                        @if($student->father_phone_number)
                                                        <div>
                                                            <p class="text-gray-600 text-sm">Phone</p>
                                                            <p class="text-gray-800">{{ $student->father_phone_number }}</p>
                                                        </div>
                                                        @endif
                                                        @if($student->father_email)
                                                        <div>
                                                            <p class="text-gray-600 text-sm">Email</p>
                                                            <p class="text-gray-800">{{ $student->father_email }}</p>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    <button class="bg-gray-800 h-10 w-10 text-white p-2 rounded-lg">
                                                        <svg fill="#feffff" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Change_password"> <path d="M464.4326,147.54a9.8985,9.8985,0,0,0-17.56,9.1406,214.2638,214.2638,0,0,1-38.7686,251.42c-83.8564,83.8476-220.3154,83.874-304.207-.0088a9.8957,9.8957,0,0,0-16.8926,7.0049v56.9a9.8965,9.8965,0,0,0,19.793,0v-34.55A234.9509,234.9509,0,0,0,464.4326,147.54Z"></path> <path d="M103.8965,103.9022c83.8828-83.874,220.3418-83.8652,304.207-.0088a9.8906,9.8906,0,0,0,16.8926-6.9961v-56.9a9.8965,9.8965,0,0,0-19.793,0v34.55C313.0234-1.3556,176.0547,3.7509,89.9043,89.9012A233.9561,233.9561,0,0,0,47.5674,364.454a9.8985,9.8985,0,0,0,17.56-9.1406A214.2485,214.2485,0,0,1,103.8965,103.9022Z"></path> <path d="M126.4009,254.5555v109.44a27.08,27.08,0,0,0,27,27H358.5991a27.077,27.077,0,0,0,27-27v-109.44a27.0777,27.0777,0,0,0-27-27H153.4009A27.0805,27.0805,0,0,0,126.4009,254.5555ZM328,288.13a21.1465,21.1465,0,1,1-21.1465,21.1464A21.1667,21.1667,0,0,1,328,288.13Zm-72,0a21.1465,21.1465,0,1,1-21.1465,21.1464A21.1667,21.1667,0,0,1,256,288.13Zm-72,0a21.1465,21.1465,0,1,1-21.1465,21.1464A21.1667,21.1667,0,0,1,184,288.13Z"></path> <path d="M343.6533,207.756V171.7538a87.6533,87.6533,0,0,0-175.3066,0V207.756H188.14V171.7538a67.86,67.86,0,0,1,135.7208,0V207.756Z"></path> </g> </g></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($student->mother_name)
                                    <div class="border border-gray-100 rounded-lg p-4 mb-4">
                                        <div class="flex items-start">
                                            @if($student->mother_profile_image)
                                            <img src="{{ asset('storage/' . $student->mother_profile_image) }}" alt="Mother" class="w-16 h-16 rounded-lg object-cover">
                                            @else
                                            <div class="w-16 h-16 rounded-lg bg-pink-100 flex items-center justify-center text-pink-500 text-xl font-bold">
                                                {{ $student->mother_name ? strtoupper(substr($student->mother_name, 0, 1)) : 'M' }}
                                            </div>
                                            @endif
                                            <div class="flex-grow ml-4">
                                                <div class="flex justify-between items-center">
                                                    <div>
                                                        <h4 class="font-semibold text-lg text-gray-800">{{ $student->mother_name }}</h4>
                                                        <p class="text-blue-600">Mother</p>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-10 flex-grow mx-8">
                                                        @if($student->mother_phone_number)
                                                        <div>
                                                            <p class="text-gray-600 text-sm">Phone</p>
                                                            <p class="text-gray-800">{{ $student->mother_phone_number }}</p>
                                                        </div>
                                                        @endif
                                                        @if($student->mother_email)
                                                        <div>
                                                            <p class="text-gray-600 text-sm">Email</p>
                                                            <p class="text-gray-800">{{ $student->mother_email }}</p>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <button class="bg-gray-800 h-10 w-10 text-white p-2 rounded-lg">
                                                        <svg fill="#feffff" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Change_password"> <path d="M464.4326,147.54a9.8985,9.8985,0,0,0-17.56,9.1406,214.2638,214.2638,0,0,1-38.7686,251.42c-83.8564,83.8476-220.3154,83.874-304.207-.0088a9.8957,9.8957,0,0,0-16.8926,7.0049v56.9a9.8965,9.8965,0,0,0,19.793,0v-34.55A234.9509,234.9509,0,0,0,464.4326,147.54Z"></path> <path d="M103.8965,103.9022c83.8828-83.874,220.3418-83.8652,304.207-.0088a9.8906,9.8906,0,0,0,16.8926-6.9961v-56.9a9.8965,9.8965,0,0,0-19.793,0v34.55C313.0234-1.3556,176.0547,3.7509,89.9043,89.9012A233.9561,233.9561,0,0,0,47.5674,364.454a9.8985,9.8985,0,0,0,17.56-9.1406A214.2485,214.2485,0,0,1,103.8965,103.9022Z"></path> <path d="M126.4009,254.5555v109.44a27.08,27.08,0,0,0,27,27H358.5991a27.077,27.077,0,0,0,27-27v-109.44a27.0777,27.0777,0,0,0-27-27H153.4009A27.0805,27.0805,0,0,0,126.4009,254.5555ZM328,288.13a21.1465,21.1465,0,1,1-21.1465,21.1464A21.1667,21.1667,0,0,1,328,288.13Zm-72,0a21.1465,21.1465,0,1,1-21.1465,21.1464A21.1667,21.1667,0,0,1,256,288.13Zm-72,0a21.1465,21.1465,0,1,1-21.1465,21.1464A21.1667,21.1667,0,0,1,184,288.13Z"></path> <path d="M343.6533,207.756V171.7538a87.6533,87.6533,0,0,0-175.3066,0V207.756H188.14V171.7538a67.86,67.86,0,0,1,135.7208,0V207.756Z"></path> </g> </g></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($student->guardian_name)
                                    <div class="border border-gray-100 rounded-lg p-4">
                                        <div class="flex items-start">
                                            @if($student->guardian_profile_image)
                                            <img src="{{ asset('storage/' . $student->guardian_profile_image) }}" alt="Guardian" class="w-16 h-16 rounded-lg object-cover">
                                            @else
                                            <div class="w-16 h-16 rounded-lg bg-purple-100 flex items-center justify-center text-purple-500 text-xl font-bold">
                                                {{ $student->guardian_name ? strtoupper(substr($student->guardian_name, 0, 1)) : 'G' }}
                                            </div>
                                            @endif
                                            <div class="flex-grow ml-4">
                                                <div class="flex justify-between items-center">
                                                    <div>
                                                        <h4 class="font-semibold text-lg text-gray-800">{{ $student->guardian_name }}</h4>
                                                        <p class="text-blue-600">Guardian ({{ $student->guardian_relation ?? 'Not specified' }})</p>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-10 flex-grow mx-8">
                                                        @if($student->guardian_phone_number)
                                                        <div>
                                                            <p class="text-gray-600 text-sm">Phone</p>
                                                            <p class="text-gray-800">{{ $student->guardian_phone_number }}</p>
                                                        </div>
                                                        @endif
                                                        @if($student->guardian_email)
                                                        <div>
                                                            <p class="text-gray-600 text-sm">Email</p>
                                                            <p class="text-gray-800">{{ $student->guardian_email }}</p>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <button class="bg-gray-800 h-10 w-10 text-white p-2 rounded-lg">
                                                        <svg fill="#feffff" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Change_password"> <path d="M464.4326,147.54a9.8985,9.8985,0,0,0-17.56,9.1406,214.2638,214.2638,0,0,1-38.7686,251.42c-83.8564,83.8476-220.3154,83.874-304.207-.0088a9.8957,9.8957,0,0,0-16.8926,7.0049v56.9a9.8965,9.8965,0,0,0,19.793,0v-34.55A234.9509,234.9509,0,0,0,464.4326,147.54Z"></path> <path d="M103.8965,103.9022c83.8828-83.874,220.3418-83.8652,304.207-.0088a9.8906,9.8906,0,0,0,16.8926-6.9961v-56.9a9.8965,9.8965,0,0,0-19.793,0v34.55C313.0234-1.3556,176.0547,3.7509,89.9043,89.9012A233.9561,233.9561,0,0,0,47.5674,364.454a9.8985,9.8985,0,0,0,17.56-9.1406A214.2485,214.2485,0,0,1,103.8965,103.9022Z"></path> <path d="M126.4009,254.5555v109.44a27.08,27.08,0,0,0,27,27H358.5991a27.077,27.077,0,0,0,27-27v-109.44a27.0777,27.0777,0,0,0-27-27H153.4009A27.0805,27.0805,0,0,0,126.4009,254.5555ZM328,288.13a21.1465,21.1465,0,1,1-21.1465,21.1464A21.1667,21.1667,0,0,1,328,288.13Zm-72,0a21.1465,21.1465,0,1,1-21.1465,21.1464A21.1667,21.1667,0,0,1,256,288.13Zm-72,0a21.1465,21.1465,0,1,1-21.1465,21.1464A21.1667,21.1667,0,0,1,184,288.13Z"></path> <path d="M343.6533,207.756V171.7538a87.6533,87.6533,0,0,0-175.3066,0V207.756H188.14V171.7538a67.86,67.86,0,0,1,135.7208,0V207.756Z"></path> </g> </g></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if(!$student->father_name && !$student->mother_name && !$student->guardian_name)
                                    <div class="flex justify-center items-center p-8 text-gray-500">
                                        <p>No parent or guardian information available</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                    <div class="p-5">
                                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Documents</h3>

                                        @if($student->medical_condition_document)
                                        <div class="flex justify-between items-center mb-5 px-4 py-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center">
                                                <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded mr-3">PDF</span>
                                                <span class="text-gray-800">Medical Certificate.pdf</span>
                                            </div>
                                            <a href="{{ route('school.peoples.students.document', ['id' => $student->admission_number, 'document_type' => 'medical']) }}" class="bg-gray-800 h-8 w-8 text-white p-2 rounded-lg">
                                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path opacity="0.5" d="M3 15C3 17.8284 3 19.2426 3.87868 20.1213C4.75736 21 6.17157 21 9 21H15C17.8284 21 19.2426 21 20.1213 20.1213C21 19.2426 21 17.8284 21 15" stroke="#feffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 3V16M12 16L16 11.625M12 16L8 11.625" stroke="#feffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                            </a>
                                        </div>
                                        @else
                                        <div class="flex justify-between items-center mb-5 px-4 py-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center">
                                                <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded mr-3">PDF</span>
                                                <span class="text-gray-800">Medical Certificate.pdf</span>
                                            </div>
                                            <span class="text-xs text-gray-500">Not available</span>
                                        </div>
                                        @endif

                                        @if($student->transfer_certificate_document)
                                        <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center">
                                                <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded mr-3">PDF</span>
                                                <span class="text-gray-800">Transfer Certificate.pdf</span>
                                            </div>
                                            <a href="{{ route('school.peoples.students.document', ['id' => $student->admission_number, 'document_type' => 'transfer']) }}" class="bg-gray-800 h-8 w-8 text-white p-2 rounded-lg">
                                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path opacity="0.5" d="M3 15C3 17.8284 3 19.2426 3.87868 20.1213C4.75736 21 6.17157 21 9 21H15C17.8284 21 19.2426 21 20.1213 20.1213C21 19.2426 21 17.8284 21 15" stroke="#feffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 3V16M12 16L16 11.625M12 16L8 11.625" stroke="#feffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                            </a>
                                        </div>
                                        @else
                                        <div class="flex justify-between items-center px-4 py-3 bg-gray-50 rounded-lg">
                                            <div class="flex items-center">
                                                <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded mr-3">PDF</span>
                                                <span class="text-gray-800">Transfer Certificate.pdf</span>
                                            </div>
                                            <span class="text-xs text-gray-500">Not available</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                    <div class="p-5">
                                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Address</h3>

                                        @if($student->current_address)
                                        <div class="mb-5">
                                            <div class="flex items-center mb-2">
                                                <div class="w-8 h-8 bg-gray-100 rounded-xl p-2 flex items-center justify-center text-gray-500 mr-2">
                                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 21C15.5 17.4 19 14.1764 19 10.2C19 6.22355 15.866 3 12 3C8.13401 3 5 6.22355 5 10.2C5 14.1764 8.5 17.4 12 21Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 12C13.1046 12 14 11.1046 14 10C14 8.89543 13.1046 8 12 8C10.8954 8 10 8.89543 10 10C10 11.1046 10.8954 12 12 12Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                                </div>
                                                <h4 class="font-medium text-gray-700">Current Address</h4>
                                            </div>
                                            <p class="text-gray-700 ml-10">{{ $student->current_address }}</p>
                                        </div>
                                        @endif

                                        @if($student->permanent_address)
                                        <div>
                                            <div class="flex items-center mb-2">
                                                <div class="w-8 h-8 bg-gray-100 rounded-xl p-2 flex items-center justify-center text-gray-500 mr-2">
                                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 21C15.5 17.4 19 14.1764 19 10.2C19 6.22355 15.866 3 12 3C8.13401 3 5 6.22355 5 10.2C5 14.1764 8.5 17.4 12 21Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 12C13.1046 12 14 11.1046 14 10C14 8.89543 13.1046 8 12 8C10.8954 8 10 8.89543 10 10C10 11.1046 10.8954 12 12 12Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                                </div>
                                                <h4 class="font-medium text-gray-700">Permanent Address</h4>
                                            </div>
                                            <p class="text-gray-700 ml-10">{{ $student->permanent_address }}</p>
                                        </div>
                                        @endif
                                        
                                        @if(!$student->current_address && !$student->permanent_address)
                                        <div class="flex justify-center items-center p-4 text-gray-500">
                                            <p>No address information available</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                <div class="p-5">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Previous School Details</h3>
                                    @if($student->previous_school_name || $student->previous_school_address)
                                    <div class="grid grid-cols-2 gap-6">
                                        @if($student->previous_school_name)
                                        <div>
                                            <p class="text-gray-600 text-sm">Previous School Name</p>
                                            <p class="text-gray-800 font-medium">{{ $student->previous_school_name }}</p>
                                        </div>
                                        @endif
                                        @if($student->previous_school_address)
                                        <div>
                                            <p class="text-gray-600 text-sm">School Address</p>
                                            <p class="text-gray-800 font-medium">{{ $student->previous_school_address }}</p>
                                        </div>
                                        @endif
                                    </div>
                                    @else
                                    <div class="flex justify-center items-center p-4 text-gray-500">
                                        <p>No previous school information available</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                    <div class="p-5">
                                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Bank Details</h3>
                                        @if($student->bank_name || $student->branch || $student->ifsc_number)
                                        <table class="w-full">
                                            <tbody>
                                            @if($student->bank_name)
                                            <tr>
                                                <td class="py-2 text-gray-600">Bank Name</td>
                                                <td class="py-2 text-right text-gray-800 font-medium">{{ $student->bank_name }}</td>
                                            </tr>
                                            @endif
                                            @if($student->branch)
                                            <tr>
                                                <td class="py-2 text-gray-600">Branch</td>
                                                <td class="py-2 text-right text-gray-800 font-medium">{{ $student->branch }}</td>
                                            </tr>
                                            @endif
                                            @if($student->ifsc_number)
                                            <tr>
                                                <td class="py-2 text-gray-600">IFSC</td>
                                                <td class="py-2 text-right text-gray-800 font-medium">{{ $student->ifsc_number }}</td>
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
                                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Medical History</h3>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-gray-600 mb-1">Known Allergies</p>
                                                @if($student->allergies && count($student->allergies) > 0)
                                                    @foreach($student->allergies as $allergy)
                                                    <span class="inline-block bg-gray-100 text-gray-800 text-sm px-3 py-1 rounded">{{ $allergy }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-gray-800">None reported</span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-gray-600 mb-1">Medications</p>
                                                @if($student->medications && count($student->medications) > 0)
                                                    @foreach($student->medications as $medication)
                                                    <span class="inline-block bg-gray-100 text-gray-800 text-sm px-3 py-1 rounded">{{ $medication }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-gray-800">None reported</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                <div class="p-5">
                                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Other Info</h3>
                                    @if($student->other_information)
                                        <p class="text-gray-700 text-sm">{{ $student->other_information }}</p>
                                    @else
                                        <p class="text-gray-700 text-sm">No additional information available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div x-show="activeTab === 'timetable'" class="bg-white rounded-lg shadow-sm p-6" x-cloak>
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Student Time Table</h2>
                                @if($student->class && $student->section)
                                <div class="text-gray-600">
                                    <span class="font-medium">{{ $student->class->name }}</span>
                                    <span>-</span>
                                    <span class="font-medium">{{ $student->section->name }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="overflow-x-auto">
                                @if(empty($timetableData))
                                    <div class="text-center text-gray-500 my-8">
                                        No timetable entries found for this student's class.
                                    </div>
                                @else
                                    @php
                                        // Group by time slots
                                        $timeSlotMap = [];
                                        foreach ($timetableData as $period) {
                                            $timeKey = $period['start_time'] . ' - ' . $period['end_time'];
                                            
                                            if (!isset($timeSlotMap[$timeKey])) {
                                                $timeSlotMap[$timeKey] = [
                                                    'timeSlot' => $timeKey,
                                                    'days' => [
                                                        'Monday' => null,
                                                        'Tuesday' => null,
                                                        'Wednesday' => null,
                                                        'Thursday' => null,
                                                        'Friday' => null,
                                                        'Saturday' => null,
                                                    ]
                                                ];
                                            }
                                            
                                            if ($period['period_type'] === 'regular') {
                                                $timeSlotMap[$timeKey]['days'][$period['day']] = [
                                                    'id' => $period['id'],
                                                    'subject_id' => $period['subject'],
                                                    'subject_name' => $period['subject_name'] ?? 'Unknown Subject',
                                                    'teacher_id' => $period['teacher'],
                                                    'teacher_name' => $period['teacher_name'] ?? 'Unknown Teacher',
                                                    'is_extra' => false
                                                ];
                                            } else {
                                                $timeSlotMap[$timeKey]['days'][$period['day']] = [
                                                    'id' => $period['id'],
                                                    'name' => $period['name'] ?? 'Break',
                                                    'is_extra' => true
                                                ];
                                            }
                                        }
                                        
                                        // Sort by time
                                        uksort($timeSlotMap, function($a, $b) {
                                            $aTime = explode(' - ', $a)[0];
                                            $bTime = explode(' - ', $b)[0];
                                            return strcmp($aTime, $bTime);
                                        });
                                        
                                        // Helper function to get background color
                                        function getSubjectBackgroundColor($subject) {
                                            if (!$subject) return 'bg-gray-100';
                                            
                                            if ($subject === 'Break' || $subject === 'Lunch') return 'bg-orange-100';
                                            if (str_contains($subject, 'Math')) return 'bg-blue-100';
                                            if (str_contains($subject, 'Computer')) return 'bg-green-100';
                                            if (str_contains($subject, 'Physics')) return 'bg-yellow-100';
                                            if (str_contains($subject, 'English')) return 'bg-purple-100';
                                            if (str_contains($subject, 'Spanish')) return 'bg-pink-100';
                                            if (str_contains($subject, 'Chemistry')) return 'bg-red-100';
                                            
                                            return 'bg-gray-100';
                                        }
                                    @endphp
                                    
                                    <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                            <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                                                <th class="px-4 py-2 font-semibold">Time</th>
                                                <th class="px-4 py-2 font-semibold">Monday</th>
                                                <th class="px-4 py-2 font-semibold">Tuesday</th>
                                                <th class="px-4 py-2 font-semibold">Wednesday</th>
                                                <th class="px-4 py-2 font-semibold">Thursday</th>
                                                <th class="px-4 py-2 font-semibold">Friday</th>
                                                <th class="px-4 py-2 font-semibold">Saturday</th>
                                        </tr>
                                    </thead>
                                        <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                                            @foreach($timeSlotMap as $timeKey => $slot)
                                                <tr>
                                                    <td class="px-4 py-2 whitespace-nowrap font-medium text-gray-900">{{ $slot['timeSlot'] }}</td>
                                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                                        <td class="px-4 py-2">
                                                            @if(isset($slot['days'][$day]) && $slot['days'][$day])
                                                                @php
                                                                    $periodData = $slot['days'][$day];
                                                                    $bgClass = getSubjectBackgroundColor($periodData['is_extra'] ? $periodData['name'] : $periodData['subject_name']);
                                                                @endphp
                                                                <div class="p-2 rounded-md {{ $bgClass }}">
                                                                    <p class="text-xs text-gray-700 font-semibold mb-1">{{ explode(' - ', $slot['timeSlot'])[0] }}</p>
                                                                    @if($periodData['is_extra'])
                                                                        <p class="text-sm font-medium text-gray-900">{{ $periodData['name'] }}</p>
                                                                    @else
                                                                        <div>
                                                                            <p class="text-sm font-medium text-gray-900">{{ $periodData['subject_name'] }}</p>
                                                                            <p class="text-xs text-gray-600">{{ $periodData['teacher_name'] }}</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <div class="p-2 text-center text-gray-400 text-xs">
                                                                    No Class
                                                                </div>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                        </tr>
                                            @endforeach
                                        </tbody>
                                </table>
                                @endif
                            </div>
                        </div>

                        <div x-show="activeTab === 'leaveAttendance'" class="bg-white rounded-lg shadow-sm p-6" x-cloak>
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Student Leave & Attendance</h2>
                            <p class="text-gray-600">This section will show leave records and attendance details.</p>
                            {{-- Add your actual Leave & Attendance content here --}}
                            <div class="mt-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Attendance Summary</h3>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="bg-blue-50 p-4 rounded-lg">
                                        <p class="text-sm text-gray-600">Total Present</p>
                                        <p class="text-2xl font-bold text-blue-800">180 days</p>
                                    </div>
                                    <div class="bg-red-50 p-4 rounded-lg">
                                        <p class="text-sm text-gray-600">Total Absent</p>
                                        <p class="text-2xl font-bold text-red-800">5 days</p>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Recent Leave Requests</h3>
                                <ul class="list-disc list-inside text-gray-700">
                                    <li>Medical Leave: 2025-03-10 to 2025-03-12 (Approved)</li>
                                    <li>Family Event: 2025-04-01 (Pending)</li>
                                </ul>
                            </div>
                        </div>

              {{-- <div x-show="activeTab === 'fees'" class="bg-white rounded-lg shadow-sm p-6" x-cloak>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Student Fees Details</h2>
        <div class="text-right">
            <p class="text-sm text-gray-600">Total Fees: <span class="font-semibold text-gray-800">₹{{ number_format($totalFees, 2) }}</span></p>
            <p class="text-sm text-gray-600">Total Paid: <span class="font-semibold text-green-600">₹{{ number_format($totalPaid, 2) }}</span></p>
            <p class="text-sm text-gray-600">Total Pending: <span class="font-semibold text-red-600">₹{{ number_format($totalPending, 2) }}</span></p>
        </div>
    </div> --}}

<div x-show="activeTab === 'fees'" class="bg-white rounded-lg shadow-sm p-6" x-cloak>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Student Fees Details</h2>
        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-sm text-gray-600">Total Fees: <span class="font-semibold text-gray-800">₹{{ number_format($totalFees, 2) }}</span></p>
                <p class="text-sm text-gray-600">Total Paid: <span class="font-semibold text-green-600">₹{{ number_format($totalPaid, 2) }}</span></p>
                <p class="text-sm text-gray-600">Total Pending: <span class="font-semibold text-red-600">₹{{ number_format($totalPending, 2) }}</span></p>
            </div>
            @if(!$studentFees->isEmpty())
            <a href="{{ route('school.student.feesPdf', $student->id) }}" 
               target="_blank"
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download PDF
            </a>
            @endif
        </div>
    </div>

    @if($studentFees->isEmpty())
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="mt-4 text-gray-600">No fees assigned to this student yet.</p>
        </div>
    @else
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Fee Overview</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">S.No</th>
                            <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Fee Group</th>
                            <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Fee Type</th>
                            <th class="py-3 px-4 border-b text-right text-sm font-semibold text-gray-700">Amount</th>
                            <th class="py-3 px-4 border-b text-right text-sm font-semibold text-gray-700">Paid</th>
                            <th class="py-3 px-4 border-b text-right text-sm font-semibold text-gray-700">Balance</th>
                            <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Due Date</th>
                            <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Status</th>
                            <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($studentFees as $index => $fee)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $fee['fee_group'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $fee['fee_type'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700 text-right font-medium">₹{{ number_format($fee['amount'], 2) }}</td>
                            <td class="py-3 px-4 text-sm text-green-600 text-right font-medium">₹{{ number_format($fee['paid_amount'], 2) }}</td>
                            <td class="py-3 px-4 text-sm text-red-600 text-right font-medium">₹{{ number_format($fee['balance'], 2) }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700 text-center">
                                @if($fee['due_date'])
                                    {{ \Carbon\Carbon::parse($fee['due_date'])->format('d M Y') }}
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @php
                                    $statusColors = [
                                        'paid' => 'text-green-700 bg-green-100 border-green-200',
                                        'pending' => 'text-orange-700 bg-orange-100 border-orange-200',
                                        'unpaid' => 'text-red-700 bg-red-100 border-red-200'
                                    ];
                                    $statusClass = $statusColors[$fee['status']] ?? 'text-gray-700 bg-gray-100 border-gray-200';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                    {{ ucfirst($fee['status']) }}
                                </span>
                                
                                @if($fee['payment_count'] > 0)
                                    <p class="text-xs text-gray-500 mt-1">{{ $fee['payment_count'] }} payment(s)</p>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($fee['balance'] > 0)
                                    <button 
                                        onclick="openPaymentModal({{ $fee['id'] }}, '{{ $fee['fee_type'] }}', {{ $fee['balance'] }})" 
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Pay Now
                                    </button>
                                @else
                                    <span class="text-green-600 text-sm font-medium">✓ Paid</span>
                                @endif
                                
                                @if($fee['payment_count'] > 0)
                                    {{-- <button 
                                        onclick="viewPaymentHistory({{ $fee['id'] }})" 
                                        class="block text-xs text-gray-600 hover:text-gray-800 mt-1">
                                        View History
                                    </button> --}}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                        <tr class="font-semibold">
                            <td colspan="3" class="py-3 px-4 text-right text-gray-800">Total:</td>
                            <td class="py-3 px-4 text-right text-gray-800">₹{{ number_format($totalFees, 2) }}</td>
                            <td class="py-3 px-4 text-right text-green-600">₹{{ number_format($totalPaid, 2) }}</td>
                            <td class="py-3 px-4 text-right text-red-600">₹{{ number_format($totalPending, 2) }}</td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($totalPending > 0)
            <div class="mt-6 flex justify-between items-center bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div>
                    <p class="text-sm font-medium text-blue-800">Outstanding Balance</p>
                    <p class="text-2xl font-bold text-blue-900">₹{{ number_format($totalPending, 2) }}</p>
                </div>
                <a href="{{ route('school.collectFee') }}" class="bg-green-600 hover:bg-green-700 text-white py-2 px-6 rounded-lg transition-colors font-medium">
                    Make Payment
                </a>
            </div>
            @endif

            {{-- Payment History Section --}}
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Payment History</h3>
                <div class="space-y-3">
                    @php
                        $recentPayments = $studentFees->flatMap(function($fee) {
                            return collect($fee['payments'])->map(function($payment) use ($fee) {
                                return array_merge($payment->toArray(), ['fee_type' => $fee['fee_type']]);
                            });
                        })->sortByDesc('collection_date')->take(5);
                    @endphp

                    @if($recentPayments->isEmpty())
                        <p class="text-gray-500 text-sm">No payment history available.</p>
                    @else
                        @foreach($recentPayments as $payment)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $payment['fee_type'] }}</p>
                                    <p class="text-sm text-gray-600">
                                        Payment Date: {{ \Carbon\Carbon::parse($payment['collection_date'])->format('d M Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Method: <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $payment['payment_type'])) }}</span>
                                        @if($payment['payment_reference_no'])
                                            | Ref: {{ $payment['payment_reference_no'] }}
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-green-600">₹{{ number_format($payment['paid_amount'], 2) }}</p>
                                    @if($payment['balance'] > 0)
                                        <p class="text-xs text-orange-600">Balance: ₹{{ number_format($payment['balance'], 2) }}</p>
                                    @endif
                                </div>
                            </div>
                            @if($payment['note'])
                                <p class="mt-2 text-sm text-gray-600 italic">Note: {{ $payment['note'] }}</p>
                            @endif
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<script>
// ============= FEE PAYMENT FUNCTIONS =============

function openPaymentModal(assignFeeId, feeType, balance) {
    window.location.href = `{{ route('school.collectFee') }}?assign_fee_id=${assignFeeId}&amount=${balance}`;
}

function viewPaymentHistory(assignFeeId) {
    const feeData = @json($studentFees);
    const fee = feeData.find(f => f.id === assignFeeId);
    
    if (!fee || !fee.payments || fee.payments.length === 0) {
        alert('No payment history found for this fee.');
        return;
    }
    
    let historyHtml = `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" id="paymentHistoryModal">
            <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 m-4">
                <div class="flex justify-between items-center mb-4 border-b pb-3">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Payment History</h2>
                        <p class="text-sm text-gray-600">${fee.fee_type} - ${fee.fee_group}</p>
                    </div>
                    <button onclick="closePaymentHistoryModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-gray-600">Total Amount</p>
                            <p class="text-lg font-bold text-gray-800">₹${fee.amount.toFixed(2)}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Total Paid</p>
                            <p class="text-lg font-bold text-green-600">₹${fee.paid_amount.toFixed(2)}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Balance</p>
                            <p class="text-lg font-bold text-red-600">₹${fee.balance.toFixed(2)}</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <h3 class="font-semibold text-gray-700 mb-2">Transaction History</h3>
    `;
    
    fee.payments.forEach((payment, index) => {
        const paymentDate = new Date(payment.collection_date).toLocaleDateString('en-IN', { 
            day: 'numeric', 
            month: 'short', 
            year: 'numeric' 
        });
        
        const paymentMethod = payment.payment_type ? payment.payment_type.replace('_', ' ').toUpperCase() : 'N/A';
        
        historyHtml += `
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">
                                Payment #${index + 1}
                            </span>
                            <span class="text-sm text-gray-600">${paymentDate}</span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-600">Payment Method:</span>
                                <span class="font-medium text-gray-800 ml-1">${paymentMethod}</span>
                            </div>
                            ${payment.payment_reference_no ? `
                            <div>
                                <span class="text-gray-600">Reference No:</span>
                                <span class="font-medium text-gray-800 ml-1">${payment.payment_reference_no}</span>
                            </div>
                            ` : ''}
                        </div>
                        
                        ${payment.note ? `
                        <div class="mt-2 text-sm">
                            <span class="text-gray-600">Note:</span>
                            <span class="text-gray-700 italic ml-1">${payment.note}</span>
                        </div>
                        ` : ''}
                    </div>
                    
                    <div class="text-right ml-4">
                        <p class="text-lg font-bold text-green-600">₹${parseFloat(payment.paid_amount).toFixed(2)}</p>
                        ${payment.balance > 0 ? `
                        <p class="text-xs text-orange-600">Remaining: ₹${parseFloat(payment.balance).toFixed(2)}</p>
                        ` : `
                        <p class="text-xs text-green-600">✓ Fully Paid</p>
                        `}
                    </div>
                </div>
            </div>
        `;
    });
    
    historyHtml += `
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button onclick="closePaymentHistoryModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', historyHtml);
}

function closePaymentHistoryModal() {
    const modal = document.getElementById('paymentHistoryModal');
    if (modal) {
        modal.remove();
    }
}

document.addEventListener('click', function(event) {
    const modal = document.getElementById('paymentHistoryModal');
    if (modal && event.target === modal) {
        closePaymentHistoryModal();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closePaymentHistoryModal();
    }
});
</script>



                        <div x-show="activeTab === 'examResults'" class="bg-white rounded-lg shadow-sm p-6" x-cloak>
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Student Exam & Results</h2>
                            <p class="text-gray-600">This tab displays exam scores and results.</p>
                            {{-- Add your actual Exam & Results content here --}}
                            <div class="mt-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">Latest Exam Results</h3>
                                <table class="min-w-full bg-white border border-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="py-2 px-4 border-b text-left">Subject</th>
                                            <th class="py-2 px-4 border-b text-right">Score</th>
                                            <th class="py-2 px-4 border-b text-center">Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="py-2 px-4 border-b">Mathematics</td>
                                            <td class="py-2 px-4 border-b text-right">92/100</td>
                                            <td class="py-2 px-4 border-b text-center">A</td>
                                        </tr>
                                        <tr>
                                            <td class="py-2 px-4 border-b">Science</td>
                                            <td class="py-2 px-4 border-b text-right">88/100</td>
                                            <td class="py-2 px-4 border-b text-center">B+</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button class="mt-4 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg">View All Results</button>
                            </div>
                        </div>

                        <div x-show="activeTab === 'library'" class="bg-white rounded-lg shadow-sm p-6" x-cloak>
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold text-gray-800">Library</h2>
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-600 text-sm">This Year</span>
                                    <i class="fas fa-cog text-gray-500"></i>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($issuedBooks as $book)
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                                    <div class="p-4">
                                        <img src="{{asset('storage/'.$book->image_path)}}" alt="Book Cover" class="w-24 h-32 rounded-lg object-cover mx-auto mb-4">
                                        <h3 class="text-lg font-semibold text-gray-800 text-center">{{$book->book_name}}</h3>
                                        <div class="flex justify-between text-sm text-gray-600 mt-2">
                                            <span>Book taken on</span>
                                            <span>{{$book->issue_date}}</span>
                                        </div>
                                        <div class="flex justify-between text-sm text-gray-600 mt-1">
                                            <span>Last Date</span>
                                            <span>{{$book->due_date}}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div> {{-- End of x-data div --}}
        </div>
    </div>
</div>
@include('client.schoolPanel.layout.footer')

@push('scripts')
<script>
// ============= FEE PAYMENT FUNCTIONS =============

function openPaymentModal(assignFeeId, feeType, balance) {
    window.location.href = `{{ route('school.collectFee') }}?assign_fee_id=${assignFeeId}&amount=${balance}`;
}

function viewPaymentHistory(assignFeeId) {
    const feeData = @json($studentFees);
    const fee = feeData.find(f => f.id === assignFeeId);
    
    if (!fee || !fee.payments || fee.payments.length === 0) {
        alert('No payment history found for this fee.');
        return;
    }
    
    let historyHtml = `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" id="paymentHistoryModal">
            <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 m-4">
                <div class="flex justify-between items-center mb-4 border-b pb-3">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Payment History</h2>
                        <p class="text-sm text-gray-600">${fee.fee_type} - ${fee.fee_group}</p>
                    </div>
                    <button onclick="closePaymentHistoryModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-gray-600">Total Amount</p>
                            <p class="text-lg font-bold text-gray-800">₹${fee.amount.toFixed(2)}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Total Paid</p>
                            <p class="text-lg font-bold text-green-600">₹${fee.paid_amount.toFixed(2)}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Balance</p>
                            <p class="text-lg font-bold text-red-600">₹${fee.balance.toFixed(2)}</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <h3 class="font-semibold text-gray-700 mb-2">Transaction History</h3>
    `;
    
    fee.payments.forEach((payment, index) => {
        const paymentDate = new Date(payment.collection_date).toLocaleDateString('en-IN', { 
            day: 'numeric', 
            month: 'short', 
            year: 'numeric' 
        });
        
        const paymentMethod = payment.payment_type ? payment.payment_type.replace('_', ' ').toUpperCase() : 'N/A';
        
        historyHtml += `
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">
                                Payment #${index + 1}
                            </span>
                            <span class="text-sm text-gray-600">${paymentDate}</span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-600">Payment Method:</span>
                                <span class="font-medium text-gray-800 ml-1">${paymentMethod}</span>
                            </div>
                            ${payment.payment_reference_no ? `
                            <div>
                                <span class="text-gray-600">Reference No:</span>
                                <span class="font-medium text-gray-800 ml-1">${payment.payment_reference_no}</span>
                            </div>
                            ` : ''}
                        </div>
                        
                        ${payment.note ? `
                        <div class="mt-2 text-sm">
                            <span class="text-gray-600">Note:</span>
                            <span class="text-gray-700 italic ml-1">${payment.note}</span>
                        </div>
                        ` : ''}
                    </div>
                    
                    <div class="text-right ml-4">
                        <p class="text-lg font-bold text-green-600">₹${parseFloat(payment.paid_amount).toFixed(2)}</p>
                        ${payment.balance > 0 ? `
                        <p class="text-xs text-orange-600">Remaining: ₹${parseFloat(payment.balance).toFixed(2)}</p>
                        ` : `
                        <p class="text-xs text-green-600">✓ Fully Paid</p>
                        `}
                    </div>
                </div>
            </div>
        `;
    });
    
    historyHtml += `
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button onclick="closePaymentHistoryModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', historyHtml);
}

function closePaymentHistoryModal() {
    const modal = document.getElementById('paymentHistoryModal');
    if (modal) {
        modal.remove();
    }
}

document.addEventListener('click', function(event) {
    const modal = document.getElementById('paymentHistoryModal');
    if (modal && event.target === modal) {
        closePaymentHistoryModal();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closePaymentHistoryModal();
    }
});
</script>
@endpush