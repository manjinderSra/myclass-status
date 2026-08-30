@extends('client.teacher.layout.master')

@section('title', 'Teacher Attendance')

@section('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-semibold mb-6 text-gray-800">
            Academics / <span class="text-l text-gray-500">Attendance</span>
        </h1>
    </div>

    {{-- Header Section for Attendance --}}
    <div class="bg-white rounded-lg shadow w-full p-6 transition-all duration-300">
        <div class="flex items-center justify-between mb-3">
            <div class="text-xl font-semibold text-gray-800">
                Attendance Management
                <p class="text-sm text-gray-500 mt-1">Mark and manage student attendance</p>
            </div>

            {{-- Right section: View Report Button --}}
            <div class="flex items-center space-x-3">
                <a href="{{ route('teacher.attendance.report') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    View Reports
                </a>
            </div>
        </div>
    </div>
    {{-- End Header Section --}}

    {{-- Attendance Form --}}
    <div class="bg-white rounded-lg shadow w-full p-6 mt-6">
        <h2 class="text-lg font-semibold mb-4">Mark Attendance</h2>
        
        <form id="attendanceSearchForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" id="date" name="date" value="{{ $today }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
            </div>
            
            <div class="md:col-span-3">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Get Students</button>
            </div>
        </form>
    </div>
    
    {{-- Students List for Attendance (Initially Hidden) --}}
    <div id="attendanceContainer" class="bg-white rounded-lg shadow w-full p-6 mt-6 hidden">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Student Attendance</h2>
            <div>
                <span id="classInfo" class="text-sm font-medium text-gray-700 mr-2"></span>
                <span id="dateInfo" class="text-sm font-medium text-gray-700"></span>
            </div>
        </div>
        
        <div class="mb-4 flex items-center space-x-4">
            <button id="markAllPresent" class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium hover:bg-green-200">Mark All Present</button>
            <button id="markAllAbsent" class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium hover:bg-red-200">Mark All Absent</button>
        </div>
        
        <form id="attendanceForm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roll No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody" class="bg-white divide-y divide-gray-200">
                        {{-- Students will be populated here via JavaScript --}}
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Save Attendance</button>
            </div>
        </form>
    </div>
    
    {{-- Success Message (Initially Hidden) --}}
    <div id="successMessage" class="hidden fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-black bg-opacity-50 absolute inset-0"></div>
        <div class="bg-white p-6 rounded-lg shadow-xl relative z-10 max-w-md w-full">
            <div class="flex items-center justify-center mb-4">
                <svg class="h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-center mb-2">Success!</h3>
            <p class="text-center text-gray-600 mb-4">Attendance has been saved successfully.</p>
            <div class="flex justify-center">
                <button id="closeSuccessMessage" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">OK</button>
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
        
        // Handle form submission to get students
        document.getElementById('attendanceSearchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const className = document.getElementById('class').value;
            const sectionId = document.getElementById('section').value;
            const date = document.getElementById('date').value;
            
            if (!className || !sectionId || !date) {
                alert('Please select class, section, and date');
                return;
            }
            
            // Show loading state
            document.getElementById('studentsTableBody').innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center">Loading students...</td></tr>';
            document.getElementById('attendanceContainer').classList.remove('hidden');
            
            // Update info text
            const sectionName = document.querySelector(`#section option[value="${sectionId}"]`).textContent;
            document.getElementById('classInfo').textContent = `${className} - ${sectionName}`;
            document.getElementById('dateInfo').textContent = `Date: ${date}`;
            
            // Fetch students for the selected class, section, and date
            fetch(`/teacher/attendance/get-students?class_name=${encodeURIComponent(className)}&section_id=${sectionId}&date=${date}`, {
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
                    const tableBody = document.getElementById('studentsTableBody');
                    tableBody.innerHTML = '';
                    
                    if (data.students.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center">No students found in this class and section</td></tr>';
                        return;
                    }
                    
                    // Populate table with students
                    data.students.forEach((student, index) => {
                        const row = document.createElement('tr');
                        row.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                        
                        // Determine status and remarks from existing attendance if any
                        const status = student.attendance ? student.attendance.status : 'present';
                        const remarks = student.attendance ? student.attendance.remarks : '';
                        
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ${student.roll_number || 'N/A'}
                                <input type="hidden" name="attendance[${index}][student_id]" value="${student.id}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${student.name}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="attendance[${index}][status]" value="present" class="form-radio h-4 w-4 text-blue-600" ${status === 'present' ? 'checked' : ''}>
                                        <span class="ml-2 text-sm text-gray-700">Present</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="attendance[${index}][status]" value="absent" class="form-radio h-4 w-4 text-red-600" ${status === 'absent' ? 'checked' : ''}>
                                        <span class="ml-2 text-sm text-gray-700">Absent</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="attendance[${index}][status]" value="late" class="form-radio h-4 w-4 text-yellow-600" ${status === 'late' ? 'checked' : ''}>
                                        <span class="ml-2 text-sm text-gray-700">Late</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="attendance[${index}][status]" value="leave" class="form-radio h-4 w-4 text-green-600" ${status === 'leave' ? 'checked' : ''}>
                                        <span class="ml-2 text-sm text-gray-700">Leave</span>
                                    </label>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="text" name="attendance[${index}][remarks]" value="${remarks}" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 w-full" placeholder="Optional remarks">
                            </td>
                        `;
                        
                        tableBody.appendChild(row);
                    });
                    
                    // Show warning if attendance already exists
                    if (data.has_existing_attendance) {
                        const warningRow = document.createElement('tr');
                        warningRow.innerHTML = `
                            <td colspan="4" class="px-6 py-4">
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                Attendance for this date already exists. Saving will update the existing records.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        `;
                        tableBody.insertBefore(warningRow, tableBody.firstChild);
                    }
                } else {
                    document.getElementById('studentsTableBody').innerHTML = `
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-red-500">
                                ${data.message || 'An error occurred while fetching students'}
                            </td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('studentsTableBody').innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-red-500">
                            An error occurred while fetching students
                        </td>
                    </tr>
                `;
            });
        });
        
        // Handle "Mark All Present" button
        document.getElementById('markAllPresent').addEventListener('click', function() {
            document.querySelectorAll('input[value="present"]').forEach(radio => {
                radio.checked = true;
            });
        });
        
        // Handle "Mark All Absent" button
        document.getElementById('markAllAbsent').addEventListener('click', function() {
            document.querySelectorAll('input[value="absent"]').forEach(radio => {
                radio.checked = true;
            });
        });
        
        // Handle attendance form submission
        document.getElementById('attendanceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const className = document.getElementById('class').value;
            const sectionId = document.getElementById('section').value;
            const date = document.getElementById('date').value;
            
            // Collect attendance data
            const formData = new FormData(this);
            formData.append('class_name', className);
            formData.append('section_id', sectionId);
            formData.append('date', date);
            
            // Convert FormData to JSON
            const attendanceData = {};
            for (const [key, value] of formData.entries()) {
                const keys = key.match(/\w+/g);
                let obj = attendanceData;
                
                for (let i = 0; i < keys.length - 1; i++) {
                    const currentKey = keys[i];
                    const nextKey = keys[i + 1];
                    const nextIndex = parseInt(nextKey);
                    
                    if (!isNaN(nextIndex)) {
                        if (!obj[currentKey]) obj[currentKey] = [];
                        if (!obj[currentKey][nextIndex]) obj[currentKey][nextIndex] = {};
                        obj = obj[currentKey][nextIndex];
                        i++;
                    } else {
                        if (!obj[currentKey]) obj[currentKey] = {};
                        obj = obj[currentKey];
                    }
                }
                
                obj[keys[keys.length - 1]] = value;
            }
            
            // Submit attendance data
            fetch('/teacher/attendance/save', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(attendanceData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    document.getElementById('successMessage').classList.remove('hidden');
                } else {
                    alert(data.message || 'An error occurred while saving attendance');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving attendance');
            });
        });
        
        // Handle close success message
        document.getElementById('closeSuccessMessage').addEventListener('click', function() {
            document.getElementById('successMessage').classList.add('hidden');
        });
    });
</script>
@endsection 