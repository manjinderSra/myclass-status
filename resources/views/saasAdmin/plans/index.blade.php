@include('saasAdmin.layout.header')

<div class="flex h-screen bg-gray-100">
    @include('saasAdmin.layout.sidebar')
    
    <div class="flex-1">
        @include('saasAdmin.layout.topbar')
        
        <main class="p-6 mt-16">
            {{-- Debug Info --}}
            {{-- <div class="bg-gray-200 p-4 mb-4 rounded">
                <h3 class="font-bold">Debug Info:</h3>
                <p>Total Plans: {{ count($plans) }}</p>
                <p>Active Plans: {{ count($plans->where('is_active', true)) }}</p>
                <p>Inactive Plans: {{ count($plans->where('is_active', false)) }}</p>
                <p>Total Subscriptions: {{ count($subscriptions) }}</p>
            </div> --}}
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Subscription Plans</h1>
                    <a href="{{ route('saasAdmin.plans.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        Add New Plan
                    </a>
                </div>
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                
                <!-- Active Plans Grid -->
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Active Plans</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    @forelse($plans->where('is_active', true) as $plan)
                        <div class="border rounded-lg overflow-hidden {{ $plan->is_popular ? 'relative' : '' }}">
                            @if($plan->is_popular)
                                <div class="absolute top-0 right-0 bg-green-500 text-white text-xs font-bold px-3 py-1 transform translate-x-2 -translate-y-2 rotate-12">
                                    Popular
                                </div>
                            @endif
                            <div class="bg-{{ $loop->iteration % 3 == 0 ? 'indigo' : ($loop->iteration % 2 == 0 ? 'purple' : 'blue') }}-50 p-4 border-b">
                                <h2 class="text-xl font-semibold text-{{ $loop->iteration % 3 == 0 ? 'indigo' : ($loop->iteration % 2 == 0 ? 'purple' : 'blue') }}-800">{{ $plan->name }}</h2>
                                <div class="mt-2 flex items-end">
                                    <span class="text-3xl font-bold text-gray-900"> <i class="fa fa-rupee"></i>{{ $plan->price }}</span>
                                    <span class="text-gray-600 ml-1">/{{ $plan->billing_cycle }}</span>
                                </div>
                                <div class="mt-2 text-sm font-medium text-gray-700 bg-white px-2 py-1 rounded-full inline-block">
                                    <span class="font-bold">{{ $plan->active_subscriptions_count }}</span> active {{ Str::plural('user', $plan->active_subscriptions_count) }}
                                </div>
                            </div>
                            <div class="p-4">
                                <ul class="space-y-3">
                                    @if($plan->max_students > 0)
                                        <li class="flex items-center">
                                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>Up to {{ $plan->max_students }} students</span>
                                        </li>
                                    @else
                                        <li class="flex items-center">
                                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>Unlimited students</span>
                                        </li>
                                    @endif
                                    
                                    @if($plan->max_teachers > 0)
                                        <li class="flex items-center">
                                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $plan->max_teachers }} teachers</span>
                                        </li>
                                    @else
                                        <li class="flex items-center">
                                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>Unlimited teachers</span>
                                        </li>
                                    @endif
                                    
                                    @foreach($plan->features->take(3) as $feature)
                                        <li class="flex items-center">
                                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $feature->name }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="border-t p-4 flex space-x-2">
                                <a href="{{ route('saasAdmin.plans.edit', $plan) }}" class="flex-1 px-4 py-2 border border-blue-600 text-blue-600 rounded hover:bg-blue-50 transition text-center">Edit</a>
                                <form action="{{ route('saasAdmin.plans.destroy', $plan) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-2 border border-red-600 text-red-600 rounded hover:bg-red-50 transition" onclick="return confirm('Are you sure you want to delete this plan?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3">
                            <p class="text-gray-500 text-center py-4">No active plans found.</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Inactive Plans List -->
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Inactive Plans</h2>
                <div class="mb-8">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Billing Cycle</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($plans->where('is_active', false) as $plan)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-sm font-medium text-gray-900">{{ $plan->name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-currency-rupee" viewBox="0 0 16 16">
  <path d="M4 3.06h2.726c1.22 0 2.12.575 2.325 1.724H4v1.051h5.051C8.855 7.001 8 7.558 6.788 7.558H4v1.317L8.437 14h2.11L6.095 8.884h.855c2.316-.018 3.465-1.476 3.688-3.049H12V4.784h-1.345c-.08-.778-.357-1.335-.793-1.732H12V2H4z"/>
</svg>{{ $plan->price }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ ucfirst($plan->billing_cycle) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium px-2 py-1 rounded-full inline-block bg-gray-100 text-gray-800">
                                                {{ $plan->active_subscriptions_count }} {{ Str::plural('user', $plan->active_subscriptions_count) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('saasAdmin.plans.edit', $plan) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                            <form action="{{ route('saasAdmin.plans.destroy', $plan) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2" onclick="return confirm('Are you sure you want to delete this plan?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No inactive plans found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Subscriptions Table -->
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Subscriptions</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($subscriptions as $subscription)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->school->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->plan->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->start_date ? $subscription->start_date->format('M d, Y') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->end_date ? $subscription->end_date->format('M d, Y') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($subscription->status == 'active')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @elseif($subscription->status == 'expired')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Expired
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Cancelled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a href="{{ route('saasAdmin.subscriptions.show', $subscription) }}" class="text-indigo-600 hover:text-indigo-900">Manage</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No subscriptions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

@include('saasAdmin.layout.footer') 