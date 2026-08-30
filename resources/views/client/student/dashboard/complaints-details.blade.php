@extends('client.student.layouts.master')

@section('title', 'Complaint Details')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Complaint Details</h1>
            <div class="flex space-x-2">
                <a href="{{ route('student.complaints.all') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    View All Complaints
                </a>
                <a href="{{ route('student.complaints') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Submit New Complaint
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if(isset($complaint))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Complaint Information -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-gray-800">Complaint Information</h2>
                            <span class="px-4 py-1 rounded-full text-sm font-medium
                                @if($complaint->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($complaint->status == 'in_progress') bg-blue-100 text-blue-800
                                @elseif($complaint->status == 'resolved') bg-green-100 text-green-800
                                @elseif($complaint->status == 'rejected') bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                            </span>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <dl>
                                <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Complaint ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $complaint->complaint_id }}</dd>
                                </div>
                                <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Nature</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $complaint->nature }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Submitted On</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $complaint->created_at->format('d M Y, h:i A') }}</dd>
                                </div>
                                <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $complaint->updated_at->format('d M Y, h:i A') }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            @if($complaint->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($complaint->status == 'in_progress') bg-blue-100 text-blue-800
                                            @elseif($complaint->status == 'resolved') bg-green-100 text-green-800
                                            @elseif($complaint->status == 'rejected') bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    <!-- Complaint Description -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Complaint Description</h3>
                        <div class="p-4 bg-gray-50 rounded-md text-gray-700">
                            <p class="whitespace-pre-line">{{ $complaint->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- School Response -->
                <div>
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">School Response</h3>
                        
                        @if($complaint->status == 'resolved' || $complaint->status == 'rejected')
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $complaint->response }}</p>
                            </div>
                            
                            @if($complaint->resolved_at)
                                <div class="text-sm text-gray-500 mt-4">
                                    <p>Responded on: {{ $complaint->resolved_at->format('d M Y, h:i A') }}</p>
                                    @if($complaint->resolver)
                                        <p class="mt-1">Responded by: {{ $complaint->resolver->name }}</p>
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="bg-gray-100 p-4 rounded-md text-center text-gray-500">
                                <p>No response yet. Your complaint is {{ $complaint->status }}.</p>
                                <p class="text-sm mt-2">The school administration will review your complaint soon.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="flex flex-col items-center justify-center py-6 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-lg font-medium">Complaint not found</p>
                    <p class="mt-1">The complaint you're looking for doesn't exist or you don't have permission to view it.</p>
                    <a href="{{ route('student.complaints') }}" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Go to Complaint Box
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection 