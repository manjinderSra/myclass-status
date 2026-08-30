@extends('client.student.layouts.master')

@section('title', 'Exam Result')

@section('content')
<div class="bg-gray-100 p-6 sm:p-8 lg:p-12 min-h-screen">
    <div class="max-w-5xl mx-auto">

        @if($results->isEmpty())
            <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                <h3 class="text-xl font-semibold text-gray-700">No results found.</h3>
                <p class="text-gray-500 mt-2">There are no results available for the selected exam and session.</p>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">

                    {{-- Header --}}
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-3xl capitalize font-bold text-gray-800 tracking-wide">   
                                 {{-- {{ $results->first()?->exam_type ?? 'Exam' }} --}}
                                     {{ $examName }}
                            </h2>
                            <p class="text-sm text-gray-500 mt-1">Academic Session: {{ $academic_session }}</p>
                        </div>
                        <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm2-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                             </svg>
                        </button>
                    </div>
                    <hr class="my-6 border-gray-200">

                    {{-- Student Details --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-x-8 gap-y-6 mb-10 text-sm">
                        <div>
                            <p class="text-gray-500">Student Name</p>
                            <p class="font-semibold text-gray-800">{{ $student->first_name .' '. $student->last_name}}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Roll No.</p>
                            <p class="font-semibold text-gray-800">{{ $student->roll_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Class</p>
                            <p class="font-semibold text-gray-800">{{ $student->class->name ?? 'N/A' }} ({{ $student->section->name ?? 'N/A' }})</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Admission No.</p>
                            <p class="font-semibold text-gray-800">{{ $student->admission_number }}</p>
                        </div>
                    </div>

                    {{-- Results Table --}}
                    <div class="overflow-x-auto mb-10">
                        <table class="w-full table-auto divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Theory (Th01)</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Practical (PC02)</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($results as $result)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $result->subject->name ?? 'N/A' }} ({{ $result->subject->code ?? 'N/A' }})</td>
                                        <td class="px-6 py-4 text-center">{{ $result->exam_type == 'theory' ? $result->marks_obtained : '-' }}</td>
                                        <td class="px-6 py-4 text-center">{{ $result->exam_type == 'practical' ? $result->marks_obtained : '-' }}</td>
                                        <td class="px-6 py-4 text-center font-semibold">{{ $result->marks_obtained }}</td>
                                        <td class="px-6 py-4 text-right">
                                            @php
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
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                                {{ $result->grade }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Summary --}}
                    <div class="bg-gray-50 px-8 py-6 border-t border-gray-200">
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 text-center sm:text-left">
                            <div>
                                <p class="text-sm text-gray-600">Overall Marks</p>
                                <p class="text-xl font-bold text-gray-800">{{ number_format($totalMarksObtained, 2) }} / {{ number_format($totalMaxMarks, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Percentage</p>
                                <p class="text-xl font-bold text-gray-800">{{ number_format($percentage, 2) }}%</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Grade</p>
                                <p class="text-xl font-bold text-indigo-600">{{ $overallGrade }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Rank</p>
                                <p class="text-xl font-bold text-indigo-600">{{ $rank }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
