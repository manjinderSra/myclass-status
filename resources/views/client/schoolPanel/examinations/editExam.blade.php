@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg max-w-2xl w-full p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Edit Exam Schedule</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="edit_id">

            <div>
                <label class="block mb-2 font-medium text-gray-700">Exam</label>
                <select name="exam_id" class="w-full px-3 py-2 border rounded" required>
                    <option value="">Select Exam</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Class</label>
                <input type="text" name="class" class="w-full px-3 py-2 border rounded" required>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Section</label>
                <input type="text" name="section" class="w-full px-3 py-2 border rounded" required>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Subject</label>
                <input type="text" name="subject" class="w-full px-3 py-2 border rounded" required>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Exam Date</label>
                <input type="date" name="exam_date" class="w-full px-3 py-2 border rounded" required>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Start Time</label>
                <input type="time" name="start_time" class="w-full px-3 py-2 border rounded" required>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">End Time</label>
                <input type="time" name="end_time" class="w-full px-3 py-2 border rounded" required>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Duration (minutes)</label>
                <input type="number" name="duration" class="w-full px-3 py-2 border rounded" required>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Room No</label>
                <input type="text" name="room_no" class="w-full px-3 py-2 border rounded" required>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Max Marks</label>
                <input type="number" name="max_marks" class="w-full px-3 py-2 border rounded" required>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Min Marks</label>
                <input type="number" name="min_marks" class="w-full px-3 py-2 border rounded" required>
            </div>

            <div class="flex justify-end mt-4">
                <button type="button" id="closeEditModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 ml-2">Update</button>
            </div>
        </form>
    </div>
</div>
<script>
$(document).ready(function() {

    // Open Edit Modal and populate data
    $('.editExamScheduleBtn').click(function() {
        let id = $(this).data('id');
        
        $.get(`/examSchedule/${id}/edit`, function(data) {
    $('#editForm').attr('action', `/examSchedule/${id}`);
            $('#editForm input[name="edit_id"]').val(data.id);
            $('#editForm select[name="exam_id"]').val(data.exam_id);
            $('#editForm input[name="class"]').val(data.class);
            $('#editForm input[name="section"]').val(data.section);
            $('#editForm input[name="subject"]').val(data.subject);
            $('#editForm input[name="exam_date"]').val(data.exam_date);
            $('#editForm input[name="start_time"]').val(data.start_time);
            $('#editForm input[name="end_time"]').val(data.end_time);
            $('#editForm input[name="duration"]').val(data.duration);
            $('#editForm input[name="room_no"]').val(data.room_no);
            $('#editForm input[name="max_marks"]').val(data.max_marks);
            $('#editForm input[name="min_marks"]').val(data.min_marks);

            $('#editModal').removeClass('hidden');
        });
    });

    // Close Edit Modal
    $('#closeEditModal').click(function() {
        $('#editModal').addClass('hidden');
    });

});
</script>
