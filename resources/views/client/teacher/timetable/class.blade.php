@extends("client.teacher.layout.master")
@section("title", "Class Timetable")

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $timetable->class_name }} - {{ $timetable->section ? $timetable->section->name : 'Unknown' }} Timetable</h2>
                <p class="text-gray-600 mt-1">View the complete timetable for this class</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('teacher.timetable') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Back to My Schedule
                </a>
                <button id="openDayModal" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    View Day
                </button>
            </div>
        </div>
        
        <!-- Error Message -->
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif
        
        @if(isset($groupedPeriods) && count($groupedPeriods) > 0)
            <!-- Today's Timetable -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
                <div class="bg-blue-50 px-6 py-4 border-b border-blue-100">
                    <h3 class="text-lg font-semibold text-gray-800">Today's Schedule ({{ $today }})</h3>
                </div>
                <div class="p-6">
                    @if(isset($groupedPeriods[$today]) && count($groupedPeriods[$today]) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($groupedPeriods[$today]->sortBy('time_from') as $period)
                                    <tr class="{{ $period->teacher == $teacher->id ? 'bg-blue-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $period->time_from }} - {{ $period->time_to }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($period->period_type == 'regular')
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Class</span>
                                            @elseif($period->period_type == 'break')
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $period->name }}</span>
                                            @else
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">{{ $period->name }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($period->period_type == 'regular')
                                                {{ $period->subjectRelation->name ?? 'N/A' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($period->teacherRelation)
                                                <div class="flex items-center">
                                                    {{ $period->teacherRelation->first_name . ' ' . $period->teacherRelation->last_name }}
                                                    @if($period->teacher == $teacher->id)
                                                        <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">You</span>
                                                    @endif
                                                </div>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No classes scheduled for today.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Weekly Timetable -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($daysOrder as $day)
                    @if(isset($groupedPeriods[$day]) && count($groupedPeriods[$day]) > 0)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden {{ $day == $today ? 'ring-2 ring-blue-500' : '' }}">
                            <div class="bg-gray-50 px-6 py-3 border-b">
                                <h3 class="text-base font-semibold text-gray-800">{{ $day }}</h3>
                            </div>
                            <div class="p-4">
                                <div class="space-y-3">
                                    @foreach($groupedPeriods[$day]->sortBy('time_from') as $period)
                                        <div class="p-3 rounded-md {{ $period->period_type == 'regular' ? 'bg-green-50 border-l-4 border-green-500' : ($period->period_type == 'break' ? 'bg-yellow-50 border-l-4 border-yellow-500' : 'bg-purple-50 border-l-4 border-purple-500') }} {{ $period->teacher == $teacher->id ? 'ring-2 ring-blue-400' : '' }}">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-900">
                                                        @if($period->period_type == 'regular')
                                                            {{ $period->subjectRelation->name ?? 'Subject N/A' }}
                                                        @else
                                                            {{ $period->name }}
                                                        @endif
                                                    </h4>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        @if($period->teacherRelation)
                                                            Teacher: {{ $period->teacherRelation->first_name . ' ' . $period->teacherRelation->last_name }}
                                                            @if($period->teacher == $teacher->id)
                                                                <span class="ml-1 px-1 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">You</span>
                                                            @endif
                                                        @else
                                                            Teacher: N/A
                                                        @endif
                                                    </p>
                                                </div>
                                                <span class="text-xs font-medium text-gray-500">
                                                    {{ $period->time_from }} - {{ $period->time_to }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded" role="alert">
                <p>No timetable available for this class. Please contact your school administrator.</p>
            </div>
        @endif
    </div>
    
    <!-- Day Selection Modal -->
    <div id="dayModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 md:mx-auto">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Select a Day</h3>
                <button id="closeDayModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($daysOrder as $day)
                    <button class="day-button w-full p-4 text-left rounded-md border {{ $day == $today ? 'bg-blue-50 border-blue-500 text-blue-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50' }}" data-day="{{ $day }}">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 {{ $day == $today ? 'text-blue-500' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $day }}</span>
                        </div>
                        @if($day == $today)
                            <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Today</span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dayModal = document.getElementById('dayModal');
        const openDayModalBtn = document.getElementById('openDayModal');
        const closeDayModalBtn = document.getElementById('closeDayModal');
        const dayButtons = document.querySelectorAll('.day-button');
        
        function openModal() {
            dayModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal() {
            dayModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        openDayModalBtn.addEventListener('click', openModal);
        closeDayModalBtn.addEventListener('click', closeModal);
        
        // Close modal when clicking outside
        dayModal.addEventListener('click', function(e) {
            if (e.target === dayModal) {
                closeModal();
            }
        });
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !dayModal.classList.contains('hidden')) {
                closeModal();
            }
        });
        
        // Day selection functionality
        dayButtons.forEach(button => {
            button.addEventListener('click', function() {
                const day = this.getAttribute('data-day');
                const dayCards = document.querySelectorAll('.bg-white.rounded-lg.shadow-sm');
                
                dayCards.forEach(card => {
                    const heading = card.querySelector('h3');
                    if (heading && heading.textContent.trim() === day) {
                        closeModal();
                        setTimeout(() => {
                            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 300);
                    }
                });
            });
        });
    });
</script>
@endsection
