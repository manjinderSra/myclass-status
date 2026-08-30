@extends('client.student.layouts.master')

@section('title', 'Student Dashboard')

@section('content')
    <!-- Debug Info (Remove in production) -->
    {{-- <div class="bg-yellow-50 border border-yellow-200 rounded p-4 mb-4">
        <h3 class="font-medium text-yellow-800">Debug Information</h3>
        <p>recentComplaints set: {{ isset($recentComplaints) ? 'Yes' : 'No' }}</p>
        @if(isset($recentComplaints))
            <p>Count: {{ $recentComplaints->count() }}</p>
            <ul class="list-disc pl-5 mt-2">
                @foreach($recentComplaints as $complaint)
                    <li>{{ $complaint->complaint_id }} - {{ $complaint->status }}</li>
                @endforeach
            </ul>
        @endif
    </div> --}}
    
    <!-- Welcome Section -->
    <div class="flex items-center justify-between p-4 bg-gray-50 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Student Dashboard</h1>
            <nav class="text-sm text-gray-500 mt-1">
                <span class="text-gray-400">Dashboard /</span>
                <span class="text-gray-700 font-medium">{{ Session::get('student_name', 'Student') }}</span>
            </nav>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('student.leaves') }}" class="flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Apply for Leave
            </a>
            <a href="{{ route('student.profile') }}" class="px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-md hover:bg-gray-200 transition">
                My Profile
            </a>
        </div>
    </div>

    <!-- Quick Stats Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4">
        <!-- Attendance Stat -->
        <div class="bg-white rounded-xl shadow p-4 flex flex-col justify-between hover:-translate-y-1 transition duration-300 ease-in-out">
            <div class="flex justify-between items-start">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">92%</h2>
                        <p class="text-sm text-gray-500">Attendance</p>
                    </div>
                </div>
                <span class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">
                    4.5%
                </span>
            </div>
            <hr class="my-4" />
            <div class="flex justify-between text-sm font-semibold text-gray-600">
                <span>Present : <span class="text-gray-900">45</span></span>
                <span>Absent : <span class="text-gray-900">4</span></span>
            </div>
        </div>

        <!-- Assignments -->
        <div class="bg-white rounded-xl shadow p-4 flex flex-col justify-between hover:-translate-y-1 transition duration-300 ease-in-out">
            <div class="flex justify-between items-start">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-100 text-green-600 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">3</h2>
                        <p class="text-sm text-gray-500">Assignments</p>
                    </div>
                </div>
                <span class="bg-yellow-500 text-white text-xs font-semibold px-2 py-1 rounded">
                    Pending
                </span>
            </div>
            <hr class="my-4" />
            <div class="flex justify-between text-sm font-semibold text-gray-600">
                <span>Completed : <span class="text-gray-900">12</span></span>
                <span>Due this week : <span class="text-gray-900">3</span></span>
            </div>
        </div>

        <!-- Leave Applications -->
        <div class="bg-white rounded-xl shadow p-4 flex flex-col justify-between hover:-translate-y-1 transition duration-300 ease-in-out">
            <div class="flex justify-between items-start">
                <div class="flex items-center space-x-4">
                    <div class="bg-purple-100 text-purple-600 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">1</h2>
                        <p class="text-sm text-gray-500">Leave Requests</p>
                    </div>
                </div>
                <span class="bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded">
                    Active
                </span>
            </div>
            <hr class="my-4" />
            <div class="flex justify-between text-sm font-semibold text-gray-600">
                <span>Approved : <span class="text-gray-900">5</span></span>
                <span>Pending : <span class="text-gray-900">1</span></span>
            </div>
        </div>

        <!-- Announcements -->
        <a href="{{ route('student.announcements') }}" class="bg-white rounded-xl shadow p-4 flex flex-col justify-between hover:-translate-y-1 transition duration-300 ease-in-out">
            <div class="flex justify-between items-start">
                <div class="flex items-center space-x-4">
                    <div class="bg-red-100 text-red-600 p-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $totalAnnouncements }}</h2>
                        <p class="text-sm text-gray-500">Announcements</p>
                    </div>
                </div>
                @if($recentAnnouncements > 0)
                <span class="bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded">
                    New
                </span>
                @endif
            </div>
            <hr class="my-4" />
            <div class="flex justify-between text-sm font-semibold text-gray-600">
                <span>This week : <span class="text-gray-900">{{ $recentAnnouncements }}</span></span>
                <span>Total : <span class="text-gray-900">{{ $totalAnnouncements }}</span></span>
            </div>
        </a>
    </div>

    <!-- Today's Schedule -->
    <div class="grid lg:grid-cols-3 sm:grid-cols-1 p-4 gap-4">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-md shadow">
                <div class="flex items-center justify-between border-b px-4 py-3">
                    <h2 class="text-lg font-bold text-gray-900">Today's Schedule</h2>
                    <a href="#" class="text-blue-600 font-semibold text-sm flex items-center space-x-1">
                        <span>View Full Timetable</span>
                    </a>
                </div>
                <div class="overflow-x-auto p-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-l-md">Time</th>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-r-md">Room</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Sample schedule items -->
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">08:00 - 09:00</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Mathematics</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">Mr. Johnson</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">Room 101</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">09:00 - 10:00</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Science</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">Ms. Smith</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">Lab 202</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">10:15 - 11:15</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">English</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">Mrs. Davis</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">Room 105</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-md shadow h-full">
                <div class="flex items-center justify-between border-b px-4 py-3">
                    <h2 class="text-lg font-bold text-gray-900">Upcoming Events</h2>
                </div>
                <div class="p-4">
                    <div class="border-l-4 border-blue-500 pl-4 mb-4">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="bg-blue-100 text-blue-600 p-2 rounded-md">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Science Exhibition</p>
                                <div class="flex items-center text-sm text-gray-500">
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
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                09:10AM - 02:00PM
                            </div>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">School Event</span>
                        </div>
                    </div>
                    
                    <div class="border-l-4 border-green-500 pl-4">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="bg-green-100 text-green-600 p-2 rounded-md">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Math Competition</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    22 July 2024
                                </div>
                            </div>
                        </div>
                        <hr class="my-3" />
                        <div class="flex justify-between items-center text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                10:00AM - 12:00PM
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Competition</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">
        <!-- Apply for Leave -->
        <div class="bg-white rounded-md shadow p-6 hover:-translate-y-1 transition duration-300 ease-in-out">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 text-blue-600 p-3 rounded-lg mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-800">Apply for Leave</h3>
                    <p class="text-sm text-gray-600">Submit a new leave application</p>
                </div>
            </div>
            <a href="{{ route('student.leaves') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                Apply Now
            </a>
        </div>

        <!-- Submit Complaint -->
        <div class="bg-white rounded-md shadow p-6 hover:-translate-y-1 transition duration-300 ease-in-out">
            <div class="flex items-center mb-4">
                <div class="bg-orange-100 text-orange-600 p-3 rounded-lg mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-800">Submit Complaint</h3>
                    <p class="text-sm text-gray-600">Report an issue or concern</p>
                </div>
            </div>
            
            @if(isset($recentComplaints) && count($recentComplaints) > 0)
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Recent Complaints</h4>
                    <div class="space-y-2 max-h-32 overflow-y-auto">
                        @foreach($recentComplaints as $complaint)
                            <div class="border border-gray-200 rounded p-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="font-medium">{{ $complaint->complaint_id }}</span>
                                    <span class="
                                        @if($complaint->status == 'Pending') text-yellow-600 
                                        @elseif($complaint->status == 'In Progress') text-blue-600 
                                        @elseif($complaint->status == 'Resolved') text-green-600 
                                        @else text-red-600 
                                        @endif text-xs font-medium">
                                        {{ $complaint->status }}
                                    </span>
                                </div>
                                <p class="text-gray-600 truncate">{{ $complaint->description }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('student.complaints') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                        Submit New
                    </a>
                    <a href="{{ route('student.complaints.all') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        View All
                    </a>
                </div>
            @else
                <a href="{{ route('student.complaints') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                    Submit Now
                </a>
            @endif
        </div>

        <!-- View Profile -->
        <div class="bg-white rounded-md shadow p-6 hover:-translate-y-1 transition duration-300 ease-in-out">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 text-green-600 p-3 rounded-lg mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-800">My Profile</h3>
                    <p class="text-sm text-gray-600">View and manage your profile</p>
                </div>
            </div>
            <a href="{{ route('student.profile') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                View Profile
            </a>
        </div>
    </div>
@endsection