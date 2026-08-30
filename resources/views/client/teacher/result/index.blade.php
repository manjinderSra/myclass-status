@extends('client.teacher.layout.master')

@section('content')
<div class="bg-white rounded-xl shadow-lg w-full p-6 mt-6 overflow-x-auto">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Exam Schedule Table --}}
    <table class="min-w-full divide-y divide-gray-200 border">
        <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Exam</th>
                <th class="px-4 py-3 text-left">Class</th>
                <th class="px-4 py-3 text-left">Section</th>
                <th class="px-4 py-3 text-left">Subject</th>
                <th class="px-4 py-3 text-left">Date</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="text-sm text-gray-700">
            @forelse($schedules as $schedule)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">{{ $schedule->exam->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3">{{ $schedule->class ?? 'N/A' }}</td>
                    <td class="px-4 py-3">{{ $schedule->section ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $schedule->subject->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($schedule->exam_date)->format('d M Y') }}</td>

                    {{-- Status Column --}}
                    <td class="px-4 py-3">
                        @if($schedule->status === 'Active')
                            <span class="text-green-600 font-semibold">Active</span>
                        @elseif($schedule->status === 'Completed')
                            <span class="text-blue-600 font-semibold">Completed</span>
                        @elseif($schedule->status === 'Canceled')
                            <span class="text-red-600 font-semibold">Canceled</span>
                            @if($schedule->cancel_reason)
                                <br><small class="text-gray-500 italic">{{ $schedule->cancel_reason }}</small>
                            @endif
                        @else
                            <span class="text-gray-500">Unknown</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-4 py-3 text-center space-x-2">
                        <a href="{{ route('teacher.exams.results.show', $schedule->id) }}"
                           class="inline-block bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
                           Upload Marks
                        </a>
                        <a href="{{ route('teacher.exams.results.view', $schedule->id) }}"
                           class="inline-block bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition">
                           Show Result
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500 italic">
                        No assigned exams found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
