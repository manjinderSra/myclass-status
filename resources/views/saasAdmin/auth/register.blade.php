@include('saasAdmin.layout.header')

<div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Sign Up</h2>

        @if($errors->any())
        <div class="mb-4 p-3 text-sm rounded bg-red-100 text-red-800">
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('saasAdmin.register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-1" for="name">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full border px-3 py-2 rounded">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-1" for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full border px-3 py-2 rounded">
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-1" for="password">Password</label>
                <input type="password" name="password" id="password" required class="w-full border px-3 py-2 rounded">
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-1" for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full border px-3 py-2 rounded">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Register
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('saasAdmin.login') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">Sign
                in</a>
        </div>
    </div>
</div>

@include('saasAdmin.layout.footer')
