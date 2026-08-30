@extends('client.student.layouts.master')

@section('title', 'My Attendance')

@section('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">
            My Attendance
        </h1>
    </div>

    {{-- Attendance Overview Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
        {{-- Total Days --}}
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-gray-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gray-100 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Days</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalDays }}</h3>
                </div>
            </div>
        </div>

        {{-- Present Days --}}
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Present</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $present }}</h3>
                </div>
            </div>
        </div>

        {{-- Absent Days --}}
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Absent</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $absent }}</h3>
                </div>
            </div>
        </div>

        {{-- Late Days --}}
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Late</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $late }}</h3>
                </div>
            </div>
        </div>

        {{-- Attendance Percentage --}}
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Percentage</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $attendancePercentage }}%</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Date Range Filter --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Filter Attendance</h2>
        <form id="dateFilterForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Filter Records
                </button>
            </div>
        </form>
    </div>

    {{-- Attendance Records Table --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold">Attendance Records</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="attendanceTableBody">
                    @foreach($attendanceRecords as $record)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($record->attendance_date)->format('d M, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColor = match($record->status) {
                                        'present' => 'bg-green-100 text-green-800',
                                        'absent' => 'bg-red-100 text-red-800',
                                        'late' => 'bg-yellow-100 text-yellow-800',
                                        'leave' => 'bg-blue-100 text-blue-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="{{ $statusColor }} px-2 py-1 rounded-full text-xs font-medium">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $record->remarks ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Handle date filter form submission
    document.getElementById('dateFilterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        
        // Fetch filtered attendance data
        fetch(`/student/attendance/data?start_date=${startDate}&end_date=${endDate}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update statistics
                updateStatistics(data.data.statistics);
                // Update table
                updateTable(data.data.records);
            } else {
                alert(data.message || 'An error occurred while fetching attendance data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching attendance data');
        });
    });
    
    // Function to update statistics cards
    function updateStatistics(stats) {
        document.querySelector('[data-stat="total_days"]').textContent = stats.total_days;
        document.querySelector('[data-stat="present"]').textContent = stats.present;
        document.querySelector('[data-stat="absent"]').textContent = stats.absent;
        document.querySelector('[data-stat="late"]').textContent = stats.late;
        document.querySelector('[data-stat="percentage"]').textContent = stats.attendance_percentage + '%';
    }
    
    // Function to update attendance table
    function updateTable(records) {
        const tableBody = document.getElementById('attendanceTableBody');
        tableBody.innerHTML = '';
        
        records.forEach(record => {
            const date = new Date(record.attendance_date).toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            
            let statusColor = 'bg-gray-100 text-gray-800';
            switch(record.status) {
                case 'present':
                    statusColor = 'bg-green-100 text-green-800';
                    break;
                case 'absent':
                    statusColor = 'bg-red-100 text-red-800';
                    break;
                case 'late':
                    statusColor = 'bg-yellow-100 text-yellow-800';
                    break;
                case 'leave':
                    statusColor = 'bg-blue-100 text-blue-800';
                    break;
            }
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${date}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="${statusColor} px-2 py-1 rounded-full text-xs font-medium">
                        ${record.status.charAt(0).toUpperCase() + record.status.slice(1)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${record.remarks || '-'}
                </td>
            `;
            
            tableBody.appendChild(row);
        });
    }
});
</script>
@endsection 