@extends('client.teacher.layout.master')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold text-gray-800">
        Student Services / <span class="text-l text-gray-500">Leave Applications</span>
    </h1>
</div>

{{-- Leave Applications List Table --}}
<div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Student Leave Applications</h2>
        <div class="flex space-x-2">
            <a href="{{ route('teacher.leaveApplications') }}" class="px-4 py-2 rounded-lg {{ !$status ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">All</a>
            <a href="{{ route('teacher.leaveApplications', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg {{ $status == 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">Pending</a>
            <a href="{{ route('teacher.leaveApplications', ['status' => 'approved']) }}" class="px-4 py-2 rounded-lg {{ $status == 'approved' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">Approved</a>
            <a href="{{ route('teacher.leaveApplications', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-lg {{ $status == 'rejected' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">Rejected</a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 mb-4 rounded-lg">{{ session('success') }}</div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-4 mb-4 rounded-lg">{{ session('error') }}</div>
    @endif
    
    @if(isset($message))
        <div class="bg-blue-100 text-blue-700 p-4 mb-4 rounded-lg">{{ $message }}</div>
    @endif
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                    <th class="px-6 py-3 text-xs">Leave ID</th>
                    <th class="px-6 py-3 text-xs">Student</th>
                    <th class="px-6 py-3 text-xs">Reason</th>
                    <th class="px-6 py-3 text-xs">From Date</th>
                    <th class="px-6 py-3 text-xs">To Date</th>
                    <th class="px-6 py-3 text-xs">Days</th>
                    <th class="px-6 py-3 text-xs">Status</th>
                    <th class="px-6 py-3 text-xs">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                @if($leaves->count() > 0)
                    @foreach($leaves as $leave)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">{{ $leave->leave_id }}</td>
                            <td class="px-6 py-4">
                                @if($leave->student)
                                    {{ $leave->student->first_name }} {{ $leave->student->last_name }}<br>
                                    <span class="text-xs text-gray-500">{{ $leave->student->student_id }}</span>
                                @else
                                    <span class="text-gray-500">Unknown Student</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $leave->reason }}</td>
                            <td class="px-6 py-4">{{ $leave->from_date->format('d M Y') }}</td>
                            <td class="px-6 py-4">{{ $leave->to_date->format('d M Y') }}</td>
                            <td class="px-6 py-4">{{ $leave->leave_days }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($leave->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($leave->status == 'approved') bg-green-100 text-green-800
                                    @elseif($leave->status == 'rejected') bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($leave->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('teacher.leaveApplications.show', $leave->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">View</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-lg font-medium">No leave applications found</p>
                                <p class="mt-1">There are no leave applications matching your filter criteria</p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $leaves->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Any JavaScript you need to run
    });
</script>
@endsection 