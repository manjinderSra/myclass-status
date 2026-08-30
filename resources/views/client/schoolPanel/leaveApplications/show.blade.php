@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                Student Services / <span class="text-l text-gray-500">Leave Application Details</span>
            </h1>
            <a href="{{ route('school.leaveApplications') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                Back to Applications
            </a>
        </div>

        {{-- Leave Application Details --}}
        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Application: {{ $leave->leave_id }}</h2>
                <span class="px-4 py-1 rounded-full text-sm font-medium
                    @if($leave->status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($leave->status == 'approved') bg-green-100 text-green-800
                    @elseif($leave->status == 'rejected') bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($leave->status) }}
                </span>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-4 mb-4 rounded-lg">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-4 mb-4 rounded-lg">{{ session('error') }}</div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-md font-semibold mb-3 text-gray-700">Student Information</h3>
                    <div class="bg-gray-50 rounded-lg overflow-hidden border">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-100">Name</th>
                                <td class="px-4 py-3 text-sm">{{ $leave->student->first_name }} {{ $leave->student->last_name }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-100">Student ID</th>
                                <td class="px-4 py-3 text-sm">{{ $leave->student->student_id }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-100">Class & Section</th>
                                <td class="px-4 py-3 text-sm">
                                    {{ $leave->student->class->name ?? 'N/A' }} - 
                                    {{ $leave->student->section->name ?? 'N/A' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-md font-semibold mb-3 text-gray-700">Leave Information</h3>
                    <div class="bg-gray-50 rounded-lg overflow-hidden border">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-100">Leave ID</th>
                                <td class="px-4 py-3 text-sm">{{ $leave->leave_id }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-100">Duration</th>
                                <td class="px-4 py-3 text-sm">
                                    {{ $leave->from_date->format('d M Y') }} to {{ $leave->to_date->format('d M Y') }}
                                    <span class="ml-2 text-gray-500">({{ $leave->leave_days }} days)</span>
                                </td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-100">Reason</th>
                                <td class="px-4 py-3 text-sm">{{ $leave->reason }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-100">Submitted On</th>
                                <td class="px-4 py-3 text-sm">{{ $leave->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mb-6">
                <h3 class="text-md font-semibold mb-3 text-gray-700">Description</h3>
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-blue-500">
                    {{ $leave->description }}
                </div>
            </div>

            @if($leave->attachment_path)
            <div class="mb-6">
                <h3 class="text-md font-semibold mb-3 text-gray-700">Attachment</h3>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    <a href="{{ asset('storage/' . $leave->attachment_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium">
                        View Attachment
                    </a>
                </div>
            </div>
            @endif
            
            @if($leave->admin_remarks)
            <div class="mb-6">
                <h3 class="text-md font-semibold mb-3 text-gray-700">Admin Remarks</h3>
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 
                    @if($leave->status == 'approved') border-green-500
                    @elseif($leave->status == 'rejected') border-red-500
                    @else border-gray-500
                    @endif">
                    {{ $leave->admin_remarks }}
                </div>
                @if($leave->processed_at)
                <p class="text-sm text-gray-500 mt-2">
                    Processed by: {{ $leave->processor->name ?? 'Unknown' }} on {{ $leave->processed_at->format('d M Y, h:i A') }}
                </p>
                @endif
            </div>
            @endif
            
            @if($leave->status == 'pending')
            <div>
                <h3 class="text-md font-semibold mb-3 text-gray-700">Process Leave Application</h3>
                <form id="updateStatusForm" action="{{ route('school.leaveApplications.updateStatus', $leave->id) }}" method="POST" class="bg-gray-50 p-4 rounded-lg">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-3">
                            <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Status</option>
                                <option value="pending" {{ $leave->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="md:col-span-7">
                            <div id="remarks-group" style="display: none;">
                                <label for="admin_remarks" class="block mb-2 text-sm font-medium text-gray-700">Remarks</label>
                                <textarea name="admin_remarks" id="admin_remarks" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your remarks about this leave application"></textarea>
                            </div>
                        </div>
                        <div class="md:col-span-2 flex items-end">
                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                Update Status
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const remarksGroup = document.getElementById('remarks-group');
        
        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                const status = this.value;
                if (status === 'rejected') {
                    remarksGroup.style.display = 'block';
                    document.getElementById('admin_remarks').setAttribute('required', 'required');
                } else {
                    remarksGroup.style.display = status === 'approved' ? 'block' : 'none';
                    if (status !== 'rejected') {
                        document.getElementById('admin_remarks').removeAttribute('required');
                    }
                }
            });
            
            // Trigger on page load if status is pre-selected
            statusSelect.dispatchEvent(new Event('change'));
        }
    });
</script> 