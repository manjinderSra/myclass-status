@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">

        {{-- Homework Details --}}
        <div class="bg-white shadow rounded-xl p-6 mb-6">
            <h1 class="text-2xl font-semibold text-gray-800 mb-4">
                Homework Details - {{ $homework->title }}
            </h1>
            <p class="text-gray-600 mb-2"><strong>Class:</strong> {{ $homework->class_name ?? '-' }}</p>
            <p class="text-gray-600 mb-2"><strong>Section:</strong> {{ $homework->section->name ?? '-' }}</p>
            <p class="text-gray-600 mb-2"><strong>Due Date:</strong> {{ $homework->submission_date ?? '-' }}</p>
            <p class="text-gray-600"><strong>Description:</strong> {!! nl2br(e($homework->description)) !!}</p>
        <p class="text-gray-600"><strong>Given By:</strong> {{$homework->teacher->first_name .' '. $homework->teacher->last_name }}</p>
        <p class="text-gray-600"><strong>File:</strong> <a href="{{asset('storage/'.$homework->image_path) }}"target="_blank">view File</a></p>
       
        </div>

        {{-- Students Table --}}
        <div class="bg-white rounded-xl shadow-lg w-full p-6 transition-all duration-300">
            @if($studentList->isEmpty())
                <p class="text-gray-500">No students found for this class & section.</p>
            @else
                <table id="studentsTable" class="w-full border border-gray-300 rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border-b text-left">#</th>
                            <th class="px-4 py-2 border-b text-left">Name</th>
                            <th class="px-4 py-2 border-b text-left">Email</th>
                            <th class="px-4 py-2 border-b text-left">Roll No</th>
                            <th class="px-4 py-2 border-b text-left">Status</th>
                            <th class="px-4 py-2 border-b text-left">Submitted At</th>
                            <th class="px-4 py-2 border-b text-left">File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studentList as $i => $s)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border-b">{{ $i + 1 }}</td>
                                <td class="px-4 py-2 border-b">{{ $s['name'] }}</td>
                                <td class="px-4 py-2 border-b">{{ $s['email'] }}</td>
                                <td class="px-4 py-2 border-b">{{ $s['roll_number'] }}</td>
                                <td class="px-4 py-2 border-b">
                                    @if($s['submitted'])
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                                            Submitted
                                        </span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border-b">
                                    {{ $s['submitted_at'] ?? '-' }}
                                </td>
                                <td class="px-4 py-2 border-b">
                                    @if($s['file_url'])
                                        <a href="{{ $s['file_url'] }}" target="_blank" class="text-blue-600 hover:underline">
                                            View File
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

{{-- DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        $('#studentsTable').DataTable({
            language: {
                search: "",
                searchPlaceholder: "Search students..."
            },
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
            dom:
                "<'flex justify-between items-center mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
                "t" +
                "<'flex justify-between items-center mt-4'<'dataTables_info'i><'dataTables_paginate'p>>",
        });
    });
</script>
