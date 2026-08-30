@extends('client.teacher.layout.master')

@section('title', 'Announcements')

@section('content')
    <!-- Page Header -->
    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Announcements</h1>
            <nav class="text-sm text-gray-500 mt-1">
                <span class="text-gray-400">Dashboard /</span>
                <span class="text-blue-600 font-medium">Announcements</span>
            </nav>
        </div>
        {{-- <div>
            <span class="inline-flex rounded-md shadow-sm">
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Mark All as Read
                </button>
            </span>
        </div> --}}
    </div>

    <!-- Announcements List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="p-6">
            @if(isset($notices) && count($notices) > 0)
                <div class="space-y-6">
                    @foreach($notices as $notice)
                        <div class="border-l-4 border-blue-500 bg-gray-50 p-4 rounded-r-lg shadow-sm hover:shadow transition">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $notice->title }}</h3>
                                <span class="text-sm text-gray-500">{{ $notice->publish_date->format('j M Y') }}</span>
                            </div>
                            
                            <p class="text-gray-600 mb-3">{{ $notice->message }}</p>
                            
                            <div class="flex items-center text-xs text-gray-500">
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    From: {{ $notice->created_by_name ?? 'Admin' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Pagination -->
                    @if(isset($notices) && method_exists($notices, 'links'))
                    <div class="mt-6">
                        {{ $notices->links() }}
                    </div>
                    @endif
                </div>
            @else
                <div class="text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No announcements yet</h3>
                    <p class="mt-1 text-sm text-gray-500">There are no announcements for you at this time.</p>
                </div>
            @endif
        </div>
    </div>
@endsection 