@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="w-full p-6 bg-gray-50">
        <!-- Title -->
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-2xl font-semibold text-gray-800">
                Teacher Monthly Attendance Report
            </h4>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('teacher.attendance.monthly') }}" 
              class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            
            <!-- Month Picker -->
            <input type="month" name="month" value="{{ $month }}" 
                   class="border rounded-lg px-3 py-2 w-full">

            <!-- Teacher Dropdown -->
            <select name="teacher_id" class="border rounded-lg px-3 py-2 w-full">
                <option value="">All Teachers</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" 
                        {{ $teacherId == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>

            <!-- Filter Button -->
            <button type="submit" 
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                Filter
            </button>
        </form>

        <!-- Report Table -->
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Teacher</th>
                        <th class="px-4 py-3">Present</th>
                        <th class="px-4 py-3">Absent</th>
                        <th class="px-4 py-3">Late</th>
                        <th class="px-4 py-3">Leave</th>
                        <th class="px-4 py-3">Total Marked</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report as $row)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $row->teacher_name }}</td>
                            <td class="px-4 py-3 text-green-600 font-semibold">{{ $row->present_count }}</td>
                            <td class="px-4 py-3 text-red-600 font-semibold">{{ $row->absent_count }}</td>
                            
                            <td class="px-4 py-3 text-yellow-600 font-semibold">{{ $row->late_count }}</td>
                            <td class="px-4 py-3 text-yellow-600 font-semibold">{{ $row->leave_count }}</td>
                            <td class="px-4 py-3">{{ $row->total_marked }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                No records found for this month.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')
