@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

{{-- Stylesheets --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="flex" x-data="bookData">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-8 py-28 bg-gray-50">
        <header class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-wide">
                Library <span class="text-indigo-600 font-normal">/ Books</span>
            </h1>
            <button
                @click="addBookModalOpen = true; generateBookId();"
                class="flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-3 rounded-lg shadow-md transition focus:outline-none focus:ring-4 focus:ring-indigo-300"
            >
                <span>Add Book</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </header>

        {{-- Books List Table --}}
        <section class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-6 text-gray-900 border-b border-gray-200 pb-3">Existing Books</h2>
            <div class="overflow-x-auto">
                <table id="booksTable" class="min-w-full divide-y divide-gray-200 text-gray-700">
                    <thead>
                        <tr class="bg-gray-100 text-xs uppercase tracking-wide text-left text-gray-700">
                            <th class="px-6 py-3">Book ID</th>
                            <th class="px-6 py-3">Image</th>
                            <th class="px-6 py-3">Book Name</th>
                            {{-- <th class="px-6 py-3">Book No</th> --}}
                            <th class="px-6 py-3">Rack No</th>
                            <th class="px-6 py-3">Publisher</th>
                            <th class="px-6 py-3">Author</th>
                            <th class="px-6 py-3">Subject</th>
                            <th class="px-6 py-3">Qty</th>
                            <th class="px-6 py-3">Price</th>
                            <th class="px-6 py-3">Post Date</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if(isset($books) && count($books) > 0)
                            @foreach($books as $book)
                                <tr class="hover:bg-indigo-50 transition-colors"
                                    data-id="{{ $book->id }}"
                                    data-book-id="{{ $book->book_id }}"
                                    data-book-name="{{ $book->book_name }}"
                                    {{-- data-book-no="{{ $book->book_no }}" --}}
                                    data-rack-no="{{ $book->rack_no }}"
                                    data-publisher="{{ $book->publisher }}"
                                    data-author="{{ $book->author }}"
                                    data-subject="{{ $book->subject }}"
                                    data-qty="{{ $book->qty }}"
                                    data-price="{{ $book->price }}"
                                    data-post-date="{{ $book->post_date }}"
                                    data-image="{{ $book->image_path ?? '' }}"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-sm">{{ $book->book_id }}</td>
                                    <td class="px-6 py-2">
                                        @if($book->image_path)
                                            <img src="{{ asset('storage/' . $book->image_path) }}" alt="{{ $book->book_name }}" class="w-12 h-16 object-cover rounded shadow-sm" />
                                        @else
                                            <div class="w-12 h-16 bg-gray-200 flex items-center justify-center rounded shadow-inner">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-semibold">{{ $book->book_name }}</td>
                                    {{-- <td class="px-6 py-4">{{ $book->book_no }}</td> --}}
                                    <td class="px-6 py-4">{{ $book->rack_no }}</td>
                                    <td class="px-6 py-4">{{ $book->publisher }}</td>
                                    <td class="px-6 py-4">{{ $book->author }}</td>
                                    <td class="px-6 py-4">{{ $book->subject }}</td>
                                    <td class="px-6 py-4 text-center">{{ $book->qty }}</td>
                                    <td class="px-6 py-4 text-right font-mono">₹{{ number_format($book->price, 2) }}</td>
                                    <td class="px-6 py-4">{{ date('d M Y', strtotime($book->post_date)) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button
                                            class="text-indigo-600 hover:text-indigo-900 font-semibold mr-3"
                                            @click="
                                                editBookId = {{ $book->id }};
                                                editBookData = {
                                                    book_name: '{{ $book->book_name }}',
                                                    {{-- book_no: '{{ $book->book_no }}', --}}
                                                    rack_no: '{{ $book->rack_no }}',
                                                    publisher: '{{ $book->publisher }}',
                                                    author: '{{ $book->author }}',
                                                    subject: '{{ $book->subject }}',
                                                    qty: {{ $book->qty }},
                                                    price: {{ $book->price }},
                                                    post_date: '{{ $book->post_date }}',
                                                    image_preview: @if($book->image_path) '<img src=\'{{ asset('storage/' . $book->image_path) }}\' class=\'w-full h-full object-cover rounded\' alt=\'Book Preview\'>' @else null @endif
                                                };
                                                editBookModalOpen = true;
                                            "
                                        >Edit</button>
                                        <button
                                            class="text-red-600 hover:text-red-800 font-semibold"
                                            @click="deleteBookId = {{ $book->id }}; deleteBookModalOpen = true;"
                                        >Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    {{-- Add Book Modal --}}
    <div
        x-show="addBookModalOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[9999] p-4"
    >
        <div
            @click.outside="addBookModalOpen = false"
            class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-8 relative space-y-6 max-h-[90vh] overflow-y-auto"
        >
            <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b border-gray-200 pb-3">Add New Book</h2>

            <button
                @click="addBookModalOpen = false"
                class="absolute top-5 right-5 text-gray-600 hover:text-gray-900 focus:outline-none rounded-full transition"
                aria-label="Close Add Book Modal"
            >
                <svg class="h-6 w-6 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <form id="addBookForm" action="{{ route('school.books.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-2 gap-6">
                @csrf

                <div class="col-span-2">
                    <label for="generated_book_id" class="block mb-2 font-semibold text-gray-700">Book ID</label>
                    <input type="text" id="generated_book_id" readonly class="w-full px-4 py-2 rounded border border-gray-300 bg-gray-100 text-gray-600 text-sm" />
                    <input type="hidden" id="book_id" name="book_id" />
                </div>

                @foreach ([
                    ['id' => 'book_name', 'label' => 'Book Name', 'type' => 'text', 'placeholder' => 'Enter book name'],
                    ['id' => 'rack_no', 'label' => 'Rack No', 'type' => 'text', 'placeholder' => 'Enter rack number'],
                    ['id' => 'publisher', 'label' => 'Publisher', 'type' => 'text', 'placeholder' => 'Enter publisher name'],
                    ['id' => 'author', 'label' => 'Author', 'type' => 'text', 'placeholder' => 'Enter author name'],
                    ['id' => 'subject', 'label' => 'Subject', 'type' => 'text', 'placeholder' => 'Enter subject'],
                    ['id' => 'price', 'label' => 'Price', 'type' => 'number', 'placeholder' => 'Enter price', 'step' => '0.01', 'min' => '0'],
                    ['id' => 'qty', 'label' => 'Qty', 'type' => 'number', 'placeholder' => 'Enter quantity', 'min' => '0'],
                ] as $field)
                    <div>
                        <label for="{{ $field['id'] }}" class="block mb-2 font-semibold text-gray-700">{{ $field['label'] }}</label>
                        <input
                            type="{{ $field['type'] }}"
                            id="{{ $field['id'] }}"
                            name="{{ $field['id'] }}"
                            placeholder="{{ $field['placeholder'] }}"
                            value="{{ old($field['id']) }}"
                            @if(isset($field['min'])) min="{{ $field['min'] }}" @endif
                            @if(isset($field['step'])) step="{{ $field['step'] }}" @endif
                            required
                            class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>
                @endforeach

                <div class="col-span-2">
                    <label class="block mb-2 font-semibold text-gray-700">Enter Book Numbers</label>
                    <div id="bookNumberInputs" class="space-y-3"></div>
                </div>

                <div class="col-span-2">
                    <label for="book_image" class="block mb-2 font-semibold text-gray-700">Book Image</label>
                    <div class="flex items-center space-x-4">
                        <input
                            type="file"
                            id="book_image"
                            name="book_image"
                            accept="image/*"
                            onchange="previewImage(this, 'image_preview')"
                            class="px-4 py-2 rounded border border-gray-300 w-full"
                        />
                        <div id="image_preview" class="w-24 h-32 bg-gray-100 border rounded flex items-center justify-center text-gray-400 text-xs">
                            No image
                        </div>
                    </div>
                </div>

                <div class="col-span-2">
                    <label for="post_date" class="block mb-2 font-semibold text-gray-700">Post Date</label>
                    <input
                        type="date"
                        id="post_date"
                        name="post_date"
                        required
                        class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                    />
                </div>

                <div class="col-span-2 flex justify-end space-x-5 mt-6">
                    <button type="button" @click="addBookModalOpen = false" class="px-6 py-3 rounded-lg bg-gray-300 text-gray-700 hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-6 py-3 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Add Book</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Book Modal --}}
    <div
        x-show="editBookModalOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[9999] p-4"
    >
        <div
            @click.outside="editBookModalOpen = false"
            class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-8 relative space-y-6 max-h-[90vh] overflow-y-auto"
        >
            <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b border-gray-200 pb-3">Edit Book</h2>

            <button
                @click="editBookModalOpen = false"
                class="absolute top-5 right-5 text-gray-600 hover:text-gray-900 focus:outline-none rounded-full transition"
                aria-label="Close Edit Modal"
            >
                <svg class="h-6 w-6 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <form
                id="editBookForm"
                method="POST"
                enctype="multipart/form-data"
                class="grid grid-cols-2 gap-6"
                x-bind:action="'/school/books/' + editBookId"
            >
                @csrf
                @method('PUT')

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Book Name</label>
                    <input
                        type="text"
                        name="edit_book_name"
                        x-bind:value="editBookData.book_name"
                        required
                        class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                    />
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Rack No</label>
                    <input
                        type="text"
                        name="edit_rack_no"
                        x-bind:value="editBookData.rack_no"
                        required
                        class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                    />
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Publisher</label>
                    <input
                        type="text"
                        name="edit_publisher"
                        x-bind:value="editBookData.publisher"
                        required
                        class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                    />
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Author</label>
                    <input
                        type="text"
                        name="edit_author"
                        x-bind:value="editBookData.author"
                        required
                        class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                    />
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Subject</label>
                    <input
                        type="text"
                        name="edit_subject"
                        x-bind:value="editBookData.subject"
                        required
                        class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                    />
                </div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Qty</label>
                    <input
                        type="number"
                        name="edit_qty"
                        min="0"
                        x-bind:value="editBookData.qty"
                        required
                        class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                    />
                </div>

                <div class="col-span-2" id="editNewBookNumbersContainer"></div>

                <div>
                    <label class="block mb-2 font-semibold text-gray-700">Price</label>
                    <input
                        type="number"
                        name="edit_price"
                        min="0"
                        step="0.01"
                        x-bind:value="editBookData.price"
                        required
                        class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                    />
                </div>

                <div class="col-span-2">
                    <label class="block mb-2 font-semibold text-gray-700">Book Image</label>
                    <div class="flex items-center space-x-4">
                        <input
                            type="file"
                            name="edit_book_image"
                            accept="image/*"
                            onchange="previewImage(this, 'edit_image_preview')"
                            class="px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500 w-full"
                        />
                        <div
                            id="edit_image_preview"
                            class="w-24 h-32 bg-gray-100 border rounded flex items-center justify-center text-gray-400 text-sm overflow-hidden"
                            x-html="editBookData.image_preview ? editBookData.image_preview : 'No image'"
                        ></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep the current image.</p>
                </div>

                <div class="col-span-2">
                    <label class="block mb-2 font-semibold text-gray-700">Post Date</label>
                    <input
                        type="date"
                        name="edit_post_date"
                        x-bind:value="editBookData.post_date"
                        required
                        class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500"
                    />
                </div>

                <div class="col-span-2 flex justify-end space-x-5 mt-6">
                    <button type="button" @click="editBookModalOpen = false" class="px-6 py-3 rounded-lg bg-gray-300 text-gray-700 hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-6 py-3 rounded-lg bg-green-600 text-white hover:bg-green-700">Update Book</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Book Modal --}}
    <div
        x-show="deleteBookModalOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
    >
        <div
            @click.outside="deleteBookModalOpen = false"
            class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 space-y-6 relative"
        >
            <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b border-gray-200 pb-3">Confirm Delete</h2>
            <p class="text-gray-700 mb-6">Are you sure you want to delete this book?</p>
            <form method="POST" x-bind:action="'/school/books/' + deleteBookId" class="flex justify-end space-x-5">
                @csrf
                @method('DELETE')
                <button type="button" @click="deleteBookModalOpen = false" class="px-6 py-3 rounded-lg bg-gray-300 text-gray-700 hover:bg-gray-400 transition focus:outline-none focus:ring-2 focus:ring-gray-400">Cancel</button>
                <button type="submit" class="px-6 py-3 rounded-lg bg-red-600 text-white hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-red-600">Delete</button>
            </form>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<style>
    div.dataTables_wrapper { width: 100%; }
    .dataTables_length select, .dataTables_filter input {
        @apply border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500;
    }
    .dataTables_filter input { width: 16rem !important; }
    .dataTables_paginate { @apply flex space-x-1 mt-4; }
    .dataTables_paginate a {
        @apply border border-gray-300 rounded px-3 py-1 text-sm text-gray-700 hover:bg-gray-200 cursor-pointer;
    }
    .dataTables_paginate .current { @apply bg-indigo-600 text-white border-indigo-600; pointer-events: none; }
    .dataTables_paginate .disabled { @apply text-gray-400 cursor-not-allowed border-gray-200; }
    .dataTables_info { @apply text-gray-600 text-sm mt-2; }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bookData', () => ({
            addBookModalOpen: @if($errors->any() && !old('_method')) true @else false @endif,
            editBookModalOpen: @if($errors->any() && old('_method') == 'PUT') true @else false @endif,
            deleteBookModalOpen: false,
            deleteBookId: null,
            editBookId: @if(old('_method') == 'PUT') {{ old('edit_book_id', 'null') }} @else null @endif,
            editBookData: {
                book_name: '{{ old('edit_book_name', '') }}',
                book_no: '{{ old('edit_book_no', '') }}',
                rack_no: '{{ old('edit_rack_no', '') }}',
                publisher: '{{ old('edit_publisher', '') }}',
                author: '{{ old('edit_author', '') }}',
                subject: '{{ old('edit_subject', '') }}',
                qty: '{{ old('edit_qty', '') }}',
                price: '{{ old('edit_price', '') }}',
                post_date: '{{ old('edit_post_date', '') }}',
                image_preview: null
            },
            booksTable: null,

            init() {
                this.initializeDataTable();
            },

            initializeDataTable() {
                this.booksTable = $('#booksTable').DataTable({
                    language: {
                        search: "",
                        searchPlaceholder: "Search books..."
                    },
                    lengthMenu: [5, 10, 25, 50],
                    pageLength: 5,
                    dom:
                        "<'flex justify-between items-center mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
                        "t" +
                        "<'flex justify-between items-center mt-4'<'dataTables_info'i><'dataTables_paginate'p>>",
                });
            },

            generateBookId() {
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const randomNum = Math.floor(1000 + Math.random() * 9000);
                const bookId = `BK-${year}${month}${day}-${randomNum}`;
                document.getElementById('generated_book_id').value = bookId;
                document.getElementById('book_id').value = bookId;
            }
        }));
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Set today's date default for post_date in Add Book modal
        document.getElementById('post_date').value = new Date().toISOString().split('T')[0];

        // SweetAlert notifications
        @if(session('success'))
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif
        @if(session('error'))
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif
    });

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded" alt="Book Preview">`;

                if (previewId === 'edit_image_preview') {
                    const bookData = Alpine.store('bookData') || Alpine.data('bookData').$data;
                    if (bookData?.editBookData) {
                        bookData.editBookData.image_preview = preview.innerHTML;
                    }
                }
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.innerHTML = `<span class="text-gray-400 text-sm">No image</span>`;
            if (previewId === 'edit_image_preview') {
                const bookData = Alpine.store('bookData') || Alpine.data('bookData').$data;
                if (bookData?.editBookData) bookData.editBookData.image_preview = null;
            }
        }
    }

    // Dynamic book number inputs based on qty (Add Book)
   document.getElementById('qty').addEventListener('input', function() {
    let qty = parseInt(this.value);
    const container = document.getElementById('bookNumberInputs');

    // Clear previous inputs
    container.innerHTML = '';

    // If empty or invalid
    if (!qty || qty <= 0) return;

    // MAX LIMIT = 20
    if (qty > 20) {
        qty = 20;
        this.value = 20;

        alert("Maximum 20 copies allowed.");
    }

    // Generate book number inputs
    for (let i = 1; i <= qty; i++) {
        container.insertAdjacentHTML('beforeend', `
            <div>
                <label class="block text-sm font-semibold text-gray-600">Book Number ${i}</label>
                <input type="text" name="book_numbers[]" 
                       required 
                       placeholder="Enter unique number for copy ${i}"
                       class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500" />
            </div>
        `);
    }
});

    // Dynamic new book numbers inputs based on edit qty
    document.addEventListener('DOMContentLoaded', () => {
        const editQtyInput = document.querySelector("input[name='edit_qty']");
        const container = document.getElementById("editNewBookNumbersContainer");

        let originalQty = 0;

        document.addEventListener("click", function(event) {
            // Track original quantity from the row clicked for editing
            if (event.target.closest("[x-on\\:click]")) {
                const row = event.target.closest("tr");
                if (row) originalQty = parseInt(row.dataset.qty);
            }
        });

        editQtyInput.addEventListener('input', function () {
            const newQty = parseInt(this.value);
            container.innerHTML = '';

            if (!newQty) return;

            if (newQty > originalQty) {
                const toAdd = newQty - originalQty;
                for (let i = 1; i <= toAdd; i++) {
                    container.insertAdjacentHTML("beforeend", `
                        <div class="mt-4">
                            <label class="block font-semibold text-gray-700">New Book Number ${i}</label>
                            <input type="text" name="new_book_numbers[]" required
                                   placeholder="Enter new book number"
                                   class="w-full px-4 py-2 rounded border border-gray-300 focus:ring-2 focus:ring-indigo-500" />
                        </div>
                    `);
                }
            }
        });
    });
</script>
