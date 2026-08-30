@extends("client.teacher.layout.master")

@section("title", "Homework Submissions")

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Homework Submissions</h2>
        <a href="{{ route('teacher.homework') }}" class="text-blue-600 hover:underline">
            Back to Homework List
        </a>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-green-50 text-green-800 p-4 rounded-lg shadow">
            <p class="text-sm font-medium">Submitted</p>
            <p class="text-2xl font-bold">{{ $submitted->count() }}</p>
        </div>
        <div class="bg-red-50 text-red-800 p-4 rounded-lg shadow">
            <p class="text-sm font-medium">Pending</p>
            <p class="text-2xl font-bold">{{ $pending->count() }}</p>
        </div>
        <div class="bg-blue-50 text-blue-800 p-4 rounded-lg shadow">
            <p class="text-sm font-medium">Total Students</p>
            <p class="text-2xl font-bold">{{ $submitted->count() + $pending->count() }}</p>
        </div>
    </div>

    <!-- Student Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 border rounded-lg">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">File</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted At</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($submitted as $index => $sub)
                <tr class="bg-green-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $sub['student_name'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $sub['email'] }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Submitted
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($sub['file_url'])
                            <a href="{{ $sub['file_url'] }}" target="_blank" class="text-blue-600 hover:underline">
                                View File
                            </a>
                        @else
                            <span class="text-gray-400 italic">No File</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $sub['submitted_at'] }}</td>
                </tr>
                @endforeach

                @foreach($pending as $index => $p)
                <tr class="bg-red-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $submitted->count() + $index + 1 }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $p['student_name'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $p['email'] }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Pending
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400 italic">-</td>
                    <td class="px-6 py-4 text-gray-400 italic">-</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
