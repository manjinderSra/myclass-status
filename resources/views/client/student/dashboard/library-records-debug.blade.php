@extends("client.student.layouts.master")

@section("title", "Library Records Debug")

@section("content")
<div class="container px-6 mx-auto grid">
    <div class="bg-white rounded-lg shadow-md p-6 my-6">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Library Records Debugging</h2>
        
        <!-- Student Information -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Student Information</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p><strong>Student ID:</strong> {{ Session::get('student_id') }}</p>
                <p><strong>Session Student ID Type:</strong> {{ gettype(Session::get('student_id')) }}</p>
                <p><strong>Student Name:</strong> {{ Session::get('student_name') }}</p>
                <p><strong>School ID:</strong> {{ Session::get('school_id') }}</p>
            </div>
        </div>
        
        <!-- Database Structure -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Database Structure</h3>
            
            <h4 class="font-medium mb-2">Books Table Columns</h4>
            <div class="bg-gray-50 p-4 rounded-lg mb-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            @php
                                $columns = DB::getSchemaBuilder()->getColumnListing('books');
                            @endphp
                            @foreach($columns as $column)
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">{{ $column }}</th>
                            @endforeach
                        </tr>
                    </thead>
                </table>
            </div>
            
            <h4 class="font-medium mb-2">Issued Books Table Columns</h4>
            <div class="bg-gray-50 p-4 rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            @php
                                $columns = DB::getSchemaBuilder()->getColumnListing('issued_books');
                            @endphp
                            @foreach($columns as $column)
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">{{ $column }}</th>
                            @endforeach
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        
        <!-- Sample Data -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Sample Data</h3>
            
            <h4 class="font-medium mb-2">All Issued Books (First 10)</h4>
            <div class="bg-gray-50 p-4 rounded-lg mb-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">ID</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">School ID</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Student ID</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Book ID</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Book Name</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Issue Date</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Due Date</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Is Returned</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $allBooks = DB::table('issued_books')->limit(10)->get();
                        @endphp
                        @forelse($allBooks as $book)
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-xs">{{ $book->id ?? 'N/A' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs">{{ $book->school_id ?? 'N/A' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs">{{ $book->student_id ?? 'N/A' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs">{{ $book->book_id ?? 'N/A' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs">{{ $book->book_name ?? 'N/A' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs">{{ $book->issue_date ?? 'N/A' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs">{{ $book->due_date ?? 'N/A' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-xs">{{ $book->is_returned ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-3 py-2 text-center text-xs text-red-500">No books found in the database</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Direct Query Tests -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Direct Query Tests</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium mb-2">Books Count by Direct Query</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p><strong>All Books:</strong> {{ DB::table('issued_books')->count() }}</p>
                        <p><strong>Student's Books:</strong> {{ DB::table('issued_books')->where('student_id', Session::get('student_id'))->count() }}</p>
                        <p><strong>Current Books (is_returned=0):</strong> {{ DB::table('issued_books')->where('student_id', Session::get('student_id'))->where('is_returned', 0)->count() }}</p>
                        <p><strong>Returned Books (is_returned=1):</strong> {{ DB::table('issued_books')->where('student_id', Session::get('student_id'))->where('is_returned', 1)->count() }}</p>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-medium mb-2">Data Type Check</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        @php
                            $sampleBook = DB::table('issued_books')->first();
                        @endphp
                        @if($sampleBook)
                            <p><strong>is_returned type:</strong> {{ gettype($sampleBook->is_returned) }}</p>
                            <p><strong>is_returned value:</strong> {{ $sampleBook->is_returned }}</p>
                            <p><strong>student_id type:</strong> {{ gettype($sampleBook->student_id) }}</p>
                        @else
                            <p class="text-red-500">No sample book found</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Create Test Data -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Create Test Data</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p>To create test data, go to <a href="{{ route('student.library') }}" class="text-blue-600 hover:underline">this link</a> and click on "Create Test Data".</p>
            </div>
        </div>
    </div>
</div>
@endsection 