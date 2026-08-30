@extends('client.student.layouts.master')

@section('title', 'Leave Application Details')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Leave Application Details</h2>
            <a href="{{ route('student.leaves') }}" class="text-blue-500 hover:text-blue-700">← Back to Applications</a>
        </div>
        
        @if(isset($leave))
        <div class="border-b pb-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
                <div>
                    <span class="text-sm text-gray-500">Leave ID:</span>
                    <span class="ml-2 text-base font-medium text-gray-800">{{ $leave->leave_id }}</span>
                </div>
                <div>
                    <span class="text-sm font-medium">Status:</span>
                    @if($leave->status == 'pending')
                        <span class="ml-2 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                    @elseif($leave->status == 'approved')
                        <span class="ml-2 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                    @else
                        <span class="ml-2 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                    @endif
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <span class="text-sm text-gray-500">Applied Date:</span>
                    <span class="ml-2 text-base text-gray-800">{{ date('d M Y, h:i A', strtotime($leave->created_at)) }}</span>
                </div>
                @if($leave->processed_at)
                <div>
                    <span class="text-sm text-gray-500">Processed Date:</span>
                    <span class="ml-2 text-base text-gray-800">{{ date('d M Y, h:i A', strtotime($leave->processed_at)) }}</span>
                </div>
                @endif
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-medium text-gray-700 mb-3">Leave Details</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Reason</p>
                        <p class="text-base font-medium text-gray-800">{{ $leave->reason }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Duration</p>
                        <p class="text-base font-medium text-gray-800">
                            {{ date('d M Y', strtotime($leave->from_date)) }} - {{ date('d M Y', strtotime($leave->to_date)) }}
                            <span class="text-sm text-gray-500 ml-2">({{ $leave->leave_days }} days)</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Description</p>
                        <p class="text-base text-gray-800">{{ $leave->description }}</p>
                    </div>
                    @if($leave->attachment_path)
                    <div>
                        <p class="text-sm text-gray-500">Attachment</p>
                        <a href="{{ asset('storage/' . $leave->attachment_path) }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            View Attachment
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-700 mb-3">Application Status</h3>
                <div class="space-y-4">
                    @if($leave->status != 'pending')
                    <div>
                        <p class="text-sm text-gray-500">Processed By</p>
                        <p class="text-base font-medium text-gray-800">{{ $leave->processor ? $leave->processor->name : 'School Admin' }}</p>
                    </div>
                    @endif
                    
                    @if($leave->admin_remarks)
                    <div>
                        <p class="text-sm text-gray-500">Admin Remarks</p>
                        <p class="text-base text-gray-800">{{ $leave->admin_remarks }}</p>
                    </div>
                    @endif
                    
                    <div class="mt-6">
                        <div class="relative pt-8">
                            <div class="flex items-center mb-6">
                                <div class="z-10 flex items-center justify-center w-8 h-8 bg-blue-200 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <div class="flex-1 ml-4">
                                    <h3 class="text-sm font-medium text-gray-700">Application Submitted</h3>
                                    <p class="text-xs text-gray-500">{{ date('d M Y, h:i A', strtotime($leave->created_at)) }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center mb-6">
                                <div class="z-10 flex items-center justify-center w-8 h-8 {{ $leave->status != 'pending' ? 'bg-blue-200' : 'bg-gray-200' }} rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 {{ $leave->status != 'pending' ? 'text-blue-600' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </div>
                                <div class="flex-1 ml-4">
                                    <h3 class="text-sm font-medium {{ $leave->status != 'pending' ? 'text-gray-700' : 'text-gray-400' }}">Application Reviewed</h3>
                                    <p class="text-xs {{ $leave->status != 'pending' ? 'text-gray-500' : 'text-gray-400' }}">
                                        {{ $leave->processed_at ? date('d M Y, h:i A', strtotime($leave->processed_at)) : 'Pending' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="z-10 flex items-center justify-center w-8 h-8 {{ $leave->status == 'approved' || $leave->status == 'rejected' ? ($leave->status == 'approved' ? 'bg-green-200' : 'bg-red-200') : 'bg-gray-200' }} rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 {{ $leave->status == 'approved' ? 'text-green-600' : ($leave->status == 'rejected' ? 'text-red-600' : 'text-gray-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="flex-1 ml-4">
                                    <h3 class="text-sm font-medium {{ $leave->status == 'approved' || $leave->status == 'rejected' ? 'text-gray-700' : 'text-gray-400' }}">
                                        {{ $leave->status == 'approved' ? 'Application Approved' : ($leave->status == 'rejected' ? 'Application Rejected' : 'Decision Pending') }}
                                    </h3>
                                    <p class="text-xs {{ $leave->status == 'approved' || $leave->status == 'rejected' ? 'text-gray-500' : 'text-gray-400' }}">
                                        {{ $leave->processed_at && ($leave->status == 'approved' || $leave->status == 'rejected') ? date('d M Y, h:i A', strtotime($leave->processed_at)) : 'Awaiting decision' }}
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Timeline line -->
                            <div class="absolute top-0 left-4 w-0.5 h-full bg-gray-200 -z-10"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-800 mb-2">Leave Application Not Found</h3>
            <p class="text-gray-600">The leave application you're looking for doesn't exist or you don't have access to view it.</p>
            <a href="{{ route('student.leaves') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                View All Applications
            </a>
        </div>
        @endif
    </div>
@endsection 