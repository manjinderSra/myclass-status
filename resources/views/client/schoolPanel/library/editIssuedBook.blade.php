@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">
                    Library / <a href="{{ route('school.issueBooks') }}" class="text-l text-gray-500 hover:text-gray-700">Issued Books</a> / <span class="text-l text-gray-500">Edit {{ $issuedBook->issue_id }}</span>
                </h1>
            </div>
            <div>
                <a href="{{ route('school.issuedBook.show', $issuedBook->id) }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                    Cancel
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
                        @if(strtotime($issuedBook->due_date) < strtotime('today'))
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-sm">Overdue by {{ ceil((time() - strtotime($issuedBook->due_date)) / 86400) }} days</span>
                        @else
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Issued</span>
                        @endif
                    </div>
                </div>
            </div>

            <form action="{{ route('school.issuedBook.update', $issuedBook->id) }}" method="POST">
                @csrf
                @method('PUT')
                
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
                    <div class="border rounded-lg p-4">
                        <h3 class="text-lg font-semibold mb-3 text-gray-700 border-b pb-2">Issue Details</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="issue_date" class="block mb-1 font-medium text-gray-700">Issue Date</label>
                                <input type="text" readonly value="{{ date('d M Y', strtotime($issuedBook->issue_date)) }}" class="w-full px-3 py-2 border rounded bg-gray-100" />
                            </div>
                            <div>
                                <label for="due_date" class="block mb-1 font-medium text-gray-700">Due Date</label>
                                <input type="date" id="due_date" name="due_date" value="{{ $issuedBook->due_date }}" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                            <div class="col-span-2 mt-2">
                                <label for="issue_remarks" class="block mb-1 font-medium text-gray-700">Issue Remarks</label>
                                <textarea id="issue_remarks" name="issue_remarks" rows="3" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $issuedBook->issue_remarks }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('school.issuedBook.show', $issuedBook->id) }}" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</a>
                    <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Display flash messages if available
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
