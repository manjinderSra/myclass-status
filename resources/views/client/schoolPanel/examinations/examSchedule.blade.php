@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-8 bg-gray-50">
        <!-- Header Section -->
        

        {{-- <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 mt-32">
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
                <p class="text-sm text-gray-500">Total Schedules</p>
                <p class="text-2xl font-bold text-gray-800">{{ count($schedules) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
                <p class="text-sm text-gray-500">Active Exams</p>
                <p class="text-2xl font-bold text-gray-800">{{ $schedules->where('exam_cancel', 0)->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
                <p class="text-sm text-gray-500">Canceled Exams</p>
                <p class="text-2xl font-bold text-gray-800">{{ $schedules->where('exam_cancel', 1)->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500">
                <p class="text-sm text-gray-500">Upcoming</p>
                <p class="text-2xl font-bold text-gray-800">{{ $schedules->where('exam_date', '>=', now()->format('Y-m-d'))->count() }}</p>
            </div>
        </div> --}}

        <div class="mb-8">
            <div class="flex items-center justify-between mt-28 mb-2">
                <h1 class="text-2xl font-bold text-gray-800">Exam Schedule</h1>
                <button id="openExamScheduleModal" class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Exam Schedule
                </button>
            </div>
            <p class="text-gray-600">Manage and organize all exam schedules for your institution</p>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">All Exam Schedules</h2>
            </div>
            
            <div class="p-6 overflow-x-auto">
                <table id="examSchedulesTable" class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50 text-gray-700 text-left text-xs font-medium uppercase tracking-wider">
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Exam</th>
                            <th class="px-4 py-3">Class</th>
                            <th class="px-4 py-3">Section</th>
                            <th class="px-4 py-3">Subject</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Time</th>
                            <th class="px-4 py-3">Duration</th>
                            <th class="px-4 py-3">Room</th>
                            <th class="px-4 py-3">Evaluator</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                        @forelse($schedules as $schedule)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-medium">{{ $schedule->id }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium capitalize">{{ $schedule->exam->name ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3">{{ $schedule->class }}</td>
                                <td class="px-4 py-3">{{ $schedule->section }}</td>
                                <td>{{ $schedule->subject ? $schedule->subject->name : 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium">{{ \Carbon\Carbon::parse($schedule->exam_date)->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($schedule->exam_date)->format('l') }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div>{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}</div>
                                    <div class="text-xs text-gray-500">to {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $schedule->duration }} min
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @php $rooms = json_decode($schedule->room_no, true); @endphp
                                    <div class="flex flex-wrap gap-1">
                                        @if(is_array($rooms))
                                            @foreach($rooms as $room)
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">{{ $room }}</span>
                                            @endforeach
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">{{ $schedule->room_no }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @php $evaluators = json_decode($schedule->evaluator_id, true); @endphp
                                    @if(is_array($evaluators) && count($evaluators) > 0)
                                        <div class="flex flex-col gap-1">
                                            @foreach($evaluators as $evaluatorId)
                                                @php $teacher = \App\Models\Teacher::find($evaluatorId); @endphp
                                                @if($teacher)
                                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">{{ $teacher->first_name }} {{ $teacher->last_name }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500 italic">Not assigned</span>
                                    @endif
                                </td>
                           <td>
    @if($schedule->status === 'Active')
        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
    @elseif($schedule->status === 'Canceled')
        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Canceled</span>
    @elseif($schedule->status === 'Completed')
        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">Completed</span>
    @else
        -
    @endif
</td>




                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <button 
                                            type="button"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors editExamScheduleBtn"
                                            data-id="{{ $schedule->id }}"
                                            title="Edit schedule">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>

                                        <button 
                                            type="button"
                                            class="p-1.5 text-green-600 hover:bg-green-50 rounded transition-colors assignTeacherBtn"
                                            data-id="{{ $schedule->id }}"
                                            title="Assign teacher">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                            </svg>
                                        </button>

                                        {{-- @if($schedule->exam_cancel == 0)
                                        <button 
                                            type="button"
                                            class="p-1.5 text-gray-600 hover:bg-gray-50 rounded transition-colors cancelExamBtn"
                                            data-id="{{ $schedule->id }}"
                                            title="Cancel exam">
                                            
                                        </button>
                                        @endif --}}
                                        <button 
                                            class="p-1.5 text-gray-600 hover:bg-gray-50 rounded transition-colors cancelExamBtn"
                                            data-id="{{ $schedule->id }}"
                                            {{ $schedule->status == 'Canceled' ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>


                                        <form 
                                            action="{{ route('exam-schedules.destroy', $schedule->id) }}" 
                                            method="POST" 
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                onclick="return confirm('Are you sure you want to delete this exam schedule?')" 
                                                class="p-1.5 text-red-600 hover:bg-red-50 rounded transition-colors"
                                                title="Delete schedule">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-4 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500 mb-1">No exam schedules found</p>
                                        <p class="text-gray-400">Get started by creating your first exam schedule</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Exam Schedule Modal --}}
<div id="examScheduleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Add Exam Schedule</h2>
            <button id="closeExamScheduleModalBtn" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <form id="examScheduleForm" action="{{ route('school.exam-schedules.store') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Exam</label>
                    <select name="exam_id" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Select Exam</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Class</label>
                    <select name="class_id" id="classDropdown" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Section</label>
                    <select name="section_id" id="sectionDropdown" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
              <div>
      <label class="block mb-2 font-medium text-gray-700">Subject</label>
    <select name="subject_id" id="edit_subject_id" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
        <option value="">Select Subject</option>
        @foreach($subjects as $subject)
            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
        @endforeach
    </select>
    </select>
</div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Exam Date</label>
                    <input type="date" name="exam_date" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Start Time</label>
                    <input type="time" id="start_time" name="start_time" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">End Time</label>
                    <input type="time" id="end_time" name="end_time" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Duration (min)</label>
                    <input type="number" id="duration" name="duration" readonly class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed" />
                </div>
               <div>
    <label class="block mb-2 font-medium text-gray-700">Room No</label>
    <input type="text" name="room_no" placeholder="Enter room numbers, e.g., 1,6,392,2133"
           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
</div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Max Marks</label>
                    <input type="number" name="max_marks" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Min Marks</label>
                    <input type="number" name="min_marks" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                </div>
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Exam Type</label>
                    <select name="exam_type" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Select Exam Type</option>
                        <option value="theory">Theory</option>
                        <option value="practical">Practical</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" id="cancelExamScheduleModal" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors shadow-sm">Add Exam Schedule</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Exam Schedule Modal --}}
<div id="editExamScheduleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Edit Exam Schedule</h2>
            <button id="closeEditExamScheduleModalBtn" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="editExamScheduleForm" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <input type="hidden" name="examSchedule_id" id="edit_examSchedule_id">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Exam</label>
                    <select name="exam_id" id="edit_exam_id" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"></select>
                    @error('exam_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Class</label>
                    <select name="class_id" id="edit_class_id" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"></select>
                    @error('class_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Section</label>
                    <select name="section_id" id="edit_section_id" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"></select>
                    @error('section_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Subject</label>
                    <input type="text" name="subject" id="edit_subject" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('subject')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Exam Date</label>
                    <input type="date" name="exam_date" id="edit_exam_date" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('exam_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Start Time</label>
                    <input type="time" name="start_time" id="edit_start_time" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('start_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">End Time</label>
                    <input type="time" name="end_time" id="edit_end_time" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('end_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Duration (minutes)</label>
                    <input type="number" name="duration" id="edit_duration" readonly class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed">
                    @error('duration')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Room No</label>
                    <input type="text" name="room_no" id="edit_room_no" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <small class="text-gray-500 text-xs">Comma-separated if multiple</small>
                    @error('room_no')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Max Marks</label>
                    <input type="number" name="max_marks" id="edit_max_marks" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('max_marks')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Min Marks</label>
                    <input type="number" name="min_marks" id="edit_min_marks" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @error('min_marks')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Exam Type</label>
                    <select name="exam_type" id="edit_exam_type" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Select Exam Type</option>
                        <option value="theory">Theory</option>
                        <option value="practical">Practical</option>
                    </select>
                    @error('exam_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" id="cancelEditExamScheduleModal" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors shadow-sm">Update Schedule</button>
            </div>
        </form>
    </div>
</div>

{{-- Assign Teacher Modal --}}
<div id="assignTeacherModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-xl w-full max-w-md">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Assign Teacher</h2>
            <button id="closeAssignTeacherModalBtn" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="assignTeacherForm" method="POST" action="{{ route('exam-schedules.assign-teacher') }}" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="schedule_id" id="assignScheduleId">

            <div class="mb-4">
                <label class="block mb-2 font-medium text-gray-700">Select Teacher(s)</label>
                <select name="evaluator_id[]" id="assignEvaluatorDropdown" multiple
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->first_name . ' '. $teacher->last_name }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-2">Hold Ctrl (Windows) or Cmd (Mac) to select multiple teachers.</p>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" id="cancelAssignTeacherModal"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors shadow-sm">Save Assignments</button>
            </div>
        </form>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

{{-- CDN Links --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    div.dataTables_wrapper { width: 100%; }
    .dataTables_length select, .dataTables_filter input {
        @apply border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors;
    }
    .dataTables_filter input { width: 16rem !important; }
    .dataTables_paginate { @apply flex space-x-2 mt-4; }
    .dataTables_paginate a {
        @apply border border-gray-300 rounded-lg px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors;
    }
    .dataTables_paginate .current { @apply bg-blue-600 text-white border-blue-600; pointer-events: none; }
    .dataTables_paginate .disabled { @apply text-gray-400 cursor-not-allowed border-gray-200; }
    .dataTables_info { @apply text-gray-600 text-sm mt-2; }
    
    /* Select2 custom styling */
    .select2-container--default .select2-selection--multiple {
        @apply border border-gray-300 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        @apply border-blue-500;
    }
</style>

<script>
$(document).ready(function () {
    // Initialize DataTable
    $('#examSchedulesTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'desc']],
        language: {
            search: "Search schedules:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ schedules",
            paginate: {
                previous: "‹",
                next: "›"
            }
        }
    });

    // Initialize Select2 for room selection
    $('#roomSelect').select2({
        placeholder: "Select one or more rooms",
        width: '100%',
        dropdownParent: $('#examScheduleModal')
    });

    // Initialize Select2 for teacher assignment
    $('#assignEvaluatorDropdown').select2({
        placeholder: "Select teacher(s)",
        width: '100%',
        dropdownParent: $('#assignTeacherModal')
    });

    // ====================
    // Add Modal Functions
    // ====================
    $('#openExamScheduleModal').click(function() {
        $('#examScheduleModal').removeClass('hidden');
        $('#examScheduleForm')[0].reset();
        $('#roomSelect').val(null).trigger('change');
    });

    $('#closeExamScheduleModalBtn, #cancelExamScheduleModal').click(function() {
        $('#examScheduleModal').addClass('hidden');
    });

    function calculateDuration() {
        const startTime = $('#start_time').val();
        const endTime = $('#end_time').val();

        if (startTime && endTime) {
            const start = parseTime12h(startTime);
            const end = parseTime12h(endTime);

            let diff = (end.hours * 60 + end.minutes) - (start.hours * 60 + start.minutes);

            // If end time is smaller than start (crosses midnight)
            if (diff < 0) {
                diff += 24 * 60;
            }

            $('#duration').val(diff); // duration in minutes
        } else {
            $('#duration').val('');
        }
    }

    $('#start_time, #end_time').on('change', calculateDuration);

    // Load subjects when class is selected
    $('#classDropdown').change(function () {
        let classId = $(this).val();
        let subjectDropdown = $('#subjectDropdown');

        subjectDropdown.empty().append('<option value="">Select Subject</option>');

        if (classId) {
            $.get(`/school/subjects-by-class/${classId}`, function (subjects) {
                subjects.forEach(subject => {
                    subjectDropdown.append(`<option value="${subject.id}">${subject.name}</option>`);
                });
            }).fail(function() {
                alert('Failed to load subjects');
            });
        }
    });

    // ====================
    // Edit Modal Functions
    // ====================
    $(document).on('click', '.editExamScheduleBtn', function() {
        const scheduleId = $(this).data('id');

        fetch(`/school/exam-schedule/${scheduleId}/edit`)
            .then(res => {
                if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                return res.json();
            })
            .then(data => {
                const schedule = data.schedule;
                const classes = data.classes;
                const sections = data.sections;

                // Fill form fields
                $('#edit_examSchedule_id').val(schedule.id);
                
                // Fill exam dropdown
                $('#edit_exam_id').html(`<option value="${schedule.exam_id}" selected>${schedule.exam.name}</option>`);

                // Fill class dropdown
                let classOptions = '<option value="">Select Class</option>';
                classes.forEach(c => {
                    const selected = c.name === schedule.class ? 'selected' : '';
                    classOptions += `<option value="${c.id}" ${selected}>${c.name}</option>`;
                });
                $('#edit_class_id').html(classOptions);

                $('#edit_exam_type').val(schedule.exam_type); 

                // Fill section dropdown
                let sectionOptions = '<option value="">Select Section</option>';
                sections.forEach(s => {
                    const selected = s.name === schedule.section ? 'selected' : '';
                    sectionOptions += `<option value="${s.id}" ${selected}>${s.name}</option>`;
                });
                $('#edit_section_id').html(sectionOptions);

                // Fill other fields
                $('#edit_subject_id').val(schedule.subject_id);
                $('#edit_exam_date').val(schedule.exam_date);
                $('#edit_start_time').val(schedule.start_time);
                $('#edit_end_time').val(schedule.end_time);
                $('#edit_duration').val(schedule.duration);

                // Room numbers
                try {
                    const rooms = JSON.parse(schedule.room_no);
                    $('#edit_room_no').val(Array.isArray(rooms) ? rooms.join(',') : schedule.room_no);
                } catch (e) {
                    $('#edit_room_no').val(schedule.room_no);
                }

                $('#edit_max_marks').val(schedule.max_marks);
                $('#edit_min_marks').val(schedule.min_marks);

                // Set form action
                $('#editExamScheduleForm').attr('action', `/school/exam-schedule/${schedule.id}`);

                // Show modal
                $('#editExamScheduleModal').removeClass('hidden');
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Failed to load schedule data. Check console for details.');
            });
    });

    // ====================
    // Assign Teacher Modal Functions
    // ====================
    $(document).on('click', '.assignTeacherBtn', function() {
        const scheduleId = $(this).data('id');
        $('#assignScheduleId').val(scheduleId);
        $('#assignTeacherModal').removeClass('hidden');

        // Fetch already assigned teachers
        fetch(`/school/exam-schedules/${scheduleId}/evaluators`)
            .then(res => res.json())
            .then(data => {
                $('#assignEvaluatorDropdown option').each(function() {
                    this.selected = data.includes(parseInt(this.value));
                });
                $('#assignEvaluatorDropdown').trigger('change');
            })
            .catch(error => {
                console.error('Error loading evaluators:', error);
            });
    });

    $('#closeAssignTeacherModalBtn, #cancelAssignTeacherModal').click(function() {
        $('#assignTeacherModal').addClass('hidden');
    });
    
    // Close modal when clicking the X button
    document.getElementById('closeEditExamScheduleModalBtn').addEventListener('click', function() {
        document.getElementById('editExamScheduleModal').classList.add('hidden');
    });

    // Close modal when clicking the Cancel button
    document.getElementById('cancelEditExamScheduleModal').addEventListener('click', function() {
        document.getElementById('editExamScheduleModal').classList.add('hidden');
    });

    // Close modals when clicking outside
    $('.fixed').click(function(e) {
        if (e.target === this) {
            $(this).addClass('hidden');
        }
    });
    
    // Cancel exam functionality
    $(document).on('click', '.cancelExamBtn', function() {
        const scheduleId = $(this).data('id');
        const reason = prompt("Please enter the reason for cancellation:");

        if (reason !== null && reason.trim() !== "") {
            fetch(`/school/exam-schedules/${scheduleId}/cancel`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({
                    exam_cancel: true,
                    cancel_reason: reason
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Exam canceled successfully.');
                    location.reload(); // refresh to reflect changes
                } else {
                    alert('Something went wrong!');
                }
            });
        }
    });
});

// Helper function to parse time in 12h format
function parseTime12h(timeString) {
    const [time, modifier] = timeString.split(' ');
    let [hours, minutes] = time.split(':');
    
    if (modifier === 'PM' && hours < 12) hours = parseInt(hours) + 12;
    if (modifier === 'AM' && hours == 12) hours = 0;
    
    return {
        hours: parseInt(hours),
        minutes: parseInt(minutes)
    };
}
</script>