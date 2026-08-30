@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')
<link href="https://unpkg.com/flowbite@latest/dist/flowbite.min.css" rel="stylesheet" />

<script src="https://unpkg.com/flowbite@latest/dist/flowbite.min.js"></script>
<style>

</style>
<div class="flex">
    @include('client.schoolPanel.layout.sidebar')
    <!-- Main Content -->
    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between p-4 bg-gray-50">
            <div>
<h1 class="text-2xl font-bold text-gray-900">
    {{ $schoolName }} 
</h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <span class="text-gray-400">Dashboard /</span>
                    <span class="text-gray-700 font-medium">Admin Dashboard</span>
                </nav>
            </div>
            <div class="flex space-x-2">
                <button class="flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New Student
                </button>
                <button class="px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-md hover:bg-gray-200 transition">
                    Fees Details
                </button>
            </div>
        </div>

        <!-- Error Messages -->
        @if(session('error'))
            <div class="mx-4 mt-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                <p>{{ session('error') }}</p>
            </div>
        @endif
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4">
            <div class="bg-white rounded-xl shadow p-4 flex flex-col justify-between hover:-translate-y-1 transition duration-300 ease-in-out">
                <div class="flex justify-between items-start">
                    <div class="flex items-center space-x-4">
                            <i class="fas fa-user-graduate text-blue-500 text-2xl"></i>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $totalStudents }}</h2>
                            <p class="text-sm text-gray-500">Total Students</p>
                        </div>
                    </div>
                    <span class="bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded">
                        {{ $studentChange }}%
                    </span>
                </div>
                <hr class="my-4" />
                <div class="flex justify-between text-sm font-semibold text-gray-600">
                    <span>Active : <span class="text-gray-900">{{ $activeStudents }}</span></span>
                    <span>Inactive : <span class="text-gray-900">{{ $inactiveStudents }}</span></span>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 flex flex-col justify-between hover:-translate-y-1 transition duration-300 ease-in-out">
                <div class="flex justify-between items-start">
                    <div class="flex items-center space-x-4">
                            <i class="fas fa-chalkboard-teacher mr-3 text-green-500 text-2xl"></i>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $totalTeachers }}</h2>
                            <p class="text-sm text-gray-500">Total Teachers</p>
                        </div>
                    </div>
                    <span class="bg-sky-500 text-white text-xs font-semibold px-2 py-1 rounded">
                        {{ $teacherChange }}%
                    </span>
                </div>
                <hr class="my-4" />
                <div class="flex justify-between text-sm font-semibold text-gray-600">
                    <span>Active : <span class="text-gray-900">{{ $activeTeachers }}</span></span>
                    <span>Inactive : <span class="text-gray-900">{{ $inactiveTeachers }}</span></span>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 flex flex-col justify-between hover:-translate-y-1 transition duration-300 ease-in-out">
                <div class="flex justify-between items-start">
                    <div class="flex items-center space-x-4">
                            <i class="fas fa-user-tie mr-3 text-yellow-500 text-2xl"></i>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $totalStaff }}</h2>
                            <p class="text-sm text-gray-500">Total Staff</p>
                        </div>
                    </div>
                    <span class="bg-yellow-500 text-white text-xs font-semibold px-2 py-1 rounded">
                        {{ $staffChange }}%
                    </span>
                </div>
                <hr class="my-4" />
                <div class="flex justify-between text-sm font-semibold text-gray-600">
                    <span>Active : <span class="text-gray-900">{{ $activeStaff }}</span></span>
                    <span>Inactive : <span class="text-gray-900">{{ $inactiveStaff }}</span></span>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 flex flex-col justify-between hover:-translate-y-1 transition duration-300 ease-in-out">
                <div class="flex justify-between items-start">
                    <div class="flex items-center space-x-4">
                            <i class="fas fa-book mr-3 text-purple-500 text-2xl"></i>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $totalSubjects }}</h2>
                            <p class="text-sm text-gray-500">Total Subjects</p>
                        </div>
                    </div>
                    <span class="bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded">
                        {{ $subjectChange }}%
                    </span>
                </div>
                <hr class="my-4" />
                <div class="flex justify-between text-sm font-semibold text-gray-600">
                    <span>Active : <span class="text-gray-900">{{ $activeSubjects }}</span></span>
                    <span>Inactive : <span class="text-gray-900">{{ $inactiveSubjects }}</span></span>
                </div>
            </div>
        </div>
        <div class="grid lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 p-4 gap-4">
            <div>
                <div class="bg-white rounded-md shadow">
                    <div class="flex items-center justify-between border-b px-4 py-3">
                        <h2 class="text-lg font-bold text-gray-900">Schedules</h2>
                        <!-- <button class="text-blue-600 font-semibold text-sm flex items-center space-x-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Add New</span>
                    </button> -->
                    </div>
                    <div class="flex justify-center items-center px-4 py-6">
                        <div id="datepicker-inline" inline-datepicker data-date="02/25/2024"></div>
                    </div>
                    <div class="max-w-sm bg-white shadow-md rounded-lg p-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Upcoming Events</h2>

                        <div class="border-l-4 border-cyan-500 pl-4">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="bg-cyan-100 text-cyan-600 p-2 rounded-md">
                                    <!-- Icon (use any icon library or placeholder) -->
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a9 9 0 00-9 9h18a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Parents, Teacher Meet</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <!-- Calendar Icon -->
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        15 July 2024
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3" />

                            <div class="flex justify-between items-center text-sm text-gray-600">
                                <div class="flex items-center">
                                    <!-- Clock Icon -->
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    09:10AM - 10:50PM
                                </div>

                                <div class="flex -space-x-2">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/men/32.jpg" alt="">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/men/33.jpg" alt="">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/women/34.jpg" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="border-l-4 border-cyan-500 pl-4 mt-4">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="bg-cyan-100 text-cyan-600 p-2 rounded-md">
                                    <!-- Icon (use any icon library or placeholder) -->
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a9 9 0 00-9 9h18a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Parents, Teacher Meet</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <!-- Calendar Icon -->
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        15 July 2024
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3" />

                            <div class="flex justify-between items-center text-sm text-gray-600">
                                <div class="flex items-center">
                                    <!-- Clock Icon -->
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    09:10AM - 10:50PM
                                </div>

                                <div class="flex -space-x-2">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/men/32.jpg" alt="">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/men/33.jpg" alt="">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/women/34.jpg" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="border-l-4 border-cyan-500 pl-4 mt-4">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="bg-cyan-100 text-cyan-600 p-2 rounded-md">
                                    <!-- Icon (use any icon library or placeholder) -->
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a9 9 0 00-9 9h18a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Parents, Teacher Meet</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <!-- Calendar Icon -->
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        15 July 2024
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3" />

                            <div class="flex justify-between items-center text-sm text-gray-600">
                                <div class="flex items-center">
                                    <!-- Clock Icon -->
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    09:10AM - 10:50PM
                                </div>

                                <div class="flex -space-x-2">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/men/32.jpg" alt="">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/men/33.jpg" alt="">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/women/34.jpg" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="border-l-4 border-cyan-500 pl-4 mt-4">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="bg-cyan-100 text-cyan-600 p-2 rounded-md">
                                    <!-- Icon (use any icon library or placeholder) -->
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a9 9 0 00-9 9h18a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Parents, Teacher Meet</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <!-- Calendar Icon -->
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        15 July 2024
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3" />

                            <div class="flex justify-between items-center text-sm text-gray-600">
                                <div class="flex items-center">
                                    <!-- Clock Icon -->
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    09:10AM - 10:50PM
                                </div>

                                <div class="flex -space-x-2">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/men/32.jpg" alt="">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/men/33.jpg" alt="">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/women/34.jpg" alt="">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div>
                <div class="bg-white rounded-md shadow">
                    <div class="flex items-center justify-between border-b px-4 py-3">
                        <h2 class="text-lg font-bold text-gray-900">Attendance</h2>
                    </div>
                    <div class="flex justify-center items-center px-4 py-6">
                        <div class="p-6 bg-white w-full max-w-md mx-auto">
                            <!-- Tabs -->
                            <div class="flex space-x-8 border-b pb-2">
                                <button class="text-blue-600 font-medium border-b-2 border-blue-600 pb-1">Students</button>
                                <button class="text-gray-500 hover:text-blue-600">Teachers</button>
                                <button class="text-gray-500 hover:text-blue-600">Staff</button>
                            </div>

                            <!-- Stats Boxes -->
                            <div class="grid grid-cols-3 gap-4 my-6 text-center">
                                <div class="bg-gray-100 flex justify-center items-center flex-col rounded-lg py-4">
                                    <div class="text-2xl font-bold">28</div>
                                    <div class="text-xs text-gray-600">Emergency</div>
                                </div>
                                <div class="bg-gray-100 flex justify-center items-center flex-col rounded-lg py-4">
                                    <div class="text-2xl font-bold">01</div>
                                    <div class="text-xs text-gray-600">Absent</div>
                                </div>
                                <div class="bg-gray-100 flex justify-center items-center flex-col rounded-lg py-4">
                                    <div class="text-2xl font-bold text-blue-600">01</div>
                                    <div class="text-xs text-gray-600">Late</div>
                                </div>
                            </div>
                            <div class="relative flex items-center justify-center w-48 h-48 mx-auto my-4">
                                <svg class="absolute w-full h-full">
                                    <circle cx="50%" cy="50%" r="70" fill="none" stroke="#3B82F6" stroke-width="20" stroke-dasharray="440" stroke-dashoffset="5" />
                                    <circle cx="50%" cy="50%" r="70" fill="none" stroke="#06B6D4" stroke-width="20" stroke-dasharray="440" stroke-dashoffset="420" />
                                </svg>
                                <span class="text-xl font-semibold text-blue-600">98.8%</span>
                            </div>
                            <div class="text-center">
                                <button class="mt-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-gray-700 font-medium flex items-center justify-center space-x-2 mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10M5 11h14M5 15h14M5 19h14" />
                                    </svg>
                                    <span>View All</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="flex gap-4 mt-4">
                    <div class="w-1/2">


                        <div id="controls-carousel" class="relative w-full" data-carousel="static">
                            <!-- Carousel wrapper -->
                            <div class="relative h-56 overflow-hidden rounded-lg md:h-96 bg-green-200">
                                <!-- Item 1 -->
                                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                    <div class="h-full w-full flex flex-col justify-between items-center">
                                        <div class="p-4 text-center">
                                            <h3 class="text-base font-semibold mb-4">Best Performer</h3>
                                            <h4 class="text-lg font-bold">Tenesa</h4>
                                            <p class="text-sm">Math Teacher</p>
                                        </div>
                                        <img src="{{ URL::to('/') }}/saasadmin/img/student-performer-01.webp" alt="Teachers" alt="">
                                    </div>
                                </div>
                                <!-- Item 2 -->
                                <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                                    <div class="h-full w-full flex flex-col justify-between items-center">
                                        <div class="p-4 text-center">
                                            <h3 class="text-base font-semibold mb-4">Best Performer</h3>
                                            <h4 class="text-lg font-bold">Micheal</h4>
                                            <p class="text-sm">Math Teacher</p>
                                        </div>
                                        <img src="{{ URL::to('/') }}/saasadmin/img/student-performer-02.webp" alt="Teachers" alt="">
                                    </div>
                                </div>
                                <!-- Item 3 -->
                                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                    <div class="h-full w-full flex flex-col justify-between items-center">
                                        <div class="p-4 text-center">
                                            <h3 class="text-base font-semibold mb-4">Best Performer</h3>
                                            <h4 class="text-lg font-bold">Tenesa</h4>
                                            <p class="text-sm">Math Teacher</p>
                                        </div>
                                        <img src="{{ URL::to('/') }}/saasadmin/img/student-performer-01.webp" alt="Teachers" alt="">
                                    </div>
                                </div>
                                <!-- Item 4 -->
                                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                    <div class="h-full w-full flex flex-col justify-between items-center">
                                        <div class="p-4 text-center">
                                            <h3 class="text-base font-semibold mb-4">Best Performer</h3>
                                            <h4 class="text-lg font-bold">Micheal</h4>
                                            <p class="text-sm">History Teacher</p>
                                        </div>
                                        <img src="{{ URL::to('/') }}/saasadmin/img/student-performer-02.webp" alt="Teachers" alt="">
                                    </div>
                                </div>
                                <!-- Item 5 -->
                                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                    <div class="h-full w-full flex flex-col justify-between items-center">
                                        <div class="p-4 text-center">
                                            <h3 class="text-base font-semibold mb-4">Best Performer</h3>
                                            <h4 class="text-lg font-bold">Tenesa</h4>
                                            <p class="text-sm">Math Teacher</p>
                                        </div>
                                        <img src="{{ URL::to('/') }}/saasadmin/img/student-performer-01.webp" alt="Teachers" alt="">
                                    </div>
                                </div>
                            </div>
                            <!-- Slider controls -->
                            <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                    <svg class="w-3 h-3 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4" />
                                    </svg>
                                    <span class="sr-only">Previous</span>
                                </span>
                            </button>
                            <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                    <svg class="w-3 h-3 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                    <span class="sr-only">Next</span>
                                </span>
                            </button>
                        </div>

                    </div>
                    <div class="w-1/2">


                        <div id="controls-carousel" class="relative w-full" data-carousel="static">
                            <!-- Carousel wrapper -->
                            <div class="relative h-56 overflow-hidden rounded-lg md:h-96 bg-blue-200">
                                <!-- Item 1 -->
                                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                    <div class="h-full w-full flex flex-col justify-between items-center">
                                        <div class="p-4 text-center">
                                            <h3 class="text-base font-semibold mb-4">Best Performer</h3>
                                            <h4 class="text-lg font-bold">Tenesa</h4>
                                            <p class="text-sm">Math Teacher</p>
                                        </div>
                                        <img src="{{ URL::to('/') }}/saasadmin/img/performer-01.webp" alt="Teachers" alt="">
                                    </div>
                                </div>
                                <!-- Item 2 -->
                                <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                                    <div class="h-full w-full flex flex-col justify-between items-center">
                                        <div class="p-4 text-center">
                                            <h3 class="text-base font-semibold mb-4">Best Performer</h3>
                                            <h4 class="text-lg font-bold">Tenesa</h4>
                                            <p class="text-sm">Math Teacher</p>
                                        </div>
                                        <img src="{{ URL::to('/') }}/saasadmin/img/performer-01.webp" alt="Teachers" alt="">
                                    </div>
                                </div>
                                <!-- Item 3 -->
                                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                    <div class="h-full w-full flex flex-col justify-between items-center">
                                        <div class="p-4 text-center">
                                            <h3 class="text-base font-semibold mb-4">Best Performer</h3>
                                            <h4 class="text-lg font-bold">Tenesa</h4>
                                            <p class="text-sm">Math Teacher</p>
                                        </div>
                                        <img src="{{ URL::to('/') }}/saasadmin/img/performer-01.webp" alt="Teachers" alt="">
                                    </div>
                                </div>
                                <!-- Item 4 -->
                                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                    <div class="h-full w-full flex flex-col justify-between items-center">
                                        <div class="p-4 text-center">
                                            <h3 class="text-base font-semibold mb-4">Best Performer</h3>
                                            <h4 class="text-lg font-bold">Tenesa</h4>
                                            <p class="text-sm">Math Teacher</p>
                                        </div>
                                        <img src="{{ URL::to('/') }}/saasadmin/img/student-performer-01.webp" alt="Teachers" alt="">
                                    </div>
                                </div>
                                <!-- Item 5 -->
                                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                    <div class="h-full w-full flex flex-col justify-between items-center">
                                        <div class="p-4 text-center">
                                            <h3 class="text-base font-semibold mb-4">Best Performer</h3>
                                            <h4 class="text-lg font-bold">Tenesa</h4>
                                            <p class="text-sm">Math Teacher</p>
                                        </div>
                                        <img src="{{ URL::to('/') }}/saasadmin/img/student-performer-01.webp" alt="Teachers" alt="">
                                    </div>
                                </div>
                            </div>
                            <!-- Slider controls -->
                            <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                    <svg class="w-3 h-3 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4" />
                                    </svg>
                                    <span class="sr-only">Previous</span>
                                </span>
                            </button>
                            <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                    <svg class="w-3 h-3 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                    <span class="sr-only">Next</span>
                                </span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            <div>
                <div class="bg-white rounded-md shadow">
                    <div class="flex items-center justify-between border-b px-4 py-3">
                        <h2 class="text-lg font-bold text-gray-900">Quick Links</h2>
                    </div>
                    <div class="flex justify-center gap-4 items-center px-4 pt-4">
                        <div class="w-1/2 bg-lime-100 p-3 flex flex-col items-center justify-center">
                            <div class="w-[50px] h-[50px] border border-green-500 rounded-full flex items-center justify-center">
                                <div class="bg-green-500 w-[40px] h-[40px] rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="17.5" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                        <path fill="#ffffff" d="M128 0c17.7 0 32 14.3 32 32l0 32 128 0 0-32c0-17.7 14.3-32 32-32s32 14.3 32 32l0 32 48 0c26.5 0 48 21.5 48 48l0 48L0 160l0-48C0 85.5 21.5 64 48 64l48 0 0-32c0-17.7 14.3-32 32-32zM0 192l448 0 0 272c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 192zm64 80l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm128 0l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zM64 400l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600">Emergency</div>
                        </div>
                        <div class="w-1/2 bg-blue-100 p-3 flex flex-col items-center justify-center">
                            <div class="w-[50px] h-[50px] border border-blue-500 rounded-full flex items-center justify-center">
                                <div class="bg-blue-500 w-[40px] h-[40px] rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="17.5" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                        <path fill="#ffffff" d="M128 0c17.7 0 32 14.3 32 32l0 32 128 0 0-32c0-17.7 14.3-32 32-32s32 14.3 32 32l0 32 48 0c26.5 0 48 21.5 48 48l0 48L0 160l0-48C0 85.5 21.5 64 48 64l48 0 0-32c0-17.7 14.3-32 32-32zM0 192l448 0 0 272c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 192zm64 80l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm128 0l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zM64 400l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600">Emergency</div>
                        </div>
                    </div>
                    <div class="flex justify-center gap-4 items-center p-4">
                        <div class="w-1/2 bg-lime-100 p-3 flex flex-col items-center justify-center">
                            <div class="w-[50px] h-[50px] border border-green-500 rounded-full flex items-center justify-center">
                                <div class="bg-green-500 w-[40px] h-[40px] rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="17.5" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                        <path fill="#ffffff" d="M128 0c17.7 0 32 14.3 32 32l0 32 128 0 0-32c0-17.7 14.3-32 32-32s32 14.3 32 32l0 32 48 0c26.5 0 48 21.5 48 48l0 48L0 160l0-48C0 85.5 21.5 64 48 64l48 0 0-32c0-17.7 14.3-32 32-32zM0 192l448 0 0 272c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 192zm64 80l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm128 0l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zM64 400l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600">Emergency</div>
                        </div>
                        <div class="w-1/2 bg-blue-100 p-3 flex flex-col items-center justify-center">
                            <div class="w-[50px] h-[50px] border border-blue-500 rounded-full flex items-center justify-center">
                                <div class="bg-blue-500 w-[40px] h-[40px] rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="20" width="17.5" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                        <path fill="#ffffff" d="M128 0c17.7 0 32 14.3 32 32l0 32 128 0 0-32c0-17.7 14.3-32 32-32s32 14.3 32 32l0 32 48 0c26.5 0 48 21.5 48 48l0 48L0 160l0-48C0 85.5 21.5 64 48 64l48 0 0-32c0-17.7 14.3-32 32-32zM0 192l448 0 0 272c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 192zm64 80l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm128 0l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zM64 400l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600">Emergency</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-md shadow mt-4">
                    <div class="flex items-center justify-between border-b px-4 py-3">
                        <h2 class="text-lg font-bold text-gray-900">Class Routine</h2>
                    </div>
                    <div class="flex justify-center gap-4 items-center px-4 pt-4 flex-col">
                        <div class="bg-white border rounded-lg p-4 flex items-center space-x-2 w-full">
                            <img class="w-10 h-10 rounded-md object-cover" src="https://randomuser.me/api/portraits/women/1.jpg" alt="User" />
                            <div class="flex-1">
                                <p class="text-gray-700 font-medium mb-2 text-sm">Oct 2024</p>
                                <div class="w-full h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 bg-blue-500 rounded-full w-4/5 bg-gradient-to-r from-blue-500 to-blue-600"></div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border rounded-lg p-4 flex items-center space-x-2 w-full">
                            <img class="w-10 h-10 rounded-md object-cover" src="https://randomuser.me/api/portraits/men/2.jpg" alt="User" />
                            <div class="flex-1">
                                <p class="text-gray-700 font-medium mb-2 text-sm">Nov 2024</p>
                                <div class="w-full h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 bg-yellow-400 rounded-full w-4/5 bg-gradient-to-r from-yellow-400 to-yellow-500"></div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border rounded-lg p-4 mb-4 flex items-center space-x-2 w-full">
                            <img class="w-10 h-10 rounded-md object-cover" src="https://randomuser.me/api/portraits/women/3.jpg" alt="User" />
                            <div class="flex-1">
                                <p class="text-gray-700 font-medium mb-2 text-sm">Oct 2024</p>
                                <div class="w-full h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 bg-green-500 rounded-full w-11/12 bg-gradient-to-r from-green-500 to-green-600"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-md shadow mt-4">
                    <div class="flex items-center justify-between border-b px-4 py-3">
                        <h2 class="text-lg font-bold text-gray-900">Performance</h2>
                    </div>
                    <div class="flex justify-center gap-4 items-center px-4 pt-4 flex-col">
                        <div class="relative flex items-center justify-center w-48 h-48 mx-auto my-4">
                            <svg class="absolute w-full h-full" viewBox="0 0 200 200">
                                <circle cx="100" cy="100" r="80" fill="none" stroke="#E5E7EB" stroke-width="20" />
                                <circle cx="100" cy="100" r="80" fill="none" stroke="#3B82F6" stroke-width="20"
                                    stroke-dasharray="502.65"
                                    stroke-dashoffset="160.85"
                                    stroke-linecap="round"
                                    transform="rotate(-90 100 100)" />
                            </svg>
                            <span class="text-xl font-semibold text-blue-600">68%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid sm:grid-cols-[1.5fr_1fr] grid-cols-1 p-4 gap-4">
            <div>
                <div class="bg-white rounded-md shadow">
                    <div class="flex items-center justify-between border-b px-4 py-3">
                        <h2 class="text-lg font-bold text-gray-900">Fees Collection</h2>
                    </div>
                    <div class="flex justify-center gap-4 items-center p-4">
                        <div>
                            <div class="flex space-x-4">
                                <!-- Y-Axis Scale -->
                                <div class="flex flex-col justify-between h-64 text-xs text-gray-600 pr-2">
                                    <span>100</span>
                                    <span>80</span>
                                    <span>60</span>
                                    <span>40</span>
                                    <span>20</span>
                                    <span>0</span>
                                </div>

                                <!-- Graph Bars -->
                                <div class="flex items-end space-x-4 h-64 border-l border-gray-300 relative">
                                    <!-- Horizontal grid lines -->
                                    <div class="absolute w-full h-full top-0 left-0 flex flex-col justify-between">
                                        <div class="border-t border-dashed border-gray-300"></div>
                                        <div class="border-t border-dashed border-gray-300"></div>
                                        <div class="border-t border-dashed border-gray-300"></div>
                                        <div class="border-t border-dashed border-gray-300"></div>
                                        <div class="border-t border-dashed border-gray-300"></div>
                                    </div>

                                    <!-- Bar (repeat 9x) -->
                                    <div class="flex flex-col items-center">
                                        <div class="w-10 h-36 bg-gray-200 flex flex-col justify-end">
                                            <div class="bg-blue-600 h-24 w-full"></div>
                                        </div>
                                        <span class="text-xs mt-1">Q1: 2023</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="w-10 h-40 bg-gray-200 flex flex-col justify-end">
                                            <div class="bg-blue-600 h-28 w-full"></div>
                                        </div>
                                        <span class="text-xs mt-1">Q1: 2023</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="w-10 h-40 bg-gray-200 flex flex-col justify-end">
                                            <div class="bg-blue-600 h-26 w-full"></div>
                                        </div>
                                        <span class="text-xs mt-1">Q1: 2023</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="w-10 h-40 bg-gray-200 flex flex-col justify-end">
                                            <div class="bg-blue-600 h-28 w-full"></div>
                                        </div>
                                        <span class="text-xs mt-1">Q1: 2023</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="w-10 h-40 bg-gray-200 flex flex-col justify-end">
                                            <div class="bg-blue-600 h-26 w-full"></div>
                                        </div>
                                        <span class="text-xs mt-1">Q1: 2023</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="w-10 h-32 bg-gray-200 flex flex-col justify-end">
                                            <div class="bg-blue-600 h-24 w-full"></div>
                                        </div>
                                        <span class="text-xs mt-1">Q1: 2023</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="w-10 h-36 bg-gray-200 flex flex-col justify-end">
                                            <div class="bg-blue-600 h-28 w-full"></div>
                                        </div>
                                        <span class="text-xs mt-1">Q1: 2023</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="w-10 h-40 bg-gray-200 flex flex-col justify-end">
                                            <div class="bg-blue-600 h-30 w-full"></div>
                                        </div>
                                        <span class="text-xs mt-1">Q1: 2023</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="w-10 h-44 bg-gray-200 flex flex-col justify-end">
                                            <div class="bg-blue-600 h-32 w-full"></div>
                                        </div>
                                        <span class="text-xs mt-1">Q1: 2023</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Legend -->
                            <div class="flex mt-6 space-x-4 text-sm">
                                <div class="flex items-center space-x-1">
                                    <div class="w-4 h-4 bg-blue-600"></div>
                                    <span>Collected Fee</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <div class="w-4 h-4 bg-gray-200 border border-gray-300"></div>
                                    <span>Total Fee</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div>
                <div class="bg-white rounded-md shadow">
                    <div class="flex items-center justify-between border-b px-4 py-3">
                        <h2 class="text-lg font-bold text-gray-900">Leave Requests</h2>
                    </div>
                    <div class="flex justify-center gap-4 items-center p-4">
                        <div class="space-y-4 w-full">
                            <!-- Card 1 -->
                            <div class="flex flex-col sm:flex-row sm:items-start p-4 bg-white rounded-xl shadow border">
                                <!-- Image -->
                                <img src="https://i.pravatar.cc/80?img=1" class="w-16 h-16 rounded-lg object-cover" alt="James" />

                                <!-- Content -->
                                <div class="sm:ml-4 flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-bold text-lg text-gray-800 flex items-center">
                                                James
                                                <span class="ml-2 bg-red-100 text-red-600 text-xs font-semibold px-2 py-0.5 rounded">Emergency</span>
                                            </h3>
                                            <p class="text-gray-500 text-sm">Physics Teacher</p>
                                        </div>
                                        <div class="flex space-x-2 mt-2 sm:mt-0">
                                            <button class="bg-green-500 hover:bg-green-600 text-white rounded-full p-1.5 flex items-center justify-center w-7 h-7">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="16" width="17.5" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                                    <path fill="#ffffff" d="M342.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 178.7l-57.4-57.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l80 80c12.5 12.5 32.8 12.5 45.3 0l160-160zm96 128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 402.7 54.6 297.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l256-256z" />
                                                </svg>
                                            </button>
                                            <button class="bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 w-7 h-7 flex justify-center items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="16" width="10.5" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                                    <path fill="#ffffff" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <hr class="my-2" />
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <p>Leave : <span class="font-semibold">12 -13 May</span></p>
                                        <p>Apply on : <span class="font-semibold">12 May</span></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2 -->
                            <div class="flex flex-col sm:flex-row sm:items-start p-4 bg-white rounded-xl shadow border">
                                <!-- Image -->
                                <img src="https://i.pravatar.cc/80?img=2" class="w-16 h-16 rounded-lg object-cover" alt="Ramien" />

                                <!-- Content -->
                                <div class="sm:ml-4 flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-bold text-lg text-gray-800 flex items-center">
                                                Ramien
                                                <span class="ml-2 bg-yellow-100 text-yellow-600 text-xs font-semibold px-2 py-0.5 rounded">Casual</span>
                                            </h3>
                                            <p class="text-gray-500 text-sm">Accountant</p>
                                        </div>
                                        <div class="flex space-x-2 mt-2 sm:mt-0">
                                            <button class="bg-green-500 hover:bg-green-600 text-white rounded-full p-1.5 flex items-center justify-center w-7 h-7">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="16" width="17.5" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                                    <path fill="#ffffff" d="M342.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 178.7l-57.4-57.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l80 80c12.5 12.5 32.8 12.5 45.3 0l160-160zm96 128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L160 402.7 54.6 297.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l256-256z" />
                                                </svg>
                                            </button>
                                            <button class="bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 w-7 h-7 flex justify-center items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="16" width="10.5" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                                    <path fill="#ffffff" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <hr class="my-2" />
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <p>Leave : <span class="font-semibold">12 -13 May</span></p>
                                        <p>Apply on : <span class="font-semibold">11 May</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap gap-4 p-4 bg-gray-50">
            <!-- Card 1: View Attendance -->
            <div class="flex items-center justify-between bg-yellow-50 rounded-xl p-4 w-64 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="bg-yellow-400 p-4 rounded-md text-white text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="17.5" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                            <path fill="#ffffff" d="M128 0c17.7 0 32 14.3 32 32l0 32 128 0 0-32c0-17.7 14.3-32 32-32s32 14.3 32 32l0 32 48 0c26.5 0 48 21.5 48 48l0 48L0 160l0-48C0 85.5 21.5 64 48 64l48 0 0-32c0-17.7 14.3-32 32-32zM0 192l448 0 0 272c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 192zm64 80l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm128 0l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zM64 400l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16z" />
                        </svg>
                    </div>
                    <h2 class="text-gray-700 font-semibold">View Attendance</h2>
                </div>
                <div class="bg-white w-7 h-7 flex items-center justify-center rounded-full text-gray-500 text-sm">
                    →
                </div>
            </div>

            <!-- Card 2: New Events -->
            <div class="flex items-center justify-between bg-green-50 rounded-xl p-4 w-64 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="bg-green-500 p-4 rounded-md text-white text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                            <path fill="#ffffff" d="M480 32c0-12.9-7.8-24.6-19.8-29.6s-25.7-2.2-34.9 6.9L381.7 53c-48 48-113.1 75-181 75l-8.7 0-32 0-96 0c-35.3 0-64 28.7-64 64l0 96c0 35.3 28.7 64 64 64l0 128c0 17.7 14.3 32 32 32l64 0c17.7 0 32-14.3 32-32l0-128 8.7 0c67.9 0 133 27 181 75l43.6 43.6c9.2 9.2 22.9 11.9 34.9 6.9s19.8-16.6 19.8-29.6l0-147.6c18.6-8.8 32-32.5 32-60.4s-13.4-51.6-32-60.4L480 32zm-64 76.7L416 240l0 131.3C357.2 317.8 280.5 288 200.7 288l-8.7 0 0-96 8.7 0c79.8 0 156.5-29.8 215.3-83.3z" />
                        </svg>
                    </div>
                    <h2 class="text-gray-700 font-semibold">New Events</h2>
                </div>
                <div class="bg-white w-7 h-7 flex items-center justify-center rounded-full text-gray-500 text-sm">
                    →
                </div>
            </div>

            <!-- Card 3: Membership Plans -->
            <div class="flex items-center justify-between bg-red-50 rounded-xl p-4 w-64 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="bg-red-500 p-4 rounded-md text-white text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                            <path fill="#ffffff" d="M352 256c0 22.2-1.2 43.6-3.3 64l-185.3 0c-2.2-20.4-3.3-41.8-3.3-64s1.2-43.6 3.3-64l185.3 0c2.2 20.4 3.3 41.8 3.3 64zm28.8-64l123.1 0c5.3 20.5 8.1 41.9 8.1 64s-2.8 43.5-8.1 64l-123.1 0c2.1-20.6 3.2-42 3.2-64s-1.1-43.4-3.2-64zm112.6-32l-116.7 0c-10-63.9-29.8-117.4-55.3-151.6c78.3 20.7 142 77.5 171.9 151.6zm-149.1 0l-176.6 0c6.1-36.4 15.5-68.6 27-94.7c10.5-23.6 22.2-40.7 33.5-51.5C239.4 3.2 248.7 0 256 0s16.6 3.2 27.8 13.8c11.3 10.8 23 27.9 33.5 51.5c11.6 26 20.9 58.2 27 94.7zm-209 0L18.6 160C48.6 85.9 112.2 29.1 190.6 8.4C165.1 42.6 145.3 96.1 135.3 160zM8.1 192l123.1 0c-2.1 20.6-3.2 42-3.2 64s1.1 43.4 3.2 64L8.1 320C2.8 299.5 0 278.1 0 256s2.8-43.5 8.1-64zM194.7 446.6c-11.6-26-20.9-58.2-27-94.6l176.6 0c-6.1 36.4-15.5 68.6-27 94.6c-10.5 23.6-22.2 40.7-33.5 51.5C272.6 508.8 263.3 512 256 512s-16.6-3.2-27.8-13.8c-11.3-10.8-23-27.9-33.5-51.5zM135.3 352c10 63.9 29.8 117.4 55.3 151.6C112.2 482.9 48.6 426.1 18.6 352l116.7 0zm358.1 0c-30 74.1-93.6 130.9-171.9 151.6c25.5-34.2 45.2-87.7 55.3-151.6l116.7 0z" />
                        </svg>
                    </div>
                    <h2 class="text-gray-700 font-semibold">Membership Plans</h2>
                </div>
                <div class="bg-white w-7 h-7 flex items-center justify-center rounded-full text-gray-500 text-sm">
                    →
                </div>
            </div>

            <!-- Card 4: Finance & Accounts -->
            <div class="flex items-center justify-between bg-cyan-50 rounded-xl p-4 w-64 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="bg-cyan-400 p-4 rounded-md text-white text-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                            <path fill="#ffffff" d="M512 80c0 18-14.3 34.6-38.4 48c-29.1 16.1-72.5 27.5-122.3 30.9c-3.7-1.8-7.4-3.5-11.3-5C300.6 137.4 248.2 128 192 128c-8.3 0-16.4 .2-24.5 .6l-1.1-.6C142.3 114.6 128 98 128 80c0-44.2 86-80 192-80S512 35.8 512 80zM160.7 161.1c10.2-.7 20.7-1.1 31.3-1.1c62.2 0 117.4 12.3 152.5 31.4C369.3 204.9 384 221.7 384 240c0 4-.7 7.9-2.1 11.7c-4.6 13.2-17 25.3-35 35.5c0 0 0 0 0 0c-.1 .1-.3 .1-.4 .2c0 0 0 0 0 0s0 0 0 0c-.3 .2-.6 .3-.9 .5c-35 19.4-90.8 32-153.6 32c-59.6 0-112.9-11.3-148.2-29.1c-1.9-.9-3.7-1.9-5.5-2.9C14.3 274.6 0 258 0 240c0-34.8 53.4-64.5 128-75.4c10.5-1.5 21.4-2.7 32.7-3.5zM416 240c0-21.9-10.6-39.9-24.1-53.4c28.3-4.4 54.2-11.4 76.2-20.5c16.3-6.8 31.5-15.2 43.9-25.5l0 35.4c0 19.3-16.5 37.1-43.8 50.9c-14.6 7.4-32.4 13.7-52.4 18.5c.1-1.8 .2-3.5 .2-5.3zm-32 96c0 18-14.3 34.6-38.4 48c-1.8 1-3.6 1.9-5.5 2.9C304.9 404.7 251.6 416 192 416c-62.8 0-118.6-12.6-153.6-32C14.3 370.6 0 354 0 336l0-35.4c12.5 10.3 27.6 18.7 43.9 25.5C83.4 342.6 135.8 352 192 352s108.6-9.4 148.1-25.9c7.8-3.2 15.3-6.9 22.4-10.9c6.1-3.4 11.8-7.2 17.2-11.2c1.5-1.1 2.9-2.3 4.3-3.4l0 3.4 0 5.7 0 26.3zm32 0l0-32 0-25.9c19-4.2 36.5-9.5 52.1-16c16.3-6.8 31.5-15.2 43.9-25.5l0 35.4c0 10.5-5 21-14.9 30.9c-16.3 16.3-45 29.7-81.3 38.4c.1-1.7 .2-3.5 .2-5.3zM192 448c56.2 0 108.6-9.4 148.1-25.9c16.3-6.8 31.5-15.2 43.9-25.5l0 35.4c0 44.2-86 80-192 80S0 476.2 0 432l0-35.4c12.5 10.3 27.6 18.7 43.9 25.5C83.4 438.6 135.8 448 192 448z" />
                        </svg>
                    </div>
                    <h2 class="text-gray-700 font-semibold">Finance & Accounts</h2>
                </div>
                <div class="bg-white w-7 h-7 flex items-center justify-center rounded-full text-gray-500 text-sm">
                    →
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-[1fr_1.5fr_1fr] sm:grid-cols-[1fr_1fr] grid-cols-1 p-4 gap-4">
            <div></div>
            <div></div>
            <div></div>
        </div>

    {{-- <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <h1 class="text-2xl font-semibold text-gray-800">Welcome to the Dashboard</h1>
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4 mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        
        <!-- Subscription Info -->
        <div class="mt-6 p-6 bg-white rounded-lg shadow-md">
            <h2 class="text-lg font-medium text-gray-800">Your Subscription</h2>
            @php
                $school = \App\Models\School::where('admin_id', auth()->id())->first();
                $schoolId = $school ? $school->id : null;
                
                $activeSubscription = null;
                if ($schoolId) {
                    $activeSubscription = \App\Models\SchoolSubscription::where('school_id', $schoolId)
                        ->where('status', 'active')
                        ->whereDate('end_date', '>=', now())
                        ->with(['plan', 'plan.features'])
                        ->latest()
                        ->first();
                }
                
                // For debugging
                if (!$activeSubscription) {
                    \Illuminate\Support\Facades\Log::info('No active subscription found for school: ' . $schoolId);
                }
            @endphp
            
            @if($activeSubscription)
                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Plan:</span>
                        <p class="font-medium">{{ $activeSubscription->plan->name }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Valid Until:</span>
                        <p>{{ $activeSubscription->end_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Status:</span>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Active
                        </span>
                    </div>
                </div>
                
                <!-- Resource Limits -->
                <div class="mt-6">
                    <h3 class="text-md font-medium text-gray-700">Resource Limits</h3>
                    
                    @php
                        // Get counts
                        $studentCount = \App\Helpers\SubscriptionHelper::getUserCount('students', $schoolId);
                        $teacherCount = \App\Helpers\SubscriptionHelper::getUserCount('teachers', $schoolId);
                        $staffCount = \App\Helpers\SubscriptionHelper::getUserCount('staff', $schoolId);
                        
                        // Get limits
                        $maxStudents = $activeSubscription->plan->max_students;
                        $maxTeachers = $activeSubscription->plan->max_teachers;
                        $maxStaff = $activeSubscription->plan->max_staff;
                        
                        // Check for approaching limits
                        $isApproachingLimit = false;
                        $hasExceededLimit = false;
                        
                        if ($maxStudents > 0 && $studentCount >= $maxStudents * 0.9) {
                            $isApproachingLimit = true;
                        }
                        if ($maxTeachers > 0 && $teacherCount >= $maxTeachers * 0.9) {
                            $isApproachingLimit = true;
                        }
                        if ($maxStaff > 0 && $staffCount >= $maxStaff * 0.9) {
                            $isApproachingLimit = true;
                        }
                        
                        // Check for exceeded limits
                        if (($maxStudents > 0 && $studentCount > $maxStudents) || 
                            ($maxTeachers > 0 && $teacherCount > $maxTeachers) || 
                            ($maxStaff > 0 && $staffCount > $maxStaff)) {
                            $hasExceededLimit = true;
                        }
                    @endphp
                    
                    @if($hasExceededLimit)
                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-md">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="text-red-700 font-medium">You have exceeded one or more resource limits. Please contact support to upgrade your plan.</p>
                        </div>
                    </div>
                    @elseif($isApproachingLimit)
                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="text-yellow-700 font-medium">You are approaching your resource limits. Consider upgrading your plan soon.</p>
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Students</span>
                                <span class="text-sm font-medium text-gray-700">
                                    {{ \App\Helpers\SubscriptionHelper::getUserCount('students', $schoolId) }} / 
                                    @if($activeSubscription->plan->max_students == 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Unlimited</span>
                                    @else
                                        {{ $activeSubscription->plan->max_students }}
                                    @endif
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php
                                    if ($maxStudents == 0) {
                                        // If unlimited students are allowed
                                        $studentPercentage = 25; // Show a static percentage for unlimited
                                    } else {
                                        // Calculate percentage with a cap at 100%
                                        $studentPercentage = min(100, ($studentCount / $maxStudents) * 100);
                                    }
                                    
                                    // Determine color based on percentage
                                    $barColor = 'bg-blue-600';
                                    if ($studentPercentage > 90) {
                                        $barColor = 'bg-red-600';
                                    } elseif ($studentPercentage > 70) {
                                        $barColor = 'bg-yellow-600';
                                    }
                                @endphp
                                <div class="{{ $barColor }} h-2 rounded-full" style="width: {{ $studentPercentage }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Teachers</span>
                                <span class="text-sm font-medium text-gray-700">
                                    {{ \App\Helpers\SubscriptionHelper::getUserCount('teachers', $schoolId) }} / 
                                    @if($activeSubscription->plan->max_teachers == 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Unlimited</span>
                                    @else
                                        {{ $activeSubscription->plan->max_teachers }}
                                    @endif
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php
                                    if ($maxTeachers == 0) {
                                        // If unlimited teachers are allowed
                                        $teacherPercentage = 25; // Show a static percentage for unlimited
                                    } else {
                                        // Calculate percentage with a cap at 100%
                                        $teacherPercentage = min(100, ($teacherCount / $maxTeachers) * 100);
                                    }
                                    
                                    // Determine color based on percentage
                                    $teacherBarColor = 'bg-green-600';
                                    if ($teacherPercentage > 90) {
                                        $teacherBarColor = 'bg-red-600';
                                    } elseif ($teacherPercentage > 70) {
                                        $teacherBarColor = 'bg-yellow-600';
                                    }
                                @endphp
                                <div class="{{ $teacherBarColor }} h-2 rounded-full" style="width: {{ $teacherPercentage }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">Staff</span>
                                <span class="text-sm font-medium text-gray-700">
                                    {{ \App\Helpers\SubscriptionHelper::getUserCount('staff', $schoolId) }} / 
                                    @if($activeSubscription->plan->max_staff == 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Unlimited</span>
                                    @else
                                        {{ $activeSubscription->plan->max_staff }}
                                    @endif
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php
                                    if ($maxStaff == 0) {
                                        // If unlimited staff are allowed
                                        $staffPercentage = 25; // Show a static percentage for unlimited
                                    } else {
                                        // Calculate percentage with a cap at 100%
                                        $staffPercentage = min(100, ($staffCount / $maxStaff) * 100);
                                    }
                                    
                                    // Determine color based on percentage
                                    $staffBarColor = 'bg-purple-600';
                                    if ($staffPercentage > 90) {
                                        $staffBarColor = 'bg-red-600';
                                    } elseif ($staffPercentage > 70) {
                                        $staffBarColor = 'bg-yellow-600';
                                    }
                                @endphp
                                <div class="{{ $staffBarColor }} h-2 rounded-full" style="width: {{ $staffPercentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-4 p-4 bg-yellow-50 rounded border border-yellow-200">
                    <p class="text-yellow-800">You don't have an active subscription. Please contact the administrator to activate your subscription.</p>
                </div>
            @endif
        </div>
        
        <!-- Available Modules -->
        <div class="mt-6">
            <h2 class="text-lg font-medium text-gray-800">Available Modules</h2>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Academic Module -->
                <div class="p-4 bg-white rounded-lg shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-2 rounded-md bg-blue-100 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="ml-3 text-md font-medium text-gray-800">Academics</h3>
                    </div>
                    <div class="mt-4 space-y-2">
                        @hasFeature('academic_sections', $schoolId)
                            <a href="{{ route('school.sections') }}" class="block text-blue-600 hover:underline">Sections</a>
                        @endhasFeature
                        
                        @hasFeature('academic_classes', $schoolId)
                            <a href="{{ route('school.class') }}" class="block text-blue-600 hover:underline">Classes</a>
                        @endhasFeature
                        
                        @hasFeature('academic_subjects', $schoolId)
                            <a href="{{ route('school.subjects') }}" class="block text-blue-600 hover:underline">Subjects</a>
                            <a href="{{ route('school.assignSubjects') }}" class="block text-blue-600 hover:underline">Assign Subjects</a>
                        @endhasFeature
                        
                        @hasFeature('attendance', $schoolId)
                            <a href="{{ route('school.attendance') }}" class="block text-blue-600 hover:underline">Attendance</a>
                        @endhasFeature
                        
                        @hasFeature('timetable', $schoolId)
                            <a href="{{ route('school.timeTable') }}" class="block text-blue-600 hover:underline">Time Table</a>
                        @endhasFeature
                        
                        @hasFeature('homework', $schoolId)
                            <a href="{{ route('school.homeWork') }}" class="block text-blue-600 hover:underline">Homework</a>
                        @endhasFeature
                    </div>
                </div>
                
                <!-- Hostel Module -->
                @hasFeature('hostel_management', $schoolId)
                    <div class="p-4 bg-white rounded-lg shadow border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-2 rounded-md bg-green-100 text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-md font-medium text-gray-800">Hostel</h3>
                        </div>
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('school.hostelList') }}" class="block text-green-600 hover:underline">Hostel List</a>
                            <a href="{{ route('school.roomType') }}" class="block text-green-600 hover:underline">Room Types</a>
                            <a href="{{ route('school.hostelRooms') }}" class="block text-green-600 hover:underline">Hostel Rooms</a>
                        </div>
                    </div>
                @endhasFeature
                
                <!-- Transport Module -->
                @hasFeature('transport_management', $schoolId)
                    <div class="p-4 bg-white rounded-lg shadow border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-2 rounded-md bg-yellow-100 text-yellow-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-md font-medium text-gray-800">Transport</h3>
                        </div>
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('school.vehicleDrivers') }}" class="block text-yellow-600 hover:underline">Vehicle Drivers</a>
                            <a href="{{ route('school.vehicles') }}" class="block text-yellow-600 hover:underline">Vehicles</a>
                            <a href="{{ route('school.routes') }}" class="block text-yellow-600 hover:underline">Routes</a>
                            <a href="{{ route('school.assignVehicle') }}" class="block text-yellow-600 hover:underline">Assign Vehicle</a>
                        </div>
                    </div>
                @endhasFeature
                
                <!-- Finance Module -->
                @hasFeature('finance_management', $schoolId)
                    <div class="p-4 bg-white rounded-lg shadow border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-2 rounded-md bg-purple-100 text-purple-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-md font-medium text-gray-800">Finance</h3>
                        </div>
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('school.collectFee') }}" class="block text-purple-600 hover:underline">Collect Fee</a>
                            <a href="{{ route('school.assignFee') }}" class="block text-purple-600 hover:underline">Assign Fee</a>
                            <a href="{{ route('school.feeGroup') }}" class="block text-purple-600 hover:underline">Fee Groups</a>
                            <a href="{{ route('school.feeType') }}" class="block text-purple-600 hover:underline">Fee Types</a>
                            <a href="{{ route('school.feeMaster') }}" class="block text-purple-600 hover:underline">Fee Master</a>
                            <a href="{{ route('school.payRoll') }}" class="block text-purple-600 hover:underline">Payroll</a>
                        </div>
                    </div>
                @endhasFeature
                
                <!-- Examination Module -->
                @hasFeature('examination_management', $schoolId)
                    <div class="p-4 bg-white rounded-lg shadow border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-2 rounded-md bg-red-100 text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-md font-medium text-gray-800">Examinations</h3>
                        </div>
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('school.exams') }}" class="block text-red-600 hover:underline">Exams</a>
                            <a href="{{ route('school.grades') }}" class="block text-red-600 hover:underline">Grades</a>
                            <a href="{{ route('school.examSchedule') }}" class="block text-red-600 hover:underline">Exam Schedule</a>
                        </div>
                    </div>
                @endhasFeature
                
                <!-- Library Module -->
                @hasFeature('library_management', $schoolId)
                    <div class="p-4 bg-white rounded-lg shadow border border-gray-200">
                        <div class="flex items-center">
                            <div class="p-2 rounded-md bg-indigo-100 text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-md font-medium text-gray-800">Library</h3>
                        </div>
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('school.books') }}" class="block text-indigo-600 hover:underline">Books</a>
                            <a href="{{ route('school.issueBooks') }}" class="block text-indigo-600 hover:underline">Issue Books</a>
                            <a href="{{ route('school.returnBooks') }}" class="block text-indigo-600 hover:underline">Return Books</a>
                        </div>
                    </div>
                @endhasFeature
            </div>
        </div>
        
        <!-- Upgrade Plan -->
        <div class="mt-6">
            <div class="p-4 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow text-white">
                <h3 class="text-lg font-medium">Need More Features?</h3>
                <p class="mt-2">Upgrade your subscription plan to access more features and increase your limits.</p>
                <div class="mt-4">
                    <a href="#" class="inline-block px-4 py-2 bg-white text-indigo-600 rounded-md font-medium hover:bg-gray-50">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div> --}}
</div>

@include('client.schoolPanel.layout.footer')