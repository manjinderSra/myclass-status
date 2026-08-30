@include('saasAdmin.layout.header')

<!-- Alpine.js for frontend interaction -->
<script src="https://unpkg.com/alpinejs" defer></script>

<div class="flex flex-col lg:flex-row min-h-screen bg-gray-100">
    @include('saasAdmin.layout.sidebar')

    <div class="flex-1 flex flex-col w-full">
        @include('saasAdmin.layout.topbar')

        <main class="p-3 sm:p-4 md:p-6 mt-16 overflow-x-hidden">
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6" x-data="{ filter: 'all' }">
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 sm:mb-6">SaaS Admin Dashboard</h1>

                <!-- Dashboard Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 md:gap-6">
                    <div @click="filter = 'all'" class="cursor-pointer bg-blue-500 text-white rounded-lg p-4 sm:p-5 shadow hover:scale-105 transition transform">
                        <h2 class="text-base sm:text-lg font-semibold">Total Schools</h2>
                        <p class="text-2xl sm:text-3xl font-bold mt-1">{{ $schools->count() }}</p>
                    </div>

                    <div @click="filter = 'inactive'" class="cursor-pointer bg-green-500 text-white rounded-lg p-4 sm:p-5 shadow hover:scale-105 transition transform">
                        <h2 class="text-base sm:text-lg font-semibold">Inactive Schools</h2>
                        <p class="text-2xl sm:text-3xl font-bold mt-1">
                            {{ $schools->filter(fn($s) => $s->subscriptions->isEmpty() || $s->subscriptions->first()->status !== 'active')->count() }}
                        </p>
                    </div>

                    <div @click="filter = 'active'" class="cursor-pointer bg-purple-500 text-white rounded-lg p-4 sm:p-5 shadow hover:scale-105 transition transform sm:col-span-2 lg:col-span-1">
                        <h2 class="text-base sm:text-lg font-semibold">Active Schools</h2>
                        <p class="text-2xl sm:text-3xl font-bold mt-1">
                            {{ $schools->filter(fn($s) => $s->subscriptions->isNotEmpty() && $s->subscriptions->first()->status === 'active')->count() }}
                        </p>
                    </div>
                </div>

                <!-- Schools Table -->
                <div class="mt-6 sm:mt-8">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4">Schools</h2>
                    
                    <!-- Desktop Table View -->
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($schools as $school)
                                    @php
                                        $isActive = $school->subscriptions->isNotEmpty() && $school->subscriptions->first()->status === 'active';
                                    @endphp
                                    <tr x-show="filter === 'all' || (filter === 'active' && {{ $isActive ? 'true' : 'false' }}) || (filter === 'inactive' && {{ $isActive ? 'false' : 'true' }})">
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
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">Teacher: {{ $school->students_count }}</div>
                                                    <div class="text-sm text-gray-500">Student: {{ $school->teachers_count }}</div>
                                                </div>
                                            </div>
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
                                            @if($isActive)
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
                                            <a href="{{ route('school.editSubscription', $school->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            No schools found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile/Tablet Card View -->
                    <div class="lg:hidden space-y-4">
                        @forelse($schools as $school)
                            @php
                                $isActive = $school->subscriptions->isNotEmpty() && $school->subscriptions->first()->status === 'active';
                            @endphp
                            <div x-show="filter === 'all' || (filter === 'active' && {{ $isActive ? 'true' : 'false' }}) || (filter === 'inactive' && {{ $isActive ? 'false' : 'true' }})" class="border border-gray-200 rounded-lg p-4 bg-white shadow-sm">
                                <div class="flex items-start space-x-3 mb-3">
                                    <div class="h-12 w-12 flex-shrink-0 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold text-sm">{{ strtoupper(substr($school->name, 0, 2)) }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-semibold text-gray-900 truncate">{{ $school->name }}</h3>
                                        <p class="text-xs text-gray-500">ID: {{ $school->id }}</p>
                                    </div>
                                    <div>
                                        @if($isActive)
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
                                        <span class="text-gray-500 font-medium">Teachers:</span>
                                        <span class="text-gray-900 text-right">{{ $school->students_count }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 font-medium">Students:</span>
                                        <span class="text-gray-900 text-right">{{ $school->teachers_count }}</span>
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
                                
                                <div class="mt-4 pt-3 border-t border-gray-200">
                                    <a href="{{ route('school.editSubscription', $school->id) }}" class="block text-center px-4 py-2 bg-indigo-50 text-indigo-600 rounded hover:bg-indigo-100 transition text-sm font-medium">
                                        Edit Subscription
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                No schools found
                            </div>
                        @endforelse
                    </div>

                    <!-- Summary -->
                    <div class="flex justify-between items-center mt-6">
                        <div class="text-xs sm:text-sm text-gray-700">
                            Showing <span class="font-semibold">{{ $schools->count() }}</span> schools
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@include('saasAdmin.layout.footer')