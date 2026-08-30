@extends("client.student.layouts.master")

@section("title", "Library Records")

@section("content")
<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                </path>
            </svg>
            Library Records
        </h2>
    </div>

    @php
        $hasAnyBooks = DB::table('issued_books')->exists();
    @endphp

    @if(!$hasAnyBooks)
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-center flex-col">
                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                    </path>
                </svg>
                <p class="text-gray-600 text-lg mb-2">No library records found</p>
                <p class="text-gray-500 text-sm text-center">No books have been issued yet.</p>
            </div>
        </div>
    @endif

    <!-- CURRENTLY ISSUED BOOKS -->
    <div class="mb-8">
        <h3 class="text-lg font-medium text-gray-700 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2">
                </path>
            </svg>
            Currently Issued Books
        </h3>

        @if(count($currentBooks) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($currentBooks as $book)
                    <div class="bg-white rounded-lg shadow-md border hover:shadow-lg transition">
                        <div class="h-48 bg-indigo-50 flex items-center justify-center">
                            @if($book->image_path)
                                <img src="{{ asset('storage/'.$book->image_path) }}" class="h-full object-contain">
                            @else
                                <div class="w-32 h-40 bg-gray-200 flex items-center justify-center rounded">
                                    <svg class="w-16 h-16 text-indigo-300" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 6.253v13"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <h4 class="text-lg font-semibold">{{ $book->book_name }}</h4>
                            <p class="text-sm text-gray-600 mb-3">Book No: {{ $book->book_no }}</p>

                            <div class="grid grid-cols-2 gap-2 text-sm mb-4">
                                <div>
                                    <p class="text-gray-500">Issue Date</p>
                                    <p>{{ \Carbon\Carbon::parse($book->issue_date)->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Due Date</p>
                                    <p class="{{ \Carbon\Carbon::parse($book->due_date)->isPast() ? 'text-red-600' : '' }}">
                                        {{ \Carbon\Carbon::parse($book->due_date)->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                @if(\Carbon\Carbon::parse($book->due_date)->isPast())
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">
                                        Overdue by {{ \Carbon\Carbon::parse($book->due_date)->diffForHumans(['parts'=>1]) }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        Due in {{ now()->diffInDays($book->due_date) }} days
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <p class="text-gray-600">No issued books.</p>
            </div>
        @endif
    </div>

    <!-- OVERDUE SECTION -->
    @if($overdueBooks->count())
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-700 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0">
                    </path>
                </svg>
                Overdue Books
            </h3>

            <div class="bg-red-50 border border-red-200 rounded-lg p-6 shadow-md">
                <div class="flex items-center mb-4">
                    <svg class="w-8 h-8 text-red-500 mr-3" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0">
                        </path>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-red-600">Attention Required</h4>
                        <p class="text-sm text-red-600">These books are overdue.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($overdueBooks as $book)
                        <div class="bg-white rounded-lg p-4 border flex">
                            @if($book->image_path)
                                <img src="{{ asset('storage/'.$book->image_path) }}"
                                     class="w-16 h-20 mr-4 rounded">
                            @else
                                <div class="w-12 h-16 bg-red-50 flex items-center justify-center mr-4 rounded">
                                    <svg class="w-8 h-8 text-red-300" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24"></svg>
                                </div>
                            @endif

                            <div>
                                <h5 class="font-medium">{{ $book->book_name }}</h5>
                                <p class="text-gray-500 text-sm">Book No: {{ $book->book_no }}</p>
                                <p class="text-xs text-red-600 mt-2">
                                    Due: {{ \Carbon\Carbon::parse($book->due_date)->format('M d, Y') }} |
                                    Overdue by {{ \Carbon\Carbon::parse($book->due_date)->diffForHumans(['parts'=>1]) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- RETURN HISTORY -->
    <div class="mb-8">
        <h3 class="text-lg font-medium text-gray-700 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3"></path>
            </svg>
            Return History
        </h3>

        @if(count($returnedBooks) > 0)
            <div class="bg-white rounded-lg shadow-md p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium">Book Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium">Issue Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium">Return Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($returnedBooks as $book)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($book->image_path)
                                            <img src="{{ asset('storage/'.$book->image_path) }}"
                                                 class="h-12 w-10 rounded"/>
                                        @else
                                            <div class="h-10 w-10 bg-indigo-50 rounded-full flex items-center justify-center">
                                                <svg class="h-6 w-6 text-indigo-400" fill="none"></svg>
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <p class="font-medium">{{ $book->book_name }}</p>
                                            <p class="text-xs text-gray-500">Book No: {{ $book->book_no }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($book->issue_date)->format('M d, Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    @if($book->is_lost)
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">
                                            Lost on {{ \Carbon\Carbon::parse($book->lost_date)->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                            {{ \Carbon\Carbon::parse($book->return_date)->format('M d, Y') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    {{ $book->return_remarks ?? $book->lost_remarks ?? 'No remarks' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-white rounded-lg p-6 text-center">
                <p class="text-gray-600">No books returned yet.</p>
            </div>
        @endif
    </div>

</div>
@endsection
