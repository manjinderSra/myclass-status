@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                Library / <span class="text-l text-gray-500">Issued Books</span>
            </h1>
            <button type="button"
                onclick="openIssueModal()"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Issue Book +
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            <table id="issueBooksTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 text-xs">ID</th>
                        <th class="px-6 py-3 text-xs">Date of Issue</th>
                        <th class="px-6 py-3 text-xs">Due Date</th>
                        <th class="px-6 py-3 text-xs">Student</th>
                        <th class="px-6 py-3 text-xs">Book</th>
                        <th class="px-6 py-3 text-xs">Status</th>
                        <th class="px-6 py-3 text-xs">Remarks</th>
                        <th class="px-6 py-3 text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                
                    @forelse($issuedBooks as $issued)
                        <tr>
                            <td class="px-6 py-4">{{ $issued->issue_id }}</td>
                            <td class="px-6 py-4">{{ date('d M Y', strtotime($issued->issue_date)) }}</td>
                            <td class="px-6 py-4">{{ date('d M Y', strtotime($issued->due_date)) }}</td>

                            <td class="px-6 py-4">
                                <div>
                                    <p>{{ $issued->student_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $issued->student_class }}</p>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <p>{{ $issued->book_name }}</p>
                                <p class="text-xs text-gray-500">Copy: {{ $issued->book_no }}</p>
                            </td>

                            <td class="px-6 py-4">
    @if($issued->is_returned)
        <span class="px-2 py-1 bg-green-200 text-green-800 rounded text-xs">
            Returned
        </span>

    @elseif($issued->is_lost)
        <span class="px-2 py-1 bg-red-200 text-red-800 rounded text-xs">
            Lost
        </span>

    @else
        @if(strtotime($issued->due_date) < strtotime('today'))
            <span class="px-2 py-1 bg-red-300 text-red-900 rounded text-xs">
                Overdue
            </span>
        @else
            <span class="px-2 py-1 bg-blue-200 text-blue-800 rounded text-xs">
                Issued
            </span>
        @endif
    @endif
</td>


                            <td class="px-6 py-4">{{ $issued->issue_remarks }}</td>

                            <td class="px-6 py-4">
                                <a href="{{ route('school.issuedBook.show',$issued->id) }}"
                                    class="text-blue-600 hover:text-blue-900">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                No issued books found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="issueBookModal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">

    <div class="bg-white rounded-lg max-w-2xl w-full p-6 relative">

        <h2 class="text-xl font-semibold mb-4">Issue Book</h2>

        <button onclick="closeIssueModal()" class="absolute top-4 right-4 text-gray-600">
            ✖
        </button>

        <form id="issueBookForm" class="grid grid-cols-2 gap-4">
            @csrf

            {{-- Student ID --}}
            <div>
                <label class="block mb-1">Student ID</label>
                <div class="flex">
                    <input id="student_id" name="student_id" type="text" class="border px-3 py-2 w-full rounded-l">
                    <button type="button" onclick="fetchStudent()"
                        class="bg-blue-600 text-white px-3 rounded-r">Search</button>
                </div>
            </div>

            {{-- Student Details --}}
            <div class="row-span-2">
                <label class="block mb-1">Student Details</label>

                <div id="studentDetailsBox" class="border p-3 h-32 overflow-y-auto bg-gray-50">
                    <p class="text-gray-400" id="studentPlaceholder">Search a student...</p>

                    <div id="studentDetails" class="hidden">
                        <p><b>Name:</b> <span id="s_name"></span></p>
                        <p><b>ID:</b> <span id="s_id"></span></p>
                        <p><b>Class:</b> <span id="s_class"></span></p>

                        <input type="hidden" name="student_name" id="student_name_hidden">
                        <input type="hidden" name="student_class" id="student_class_hidden">
                        <input type="hidden" name="student_id" id="student_id_hidden">

                    </div>
                </div>
            </div>

            {{-- Book ID --}}
            <div>
                <label class="block mb-1">Book ID</label>
                <div class="flex">
                    <input id="book_id_issue" name="book_id" type="text" class="border px-3 py-2 w-full rounded-l">
                    <button type="button" onclick="fetchBook()" 
                        class="bg-blue-600 text-white px-3 rounded-r">Search</button>
                </div>
            </div>

            {{-- Book Details --}}
            <div class="row-span-2">
                <label class="block mb-1">Book Details</label>

                <div id="bookDetailsBox" class="border p-3 h-32 overflow-y-auto bg-gray-50">
                    <p id="bookPlaceholder" class="text-gray-400">Search a book...</p>

                    <div id="bookDetails" class="hidden">
                        <p><b>Name:</b> <span id="b_name"></span></p>
                        <p><b>Author:</b> <span id="b_author"></span></p>
                        <p><b>Subject:</b> <span id="b_subject"></span></p>
                        <p><b>Total Copies:</b> <span id="b_total"></span></p>
                        <p><b>Available:</b> <span id="b_available" class="text-green-600"></span></p>

                        <label class="mt-2 block font-medium">Select Copy</label>
                        <select id="book_copy_select"
                            class="border rounded w-full px-2 py-1"
                            onchange="selectCopy(this)">
                            <option value="">Select available copy</option>
                        </select>

                        <input type="hidden" name="book_name" id="book_name_hidden">
                        <input type="hidden" name="book_number_id" id="book_number_id_hidden">
                        <input type="hidden" name="book_id" id="book_id_hidden">

                    </div>
                </div>
            </div>

            {{-- Dates --}}
            <div>
                <label>Issue Date</label>
                <input type="date" name="issue_date" id="issue_date"
                    class="border px-3 py-2 w-full rounded">
            </div>

            <div>
                <label>Due Date</label>
                <input type="date" name="due_date" id="due_date"
                    class="border px-3 py-2 w-full rounded">
            </div>

            <div class="col-span-2">
                <label>Remarks</label>
                <textarea name="issue_remarks" id="issue_remarks"
                    class="border px-3 py-2 w-full rounded"></textarea>
            </div>

            <div class="col-span-2 text-right">
                <button type="button" onclick="closeIssueModal()" class="px-4 py-2 bg-gray-300 rounded">
                    Cancel
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded">
                    Issue Book
                </button>
            </div>
        </form>
    </div>
</div>
@include('client.schoolPanel.layout.footer')

<script>
document.addEventListener("DOMContentLoaded", function () {

    window.openIssueModal = function () {
        document.getElementById('issueBookModal').classList.remove('hidden');
    };

    window.closeIssueModal = function () {
        document.getElementById('issueBookModal').classList.add('hidden');
    };

    window.fetchStudent = function () {
        let id = document.getElementById('student_id').value;

        fetch(`/school/library/fetch-student/${id}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    document.getElementById('studentPlaceholder').innerText = "Student not found";
                    return;
                }

                let s = data.student;
                document.getElementById('studentPlaceholder').classList.add('hidden');
                document.getElementById('studentDetails').classList.remove('hidden');

                document.getElementById('s_name').innerText = s.name;
                document.getElementById('s_id').innerText = s.student_id;
                document.getElementById('s_class').innerText = s.class;

                document.getElementById('student_name_hidden').value = s.name;
                document.getElementById('student_class_hidden').value = s.class;
                document.getElementById('student_id_hidden').value = s.student_id;

            });
    };

    window.fetchBook = function () {
        let id = document.getElementById('book_id_issue').value;

        fetch(`/school/library/fetch-book/${id}`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    document.getElementById('bookPlaceholder').innerText = "Book not found";
                    return;
                }

                let b = data.book;

                document.getElementById('bookPlaceholder').classList.add('hidden');
                document.getElementById('bookDetails').classList.remove('hidden');

                document.getElementById('b_name').innerText = b.book_name;
                document.getElementById('b_author').innerText = b.author;
                document.getElementById('b_subject').innerText = b.subject;
                document.getElementById('b_total').innerText = b.total_copies;
                document.getElementById('b_available').innerText = b.available_copies;

                document.getElementById('book_id_hidden').value = b.book_id;
                document.getElementById('book_name_hidden').value = b.book_name;

let copySelect = document.getElementById('book_copy_select');
copySelect.innerHTML = `<option value="">Select available copy</option>`;

if (Array.isArray(b.manual_book_numbers)) {
    b.manual_book_numbers.forEach(copy => {
        if (copy.status === "return") {
            copySelect.innerHTML += `<option value="${copy.id}">${copy.book_no}</option>`;
        }
    });
} else {
    console.warn("manual_book_numbers missing");
    copySelect.innerHTML = `<option>No copies available</option>`;
}

            });
    };

    window.selectCopy = function (e) {
        document.getElementById('book_number_id_hidden').value = e.value;
    };

    document.getElementById('issueBookForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let fd = new FormData(this);

        fetch(`{{ route('school.issueBook.store') }}`, {
            method: "POST",
            headers: {
                "Accept": "application/json"
            },
            body: fd
        })

        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("Book issued successfully!");
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(err => alert("Error: " + err));
    });

});
</script>

