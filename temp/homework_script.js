$(document).ready(function () {
    // Initialize DataTables for Homeworks
    $('#homeworksTable').DataTable({
        // You can customize DataTables options here, e.g., for pagination, searching, ordering
        // Refer to DataTables documentation: https://datatables.net/manual/options
        "paging": true,      // Enable pagination
        "searching": true,   // Enable search box
        "ordering": true,    // Enable column ordering
        "info": true,        // Show "Showing X of Y entries" info
        "autoWidth": false,  // Disable auto-width for better responsiveness
        "responsive": true,  // Enable responsive features (requires DataTables Responsive extension)
       dom:
            "<'flex justify-between items-center mb-4'<'dataTables_length'l><'dataTables_filter'f>>" +
            "t" +
            "<'flex justify-between items-center mt-4'<'dataTables_info'i><'dataTables_paginate'p>>",
        // "buttons": [
        //     'copy', 'csv', 'excel', 'pdf', 'print'
        // ]
    });

    // Store all classes data globally
    let allClassesData = []; // To store the fetched data globally within this scope
    let allSubjectsData = []; // To store the fetched subjects data
    let allTeachersData = []; // To store the fetched teachers data

    // Function to fetch classes and sections from the API
    async function fetchClassesAndSections() {
        try {
            const response = await fetch('http://127.0.0.1:8000/school/api/active-classes');
            const data = await response.json();
            if (data.success && data.classes) {
                allClassesData = data.classes; // Store the fetched data
                populateClassDropdowns(allClassesData); // Populate all class dropdowns
                return true;
            } else {
                console.error('API call failed or no classes data:', data.message || 'No classes data found.');
                return false;
            }
        } catch (error) {
            console.error('Error fetching classes and sections:', error);
            return false;
        }
    }

    // Function to fetch subjects from the API
    async function fetchSubjects() {
        try {
            const response = await fetch('http://127.0.0.1:8000/school/api/active-subjects');
            const data = await response.json();
            
            if (data.success) {
                allSubjectsData = data.subjects;
                console.log('Fetched subjects:', allSubjectsData);
                populateSubjectDropdowns();
                return true;
            } else {
                console.error('Failed to fetch subjects:', data.message);
                return false;
            }
        } catch (error) {
            console.error('Error fetching subjects:', error);
            return false;
        }
    }

    // Function to populate all class dropdowns
    function populateClassDropdowns(data) {
        const uniqueClasses = {};
        data.forEach(item => {
            uniqueClasses[item.name] = true;
        });

        // Filter form class dropdown
        const filterHomeworkClass = $('#filterHomeworkClass');
        filterHomeworkClass.html('<option value="">Select Class</option>'); // Clear existing options
        
        // Add Homework form class dropdown
        const homeworkClass = $('#homeworkClass');
        homeworkClass.html('<option value="">Select Class</option>'); // Clear existing options
        
        Object.keys(uniqueClasses).forEach(className => {
            const option = document.createElement('option');
            option.value = className;
            option.textContent = className;
            
            // Add to filter dropdown
            filterHomeworkClass.append(option.cloneNode(true));
            
            // Add to homework form dropdown
            homeworkClass.append(option);
        });
    }

    // Function to populate section dropdowns based on selected class
    function populateSectionDropdown(selectedClassName, sectionDropdown, data) {
        sectionDropdown.html('<option value="">Select Section</option>'); // Clear and add default
        if (selectedClassName) {
            const filteredSections = data.filter(item => item.name === selectedClassName);
            // Use a Set to ensure unique sections for the selected class if there are duplicates
            const uniqueSectionsForClass = new Set();
            filteredSections.forEach(item => {
                if (item.section && !uniqueSectionsForClass.has(item.section.id)) {
                    const option = document.createElement('option');
                    option.value = item.section.id;
                    option.textContent = item.section.name;
                    sectionDropdown.append(option);
                    uniqueSectionsForClass.add(item.section.id);
                }
            });
        }
    }

    // Function to populate all subject dropdowns
    function populateSubjectDropdowns() {
        // Populate filter dropdown
        const filterHomeworkSubject = $('#filterHomeworkSubject');
        filterHomeworkSubject.html('<option value="">Select Subject</option>');
        
        // Populate homework form dropdown
        const homeworkSubject = $('#homeworkSubject');
        homeworkSubject.html('<option value="">Select Subject</option>');
        
        allSubjectsData.forEach(subject => {
            const option = document.createElement('option');
            option.value = subject.id;
            option.textContent = subject.name;
            
            // Add to filter dropdown
            filterHomeworkSubject.append(option.cloneNode(true));
            
            // Add to homework form dropdown
            homeworkSubject.append(option);
        });
    }

    // Initial calls to fetch data and populate dropdowns
    Promise.all([
        fetchClassesAndSections(),
        fetchSubjects()
    ]).then(() => {
        console.log('All data loaded successfully');
    }).catch(error => {
        console.error("Error loading data:", error);
        Swal.fire({
            title: 'Error',
            text: 'Failed to load necessary data. Please refresh the page.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });

    // Event listener for filter Class dropdown change
    $('#filterHomeworkClass').on('change', function() {
        populateSectionDropdown($(this).val(), $('#filterHomeworkSection'), allClassesData);
    });

    // Event listener for homework Class dropdown change
    $('#homeworkClass').on('change', function() {
        populateSectionDropdown($(this).val(), $('#homeworkSection'), allClassesData);
    });

    // --- Add/Edit Homework Modal Logic ---
    const addEditHomeworkModal = $('#addEditHomeworkModal');
    const openAddHomeworkModal = $('#openAddHomeworkModal');
    const closeAddEditHomeworkModal = $('#closeAddEditHomeworkModal');
    const homeworkModalTitle = $('#homeworkModalTitle');
    const homeworkForm = $('#homeworkForm');
    const saveHomeworkBtn = $('#saveHomeworkBtn');
    const cancelAddEditHomeworkBtn = $('#cancelAddEditHomeworkBtn');

    openAddHomeworkModal.on('click', function() {
        homeworkModalTitle.text('Add New Homework');
        saveHomeworkBtn.text('Save Homework');
        homeworkForm[0].reset(); // Clear form fields
        
        // Reset section dropdown
        $('#homeworkSection').html('<option value="">Select Section</option>');
        
        addEditHomeworkModal.removeClass('hidden');
    });

    closeAddEditHomeworkModal.on('click', function() {
        addEditHomeworkModal.addClass('hidden');
    });

    cancelAddEditHomeworkBtn.on('click', function() {
        addEditHomeworkModal.addClass('hidden');
    });

    homeworkForm.on('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        let isValid = true;
        const requiredFields = $(this).find('[required]');
        requiredFields.each(function() {
            if (!$(this).val()) {
                $(this).addClass('border-red-500');
                isValid = false;
            } else {
                $(this).removeClass('border-red-500');
            }
        });
        
        if (!isValid) {
            Swal.fire({
                title: 'Error',
                text: 'Please fill in all required fields',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        const formData = $(this).serializeArray();
        console.log('Homework Data:', formData);
        
        // Show success message (in a real app, you'd send this data via AJAX)
        Swal.fire({
            title: 'Success!',
            text: 'Homework saved successfully',
            icon: 'success',
            confirmButtonText: 'OK'
        });
        
        addEditHomeworkModal.addClass('hidden');
        // Here you would typically send this data via AJAX to your backend
        // After successful save, you might want to refresh the table or add the new entry to it.
        // If using DataTables with AJAX source, you'd call table.ajax.reload();
    });

    // --- Edit Homework Logic ---
    $(document).on('click', '.editHomeworkBtn', function(e) {
        e.preventDefault();
        const homeworkId = $(this).data('id');
        homeworkModalTitle.text('Edit Homework');
        saveHomeworkBtn.text('Update Homework');
        // In a real application, you would fetch homework data by ID via AJAX
        // and populate the form fields with that data.
        console.log('Fetching homework with ID:', homeworkId);
        // Example of populating with dummy data for demonstration
        $('#homeworkClass').val('1');
        $('#homeworkSection').val('A');
        $('#homeworkSubject').val('Maths');
        $('#homeworkDate').val('2025-05-28');
        $('#submissionDate').val('2025-06-01');
        $('#description').val('Complete exercises from chapter 5.');

        addEditHomeworkModal.removeClass('hidden');
    });

    // --- Delete Homework Modal Logic ---
    const deleteHomeworkModal = $('#deleteHomeworkModal');
    const closeDeleteHomeworkModal = $('#closeDeleteHomeworkModal');
    const confirmDeleteHomeworkBtn = $('#confirmDeleteHomeworkBtn');
    let homeworkToDeleteId = null;

    $(document).on('click', '.deleteHomeworkBtn', function(e) {
        e.preventDefault();
        homeworkToDeleteId = $(this).data('id'); // Get ID from data attribute
        deleteHomeworkModal.removeClass('hidden');
    });

    closeDeleteHomeworkModal.on('click', function() {
        deleteHomeworkModal.addClass('hidden');
        homeworkToDeleteId = null;
    });

    confirmDeleteHomeworkBtn.on('click', function() {
        if (homeworkToDeleteId) {
            // Show loading state
            const deleteButton = $(this);
            const originalButtonText = deleteButton.text();
            deleteButton.text('Deleting...').prop('disabled', true);
            
            // In a real app, you would make an AJAX call to delete the entry
            // For demonstration, we're just showing a success message
            setTimeout(() => {
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Homework has been deleted successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
                
                deleteHomeworkModal.addClass('hidden');
                homeworkToDeleteId = null;
                
                // Reset button state
                deleteButton.text(originalButtonText).prop('disabled', false);
                
                // Optionally, refresh the homework table or remove the row
                // If using DataTables with AJAX source, you'd call table.ajax.reload();
            }, 500);
        }
    });

    // --- Filter Homework Modal Logic ---
    const filterHomeworkModal = $('#filterHomeworkModal');
    const openFilterHomeworkModal = $('#openFilterHomeworkModal');
    const closeFilterHomeworkModal = $('#closeFilterHomeworkModal');
    const filterHomeworkForm = $('#filterHomeworkForm');
    const resetFilterHomeworkBtn = $('#resetFilterHomeworkBtn');

    openFilterHomeworkModal.on('click', function() {
        filterHomeworkModal.removeClass('hidden');
    });

    closeFilterHomeworkModal.on('click', function() {
        filterHomeworkModal.addClass('hidden');
    });

    resetFilterHomeworkBtn.on('click', function() {
        filterHomeworkForm[0].reset();
        $('#filterHomeworkSection').html('<option value="">Select Section</option>');
    });

    filterHomeworkForm.on('submit', function(e) {
        e.preventDefault();
        
        // Show loading indicator
        const submitButton = $(this).find('button[type="submit"]');
        const originalButtonText = submitButton.text();
        submitButton.text('Applying...').prop('disabled', true);
        
        const formData = $(this).serializeArray();
        console.log('Filter Homework Data:', formData);
        
        // In a real app, you would make an AJAX call with the filter parameters
        // For demonstration, we're just showing a success message
        setTimeout(() => {
            Swal.fire({
                title: 'Filters Applied',
                text: 'Homework table has been filtered',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
            
            filterHomeworkModal.addClass('hidden');
            
            // Reset button state
            submitButton.text(originalButtonText).prop('disabled', false);
            
            // Here you would typically re-draw the DataTables with the filtered data
            // Example: homeworksTable.ajax.url('your_api_endpoint_with_filters').load();
        }, 500);
    });
}); 