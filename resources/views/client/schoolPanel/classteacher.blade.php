@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')




    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gray-50">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Assign Class Teachers</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow">
                <span>{{ session('success') }}</span>
            </div>
        @endif


 @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow" role="alert">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18.364 5.636a9 9 0 11-12.728 0 9 9 0 0112.728 0zM9 13h2v2H9v-2zm0-6h2v4H9V7z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif






        <div class="bg-white shadow-md rounded-xl overflow-hidden">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Class</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Section</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Assigned Teacher</th>
                        <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classes as $class)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $class->name }}</td>
                            <td class="px-4 py-3">{{ $class->section->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                {{ $class->teacher ? $class->teacher->first_name . ' ' . $class->teacher->last_name : 'Not Assigned' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('school.class-teachers.assign', $class->id) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    <select name="teacher_id" class="border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
                                        <option value="">Select Teacher</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" 
                                                {{ $class->teacher_id == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->first_name }} {{ $teacher->last_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700">
                                        Assign
                                    </button>
                                    <a href="{{ route('school.class.students', [$class->school_id, $class->id, $class->section_id]) }}" 
   class="text-indigo-600 hover:text-indigo-800 font-medium ml-3">
   Show Students
</a>
                                </form>
                                  
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')
