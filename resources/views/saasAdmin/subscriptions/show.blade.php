@include('saasAdmin.layout.header')

<div class="flex h-screen bg-gray-100">
    @include('saasAdmin.layout.sidebar')
    
    <div class="flex-1">
        @include('saasAdmin.layout.topbar')
        
        <main class="p-6 mt-16">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Subscription Details</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('saasAdmin.subscriptions.edit', $subscription) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Edit
                        </a>
                        <a href="{{ route('saasAdmin.subscriptions') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                            Back to Subscriptions
                        </a>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <h2 class="text-lg font-medium text-gray-800 mb-4">School Information</h2>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-500">Name:</span>
                                <p class="text-gray-800 font-medium">{{ $subscription->school->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Email:</span>
                                <p class="text-gray-800">{{ $subscription->school->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Phone:</span>
                                <p class="text-gray-800">{{ $subscription->school->phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Status:</span>
                                <p class="text-gray-800">
                                    @if($subscription->school->status == 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @elseif($subscription->school->status == 'inactive')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Registration Date:</span>
                                <p class="text-gray-800">{{ $subscription->school->registration_date ? $subscription->school->registration_date->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <h2 class="text-lg font-medium text-gray-800 mb-4">Plan Information</h2>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-500">Name:</span>
                                <p class="text-gray-800 font-medium">{{ $subscription->plan->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Price:</span>
                                <p class="text-gray-800"><i class="fa fa-rupee"></i>{{ $subscription->plan->price ?? '0' }}/{{ $subscription->plan->billing_cycle ?? 'month' }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Max Students:</span>
                                <p class="text-gray-800">{{ $subscription->plan->max_students > 0 ? $subscription->plan->max_students : 'Unlimited' }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Max Teachers:</span>
                                <p class="text-gray-800">{{ $subscription->plan->max_teachers > 0 ? $subscription->plan->max_teachers : 'Unlimited' }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Max Staff:</span>
                                <p class="text-gray-800">{{ $subscription->plan->max_staff > 0 ? $subscription->plan->max_staff : 'Unlimited' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 md:col-span-2">
                        <h2 class="text-lg font-medium text-gray-800 mb-4">Subscription Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm text-gray-500">Status:</span>
                                    <p class="text-gray-800">
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
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Start Date:</span>
                                    <p class="text-gray-800">{{ $subscription->start_date ? $subscription->start_date->format('M d, Y') : 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">End Date:</span>
                                    <p class="text-gray-800">{{ $subscription->end_date ? $subscription->end_date->format('M d, Y') : 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Created At:</span>
                                    <p class="text-gray-800">{{ $subscription->created_at ? $subscription->created_at->format('M d, Y H:i') : 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm text-gray-500">Price Paid:</span>
                                    <p class="text-gray-800">${{ $subscription->price_paid ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Payment Method:</span>
                                    <p class="text-gray-800">{{ $subscription->payment_method ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Transaction ID:</span>
                                    <p class="text-gray-800">{{ $subscription->transaction_id ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500">Updated At:</span>
                                    <p class="text-gray-800">{{ $subscription->updated_at ? $subscription->updated_at->format('M d, Y H:i') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 md:col-span-2">
                        <h2 class="text-lg font-medium text-gray-800 mb-4">Included Features</h2>
                        @if($subscription->plan && $subscription->plan->features->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($subscription->plan->features->groupBy('feature_group') as $group => $features)
                                    <div>
                                        <h3 class="text-md font-medium text-gray-700 mb-2">{{ $group }}</h3>
                                        <ul class="space-y-2">
                                            @foreach($features as $feature)
                                                <li class="flex items-start">
                                                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <div>
                                                        <span class="text-gray-800">{{ $feature->name }}</span>
                                                        @if($feature->value_type !== 'boolean' && $feature->pivot->allowed_value)
                                                            <span class="text-gray-500 text-sm"> ({{ $feature->pivot->allowed_value }})</span>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No features available for this plan.</p>
                        @endif
                    </div>
                </div>
                
                <div class="mt-6 flex justify-between">
                    <div>
                        @if($subscription->status == 'active')
                            <form action="{{ route('saasAdmin.subscriptions.cancel', $subscription) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition" onclick="return confirm('Are you sure you want to cancel this subscription?')">
                                    Cancel Subscription
                                </button>
                            </form>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('saasAdmin.subscriptions.edit', $subscription) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Edit Subscription
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@include('saasAdmin.layout.footer') 