@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="w-full p-6 bg-gray-50">
        <!-- Title & Action Button -->
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-2xl font-semibold text-gray-800">Teacher Attendance</h4>
            <a href="#" 
               class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                Mark Attendance
            </a>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter Form -->
        <form method="GET" action="#" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
           <input type="date" name="attendance_date" 
       value="{{ request('attendance_date', now()->format('Y-m-d')) }}" 
       class="border rounded-lg px-3 py-2 w-full">

            <select name="teacher_id" class="border rounded-lg px-3 py-2 w-full">
                <option value="">All Teachers</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" 
                        {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Filter
            </button>

            <a href="{{route('teacher.attendance.monthly')}}" 
               class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                Teacher Wise Attendance
            </a>
        </form>

        <!-- Attendance Table -->
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Teacher</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Remark</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800">{{ $attendance->first_name }}</td>
                            <td class="px-4 py-3">{{ $attendance->attendance_date }}</td>
                            <td class="px-4 py-3">
                                @if($attendance->status == 'Present')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-sm rounded">
                                        {{ $attendance->status }}
                                    </span>
                                @elseif($attendance->status == 'Absent')
                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-sm rounded">
                                        {{ $attendance->status }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-sm rounded">
                                        {{ $attendance->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $attendance->remark ?? '-' }}</td>
                            <td class="px-4 py-3 flex space-x-2">
    <!-- Mark Present -->
    <form method="POST" action="{{ route('teacher.attendance.mark') }}">
        @csrf
        <input type="hidden" name="teacher_id" value="{{ $attendance->teacher_id }}">
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="status" value="Present">
        <button type="submit" 
            class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm" title="Present">
            P
        </button>
    </form>

    <!-- Mark Absent -->
    <form method="POST" action="{{ route('teacher.attendance.mark') }}">
        @csrf
        <input type="hidden" name="teacher_id" value="{{ $attendance->teacher_id }}">
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="status" value="Absent">
        <button type="submit" 
            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm" title="Absent">
            A
        </button>
    </form>
    
       <form method="POST" action="{{ route('teacher.attendance.mark') }}">
        @csrf
        <input type="hidden" name="teacher_id" value="{{ $attendance->teacher_id }}">
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="status" value="late">
        <button type="submit" 
            class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm" title="Late">
            L
        </button>
    </form>

    <!-- Mark Leave -->
    <form method="POST" action="{{ route('teacher.attendance.mark') }}">
        @csrf
        <input type="hidden" name="teacher_id" value="{{ $attendance->teacher_id }}">
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="status" value="On Leave">
        <button type="submit" 
            class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm" title="On Leave">
            Leave
        </button>
    </form>
    
    
</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                No attendance records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    
    </div>
</div>

@include('client.schoolPanel.layout.footer')
