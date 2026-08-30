@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <!-- Back Button and Ticket ID -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('school.supportTickets.index') }}" class="flex items-center text-blue-600 hover:text-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Tickets
                    </a>
                </div>
                <div class="flex items-center">
                    <span class="text-sm text-gray-600">Ticket ID:</span>
                    <span class="ml-2 px-3 py-1 text-sm font-medium text-gray-800 bg-gray-100 rounded-md">{{ $ticket['id'] }}</span>
                </div>
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
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="border-b border-gray-200 px-6 py-4">
                <h1 class="text-xl font-semibold text-gray-800">{{ $ticket['subject'] }}</h1>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="block text-gray-500 font-medium">Requester</span>
                    <span class="block text-gray-800">{{ $ticket['requester'] }}</span>
                    <span class="block text-gray-500">{{ $ticket['requester_email'] }}</span>
                    <span class="block text-gray-500">{{ $ticket['requester_role'] }}</span>
                </div>
                
                <div>
                    <span class="block text-gray-500 font-medium">Status</span>
                    <form action="{{ route('school.supportTickets.updateStatus', $ticket['id']) }}" method="POST" class="inline-block">
                        @csrf
                        <select name="status" onchange="this.form.submit()" class="mt-1 block w-full py-1 px-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="Open" {{ $ticket['status'] == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="In Progress" {{ $ticket['status'] == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Resolved" {{ $ticket['status'] == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="Closed" {{ $ticket['status'] == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </form>
                </div>
                
                <div>
                    <span class="block text-gray-500 font-medium">Priority</span>
                    <span class="block mt-1">
                        @if($ticket['priority'] == 'High')
                            <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                {{ $ticket['priority'] }}
                            </span>
                        @elseif($ticket['priority'] == 'Medium')
                            <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ $ticket['priority'] }}
                            </span>
                        @else
                            <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $ticket['priority'] }}
                            </span>
                        @endif
                    </span>
                </div>
                
                <div>
                    <span class="block text-gray-500 font-medium">Created</span>
                    <span class="block text-gray-800">{{ $ticket['created_at'] }}</span>
                    <span class="block text-gray-500 font-medium mt-2">Last Updated</span>
                    <span class="block text-gray-800">{{ $ticket['last_updated'] }}</span>
                </div>
            </div>
        </div>
        
        <!-- Conversation Thread -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Conversation</h2>
            </div>
            
            <div class="px-6 py-4 space-y-6">
                @foreach($ticket['messages'] as $message)
                    <div class="flex {{ $message['sender_role'] == 'School' ? 'justify-end' : 'justify-start' }}">
                        <div class="{{ $message['sender_role'] == 'School' ? 'order-2 ml-4' : 'order-1 mr-4' }}">
                            <div class="h-10 w-10 rounded-full {{ $message['sender_role'] == 'School' ? 'bg-blue-100 text-blue-700' : ($message['sender_role'] == 'Teacher' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700') }} flex items-center justify-center font-semibold">
                                T
                            </div>
                        </div>
                        <div class="{{ $message['sender_role'] == 'School' ? 'order-1 text-right' : 'order-2' }} flex-grow max-w-lg">
                            <div class="flex items-center {{ $message['sender_role'] == 'School' ? 'justify-end' : 'justify-start' }}">
                                <div>
                                    <span class="text-sm font-semibold text-gray-900">Teacher</span>
                                    <span class="ml-2 px-2 py-0.5 rounded-full text-xs {{ $message['sender_role'] == 'School' ? 'bg-blue-100 text-blue-700' : ($message['sender_role'] == 'Teacher' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700') }}">
                                        {{ $message['sender_role'] }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500 ml-2">{{ $message['timestamp'] }}</span>
                            </div>
                            <div class="mt-2 text-sm text-gray-800 {{ $message['sender_role'] == 'School' ? 'bg-blue-50 border-blue-200' : ($message['sender_role'] == 'Teacher' ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200') }} border rounded-lg p-4 shadow-sm">
                                <p class="whitespace-pre-line">{{ $message['content'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Reply Form -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Add Reply</h2>
            </div>
            
            <div class="p-6">
                <form action="{{ route('school.supportTickets.addReply', $ticket['id']) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <textarea name="message" rows="4" class="shadow-sm block w-full focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md" placeholder="Type your reply here..."></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Send Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer') 