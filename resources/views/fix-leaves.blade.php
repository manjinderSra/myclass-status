<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix Leave Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-6">Leave Applications Diagnostic</h1>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Student Information</h2>
            @if(isset($student))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p><strong>ID (Primary Key):</strong> {{ $student->id }}</p>
                        <p><strong>Student ID (String):</strong> {{ $student->student_id }}</p>
                        <p><strong>Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</p>
                    </div>
                    <div>
                        <p><strong>School ID:</strong> {{ $student->school_id }}</p>
                        <p><strong>Class:</strong> {{ $student->class->name ?? 'Not assigned' }}</p>
                        <p><strong>Section:</strong> {{ $student->section->name ?? 'Not assigned' }}</p>
                    </div>
                </div>
            @else
                <p class="text-red-500">No student information available.</p>
            @endif
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Leave Applications</h2>
            
            <div class="mb-6">
                <h3 class="font-medium text-lg mb-2">Using Student ID Field (String)</h3>
                @if(isset($leavesByStudentIdField) && count($leavesByStudentIdField) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Leave ID</th>
                                    <th class="py-3 px-6 text-left">Reason</th>
                                    <th class="py-3 px-6 text-left">Dates</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm">
                                @foreach($leavesByStudentIdField as $leave)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-6">{{ $leave->leave_id }}</td>
                                        <td class="py-3 px-6">{{ $leave->reason }}</td>
                                        <td class="py-3 px-6">{{ $leave->from_date }} to {{ $leave->to_date }}</td>
                                        <td class="py-3 px-6">{{ $leave->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">No leave applications found using student_id field.</p>
                @endif
            </div>
            
            <div>
                <h3 class="font-medium text-lg mb-2">Using Primary Key (ID)</h3>
                @if(isset($leavesByIdField) && count($leavesByIdField) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Leave ID</th>
                                    <th class="py-3 px-6 text-left">Reason</th>
                                    <th class="py-3 px-6 text-left">Dates</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm">
                                @foreach($leavesByIdField as $leave)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-6">{{ $leave->leave_id }}</td>
                                        <td class="py-3 px-6">{{ $leave->reason }}</td>
                                        <td class="py-3 px-6">{{ $leave->from_date }} to {{ $leave->to_date }}</td>
                                        <td class="py-3 px-6">{{ $leave->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">No leave applications found using id field.</p>
                @endif
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Table Schema</h2>
            @if(isset($tableSchema) && count($tableSchema) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Field</th>
                                <th class="py-3 px-6 text-left">Type</th>
                                <th class="py-3 px-6 text-left">Null</th>
                                <th class="py-3 px-6 text-left">Key</th>
                                <th class="py-3 px-6 text-left">Default</th>
                                <th class="py-3 px-6 text-left">Extra</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm">
                            @foreach($tableSchema as $column)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6">{{ $column->Field }}</td>
                                    <td class="py-3 px-6">{{ $column->Type }}</td>
                                    <td class="py-3 px-6">{{ $column->Null }}</td>
                                    <td class="py-3 px-6">{{ $column->Key }}</td>
                                    <td class="py-3 px-6">{{ $column->Default }}</td>
                                    <td class="py-3 px-6">{{ $column->Extra }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">No schema information available.</p>
            @endif
        </div>
        
        <div class="flex space-x-4">
            <a href="{{ url('/fix-leaves/test') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                Create Test Leave Application
            </a>
            <a href="{{ url('/student/leaves') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Go to Leave Applications
            </a>
            <a href="{{ url('/') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html> 