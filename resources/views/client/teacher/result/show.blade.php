@extends('client.teacher.layout.master')

@section('title', 'Exam Results')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-2">
        Exam Results
    </h2>

    @forelse($results as $examName => $examResults)
        <div class="mb-10">
            <h3 class="text-lg font-bold text-blue-600 mb-3 border-b border-blue-200 pb-2">
                {{ $examName }}
            </h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Student</th>
                            <th class="px-4 py-3 text-left">Class</th>
                            <th class="px-4 py-3 text-left">Section</th>
                            <th class="px-4 py-3 text-left">Subject</th>
                            <th class="px-4 py-3 text-left">Marks</th>
                            <th class="px-4 py-3 text-left">Total</th>
                            <th class="px-4 py-3 text-left">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-800">
                        @foreach($examResults as $result)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">
                                    {{ $result->student->first_name ?? '-' }} {{ $result->student->last_name ?? '' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $result->examSchedule->class ?? '-' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $result->examSchedule->section ?? '-' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $result->subject->name ?? $result->examSchedule->subject ?? '-' }}
                                </td>
                                <td class="px-4 py-2 font-semibold text-green-700">
                                    {{ $result->marks_obtained }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $result->total_marks }}
                                </td>
                                <td class="px-4 py-2 text-gray-600">
                                    {{ $result->remarks ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <p class="text-gray-500 italic">No results uploaded for your assigned subjects yet.</p>
    @endforelse
</div>
@endsection
