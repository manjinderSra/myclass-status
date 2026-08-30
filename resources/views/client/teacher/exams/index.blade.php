@include('client.teacher.layout.master')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <style>
        .main-content {
            margin-left: 0;
            min-height: 0;
            padding-top: 0;
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
        }

        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            overflow-x: auto;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container thead {
            background-color: #f9fafb;
        }

        .table-container thead th {
            font-weight: 600;
            color: #4b5563;
        }

        .table-container tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .table-container tbody tr:hover {
            background-color: #e5e7eb;
        }

        .table-container tbody td {
            color: #4b5563;
        }
    </style>

    <div class="flex-1 overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                Academics / <span class="text-l text-gray-500">Assigned Exams</span>
            </h1>
        </div>

        {{-- Exam Table --}}
        <div class="table-container p-6">
            <table id="teacherExamsTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th>Exam Name</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Room</th>
                        <th>Status</th>
                        <th>Cancel Reason</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($schedules as $schedule)
                        <tr>
                            <td style="text-transform: capitalize;">{{ $schedule->exam->name ?? 'N/A' }}</td>
                            <td>{{ $schedule->class ?? 'N/A' }}</td>
                            <td>{{ $schedule->section ?? '-' }}</td>
                            <td style="text-transform: capitalize;">{{ $schedule->subject->name?? '-' }}</td>
                            <td>{{ $schedule->exam_date }}</td>
                            <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                            <td>
                                @php
                                    $rooms = $schedule->room_no;

                                    if (is_string($rooms)) {
                                        $roomsArray = json_decode($rooms, true);
                                        if (!is_array($roomsArray)) $roomsArray = [$rooms];
                                    } elseif (is_array($rooms)) {
                                        $roomsArray = $rooms;
                                    } elseif ($rooms === null) {
                                        $roomsArray = [];
                                    } else {
                                        $roomsArray = [$rooms];
                                    }
                                @endphp

                                @if(!empty($roomsArray))
                                    {{ implode(', ', $roomsArray) }}
                                @else
                                    -
                                @endif
                            </td>
                           <td>
                                @if($schedule->status === 'Canceled')
                                    <span class="inline-block px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                        Canceled
                                    </span>
                                @elseif($schedule->status === 'Completed')
                                    <span class="inline-block px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full">
                                        Completed
                                    </span>
                                @elseif($schedule->status === 'Active')
                                    <span class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full">
                                        Unknown
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($schedule->status === 'Canceled' && $schedule->cancel_reason)
                                    {{ $schedule->cancel_reason }}
                                @else
                                    -
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-gray-500">
                                No assigned exams.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- DataTables --}}
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script>
$(document).ready(function () {
    $('#teacherExamsTable').DataTable({
        responsive: true,
        paging: true,
        searching: true,
        lengthChange: false,
        pageLength: 10,
    });
});
</script>
