@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold mb-6 text-gray-800">
                General Settings / <span class="text-l text-gray-500">Grades</span>
            </h1>
            <button id="openGradeModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Create Grade +
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg w-full p-6">
            <table id="gradesTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left text-sm uppercase">
                        <th class="px-6 py-3 font-semibold">Grade ID</th>
                        <th class="px-6 py-3 font-semibold">Grade Name</th>
                        <th class="px-6 py-3 font-semibold">Min Score</th>
                        <th class="px-6 py-3 font-semibold">Max Score</th>
                        <th class="px-6 py-3 font-semibold">Description</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                        <th class="px-6 py-3 font-semibold">Created At</th>
                        <th class="px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grades as $grade)
                        <tr>
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">{{ $grade->name }}</td>
                            <td class="px-6 py-4">{{ $grade->min_score }}</td>
                            <td class="px-6 py-4">{{ $grade->max_score }}</td>
                            <td class="px-6 py-4">{{ $grade->description ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($grade->status)
                                    <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Enabled
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Disabled
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $grade->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('grades.destroy', $grade) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure you want to delete this grade?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">No grades found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Create Grade Modal --}}
<div id="gradeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-bold mb-4">Create New Grade</h3>
        <form method="POST" action="{{ route('grades.store') }}">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Grade Name</label>
                <input type="text" name="name" id="name" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label for="min_score" class="block text-sm font-medium text-gray-700">Min Score</label>
                <input type="number" name="min_score" id="min_score" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label for="max_score" class="block text-sm font-medium text-gray-700">Max Score</label>
                <input type="number" name="max_score" id="max_score" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" class="w-full border rounded p-2"></textarea>
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="status" id="status" class="h-4 w-4">
                <label for="status" class="ml-2 text-sm">Enable this grade</label>
            </div>
            <div class="flex justify-end">
                <button type="button" id="closeGradeModal" class="bg-gray-300 px-4 py-2 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('openGradeModal').addEventListener('click', () => {
        document.getElementById('gradeModal').classList.remove('hidden');
    });
    document.getElementById('closeGradeModal').addEventListener('click', () => {
        document.getElementById('gradeModal').classList.add('hidden');
    });
</script>
