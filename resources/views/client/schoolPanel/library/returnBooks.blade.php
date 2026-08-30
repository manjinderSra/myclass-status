@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                Library / <span class="text-l text-gray-500">Returned Books</span>
            </h1>
        </div>

        {{-- FILTER SECTION --}}
        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300 mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Filter</h2>
            <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="from_date" class="block mb-2 font-medium text-gray-700">From Date</label>
                    <input type="date" id="from_date" name="from_date"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <div>
                    <label for="to_date" class="block mb-2 font-medium text-gray-700">To Date</label>
                    <input type="date" id="to_date" name="to_date"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <div>
                    <label for="student_id" class="block mb-2 font-medium text-gray-700">Student ID</label>
                    <input type="text" id="student_id" name="student_id"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter student ID" />
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Apply Filter</button>
                    <button type="button" id="resetFilter"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 ml-2">Reset</button>
                </div>
            </form>
        </div>

        {{-- RETURNED BOOKS TABLE --}}
        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Returned Books</h2>

            <table id="returnedBooksTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 text-xs">Issue ID</th>
                        <th class="px-6 py-3 text-xs">Return Date</th>
                        <th class="px-6 py-3 text-xs">Student</th>
                        <th class="px-6 py-3 text-xs">Book</th>
                        <th class="px-6 py-3 text-xs">Issue Date</th>
                        <th class="px-6 py-3 text-xs">Status</th>   {{-- ADDED --}}
                        <th class="px-6 py-3 text-xs">Days Issued</th>
                        <th class="px-6 py-3 text-xs">Remarks</th>
                        <th class="px-6 py-3 text-xs">Actions</th>
                    </tr>
                </thead>

                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @if(isset($returnedBooks) && count($returnedBooks) > 0)
                        @foreach($returnedBooks as $book)
<tr class="hover:bg-gray-50 transition-colors">

    <td class="px-6 py-4">{{ $book->issue_id }}</td>

    {{-- Correct Returned/Lost Date --}}
    <td class="px-6 py-4">
        {{ date('d M Y', strtotime($book->final_date)) }}
    </td>

    <td class="px-6 py-4">
        <div class="flex items-center">
            {{-- <img src="https://via.placeholder.com/30" class="w-8 h-8 rounded-full mr-2"> --}}
            <span>
                {{ $book->student_name }}<br>
                <span class="text-xs text-gray-500">{{ $book->student_class }}</span>
            </span>
        </div>
    </td>

    <td class="px-6 py-4">{{ $book->book_name }}</td>

    <td class="px-6 py-4">{{ date('d M Y', strtotime($book->issue_date)) }}</td>

    {{-- STATUS --}}
    <td class="px-6 py-4">
        @if($book->status === "Returned")
            <span class="px-2 py-1 bg-green-200 text-green-800 rounded text-xs">Returned</span>
        @elseif($book->status === "Lost")
            <span class="px-2 py-1 bg-red-200 text-red-800 rounded text-xs">Lost</span>
        @endif
    </td>

    {{-- Days Issued --}}
    <td class="px-6 py-4">
        {{ ceil((strtotime($book->final_date) - strtotime($book->issue_date)) / 86400) }}
    </td>

    <td class="px-6 py-4">{{ $book->return_remarks ?? 'No remarks' }}</td>

    <td class="px-6 py-4">
        <a href="{{ route('school.issuedBook.show', $book->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">
            View
        </a>
    </td>

</tr>

                        @endforeach

                    @else
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                    <p class="text-lg font-medium">No returned books found</p>
                                    <p class="mt-1">Try adjusting your filters or check back later</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

{{-- DATATABLES --}}
<link rel="stylesheet"
    href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script
    src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {

        $('#returnedBooksTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search returned books..."
            },
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
            dom:
                "<'flex justify-between items-center mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
                "t" +
                "<'flex justify-between items-center mt-4'<'dataTables_info'i><'dataTables_paginate'p>>",
        });

        $('#filterForm').submit(function(e) {
            e.preventDefault();

            const fromDate = $('#from_date').val();
            const toDate = $('#to_date').val();
            const studentId = $('#student_id').val();

            let query = [];

            if (fromDate) query.push("from_date=" + fromDate);
            if (toDate) query.push("to_date=" + toDate);
            if (studentId) query.push("student_id=" + studentId);

            window.location.href =
                "{{ route('school.returnBooks') }}" + (query.length ? "?" +
                    query.join("&") : "");
        });

        $('#resetFilter').click(function() {
            window.location.href = "{{ route('school.returnBooks') }}";
        });

    });
</script>
