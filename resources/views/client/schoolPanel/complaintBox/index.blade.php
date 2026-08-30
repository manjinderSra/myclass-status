@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                Student Services / <span class="text-l text-gray-500">Complaint Box</span>
            </h1>
        </div>

        {{-- Complaints List Table --}}
        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Student Complaints</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('school.complaintBox') }}" class="px-4 py-2 rounded-lg {{ !$status ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">All</a>
                    <a href="{{ route('school.complaintBox', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg {{ $status == 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">Pending</a>
                    <a href="{{ route('school.complaintBox', ['status' => 'in_progress']) }}" class="px-4 py-2 rounded-lg {{ $status == 'in_progress' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">In Progress</a>
                    <a href="{{ route('school.complaintBox', ['status' => 'resolved']) }}" class="px-4 py-2 rounded-lg {{ $status == 'resolved' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">Resolved</a>
                    <a href="{{ route('school.complaintBox', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-lg {{ $status == 'rejected' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition">Rejected</a>
                </div>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-4 mb-4 rounded-lg">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-4 mb-4 rounded-lg">{{ session('error') }}</div>
            @endif
            
            @if(isset($error))
                <div class="bg-red-100 text-red-700 p-4 mb-4 rounded-lg">{{ $error }}</div>
            @endif
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                            <th class="px-6 py-3 text-xs">Complaint ID</th>
                            <th class="px-6 py-3 text-xs">Student</th>
                            <th class="px-6 py-3 text-xs">Nature</th>
                            <th class="px-6 py-3 text-xs">Submitted On</th>
                            <th class="px-6 py-3 text-xs">Status</th>
                            <th class="px-6 py-3 text-xs">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                        @if($complaints->count() > 0)
                            @foreach($complaints as $complaint)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">{{ $complaint->complaint_id }}</td>
                                    <td class="px-6 py-4">
                                        @if($complaint->student)
                                            {{ $complaint->student->first_name }} {{ $complaint->student->last_name }}<br>
                                            <span class="text-xs text-gray-500">{{ $complaint->student->student_id }}</span>
                                        @else
                                            <span class="text-gray-500">Unknown Student</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $complaint->nature }}</td>
                                    <td class="px-6 py-4">{{ $complaint->created_at->format('d M Y, h:i A') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                            @if($complaint->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($complaint->status == 'in_progress') bg-blue-100 text-blue-800
                                            @elseif($complaint->status == 'resolved') bg-green-100 text-green-800
                                            @elseif($complaint->status == 'rejected') bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('school.complaintBox.show', $complaint->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-lg font-medium">No complaints found</p>
                                        <p class="mt-1">There are no complaints matching your filter criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $complaints->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Any JavaScript you need to run
    });
</script> 