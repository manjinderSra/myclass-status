@extends("client.student.layouts.master")
@section("title", "Homework")

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Homework</h2>
    </div>

    {{-- Flash Messages --}}
    {{-- @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif --}}


    @if(isset($homework) && count($homework) > 0)

        <!-- Today's Homework -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-100">
                <h3 class="text-lg font-semibold text-gray-800">
                    Today's Homework ({{ now()->format('l, d M Y') }})
                </h3>
            </div>

            <div class="p-6">
                @php
                    $todayHomework = $homework->filter(
                        fn($hw) => \Carbon\Carbon::parse($hw->homework_date)->isToday()
                    );
                @endphp

                @if(count($todayHomework) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Submission Date</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Teacher</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Attachment</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($todayHomework as $hw)
                                @php
                                    $isOverdue = \Carbon\Carbon::parse($hw->submission_date)->isPast()
                                        && !$hw->isSubmittedBy(auth()->id());
                                @endphp

                                <tr class="{{ $isOverdue ? 'bg-red-50' : '' }}">
                                    <td class="px-6 py-4 text-sm">{{ $hw->subject->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $hw->description }}</td>
                                    <td class="px-6 py-4 text-sm {{ $isOverdue ? 'text-red-600 font-semibold' : '' }}">
                                        {{ \Carbon\Carbon::parse($hw->submission_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">{{ $hw->teacher->first_name ?? '' }} {{ $hw->teacher->last_name ?? '' }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($hw->image_path)
                                            <a href="{{ asset('storage/' . $hw->image_path) }}" target="_blank" class="text-blue-600 hover:underline">View</a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="text-center py-8 text-gray-500">No homework assigned for today.</div>
                @endif
            </div>
        </div>


        <!-- All Homework -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($homework->groupBy(fn($hw) => \Carbon\Carbon::parse($hw->homework_date)->format('Y-m-d')) as $date => $hwList)

                <div class="bg-white rounded-lg shadow-sm overflow-hidden {{ \Carbon\Carbon::parse($date)->isToday() ? 'ring-2 ring-blue-500' : '' }}">
                    <div class="bg-gray-50 px-6 py-3 border-b">
                        <h3 class="text-base font-semibold text-gray-800">{{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}</h3>
                    </div>

                    <div class="p-4 space-y-3">

                        @foreach($hwList as $hw)
                        @php
                            $due = \Carbon\Carbon::parse($hw->submission_date)->endOfDay();
                            $now = now();
                            $isOverdue = $now->greaterThan($due) && !$hw->isSubmittedBy(auth()->id());
                        @endphp

                        <div class="p-3 rounded-md border-l-4 {{ $isOverdue ? 'bg-red-50 border-red-500' : 'bg-green-50 border-green-500' }}">
                            <div class="flex justify-between">
                                <div>
                                    <h4 class="text-sm font-medium">{{ $hw->subject->name }}</h4>
                                    <p class="text-xs text-gray-500">Teacher: {{ $hw->teacher->first_name }} {{ $hw->teacher->last_name }}</p>
                                    <p class="text-sm mt-2">{{ $hw->description }}</p>

                                    @if($hw->image_path)
                                        <a href="{{ asset('storage/' . $hw->image_path) }}" class="text-xs text-blue-600 hover:underline" target="_blank">View Attachment</a>
                                    @endif
                                </div>

                                <span class="text-xs {{ $isOverdue ? 'text-red-600' : 'text-gray-500' }}">
                                    Due: {{ \Carbon\Carbon::parse($hw->submission_date)->format('d M Y') }}
                                </span>
                            </div>


                            {{-- Submit Form: Only show if NOT submitted AND due date NOT passed --}}
                            @if(!$hw->isSubmittedBy(Session::get('student_id')) && !$isOverdue)
    <form action="{{ route('student.homework.submit', $hw->id) }}" method="POST" enctype="multipart/form-data" class="mt-3 border-t pt-3">
        @csrf
        <label class="text-xs font-medium">Submit Your Homework</label>
        <input type="file" name="submission_file" required class="border rounded px-2 py-1 text-sm" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/png,image/webp">
        <button class="mt-2 px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
            Submit
        </button>
    </form>
@else
    <div class="mt-3 text-xs text-gray-600 bg-gray-100 px-3 py-1 rounded">
        @if($hw->isSubmittedBy(Session::get('student_id')))
            <a href="{{ asset('storage/' . $hw->submission->file_path) }}" target="_blank" class="text-blue-600 hover:underline">
                View Submission
            </a>
        @else
            Submission Closed
        @endif
    </div>
@endif


                        </div>
                        @endforeach

                    </div>
                </div>

            @endforeach
        </div>


    @else
        <div class="bg-blue-50 p-4 border-l-4 border-blue-500 rounded">
            <p>No homework available for your class.</p>
        </div>
    @endif
</div>
@endsection
