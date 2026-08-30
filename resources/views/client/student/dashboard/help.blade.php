@extends("client.student.layouts.master")
@section("title", "Help & Support")
@section("content")
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
<div class="flex justify-between items-center mb-6"><h2 class="text-xl font-semibold text-gray-800">Help & Support</h2><button id="openSupportModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Submit Support Ticket</button></div>
@if(session("success"))<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session("success") }}</div>@endif
@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
@endif
<div class="bg-blue-50 rounded-lg p-6 mb-6"><h3 class="text-lg font-semibold mb-4">Contact Information</h3><div class="grid grid-cols-1 md:grid-cols-2 gap-4">@if(isset($school))<div><p class="text-sm text-gray-500">School Name</p><p class="font-medium">{{ $school->name }}</p></div><div><p class="text-sm text-gray-500">Email</p><p class="font-medium">{{ $school->email ?? "N/A" }}</p></div><div><p class="text-sm text-gray-500">Phone</p><p class="font-medium">{{ $school->phone ?? "N/A" }}</p></div><div><p class="text-sm text-gray-500">Address</p><p class="font-medium">{{ $school->address ?? "N/A" }}</p></div>@else<p>School information not available.</p>@endif</div></div>

<!-- FAQs -->
<div class="mb-6">
    <h3 class="text-lg font-semibold mb-4">Frequently Asked Questions</h3>
    
    @if(isset($faqs) && count($faqs) > 0)
        <div class="space-y-4">
            @foreach($faqs as $faq)
                <div class="border rounded-lg">
                    <div class="bg-gray-50 p-4">
                        <h4 class="font-medium">{{ $faq->question }}</h4>
                    </div>
                    <div class="p-4">
                        <p>{{ $faq->answer }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600">No FAQs available at the moment.</p>
    @endif
</div>
</div>

<!-- Support Ticket Modal -->
<div id="supportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-xl max-w-md mx-auto mt-20">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold">Submit Support Ticket</h3>
            <button id="closeSupportModal" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        
        <form action="{{ route('student.help.ticket') }}" method="POST" class="p-4">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block mb-1">Subject <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" class="w-full border rounded p-2" required>
                </div>
                
                <div>
                    <label class="block mb-1">Message <span class="text-red-500">*</span></label>
                    <textarea name="message" rows="4" class="w-full border rounded p-2" required></textarea>
                </div>
                
                <div>
                    <label class="block mb-1">Priority <span class="text-red-500">*</span></label>
                    <select name="priority" class="w-full border rounded p-2" required>
                        <option value="">Select Priority</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-4 pt-4 border-t">
                <button type="button" id="cancelSupportBtn" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('supportModal');
        const openBtn = document.getElementById('openSupportModal');
        const closeBtn = document.getElementById('closeSupportModal');
        const cancelBtn = document.getElementById('cancelSupportBtn');
        
        openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
        closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
        cancelBtn.addEventListener('click', () => modal.classList.add('hidden'));
        
        // Close when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.add('hidden');
        });
        
        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
            }
        });
    });
</script>
@endsection
