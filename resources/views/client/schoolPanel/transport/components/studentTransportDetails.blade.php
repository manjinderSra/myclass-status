{{-- Student Transport Details Component --}}
<div class="student-transport-details bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4 text-gray-800">Student Transport Details</h2>
    
    <div class="mb-6">
        <div class="flex flex-col md:flex-row mb-4">
            <div class="w-full md:w-1/2 md:pr-2 mb-4 md:mb-0">
                <div class="relative">
                    <input type="text" id="studentAdmissionNumber" placeholder="Enter Admission Number" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="w-full md:w-1/2 md:pl-2 flex">
                <button id="fetchTransportBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Fetch Details
                </button>
            </div>
        </div>
    </div>
    
    {{-- Loading indicator --}}
    <div id="loadingIndicator" class="hidden flex justify-center items-center my-8">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-700"></div>
    </div>
    
    {{-- Error message --}}
    <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span id="errorMessageText" class="block sm:inline"></span>
    </div>
    
    {{-- Transport details container --}}
    <div id="transportDetails" class="hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Student Information --}}
            <div class="border rounded-lg p-4 bg-gray-50">
                <h3 class="text-md font-semibold mb-3 text-gray-700 border-b pb-2">Student Information</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Name:</span> <span id="studentName" class="ml-1"></span></p>
                    <p><span class="font-medium">Admission No:</span> <span id="admissionNumber" class="ml-1"></span></p>
                    <p><span class="font-medium">Student ID:</span> <span id="studentId" class="ml-1"></span></p>
                </div>
            </div>
            
            {{-- Pickup Point Information --}}
            <div class="border rounded-lg p-4 bg-gray-50">
                <h3 class="text-md font-semibold mb-3 text-gray-700 border-b pb-2">Pickup Point Information</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Name:</span> <span id="pickupPointName" class="ml-1"></span></p>
                    <p><span class="font-medium">Address:</span> <span id="pickupPointAddress" class="ml-1"></span></p>
                    <p><span class="font-medium">Pickup Time:</span> <span id="pickupTime" class="ml-1"></span></p>
                    <p><span class="font-medium">Drop Time:</span> <span id="dropTime" class="ml-1"></span></p>
                </div>
            </div>
            
            {{-- Route Information --}}
            <div class="border rounded-lg p-4 bg-gray-50">
                <h3 class="text-md font-semibold mb-3 text-gray-700 border-b pb-2">Route Information</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Route Name:</span> <span id="routeName" class="ml-1"></span></p>
                    <p><span class="font-medium">Description:</span> <span id="routeDescription" class="ml-1"></span></p>
                </div>
            </div>
            
            {{-- Vehicle Information --}}
            <div class="border rounded-lg p-4 bg-gray-50">
                <h3 class="text-md font-semibold mb-3 text-gray-700 border-b pb-2">Vehicle Information</h3>
                <div class="space-y-2">
                    <p><span class="font-medium">Vehicle Number:</span> <span id="vehicleNumber" class="ml-1"></span></p>
                    <p><span class="font-medium">Model:</span> <span id="vehicleModel" class="ml-1"></span></p>
                    <p><span class="font-medium">Seating Capacity:</span> <span id="vehicleCapacity" class="ml-1"></span></p>
                </div>
            </div>
            
            {{-- Driver Information --}}
            <div class="border rounded-lg p-4 bg-blue-50 col-span-1 md:col-span-2">
                <h3 class="text-md font-semibold mb-3 text-gray-700 border-b pb-2">Driver Information</h3>
                <div class="flex flex-wrap">
                    <div class="w-full md:w-1/3 space-y-2 mb-4 md:mb-0">
                        <p><span class="font-medium">Name:</span> <span id="driverName" class="ml-1"></span></p>
                    </div>
                    <div class="w-full md:w-1/3 space-y-2 mb-4 md:mb-0">
                        <p><span class="font-medium">Contact:</span> <span id="driverContact" class="ml-1"></span></p>
                    </div>
                    <div class="w-full md:w-1/3 space-y-2">
                        <p><span class="font-medium">License:</span> <span id="driverLicense" class="ml-1"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fetchTransportBtn = document.getElementById('fetchTransportBtn');
    const studentAdmissionNumber = document.getElementById('studentAdmissionNumber');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const errorMessage = document.getElementById('errorMessage');
    const errorMessageText = document.getElementById('errorMessageText');
    const transportDetails = document.getElementById('transportDetails');
    
    fetchTransportBtn.addEventListener('click', fetchTransportDetails);
    studentAdmissionNumber.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            fetchTransportDetails();
        }
    });
    
    function fetchTransportDetails() {
        const admissionNumber = studentAdmissionNumber.value.trim();
        
        if (!admissionNumber) {
            showError('Please enter a valid admission number');
            return;
        }
        
        // Show loading indicator and hide any previous data/errors
        loadingIndicator.classList.remove('hidden');
        errorMessage.classList.add('hidden');
        transportDetails.classList.add('hidden');
        
        // Fetch transport details from API
        fetch(`{{ route('school.api.student-transport') }}?admission_number=${admissionNumber}`)
            .then(response => response.json())
            .then(data => {
                loadingIndicator.classList.add('hidden');
                
                if (!data.success) {
                    showError(data.message || 'Failed to fetch transport details');
                    return;
                }
                
                // Display the transport details
                displayTransportDetails(data.transport_details);
                transportDetails.classList.remove('hidden');
            })
            .catch(error => {
                loadingIndicator.classList.add('hidden');
                showError('An error occurred while fetching transport details');
                console.error('Error:', error);
            });
    }
    
    function showError(message) {
        errorMessageText.textContent = message;
        errorMessage.classList.remove('hidden');
    }
    
    function displayTransportDetails(details) {
        // Student details
        document.getElementById('studentName').textContent = details.student.name;
        document.getElementById('admissionNumber').textContent = details.student.admission_number;
        document.getElementById('studentId').textContent = details.student.student_id;
        
        // Pickup point details
        document.getElementById('pickupPointName').textContent = details.pickup_point.name;
        document.getElementById('pickupPointAddress').textContent = details.pickup_point.address;
        document.getElementById('pickupTime').textContent = details.pickup_point.pickup_time;
        document.getElementById('dropTime').textContent = details.pickup_point.drop_time;
        
        // Route details
        document.getElementById('routeName').textContent = details.route.name;
        document.getElementById('routeDescription').textContent = details.route.description || 'N/A';
        
        // Vehicle details
        if (details.vehicle) {
            document.getElementById('vehicleNumber').textContent = details.vehicle.number;
            document.getElementById('vehicleModel').textContent = details.vehicle.model || 'N/A';
            document.getElementById('vehicleCapacity').textContent = details.vehicle.capacity || 'N/A';
        } else {
            document.getElementById('vehicleNumber').textContent = 'N/A';
            document.getElementById('vehicleModel').textContent = 'N/A';
            document.getElementById('vehicleCapacity').textContent = 'N/A';
        }
        
        // Driver details
        if (details.driver) {
            document.getElementById('driverName').textContent = details.driver.name;
            document.getElementById('driverContact').textContent = details.driver.contact;
            document.getElementById('driverLicense').textContent = details.driver.license_number || 'N/A';
        } else {
            document.getElementById('driverName').textContent = 'N/A';
            document.getElementById('driverContact').textContent = 'N/A';
            document.getElementById('driverLicense').textContent = 'N/A';
        }
    }
});
</script> 