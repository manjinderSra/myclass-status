@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                Student Services / <span class="text-l text-gray-500">Complaint Details</span>
            </h1>
            <a href="{{ route('school.complaintBox') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                Back to Complaints
            </a>
        </div>

        {{-- Complaint Details --}}
        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Complaint: {{ $complaint->complaint_id }}</h2>
                <span class="px-4 py-1 rounded-full text-sm font-medium
                    @if($complaint->status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($complaint->status == 'in_progress') bg-blue-100 text-blue-800
                    @elseif($complaint->status == 'resolved') bg-green-100 text-green-800
                    @elseif($complaint->status == 'rejected') bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
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
                                <td class="px-4 py-3 text-sm">{{ $complaint->student->first_name }} {{ $complaint->student->last_name }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-100">Student ID</th>
                                <td class="px-4 py-3 text-sm">{{ $complaint->student->student_id }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-100">Class & Section</th>
                                <td class="px-4 py-3 text-sm">
                                    {{ $complaint->student->class->name ?? 'N/A' }} - 
                                    {{ $complaint->student->section->name ?? 'N/A' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-md font-semibold mb-3 text-gray-700">Complaint Information</h3>
                    <div class="bg-gray-50 rounded-lg overflow-hidden border">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-100">Complaint ID</th>
                                <td class="px-4 py-3 text-sm">{{ $complaint->complaint_id }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-100">Nature</th>
                                <td class="px-4 py-3 text-sm">{{ $complaint->nature }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase bg-gray-100">Submitted On</th>
                                <td class="px-4 py-3 text-sm">{{ $complaint->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mb-6">
                <h3 class="text-md font-semibold mb-3 text-gray-700">Complaint Description</h3>
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-blue-500">
                    {{ $complaint->description }}
                </div>
            </div>
            
            @if($complaint->response)
            <div class="mb-6">
                <h3 class="text-md font-semibold mb-3 text-gray-700">Response</h3>
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-green-500">
                    {{ $complaint->response }}
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    Responded by: {{ $complaint->resolver->name ?? 'Unknown' }} on {{ $complaint->resolved_at->format('d M Y, h:i A') }}
                </p>
            </div>
            @endif
            
            @if($complaint->status == 'pending' || $complaint->status == 'in_progress')
            <div>
                <h3 class="text-md font-semibold mb-3 text-gray-700">Update Status</h3>
                <form id="updateStatusForm" action="{{ route('school.complaintBox.updateStatus', $complaint->id) }}" method="POST" class="bg-gray-50 p-4 rounded-lg">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-3">
                            <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Status</option>
                                <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved">Resolved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="md:col-span-7">
                            <div id="response-group" style="display: none;">
                                <label for="response" class="block mb-2 text-sm font-medium text-gray-700">Response</label>
                                <textarea name="response" id="response" rows="3" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your response to the complaint"></textarea>
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
        const responseGroup = document.getElementById('response-group');
        
        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                const status = this.value;
                if (status === 'resolved' || status === 'rejected') {
                    responseGroup.style.display = 'block';
                    document.getElementById('response').setAttribute('required', 'required');
                } else {
                    responseGroup.style.display = 'none';
                    document.getElementById('response').removeAttribute('required');
                }
            });
            
            // Trigger on page load if status is pre-selected
            statusSelect.dispatchEvent(new Event('change'));
        }
    });
</script> 