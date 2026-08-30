@extends('client.student.layouts.master')

@section('title', 'View Exam Result')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-semibold text-gray-800 mb-8">Check Your Exam Result</h2>

    {{-- Search Form --}}
    <form action="{{ route('student.exam-results.fetch') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Exam Type --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-600 mb-2">Exam Type</label>
            <select name="exam_id" required class="border border-gray-300 rounded-lg p-3 text-gray-800 focus:ring-2 focus:ring-blue-500 transition duration-200">
                <option value="">Select Exam</option>
                @foreach($examTypes as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Academic Session --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-600 mb-2">Academic Session</label>
            <select name="academic_session" required class="border border-gray-300 rounded-lg p-3 text-gray-800 focus:ring-2 focus:ring-blue-500 transition duration-200">
                <option value="">Select Session</option>
                @foreach($sessions as $session)
                    <option value="{{ $session }}">{{ $session }}</option>
                @endforeach
            </select>
        </div>

        {{-- Search Button --}}
        <div class="flex items-end justify-center md:justify-start">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
                Search
            </button>
        </div>
    </form>
</div>

{{-- Latest Subject-wise Results --}}
@if($latestResults->isNotEmpty())
<div class="bg-white rounded-xl shadow-lg overflow-hidden mt-10">
    <div class="p-8">
        <h3 class="text-2xl font-semibold text-gray-800 mb-6">Latest Subject-wise Results</h3>

        <div class="overflow-x-auto">
            <table class="w-full table-auto divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Theory</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Practical</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($latestResults as $result)
                        @php
                            $schedule = $result->examSchedule;
                            $exam = $schedule?->exam;
                            $teacher = $schedule?->teacher ?? $result->teacher;
                            $color = match($result->grade) {
                                'A+' => 'bg-green-100 text-green-800',
                                'A' => 'bg-blue-100 text-blue-800',
                                'B+' => 'bg-indigo-100 text-indigo-800',
                                'B' => 'bg-yellow-100 text-yellow-800',
                                'C+' => 'bg-orange-100 text-orange-800',
                                'C' => 'bg-pink-100 text-pink-800',
                                'D' => 'bg-red-100 text-red-800',
                                'F' => 'bg-gray-100 text-gray-800',
                                default => 'bg-gray-200 text-gray-800',
                            };
                        @endphp
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $result->subject->name ?? 'N/A' }} ({{ $result->subject->code ?? 'N/A' }})
                            </td>
                             <td class="px-6 capitalize py-4">{{ $exam->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $schedule?->exam_date ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $schedule?->start_time ?? 'N/A' }} - {{ $schedule?->end_time ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                {{ $teacher?->first_name . ' ' . $teacher?->last_name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">{{ $schedule?->room_no ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-center">{{ $result->exam_type == 'theory' ? $result->marks_obtained : '-' }}</td>
                            <td class="px-6 py-4 text-center">{{ $result->exam_type == 'practical' ? $result->marks_obtained : '-' }}</td>
                            <td class="px-6 py-4 text-center font-semibold">{{ $result->marks_obtained }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                    {{ $result->grade }}
                                </span>
                            </td>
                           
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection
