@include('saasAdmin.layout.header')

<div class="flex h-screen bg-gray-100">
    @include('saasAdmin.layout.sidebar')
    
    <div class="flex-1">
        @include('saasAdmin.layout.topbar')
        
        <main class="p-6 mt-16">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800">Edit Subscription Plan</h1>
                    <a href="{{ route('saasAdmin.plans') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Back to Plans
                    </a>
                </div>
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                
                <form action="{{ route('saasAdmin.plans.update', $plan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Plan Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $plan->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm"><i class="fa fa-rupee"></i></span>
                                </div>
                                <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $plan->price) }}" class="w-full pl-7 pr-12 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="0.00" required>
                            </div>
                            @error('price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="billing_cycle" class="block text-sm font-medium text-gray-700 mb-1">Billing Cycle</label>
                            <select name="billing_cycle" id="billing_cycle" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="monthly" {{ old('billing_cycle', $plan->billing_cycle) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ old('billing_cycle', $plan->billing_cycle) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                            @error('billing_cycle')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $plan->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="max_students" class="block text-sm font-medium text-gray-700 mb-1">Max Students (0 for unlimited)</label>
                            <input type="number" name="max_students" id="max_students" value="{{ old('max_students', $plan->max_students) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" min="0" required>
                            @error('max_students')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="max_teachers" class="block text-sm font-medium text-gray-700 mb-1">Max Teachers (0 for unlimited)</label>
                            <input type="number" name="max_teachers" id="max_teachers" value="{{ old('max_teachers', $plan->max_teachers) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" min="0" required>
                            @error('max_teachers')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="max_staff" class="block text-sm font-medium text-gray-700 mb-1">Max Staff (0 for unlimited)</label>
                            <input type="number" name="max_staff" id="max_staff" value="{{ old('max_staff', $plan->max_staff) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" min="0" required>
                            @error('max_staff')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex items-center space-x-6">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_popular" id="is_popular" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('is_popular', $plan->is_popular) ? 'checked' : '' }}>
                                <label for="is_popular" class="ml-2 block text-sm text-gray-900">Mark as Popular</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" {{ old('is_active', $plan->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h2 class="text-lg font-medium text-gray-800 mb-3">Plan Features</h2>
                        <div class="border rounded-lg p-4">
                            <!-- Quick Selection Options -->
                            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                <h3 class="text-md font-medium text-gray-700 mb-2">Quick Selection</h3>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" id="selectAll" class="px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-sm">Select All</button>
                                    <button type="button" id="deselectAll" class="px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-sm">Deselect All</button>
                                    @foreach ($featureGroups as $group)
                                        <button type="button" data-group="{{ $group }}" class="select-group px-2 py-1 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 text-sm">Select {{ ucwords(str_replace('_', ' ', $group)) }}</button>
                                    @endforeach
                                </div>
                            </div>
                            
                            @forelse ($featureGroups as $group)
                                <div class="mb-4 feature-group-section" id="group-{{ $group }}">
                                    <div class="flex items-center justify-between bg-gray-100 p-2 rounded cursor-pointer group-header">
                                        <h3 class="text-md font-medium text-gray-700">{{ ucwords(str_replace('_', ' ', $group)) }}</h3>
                                        <div>
                                            <button type="button" class="px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 text-xs select-section">Select All</button>
                                            <svg class="h-5 w-5 inline-block text-gray-500 toggle-icon" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="group-features p-3 border border-gray-200 rounded-b-lg">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach ($features->where('feature_group', $group) as $feature)
                                                <div class="flex items-start p-2 hover:bg-gray-50 rounded feature-row">
                                                    <div class="flex items-center h-5">
                                                        <input type="checkbox" name="features[]" id="feature_{{ $feature->id }}" value="{{ $feature->id }}" 
                                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded feature-checkbox" 
                                                            data-group="{{ $group }}"
                                                            {{ in_array($feature->id, old('features', $plan->features->pluck('id')->toArray())) ? 'checked' : '' }}
                                                            {{ in_array($feature->code, ['max_students', 'max_teachers', 'max_staff']) ? 'data-resource-limit="true"' : '' }}>
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <label for="feature_{{ $feature->id }}" class="font-medium text-gray-700">{{ $feature->name }}</label>
                                                        @if ($feature->value_type !== 'boolean')
                                                            <div class="mt-1">
                                                                <div class="flex items-center">
                                                                    @if ($feature->value_type === 'number')
                                                                        <input type="number" name="feature_values[{{ $feature->id }}]" id="feature_value_{{ $feature->id }}" placeholder="Value" class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md feature-value" value="{{ old('feature_values.' . $feature->id, $planFeatures[$feature->id] ?? '') }}">
                                                                        <span class="ml-1 text-xs text-gray-500">{{ $feature->code == 'max_file_size' ? 'MB' : '' }}</span>
                                                                    @else
                                                                        <input type="text" name="feature_values[{{ $feature->id }}]" id="feature_value_{{ $feature->id }}" placeholder="Value" class="w-full px-2 py-1 text-sm border border-gray-300 rounded-md feature-value" value="{{ old('feature_values.' . $feature->id, $planFeatures[$feature->id] ?? '') }}">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <p class="text-gray-500 text-xs mt-1">{{ $feature->description }}</p>
                                                        <p class="text-gray-400 text-xs mt-1">Code: <code>{{ $feature->code }}</code></p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No features available. <a href="{{ route('saasAdmin.features.create') }}" class="text-blue-600 hover:underline">Create some features</a> to add to your plans.</p>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="debugFormBtn" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">Debug Form</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Update Plan</button>
                    </div>
                </form>
                
                <!-- Debug info - Show submission errors -->
                @if ($errors->any())
                <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong>Form submission errors:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </main>
    </div>
</div>

@include('saasAdmin.layout.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hide resource limit features (max_students, max_teachers, max_staff) that are already in the main form
        const resourceLimitCodes = ['max_students', 'max_teachers', 'max_staff'];
        document.querySelectorAll('.feature-row').forEach(row => {
            const codeElement = row.querySelector('code');
            if (codeElement && resourceLimitCodes.includes(codeElement.textContent.trim())) {
                row.style.display = 'none';
            }
        });
        
        // Toggle feature groups
        const groupHeaders = document.querySelectorAll('.group-header');
        groupHeaders.forEach(header => {
            header.addEventListener('click', function(e) {
                // Don't toggle if clicking the select button
                if (e.target.classList.contains('select-section') || e.target.closest('.select-section')) {
                    return;
                }
                
                const featureContainer = this.nextElementSibling;
                const toggleIcon = this.querySelector('.toggle-icon');
                
                if (featureContainer.style.display === 'none') {
                    featureContainer.style.display = 'block';
                    toggleIcon.classList.remove('transform', 'rotate-180');
                } else {
                    featureContainer.style.display = 'none';
                    toggleIcon.classList.add('transform', 'rotate-180');
                }
            });
        });
        
        // Select all features
        document.getElementById('selectAll').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.feature-checkbox:not([data-resource-limit="true"])');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
        });
        
        // Deselect all features
        document.getElementById('deselectAll').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.feature-checkbox:not([data-resource-limit="true"])');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        });
        
        // Select features by group
        const groupButtons = document.querySelectorAll('.select-group');
        groupButtons.forEach(button => {
            button.addEventListener('click', function() {
                const group = this.dataset.group;
                const checkboxes = document.querySelectorAll(`.feature-checkbox[data-group="${group}"]:not([data-resource-limit="true"])`);
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
            });
        });
        
        // Select all features in a section
        const sectionSelectors = document.querySelectorAll('.select-section');
        sectionSelectors.forEach(selector => {
            selector.addEventListener('click', function(e) {
                e.stopPropagation();
                
                const section = this.closest('.feature-group-section');
                const checkboxes = section.querySelectorAll('.feature-checkbox:not([data-resource-limit="true"])');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
            });
        });
        
        // Toggle feature value inputs when checkboxes change
        const featureCheckboxes = document.querySelectorAll('.feature-checkbox');
        featureCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const featureId = this.id.replace('feature_', '');
                const valueInput = document.getElementById(`feature_value_${featureId}`);
                
                if (valueInput) {
                    valueInput.disabled = !this.checked;
                    if (!this.checked) {
                        valueInput.value = '';
                    }
                }
            });
            
            // Initialize on page load
            if (checkbox.checked) {
                const featureId = checkbox.id.replace('feature_', '');
                const valueInput = document.getElementById(`feature_value_${featureId}`);
                if (valueInput) {
                    valueInput.disabled = false;
                }
            } else {
                const featureId = checkbox.id.replace('feature_', '');
                const valueInput = document.getElementById(`feature_value_${featureId}`);
                if (valueInput) {
                    valueInput.disabled = true;
                }
            }
        });

        // Debug button to check form values without submission
        document.getElementById('debugFormBtn').addEventListener('click', function() {
            const formData = new FormData(document.querySelector('form'));
            let formValues = {};
            
            for (let [key, value] of formData.entries()) {
                formValues[key] = value;
            }
            
            // Create features list
            formValues.features = [];
            document.querySelectorAll('input[name="features[]"]:checked').forEach(function(checkbox) {
                formValues.features.push(checkbox.value);
            });
            
            // Create feature values object
            formValues.feature_values = {};
            document.querySelectorAll('.feature-value:not([disabled])').forEach(function(input) {
                const featureId = input.id.replace('feature_value_', '');
                formValues.feature_values[featureId] = input.value;
            });
            
            console.log('Form values:', formValues);
            
            // Show alert with basic form data
            alert('Form data ready for submission:\n' + 
                'Name: ' + formValues.name + '\n' +
                'Price: ' + formValues.price + '\n' + 
                'Features selected: ' + formValues.features.length);
        });
    });
</script> 