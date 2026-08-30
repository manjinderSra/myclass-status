@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 p-28 bg-gray-50 min-h-screen">

        <div class="bg-white shadow rounded-lg p-6">

            <h2 class="text-xl font-bold mb-4">Add New Account</h2>

            <form action="{{ route('school.accountDetail.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                    <div>
                        <label class="block font-semibold">Account Holder Name</label>
                        <input type="text" name="name" class="w-full border p-2 rounded" required />
                    </div>

                    <div>
                        <label class="block font-semibold">Account Number</label>
                        <input type="text" name="account_number" class="w-full border p-2 rounded" required />
                    </div>

                    <div>
                        <label class="block font-semibold">IFSC Code</label>
                        <input type="text" name="ifsc" class="w-full border p-2 rounded" />
                    </div>

                    <div>
                        <label class="block font-semibold">UPI ID</label>
                        <input type="text" name="upi_id" class="w-full border p-2 rounded" />
                    </div>

                    <div class="md:col-span-2">
                        <label class="block font-semibold">Note</label>
                        <textarea name="note" class="w-full border p-2 rounded"></textarea>
                    </div>

                </div>

                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                    Save Account
                </button>

                <a href="{{ route('school.accountDetail.index') }}" 
                   class="ml-3 text-blue-600 font-semibold">
                    Back
                </a>
            </form>

        </div>

    </div>
</div>
