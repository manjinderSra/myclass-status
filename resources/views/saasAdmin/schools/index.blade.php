@include('saasAdmin.layout.header')

<div class="flex flex-col lg:flex-row min-h-screen bg-gray-100">
    @include('saasAdmin.layout.sidebar')
    
    <div class="flex-1 flex flex-col w-full">
        @include('saasAdmin.layout.topbar')
        
        <main class="p-3 sm:p-4 md:p-6 mt-16 overflow-x-hidden">
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 gap-3">
                    <h1 class="text-xl sm:text-2xl font-semibold text-gray-800">School Subscriptions</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('saasAdmin.addSchool') }}" class="px-3 sm:px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm sm:text-base whitespace-nowrap">
                            Add School
                        </a>
                    </div>
                </div>
                
                <!-- Schools Table - Desktop View -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($schools as $school)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 font-semibold">{{ strtoupper(substr($school->name, 0, 2)) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $school->name }}</div>
                                            <div class="text-sm text-gray-500">ID: {{ $school->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $school->admin->name ?? 'No Admin' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $school->admin->email ?? 'No Email' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($school->subscriptions->isNotEmpty())
                                            {{ $school->subscriptions->first()->plan->name ?? 'No Plan' }}
                                        @else
                                            No Subscription
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($school->subscriptions->isNotEmpty() && $school->subscriptions->first()->status === 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex flex-col space-y-2">
                                        <a href="javascript:void(0)" onclick="openPasswordModal({{ $school->admin->id }})" class="text-red-600 hover:text-red-900">
                                            Forget password
                                        </a>
                                        <a href="{{ route('school.editSubscription', $school->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No schools found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Schools Cards - Mobile/Tablet View -->
                <div class="lg:hidden space-y-4">
                    @forelse($schools as $school)
                    <div class="border border-gray-200 rounded-lg p-4 bg-white shadow-sm">
                        <div class="flex items-start space-x-3 mb-3">
                            <div class="h-12 w-12 flex-shrink-0 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-semibold text-sm">{{ strtoupper(substr($school->name, 0, 2)) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-semibold text-gray-900 truncate">{{ $school->name }}</h3>
                                <p class="text-xs text-gray-500">ID: {{ $school->id }}</p>
                            </div>
                            <div>
                                @if($school->subscriptions->isNotEmpty() && $school->subscriptions->first()->status === 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500 font-medium">Admin:</span>
                                <span class="text-gray-900 text-right">{{ $school->admin->name ?? 'No Admin' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 font-medium">Email:</span>
                                <span class="text-gray-900 text-right truncate ml-2">{{ $school->admin->email ?? 'No Email' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 font-medium">Subscription:</span>
                                <span class="text-gray-900 text-right">
                                    @if($school->subscriptions->isNotEmpty())
                                        {{ $school->subscriptions->first()->plan->name ?? 'No Plan' }}
                                    @else
                                        No Subscription
                                    @endif
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-2 mt-4 pt-3 border-t border-gray-200">
                            <a href="javascript:void(0)" onclick="openPasswordModal({{ $school->admin->id }})" class="flex-1 text-center px-3 py-2 bg-red-50 text-red-600 rounded hover:bg-red-100 transition text-sm font-medium">
                                Forget password
                            </a>
                            <a href="{{ route('school.editSubscription', $school->id) }}" class="flex-1 text-center px-3 py-2 bg-indigo-50 text-indigo-600 rounded hover:bg-indigo-100 transition text-sm font-medium">
                                Edit
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        No schools found
                    </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                <div class="flex justify-between items-center mt-6">
                    <div class="text-xs sm:text-sm text-gray-700">
                        Showing <span class="font-semibold">{{ $schools->count() }}</span> schools
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal -->
<div id="passwordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 px-4">
    <div class="bg-white p-4 sm:p-6 rounded shadow-lg w-full max-w-md relative">
        <h2 class="text-lg sm:text-xl font-semibold mb-4">Reset Admin Password</h2>

        <form id="resetPasswordForm">
            @csrf
            <input type="hidden" id="modal_user_id" name="user_id">

            <input type="password" name="new_password" class="w-full border p-2 mb-2 rounded text-sm sm:text-base"
                   placeholder="New Password">

            <input type="password" name="new_password_confirmation" class="w-full border p-2 mb-4 rounded text-sm sm:text-base"
                   placeholder="Confirm Password">

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full hover:bg-blue-700 transition text-sm sm:text-base">
                Update Password
            </button>
        </form>

        <button onclick="closePasswordModal()" class="absolute top-2 right-3 text-2xl text-gray-500 hover:text-gray-700">×</button>
    </div>
</div>

@include('saasAdmin.layout.footer')

<script>
function openPasswordModal(userId){
    document.getElementById('modal_user_id').value = userId;
    document.getElementById('passwordModal').classList.remove('hidden');
    document.getElementById('passwordModal').classList.add('flex');
}

function closePasswordModal(){
    document.getElementById('passwordModal').classList.add('hidden');
    document.getElementById('passwordModal').classList.remove('flex');
}

document.getElementById('resetPasswordForm').addEventListener('submit', async function(e){
    e.preventDefault();

    let form = new FormData(this);

    let response = await fetch("{{ route('saasAdmin.forgetSchoolPassword.update') }}", {
        method: "POST",
        headers: { "X-CSRF-TOKEN" : "{{ csrf_token() }}" },
        body: form
    });

    if(response.ok){
        alert("Password Updated!");
        closePasswordModal();
    } else {
        alert("Error");
    }
});
</script>