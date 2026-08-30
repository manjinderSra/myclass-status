@include('saasAdmin.layout.header')

<div class="flex h-screen bg-gray-100">
    @include('saasAdmin.layout.sidebar')

    <div class="flex-1">
        @include('saasAdmin.layout.topbar')

        <main class="p-6 mt-16">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Change Subscription Plan</h1>
                    <a href="{{ route('saasAdmin.subscriptions') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Back to Subscriptions
                    </a>
                </div>

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('saasAdmin.school.addSchool') }}" method="POST">
                    @csrf

                    <!-- School Select or New -->
                    <div class="mb-6">
                        <label for="school_id" class="block text-sm font-medium text-gray-700 mb-1">School</label>
                        <select id="school_id" name="school_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('school_id') border-red-500 @enderror" required>
                            <option value="">Select School</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }} ({{ $school->email }})
                                </option>
                            @endforeach
                            <option value="new" {{ old('school_id') == 'new' ? 'selected' : '' }}>Add New School</option>
                        </select>
                        @error('school_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New School Fields -->
                    <div id="new_school_fields" class="mb-6 {{ old('school_id') == 'new' ? '' : 'hidden' }}">
    <label class="block text-sm font-medium text-gray-700 mb-1">New School Details</label>

    <input type="text" name="new_school_name" placeholder="School Name" value="{{ old('new_school_name') }}" class="mb-2 w-full px-3 py-2 border rounded-md" />
    @error('new_school_name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror

    <input type="email" name="new_school_email" placeholder="School Email" value="{{ old('new_school_email') }}" class="mb-2 w-full px-3 py-2 border rounded-md" />
    @error('new_school_email')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror

    <input type="password" name="new_school_password" placeholder="Password" class="mb-2 w-full px-3 py-2 border rounded-md" />
    @error('new_school_password')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror

    <input type="password" name="new_school_password_confirmation" placeholder="Confirm Password" class="w-full px-3 py-2 border rounded-md" />
</div>


                    <!-- Current Plan Display -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Plan</label>
                        <div id="current_plan_info" class="p-4 bg-gray-50 rounded-md text-sm text-gray-600">
                            Select a school to see their current plan
                        </div>
                    </div>

                    <!-- Plan Select -->
                    <div class="mb-6">
                        <label for="plan_id" class="block text-sm font-medium text-gray-700 mb-1">New Plan</label>
                        <select id="plan_id" name="plan_id" class="w-full px-3 py-2 border border-gray-300 rounded-md @error('plan_id') border-red-500 @enderror" required>
                            <option value="">Select Plan</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} ({{ $plan->billing_cycle == 'monthly' ? '$'.$plan->monthly_price.'/month' : '$'.$plan->yearly_price.'/year' }})
                                </option>
                            @endforeach
                        </select>
                        @error('plan_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Billing + Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="billing_cycle" class="block text-sm font-medium text-gray-700 mb-1">Billing Cycle</label>
                            <select id="billing_cycle" name="billing_cycle" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                                <option value="monthly" {{ old('billing_cycle') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ old('billing_cycle') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>
                    </div>

                    <!-- Start & End Date -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" class="w-full px-3 py-2 border rounded-md" required>
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="{{ old('end_date', date('Y-m-d', strtotime('+1 month'))) }}" class="w-full px-3 py-2 border rounded-md" required>
                        </div>
                    </div>

                    <!-- Payment Status & Amount -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                            <select id="payment_status" name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                                <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ old('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div>
                            <label for="amount_paid" class="block text-sm font-medium text-gray-700 mb-1">Amount Paid</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">$</span>
                                </div>
                                <input type="number" id="amount_paid" name="amount_paid" step="0.01" min="0" value="{{ old('amount_paid', '0.00') }}" class="w-full pl-7 px-3 py-2 border rounded-md" required>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-6">
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <input type="text" name="payment_method" value="{{ old('payment_method', 'manual') }}" class="w-full px-3 py-2 border rounded-md">
                    </div>

                    <!-- Notes -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border rounded-md">{{ old('notes', 'Plan changed by admin') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Save Subscription Plan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

@include('saasAdmin.layout.footer')

<!-- JS Section -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const schoolSelect = document.getElementById('school_id');
        const newSchoolFields = document.getElementById('new_school_fields');
        const currentPlanInfo = document.getElementById('current_plan_info');
        const planSelect = document.getElementById('plan_id');
        const billingCycleSelect = document.getElementById('billing_cycle');
        const amountPaidInput = document.getElementById('amount_paid');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        // Show/hide new school fields
        schoolSelect.addEventListener('change', function () {
            if (this.value === 'new') {
                newSchoolFields.classList.remove('hidden');
                currentPlanInfo.innerHTML = `<p class="text-blue-500 text-sm">Adding a new school — no current plan available.</p>`;
            } else {
                newSchoolFields.classList.add('hidden');

                // Fetch current subscription
                fetch(`/api/schools/${this.value}/subscription`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.subscription) {
                            const sub = data.subscription;
                            currentPlanInfo.innerHTML = `
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-800">${sub.plan.name}</p>
                                        <p class="text-sm text-gray-600">Valid until: ${new Date(sub.end_date).toLocaleDateString()}</p>
                                        <p class="text-sm text-gray-600">Status: ${sub.status}</p>
                                    </div>
                                    <div>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">${sub.billing_cycle}</span>
                                    </div>
                                </div>
                            `;
                        } else {
                            currentPlanInfo.innerHTML = `<p class="text-yellow-600">No active subscription found for this school.</p>`;
                        }
                    })
                    .catch(() => {
                        currentPlanInfo.innerHTML = `<p class="text-red-500">Error loading subscription.</p>`;
                    });
            }
        });

        // Auto update amount based on selected plan and billing cycle
        function updateAmount() {
            const planId = planSelect.value;
            const billingCycle = billingCycleSelect.value;
            if (planId) {
                fetch(`/api/plans/${planId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.plan) {
                            amountPaidInput.value = billingCycle === 'monthly' ? data.plan.monthly_price : data.plan.yearly_price;
                        }
                    });
            }
        }
        planSelect.addEventListener('change', updateAmount);
        billingCycleSelect.addEventListener('change', updateAmount);

        // Auto calculate end date
        function updateEndDate() {
            const startDate = new Date(startDateInput.value);
            const billingCycle = billingCycleSelect.value;
            if (!isNaN(startDate)) {
                const endDate = new Date(startDate);
                if (billingCycle === 'monthly') endDate.setMonth(endDate.getMonth() + 1);
                else endDate.setFullYear(endDate.getFullYear() + 1);

                const format = d => `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
                endDateInput.value = format(endDate);
            }
        }
        startDateInput.addEventListener('change', updateEndDate);
        billingCycleSelect.addEventListener('change', updateEndDate);
    });
</script>
