@extends('client.teacher.layout.master')

@section('content')
<div class="bg-white rounded-xl shadow-lg w-full p-6 mt-6">
    <h2 class="text-2xl capitalize font-semibold mb-6 text-gray-800">
        Upload Marks - {{ $schedule->exam->name }} 
        {{-- ({{ $schedule->subject }}) --}}
    </h2>

    <form action="{{ route('teacher.exams.results.store', $schedule->id) }}" method="POST">
        @csrf

        {{-- Subject ID (only once, not per student) --}}
        <input type="hidden" name="subject_id" value="{{ $teacher->subject_id }}">

        {{-- Total Marks Input --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Total Marks</label>
            <input type="number" name="total_marks" step="0.01" required
                   value="{{ $existingMarks->first()->total_marks ?? '' }}"
                   class="border rounded px-4 py-2 w-1/3 focus:outline-none focus:ring focus:border-blue-300">
            @error('total_marks')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Marks Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-700">Student</th>
                        <th class="px-4 py-2 text-left text-gray-700">Marks Obtained</th>
                        <th class="px-4 py-2 text-left text-gray-700">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                        @php $mark = $existingMarks->get($student->id); @endphp
                        <tr class="border-t">
                            <td class="px-4 py-2">
                                {{ $student->first_name }} {{ $student->last_name }}
                                <input type="hidden" name="marks[{{ $index }}][student_id]" value="{{ $student->id }}">
                            </td>

                            <td class="px-4 py-2">
                                <input type="number" step="0.01" 
                                       name="marks[{{ $index }}][marks_obtained]" 
                                       required
                                       value="{{ $mark->marks_obtained ?? '' }}"
                                       class="border rounded px-3 py-1 w-24 focus:outline-none focus:ring focus:border-blue-300">
                                @error('marks.' . $index . '.marks_obtained')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </td>

                            <td class="px-4 py-2">
                                <input type="text" name="marks[{{ $index }}][remarks]" 
                                       value="{{ $mark->remarks ?? '' }}" 
                                       class="border rounded px-3 py-1 w-full focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Submit Button --}}
        <div class="mt-6">
            <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">
                Save Marks
            </button>
        </div>
    </form>
</div>
@endsection
