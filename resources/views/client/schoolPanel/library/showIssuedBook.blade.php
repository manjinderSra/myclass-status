@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">
                    Library / <a href="{{ route('school.issueBooks') }}" class="text-l text-gray-500 hover:text-gray-700">Issued Books</a> / <span class="text-l text-gray-500">{{ $issuedBook->issue_id }}</span>
                </h1>
            </div>

            <div class="flex space-x-3">
                {{-- show buttons only when NOT returned and NOT lost --}}
                @if(!$issuedBook->is_returned && !$issuedBook->is_lost)
                    <a href="{{ route('school.issuedBook.edit', $issuedBook->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                        Edit
                    </a>

                    <button id="returnBookBtn" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        Return Book
                    </button>

                    <button id="lostBookBtn" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                        Lost Book
                    </button>
                @endif

                <a href="{{ route('school.issueBooks') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                    Back
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <span class="text-xs text-gray-500">Issue ID</span>
                    <h2 class="text-xl font-semibold text-blue-600">{{ $issuedBook->issue_id }}</h2>
                </div>

                <div class="text-right">
                    <span class="text-xs text-gray-500">Status</span>
                    <div class="mt-1">
                        @if($issuedBook->is_returned)
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Returned on {{ date('d M Y', strtotime($issuedBook->return_date)) }}</span>
                        @elseif($issuedBook->is_lost)
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-sm">Lost on {{ date('d M Y', strtotime($issuedBook->lost_date)) }}</span>
                        @else
                            @if(strtotime($issuedBook->due_date) < strtotime('today'))
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-sm">Overdue by {{ ceil((time() - strtotime($issuedBook->due_date)) / 86400) }} days</span>
                            @else
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Issued</span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="border rounded-lg p-4 bg-gray-50">
                    <h3 class="text-lg font-semibold mb-3 text-gray-700 border-b pb-2">Student Details</h3>
                    <div class="mb-4 flex items-start">
                        <img src="https://via.placeholder.com/80" alt="Student" class="w-16 h-16 rounded-full mr-3">
                        <div>
                            <p class="font-semibold text-lg">{{ $issuedBook->student_name }}</p>
                            <p class="text-gray-600">{{ $issuedBook->student_class }}</p>
                            <p class="text-gray-600">ID: {{ $issuedBook->student_id }}</p>
                        </div>
                    </div>
                </div>

                <div class="border rounded-lg p-4 bg-gray-50">
                    <h3 class="text-lg font-semibold mb-3 text-gray-700 border-b pb-2">Book Details</h3>
                    <div class="mb-4">
                        <p class="font-semibold text-lg">{{ $issuedBook->book_name }}</p>
                        <p class="text-gray-600">Book ID: {{ $issuedBook->book_id }}</p>
                        <p class="text-gray-600">Book No: {{ $issuedBook->book_no }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Issue Details --}}
                <div class="border rounded-lg p-4 bg-gray-50">
                    <h3 class="text-lg font-semibold mb-3 text-gray-700 border-b pb-2">Issue Details</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-sm text-gray-500">Issue Date</p>
                            <p class="font-medium">{{ date('d M Y', strtotime($issuedBook->issue_date)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Due Date</p>
                            <p class="font-medium">{{ date('d M Y', strtotime($issuedBook->due_date)) }}</p>
                        </div>
                        <div class="col-span-2 mt-2">
                            <p class="text-sm text-gray-500">Issue Remarks</p>
                            <p class="font-medium">{{ $issuedBook->issue_remarks ?? 'No remarks' }}</p>
                        </div>
                    </div>
                </div>

                {{-- If returned show Return Details (keeps same as before) --}}
                @if($issuedBook->is_returned)
                <div class="border rounded-lg p-4 bg-gray-50">
                    <h3 class="text-lg font-semibold mb-3 text-gray-700 border-b pb-2">Return Details</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-sm text-gray-500">Return Date</p>
                            <p class="font-medium">{{ date('d M Y', strtotime($issuedBook->return_date)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Days Issued</p>
                            <p class="font-medium">{{ ceil((strtotime($issuedBook->return_date) - strtotime($issuedBook->issue_date)) / 86400) }} days</p>
                        </div>
                        <div class="col-span-2 mt-2">
                            <p class="text-sm text-gray-500">Return Remarks</p>
                            <p class="font-medium">{{ $issuedBook->return_remarks ?? 'No remarks' }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- If lost show Lost Details --}}
                @if($issuedBook->is_lost)
                <div class="border rounded-lg p-4 bg-gray-50">
                    <h3 class="text-lg font-semibold mb-3 text-gray-700 border-b pb-2">Lost Book Details</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-sm text-gray-500">Lost Date</p>
                            <p class="font-medium">{{ date('d M Y', strtotime($issuedBook->lost_date)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Days Issued</p>
                            <p class="font-medium">{{ ceil((strtotime($issuedBook->lost_date) - strtotime($issuedBook->issue_date)) / 86400) }} days</p>
                        </div>
                        <div class="col-span-2 mt-2">
                            <p class="text-sm text-gray-500">Lost Remarks</p>
                            <p class="font-medium">{{ $issuedBook->lost_remarks ?? 'No remarks' }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Return Book Modal --}}
<div id="returnBookModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Return Book</h2>
        <button id="closeReturnBookModalBtn" type="button" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>

        <form action="{{ route('school.issuedBook.return', $issuedBook->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="return_date" class="block mb-2 font-medium text-gray-700">Return Date</label>
                <input type="date" id="return_date" name="return_date" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ date('Y-m-d') }}" />
            </div>
            <div>
                <label for="return_remarks" class="block mb-2 font-medium text-gray-700">Return Remarks (Optional)</label>
                <textarea id="return_remarks" name="return_remarks" rows="3" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Add any remarks about the book's condition, late return, etc."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancelReturnBookBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Return Book</button>
            </div>
        </form>
    </div>
</div>

{{-- Lost Book Modal --}}
<div id="lostBookModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Mark Book as Lost</h2>
        <button id="closeLostBookModalBtn" type="button" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>

        <form action="{{ route('school.issuedBook.lost', $issuedBook->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-2 font-medium text-gray-700">Lost Date</label>
                <input type="date" name="lost_date" required class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-red-500" value="{{ date('Y-m-d') }}">
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Lost Remarks (Optional)</label>
                <textarea name="lost_remarks" rows="3" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-red-500"></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" id="cancelLostBookBtn" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Mark Lost</button>
            </div>
        </form>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Elements
    const returnBookBtn = document.getElementById('returnBookBtn');
    const returnBookModal = document.getElementById('returnBookModal');
    const closeReturnBookModalBtn = document.getElementById('closeReturnBookModalBtn');
    const cancelReturnBookBtn = document.getElementById('cancelReturnBookBtn');

    const lostBookBtn = document.getElementById('lostBookBtn');
    const lostBookModal = document.getElementById('lostBookModal');
    const closeLostBookModalBtn = document.getElementById('closeLostBookModalBtn');
    const cancelLostBookBtn = document.getElementById('cancelLostBookBtn');

    // Safe modal open/close handlers (only if elements exist)
    if (returnBookBtn && returnBookModal) {
        returnBookBtn.addEventListener('click', () => returnBookModal.classList.remove('hidden'));
    }
    if (closeReturnBookModalBtn && returnBookModal) {
        closeReturnBookModalBtn.addEventListener('click', () => returnBookModal.classList.add('hidden'));
    }
    if (cancelReturnBookBtn && returnBookModal) {
        cancelReturnBookBtn.addEventListener('click', () => returnBookModal.classList.add('hidden'));
    }

    if (lostBookBtn && lostBookModal) {
        lostBookBtn.addEventListener('click', () => lostBookModal.classList.remove('hidden'));
    }
    if (closeLostBookModalBtn && lostBookModal) {
        closeLostBookModalBtn.addEventListener('click', () => lostBookModal.classList.add('hidden'));
    }
    if (cancelLostBookBtn && lostBookModal) {
        cancelLostBookBtn.addEventListener('click', () => lostBookModal.classList.add('hidden'));
    }

    // Flash messages via SweetAlert (if included in layout)
    @if(session('success'))
        Swal.fire({
            title: 'Success!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            title: 'Error!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonText: 'OK'
        });
    @endif
});
</script>
