@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')
<div class="p-6 bg-white shadow rounded-lg">

    <h2 class="text-xl font-bold mb-4">Edit Account</h2>

    <form action="{{ route('accounts.update', $account->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-semibold">Account Holder Name</label>
                <input type="text" name="name" value="{{ $account->name }}" class="w-full border p-2 rounded" />
            </div>

            <div>
                <label class="block font-semibold">Account Number</label>
                <input type="text" name="account_number" value="{{ $account->account_number }}" class="w-full border p-2 rounded" />
            </div>

            <div>
                <label class="block font-semibold">IFSC Code</label>
                <input type="text" name="ifsc" value="{{ $account->ifsc }}" class="w-full border p-2 rounded" />
            </div>

            <div>
                <label class="block font-semibold">UPI ID</label>
                <input type="text" name="upi_id" value="{{ $account->upi_id }}" class="w-full border p-2 rounded" />
            </div>

            <div class="col-span-2">
                <label class="block font-semibold">Note</label>
                <textarea name="note" class="w-full border p-2 rounded">{{ $account->note }}</textarea>
            </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            Update Account
        </button>

        <a href="{{ route('accounts.index') }}" class="ml-2 text-blue-600">
            Back
        </a>
    </form>

</div>
@endsection
