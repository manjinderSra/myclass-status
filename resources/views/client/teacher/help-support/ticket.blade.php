@extends('client.teacher.layout.master')

@section('title', 'Support Ticket: ' . $ticketDetails['subject'])

@section('content')
    <!-- Page Header -->
    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Ticket: {{ $ticketDetails['formatted_id'] }}</h1>
            <nav class="text-sm text-gray-500 mt-1">
                <span class="text-gray-400">Dashboard /</span>
                <a href="{{ route('teacher.help-support') }}" class="text-indigo-600 hover:text-indigo-800">Help & Support</a>
                <span class="text-gray-400"> / </span>
                <span class="text-gray-700 font-medium">Ticket Details</span>
            </nav>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Ticket Details -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">{{ $ticketDetails['subject'] }}</h2>
                    <div class="flex flex-col md:flex-row md:items-center mt-2 space-y-1 md:space-y-0">
                        <span class="text-sm text-gray-500 md:mr-4">Created: {{ $ticketDetails['created_at'] }}</span>
                        <span class="text-sm text-gray-500">Updated: {{ $ticketDetails['updated_at'] }}</span>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex flex-wrap items-center gap-2">
                    @if($ticketDetails['status'] == 'Open')
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            {{ $ticketDetails['status'] }}
                        </span>
                    @elseif($ticketDetails['status'] == 'Closed')
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                            {{ $ticketDetails['status'] }}
                        </span>
                    @elseif($ticketDetails['status'] == 'Reopened')
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            {{ $ticketDetails['status'] }}
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            {{ $ticketDetails['status'] }}
                        </span>
                    @endif
                    
                    @if($ticketDetails['priority'] == 'High')
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                            {{ $ticketDetails['priority'] }} Priority
                        </span>
                    @elseif($ticketDetails['priority'] == 'Medium')
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            {{ $ticketDetails['priority'] }} Priority
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            {{ $ticketDetails['priority'] }} Priority
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Conversation Thread -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Conversation</h2>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                @foreach($ticketDetails['messages'] as $message)
                    <div class="flex {{ $message['sender_type'] == 'Teacher' ? 'justify-end' : 'justify-start' }}">
                        <div class="{{ $message['sender_type'] == 'Teacher' ? 'order-2 ml-4' : 'order-1 mr-4' }}">
                            <div class="h-10 w-10 rounded-full {{ $message['sender_type'] == 'Teacher' ? 'bg-indigo-100 text-indigo-700' : ($message['sender_type'] == 'School' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }} flex items-center justify-center font-semibold">
                                {{ substr($message['sender_type'], 0, 1) }}
                            </div>
                        </div>
                        <div class="{{ $message['sender_type'] == 'Teacher' ? 'order-1 text-right' : 'order-2' }} flex-grow max-w-lg">
                            <div class="flex items-center {{ $message['sender_type'] == 'Teacher' ? 'justify-end' : 'justify-start' }}">
                                <div>
                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ $message['sender_type'] }}
                                    </span>
                                    <span class="ml-2 px-2 py-0.5 rounded-full text-xs {{ $message['sender_type'] == 'Teacher' ? 'bg-indigo-100 text-indigo-700' : ($message['sender_type'] == 'School' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                                        {{ $message['sender_type'] == 'Teacher' ? 'You' : 'Support Team' }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500 ml-2">{{ $message['created_at'] }}</span>
                            </div>
                            <div class="mt-2 text-sm text-gray-800 {{ $message['sender_type'] == 'Teacher' ? 'bg-indigo-50 border-indigo-200' : ($message['sender_type'] == 'School' ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200') }} border rounded-lg p-4 shadow-sm">
                                <p class="whitespace-pre-line">{{ $message['message'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($ticketDetails['status'] != 'Closed')
                <!-- Reply Form -->
                <form action="{{ route('teacher.help-support.reply', $ticketDetails['id']) }}" method="POST" class="mt-8 border-t border-gray-200 pt-6">
                    @csrf
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Your Reply</label>
                        <textarea id="message" name="message" rows="4" class="shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md" placeholder="Type your reply here..."></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Send Reply
                        </button>
                    </div>
                </form>
            @else
                <div class="mt-8 border-t border-gray-200 pt-6 text-center">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-600 text-sm">This ticket is closed. To reopen, please submit a new reply.</p>
                        <form action="{{ route('teacher.help-support.reply', $ticketDetails['id']) }}" method="POST" class="mt-4">
                            @csrf
                            <div class="mb-4">
                                <textarea id="message" name="message" rows="4" class="shadow-sm block w-full focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md" placeholder="Type your message to reopen this ticket..."></textarea>
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reopen Ticket
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Back to Help Center -->
    <div class="mt-6 text-center">
        <a href="{{ route('teacher.help-support') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Support Center
        </a>
    </div>
@endsection 