@include('client.schoolPanel.layout.header')

<div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Sign Up</h2>

        {{-- AJAX Status Message --}}
        <div id="statusMessage" class="hidden mb-4 p-3 text-sm rounded"></div>

        <form id="registerForm">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-1" for="name">Name</label>
                <input type="text" name="name" id="name" required class="w-full border px-3 py-2 rounded">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-1" for="email">Email</label>
                <input type="email" name="email" id="email" required class="w-full border px-3 py-2 rounded">
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

            <!-- Hidden Role -->
            <input type="hidden" name="role" value="saasAdmin">

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Register
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('saasAdmin.register') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">Sign
                in</a>
        </div>
    </div>
</div>

{{-- AJAX Script --}}
<script>
    $('#registerForm').on('submit', function (e) {
        e.preventDefault();

        let formData = {
            name: $('#name').val(),
            email: $('#email').val(),
            password: $('#password').val(),
            password_confirmation: $('#password_confirmation').val(),
            role: 'saasAdmin',
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: "{{ url('api/register') }}",
            type: "POST",
            data: formData,
            success: function () {
                $('#statusMessage')
                    .removeClass()
                    .addClass('mb-4 p-3 text-sm rounded bg-green-100 text-green-800')
                    .text('Registration successful! Redirecting...')
                    .show();

                setTimeout(() => {
                    window.location.href = "{{ route('saasAdmin.dashboard') }}";
                }, 1500);
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors;
                let errorText = "Something went wrong.";

                if (errors) {
                    errorText = Object.values(errors).map(e => e.join(', ')).join('\n');
                }

                $('#statusMessage')
                    .removeClass()
                    .addClass('mb-4 p-3 text-sm rounded bg-red-100 text-red-800')
                    .text(errorText)
                    .show();
            }
        });
    });
</script>

@include('client.schoolPanel.layout.header')
