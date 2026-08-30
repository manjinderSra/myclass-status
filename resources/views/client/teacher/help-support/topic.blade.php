@extends('client.teacher.layout.master')

@section('title', $topicDetails['title'])

@section('content')
    <!-- Page Header -->
    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $topicDetails['title'] }}</h1>
            <nav class="text-sm text-gray-500 mt-1">
                <span class="text-gray-400">Dashboard /</span>
                <a href="{{ route('teacher.help-support') }}" class="text-indigo-600 hover:text-indigo-800">Help & Support</a>
                <span class="text-gray-400"> / </span>
                <span class="text-gray-700 font-medium">{{ $topicDetails['title'] }}</span>
            </nav>
        </div>
        <div class="hidden md:block">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Last updated: {{ $topicDetails['last_updated'] }}
            </span>
        </div>
    </div>

    <!-- Topic Content -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="p-6">
            <div class="mb-4 md:hidden">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Last updated: {{ $topicDetails['last_updated'] }}
                </span>
            </div>
            <article class="prose prose-indigo max-w-none prose-headings:text-indigo-900 prose-a:text-indigo-600 prose-a:no-underline hover:prose-a:underline prose-img:rounded-lg">
                {!! $topicDetails['content'] !!}
            </article>
        </div>
    </div>

    <!-- Helpful? Rating Section -->
    {{-- <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="p-6">
            <div class="text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Was this article helpful?</h3>
                <div class="flex justify-center space-x-4">
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                        </svg>
                        Yes, it helped
                    </button>
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2" />
                        </svg>
                        No, need more info
                    </button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Related Topics -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Related Topics</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @forelse($topicDetails['related_topics'] as $relatedTopic)
                    <a href="{{ route('teacher.help-support.topic', $relatedTopic->slug) }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-indigo-50 transition-colors duration-200 border border-gray-200 hover:border-indigo-200">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900 group-hover:text-indigo-900">{{ $relatedTopic->title }}</h3>
                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($relatedTopic->description, 50) }}</p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-3 text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No related topics</h3>
                        <p class="mt-1 text-sm text-gray-500">There are no related topics available for this article.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Need More Help? -->
    <div class="bg-indigo-50 shadow rounded-lg overflow-hidden mb-6">
        <div class="p-6">
            <div class="text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Still need help?</h3>
                <p class="text-sm text-gray-600 mb-4">If you couldn't find what you were looking for, submit a ticket to our support team.</p>
                <a href="{{ route('teacher.help-support') }}#open-ticket-btn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    Submit a Support Ticket
                </a>
            </div>
        </div>
    </div>

    <!-- Back to Help Center -->
    <div class="mt-6 text-center">
        <a href="{{ route('teacher.help-support') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Help Center
        </a>
    </div>
@endsection 