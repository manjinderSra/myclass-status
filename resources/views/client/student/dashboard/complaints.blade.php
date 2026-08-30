@extends('client.student.layouts.master')

@section('title', 'Complaint Box')

@section('content')
    <!-- Debug Information - Remove in Production -->
   
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Complaint Box</h2>
            <button id="openComplaintModal" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Submit New Complaint
            </button>
        </div>
        
        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif
        
        <!-- Error Message -->
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif
        
        <!-- Recent Complaints -->
        <div class="overflow-x-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">My Recent Complaints</h3>
                <a href="{{ route('student.complaints.all') }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">View All</a>
            </div>
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Complaint ID</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nature</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted On</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($complaints) && count($complaints) > 0)
                        @foreach($complaints as $complaint)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $complaint->complaint_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $complaint->nature }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $complaint->created_at->format('d M Y, h:i A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($complaint->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($complaint->status == 'in_progress') bg-blue-100 text-blue-800
                                    @elseif($complaint->status == 'resolved') bg-green-100 text-green-800
                                    @elseif($complaint->status == 'rejected') bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="{{ route('student.complaints.view', $complaint->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No complaints submitted yet</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Complaint Submission Modal -->
    <div id="complaintModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 md:mx-auto">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Submit a New Complaint</h3>
                <button id="closeComplaintModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('student.complaints.submit') }}" method="POST" class="px-6 py-4">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="nature" class="block text-sm font-medium text-gray-700 mb-1">Nature of Complaint <span class="text-red-500">*</span></label>
                        <input type="text" id="nature" name="nature" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                            placeholder="E.g., Classroom, Facilities, Teacher, etc."
                            value="{{ old('nature') }}"
                            maxlength="255" required>
                        @error('nature')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="5" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Please provide details about your complaint..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Maximum 1000 characters. Please be clear and specific.</p>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 px-6 py-4 border-t mt-4">
                    <button type="button" id="cancelComplaintBtn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Submit Complaint
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const complaintModal = document.getElementById('complaintModal');
        const openComplaintModalBtn = document.getElementById('openComplaintModal');
        const closeComplaintModalBtn = document.getElementById('closeComplaintModal');
        const cancelComplaintBtn = document.getElementById('cancelComplaintBtn');
        
        function openModal() {
            complaintModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeModal() {
            complaintModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        openComplaintModalBtn.addEventListener('click', openModal);
        closeComplaintModalBtn.addEventListener('click', closeModal);
        cancelComplaintBtn.addEventListener('click', closeModal);
        
        // Close modal when clicking outside
        complaintModal.addEventListener('click', function(e) {
            if (e.target === complaintModal) {
                closeModal();
            }
        });
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !complaintModal.classList.contains('hidden')) {
                closeModal();
            }
        });
    });
</script>
@endsection 