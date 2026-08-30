@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<style>
    .swal2-container {
        z-index: 999999 !important;
    }
    .active-tab {
        border-color: #2563eb !important;
        color: #2563eb !important;
    }
    .extra-rows-container {
        max-height: 240px;
        overflow-y: auto;
        scrollbar-width: none;
    }
    .extra-rows-container::-webkit-scrollbar {
        display: none;
    }
</style>

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        {{-- Page Header --}}
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Academics / <span class="text-l text-gray-500">Timetable</span>
            </h1>
        </div>

      {{-- Header Section for Timetable --}}
<div class="bg-white rounded-lg shadow w-full p-6 transition-all duration-300">
    <div class="flex items-center justify-between mb-3">
        <div class="text-xl font-semibold text-gray-800">
            Timetable Management
            <p class="text-sm text-gray-500 mt-1">View and manage school timetables</p>
        </div>

        {{-- Right section: Filter Button and Add Timetable Button --}}
        <div class="flex items-center space-x-3">
            {{-- Filter Button --}}
            <button id="openFilterTimetableModal" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V19l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Filter
            </button>
            {{-- Add Timetable Button --}}
            <button id="openAddTimetableModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Add Timetable +
            </button>
        </div>
    </div>
</div>
{{-- End Header Section --}}

{{-- Timetable Grid View --}}
<div class="bg-white rounded-xl shadow-lg w-full p-6 mt-6 overflow-x-auto">
    {{-- Timetable Header Info --}}
    <div class="timetable-header-info flex justify-between items-center mb-4">
        <div class="text-lg font-semibold text-gray-800" id="timetableHeaderText">
            @if($timetables->isNotEmpty() && $timetables->first()->periods->isNotEmpty())
                {{ $timetables->first()->class_name }} - Section {{ $timetables->first()->section->name ?? 'N/A' }}
            @else
                Select a class and section to view timetable
            @endif
        </div>
       
        {{-- Delete Button - Only show when timetable exists --}}
        {{-- Delete Button - Always render, controlled by JavaScript --}}
<button
    id="deleteTimetableBtn"
    data-class-name=""
    data-section-id=""
    class="bg-gray-400 text-white text-sm font-medium px-4 py-2 rounded-lg cursor-not-allowed opacity-50"
    disabled>
    <svg class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
    </svg>
    Delete Timetable
</button>

    </div>

    {{-- Rest of your timetable content goes here --}}







            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-4 py-2 font-semibold">Time Table</th>
                        <th class="px-4 py-2 font-semibold">Monday</th>
                        <th class="px-4 py-2 font-semibold">Tuesday</th>
                        <th class="px-4 py-2 font-semibold">Wednesday</th>
                        <th class="px-4 py-2 font-semibold">Thursday</th>
                        <th class="px-4 py-2 font-semibold">Friday</th>
                        <th class="px-4 py-2 font-semibold">Saturday</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        
                        // Organize periods by time slots
                        $timeSlotMap = [];
                        
                        if(isset($timetables) && $timetables->count() > 0 && $timetables->first()->periods) {
                            foreach($timetables->first()->periods as $period) {
                                // Format time key
                                $timeKey = date('h:i A', strtotime($period->time_from)) . ' - ' . date('h:i A', strtotime($period->time_to));
                                
                                if(!isset($timeSlotMap[$timeKey])) {
                                    $timeSlotMap[$timeKey] = [
                                        'time_from' => $period->time_from,
                                        'days' => []
                                    ];
                                }
                                
                                $timeSlotMap[$timeKey]['days'][$period->day] = $period;
                            }
                            
                            // Sort by time
                            uasort($timeSlotMap, function($a, $b) {
                                return strtotime($a['time_from']) - strtotime($b['time_from']);
                            });
                        }
                    @endphp

                    @forelse($timeSlotMap as $timeSlot => $slot)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap font-medium text-gray-900">{{ $timeSlot }}</td>
                            
                            @foreach($days as $day)
                                <td class="px-4 py-2">
                                    @php
                                        $period = $slot['days'][$day] ?? null;
                                    @endphp
                                    
                                    @if($period)
                                        @php
                                            // Determine background color
                                            $bgColor = 'bg-gray-100';
                                            if($period->subject) {
                                                $subjectName = strtolower($period->subject->name ?? '');
                                                if(str_contains($subjectName, 'math')) $bgColor = 'bg-blue-100';
                                                elseif(str_contains($subjectName, 'computer')) $bgColor = 'bg-green-100';
                                                elseif(str_contains($subjectName, 'physics')) $bgColor = 'bg-yellow-100';
                                                elseif(str_contains($subjectName, 'english')) $bgColor = 'bg-purple-100';
                                                elseif(str_contains($subjectName, 'spanish')) $bgColor = 'bg-pink-100';
                                                elseif(str_contains($subjectName, 'chemistry')) $bgColor = 'bg-red-100';
                                            }
                                        @endphp
                                        
                                        <div class="p-2 rounded-md {{ $bgColor }}">
                                            <p class="text-xs text-gray-700 font-semibold mb-1">
                                                {{ date('h:i A', strtotime($period->time_from)) }}
                                            </p>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $period->subject->name ?? 'No Subject' }}
                                            </p>
                                            <p class="text-xs text-gray-600">
                                                {{ $period->teacher->name ?? 'No Teacher' }}
                                            </p>
                                            <div class="flex justify-end mt-1 space-x-1">
@php
    $isRegular = isset($period->period_type) 
                 && strtolower($period->period_type) === 'regular';
@endphp

@if ($isRegular)
    <button class="text-blue-500 hover:text-blue-700 text-xs editTimetableEntryBtn"
            data-id="{{ $period->id }}">
        Edit
    </button>
@else
    <button class="text-gray-400 cursor-not-allowed text-xs" disabled>
        Edit
    </button>
@endif

                                                {{-- <button class="text-red-500 hover:text-red-700 text-xs deleteTimetableEntryBtn" data-id="{{ $period->id }}">Delete</button> --}}
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-2 text-center text-gray-400 text-xs">
                                            No Class
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900">No timetable entries found</p>
                                    <p class="text-sm text-gray-400 mt-1">Please select a class and section or add a new timetable</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Legend for Breaks --}}
        <div class="flex justify-center space-x-6 mt-8 text-sm">
            <div class="flex items-center">
                <span class="w-4 h-4 rounded-full bg-blue-600 mr-2"></span>
                <span>Morning Break</span>
                <span class="ml-2 text-gray-600">09:00 to 10:45 AM</span>
            </div>
            <div class="flex items-center">
                <span class="w-4 h-4 rounded-full bg-orange-500 mr-2"></span>
                <span>Lunch</span>
                <span class="ml-2 text-gray-600">12:00 to 01:00 PM</span>
            </div>
            <div class="flex items-center">
                <span class="w-4 h-4 rounded-full bg-teal-500 mr-2"></span>
                <span>Evening Break</span>
                <span class="ml-2 text-gray-600">03:00 to 03:45 PM</span>
            </div>
        </div>
        {{-- Filter Timetable Modal --}}
        <div id="filterTimetableModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg max-w-sm w-full p-6 relative">
                <button id="closeFilterTimetableModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h2 class="text-xl font-semibold mb-6">Filter</h2>
                <form id="filterTimetableForm">
                    <div class="mb-4">
                        <label for="filterClass" class="block text-gray-700 text-sm font-bold mb-2">Class</label>
                        <select id="filterClass" name="filterClass" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Select</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="filterSection" class="block text-gray-700 text-sm font-bold mb-2">Section</label>
                        <select id="filterSection" name="filterSection" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" id="resetFilterTimetableBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                            Reset
                        </button>
                        <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                            Apply
                        </button>
                    </div>
                </form>
            </div>
        </div>
        {{-- Add Timetable Modal --}}
        <div id="addTimetableModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[9999]">
            <div class="bg-white rounded-lg max-w-4xl w-full p-6 relative max-h-[90vh] overflow-y-auto">
                <button id="closeAddTimetableModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 z-10">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h2 class="text-xl font-semibold mb-4">
                    Add Time Table 
                    <span class="text-red-600 text-sm font-normal">— Duplicate records for the same class and section are not allowed.</span>
                </h2>
                
                <form id="addTimetableForm" action="{{ route('school.timetable.store') }}" method="POST">
                    @csrf
                    
                    {{-- Class and Section Selection --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="addTimetableClass" class="block text-gray-700 text-sm font-bold mb-2">Class</label>
                            <select id="addTimetableClass" name="class_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div>
                            <label for="section" class="block text-sm font-semibold text-gray-700 mb-1">Section</label>
                            <select id="section" name="section_id" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base transition duration-150 ease-in-out bg-white" required>
                                <option value="">Select Section</option>
                            </select>
                        </div>
                    </div>

                    {{-- Day Tabs --}}
                    <div class="mb-6">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8" aria-label="Tabs" id="dayTabs">
                                <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active-tab" data-day="Monday">
                                    Monday
                                </button>
                                <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-day="Tuesday">
                                    Tuesday
                                </button>
                                <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-day="Wednesday">
                                    Wednesday
                                </button>
                                <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-day="Thursday">
                                    Thursday
                                </button>
                                <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-day="Friday">
                                    Friday
                                </button>
                                <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-day="Saturday">
                                    Saturday
                                </button>
                            </nav>
                        </div>

                        {{-- Day Content Panels --}}
                        <div id="dayContent" class="mt-4">
                            {{-- Monday Panel --}}
                            <div class="day-panel" id="panel-Monday">
                                <div class="extra-rows-container"></div>
                                <div class="flex space-x-4 mt-4">
                                    <button type="button" class="add-new-period-row bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add New
                                    </button>
                                    <button type="button" class="add-extra-row bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Extra
                                    </button>
                                </div>
                            </div>

                            {{-- Tuesday Panel --}}
                            <div class="day-panel hidden" id="panel-Tuesday">
                                <div class="extra-rows-container"></div>
                                <div class="flex space-x-4 mt-4">
                                    <button type="button" class="add-new-period-row bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add New
                                    </button>
                                    <button type="button" class="add-extra-row bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Extra
                                    </button>
                                </div>
                            </div>

                            {{-- Wednesday Panel --}}
                            <div class="day-panel hidden" id="panel-Wednesday">
                                <div class="extra-rows-container"></div>
                                <div class="flex space-x-4 mt-4">
                                    <button type="button" class="add-new-period-row bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add New
                                    </button>
                                    <button type="button" class="add-extra-row bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Extra
                                    </button>
                                </div>
                            </div>

                            {{-- Thursday Panel --}}
                            <div class="day-panel hidden" id="panel-Thursday">
                                <div class="extra-rows-container"></div>
                                <div class="flex space-x-4 mt-4">
                                    <button type="button" class="add-new-period-row bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add New
                                    </button>
                                    <button type="button" class="add-extra-row bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Extra
                                    </button>
                                </div>
                            </div>

                            {{-- Friday Panel --}}
                            <div class="day-panel hidden" id="panel-Friday">
                                <div class="extra-rows-container"></div>
                                <div class="flex space-x-4 mt-4">
                                    <button type="button" class="add-new-period-row bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add New
                                    </button>
                                    <button type="button" class="add-extra-row bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Extra
                                    </button>
                                </div>
                            </div>

                            {{-- Saturday Panel --}}
                            <div class="day-panel hidden" id="panel-Saturday">
                                <div class="extra-rows-container"></div>
                                <div class="flex space-x-4 mt-4">
                                    <button type="button" class="add-new-period-row bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add New
                                    </button>
                                    <button type="button" class="add-extra-row bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                                        <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Extra
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex justify-end space-x-4">
                        <label class="inline-flex items-center mr-auto">
                            <input type="checkbox" id="applyExtraToAll" class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="ml-2 text-sm text-gray-700">Apply extra to all</span>
                        </label>
                        <button type="button" id="cancelAddTimetableBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                            Add Time Table
                        </button>
                    </div>
                </form>
            </div>
        </div>
        {{-- Edit Timetable Entry Modal --}}
        <div id="editTimetableEntryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg max-w-2xl w-full p-6 relative">
                <button id="closeEditTimetableEntryModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <h2 class="text-xl font-semibold mb-6">Edit Timetable Entry</h2>
                
                <form id="editTimetableEntryForm">
                    @csrf
                    {{-- @method('PUT') --}}
                    <input type="hidden" id="editEntryId" name="entry_id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="editDay" class="block text-gray-700 text-sm font-bold mb-2">Day</label>
                            <select id="editDay" name="day" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">Select Day</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                            </select>
                        </div>
                        <div>
                            <label for="editSubject" class="block text-gray-700 text-sm font-bold mb-2">Subject</label>
                            <select id="editSubject" name="subject" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">Select Subject</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="editTeacher" class="block text-gray-700 text-sm font-bold mb-2">Teacher</label>
                            <select id="editTeacher" name="teacher" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">Select Teacher</option>
                            </select>
                        </div>
                        <div>
                            <label for="editTimeFrom" class="block text-gray-700 text-sm font-bold mb-2">Time From</label>
                            <input type="time" id="editTimeFrom" name="time_from" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="editTimeTo" class="block text-gray-700 text-sm font-bold mb-2">Time To</label>
                        <input type="time" id="editTimeTo" name="time_to" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    
                    <div class="flex justify-end space-x-4">
                        <button type="button" id="cancelEditTimetableEntryBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                            Update Entry
                        </button>
                    </div>
                </form>
            </div>
        </div>
        {{-- Delete Confirmation Modal --}}
        <div id="deleteTimetableEntryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
                <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
                <p class="text-gray-700 mb-6">Are you sure you want to delete this timetable entry? This action cannot be undone.</p>
                <div class="flex justify-end space-x-4">
                    <button type="button" id="closeDeleteTimetableEntryModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                        Cancel
                    </button>
                    <button id="confirmDeleteTimetableEntryBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>

        {{-- Extra Field Confirm Modal (Apply to All Days) --}}
        <div id="extraFieldConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[99999]">
            <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
                <h2 class="text-xl font-semibold mb-4">Apply to All Days?</h2>
                <p class="text-gray-700 mb-6">Would you like to apply this extra field (like break or lunch) to all days of the week?</p>
                <div class="flex justify-end space-x-4">
                    <button type="button" id="cancelExtraToAllBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                        No, Just This Day
                    </button>
                    <button id="confirmExtraToAllBtn" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                        Yes, Apply to All
                    </button>
                </div>
            </div>
        </div>

        {{-- Regular Period Confirm Modal (Apply to All Days) --}}
        <div id="regularPeriodConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[99999]">
            <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
                <h2 class="text-xl font-semibold mb-4">Apply to All Days?</h2>
                <p class="text-gray-700 mb-6">Would you like to apply this regular period to all days of the week?</p>
                <div class="flex justify-end space-x-4">
                    <button type="button" id="cancelRegularToAllBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">
                        No, Just This Day
                    </button>
                    <button id="confirmRegularToAllBtn" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                        Yes, Apply to All
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

@include('client.schoolPanel.layout.footer')
<script>
$(document).ready(function() {
    // Global Variables
    let classes = [];
    let subjects = [];
    let teachers = [];
    let currentExtraDay = null;
    let currentRegularDay = null;
    let timetableEntryIdToDelete = null;
    let currentEditEntryId = null;

    // ==================== INITIALIZATION ====================
    
    // Fetch all necessary data on page load
    function initializePage() {
        showLoadingSwal('Loading...', 'Please wait while we load the data...');
        
        Promise.all([
            fetchClasses(),
            fetchSubjects(),
            fetchTeachers()
        ]).then(() => {
            Swal.close();
            
            // Load saved filter from localStorage
            const savedFilter = localStorage.getItem('filterTimetable');
            if (savedFilter) {
                const filterData = JSON.parse(savedFilter);
                if (filterData.className && filterData.sectionId) {
                    setTimeout(function() {
                        $('#filterClass').val(filterData.className);
                        populateSectionDropdown(filterData.className, $('#filterSection'));
                        $('#filterSection').val(filterData.sectionId);
                        loadTimetable(filterData.className, filterData.sectionId);
                    }, 500);
                }
            } else if (classes.length > 0 && classes[0].section) {
                // Auto-load first class and section
                loadTimetable(classes[0].name, classes[0].section.id);
            }
        }).catch((error) => {
            Swal.close();
            console.error('Error initializing page:', error);
            showErrorSwal('Failed to load necessary data. Please refresh the page.');
        });
    }

    // ==================== API CALLS ====================
    
    // Fetch Classes
    function fetchClasses() {
        return $.ajax({
            url: '{{ route("school.api.active-classes") }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success && data.classes) {
                    classes = data.classes;
                    populateClassDropdowns();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching classes:', error);
                showErrorSwal('Failed to fetch classes');
            }
        });
    }

    // Fetch Subjects
    function fetchSubjects() {
        return $.ajax({
            url: '{{ route("school.api.active-subjects") }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    subjects = data.subjects;
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching subjects:', error);
                showErrorSwal('Failed to fetch subjects');
            }
        });
    }

    // Fetch Teachers
    function fetchTeachers() {
        return $.ajax({
            url: '{{ route("school.api.active-teachers") }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    teachers = data.teachers;
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching teachers:', error);
                showErrorSwal('Failed to fetch teachers');
            }
        });
    }

    // Load Timetable based on filter
    function loadTimetable(className, sectionId) {
        $.ajax({
            url: '{{ route("school.timetable.filter") }}',
            type: 'GET',
            data: {
                filterClass: className,
                filterSection: sectionId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    updateTimetableDisplay(response.data);
                    updateTimetableHeader(className, sectionId);
                } else {
                    showErrorSwal(response.message || 'Failed to load timetable');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading timetable:', error);
                showErrorSwal('An error occurred while loading the timetable');
            }
        });
    }

    // ==================== HELPER FUNCTIONS ====================
    
    // Show Loading SweetAlert
    function showLoadingSwal(title, text) {
        Swal.fire({
            title: title,
            text: text,
            didOpen: () => {
                Swal.showLoading();
            },
            allowOutsideClick: false
        });
    }

    // Show Success SweetAlert
    function showSuccessSwal(message, callback) {
        Swal.fire({
            title: 'Success!',
            text: message,
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (callback && typeof callback === 'function') {
                callback();
            }
        });
    }

    // Show Error SweetAlert
    function showErrorSwal(message) {
        Swal.fire({
            title: 'Error',
            html: message,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }

    // Populate Class Dropdowns
    function populateClassDropdowns() {
        let uniqueClasses = {};
        classes.forEach(function(item) {
            if (item && item.name) {
                if (!uniqueClasses[item.name]) {
                    uniqueClasses[item.name] = item.id;
                }
            }
        });

        // Populate Filter Class Dropdown
        let filterClassOptions = '<option value="">Select Class</option>';
        Object.keys(uniqueClasses).forEach(function(className) {
            filterClassOptions += `<option value="${className}">${className}</option>`;
        });
        $('#filterClass').html(filterClassOptions);

        // Populate Add Timetable Class Dropdown
        let addClassOptions = '<option value="">Select Class</option>';
        Object.keys(uniqueClasses).forEach(function(className) {
            const classId = uniqueClasses[className];
            addClassOptions += `<option value="${classId}">${className}</option>`;
        });
        $('#addTimetableClass').html(addClassOptions);
    }

    // Populate Section Dropdown based on selected class
    function populateSectionDropdown(className, sectionDropdown) {
        sectionDropdown.html('<option value="">Select Section</option>');
        
        if (className) {
            let sections = [];
            classes.forEach(function(item) {
                if (item.name === className && item.section) {
                    sections.push(item.section);
                }
            });

            // Remove duplicates
            let uniqueSections = [];
            let sectionIds = new Set();
            sections.forEach(function(section) {
                if (!sectionIds.has(section.id)) {
                    sectionIds.add(section.id);
                    uniqueSections.push(section);
                }
            });

            // Populate dropdown
            uniqueSections.forEach(function(section) {
                sectionDropdown.append(`<option value="${section.id}">${section.name}</option>`);
            });
        }
    }

    // Populate Subject Dropdown
    function populateSubjectDropdown(selectElement) {
        selectElement.html('<option value="">Select Subject</option>');
        
        subjects.forEach(function(subject) {
            selectElement.append(`<option value="${subject.id}">${subject.name}</option>`);
        });

        // When subject changes, populate teacher dropdown
        selectElement.off('change').on('change', function() {
            const subjectId = $(this).val();
            const selectId = $(this).attr('id');
            
            // Extract day and index from select id
            const dayPeriodMatch = selectId.match(/^(\w+)Subject(\d*)$/);
            if (dayPeriodMatch) {
                const day = dayPeriodMatch[1];
                const periodIndex = dayPeriodMatch[2];
                const teacherSelect = $(`#${day}Teacher${periodIndex}`);
                populateTeacherDropdown(teacherSelect, subjectId);
            }
        });
    }

    // Populate Teacher Dropdown based on subject
    function populateTeacherDropdown(teacherSelect, subjectId) {
        teacherSelect.html('<option value="">Select Teacher</option>');
        
        if (!subjectId) {
            return;
        }

        // Filter teachers by subject
        const relevantTeachers = teachers.filter(t => t.subject_id == subjectId);

        if (relevantTeachers.length > 0) {
            relevantTeachers.forEach(function(teacher) {
                teacherSelect.append(`<option value="${teacher.id}">${teacher.name}</option>`);
            });
        } else {
            teacherSelect.append('<option value="" disabled>No teachers available for this subject</option>');
        }
    }

    // ✅ UPDATED: Update Timetable Header with button enable/disable logic
    function updateTimetableHeader(className, sectionId) {
        let sectionName = "Unknown Section";
        classes.forEach(function(classData) {
            if (classData.name === className && classData.section && classData.section.id == sectionId) {
                sectionName = classData.section.name;
            }
        });
        
        // Update header text
        $('#timetableHeaderText').html(`${className} - ${sectionName}`);
        
        // Update delete button data attributes and enable it
        const deleteBtn = $('#deleteTimetableBtn');
        deleteBtn.attr('data-class-name', className);
        deleteBtn.attr('data-section-id', sectionId);
        
        // Enable the delete button and update styling
        deleteBtn.prop('disabled', false);
        deleteBtn.removeClass('bg-gray-400 cursor-not-allowed opacity-50');
        deleteBtn.addClass('bg-red-600 hover:bg-red-700 cursor-pointer');
    }

    // ✅ NEW: Disable Delete Button when no timetable exists
    function disableDeleteButton() {
        const deleteBtn = $('#deleteTimetableBtn');
        deleteBtn.attr('data-class-name', '');
        deleteBtn.attr('data-section-id', '');
        deleteBtn.prop('disabled', true);
        deleteBtn.removeClass('bg-red-600 hover:bg-red-700 cursor-pointer');
        deleteBtn.addClass('bg-gray-400 cursor-not-allowed opacity-50');
    }

    // Get Subject Color based on name
    function getSubjectColor(subjectName) {
        if (!subjectName) return 'bg-gray-100';
        
        const name = subjectName.toLowerCase();
        if (name.includes('math')) return 'bg-blue-100';
        if (name.includes('computer')) return 'bg-green-100';
        if (name.includes('physics')) return 'bg-yellow-100';
        if (name.includes('english')) return 'bg-purple-100';
        if (name.includes('spanish')) return 'bg-pink-100';
        if (name.includes('chemistry')) return 'bg-red-100';
        
        return 'bg-gray-100';
    }

    // ✅ UPDATED: Update Timetable Display with button disable logic
    function updateTimetableDisplay(timetableData) {
        const timetableBody = $('table tbody');
        timetableBody.empty();
        

        if (timetableData.length === 0) {
            // ✅ Disable delete button when no timetable exists
            disableDeleteButton();
            
            timetableBody.append(`
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-lg font-medium text-gray-900">No timetable entries found</p>
                            <p class="text-sm text-gray-400 mt-1">Please add a new timetable for the selected class and section</p>
                        </div>
                    </td>
                </tr>
            `);
            return;
        }

        // Organize periods by time slots
        const timeSlotMap = {};
        timetableData.forEach(function(period) {
            const timeKey = `${period.start_time} - ${period.end_time}`;
            
            if (!timeSlotMap[timeKey]) {
                timeSlotMap[timeKey] = {
                    timeSlot: timeKey,
                    days: {
                        'Monday': null,
                        'Tuesday': null,
                        'Wednesday': null,
                        'Thursday': null,
                        'Friday': null,
                        'Saturday': null
                    }
                };
            }
            
            timeSlotMap[timeKey].days[period.day] = period;
        });

        // Sort time slots by start time
        const timeSlots = Object.values(timeSlotMap).sort((a, b) => {
            const timeA = a.timeSlot.split(' - ')[0];
            const timeB = b.timeSlot.split(' - ')[0];
            return timeA.localeCompare(timeB);
        });

        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Build table rows
        timeSlots.forEach(function(slot) {
            let row = `<tr><td class="px-4 py-2 whitespace-nowrap font-medium text-gray-900">${slot.timeSlot}</td>`;
            
            days.forEach(function(day) {
                const period = slot.days[day];
                
                if (period) {
                    if (period.period_type === 'regular') {
                        // Regular period with subject and teacher
                        const teacherName = teachers.find(t => t.id == period.teacher)?.name || period.teacher;
                        const subjectName = subjects.find(s => s.id == period.subject)?.name || period.subject;
                        const bgColor = getSubjectColor(subjectName);
                        
                        row += `
                            <td class="px-4 py-2">
                                <div class="p-2 rounded-md ${bgColor}">
                                    <p class="text-xs text-gray-700 font-semibold mb-1">${slot.timeSlot.split(' - ')[0]}</p>
                                    <p class="text-sm font-medium text-gray-900">${subjectName}</p>
                                    <p class="text-xs text-gray-600">${teacherName}</p>
                                    <div class="flex justify-end mt-1 space-x-1">
                                        <button class="text-blue-500 hover:text-blue-700 text-xs editTimetableEntryBtn" data-id="${period.id}">Edit</button>
                                    </div>
                                </div>
                            </td>
                        `;
                    } else {
                        // Extra period (break, lunch, etc.)
                        row += `
                            <td class="px-4 py-2">
                                <div class="p-2 rounded-md bg-gray-100">
                                    <p class="text-xs text-gray-700 font-semibold mb-1">${slot.timeSlot.split(' - ')[0]}</p>
                                    <p class="text-sm font-medium text-gray-900">${period.name}</p>
                                    <div class="flex justify-end mt-1 space-x-1">
                                        <button class="text-blue-500 hover:text-blue-700 text-xs editTimetableEntryBtn" data-id="${period.id}">Edit</button>
                                    </div>
                                </div>
                            </td>
                        `;
                    }
                } else {
                    // No class
                    row += `
                        <td class="px-4 py-2">
                            <div class="p-2 text-center text-gray-400 text-xs">No Class</div>
                        </td>
                    `;
                }
            });
            
            row += `</tr>`;
            timetableBody.append(row);
        });
    }

    // ==================== MODAL EVENT HANDLERS ====================
    
    // Open Filter Modal
    $('#openFilterTimetableModal').on('click', function() {
        $('#filterTimetableModal').removeClass('hidden');
    });

    // Close Filter Modal
    $('#closeFilterTimetableModal').on('click', function() {
        $('#filterTimetableModal').addClass('hidden');
    });

    // Filter Class Change
    $('#filterClass').on('change', function() {
        const selectedClass = $(this).val();
        populateSectionDropdown(selectedClass, $('#filterSection'));
    });

    // Filter Form Submit
    $('#filterTimetableForm').on('submit', function(e) {
        e.preventDefault();
        
        const className = $('#filterClass').val();
        const sectionId = $('#filterSection').val();
        
        if (!className || !sectionId) {
            showErrorSwal('Please select both class and section');
            return;
        }

        // Save filter to localStorage
        localStorage.setItem('filterTimetable', JSON.stringify({
            className: className,
            sectionId: sectionId
        }));

        loadTimetable(className, sectionId);
        $('#filterTimetableModal').addClass('hidden');
    });

    // Reset Filter
    $('#resetFilterTimetableBtn').on('click', function() {
        $('#filterTimetableForm')[0].reset();
        $('#filterSection').html('<option value="">Select</option>');
    });

    // Open Add Timetable Modal
    $('#openAddTimetableModal').on('click', function() {
        // Reset form
        $('#addTimetableForm')[0].reset();
        $('#applyExtraToAll').prop('checked', false);
        
        // Clear all day panels
        $('.day-panel .extra-rows-container').empty();
        
        $('#addTimetableModal').removeClass('hidden');
    });

    // Close Add Timetable Modal
    $('#closeAddTimetableModal, #cancelAddTimetableBtn').on('click', function() {
        $('#addTimetableModal').addClass('hidden');
    });

    // Add Timetable Class Change
    $('#addTimetableClass').on('change', function() {
        const selectedClassId = $(this).val();
        const selectedClassName = $(this).find('option:selected').text();
        populateSectionDropdown(selectedClassName, $('#section'));
    });

    // Day Tab Navigation
    $('#dayTabs').on('click', '.tab-button', function() {
        const selectedDay = $(this).data('day');
        
        // Remove active class from all tabs
        $('#dayTabs .tab-button').removeClass('active-tab border-blue-600 text-blue-600');
        $('#dayTabs .tab-button').addClass('border-transparent text-gray-500');
        
        // Hide all day panels
        $('#dayContent .day-panel').addClass('hidden');
        
        // Add active class to clicked tab
        $(this).addClass('active-tab border-blue-600 text-blue-600');
        $(this).removeClass('border-transparent text-gray-500');
        
        // Show selected day panel
        $(`#panel-${selectedDay}`).removeClass('hidden');
    });

    // Add New Period Row (Regular)
    $(document).on('click', '.add-new-period-row', function() {
        const day = $(this).closest('.day-panel').attr('id').replace('panel-', '');
        currentRegularDay = day;
        $('#regularPeriodConfirmModal').removeClass('hidden');
    });

    // Add Extra Row (Break/Lunch)
    $(document).on('click', '.add-extra-row', function() {
        const day = $(this).closest('.day-panel').attr('id').replace('panel-', '');
        currentExtraDay = day;
        $('#extraFieldConfirmModal').removeClass('hidden');
    });

    // Confirm Add Regular Period to All Days
    $('#confirmRegularToAllBtn').on('click', function() {
        $('#regularPeriodConfirmModal').addClass('hidden');
        $('#applyExtraToAll').prop('checked', true);
        addPeriodRow(currentRegularDay, false);
        currentRegularDay = null;
    });

    // Cancel Add Regular Period to All Days
    $('#cancelRegularToAllBtn').on('click', function() {
        $('#regularPeriodConfirmModal').addClass('hidden');
        $('#applyExtraToAll').prop('checked', false);
        addPeriodRow(currentRegularDay, false);
        currentRegularDay = null;
    });

    // Confirm Add Extra to All Days
    $('#confirmExtraToAllBtn').on('click', function() {
        $('#extraFieldConfirmModal').addClass('hidden');
        $('#applyExtraToAll').prop('checked', true);
        addPeriodRow(currentExtraDay, true);
        currentExtraDay = null;
    });

    // Cancel Add Extra to All Days
    $('#cancelExtraToAllBtn').on('click', function() {
        $('#extraFieldConfirmModal').addClass('hidden');
        $('#applyExtraToAll').prop('checked', false);
        addPeriodRow(currentExtraDay, true);
        currentExtraDay = null;
    });

    // Remove Period Row
    $(document).on('click', '.remove-period-row', function() {
        $(this).closest('.timetable-period-row').remove();
    });

    // Add Period Row Function
    function addPeriodRow(day, isExtra = false) {
        const currentDayPanel = $(`#panel-${day}`);
        const currentRowCount = currentDayPanel.find('.timetable-period-row').length;
        const newIndex = isExtra ? `extra${currentRowCount}` : currentRowCount;

        let newRow;

        if (isExtra) {
            // Extra row for breaks, lunch, etc.
            newRow = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end mb-4 timetable-period-row">
                    <div>
                        <label for="${day}Name${newIndex}" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                        <input type="text" id="${day}Name${newIndex}" name="periods[${day}][${newIndex}][name]" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                            placeholder="Break, Lunch, etc.">
                    </div>
                    <div>
                        <label for="${day}TimeFrom${newIndex}" class="block text-gray-700 text-sm font-bold mb-2">Time From</label>
                        <input type="time" id="${day}TimeFrom${newIndex}" name="periods[${day}][${newIndex}][time_from]" 
                            class="time-from-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-full">
                            <label for="${day}TimeTo${newIndex}" class="block text-gray-700 text-sm font-bold mb-2">Time To</label>
                            <input type="time" id="${day}TimeTo${newIndex}" name="periods[${day}][${newIndex}][time_to]" 
                                class="time-to-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <button type="button" class="remove-period-row text-red-500 hover:text-red-700 mt-8">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            `;
        } else {
            // Regular period row
            newRow = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end mb-4 timetable-period-row">
                    <div>
                        <label for="${day}Subject${newIndex}" class="block text-gray-700 text-sm font-bold mb-2">Subject</label>
                        <select id="${day}Subject${newIndex}" name="periods[${day}][${newIndex}][subject]" 
                            class="subject-select shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div>
                        <label for="${day}Teacher${newIndex}" class="block text-gray-700 text-sm font-bold mb-2">Teacher</label>
                        <select id="${day}Teacher${newIndex}" name="periods[${day}][${newIndex}][teacher]" 
                            class="teacher-select shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div>
                        <label for="${day}TimeFrom${newIndex}" class="block text-gray-700 text-sm font-bold mb-2">Time From</label>
                        <input type="time" id="${day}TimeFrom${newIndex}" name="periods[${day}][${newIndex}][time_from]" 
                            class="time-from-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-full">
                            <label for="${day}TimeTo${newIndex}" class="block text-gray-700 text-sm font-bold mb-2">Time To</label>
                            <input type="time" id="${day}TimeTo${newIndex}" name="periods[${day}][${newIndex}][time_to]" 
                                class="time-to-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <button type="button" class="remove-period-row text-red-500 hover:text-red-700 mt-8">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            `;
        }

        // Append the new row
        currentDayPanel.find('.extra-rows-container').append(newRow);

        // If it's a regular period, populate the subject dropdown
        if (!isExtra) {
            populateSubjectDropdown($(`#${day}Subject${newIndex}`));
        }

        // Check if "Apply to All" checkbox is checked
        const applyToAll = $('#applyExtraToAll').is(':checked');
        if (applyToAll) {
            const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            days.forEach(function(otherDay) {
                if (otherDay !== day) {
                    const otherDayPanel = $(`#panel-${otherDay}`);
                    const otherRowCount = otherDayPanel.find('.timetable-period-row').length;
                    const otherNewIndex = isExtra ? `extra${otherRowCount}` : otherRowCount;

                    let otherRow;
                    if (isExtra) {
                        otherRow = `
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end mb-4 timetable-period-row">
                                <div>
                                    <label for="${otherDay}Name${otherNewIndex}" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                                    <input type="text" id="${otherDay}Name${otherNewIndex}" name="periods[${otherDay}][${otherNewIndex}][name]" 
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                        placeholder="Break, Lunch, etc.">
                                </div>
                                <div>
                                    <label for="${otherDay}TimeFrom${otherNewIndex}" class="block text-gray-700 text-sm font-bold mb-2">Time From</label>
                                    <input type="time" id="${otherDay}TimeFrom${otherNewIndex}" name="periods[${otherDay}][${otherNewIndex}][time_from]" 
                                        class="time-from-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-full">
                                        <label for="${otherDay}TimeTo${otherNewIndex}" class="block text-gray-700 text-sm font-bold mb-2">Time To</label>
                                        <input type="time" id="${otherDay}TimeTo${otherNewIndex}" name="periods[${otherDay}][${otherNewIndex}][time_to]" 
                                            class="time-to-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                    <button type="button" class="remove-period-row text-red-500 hover:text-red-700 mt-8">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        `;
                    } else {
                        otherRow = `
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end mb-4 timetable-period-row">
                                <div>
                                    <label for="${otherDay}Subject${otherNewIndex}" class="block text-gray-700 text-sm font-bold mb-2">Subject</label>
                                    <select id="${otherDay}Subject${otherNewIndex}" name="periods[${otherDay}][${otherNewIndex}][subject]" 
                                        class="subject-select shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="${otherDay}Teacher${otherNewIndex}" class="block text-gray-700 text-sm font-bold mb-2">Teacher</label>
                                    <select id="${otherDay}Teacher${otherNewIndex}" name="periods[${otherDay}][${otherNewIndex}][teacher]" 
                                        class="teacher-select shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Select</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="${otherDay}TimeFrom${otherNewIndex}" class="block text-gray-700 text-sm font-bold mb-2">Time From</label>
                                    <input type="time" id="${otherDay}TimeFrom${otherNewIndex}" name="periods[${otherDay}][${otherNewIndex}][time_from]" 
                                        class="time-from-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-full">
                                        <label for="${otherDay}TimeTo${otherNewIndex}" class="block text-gray-700 text-sm font-bold mb-2">Time To</label>
                                        <input type="time" id="${otherDay}TimeTo${otherNewIndex}" name="periods[${otherDay}][${otherNewIndex}][time_to]" 
                                            class="time-to-input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                    <button type="button" class="remove-period-row text-red-500 hover:text-red-700 mt-8">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        `;
                    }

                    otherDayPanel.find('.extra-rows-container').append(otherRow);
                    
                    if (!isExtra) {
                        populateSubjectDropdown($(`#${otherDay}Subject${otherNewIndex}`));
                    }
                }
            });
        }
    }

    // ==================== FORM SUBMIT HANDLERS ====================
    
    // Add Timetable Form Submit
    $('#addTimetableForm').on('submit', function(e) {
        e.preventDefault();
        
        const selectedClassId = $('#addTimetableClass').val();
        const selectedClassName = $('#addTimetableClass option:selected').text();
        const sectionId = $('#section').val();
        
        if (!selectedClassId || !sectionId) {
            showErrorSwal('Please select both class and section');
            return;
        }

        const formData = new FormData(this);
        formData.set('class_name', selectedClassName.trim());

        // Collect periods from all day panels
        const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        days.forEach(function(day) {
            const dayPanel = $(`#panel-${day}`);
            const periodRows = dayPanel.find('.timetable-period-row');
            
            periodRows.each(function(index) {
                const $row = $(this);
                
                // Check if this is a regular period (has subject field)
                if ($row.find('select[id$="Subject"]').length > 0) {
                    const subjectId = $row.find('select[id$="Subject"]').val();
                    const teacherId = $row.find('select[id$="Teacher"]').val();
                    const timeFrom = $row.find('input[id$="TimeFrom"]').val();
                    const timeTo = $row.find('input[id$="TimeTo"]').val();
                    
                    if (subjectId && teacherId && timeFrom && timeTo) {
                        formData.append(`periods[${day}][${index}][subject]`, subjectId);
                        formData.append(`periods[${day}][${index}][teacher]`, teacherId);
                        formData.append(`periods[${day}][${index}][time_from]`, timeFrom);
                        formData.append(`periods[${day}][${index}][time_to]`, timeTo);
                    }
                } 
                // Check if this is an extra period (has name field)
                else if ($row.find('input[id$="Name"]').length > 0) {
                    const name = $row.find('input[id$="Name"]').val();
                    const timeFrom = $row.find('input[id$="TimeFrom"]').val();
                    const timeTo = $row.find('input[id$="TimeTo"]').val();
                    
                    if (name && timeFrom && timeTo) {
                        formData.append(`periods[${day}][${index}][name]`, name);
                        formData.append(`periods[${day}][${index}][time_from]`, timeFrom);
                        formData.append(`periods[${day}][${index}][time_to]`, timeTo);
                    }
                }
            });
        });

        // Submit form using named route
        $.ajax({
            url: '{{ route("school.timetable.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                showLoadingSwal('Saving...', 'Please wait while we save your timetable');
            },
            success: function(response) {
                Swal.close();
                if (response.success) {
                    showSuccessSwal(response.message || 'Timetable added successfully', function() {
                        window.location.reload();
                    });
                } else {
                    showErrorSwal(response.message || 'Failed to add timetable');
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                let errorMessage = 'An error occurred while saving the timetable';
                
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        errorMessage = Object.values(errors).flat().join('<br>');
                    } else if (xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                }
                
                showErrorSwal(errorMessage);
            }
        });
    });

    // ==================== EDIT TIMETABLE ENTRY ====================
    
    // Open Edit Modal
    $(document).on('click', '.editTimetableEntryBtn', function(e) {
        e.preventDefault();
        const entryId = $(this).data('id');
        currentEditEntryId = entryId;
        
        // Fetch entry details using named route
        $.ajax({
            url: '{{ route("school.timetable.period.edit", ":id") }}'.replace(':id', entryId),
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                showLoadingSwal('Loading...', 'Fetching entry details...');
            },
            success: function(response) {
                Swal.close();
                if (response.success) {
                    const entry = response.data;
                    
                    // Populate edit form
                    $('#editEntryId').val(entry.id);
                    $('#editDay').val(entry.day);
                    $('#editTimeFrom').val(entry.time_from);
                    $('#editTimeTo').val(entry.time_to);
                    
                    // Populate subjects dropdown in edit modal
                    let subjectOptions = '<option value="">Select Subject</option>';
                    subjects.forEach(function(subject) {
                        const selected = subject.id == entry.subject_id ? 'selected' : '';
                        subjectOptions += `<option value="${subject.id}" ${selected}>${subject.name}</option>`;
                    });
                    $('#editSubject').html(subjectOptions);
                    
                    // Populate teachers dropdown based on selected subject
                    let teacherOptions = '<option value="">Select Teacher</option>';
                    const relevantTeachers = teachers.filter(t => t.subject_id == entry.subject_id);
                    relevantTeachers.forEach(function(teacher) {
                        const selected = teacher.id == entry.teacher_id ? 'selected' : '';
                        teacherOptions += `<option value="${teacher.id}" ${selected}>${teacher.name}</option>`;
                    });
                    $('#editTeacher').html(teacherOptions);
                    
                    // Show modal
                    $('#editTimetableEntryModal').removeClass('hidden');
                } else {
                    showErrorSwal(response.message || 'Failed to load entry details');
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                showErrorSwal('An error occurred while loading the entry');
            }
        });
    });

    // Close Edit Modal
    $('#closeEditTimetableEntryModal, #cancelEditTimetableEntryBtn').on('click', function() {
        $('#editTimetableEntryModal').addClass('hidden');
    });

    // Edit Subject Change
    $('#editSubject').on('change', function() {
        const subjectId = $(this).val();
        populateTeacherDropdown($('#editTeacher'), subjectId);
    });

    // Edit Form Submit
    $('#editTimetableEntryForm').on('submit', function(e) {
        e.preventDefault();
        
        const entryId = $('#editEntryId').val();
        const formData = $(this).serialize();
        
        // Submit using named route
        $.ajax({
            url: '{{ route("school.timetable.period.update", ":id") }}'.replace(':id', entryId),
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                showLoadingSwal('Updating...', 'Please wait while we update the entry');
            },
            success: function(response) {
                Swal.close();
                if (response.success) {
                    $('#editTimetableEntryModal').addClass('hidden');
                    showSuccessSwal(response.message || 'Entry updated successfully', function() {
                        window.location.reload();
                    });
                } else {
                    showErrorSwal(response.message || 'Failed to update entry');
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                let errorMessage = 'An error occurred while updating';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                showErrorSwal(errorMessage);
            }
        });
    });

    // ==================== DELETE TIMETABLE ENTRY ====================
    
    // Open Delete Confirmation Modal
    $(document).on('click', '.deleteTimetableEntryBtn', function(e) {
        e.preventDefault();
        timetableEntryIdToDelete = $(this).data('id');
        $('#deleteTimetableEntryModal').removeClass('hidden');
    });

    // Close Delete Modal
    $('#closeDeleteTimetableEntryModal').on('click', function() {
        $('#deleteTimetableEntryModal').addClass('hidden');
        timetableEntryIdToDelete = null;
    });

    // Confirm Delete using named route
    $('#confirmDeleteTimetableEntryBtn').on('click', function() {
        if (!timetableEntryIdToDelete) {
            return;
        }

        $.ajax({
            url: '{{ route("school.timetable.destroy", ":id") }}'.replace(':id', timetableEntryIdToDelete),
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            beforeSend: function() {
                $('#deleteTimetableEntryModal').addClass('hidden');
                showLoadingSwal('Deleting...', 'Please wait...');
            },
            success: function(response) {
                Swal.close();
                if (response.success) {
                    showSuccessSwal(response.message || 'Entry deleted successfully', function() {
                        window.location.reload();
                    });
                } else {
                    showErrorSwal(response.message || 'Failed to delete entry');
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                showErrorSwal('An error occurred while deleting the entry');
            }
        });
    });

    // ✅ UPDATED: Delete Entire Timetable Button Handler
    $(document).on('click', '#deleteTimetableBtn', function (e) {
        e.preventDefault();

        const className = $(this).data('class-name');
        const sectionId = $(this).data('section-id');
        
        if (!className || !sectionId) {
            Swal.fire('Error', 'No timetable found to delete.', 'error');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete the timetable and all its periods.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("school.timetable.destroy") }}',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'DELETE',
                        class_name: className,
                        section_id: sectionId
                    },
                    beforeSend: function () {
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait while we remove the timetable.',
                            didOpen: () => Swal.showLoading(),
                            allowOutsideClick: false
                        });
                    },
                    success: function (response) {
                        Swal.close();

                        if (response.success) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function (xhr) {
                        Swal.close();
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to delete timetable', 'error');
                    }
                });
            }
        });
    });

    // ==================== INITIALIZE PAGE ====================
    initializePage();
});
</script>


