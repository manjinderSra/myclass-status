@extends('client.teacher.layout.master')

@section('title', 'Teacher Profile')

@section('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #4268F6 0%, #2E4BB8 100%);
        border-radius: 0.5rem 0.5rem 0 0;
        padding: 2rem 1.5rem;
    }
    
    .profile-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        background-color: #f0f4f8;
        overflow: hidden;
    }
    
    .profile-tab.active {
        color: #4268F6;
        border-color: #4268F6;
    }
    
    .info-card {
        background-color: #f8fafc;
        border-radius: 0.5rem;
        padding: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border-left: 3px solid #4268F6;
        margin-bottom: 1rem;
    }
    
    .info-label {
        color: #64748b;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        color: #1e293b;
        font-weight: 500;
    }
    
    .tab-button {
        position: relative;
        padding: 0.75rem 1.25rem;
        font-weight: 500;
        border-bottom: 2px solid transparent;
        color: #64748b;
        transition: all 0.2s;
    }
    
    .tab-button:hover {
        color: #4268F6;
    }
    
    .tab-button.active {
        color: #4268F6;
        border-bottom-color: #4268F6;
    }
    
    .tab-button.active:after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #4268F6;
    }
    
    .section-title {
        color: #1e293b;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .action-button {
        background-color: #4268F6;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: background-color 0.2s;
    }
    
    .action-button:hover {
        background-color: #3451C6;
    }
</style>
@endsection

@section('content')
<!-- Success Message Banner -->
@if(session('success'))
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 rounded-md">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button onclick="this.parentElement.parentElement.parentElement.parentElement.remove()" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
                    <span class="sr-only">Dismiss</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Error Message Banner -->
@if($errors->any())
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-md">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
            <div class="mt-2 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button onclick="this.parentElement.parentElement.parentElement.parentElement.remove()" class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600">
                    <span class="sr-only">Dismiss</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<div class="bg-white rounded-lg shadow-md overflow-hidden" x-data="{ activeTab: 'personal' }">
    <!-- Profile Header -->
    <div class="profile-header text-white">
        <div class="flex flex-col md:flex-row items-center">
            <div class="relative mb-4 md:mb-0 md:mr-6">
                <div class="profile-image">
                    <img src="{{ $teacher->profile_image ? asset('storage/' . $teacher->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($teacher->first_name . ' ' . $teacher->last_name) . '&background=4268F6&color=fff&size=120' }}" 
                         alt="Profile Photo" 
                         class="w-full h-full object-cover"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($teacher->first_name . ' ' . $teacher->last_name) }}&background=4268F6&color=fff&size=120'">
                </div>
                <button @click="$refs.imageUploadModal.classList.remove('hidden')" class="absolute bottom-0 right-0 bg-white rounded-full p-2 text-blue-600 hover:bg-gray-100 transition-colors shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
            <div class="text-center md:text-left">
                <h1 class="text-2xl font-bold">{{ $teacher->first_name }} {{ $teacher->last_name }}</h1>
                <p class="text-blue-100">Employee ID: {{ $teacher->employee_id }}</p>
                <p class="text-blue-100">{{ $teacher->subject }}</p>
                
                <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <svg class="mr-1.5 h-2 w-2 text-blue-400" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        {{ $teacher->status ?? 'Active' }}
                    </span>
                    
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                            <circle cx="4" cy="4" r="3" />
                        </svg>
                        {{ $teacher->contract_type ?? 'Full Time' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Profile Image Upload Modal -->
    <div x-ref="imageUploadModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('teacher.profile.updateImage') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Update Profile Picture</h3>
                                <div class="mt-2">
                                    <div class="mb-4">
                                        <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-2">Choose an image</label>
                                        <input type="file" id="profile_image" name="profile_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <p class="mt-1 text-sm text-gray-500">JPG, PNG, or GIF up to 2MB</p>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <div class="aspect-w-16 aspect-h-9">
                                            <img id="preview-image" src="{{ $teacher->profile_image ? asset('storage/' . $teacher->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($teacher->first_name . ' ' . $teacher->last_name) . '&background=4268F6&color=fff&size=200' }}" alt="Preview" class="object-cover rounded-lg mx-auto" style="max-height: 200px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Upload Picture
                        </button>
                        <button type="button" @click="$refs.imageUploadModal.classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Profile Navigation -->
    <div class="border-b">
        <div class="flex overflow-x-auto">
            <button @click="activeTab = 'personal'" 
                    :class="{'active': activeTab === 'personal'}"
                    class="tab-button">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Personal Details
                </span>
            </button>
            <button @click="activeTab = 'education'" 
                    :class="{'active': activeTab === 'education'}"
                    class="tab-button">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                    Education
                </span>
            </button>
            <button @click="activeTab = 'employment'" 
                    :class="{'active': activeTab === 'employment'}"
                    class="tab-button">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Employment
                </span>
            </button>
            <button @click="activeTab = 'account'" 
                    :class="{'active': activeTab === 'account'}"
                    class="tab-button">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Account
                </span>
            </button>
        </div>
    </div>
    
    <!-- Profile Content -->
    <div class="p-6">
        <!-- Personal Details Section -->
        <div x-show="activeTab === 'personal'">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="section-title">Basic Information</h3>
                    <div class="space-y-4">
                        <div class="info-card">
                            <div class="info-label">Full Name</div>
                            <div class="info-value">{{ $teacher->first_name }} {{ $teacher->last_name }}</div>
                        </div>
                        <div class="info-card">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">{{ $teacher->email }}</div>
                        </div>
                        <div class="info-card">
                            <div class="info-label">Gender</div>
                            <div class="info-value">{{ $teacher->gender }}</div>
                        </div>
                        <div class="info-card">
                            <div class="info-label">Date of Birth</div>
                            <div class="info-value">{{ $teacher->date_of_birth ? $teacher->date_of_birth->format('d M Y') : 'Not available' }}</div>
                        </div>
                        <div class="info-card">
                            <div class="info-label">Blood Group</div>
                            <div class="info-value">{{ $teacher->blood_group ?? 'Not available' }}</div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="section-title">Contact Information</h3>
                    <div class="space-y-4 mb-6">
                        <div class="info-card">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value">{{ $teacher->primary_contact ?? 'Not available' }}</div>
                        </div>
                        <div class="info-card">
                            <div class="info-label">Current Address</div>
                            <div class="info-value">{{ $teacher->current_address ?? 'Not available' }}</div>
                        </div>
                        <div class="info-card">
                            <div class="info-label">Permanent Address</div>
                            <div class="info-value">{{ $teacher->permanent_address ?? 'Not available' }}</div>
                        </div>
                    </div>
                    
                    <h3 class="section-title">Family Information</h3>
                    <div class="space-y-4">
                        <div class="info-card">
                            <div class="info-label">Father's Name</div>
                            <div class="info-value">{{ $teacher->father_name ?? 'Not available' }}</div>
                        </div>
                        <div class="info-card">
                            <div class="info-label">Mother's Name</div>
                            <div class="info-value">{{ $teacher->mother_name ?? 'Not available' }}</div>
                        </div>
                        <div class="info-card">
                            <div class="info-label">Marital Status</div>
                            <div class="info-value">{{ $teacher->marital_status ?? 'Not available' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-8">
                <button class="action-button flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Personal Details
                </button>
            </div>
        </div>
        
        <!-- Education & Qualifications Section -->
        <div x-show="activeTab === 'education'">
            <h3 class="section-title">Educational Qualifications</h3>
            
            @if ($teacher->qualification)
                @php
                    // Parse the qualification text to display in a structured way
                    $qualifications = explode(',', $teacher->qualification);
                @endphp
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    @foreach($qualifications as $qualification)
                        <div class="info-card">
                            <div class="font-medium text-gray-800">{{ $qualification }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-blue-50 p-4 rounded-lg mb-6 border-l-4 border-blue-500">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">No qualification information available.</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="mt-8">
                <button class="action-button flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Add Qualification
                </button>
            </div>
        </div>
        
        <!-- Employment History Section -->
        <div x-show="activeTab === 'employment'">
            <h3 class="section-title">Current Employment</h3>
            
            <div class="bg-blue-50 p-5 rounded-lg mb-6 border-l-4 border-blue-500">
                <div class="flex justify-between mb-2">
                    <h4 class="font-medium text-gray-800">{{ $teacher->subject }}</h4>
                    <span class="text-gray-500">{{ $teacher->date_of_joining ? $teacher->date_of_joining->format('M Y') : '' }} - Present</span>
                </div>
                <p class="text-gray-600 mb-3">{{ $teacher->school->name ?? 'Current School' }}</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="bg-white p-3 rounded-md shadow-sm">
                        <div class="text-sm text-gray-500">Contract Type</div>
                        <div class="font-medium">{{ $teacher->contract_type ?? 'Not specified' }}</div>
                    </div>
                    <div class="bg-white p-3 rounded-md shadow-sm">
                        <div class="text-sm text-gray-500">Work Shift</div>
                        <div class="font-medium">{{ $teacher->work_shift ?? 'Not specified' }}</div>
                    </div>
                    <div class="bg-white p-3 rounded-md shadow-sm">
                        <div class="text-sm text-gray-500">Work Location</div>
                        <div class="font-medium">{{ $teacher->work_location ?? 'Not specified' }}</div>
                    </div>
                </div>
            </div>
            
            @if ($teacher->work_experience || $teacher->previous_school)
                <h3 class="section-title">Previous Employment</h3>
                
                @if ($teacher->previous_school)
                    <div class="bg-gray-50 p-4 rounded-lg mb-6 shadow-sm">
                        <div class="flex justify-between mb-2">
                            <h4 class="font-medium text-gray-800">Teacher</h4>
                        </div>
                        <p class="text-gray-600 mb-3">{{ $teacher->previous_school }}</p>
                        @if ($teacher->previous_school_address || $teacher->previous_school_phone)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($teacher->previous_school_address)
                                <div>
                                    <div class="text-sm text-gray-500">Address</div>
                                    <div class="font-medium">{{ $teacher->previous_school_address }}</div>
                                </div>
                                @endif
                                
                                @if($teacher->previous_school_phone)
                                <div>
                                    <div class="text-sm text-gray-500">Phone</div>
                                    <div class="font-medium">{{ $teacher->previous_school_phone }}</div>
                                </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
                
                @if ($teacher->work_experience)
                    <div class="bg-gray-50 p-4 rounded-lg mb-6 shadow-sm">
                        <div class="mb-2">
                            <h4 class="font-medium text-gray-800">Work Experience</h4>
                        </div>
                        <p class="text-gray-600">{{ $teacher->work_experience }}</p>
                    </div>
                @endif
            @endif
            
            <div class="mt-8">
                <button class="action-button flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Add Employment Record
                </button>
            </div>
        </div>
        
        <!-- Account Settings Section -->
        <div x-show="activeTab === 'account'">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="section-title">Update Password</h3>
                    <form class="bg-gray-50 p-5 rounded-lg">
                        <div class="mb-4">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Enter your current password">
                        </div>
                        
                        <div class="mb-4">
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Enter new password">
                        </div>
                        
                        <div class="mb-6">
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Confirm new password">
                        </div>
                        
                        <button type="submit" class="action-button flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            Update Password
                        </button>
                    </form>
                </div>
                
                <div>
                    <h3 class="section-title">Bank Information</h3>
                    <div class="bg-gray-50 p-5 rounded-lg mb-6">
                        <div class="space-y-4">
                            <div>
                                <div class="text-sm text-gray-500">Bank Name</div>
                                <div class="font-medium">{{ $teacher->bank_name ?? 'Not available' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Branch</div>
                                <div class="font-medium">{{ $teacher->branch ?? 'Not available' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">IFSC Number</div>
                                <div class="font-medium">{{ $teacher->ifsc_number ?? 'Not available' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="section-title">Leave Information</h3>
                    <div class="bg-gray-50 p-5 rounded-lg">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-500">Medical Leaves</div>
                                <div class="font-medium">{{ $teacher->medical_leaves ?? 'Not specified' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Casual Leaves</div>
                                <div class="font-medium">{{ $teacher->casual_leaves ?? 'Not specified' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Maternity Leaves</div>
                                <div class="font-medium">{{ $teacher->maternity_leaves ?? 'Not specified' }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Sick Leaves</div>
                                <div class="font-medium">{{ $teacher->sick_leaves ?? 'Not specified' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('tabs', () => ({
            activeTab: 'personal',
            setActiveTab(tab) {
                this.activeTab = tab;
            }
        }));
    });

    // Preview image before upload
    document.getElementById('profile_image').onchange = function(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const preview = document.getElementById('preview-image');
            preview.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    };
</script>
@endsection 