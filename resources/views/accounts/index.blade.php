@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto bg-gray-100 p-10">

        <div class="bg-white p-20 rounded-2xl shadow-md">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Accounts List</h2>

                <a href="{{ route('school.accountDetail.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 transition text-white px-4 py-2 rounded-lg shadow-sm flex items-center gap-2">
                    ➕ Add Account
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-500 text-white p-3 rounded-lg mb-4 shadow">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden border border-gray-200 rounded-xl shadow-sm">
                <table class="w-full text-left text-gray-700">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                        <tr>
                            <th class="border-b p-3">Name</th>
                            <th class="border-b p-3">Account Number</th>
                            <th class="border-b p-3">IFSC</th>
                            <th class="border-b p-3">UPI ID</th>
                            <th class="border-b p-3">Note</th>
                            <th class="border-b p-3 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y text-sm">
                        @forelse($accounts as $acc)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3">{{ $acc->name }}</td>
                            <td class="p-3">{{ $acc->account_number }}</td>
                            <td class="p-3">{{ $acc->ifsc }}</td>
                            <td class="p-3">{{ $acc->upi_id }}</td>
                            <td class="p-3">{{ $acc->note }}</td>

                            <td class="p-3">
                                <div class="flex items-center justify-center gap-3">

                                    {{-- Edit Button --}}
                                    {{-- <a href="{{ route('school.accountDetail.update', $acc->id) }}"
                                       class="px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded-lg shadow">
                                        Edit
                                    </a> --}}

                                    {{-- Delete Button --}}
                                    <form action="{{ route('school.accountDetail.destroy', $acc->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded-lg shadow">
                                            Delete
                                        </button>
                                    </form>

                                    {{-- Featured Button --}}
                                    @if($acc->is_featured)
                                        <span class="px-3 py-1 bg-green-600 text-white text-xs rounded-lg shadow">
                                            Featured
                                        </span>
                                    @else
                                        <a href="{{ route('school.accountDetail.featured', $acc->id) }}"
                                           class="px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white text-xs rounded-lg shadow">
                                            Set Featured
                                        </a>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-5 text-gray-500">
                                    No accounts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
