$(document).ready(function () {
    let deleteExamId;

    function resetCreateForm() {
        $('#examForm')[0].reset();
    }

    // Open Create Modal
    $('#openExamModal').click(function () {
        resetCreateForm();
        $('#examModal').removeClass('hidden');
    });

    $('#closeExamModal').click(function () {
        $('#examModal').addClass('hidden');
    });

    // Submit Create Form
    $('#examForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '/school/exams',
            type: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    toastr.success(res.message);
                    location.reload();
                } else {
                    toastr.error('Failed to create exam');
                }
            }
        });
    });

    // Edit Exam
    $('.editExamBtn').click(function () {
        const row = $(this).closest('tr');
        $('#editExamForm input[name="edit_id"]').val(row.data('id'));
        $('#editExamForm input[name="edit_name"]').val(row.data('name'));
        $('#editExamForm input[name="edit_start"]').val(row.data('start'));
        $('#editExamForm input[name="edit_end"]').val(row.data('end'));
        $('#editExamForm textarea[name="edit_description"]').val(row.data('description'));
        $('#editExamForm select[name="edit_status"]').val(row.data('status'));
        $('#editExamModal').removeClass('hidden');
    });

    $('#closeEditExamModal').click(function () {
        $('#editExamModal').addClass('hidden');
    });

    $('#editExamForm').submit(function (e) {
        e.preventDefault();
        const id = $('input[name="edit_id"]').val();
        $.ajax({
            url: `/school/exams/${id}`,
            type: 'PUT',
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    toastr.success(res.message);
                    location.reload();
                } else {
                    toastr.error('Failed to update exam');
                }
            }
        });
    });

    // Delete Exam
    $('.deleteExamBtn').click(function () {
        const row = $(this).closest('tr');
        deleteExamId = row.data('id');
        $('#deleteExamName').text(row.data('name'));
        $('#deleteExamModal').removeClass('hidden');
    });

    $('#closeDeleteExamModal').click(function () {
        $('#deleteExamModal').addClass('hidden');
    });

    $('#confirmDeleteExamBtn').click(function () {
        $.ajax({
            url: `/school/exams/${deleteExamId}`,
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                if (res.success) {
                    toastr.success(res.message);
                    location.reload();
                } else {
                    toastr.error('Failed to delete exam');
                }
            }
        });
    });
});
