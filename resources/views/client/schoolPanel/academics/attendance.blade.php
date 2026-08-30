@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                Academics / <span class="text-l text-gray-500">Attendance</span>
            </h1>
          
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Attendance Overview Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
            {{-- Total Days --}}
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-gray-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-gray-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V..." />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Students</p>
                        <p class="text-xl font-semibold text-gray-800" id="totalDaysCount">{{ $totalDays ?? 0 }}</p>
                    </div>
                </div>
            </div>
            {{-- Present Days --}}
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Present</p>
                        <p class="text-xl font-semibold text-gray-800" id="presentDaysCount">{{ $presentDays ?? 0 }}</p>
                    </div>
                </div>
            </div>
            {{-- Absent Days --}}
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Absent</p>
                        <p class="text-xl font-semibold text-gray-800" id="absentDaysCount">{{ $absentDays ?? 0 }}</p>
                    </div>
                </div>
            </div>
            {{-- Late Days --}}
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Late</p>
                        <p class="text-xl font-semibold text-gray-800" id="lateDaysCount">{{ $lateDays ?? 0 }}</p>
                    </div>
                </div>
            </div>
            {{-- Leave Days --}}
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Leave</p>
                        <p class="text-xl font-semibold text-gray-800" id="leaveDaysCount">{{ $leaveDays ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Section --}}
<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <form method="GET" action="{{ route('client.schoolPanel.academics.attendance') }}" class="flex flex-wrap items-end gap-4">
        <div>
            <label for="class_id" class="block text-gray-700 font-medium mb-2">Class</label>
            <select id="class_id" name="class_id" class="border border-gray-300 rounded px-3 py-2 w-48 focus:ring-2 focus:ring-blue-500">
                <option value="">All Classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ (isset($classId) && $classId == $class->id) ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="section_id" class="block text-gray-700 font-medium mb-2">Section</label>
            <select id="section_id" name="section_id" class="border border-gray-300 rounded px-3 py-2 w-48 focus:ring-2 focus:ring-blue-500">
                <option value="">All Sections</option>
                @foreach($sections as $section)
                    <option value="{{ $section->id }}" {{ (isset($sectionId) && $sectionId == $section->id) ? 'selected' : '' }}>
                        {{ $section->name }}
                    </option>
                @endforeach
            </select>
        </div>

            <div>
        <label for="attendance_date" class="block text-gray-700 font-medium mb-2">Date</label>
        <input type="date" id="attendance_date" name="attendance_date"
               value="{{ $attendanceDate ?? '' }}"
               class="border border-gray-300 rounded px-3 py-2 w-48 focus:ring-2 focus:ring-blue-500">
    </div>

        <div class="flex items-center space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
            <a href="{{ route('client.schoolPanel.academics.attendance') }}" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Reset</a>
        </div>

    </form>
</div>


        {{-- Attendance Records Table --}}
        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <table id="attendanceTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">Roll Number</th>
                        <th class="px-6 py-3 font-semibold">Student Name</th>
                        <th class="px-6 py-3 font-semibold">Class</th>
                        <th class="px-6 py-3 font-semibold">Date</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Remarks</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
    @forelse($attendances as $attendance)
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">{{ $attendance->student->roll_number ?? '-' }}</td>
            <td class="px-6 py-4">{{ $attendance->student->first_name . ' ' . $attendance->student->last_name }}</td>
            <td class="px-6 py-4">
                {{ $attendance->student->class->name ?? '-' }}
                @if(!empty($attendance->student->section))
                    - {{ $attendance->student->section->name }}
                @endif
            </td>
            <td class="px-6 py-4">{{ $attendance->attendance_date->format('M d, Y') }}</td>
            <td class="px-6 py-4">
                @php
                    $statusColor = match($attendance->status) {
                        'present' => 'bg-green-100 text-green-800',
                        'absent' => 'bg-red-100 text-red-800',
                        'late' => 'bg-yellow-100 text-yellow-800',
                        'leave' => 'bg-blue-100 text-blue-800',
                        default => 'bg-gray-100 text-gray-800',
                    };
                @endphp
                <span class="{{ $statusColor }} px-2 py-1 rounded-full text-xs font-medium">
                    {{ ucfirst($attendance->status) }}
                </span>
            </td>
            <td class="px-6 py-4">{{ $attendance->remarks ?? '-' }}</td>
        </tr>
    @empty
        {{-- ✅ Message when no data found --}}
        <tr>
            <td colspan="6" class="text-center py-6 text-gray-500 italic">
                No attendance records found for the selected filters.
            </td>
        </tr>
    @endforelse
</tbody>

            </table>
        </div>
    </div>
</div>

{{-- Mark Attendance Modal --}}
<div id="markAttendanceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Mark Attendance</h2>
        <form id="markAttendanceForm">
            @csrf
            <label class="block mb-2 font-medium text-gray-700">Student</label>
            <select name="student_id" required class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Select Student --</option>
                @foreach($students as $student)
                <option value="{{ $student->id }}">{{ $student->name }}</option>
                @endforeach
            </select>

            <label class="block mb-2 font-medium text-gray-700">Date</label>
            <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />

            <label class="block mb-2 font-medium text-gray-700">Status</label>
            <select name="status" required class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="present">Present</option>
                <option value="absent">Absent</option>
                <option value="late">Late</option>
                <option value="leave">Leave</option>
            </select>

            <label class="block mb-2 font-medium text-gray-700">Remarks (Optional)</label>
            <textarea name="remarks" rows="3" placeholder="Add any remarks..." class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeMarkAttendanceModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Save Attendance</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Attendance Modal --}}
<div id="editAttendanceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Edit Attendance</h2>
        <form id="editAttendanceForm">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_attendance_id" name="attendance_id" />

            <label class="block mb-2 font-medium text-gray-700">Student</label>
            <select id="edit_student_id" name="student_id" required class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Select Student --</option>
                @foreach($students as $student)
                <option value="{{ $student->id }}">{{ $student->name }}</option>
                @endforeach
            </select>

            <label class="block mb-2 font-medium text-gray-700">Date</label>
            <input type="date" id="edit_date" name="date" required class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" />

            <label class="block mb-2 font-medium text-gray-700">Status</label>
            <select id="edit_status" name="status" required class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="present">Present</option>
                <option value="absent">Absent</option>
                <option value="late">Late</option>
                <option value="leave">Leave</option>
            </select>

            <label class="block mb-2 font-medium text-gray-700">Remarks (Optional)</label>
            <textarea id="edit_remarks" name="remarks" rows="3" placeholder="Add any remarks..." class="w-full px-3 py-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeEditAttendanceModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Update Attendance</button>
            </div>
        </form>
    </div>
</div>

{{-- Delete Attendance Modal --}}
<div id="deleteAttendanceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
        <p class="text-gray-700 mb-6">Are you sure you want to delete attendance record for <span id="deleteStudentName" class="font-semibold"></span> on <span id="deleteAttendanceDate" class="font-semibold"></span>? This action cannot be undone.</p>
        <div class="flex justify-end space-x-4">
            <button type="button" id="closeDeleteAttendanceModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
            <button id="confirmDeleteAttendanceBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

{{-- DataTables CDN --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

{{-- Toastr CDN --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<style>
    /* Custom styles for DataTables and Toastr to match your existing styling */
    div.dataTables_wrapper { width: 100%; }
    .dataTables_length select, .dataTables_filter input {
        @apply border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500;
    }
    .dataTables_filter input { width: 16rem !important; }
    .dataTables_paginate { @apply flex space-x-1 mt-4; }
    .dataTables_paginate a {
        @apply border border-gray-300 rounded px-3 py-1 text-sm text-gray-700 hover:bg-gray-200 cursor-pointer;
    }
    .dataTables_paginate .current { @apply bg-blue-600 text-white border-blue-600; pointer-events: none; }
    .dataTables_paginate .disabled { @apply text-gray-400 cursor-not-allowed border-gray-200; }
    .dataTables_info { @apply text-gray-600 text-sm mt-2; }

    /* Toastr custom styles for opacity and background */
    #toast-container .toast {
        opacity: 1 !important;
    }
    #toast-container .toast-success {
        background-color: rgba(47, 133, 90, 0.95) !important;
    }
    #toast-container .toast-error {
        background-color: rgba(191, 38, 33, 0.95) !important;
    }
    #toast-container .toast-info {
        background-color: rgba(21, 115, 178, 0.95) !important;
    }
    #toast-container .toast-warning {
        background-color: rgba(230, 153, 26, 0.95) !important;
    }
</style>


