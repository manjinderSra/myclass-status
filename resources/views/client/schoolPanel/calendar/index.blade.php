@include('client.schoolPanel.layout.header')
@include('client.schoolPanel.layout.topbar')

<div class="flex">
    @include('client.schoolPanel.layout.sidebar')

    <div class="flex-1 h-screen overflow-y-auto px-6 py-24 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4 md:mb-0 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                School Calendar
            </h1>
            <div class="flex flex-wrap gap-3">
                <button id="addHolidayBtn" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-5 py-2.5 rounded-lg hover:from-red-600 hover:to-red-700 transition-all flex items-center shadow-md hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    Add Holiday
                </button>
            </div>
        </div>

        <!-- Calendar Filters -->
        <div class="bg-white p-6 rounded-xl shadow-sm mb-8 border border-gray-200">
            <h2 class="text-lg font-semibold mb-5 text-gray-900 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                Calendar Filters
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                <label class="flex items-center p-3.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all cursor-pointer hover:border-blue-400">
                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded" id="filter-events" checked>
                    <span class="ml-2.5 text-gray-800 font-medium">Events</span>
                </label>
                <label class="flex items-center p-3.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all cursor-pointer hover:border-purple-400">
                    <input type="checkbox" class="form-checkbox h-5 w-5 text-purple-600 rounded" id="filter-programs" checked>
                    <span class="ml-2.5 text-gray-800 font-medium">Programs</span>
                </label>
                <label class="flex items-center p-3.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all cursor-pointer hover:border-purple-400">
                    <input type="checkbox" class="form-checkbox h-5 w-5 text-purple-600 rounded" id="filter-student-birthdays" checked>
                    <span class="ml-2.5 text-gray-800 font-medium">Student Birthdays</span>
                </label>
                <label class="flex items-center p-3.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all cursor-pointer hover:border-blue-400">
                    <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded" id="filter-teacher-birthdays" checked>
                    <span class="ml-2.5 text-gray-800 font-medium">Teacher Birthdays</span>
                </label>
                <label class="flex items-center p-3.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all cursor-pointer hover:border-red-400">
                    <input type="checkbox" class="form-checkbox h-5 w-5 text-red-600 rounded" id="filter-holidays" checked>
                    <span class="ml-2.5 text-gray-800 font-medium">Holidays</span>
                </label>
                <label class="flex items-center p-3.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all cursor-pointer hover:border-red-400">
                    <input type="checkbox" class="form-checkbox h-5 w-5 text-red-600 rounded" id="filter-exams" checked>
                    <span class="ml-2.5 text-gray-800 font-medium">Exam</span>
                </label>
            </div>
        </div>

        <!-- Calendar Container -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div id="calendar" class="min-h-[700px]"></div>
        </div>
        
        <!-- Debug information -->
        {{-- @if(config('app.debug'))
        <div class="mt-8 bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold mb-3 text-gray-900">Debug Information</h3>
            <div class="text-xs font-mono bg-gray-100 p-4 rounded-lg overflow-auto max-h-64">
                <p class="mb-2"><strong>Calendar Items:</strong> {{ count($calendarItems) }}</p>
                <pre>{{ json_encode($calendarItems, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        @endif --}}
    </div>
</div>

<!-- Holiday Modal -->
<div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50" id="holidayModal">
    <div class="relative top-20 mx-auto p-6 border w-full max-w-md shadow-xl rounded-xl bg-white">
        <div class="mt-3">
            <h3 class="text-xl leading-6 font-bold text-gray-900 mb-5" id="modalTitle">Add Holiday</h3>
            <form id="holidayForm" class="mt-4">
                <div class="mb-5">
                    <label for="holidayTitle" class="block text-sm font-medium text-gray-800 mb-2">Holiday Title</label>
                    <input type="text" id="holidayTitle" name="title" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                </div>
                <div class="mb-5">
                    <label for="holidayDate" class="block text-sm font-medium text-gray-800 mb-2">Date</label>
                    <input type="date" id="holidayDate" name="date" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                </div>
                <div class="mb-5">
                    <label for="holidayDescription" class="block text-sm font-medium text-gray-800 mb-2">Description</label>
                    <textarea id="holidayDescription" name="description" rows="3" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"></textarea>
                </div>
                <input type="hidden" id="holidayId" name="id">
                
                <div class="flex justify-between mt-6">
                    <div class="flex space-x-3" id="editDeleteBtns">
                        <button type="button" id="deleteHolidayBtn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg shadow transition-all">Delete</button>
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" id="closeModal" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2.5 rounded-lg shadow transition-all">Cancel</button>
                        <button type="submit" id="saveHolidayBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg shadow transition-all">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Event/Birthday/Program Detail Modal -->
<div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50" id="detailModal">
    <div class="relative top-20 mx-auto p-6 border w-full max-w-md shadow-xl rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl leading-6 font-bold text-gray-900" id="detailTitle"></h3>
                <button type="button" class="close-detail-modal text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="detailContent" class="text-left"></div>
            <div class="mt-6 flex justify-end">
                <button type="button" class="close-detail-modal bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2.5 rounded-lg shadow transition-all">Close</button>
            </div>
        </div>
    </div>
</div>

@include('client.schoolPanel.layout.footer')

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize calendar data from PHP
    const calendarItems = @json($calendarItems);
    
    // Initialize calendar
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        events: calendarItems,
        eventClick: function(info) {
            const eventType = info.event.extendedProps.type;

            if (eventType === 'holiday') {
                showHolidayModal(info.event);
            } else {
                showDetailModal(info.event);
            }
        },
        eventDidMount: function(info) {
            const eventType = info.event.extendedProps.type;
            const subType = info.event.extendedProps.subtype;

            // Add data attributes for filtering
            if (eventType === 'birthday') {
                info.el.setAttribute('data-type', 'birthday');
                info.el.setAttribute('data-subtype', subType);
            } else if (eventType === 'holiday') {
                info.el.setAttribute('data-type', 'holiday');
            } else if (eventType === 'program') {
                info.el.setAttribute('data-type', 'program');
            } else if (eventType === 'exams') {
                info.el.setAttribute('data-type', 'exams');
            } else {
                info.el.setAttribute('data-type', 'event');
                info.el.setAttribute('data-status', info.event.extendedProps.status);
            }

            // Add tooltip with event title
            info.el.setAttribute('title', info.event.title);
        },
        dayMaxEvents: true,
        themeSystem: 'standard',
        height: 'auto'
    });

    calendar.render();

    // Filter functionality
    document.getElementById('filter-events').addEventListener('change', function(e) {
        filterCalendarEvents('event', e.target.checked);
    });

    document.getElementById('filter-programs').addEventListener('change', function(e) {
        filterCalendarEvents('program', e.target.checked);
    });

    document.getElementById('filter-student-birthdays').addEventListener('change', function(e) {
        filterCalendarEvents('birthday', e.target.checked, 'student');
    });

    document.getElementById('filter-teacher-birthdays').addEventListener('change', function(e) {
        filterCalendarEvents('birthday', e.target.checked, 'teacher');
    });

    document.getElementById('filter-holidays').addEventListener('change', function(e) {
        filterCalendarEvents('holiday', e.target.checked);
    });

    document.getElementById('filter-exams').addEventListener('change', function(e) {
        filterCalendarEvents('exams', e.target.checked);
    });

    function filterCalendarEvents(type, show, subType = null) {
        const elements = document.querySelectorAll(`[data-type="${type}"]`);

        elements.forEach(function(el) {
            if (subType) {
                if (el.getAttribute('data-subtype') === subType) {
                    el.style.display = show ? '' : 'none';
                }
            } else {
                el.style.display = show ? '' : 'none';
            }
        });
    }

    // Modal functionality
    const holidayModal = document.getElementById('holidayModal');
    const detailModal = document.getElementById('detailModal');

    // Add Holiday button
    document.getElementById('addHolidayBtn').addEventListener('click', function() {
        document.getElementById('modalTitle').textContent = 'Add Holiday';
        document.getElementById('holidayForm').reset();
        document.getElementById('holidayId').value = '';
        document.getElementById('editDeleteBtns').style.display = 'none';
        holidayModal.classList.remove('hidden');
    });

    // Close modals
    document.getElementById('closeModal').addEventListener('click', function() {
        holidayModal.classList.add('hidden');
    });

    document.querySelectorAll('.close-detail-modal').forEach(function(btn) {
        btn.addEventListener('click', function() {
            detailModal.classList.add('hidden');
        });
    });

    // Holiday form submission
    document.getElementById('holidayForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = {
            title: document.getElementById('holidayTitle').value,
            date: document.getElementById('holidayDate').value,
            description: document.getElementById('holidayDescription').value
        };

        const holidayId = document.getElementById('holidayId').value;
        let url = "{{ route('school.calendar.holidays.store') }}";
        let method = 'POST';

        if (holidayId) {
            url = "{{ route('school.calendar.holidays.update', '') }}/" + holidayId;
            method = 'PUT';
        }

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (holidayId) {
                    const existingEvent = calendar.getEventById('holiday_' + holidayId);
                    if (existingEvent) {
                        existingEvent.setProp('title', '📅 ' + formData.title);
                        existingEvent.setStart(formData.date);
                        existingEvent.setExtendedProp('description', formData.description);
                    }
                } else {
                    calendar.addEvent(data.holiday);
                }

                holidayModal.classList.add('hidden');
                showNotification(data.message, 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'error');
        });
    });

    // Delete holiday
    document.getElementById('deleteHolidayBtn').addEventListener('click', function() {
        const holidayId = document.getElementById('holidayId').value;
        if (!holidayId) return;

        if (confirm('Are you sure you want to delete this holiday?')) {
            fetch("{{ route('school.calendar.holidays.delete', '') }}/" + holidayId, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const existingEvent = calendar.getEventById('holiday_' + holidayId);
                    if (existingEvent) existingEvent.remove();
                    holidayModal.classList.add('hidden');
                    showNotification(data.message, 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred. Please try again.', 'error');
            });
        }
    });

    // Show holiday edit modal
    function showHolidayModal(event) {
        const id = event.id.replace('holiday_', '');
        document.getElementById('modalTitle').textContent = 'Edit Holiday';
        document.getElementById('holidayTitle').value = event.title.replace('📅 ', '');
        document.getElementById('holidayDate').value = event.startStr;
        document.getElementById('holidayDescription').value = event.extendedProps.description || '';
        document.getElementById('holidayId').value = id;
        document.getElementById('editDeleteBtns').style.display = 'block';
        holidayModal.classList.remove('hidden');
    }

    // 🧾 Show detail modal for events, programs, birthdays, holidays & exams
    function showDetailModal(event) {
        const eventType = event.extendedProps.type;
        let content = '';
        document.getElementById('detailTitle').textContent = event.title;

        if (eventType === 'event') {
            content = `
                <div class="bg-blue-50 p-4 rounded-lg mb-4 border border-blue-100">
                    <p class="mb-2"><strong class="text-gray-800">Date:</strong> <span class="text-gray-700">${new Date(event.startStr).toLocaleDateString()}</span></p>
                    <p class="mb-2"><strong class="text-gray-800">Status:</strong> <span class="capitalize px-2.5 py-1 rounded-full text-xs font-bold ${getStatusBadgeClass(event.extendedProps.status)}">${event.extendedProps.status}</span></p>
                    ${event.extendedProps.location ? `<p class="mb-0"><strong class="text-gray-800">Location:</strong> <span class="text-gray-700">${event.extendedProps.location}</span></p>` : ''}
                </div>
                ${event.extendedProps.description ? `<div class="mt-4"><h4 class="font-medium text-gray-800 mb-2">Description</h4><p class="text-gray-700">${event.extendedProps.description}</p></div>` : ''}
            `;
        } 
        else if (eventType === 'program') {
            content = `
                <div class="bg-purple-50 p-4 rounded-lg mb-4 border border-purple-100">
                    <p class="mb-2"><strong class="text-gray-800">Status:</strong> <span class="text-gray-700">${event.extendedProps.status}</span></p>
                    ${event.extendedProps.coordinator ? `<p class="mb-0"><strong class="text-gray-800">Coordinator:</strong> <span class="text-gray-700">${event.extendedProps.coordinator}</span></p>` : ''}
                </div>
                ${event.extendedProps.description ? `<div class="mt-4"><h4 class="font-medium text-gray-800 mb-2">Description</h4><p class="text-gray-700">${event.extendedProps.description}</p></div>` : ''}
            `;
        } 
        else if (eventType === 'birthday') {
            const subType = event.extendedProps.subtype;
            const bgColor = subType === 'student' ? 'bg-purple-50 border-purple-100' : 'bg-blue-50 border-blue-100';
            content = `
                <div class="${bgColor} p-4 rounded-lg mb-4 border">
                    <p class="mb-2"><strong class="text-gray-800">Type:</strong> <span class="text-gray-700">${subType === 'student' ? 'Student' : 'Teacher'}</span></p>
                    <p class="mb-2"><strong class="text-gray-800">Name:</strong> <span class="text-gray-700">${event.extendedProps.name}</span></p>
                    ${event.extendedProps.class ? `<p class="mb-2"><strong class="text-gray-800">Class:</strong> <span class="text-gray-700">${event.extendedProps.class}</span></p>` : ''}
                    ${event.extendedProps.subject ? `<p class="mb-2"><strong class="text-gray-800">Subject:</strong> <span class="text-gray-700">${event.extendedProps.subject}</span></p>` : ''}
                    <p class="mb-0"><strong class="text-gray-800">Age:</strong> <span class="text-gray-700">${event.extendedProps.age}</span></p>
                </div>
            `;
        } 
        else if (eventType === 'holiday') {
            content = `
                <div class="bg-red-50 p-4 rounded-lg mb-4 border border-red-100">
                    <p class="mb-0"><strong class="text-gray-800">Date:</strong> <span class="text-gray-700">${new Date(event.startStr).toLocaleDateString()}</span></p>
                </div>
                ${event.extendedProps.description ? `<div class="mt-4"><h4 class="font-medium text-gray-800 mb-2">Description</h4><p class="text-gray-700">${event.extendedProps.description}</p></div>` : ''}
            `;
        }
       else if (eventType === 'exams') {
    const startDate = new Date(event.startStr);
    const endDate = new Date(event.endStr);

    content = `
        <div class="bg-yellow-50 p-4 rounded-lg mb-4 border border-yellow-100">
            <p class="mb-2"><strong class="text-gray-800">Subject:</strong> <span class="text-gray-700">${event.extendedProps.subject || 'N/A'}</span></p>
            <p class="mb-2"><strong class="text-gray-800">Exam Type:</strong> <span class="text-gray-700">${event.extendedProps.exam_type || 'N/A'}</span></p>
            <p class="mb-2"><strong class="text-gray-800">Class:</strong> <span class="text-gray-700">${event.extendedProps.class || 'N/A'}</span></p>
            <p class="mb-2"><strong class="text-gray-800">Section:</strong> <span class="text-gray-700">${event.extendedProps.section || 'N/A'}</span></p>
            <p class="mb-2"><strong class="text-gray-800">Date:</strong> <span class="text-gray-700">${startDate.toLocaleDateString()}</span></p>
            <p class="mb-2"><strong class="text-gray-800">Start Time:</strong> <span class="text-gray-700">${startDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span></p>
            <p class="mb-2"><strong class="text-gray-800">End Time:</strong> <span class="text-gray-700">${endDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span></p>
            ${event.extendedProps.duration ? `<p class="mb-2"><strong class="text-gray-800">Duration:</strong> <span class="text-gray-700">${event.extendedProps.duration}</span></p>` : ''}
            ${event.extendedProps.room_no ? `<p class="mb-2"><strong class="text-gray-800">Room No:</strong> <span class="text-gray-700">${event.extendedProps.room_no}</span></p>` : ''}
            ${event.extendedProps.max_marks ? `<p class="mb-2"><strong class="text-gray-800">Max Marks:</strong> <span class="text-gray-700">${event.extendedProps.max_marks}</span></p>` : ''}
            ${event.extendedProps.min_marks ? `<p class="mb-2"><strong class="text-gray-800">Min Marks:</strong> <span class="text-gray-700">${event.extendedProps.min_marks}</span></p>` : ''}
            ${event.extendedProps.status ? `<p class="mb-0"><strong class="text-gray-800">Status:</strong> <span class="capitalize px-2.5 py-1 rounded-full text-xs font-bold ${getExamStatusBadgeClass(event.extendedProps.status)}">${event.extendedProps.status}</span></p>` : ''}
        </div>

        ${event.extendedProps.cancel_reason ? `
            <div class="bg-red-50 p-3 rounded-lg mt-2 border border-red-100">
                <h4 class="font-medium text-red-700 mb-1">Cancel Reason</h4>
                <p class="text-red-600">${event.extendedProps.cancel_reason}</p>
            </div>
        ` : ''}
    `;
}


        document.getElementById('detailContent').innerHTML = content;
        detailModal.classList.remove('hidden');
    }

    // Helper functions
    function getStatusBadgeClass(status) {
        switch (status) {
            case 'upcoming': return 'bg-blue-100 text-blue-800';
            case 'ongoing': return 'bg-green-100 text-green-800';
            case 'completed': return 'bg-gray-100 text-gray-800';
            case 'cancelled': return 'bg-red-100 text-red-800';
            default: return 'bg-blue-100 text-blue-800';
        }
    }
    function getExamStatusBadgeClass(status) {
    if (!status) return 'bg-amber-100 text-amber-800'; // default

    switch (status.toLowerCase()) {
        case 'active':
            return 'bg-green-100 text-green-800';
        case 'completed':
            return 'bg-gray-100 text-gray-800';
        case 'canceled':
        case 'cancelled':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-amber-100 text-amber-800';
    }
}


    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.classList.add('fixed', 'bottom-4', 'right-4', 'px-6', 'py-3', 'rounded-lg', 'shadow-lg', 'text-white', 'z-50', 'transition-all');
        notification.classList.add(type === 'success' ? 'bg-green-600' : 'bg-red-600');
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    // Close modals when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === holidayModal) holidayModal.classList.add('hidden');
        if (e.target === detailModal) detailModal.classList.add('hidden');
    });
});
</script>