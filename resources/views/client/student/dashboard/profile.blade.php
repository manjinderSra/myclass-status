@extends('client.student.layouts.master')

@section('title', 'My Profile')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">My Profile</h2>
        <p class="text-gray-600 mb-6">View and manage your personal information and academic details.</p>
        
        <div class="flex flex-col md:flex-row">
            <!-- Profile Image Section -->
            <div class="w-full md:w-1/3 mb-6 md:mb-0 md:pr-6">
                <div class="flex flex-col items-center">
                    <div class="w-48 h-48 rounded-full overflow-hidden bg-gray-100 mb-4">
                        @if(Session::has('student_profile_image'))
                            <img src="{{ asset('storage/' . Session::get('student_profile_image')) }}" alt="Profile Image" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <h3 class="text-lg font-medium text-gray-800">{{ Session::get('student_name', 'Student Name') }}</h3>
                    <p class="text-sm text-gray-500">{{ Session::get('student_admission_number', 'Admission #') }}</p>
                    <p class="text-sm text-gray-500">{{ Session::get('student_class', 'Class') }} - {{ Session::get('student_section', 'Section') }}</p>
                </div>
            </div>
            
            <!-- Student Details Section -->
            <div class="w-full md:w-2/3">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3 pb-2 border-b">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Date of Birth</p>
                            <p class="text-base font-medium text-gray-800">{{ Session::get('student_dob', 'Not Available') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Gender</p>
                            <p class="text-base font-medium text-gray-800">{{ Session::get('student_gender', 'Not Available') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Blood Group</p>
                            <p class="text-base font-medium text-gray-800">{{ Session::get('student_blood_group', 'Not Available') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-base font-medium text-gray-800">{{ Session::get('student_email', 'Not Available') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Contact Number</p>
                            <p class="text-base font-medium text-gray-800">{{ Session::get('student_contact', 'Not Available') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Address</p>
                            <p class="text-base font-medium text-gray-800">{{ Session::get('student_address', 'Not Available') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3 pb-2 border-b">Academic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Academic Year</p>
                            <p class="text-base font-medium text-gray-800">{{ Session::get('student_academic_year', 'Not Available') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Admission Date</p>
                            <p class="text-base font-medium text-gray-800">{{ Session::get('student_admission_date', 'Not Available') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Roll Number</p>
                            <p class="text-base font-medium text-gray-800">{{ Session::get('student_roll_number', 'Not Available') }}</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-3 pb-2 border-b">Parent/Guardian Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Father's Name</p>
                            <p class="text-base font-medium text-gray-800">{{ Session::get('student_father_name', 'Not Available') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Father's Contact</p>
                            <p class="text-base font-medium text-gray-800">{{ Session::get('student_father_contact', 'Not Available') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Mother's Name</p>
                            <p class="text-base font-medium text-gray-800">{{ Session::get('student_mother_name', 'Not Available') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Mother's Contact</p>
                            <p class="text-base font-medium text-gray-800">{{ Session::get('student_mother_contact', 'Not Available') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Guardian's Name</p>
                            <p class="text-base font-medium text-gray-800">{{ Session::get('student_guardian_name', 'Not Available') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Guardian's Contact</p>
                            <p class="text-base font-medium text-gray-800">{{ Session::get('student_guardian_contact', 'Not Available') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Password Change Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Change Password</h3>
        <form method="POST" action="{{ route('student.updatePassword') }}" class="space-y-4">
            @csrf
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                <input type="password" name="current_password" id="current_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input type="password" name="new_password" id="new_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update Password
                </button>
            </div>
        </form>
    </div>
@endsection 