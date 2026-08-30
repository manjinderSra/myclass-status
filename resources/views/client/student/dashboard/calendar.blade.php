@extends("client.student.layouts.master")

@section("title", "Event Calendar")

@section("content")
<div class="container px-6 mx-auto grid">
    <div class="flex justify-between items-center my-6">
        <h2 class="text-2xl font-semibold text-gray-700 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Event Calendar
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('student.programs-events') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Events
            </a>
        </div>
    </div>

    <!-- Calendar Filters -->
    <div class="bg-white p-4 rounded-lg shadow-md mb-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            Calendar Filters
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded" id="filter-events" checked>
                <span class="ml-2 text-gray-700 font-medium">Events</span>
            </label>
            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                <input type="checkbox" class="form-checkbox h-5 w-5 text-purple-600 rounded" id="filter-programs" checked>
                <span class="ml-2 text-gray-700 font-medium">Programs</span>
            </label>
            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                <input type="checkbox" class="form-checkbox h-5 w-5 text-purple-600 rounded" id="filter-student-birthdays" checked>
                <span class="ml-2 text-gray-700 font-medium">Student Birthdays</span>
            </label>
            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded" id="filter-teacher-birthdays" checked>
                <span class="ml-2 text-gray-700 font-medium">Teacher Birthdays</span>
            </label>
            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                <input type="checkbox" class="form-checkbox h-5 w-5 text-red-600 rounded" id="filter-holidays" checked>
                <span class="ml-2 text-gray-700 font-medium">Holidays</span>
            </label>
            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                <input type="checkbox" class="form-checkbox h-5 w-5 text-green-600 rounded" id="filter-issued-books" checked>
                <span class="ml-2 text-gray-700 font-medium">Library Books</span>
            </label>
        </div>
    </div>

    <!-- Calendar Container -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <div id="calendar" class="min-h-[600px]"></div>
    </div>
</div>

<!-- Event Details Modal -->
<div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden z-50 flex items-center justify-center" id="detailModal">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 overflow-hidden">
        <div class="bg-indigo-600 px-4 py-3 flex justify-between items-center">
            <h3 class="text-lg font-medium text-white" id="modal-title">Event Details</h3>
            <button id="close-modal" class="text-white hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-4" id="modal-content">
            <!-- Event details will be inserted here via JavaScript -->
        </div>
        <div class="px-4 py-3 bg-gray-50 flex justify-end">
            <a id="event-details-link" href="#" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition-colors duration-150">
                View Details
                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection

@section("scripts")
<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get data from PHP
        const events = @json($events ?? []);
        const programs = @json($programs ?? []);
        const studentBirthdays = @json($studentBirthdays ?? []);
        const teacherBirthdays = @json($teacherBirthdays ?? []);
        const holidays = @json($holidays ?? []);
        const issuedBooks = @json($issuedBooks ?? []);
        
        console.log("Events:", events);
        console.log("Programs:", programs);
        console.log("Student Birthdays:", studentBirthdays);
        console.log("Teacher Birthdays:", teacherBirthdays);
        console.log("Holidays:", holidays);
        console.log("Issued Books:", issuedBooks);
        
        // Format events for FullCalendar
        const calendarEvents = events.map(event => {
            // Format the start and end times properly
            const startTime = event.start_time ? event.start_time : '';
            const endTime = event.end_time ? event.end_time : '';
            
            return {
                id: 'event_' + event.id,
                title: event.title,
                start: event.event_date + (startTime ? 'T' + startTime : ''),
                end: event.event_date + (endTime ? 'T' + endTime : ''),
                url: '/student/events/' + event.id,
                backgroundColor: getEventColor(event.status),
                borderColor: getEventColor(event.status),
                textColor: '#ffffff',
                extendedProps: {
                    type: 'event',
                    status: event.status,
                    location: event.location,
                    description: event.description
                }
            };
        });
        
        // Format programs for FullCalendar
        const calendarPrograms = programs.map(program => {
            return {
                id: 'program_' + program.id,
                title: '[Program] ' + program.title,
                start: program.created_at,
                url: '/student/programs/' + program.id,
                backgroundColor: '#6b21a8', // Deep purple
                borderColor: '#6b21a8',
                textColor: '#ffffff',
                allDay: true,
                extendedProps: {
                    type: 'program',
                    status: program.status,
                    coordinator: program.coordinator,
                    description: program.description
                }
            };
        });
        
        // Format student birthdays for FullCalendar
        const calendarStudentBirthdays = studentBirthdays.map(student => {
            const currentYear = new Date().getFullYear();
            const birthDate = new Date(student.dob);
            const birthMonth = birthDate.getMonth() + 1;
            const birthDay = birthDate.getDate();
            
            return {
                id: 'student_' + student.id,
                title: '🎂 ' + student.first_name + ' ' + student.last_name + '\'s Birthday',
                start: currentYear + '-' + (birthMonth < 10 ? '0' + birthMonth : birthMonth) + '-' + (birthDay < 10 ? '0' + birthDay : birthDay),
                backgroundColor: '#8e44ad', // Purple
                borderColor: '#8e44ad',
                textColor: '#ffffff',
                allDay: true,
                extendedProps: {
                    type: 'birthday',
                    subtype: 'student',
                    name: student.first_name + ' ' + student.last_name,
                    class: student.class ? student.class.name : '',
                    age: currentYear - birthDate.getFullYear()
                }
            };
        });
        
        // Format teacher birthdays for FullCalendar
        const calendarTeacherBirthdays = teacherBirthdays.map(teacher => {
            const currentYear = new Date().getFullYear();
            const birthDate = new Date(teacher.date_of_birth);
            const birthMonth = birthDate.getMonth() + 1;
            const birthDay = birthDate.getDate();
            
            return {
                id: 'teacher_' + teacher.id,
                title: '🎂 ' + teacher.first_name + ' ' + teacher.last_name + '\'s Birthday',
                start: currentYear + '-' + (birthMonth < 10 ? '0' + birthMonth : birthMonth) + '-' + (birthDay < 10 ? '0' + birthDay : birthDay),
                backgroundColor: '#2980b9', // Blue
                borderColor: '#2980b9',
                textColor: '#ffffff',
                allDay: true,
                extendedProps: {
                    type: 'birthday',
                    subtype: 'teacher',
                    name: teacher.first_name + ' ' + teacher.last_name,
                    subject: teacher.subject ? teacher.subject.name : '',
                    age: currentYear - birthDate.getFullYear()
                }
            };
        });
        
        // Format holidays for FullCalendar
        const calendarHolidays = holidays.map(holiday => {
            return {
                id: 'holiday_' + holiday.id,
                title: '📅 ' + holiday.title,
                start: holiday.date,
                backgroundColor: '#e74c3c', // Red
                borderColor: '#e74c3c',
                textColor: '#ffffff',
                allDay: true,
                extendedProps: {
                    type: 'holiday',
                    description: holiday.description
                }
            };
        });

        // Format issued books for FullCalendar
        const calendarIssuedBooks = issuedBooks.map(book => {
            return {
                id: 'book_' + book.id,
                title: '📚 Due: ' + book.book_name,
                start: book.due_date,
                backgroundColor: '#27ae60', // Green
                borderColor: '#27ae60',
                textColor: '#ffffff',
                allDay: true,
                extendedProps: {
                    type: 'issued_book',
                    book_name: book.book_name,
                    book_no: book.book_no,
                    issue_date: book.issue_date,
                    due_date: book.due_date
                }
            };
        });
        
        // Combine all calendar items
        const calendarItems = [
            ...calendarEvents, 
            ...calendarPrograms, 
            ...calendarStudentBirthdays, 
            ...calendarTeacherBirthdays, 
            ...calendarHolidays,
            ...calendarIssuedBooks
        ];
        
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
                info.jsEvent.preventDefault(); // prevent browser from following the link
                showDetailModal(info.event);
            },
            eventDidMount: function(info) {
                // Add data attributes for filtering
                const eventType = info.event.extendedProps.type;
                const subType = info.event.extendedProps.subtype;
                
                if (eventType === 'birthday') {
                    info.el.setAttribute('data-type', 'birthday');
                    info.el.setAttribute('data-subtype', subType);
                } else if (eventType === 'holiday') {
                    info.el.setAttribute('data-type', 'holiday');
                } else if (eventType === 'program') {
                    info.el.setAttribute('data-type', 'program');
                } else if (eventType === 'issued_book') {
                    info.el.setAttribute('data-type', 'issued_book');
                } else {
                    info.el.setAttribute('data-type', 'event');
                    info.el.setAttribute('data-status', info.event.extendedProps.status);
                }
                
                // Add tooltip with event title
                info.el.setAttribute('title', info.event.title);
            },
            dayMaxEvents: true, // allow "more" link when too many events
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
        
        document.getElementById('filter-issued-books').addEventListener('change', function(e) {
            filterCalendarEvents('issued_book', e.target.checked);
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
        const detailModal = document.getElementById('detailModal');
        const closeModal = document.getElementById('close-modal');
        
        closeModal.addEventListener('click', function() {
            detailModal.classList.add('hidden');
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === detailModal) {
                detailModal.classList.add('hidden');
            }
        });
        
        // Function to show event/program details in modal
        function showDetailModal(event) {
            const modalTitle = document.getElementById('modal-title');
            const modalContent = document.getElementById('modal-content');
            const detailsLink = document.getElementById('event-details-link');
            
            const eventType = event.extendedProps.type;
            
            // Set modal title based on type
            if (eventType === 'program') {
                modalTitle.textContent = 'Program Details';
            } else if (eventType === 'event') {
                modalTitle.textContent = 'Event Details';
            } else if (eventType === 'birthday') {
                const subtype = event.extendedProps.subtype;
                modalTitle.textContent = subtype === 'student' ? 'Student Birthday' : 'Teacher Birthday';
            } else if (eventType === 'holiday') {
                modalTitle.textContent = 'Holiday Details';
            }
            
            // Set content based on type
            let contentHTML = '';
            
            if (eventType === 'event') {
                // Format event date
                const eventDate = new Date(event.start);
                const formattedDate = eventDate.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                // Get start and end times
                let timeInfo = '';
                if (event.extendedProps.start_time) {
                    timeInfo = event.extendedProps.start_time;
                    if (event.extendedProps.end_time) {
                        timeInfo += ' - ' + event.extendedProps.end_time;
                    }
                }
                
                contentHTML = `
                    <h4 class="text-lg font-medium mb-2">${event.title}</h4>
                    <p class="text-gray-600 mb-4">${event.extendedProps.description || 'No description available.'}</p>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <span class="text-gray-500">Date:</span>
                            <span class="font-medium">${formattedDate}</span>
                        </div>
                        ${timeInfo ? `
                        <div>
                            <span class="text-gray-500">Time:</span>
                            <span class="font-medium">${timeInfo}</span>
                        </div>
                        ` : ''}
                        <div>
                            <span class="text-gray-500">Location:</span>
                            <span class="font-medium">${event.extendedProps.location || 'Not specified'}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Status:</span>
                            <span class="px-2 py-1 text-xs rounded-full ${getStatusClass(event.extendedProps.status)}">
                                ${capitalizeFirstLetter(event.extendedProps.status)}
                            </span>
                        </div>
                    </div>
                `;
                detailsLink.href = `/student/events/${event.id.replace('event_', '')}`;
                detailsLink.style.display = 'flex';
            } else if (eventType === 'program') {
                contentHTML = `
                    <h4 class="text-lg font-medium mb-2">${event.title.replace('[Program] ', '')}</h4>
                    <p class="text-gray-600 mb-4">${event.extendedProps.description || 'No description available.'}</p>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <span class="text-gray-500">Coordinator:</span>
                            <span class="font-medium">${event.extendedProps.coordinator || 'Not specified'}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Status:</span>
                            <span class="px-2 py-1 text-xs rounded-full ${event.extendedProps.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                ${capitalizeFirstLetter(event.extendedProps.status)}
                            </span>
                        </div>
                    </div>
                `;
                detailsLink.href = `/student/programs/${event.id.replace('program_', '')}`;
                detailsLink.style.display = 'flex';
            } else if (eventType === 'birthday') {
                const subtype = event.extendedProps.subtype;
                const age = event.extendedProps.age;
                
                if (subtype === 'student') {
                    contentHTML = `
                        <h4 class="text-lg font-medium mb-2">${event.extendedProps.name}'s Birthday</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-500">Age:</span>
                                <span class="font-medium">${age} years</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Class:</span>
                                <span class="font-medium">${event.extendedProps.class || 'Not specified'}</span>
                            </div>
                        </div>
                    `;
                } else {
                    contentHTML = `
                        <h4 class="text-lg font-medium mb-2">${event.extendedProps.name}'s Birthday</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-500">Age:</span>
                                <span class="font-medium">${age} years</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Subject:</span>
                                <span class="font-medium">${event.extendedProps.subject || 'Not specified'}</span>
                            </div>
                        </div>
                    `;
                }
                detailsLink.style.display = 'none';
            } else if (eventType === 'holiday') {
                contentHTML = `
                    <h4 class="text-lg font-medium mb-2">${event.title.replace('📅 ', '')}</h4>
                    <p class="text-gray-600 mb-4">${event.extendedProps.description || 'No description available.'}</p>
                    <div class="text-sm">
                        <div>
                            <span class="text-gray-500">Date:</span>
                            <span class="font-medium">${new Date(event.start).toLocaleDateString('en-US', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            })}</span>
                        </div>
                    </div>
                `;
                detailsLink.style.display = 'none';
            }
            
            modalContent.innerHTML = contentHTML;
            detailModal.classList.remove('hidden');
        }
        
        // Helper function to get event color based on status
        function getEventColor(status) {
            switch(status) {
                case 'upcoming':
                    return '#4299e1'; // Blue
                case 'ongoing':
                    return '#48bb78'; // Green
                case 'completed':
                    return '#a0aec0'; // Gray
                case 'cancelled':
                    return '#f56565'; // Red
                default:
                    return '#4299e1'; // Default blue
            }
        }
        
        // Helper function to get status class for badges
        function getStatusClass(status) {
            switch(status) {
                case 'upcoming':
                    return 'bg-blue-100 text-blue-800';
                case 'ongoing':
                    return 'bg-green-100 text-green-800';
                case 'completed':
                    return 'bg-gray-100 text-gray-800';
                case 'cancelled':
                    return 'bg-red-100 text-red-800';
                default:
                    return 'bg-blue-100 text-blue-800';
            }
        }
        
        // Helper function to capitalize first letter
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    });
</script>
@endsection
