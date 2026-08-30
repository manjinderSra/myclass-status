@extends('client.teacher.layout.master')

@section('title', 'Attendance Report')

@section('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- Print styles --}}
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #reportContainer, #reportContainer * {
            visibility: visible;
        }
        #reportContainer {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        #printReport {
            display: none;
        }
    }
</style>
@endsection

@section('content')
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">
            Academics / <span class="text-l text-gray-500">Attendance Report</span>
        </h1>
    </div>

    {{-- Header Section for Attendance Report --}}
    <div class="bg-white rounded-lg shadow w-full p-6 transition-all duration-300">
        <div class="flex items-center justify-between mb-3">
            <div class="text-xl font-semibold text-gray-800">
                Attendance Reports
                <p class="text-sm text-gray-500 mt-1">View and analyze student attendance records</p>
            </div>

            {{-- Right section: Back to Attendance Button --}}
            <div class="flex items-center space-x-3">
                <a href="{{ route('teacher.attendance') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Take Attendance
                </a>
            </div>
        </div>
    </div>
    {{-- End Header Section --}}

    {{-- Report Filter Form --}}
    <div class="bg-white rounded-lg shadow w-full p-6 mt-6">
        <h2 class="text-lg font-semibold mb-4">Generate Attendance Report</h2>
        
        <form id="reportFilterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                <select id="class" name="class" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                    <option value="">Select Class</option>
                    @foreach($teachingAssignments as $assignment)
                        <option value="{{ $assignment['class_name'] }}">{{ $assignment['class_name'] }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="section" class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                <select id="section" name="section" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required disabled>
                    <option value="">Select Class First</option>
                </select>
            </div>
            
            <div>
                <label for="from_date" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input type="date" id="from_date" name="from_date" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
            </div>
            
            <div>
                <label for="to_date" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input type="date" id="to_date" name="to_date" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
            </div>
            
            <div class="md:col-span-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Generate Report</button>
            </div>
        </form>
    </div>
    
    {{-- Report Results (Initially Hidden) --}}
    <div id="reportContainer" class="bg-white rounded-lg shadow w-full p-6 mt-6 hidden">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Attendance Report</h2>
            <div>
                <span id="classInfo" class="text-sm font-medium text-gray-700 mr-2"></span>
                <span id="dateRangeInfo" class="text-sm font-medium text-gray-700"></span>
            </div>
        </div>
        
        <div class="mb-4 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                <span id="totalStudentsInfo"></span> |
                <span id="dateRangeInfo2"></span>
            </div>
            <button id="printReport" class="bg-gray-100 text-gray-800 px-3 py-1 rounded text-sm font-medium hover:bg-gray-200 flex items-center">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Report
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roll No</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leave</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">%</th>
                        <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                    </tr>
                </thead>
                <tbody id="reportTableBody" class="bg-white divide-y divide-gray-200">
                    {{-- Report data will be populated here via JavaScript --}}
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Student Attendance Details Modal (Initially Hidden) --}}
    <div id="studentDetailsModal" class="hidden fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-black bg-opacity-50 absolute inset-0"></div>
        <div class="bg-white p-6 rounded-lg shadow-xl relative z-10 max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium" id="modalStudentName"></h3>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-600" id="modalStudentInfo"></p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="modalTableBody" class="bg-white divide-y divide-gray-200">
                        {{-- Student attendance details will be populated here --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set up CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Store teaching assignments data
        const teachingAssignments = @json($teachingAssignments);
        
        // Create a map for quick lookup of sections by class
        const classSectionsMap = new Map();
        teachingAssignments.forEach(assignment => {
            classSectionsMap.set(assignment.class_name, assignment.sections);
        });
        
        // Set default dates (current month)
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
        const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        
        document.getElementById('from_date').value = formatDate(firstDay);
        document.getElementById('to_date').value = formatDate(today);
        
        // Function to format date as YYYY-MM-DD
        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
        
        // Function to update sections dropdown based on selected class
        function updateSections() {
            const selectedClass = document.getElementById('class').value;
            const sectionSelect = document.getElementById('section');
            
            // Clear and disable section dropdown if no class is selected
            if (!selectedClass) {
                sectionSelect.innerHTML = '<option value="">Select Class First</option>';
                sectionSelect.disabled = true;
                return;
            }
            
            // Get sections for the selected class
            const sections = classSectionsMap.get(selectedClass) || [];
            
            // Update sections dropdown
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            sections.forEach(section => {
                const option = document.createElement('option');
                option.value = section.id;
                option.textContent = section.name;
                sectionSelect.appendChild(option);
            });
            
            // Enable section dropdown
            sectionSelect.disabled = false;
        }
        
        // Add event listener to class dropdown
        document.getElementById('class').addEventListener('change', updateSections);
        
        // Handle form submission to generate report
        document.getElementById('reportFilterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const className = document.getElementById('class').value;
            const sectionId = document.getElementById('section').value;
            const fromDate = document.getElementById('from_date').value;
            const toDate = document.getElementById('to_date').value;
            
            if (!className || !sectionId || !fromDate || !toDate) {
                alert('Please select class, section, and date range');
                return;
            }
            
            // Show loading state
            document.getElementById('reportTableBody').innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center">Loading report data...</td></tr>';
            document.getElementById('reportContainer').classList.remove('hidden');
            
            // Update info text
            const sectionName = document.querySelector(`#section option[value="${sectionId}"]`).textContent;
            document.getElementById('classInfo').textContent = `${className} - ${sectionName}`;
            document.getElementById('dateRangeInfo').textContent = `Period: ${fromDate} to ${toDate}`;
            document.getElementById('dateRangeInfo2').textContent = `Period: ${fromDate} to ${toDate}`;
            
            // Fetch report data
            fetch(`/teacher/attendance/report-data?class_name=${encodeURIComponent(className)}&section_id=${sectionId}&from_date=${fromDate}&to_date=${toDate}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear table body
                    const tableBody = document.getElementById('reportTableBody');
                    tableBody.innerHTML = '';
                    
                    if (data.report_data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center">No attendance data found for the selected criteria</td></tr>';
                        return;
                    }
                    
                    // Update total students info
                    document.getElementById('totalStudentsInfo').textContent = `Total Students: ${data.report_data.length}`;
                    
                    // Populate table with report data
                    data.report_data.forEach((student, index) => {
                        const row = document.createElement('tr');
                        row.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                        
                        // Calculate attendance percentage color
                        let percentageColor = 'text-green-600';
                        if (student.statistics.attendance_percentage < 75) {
                            percentageColor = 'text-red-600';
                        } else if (student.statistics.attendance_percentage < 90) {
                            percentageColor = 'text-yellow-600';
                        }
                        
                        row.innerHTML = `
                            <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ${student.roll_number || 'N/A'}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${student.name}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${student.statistics.present}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${student.statistics.absent}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${student.statistics.late}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${student.statistics.leave}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm font-medium ${percentageColor}">
                                ${student.statistics.attendance_percentage}%
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button class="view-details bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs hover:bg-blue-200" 
                                        data-student-id="${student.id}" 
                                        data-student-name="${student.name}"
                                        data-student-roll="${student.roll_number || 'N/A'}"
                                        data-attendance='${JSON.stringify(student.attendance)}'>
                                    View Details
                                </button>
                            </td>
                        `;
                        
                        tableBody.appendChild(row);
                    });
                    
                    // Add event listeners to view details buttons
                    document.querySelectorAll('.view-details').forEach(button => {
                        button.addEventListener('click', function() {
                            showStudentDetails(this);
                        });
                    });
                    
                } else {
                    document.getElementById('reportTableBody').innerHTML = `
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-red-500">
                                ${data.message || 'An error occurred while fetching report data'}
                            </td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('reportTableBody').innerHTML = `
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-red-500">
                            An error occurred while fetching report data
                        </td>
                    </tr>
                `;
            });
        });
        
        // Function to show student attendance details
        function showStudentDetails(button) {
            const studentId = button.getAttribute('data-student-id');
            const studentName = button.getAttribute('data-student-name');
            const studentRoll = button.getAttribute('data-student-roll');
            const attendance = JSON.parse(button.getAttribute('data-attendance'));
            
            // Update modal title and info
            document.getElementById('modalStudentName').textContent = studentName;
            document.getElementById('modalStudentInfo').textContent = `Roll No: ${studentRoll} | ID: ${studentId}`;
            
            // Clear modal table body
            const modalTableBody = document.getElementById('modalTableBody');
            modalTableBody.innerHTML = '';
            
            // Get dates and sort them
            const dates = Object.keys(attendance).sort();
            
            // Populate modal table with attendance details
            dates.forEach(date => {
                const status = attendance[date];
                const row = document.createElement('tr');
                
                // Determine status color and label
                let statusColor = 'bg-gray-100 text-gray-800';
                if (status === 'present') {
                    statusColor = 'bg-green-100 text-green-800';
                } else if (status === 'absent') {
                    statusColor = 'bg-red-100 text-red-800';
                } else if (status === 'late') {
                    statusColor = 'bg-yellow-100 text-yellow-800';
                } else if (status === 'leave') {
                    statusColor = 'bg-blue-100 text-blue-800';
                }
                
                row.innerHTML = `
                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${date}
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm">
                        <span class="${statusColor} px-2 py-1 rounded-full text-xs font-medium">
                            ${status === 'N/A' ? 'Not Marked' : status.charAt(0).toUpperCase() + status.slice(1)}
                        </span>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                        -
                    </td>
                `;
                
                modalTableBody.appendChild(row);
            });
            
            // Show the modal
            document.getElementById('studentDetailsModal').classList.remove('hidden');
        }
        
        // Handle close modal button
        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('studentDetailsModal').classList.add('hidden');
        });
        
        // Close modal when clicking outside
        document.getElementById('studentDetailsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
        
        // Handle print report button
        document.getElementById('printReport').addEventListener('click', function() {
            window.print();
        });
    });
</script>
@endsection
