@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Search Student Exam Results</h1>

        {{-- Search Form --}}
        <form action="{{ route('school.exam-results.fetch') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 bg-white p-6 rounded-xl shadow-md">
            {{-- Exam Type --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Exam Type</label>
                <select name="exam_type" required class="border rounded w-full p-2">
                    <option value="">Select Exam Type</option>
                    @foreach($examTypes as $id => $name)
                        <option value="{{ $name }}" {{ request('exam_type') == $name ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Academic Session --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Academic Session</label>
                <select name="academic_session" required class="border rounded w-full p-2">
                    <option value="">Select Session</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session }}" {{ request('academic_session') == $session ? 'selected' : '' }}>{{ $session }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Class --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Class</label>
                <input type="text" name="class" value="{{ request('class') }}" class="border rounded w-full p-2" placeholder="Enter Class" required>
            </div>

            {{-- Section --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Section</label>
                <input type="text" name="section" value="{{ request('section') }}" class="border rounded w-full p-2" placeholder="Enter Section" required>
            </div>

            {{-- Search Button --}}
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Search
                </button>
            </div>
        </form>

        {{-- Results --}}
        @if(isset($resultsGrouped) && $resultsGrouped->isEmpty())
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <h3 class="text-lg font-semibold text-gray-700">No results found.</h3>
                <p class="text-gray-500 mt-2">Please check the selected criteria.</p>
            </div>
        @elseif(isset($resultsGrouped))
            @foreach($resultsGrouped as $examScheduleId => $examResults)
                @php
                    $exam = $examResults->first(); // pick first row to get exam info
                @endphp

                <div class="mb-8 bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 sm:p-8">
                        {{-- Exam Header --}}
                        <div class="mb-4">
                            <h2 class="text-xl capitalize font-bold text-gray-800 mb-1">{{ $exam->exam_name }}</h2>
                            <p class="text-sm text-gray-500">Academic Session: {{ $exam->academic_session }}</p>
                            <p class="text-sm text-gray-500">Class: {{ $exam->exam_class }} ({{ $exam->exam_section }})</p>
                            <p class="text-sm text-gray-500">Exam Date: {{ $exam->exam_date }}</p>
                        </div>

                        <hr class="mb-4">

                        {{-- Exam Table --}}
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission No.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Theory</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Practical</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                   <tbody class="bg-white divide-y divide-gray-200">
    @foreach($examResults as $result)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ $result->first_name . ' ' . $result->last_name }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-900">{{ $result->roll_number ?? 'N/A' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $result->exam_subject ?? 'N/A' }}</td>

            {{-- Show marks based on exam_type --}}
            <td class="px-6 py-4 text-center">
                {{ $result->exam_type_result == 'theory' ? $result->marks_obtained : '-' }}
            </td>
            <td class="px-6 py-4 text-center">
                {{ $result->exam_type_result == 'practical' ? $result->marks_obtained : '-' }}
            </td>

            <td class="px-6 py-4 text-center font-semibold">{{ $result->marks_obtained ?? 'N/A' }}</td>

            {{-- Grade --}}
            <td class="px-6 py-4 text-right">
                @php
                    $marks = (float) $result->marks_obtained;
                    $grade = $marks >= 90 ? 'A+' : ($marks >= 75 ? 'A' : ($marks >= 50 ? 'B+' : 'D'));
                @endphp
                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full
                    {{ $grade == 'A+' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $grade == 'A' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $grade == 'B+' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $grade == 'D' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ $grade }}
                </span>
            </td>

            {{-- Rank --}}
            <td class="px-6 py-4 text-center">
                {{ $ranks[$result->exam_schedule_id][$result->student_id] ?? 'N/A' }}
            </td>
        </tr>
    @endforeach
</tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

@include('client.schoolPanel.layout.footer')
