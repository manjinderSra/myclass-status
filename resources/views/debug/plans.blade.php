<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plans Debug</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Plans Debug</h1>
        
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-semibold mb-4">Summary</h2>
            <p>Total Plans: {{ count($plans) }}</p>
            <p>Active Plans: {{ count($plans->where('is_active', true)) }}</p>
            <p>Inactive Plans: {{ count($plans->where('is_active', false)) }}</p>
            <p>Total Subscriptions: {{ count($allSubscriptions) }}</p>
            <p>Active Subscriptions: {{ count($allSubscriptions->where('status', 'active')) }}</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-semibold mb-4">Plans List</h2>
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active Subscriptions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($plans as $plan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $plan->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $plan->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${{ $plan->price }} / {{ $plan->billing_cycle }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($plan->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $subscriptionCounts[$plan->id] ?? 0 }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if(count($plans) === 0)
                <p class="text-gray-500 text-center py-4">No plans found</p>
            @endif
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">All Subscriptions</h2>
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan ID</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School ID</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($allSubscriptions as $subscription)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->plan_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $subscription->school_id }}</td>
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
                                        {{ $subscription->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs">
                                    <div>Start: {{ $subscription->start_date ? $subscription->start_date->format('Y-m-d') : 'N/A' }}</div>
                                    <div>End: {{ $subscription->end_date ? $subscription->end_date->format('Y-m-d') : 'N/A' }}</div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if(count($allSubscriptions) === 0)
                <p class="text-gray-500 text-center py-4">No subscriptions found</p>
            @endif
        </div>
    </div>
</body>
</html> 