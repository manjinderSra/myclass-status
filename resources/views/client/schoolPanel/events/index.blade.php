@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Events</h1>
                <p class="text-sm text-gray-500 mt-1">Manage your school's events and activities</p>
            </div>
            <a href="{{ route('school.events.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add New Event
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow" role="alert">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                <div class="w-full md:w-auto">
                    <div class="inline-flex rounded-md shadow-sm">
                        <a href="{{ route('school.events.index', ['status' => 'upcoming']) }}" 
                           class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 text-sm font-medium {{ request('status', 'upcoming') == 'upcoming' ? 'bg-blue-50 text-blue-600 border-blue-600 z-10' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            Upcoming
                        </a>
                        <a href="{{ route('school.events.index', ['status' => 'ongoing']) }}" 
                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium {{ request('status') == 'ongoing' ? 'bg-blue-50 text-blue-600 border-blue-600 z-10' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Ongoing
                        </a>
                        <a href="{{ route('school.events.index', ['status' => 'completed']) }}" 
                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium {{ request('status') == 'completed' ? 'bg-blue-50 text-blue-600 border-blue-600 z-10' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Completed
                        </a>
                        <a href="{{ route('school.events.index', ['status' => 'cancelled']) }}" 
                           class="relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 text-sm font-medium {{ request('status') == 'cancelled' ? 'bg-blue-50 text-blue-600 border-blue-600 z-10' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Cancelled
                        </a>
                    </div>
                </div>
                
                <div class="relative w-full md:w-64">
                    <button id="programFilterBtn" class="w-full md:w-auto bg-white border border-gray-300 px-4 py-2 rounded-md inline-flex items-center justify-between text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                        <span class="truncate max-w-[200px]">{{ request('program_id') ? $programs->firstWhere('id', request('program_id'))->title : 'Filter by Program' }}</span>
                        <svg class="ml-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div id="programFilterDropdown" class="hidden absolute right-0 mt-2 w-full rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10 max-h-60 overflow-y-auto">
                        <div class="py-1">
                            <a href="{{ route('school.events.index', ['status' => request('status', 'upcoming')]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                All Programs
                            </a>
                            @foreach($programs as $program)
                                <a href="{{ route('school.events.index', ['status' => request('status', 'upcoming'), 'program_id' => $program->id]) }}" 
                                   class="block px-4 py-2 text-sm {{ request('program_id') == $program->id ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                    {{ $program->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(count($events) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="h-40 bg-gray-200 relative">
                            @if($event->image_path)
                                <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="absolute top-3 right-3 flex space-x-2">
                                @php
                                    $statusColors = [
                                        'upcoming' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800', 'dot' => 'bg-indigo-500'],
                                        'ongoing' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'dot' => 'bg-green-500'],
                                        'completed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'dot' => 'bg-blue-500'],
                                        'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'dot' => 'bg-red-500'],
                                    ];
                                    $statusColor = $statusColors[$event->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'dot' => 'bg-gray-500'];
                                @endphp
                                
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColor['bg'] }} {{ $statusColor['text'] }}">
                                    <span class="h-2 w-2 mr-1 {{ $statusColor['dot'] }} rounded-full"></span>
                                    {{ ucfirst($event->status) }}
                                </span>
                                
                                @if($event->is_featured)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Featured
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2 truncate">{{ $event->title }}</h3>
                            
                            <div class="flex flex-col space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $event->event_date->format('M d, Y') }}</span>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $event->start_time ? date('g:i A', strtotime($event->start_time)) : 'N/A' }}</span>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate">{{ $event->location }}</span>
                                </div>
                                
                                @if($event->program)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                        </svg>
                                        <a href="{{ route('school.programs.show', $event->program_id) }}" class="text-blue-600 hover:text-blue-800 truncate">{{ $event->program->title }}</a>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                <a href="{{ route('school.events.show', $event->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                    View Details
                                </a>
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('school.events.edit', $event->id) }}" class="text-indigo-600 hover:text-indigo-800" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    
                                    <form action="{{ route('school.events.destroy', $event->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this event?')" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $events->appends(request()->except('page'))->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-10 text-center">
                <div class="mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-800 mb-2">No events found</h3>
                <p class="text-gray-500 mb-6">Create your first event to share important dates with your school community</p>
                <a href="{{ route('school.events.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add New Event
                </a>
            </div>
        @endif
    </div>
</div>

@include('client.schoolPanel.layout.footer')

<!-- Add Tailwind CSS line clamp plugin -->
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Program filter dropdown toggle
        const programFilterBtn = document.getElementById('programFilterBtn');
        const programFilterDropdown = document.getElementById('programFilterDropdown');
        
        if (programFilterBtn && programFilterDropdown) {
            programFilterBtn.addEventListener('click', function() {
                programFilterDropdown.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!programFilterBtn.contains(event.target) && !programFilterDropdown.contains(event.target)) {
                    programFilterDropdown.classList.add('hidden');
                }
            });
        }
        
        // Add animation to success message
        const successAlert = document.querySelector('.bg-green-100');
        if (successAlert) {
            setTimeout(() => {
                successAlert.classList.add('transition', 'duration-500', 'opacity-0');
                setTimeout(() => {
                    successAlert.remove();
                }, 500);
            }, 3000);
        }
    });
</script>