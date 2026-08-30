@extends('client.student.layouts.master')

@section('title', 'Exam Schedules')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Exam Schedules</h2>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @php
        // Group schedules by exam name
        $groupedExams = $examSchedules->groupBy(fn($item) => $item->exam->name ?? 'Unnamed Exam');
    @endphp

    @forelse($groupedExams as $examName => $exams)
        <!-- Exam Group Header -->
        <h3 class="text-xl font-semibold text-blue-600 mb-4 mt-6 capitalize">{{ $examName }}</h3>
            
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam Type</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cancel Reason</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($exams as $exam)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $exam->subject->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 capitalize whitespace-nowrap text-sm text-gray-900">
                                {{ $exam->exam_type ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ date('d M Y', strtotime($exam->exam_date)) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $exam->start_time }} - {{ $exam->end_time }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @php
                                    $rooms = $exam->room_no;

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
                                @if($exam->status === 'Canceled')
                                    <span class="inline-block px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                        {{ $exam->status }}
                                    </span>
                                @elseif($exam->status === 'Completed')
                                    <span class="inline-block px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-200 rounded-full">
                                        {{ $exam->status }}
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                        {{ $exam->status }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($exam->status === 'Canceled' && !empty($exam->cancel_reason))
                                    {{ $exam->cancel_reason }}
                                @else
                                    -
                                @endif
                        </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p class="text-center py-6 text-gray-500">
            No exam schedule available for your class.
        </p>
    @endforelse
<!-- Pagination -->
@if($examSchedules->hasPages())
    <nav class="mt-6 flex justify-center items-center space-x-2">
        {{-- Previous Page Link --}}
        @if($examSchedules->onFirstPage())
            <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed">‹</span>
        @else
            <a href="{{ $examSchedules->previousPageUrl() }}" class="px-3 py-1 bg-white text-gray-700 border rounded hover:bg-gray-100">‹</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($examSchedules->getUrlRange(1, $examSchedules->lastPage()) as $page => $url)
            @if ($page == $examSchedules->currentPage())
                <span class="px-3 py-1 bg-blue-600 text-white rounded">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="px-3 py-1 bg-white text-gray-700 border rounded hover:bg-gray-100">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if($examSchedules->hasMorePages())
            <a href="{{ $examSchedules->nextPageUrl() }}" class="px-3 py-1 bg-white text-gray-700 border rounded hover:bg-gray-100">›</a>
        @else
            <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed">›</span>
        @endif
    </nav>
@endif

    <!-- Pagination -->
    {{-- <div class="mt-6">
        {{ $examSchedules->links() }}
    </div> --}}
</div>
@endsection
