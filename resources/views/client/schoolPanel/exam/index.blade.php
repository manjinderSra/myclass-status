@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold text-gray-800">Exam</h1>

    <div class="flex items-center space-x-4">
        <form method="GET" action="{{ route('school.exams.index') }}">
            <select name="session" onchange="this.form.submit()"
                class="border border-gray-300 rounded px-3 py-2 text-sm">
                <option value="">All Sessions</option>
                @foreach($sessions as $session)
                    <option value="{{ $session }}" {{ request('session') == $session ? 'selected' : '' }}>
                        {{ $session }}
                    </option>
                @endforeach
            </select>
        </form>

        <button id="openExamModal"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            + Create Exam
        </button>
    </div>
</div>


        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @forelse($exams as $session => $sessionExams)
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">
                    Academic Session: {{ $session }}
                </h2>

                <table class="w-full border border-gray-300 rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Start Date</th>
                            <th class="px-4 py-2 text-left">End Date</th>
                             <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Description</th>
                            <th class="px-4 py-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessionExams as $exam)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border-b">{{ $exam->name }}</td>
                                <td class="px-4 py-2 border-b">{{ $exam->start_date }}</td>
                                <td class="px-4 py-2 border-b">{{ $exam->end_date }}</td>
                                 <td class="px-4 py-2 border-b">
                <span class="px-2 py-1 rounded text-xs 
                    {{ $exam->status == 'upcoming' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $exam->status == 'ongoing' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $exam->status == 'completed' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $exam->status == 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                    {{ ucfirst($exam->status) }}
                </span>
            </td>
                                <td class="px-4 py-2 border-b">{{ $exam->description ?? '-' }}</td>
                                 <td class="px-4 py-2 border-b text-right">
        <button class="px-3 py-1 bg-yellow-500 text-white rounded editExamBtn"
            data-id="{{ $exam->id }}">
            Edit
        </button>
    </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <p class="text-gray-500">No exams found.</p>
        @endforelse
    </div>
</div>

<!-- Create Exam Modal -->
<div id="examModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Create Exam</h2>
        <form action="{{ route('school.exams.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Academic Session</label>
                <input type="text" name="academic_session" placeholder="e.g. 2024/25"
                    class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Exam Name</label>
                <input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Description</label>
                <textarea name="description" class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
            </div>
            <div class="mb-4">
    <label class="block text-gray-700 mb-1">Status</label>
    <select name="status" class="w-full border border-gray-300 rounded px-3 py-2" required>
        <option value="upcoming">Upcoming</option>
        <option value="ongoing">Ongoing</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
    </select>
</div>

            <div class="flex justify-end space-x-4">
                <button type="button" id="closeExamModal"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>



<!-- Edit Exam Modal -->
<!-- Edit Exam Modal -->
<div id="editExamModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-semibold mb-4">Edit Exam</h2>

        <!-- Edit Exam Form -->
        <form id="editExamForm" action="{{ route('school.exams.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Hidden ID Field -->
            <input type="hidden" name="id" id="edit_id">

            <!-- Academic Session -->
            <div class="mb-4">
                <label for="edit_academic_session" class="block text-gray-700 mb-1">Academic Session</label>
                <input type="text" name="academic_session" id="edit_academic_session"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Exam Name -->
            <div class="mb-4">
                <label for="edit_name" class="block text-gray-700 mb-1">Exam Name</label>
                <input type="text" name="name" id="edit_name"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Start Date -->
            <div class="mb-4">
                <label for="edit_start_date" class="block text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" id="edit_start_date"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- End Date -->
            <div class="mb-4">
                <label for="edit_end_date" class="block text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" id="edit_end_date"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="edit_description" class="block text-gray-700 mb-1">Description</label>
                <textarea name="description" id="edit_description"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="3"></textarea>
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="edit_status" class="block text-gray-700 mb-1">Status</label>
                <select name="status" id="edit_status"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="upcoming">Upcoming</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <button type="button" id="closeEditExamModal"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>



@include('client.schoolPanel.layout.footer')

<script>
    $(document).ready(function() {
        $('#openExamModal').click(() => $('#examModal').removeClass('hidden'));
        $('#closeExamModal').click(() => $('#examModal').addClass('hidden'));
    });
    
    
    
    $(document).ready(function () {
    // Create modal logic already exists
    $('#openExamModal').click(() => $('#examModal').removeClass('hidden'));
    $('#closeExamModal').click(() => $('#examModal').addClass('hidden'));

    // Edit modal logic
    $('.editExamBtn').click(function () {
        const examId = $(this).data('id');

        $.get(`/school/exams/${examId}/edit`, function (data) {
            $('#edit_academic_session').val(data.academic_session);
            $('#edit_name').val(data.name);
            $('#edit_start_date').val(data.start_date);
            $('#edit_end_date').val(data.end_date);
            $('#edit_description').val(data.description);
             $('#edit_status').val(data.status);
            $('#edit_id').val(data.id);
            


            $('#editExamModal').removeClass('hidden');
        });
    });

    $('#closeEditExamModal').click(() => $('#editExamModal').addClass('hidden'));
});

</script>


