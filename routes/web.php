<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AssignFeeController;
use App\Http\Controllers\Client\SchoolPanel\Academics\TimeTableController;
use App\Http\Controllers\Client\SchoolPanel\LibraryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\FeeGroupController;
use App\Http\Controllers\FeeTypeController;
use App\Http\Controllers\Client\SchoolPanel\Peoples\StudentController;
use App\Http\Controllers\CreateTestLibraryDataController;
use App\Http\Controllers\FixLeavesController;
use App\Http\Controllers\TeacherProfileController;
use App\Http\Controllers\TeacherAnnouncementsController;
use App\Http\Controllers\TeacherHelpSupportController;
use App\Http\Controllers\TeacherTimetableController;
use App\Http\Controllers\TeacherHomeworkController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\School\CollectFeeController;
use Illuminate\Support\Facades\DB;

use function Psy\debug;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return view('landing.index');
})->name('landing.index');

Route::get('/in/about', function () {
    return view('landing.about');
})->name('landing.about');

Route::get('/in/contact', function () {
    return view('landing.contact');
})->name('landing.contact');

Route::get('/in/pricing', [App\Http\Controllers\LandingController::class, 'pricing'])->name('landing.pricing');
Route::get('/in/plan/{id}', [App\Http\Controllers\LandingController::class, 'planDetails'])->name('landing.plan.details');
Route::get('/in/plan/{id}/purchase', [App\Http\Controllers\LandingController::class, 'planPurchase'])->name('landing.plan.purchase');
Route::post('/in/register-school/{planId}', [App\Http\Controllers\LandingController::class, 'registerSchool'])->name('landing.register.school');

Route::get('/in/term', function () {
    return view('landing.term');
})->name('landing.term');

Route::get('/in/privacy', function () {
    return view('landing.privacy');
})->name('landing.privacy');

Route::get('/in/events', function () {
    return view('landing.events');
})->name('landing.events');

Route::get('/in/teachers', function () {
    return view('landing.teachers');
})->name('landing.teachers');



// SAAS ADMIN
Route::controller(App\Http\Controllers\SaasAdminController::class)->group(function () {
    Route::get('/admin/login', 'showLogin')->name('saasAdmin.login');
    Route::post('/admin/login', 'login');
    Route::get('/admin/register', 'showRegister')->name('saasAdmin.register');
    Route::post('/admin/register', 'register');
    Route::post('/admin/logout', 'logout')->name('saasAdmin.logout');
    Route::post('/admin/school/addSchool', 'changePlan')->name('saasAdmin.school.addSchool');
    // Protected routes
    Route::middleware('auth')->group(function () {
        Route::get('/admin/dashboard', 'showDashboard')->name('saasAdmin.dashboard');
        Route::get('/admin/schools', 'showSchools')->name('saasAdmin.schools');
        // show form
        // Route::get('/admin/forgetSchoolPassword/{school}', 
        //     'showForgetForm'
        // )->name('saasAdmin.forgetSchoolPassword.form');

        // update password
        Route::post(
            '/admin/forgetSchoolPassword',
            'forgetSchoolPassword'
        )->name('saasAdmin.forgetSchoolPassword.update');

        Route::get('/admin/addSchool', 'addSchool')->name('saasAdmin.addSchool');
        Route::get('/admin/schools/{school}/edit', 'editSchoolSubscription')->name('school.editSubscription');
        // Route to handle the update of a school's subscription
        Route::put('/admin/schools/{school}', 'updateSchoolSubscription')->name('school.updateSchoolSubscription');

        Route::get('/admin/plans', 'showPlans')->name('saasAdmin.plans');
        Route::get('/admin/features', 'showFeatures')->name('saasAdmin.features');
        Route::get('/admin/subscriptions', 'showSubscriptions')->name('saasAdmin.subscriptions');
    });
});

// Plan Management
Route::controller(App\Http\Controllers\PlanController::class)->middleware('auth')->group(function () {
    Route::get('/admin/plans/debug', 'debugSubscriptionCounts')->name('saasAdmin.plans.debug');
    Route::get('/admin/plans/create', 'create')->name('saasAdmin.plans.create');
    Route::post('/admin/plans', 'store')->name('saasAdmin.plans.store');
    Route::get('/admin/plans/{plan}/edit', 'edit')->name('saasAdmin.plans.edit');
    Route::put('/admin/plans/{plan}', 'update')->name('saasAdmin.plans.update');
    Route::delete('/admin/plans/{plan}', 'destroy')->name('saasAdmin.plans.destroy');
});

// Feature Management
Route::controller(App\Http\Controllers\FeatureController::class)->middleware('auth')->group(function () {
    Route::get('/admin/features/create', 'create')->name('saasAdmin.features.create');
    Route::post('/admin/features', 'store')->name('saasAdmin.features.store');
    Route::get('/admin/features/{feature}/edit', 'edit')->name('saasAdmin.features.edit');
    Route::put('/admin/features/{feature}', 'update')->name('saasAdmin.features.update');
    Route::delete('/admin/features/{feature}', 'destroy')->name('saasAdmin.features.destroy');
    Route::post('/admin/features/add-defaults', 'addDefaultFeatures')->name('saasAdmin.features.addDefaults');
});

// Subscription Management
Route::controller(App\Http\Controllers\SubscriptionController::class)->middleware('auth')->group(function () {
    Route::get('/admin/subscriptions', 'index')->name('saasAdmin.subscriptions');
    Route::get('/admin/subscriptions/create', 'create')->name('saasAdmin.subscriptions.create');
    Route::post('/admin/subscriptions', 'store')->name('saasAdmin.subscriptions.store');
    Route::get('/admin/subscriptions/{subscription}', 'show')->name('saasAdmin.subscriptions.show');
    Route::get('/admin/subscriptions/{subscription}/edit', 'edit')->name('saasAdmin.subscriptions.edit');
    Route::put('/admin/subscriptions/{subscription}', 'update')->name('saasAdmin.subscriptions.update');
    Route::delete('/admin/subscriptions/{subscription}', 'destroy')->name('saasAdmin.subscriptions.destroy');
    Route::post('/admin/subscriptions/change-plan', 'changePlan')->name('saasAdmin.subscriptions.changePlan');

    Route::get('/admin/subscriptions/change-plan/form', 'showChangePlanForm')->name('saasAdmin.subscriptions.changePlanForm');
    Route::patch('/admin/subscriptions/{subscription}/cancel', 'cancel')->name('saasAdmin.subscriptions.cancel');
});

// Student Panel Authentication Routes
Route::get('/student/login', [App\Http\Controllers\StudentAuthController::class, 'showLogin'])->name('student.login');
Route::post('/student/login', [App\Http\Controllers\StudentAuthController::class, 'login'])->name('student.login.submit');
Route::post('/student/logout', [App\Http\Controllers\StudentAuthController::class, 'logout'])->name('student.logout');
Route::get('/student/logout-success', [App\Http\Controllers\StudentAuthController::class, 'showLogoutSuccess'])->name('student.logout.success');

// Teacher Panel Authentication Routes
Route::get('/teacher/login', [App\Http\Controllers\TeacherAuthController::class, 'showLogin'])->name('teacher.login');
Route::post('/teacher/login', [App\Http\Controllers\TeacherAuthController::class, 'login'])->name('teacher.login.submit');
Route::post('/teacher/logout', [App\Http\Controllers\TeacherAuthController::class, 'logout'])->name('teacher.logout');
Route::get('/teacher/logout-success', [App\Http\Controllers\TeacherAuthController::class, 'showLogoutSuccess'])->name('teacher.logout.success');


// Teacher Debug Route - Temporary
Route::get('/teacher/debug', function () {
    return view('client.teacher.debug');
})->name('teacher.debug');

// Teacher Dashboard - Protected with teacher.auth middleware
Route::middleware(['teacher.auth'])->group(function () {
    Route::get('/teacher/dashboard', function () {
        return view('client.teacher.dashboard.dashboard');
    })->name('teacher.dashboard');

    // Teacher Profile Routes
    Route::get('/teacher/profile', [App\Http\Controllers\TeacherProfileController::class, 'index'])->name('teacher.profile');
    Route::post('/teacher/profile/update-image', [App\Http\Controllers\TeacherProfileController::class, 'updateProfileImage'])->name('teacher.profile.updateImage');
    Route::post('/teacher/profile/update-password', [App\Http\Controllers\TeacherProfileController::class, 'updatePassword'])->name('teacher.profile.updatePassword');
    Route::post('/teacher/profile/update-personal', [App\Http\Controllers\TeacherProfileController::class, 'updatePersonalDetails'])->name('teacher.profile.updatePersonal');

    // Exam Result Teacher with teacher prefix
    Route::prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/exams/results', [App\Http\Controllers\TeacherExamResultController::class, 'index'])->name('exams.results.index');
        Route::get('/exams/results/{scheduleId}/view', [App\Http\Controllers\TeacherExamResultController::class, 'viewResults'])->name('exams.results.view');
        Route::get('/exams/results/{schedule}', [App\Http\Controllers\TeacherExamResultController::class, 'show'])->name('exams.results.show');
        Route::post('/exams/results/{schedule}', [App\Http\Controllers\TeacherExamResultController::class, 'store'])->name('exams.results.store');
    });

    // Teacher Announcements Routes
    Route::get('/teacher/announcements', [App\Http\Controllers\TeacherAnnouncementsController::class, 'index'])->name('teacher.announcements');
    Route::post('/teacher/announcements/mark-all-read', [App\Http\Controllers\TeacherAnnouncementsController::class, 'markAllAsRead'])->name('teacher.announcements.markAllRead');

    // Teacher Leave Applications Routes
    Route::get('/teacher/leave-applications', [App\Http\Controllers\Client\TeacherPanel\LeaveApplicationController::class, 'index'])->name('teacher.leaveApplications');
    Route::get('/teacher/leave-applications/{id}', [App\Http\Controllers\Client\TeacherPanel\LeaveApplicationController::class, 'show'])->name('teacher.leaveApplications.show');
    Route::post('/teacher/leave-applications/{id}/update-status', [App\Http\Controllers\Client\TeacherPanel\LeaveApplicationController::class, 'updateStatus'])->name('teacher.leaveApplications.updateStatus');

    // Teacher Help & Support Routes
    Route::get('/teacher/help-support', [App\Http\Controllers\TeacherHelpSupportController::class, 'index'])->name('teacher.help-support');
    Route::get('/teacher/help-support/topic/{topic}', [App\Http\Controllers\TeacherHelpSupportController::class, 'viewTopic'])->name('teacher.help-support.topic');
    Route::post('/teacher/help-support/submit-ticket', [App\Http\Controllers\TeacherHelpSupportController::class, 'submitTicket'])->name('teacher.help-support.submit-ticket');
    Route::get('/teacher/help-support/ticket/{ticket}', [App\Http\Controllers\TeacherHelpSupportController::class, 'viewTicket'])->name('teacher.help-support.ticket');
    Route::post('/teacher/help-support/ticket/{ticket}/reply', [App\Http\Controllers\TeacherHelpSupportController::class, 'replyToTicket'])->name('teacher.help-support.reply');

    // Add other teacher-protected routes here

    // Teacher Timetable Routes
    Route::get('/teacher/timetable', [App\Http\Controllers\TeacherTimetableController::class, 'index'])->name('teacher.timetable');
    Route::get('/teacher/timetable/class/{timetableId}', [App\Http\Controllers\TeacherTimetableController::class, 'showClassTimetable'])->name('teacher.timetable.class');

    Route::get('/teacher/exams', [App\Http\Controllers\TeacherTimetableController::class, 'showExamSchedule'])->name('teacher.exam');

    // Teacher Homework Routes
    Route::get('/teacher/homework', [App\Http\Controllers\TeacherHomeworkController::class, 'index'])->name('teacher.homework');
    Route::get('/teacher/homework/filter', [App\Http\Controllers\TeacherHomeworkController::class, 'filter'])->name('teacher.homework.filter');
    Route::post('/teacher/homework/store', [App\Http\Controllers\TeacherHomeworkController::class, 'store'])->name('teacher.homework.store');
    Route::get('/teacher/homework/get/{id}', [App\Http\Controllers\TeacherHomeworkController::class, 'get'])->name('teacher.homework.get');
    Route::post('/teacher/homework/update/{id}', [App\Http\Controllers\TeacherHomeworkController::class, 'update'])->name('teacher.homework.update');
    Route::delete('/teacher/homework/delete/{id}', [App\Http\Controllers\TeacherHomeworkController::class, 'delete'])->name('teacher.homework.delete');

    Route::get('/teacher/homeworks/{homework}/submissions', [App\Http\Controllers\TeacherHomeworkController::class, 'showSubmissions'])
        ->name('teacher.homeworks.submissions');

    // Teacher Attendance Routes
    Route::get('/teacher/attendance', [App\Http\Controllers\TeacherAttendanceController::class, 'index'])->name('teacher.attendance');
    Route::get('/teacher/attendance/get-students', [App\Http\Controllers\TeacherAttendanceController::class, 'getStudents'])->name('teacher.attendance.getStudents');
    Route::post('/teacher/attendance/save', [App\Http\Controllers\TeacherAttendanceController::class, 'saveAttendance'])->name('teacher.attendance.save');
    Route::get('/teacher/attendance/report', [App\Http\Controllers\TeacherAttendanceController::class, 'report'])->name('teacher.attendance.report');
    Route::get('/teacher/attendance/report-data', [App\Http\Controllers\TeacherAttendanceController::class, 'getReportData'])->name('teacher.attendance.reportData');
});



// Student Dashboard - Protected with student.auth middleware
Route::middleware(['student.auth'])->group(function () {
    Route::get('/student/dashboard', [App\Http\Controllers\StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::middleware(['auth'])->group(function () {});


    Route::get('/student/profile', [App\Http\Controllers\StudentDashboardController::class, 'profile'])->name('student.profile');
    Route::post('/student/update-password', [App\Http\Controllers\StudentDashboardController::class, 'updatePassword'])->name('student.updatePassword');

    Route::get('student/attendance', [App\Http\Controllers\Student\AttendanceController::class, 'index'])->name('student.attendance');
    Route::get('student/attendance/data', [App\Http\Controllers\Student\AttendanceController::class, 'getAttendanceData'])->name('student.attendance.data');
    // Leave application routes
    Route::get('/student/leaves', [App\Http\Controllers\StudentDashboardController::class, 'leaves'])->name('student.leaves');
    Route::get('/student/leaves/all', [App\Http\Controllers\StudentDashboardController::class, 'allLeaves'])->name('student.leaves.all');
    Route::get('/student/leaves/{id}', [App\Http\Controllers\StudentDashboardController::class, 'leaveDetails'])->name('student.leaves.view');
    Route::post('/student/leaves/submit', [App\Http\Controllers\StudentDashboardController::class, 'submitLeave'])->name('student.leaves.submit');

    // Student Complaint Box routes
    Route::get('/student/complaints', [App\Http\Controllers\StudentDashboardController::class, 'complaints'])->name('student.complaints');
    Route::get('/student/complaints/all', [App\Http\Controllers\StudentDashboardController::class, 'allComplaints'])->name('student.complaints.all');
    Route::get('/student/complaints/{id}', [App\Http\Controllers\StudentDashboardController::class, 'complaintDetails'])->name('student.complaints.view');
    Route::post('/student/complaints/submit', [App\Http\Controllers\StudentDashboardController::class, 'submitComplaint'])->name('student.complaints.submit');


    Route::get('/student/home-work', [App\Http\Controllers\StudentDashboardController::class, 'indexHomeWork'])->name('student.homework');
    Route::post('/student/homework/{id}/submit', [App\Http\Controllers\StudentDashboardController::class, 'submitHomeWork'])->name('student.homework.submit');



    Route::get('/student/exams', [App\Http\Controllers\StudentDashboardController::class, 'indexExam'])->name('student.exam');
    Route::get('/student/results', [App\Http\Controllers\StudentDashboardController::class, 'showResultPage'])->name('student.result');
    Route::get('/student/results/show', [App\Http\Controllers\StudentDashboardController::class, 'fetchResults'])->name('student.exam-results.fetch');


    // Student Timetable route
    Route::get('/student/timetable', [App\Http\Controllers\StudentDashboardController::class, 'timetable'])->name('student.timetable');

    // Student Rules & Regulations route
    Route::get('/student/rules', [App\Http\Controllers\StudentDashboardController::class, 'rulesAndRegulations'])->name('student.rules');


    // debug();
    // Temporary route for debugging complaints
    // Route::get('/debug-complaints', function () {
    //     try {
    //         // Get student
    //         $student = \App\Models\Student::findOrFail(\Illuminate\Support\Facades\Session::get('student_id'));

    //         echo "<h3>Student Information</h3>";
    //         echo "ID: " . $student->id . "<br>";
    //         echo "Student ID: " . $student->student_id . "<br>";
    //         echo "School ID: " . $student->school_id . "<br>";
    //         echo "Name: " . $student->first_name . " " . $student->last_name . "<br><br>";

    //         // Check if complaints table exists
    //         echo "<h3>Database Check</h3>";
    //         $tableExists = \Illuminate\Support\Facades\Schema::hasTable('complaints');
    //         echo "Complaints table exists: " . ($tableExists ? 'Yes' : 'No') . "<br>";

    //         if ($tableExists) {
    //             // Get all complaints
    //             $allComplaints = \App\Models\Complaint::all();
    //             echo "Total complaints in database: " . $allComplaints->count() . "<br><br>";

    //             // Get complaints for this student
    //             $studentComplaints = \App\Models\Complaint::where('student_id', $student->student_id)->get();
    //             echo "Complaints for this student: " . $studentComplaints->count() . "<br><br>";

    //             if ($studentComplaints->count() > 0) {
    //                 echo "<h4>Student's Complaints:</h4>";
    //                 echo "<ul>";
    //                 foreach ($studentComplaints as $complaint) {
    //                     echo "<li>ID: " . $complaint->id . " | Complaint ID: " . $complaint->complaint_id . " | Nature: " . $complaint->nature . " | Status: " . $complaint->status . "</li>";
    //                 }
    //                 echo "</ul>";
    //             } else {
    //                 echo "No complaints found for this student.<br>";

    //                 // Create a test complaint
    //                 echo "<h4>Creating a test complaint...</h4>";

    //                 $testComplaint = new \App\Models\Complaint();
    //                 $testComplaint->school_id = $student->school_id;
    //                 $testComplaint->student_id = $student->student_id;
    //                 $testComplaint->complaint_id = 'TEST-' . time();
    //                 $testComplaint->nature = 'Test Issue';
    //                 $testComplaint->description = 'This is a test complaint created for debugging';
    //                 $testComplaint->status = 'pending';
    //                 $testComplaint->save();

    //                 echo "Test complaint created with ID: " . $testComplaint->complaint_id . "<br>";
    //             }
    //         }

    //         echo "<br><a href='/student/complaints'>Go to complaints page</a>";
    //     } catch (\Exception $e) {
    //         echo "<h3>Error</h3>";
    //         echo "Message: " . $e->getMessage() . "<br>";
    //         echo "File: " . $e->getFile() . " (Line " . $e->getLine() . ")<br>";
    //         echo "Trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
    //     }
    // });

    // Student Help & Support routes
    Route::get('/student/help', [App\Http\Controllers\StudentDashboardController::class, 'helpAndSupport'])->name('student.help');
    Route::post('/student/help/ticket', [App\Http\Controllers\StudentDashboardController::class, 'submitSupportTicket'])->name('student.help.ticket');

    // Programs and Events Routes
    Route::get('/student/programs-events', [App\Http\Controllers\StudentDashboardController::class, 'programsAndEvents'])->name('student.programs-events');
    Route::get('/student/programs/{id}', [App\Http\Controllers\StudentDashboardController::class, 'programDetails'])->name('student.programs.view');
    Route::get('/student/events/{id}', [App\Http\Controllers\StudentDashboardController::class, 'eventDetails'])->name('student.events.view');
    Route::get('/student/calendar', [App\Http\Controllers\StudentDashboardController::class, 'calendar'])->name('student.calendar');

    // Library Routes
    Route::get('/student/library', [App\Http\Controllers\StudentDashboardController::class, 'libraryRecords'])->name('student.library');
    Route::get('/student/library-records/create-test-data', [CreateTestLibraryDataController::class, 'createTestData'])->name('student.library.create-test-data');
    Route::get('/student/library-debug', [App\Http\Controllers\StudentDashboardController::class, 'libraryDebug'])->name('student.library.debug');

    // Student Announcements
    Route::get('/student/announcements', [App\Http\Controllers\StudentDashboardController::class, 'announcements'])->name('student.announcements');
});

// Client School Panel Authentication(login, logout)
Route::get('/school/login', [App\Http\Controllers\SchoolAuthController::class, 'showLogin'])->name('school.login');
Route::post('/school/login', [App\Http\Controllers\SchoolAuthController::class, 'login'])->name('school.login.submit');
Route::get('/school/logout', [App\Http\Controllers\SchoolAuthController::class, 'logout'])->name('school.logout');
Route::get('/school/signup', [App\Http\Controllers\SchoolAuthController::class, 'redirectToSignup'])->name('school.signup');





// School Panel Dashboard - Protected with school.auth middleware
Route::middleware(['school.auth'])->group(function () {
    Route::get('/school/dashboard', [App\Http\Controllers\Client\SchoolPanel\DashboardController::class, 'index'])->name('school.dashboard');
});

// School Panel with Feature Restrictions
Route::middleware(['school.auth', 'subscription.feature:institute_profile'])->group(function () {
    Route::get('/school/instituteProfile', [App\Http\Controllers\SchoolProfileController::class, 'index'])->name('school.instituteProfile');
    Route::post('/school/instituteProfile', [App\Http\Controllers\SchoolProfileController::class, 'update'])->name('school.instituteProfile.update');
});

Route::middleware(['school.auth', 'subscription.feature:rules_regulations'])->group(function () {
    Route::get('/school/rulesAndRegulations', [App\Http\Controllers\SchoolRulesController::class, 'index'])->name('school.rulesAndRegulations');
    Route::post('/school/rulesAndRegulations/category', [App\Http\Controllers\SchoolRulesController::class, 'storeCategory'])->name('school.rulesAndRegulations.storeCategory');
    Route::post('/school/rulesAndRegulations/rule', [App\Http\Controllers\SchoolRulesController::class, 'storeRule'])->name('school.rulesAndRegulations.storeRule');
    Route::put('/school/rulesAndRegulations/rule/{id}', [App\Http\Controllers\SchoolRulesController::class, 'updateRule'])->name('school.rulesAndRegulations.updateRule');
    Route::delete('/school/rulesAndRegulations/rule/{id}', [App\Http\Controllers\SchoolRulesController::class, 'deleteRule'])->name('school.rulesAndRegulations.deleteRule');
});

Route::middleware(['school.auth', 'subscription.feature:account_settings'])->group(function () {
    Route::get('/school/accSettings', function () {
        return view('client.schoolPanel.generalSettings.accSettings');
    })->name('school.accSettings');
});

Route::middleware(['school.auth', 'subscription.feature:notice_board'])->group(function () {
    Route::get('/school/noticeBoard', [App\Http\Controllers\Client\SchoolPanel\Announcements\NoticeController::class, 'index'])->name('school.noticeBoard');
    // Notice routes for CRUD operations
    Route::post('/notices', [App\Http\Controllers\Client\SchoolPanel\Announcements\NoticeController::class, 'store'])->name('school.notices.store');
    Route::get('/notices/{id}/edit', [App\Http\Controllers\Client\SchoolPanel\Announcements\NoticeController::class, 'edit'])->name('school.notices.edit');
    Route::put('/notices/{id}', [App\Http\Controllers\Client\SchoolPanel\Announcements\NoticeController::class, 'update'])->name('school.notices.update');
    Route::delete('/notices/{id}', [App\Http\Controllers\Client\SchoolPanel\Announcements\NoticeController::class, 'destroy'])->name('school.notices.destroy');
});

// Teacher
Route::middleware(['school.auth', 'subscription.feature:role_management'])->group(function () {
    // Roles and Permissions routes
    Route::get('/school/rolesAndPermissions', [App\Http\Controllers\RoleController::class, 'index'])->name('school.rolesAndPermissions.index');
    Route::get('/school/roles/create', [App\Http\Controllers\RoleController::class, 'create'])->name('school.roles.create');
    Route::post('/school/roles', [App\Http\Controllers\RoleController::class, 'store'])->name('school.roles.store');
    Route::get('/school/roles/{role}', [App\Http\Controllers\RoleController::class, 'show'])->name('school.roles.show');
    Route::get('/school/roles/{role}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('school.roles.edit');
    Route::put('/school/roles/{role}', [App\Http\Controllers\RoleController::class, 'update'])->name('school.roles.update');
    Route::delete('/school/roles/{role}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('school.roles.destroy');

    // API endpoint to get available permissions
    Route::get('/school/api/permissions', [App\Http\Controllers\RoleController::class, 'getAvailablePermissions'])->name('school.api.permissions');
});

// Academic features with subscription check
Route::middleware(['school.auth', 'subscription.feature:academic_sections'])->group(function () {
    // Section routes
    Route::get('/school/sections', [App\Http\Controllers\SectionController::class, 'index'])->name('school.sections');
    Route::post('/school/sections', [App\Http\Controllers\SectionController::class, 'store'])->name('school.sections.store');
    Route::get('/school/sections/{section}', [App\Http\Controllers\SectionController::class, 'show'])->name('school.sections.show');
    Route::put('/school/sections/{section}', [App\Http\Controllers\SectionController::class, 'update'])->name('school.sections.update');
    Route::delete('/school/sections/{section}', [App\Http\Controllers\SectionController::class, 'destroy'])->name('school.sections.destroy');
    Route::get('/school/api/active-sections', [App\Http\Controllers\SectionController::class, 'getActiveSections'])->name('school.api.active-sections');
    Route::post('/school/classes/{id}/assign-teacher', [App\Http\Controllers\SchoolProfileController::class, 'assignTeacher'])->name('classes.assign-teacher');
    Route::get('/school/classes-teacher', [App\Http\Controllers\SchoolProfileController::class, 'indexClassTeacher'])->name('admin.classes-teacher');
    Route::post('/school/{classId}/assign', [App\Http\Controllers\SchoolProfileController::class, 'assignClassTeacher'])->name('school.class-teachers.assign');
});



Route::middleware(['school.auth', 'subscription.feature:academic_classes'])->group(function () {
    // Class routes
    Route::get('/school/class', [App\Http\Controllers\SchoolClassController::class, 'index'])->name('school.class');
    Route::post('/school/class', [App\Http\Controllers\SchoolClassController::class, 'store'])->name('school.class.store');
    Route::get('/school/class/{class}', [App\Http\Controllers\SchoolClassController::class, 'show'])->name('school.class.show');
    Route::put('/school/class/{class}', [App\Http\Controllers\SchoolClassController::class, 'update'])->name('school.class.update');
    Route::delete('/school/class/{class}', [App\Http\Controllers\SchoolClassController::class, 'destroy'])->name('school.class.destroy');
    Route::get('/school/api/active-classes', [App\Http\Controllers\SchoolClassController::class, 'getActiveClasses'])->name('school.api.active-classes');
    Route::get('/school/class/{school_id}/{class_id}/{section_id}', [App\Http\Controllers\SchoolClassController::class, 'showStudents'])->name('school.class.students');
});

Route::middleware(['school.auth', 'subscription.feature:academic_subjects'])->group(function () {
    // Subject routes
    Route::get('/school/subjects', [App\Http\Controllers\SubjectController::class, 'index'])->name('school.subjects');
    Route::post('/school/subjects', [App\Http\Controllers\SubjectController::class, 'store'])->name('school.subjects.store');
    Route::get('/school/subjects/{subject}', [App\Http\Controllers\SubjectController::class, 'show'])->name('school.subjects.show');
    Route::put('/school/subjects/{subject}', [App\Http\Controllers\SubjectController::class, 'update'])->name('school.subjects.update');
    Route::delete('/school/subjects/{subject}', [App\Http\Controllers\SubjectController::class, 'destroy'])->name('school.subjects.destroy');
    Route::get('/school/api/active-subjects', [App\Http\Controllers\SubjectController::class, 'getActiveSubjects'])->name('school.api.active-subjects');
    Route::post('/school/subjects/assign-to-class', [App\Http\Controllers\SubjectController::class, 'assignToClass'])->name('school.subjects.assign-to-class');

    // Assign Subjects routes
    Route::get('/school/assignSubjects', [App\Http\Controllers\ClassSubjectController::class, 'index'])->name('school.assignSubjects');
    Route::post('/school/assignSubjects', [App\Http\Controllers\ClassSubjectController::class, 'store'])->name('school.assignSubjects.store');
    Route::put('/school/assignSubjects/{class}', [App\Http\Controllers\ClassSubjectController::class, 'update'])->name('school.assignSubjects.update');
    Route::delete('/school/assignSubjects/{class}', [App\Http\Controllers\ClassSubjectController::class, 'destroy'])->name('school.assignSubjects.destroy');
    Route::get('/school/class/{class}/subjects', [App\Http\Controllers\ClassSubjectController::class, 'getClassSubjects'])->name('school.class.subjects');
});

Route::middleware(['school.auth', 'subscription.feature:attendance'])->group(function () {


    Route::get('/school/attendance', [App\Http\Controllers\Client\SchoolPanel\Academics\TimeTableController::class, 'attendanceIndex'])->name('school.attendance');
    Route::get('client/school-panel/academics/attendance', [TimeTableController::class, 'attendanceIndex'])->name('client.schoolPanel.academics.attendance');


    Route::get('/school/attendance/teacher', [App\Http\Controllers\Client\SchoolPanel\Academics\TimeTableController::class, 'attendanceIndexTeacher'])->name('school.attendance.teacher');

    Route::post('/school/teacher/attendance/mark', [App\Http\Controllers\Client\SchoolPanel\Academics\TimeTableController::class, 'teacherMarkAttendance'])
        ->name('teacher.attendance.mark');

    Route::get('/school/teacher/attendance/monthly', [App\Http\Controllers\Client\SchoolPanel\Academics\TimeTableController::class, 'teacherMonthlyAttendance'])
        ->name('teacher.attendance.monthly');
});

Route::middleware(['school.auth', 'subscription.feature:timetable'])->group(function () {
    Route::get('/school/timeTable', [App\Http\Controllers\Client\SchoolPanel\Academics\TimeTableController::class, 'index'])->name('school.timeTable');
    Route::get('/school/timeTable/sections/{classId}', [App\Http\Controllers\Client\SchoolPanel\Academics\TimeTableController::class, 'getSectionsByClass'])->name('school.timeTable.sections');
    Route::post('/school/timeTable/store', [App\Http\Controllers\Client\SchoolPanel\Academics\TimeTableController::class, 'store'])->name('school.timetable.store');
    Route::get('/school/timeTable/filter', [App\Http\Controllers\Client\SchoolPanel\Academics\TimeTableController::class, 'filter'])->name('school.timetable.filter');
    Route::get('/school/timetable/period/{id}', [TimeTableController::class, 'edit'])->name('school.timetable.period.edit');
    Route::post('/school/timetable/period/{id}/update', [TimeTableController::class, 'update'])->name('school.timetable.period.update');
    Route::delete('/school//timeTable/delete', [App\Http\Controllers\Client\SchoolPanel\Academics\TimeTableController::class, 'destroy'])->name('school.timetable.destroy');
    Route::get('/school/api/active-teachers', [App\Http\Controllers\Client\SchoolPanel\Peoples\TeacherController::class, 'getActiveTeachers'])->name('school.api.active-teachers');
    Route::get('/school/api/timetable-subjects', [App\Http\Controllers\Client\SchoolPanel\Academics\TimeTableController::class, 'getSubjectsByClassAndSection'])->name('school.api.timetable-subjects');
});

Route::middleware(['school.auth', 'subscription.feature:homework'])->group(function () {
    Route::get('/school/homeWork', [App\Http\Controllers\Client\SchoolPanel\Academics\HomeworkController::class, 'index'])->name('school.homeWork');

    Route::get('/school/homeWork/{id}/detail', [App\Http\Controllers\TeacherHomeworkController::class, 'showSubmissionsAdmin'])->name('school.homeWork.sub');


    Route::get('/school/homework/filter', [App\Http\Controllers\Client\SchoolPanel\Academics\HomeworkController::class, 'filter'])->name('school.homework.filter');
    Route::post('/school/homework/store', [App\Http\Controllers\Client\SchoolPanel\Academics\HomeworkController::class, 'store'])->name('school.homework.store');
    Route::get('/school/homework/{id}', [App\Http\Controllers\Client\SchoolPanel\Academics\HomeworkController::class, 'show'])->name('school.homework.show');
    Route::put('/school/homework/{id}', [App\Http\Controllers\Client\SchoolPanel\Academics\HomeworkController::class, 'update'])->name('school.homework.update');
    Route::delete('/school/homework/{id}', [App\Http\Controllers\Client\SchoolPanel\Academics\HomeworkController::class, 'destroy'])->name('school.homework.destroy');
});

// Hostel features with subscription check
Route::middleware(['school.auth', 'subscription.feature:hostel_management'])->group(function () {
    Route::get('/school/hostelList', [App\Http\Controllers\HostelController::class, 'index'])->name('school.hostelList');
    Route::post('/school/hostelList', [App\Http\Controllers\HostelController::class, 'store'])->name('school.hostelList.store');
    Route::get('/school/hostel/{hostel}', [App\Http\Controllers\HostelController::class, 'show'])->name('school.hostel.show');
    Route::put('/school/hostel/{hostel}', [App\Http\Controllers\HostelController::class, 'update'])->name('school.hostel.update');
    Route::delete('/school/hostel/{hostel}', [App\Http\Controllers\HostelController::class, 'destroy'])->name('school.hostel.destroy');
    Route::get('/school/api/active-hostels', [App\Http\Controllers\HostelController::class, 'getActiveHostels'])->name('school.api.active-hostels');

    Route::get('/school/roomType', [App\Http\Controllers\HostelRoomTypeController::class, 'index'])->name('school.roomType');
    Route::post('/school/roomType', [App\Http\Controllers\HostelRoomTypeController::class, 'store'])->name('school.roomType.store');
    Route::get('/school/roomType/{roomType}', [App\Http\Controllers\HostelRoomTypeController::class, 'show'])->name('school.roomType.show');
    Route::put('/school/roomType/{roomType}', [App\Http\Controllers\HostelRoomTypeController::class, 'update'])->name('school.roomType.update');
    Route::delete('/school/roomType/{roomType}', [App\Http\Controllers\HostelRoomTypeController::class, 'destroy'])->name('school.roomType.destroy');
    Route::get('/school/api/active-room-types', [App\Http\Controllers\HostelRoomTypeController::class, 'getActiveRoomTypes'])->name('school.api.active-room-types');
    Route::get('/school/api/all-room-types', [App\Http\Controllers\HostelRoomTypeController::class, 'getAllRoomTypes'])->name('school.api.all-room-types');

    Route::get('/school/hostelRooms', [App\Http\Controllers\HostelRoomController::class, 'index'])->name('school.hostelRooms');
    Route::post('/school/hostelRoom', [App\Http\Controllers\HostelRoomController::class, 'store'])->name('school.hostelRoom.store');
    Route::get('/school/hostelRoom/{hostelRoom}', [App\Http\Controllers\HostelRoomController::class, 'show'])->name('school.hostelRoom.show');
    Route::put('/school/hostelRoom/{hostelRoom}', [App\Http\Controllers\HostelRoomController::class, 'update'])->name('school.hostelRoom.update');
    Route::delete('/school/hostelRoom/{hostelRoom}', [App\Http\Controllers\HostelRoomController::class, 'destroy'])->name('school.hostelRoom.destroy');
    Route::get('/school/api/all-hostel-rooms', [App\Http\Controllers\HostelRoomController::class, 'getAllHostelRooms'])->name('school.api.all-hostel-rooms');
});

// Transport features with subscription check
Route::middleware(['school.auth', 'subscription.feature:transport_management'])->group(function () {
    // Vehicle Drivers
    Route::get('/school/vehicleDrivers', [App\Http\Controllers\VehicleDriverController::class, 'index'])->name('school.vehicleDrivers');
    Route::post('/school/vehicleDriver', [App\Http\Controllers\VehicleDriverController::class, 'store'])->name('school.vehicleDriver.store');
    Route::get('/school/vehicleDriver/{vehicleDriver}', [App\Http\Controllers\VehicleDriverController::class, 'show'])->name('school.vehicleDriver.show');
    Route::put('/school/vehicleDriver/{vehicleDriver}', [App\Http\Controllers\VehicleDriverController::class, 'update'])->name('school.vehicleDriver.update');
    Route::delete('/school/vehicleDriver/{vehicleDriver}', [App\Http\Controllers\VehicleDriverController::class, 'destroy'])->name('school.vehicleDriver.destroy');
    Route::get('/school/api/all-vehicle-drivers', [App\Http\Controllers\VehicleDriverController::class, 'getAllDrivers'])->name('school.api.all-vehicle-drivers');

    // Vehicles
    Route::get('/school/vehicles', [App\Http\Controllers\VehicleController::class, 'index'])->name('school.vehicles');
    Route::post('/school/vehicle', [App\Http\Controllers\VehicleController::class, 'store'])->name('school.vehicle.store');
    Route::get('/school/vehicle/{vehicle}', [App\Http\Controllers\VehicleController::class, 'show'])->name('school.vehicle.show');
    Route::put('/school/vehicle/{vehicle}', [App\Http\Controllers\VehicleController::class, 'update'])->name('school.vehicle.update');
    Route::delete('/school/vehicle/{vehicle}', [App\Http\Controllers\VehicleController::class, 'destroy'])->name('school.vehicle.destroy');
    Route::get('/school/api/all-vehicles', [App\Http\Controllers\VehicleController::class, 'getAllVehicles'])->name('school.api.all-vehicles');

    // Routes
    Route::get('/school/routes', [App\Http\Controllers\RouteController::class, 'index'])->name('school.routes');
    Route::post('/school/route', [App\Http\Controllers\RouteController::class, 'store'])->name('school.route.store');
    Route::get('/school/route/{route}', [App\Http\Controllers\RouteController::class, 'show'])->name('school.route.show');
    Route::put('/school/route/{route}', [App\Http\Controllers\RouteController::class, 'update'])->name('school.route.update');
    Route::delete('/school/route/{route}', [App\Http\Controllers\RouteController::class, 'destroy'])->name('school.route.destroy');
    Route::get('/school/api/all-routes', [App\Http\Controllers\RouteController::class, 'getAllRoutes'])->name('school.api.all-routes');

    // Vehicle Route Assignments (NEW)
    Route::get('/school/assignVehicle', [App\Http\Controllers\RouteAssignmentController::class, 'index'])->name('school.assignVehicle');
    Route::post('/school/route/assign-vehicle', [App\Http\Controllers\RouteAssignmentController::class, 'assignVehicle'])->name('school.route.assign-vehicle');
    Route::get('/school/api/assigned-routes', [App\Http\Controllers\RouteAssignmentController::class, 'getAssignedRoutes'])->name('school.api.assigned-routes');
});

// Finance features with subscription check

// Assuming you have your standard namespace and imports here...


Route::middleware(['school.auth', 'subscription.feature:finance_management'])->group(function () {

    // 1. Index Route (GET): Displays the main view and assigned fees table
    Route::get('/collectFee', [\App\Http\Controllers\CollectFeeController::class, 'index'])->name('school.collectFee');
    // Add this route to your existing collect fee routes
    Route::post('/collectFee/pay/{feeId}', [\App\Http\Controllers\CollectFeeController::class, 'payFee'])->name('collectFee.pay');

    // 2. Filter Fee Masters (GET): AJAX for filtering fee types in the modal
    Route::get('/collect-fees/filter-fee-masters', [\App\Http\Controllers\CollectFeeController::class, 'filterFeeMasters'])->name('school.assignFee.filterFeeMasters');

    // 3. Filter Students (GET): AJAX for filtering students in the modal
    Route::get('/collect-fees/filter-students', [\App\Http\Controllers\CollectFeeController::class, 'filterStudents'])->name('school.assignFee.filterStudents');

    // 4. Store (POST): Handles assigning selected fees to selected students
    Route::post('/school/assign-fees', [\App\Http\Controllers\CollectFeeController::class, 'store'])->name('school.assignFee.store');

    // 5. Edit (GET): Fetches single assignment data for the edit modal
    Route::get('/school/assign-fees/edit/{id}', [\App\Http\Controllers\CollectFeeController::class, 'edit'])->name('school.assignFee.edit');

    // 6. Update (PUT/PATCH): Saves changes from the edit modal
    Route::put('/school/assign-fees/{id}', [\App\Http\Controllers\CollectFeeController::class, 'update'])->name('school.assignFee.update');

    // 7. Destroy (DELETE): Deletes a fee assignment
    Route::delete('/school/assign-fees/{id}', [\App\Http\Controllers\CollectFeeController::class, 'destroy'])->name('school.assignFee.destroy');




    // Asign Fee

    // Route::get('/school/assignFee', function () {
    //     return view('client.schoolPanel.finance.assignFee');
    // })->name('school.assignFee');
    // Route::get('/school/assignFee', [AssignFeeController::class, 'index'])->name('school.assignFee');
    Route::prefix('school')->group(function () {
        Route::get('/assign-fees', [AssignFeeController::class, 'index'])->name('school.assignFee.index');
        Route::post('/assign-fees', [AssignFeeController::class, 'store'])->name('school.assignFee.store');
        Route::get('/assign-fees/edit/{id}', [AssignFeeController::class, 'edit'])->name('school.assignFee.edit');
        Route::put('/assign-fees/{id}', [AssignFeeController::class, 'update'])->name('school.assignFee.update');
        Route::delete('/assign-fees/{id}', [AssignFeeController::class, 'destroy'])->name('school.assignFee.destroy');

        // New API routes for filtering
        Route::get('/assign-fees/filter/students', [AssignFeeController::class, 'getFilteredStudents'])->name('school.assignFee.filterStudents');
        Route::get('/assign-fees/filter/fee-masters', [AssignFeeController::class, 'getFilteredFeeMasters'])->name('school.assignFee.filterFeeMasters');
    });


    // Fee Group routes
    Route::get('/school/feeGroup', [App\Http\Controllers\FeeGroupController::class, 'index'])->name('school.feeGroup');
    Route::post('/school/feeGroup', [App\Http\Controllers\FeeGroupController::class, 'store'])->name('school.feeGroup.store');
    Route::get('/school/feeGroup/{feeGroup}', [App\Http\Controllers\FeeGroupController::class, 'show'])->name('school.feeGroup.show');
    Route::put('/school/feeGroup/{feeGroup}', [App\Http\Controllers\FeeGroupController::class, 'update'])->name('school.feeGroup.update');
    Route::delete('/school/feeGroup/{feeGroup}', [App\Http\Controllers\FeeGroupController::class, 'destroy'])->name('school.feeGroup.destroy');
    Route::get('/school/api/all-fee-groups', [App\Http\Controllers\FeeGroupController::class, 'getAllFeeGroups'])->name('school.api.all-fee-groups');
    Route::get('/get-fee-types/{groupId}', [App\Http\Controllers\FeeTypeController::class, 'getFeeTypes'])->name('school.api.getFeeTypes');

    // Fee Type routes

    // Route::get('', [App\Http\Controllers\FeeTypeController::class, 'index'])->name('school.feeType');


    Route::prefix('/school/feeType')->name('fee-types.')->group(function () {
        Route::get('/', [App\Http\Controllers\FeeTypeController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\FeeTypeController::class, 'storeFeeType'])->name('fee-types.store');
        Route::put('/school-panel/fees/fee-type/{id}', [FeeTypeController::class, 'updateFeeType'])->name('client.schoolPanel.feeType.update');
        Route::get('/{feeType}', [App\Http\Controllers\FeeTypeController::class, 'destroy'])->name('destroy');

        Route::post('/bulk-update', [App\Http\Controllers\FeeTypeController::class, 'bulkUpdate'])->name('bulk-update');
        Route::get('/export', [App\Http\Controllers\FeeTypeController::class, 'export'])->name('export');
        Route::post('/import', [App\Http\Controllers\FeeTypeController::class, 'import'])->name('import');
    });


    Route::get('/school/feeMaster', [App\Http\Controllers\FeeTypeController::class, 'indexFeeMaster'])->name('school.feeMaster');
    Route::get('/school/fee-masters', [FeeTypeController::class, 'list'])->name('fee-master.list');
    Route::post('/school/fee-masters', [FeeTypeController::class, 'store'])->name('fee-master.store');
    Route::get('/school/fee-masters/{feeMaster}', [FeeTypeController::class, 'show'])->name('fee-master.show');
    Route::put('/school/fee-type/{feeType}', [FeeTypeController::class, 'update'])->name('fee-type.update');
    Route::put('/school/fee-masters/{feeMaster}', [FeeTypeController::class, 'feeMasterUpdate'])->name('fee-master.update');
    // Route::delete('/school/fee-masters/{feeMaster}', [FeeTypeController::class, 'destroy'])->name('fee-master.destroy');
    Route::delete('/school/fee-Type/{id}', [FeeTypeController::class, 'destroy'])
        ->name('fee-type.destroy');

    Route::delete('/school/fee-masters/{id}', [FeeTypeController::class, 'feeMasterDestroy'])
        ->name('fee-master.destroy');

    // Route::get('/school/accountDetail', function () {
    //     return view('client.schoolPanel.finance.accountDetail');
    // })->name('school.accountDetail');
    Route::resource('accounts', AccountsController::class)
        ->names('school.accountDetail')->except(['show']);

    Route::get('/accounts/featured/{id}', [AccountsController::class, 'makeFeatured'])
     ->name('school.accountDetail.featured');

});



Route::get('/school/expenses', function () {
    return view('client.schoolPanel.accounts.expenses');
})->name('school.expenses');

Route::get('/school/expenseCategory', function () {
    return view('client.schoolPanel.accounts.expenseCategory');
})->name('school.expenseCategory');

Route::get('/school/invoiceView', function () {
    return view('client.schoolPanel.accounts.invoiceView');
})->name('school.invoiceView');

Route::get('/school/transactions', function () {
    return view('client.schoolPanel.accounts.transactions');
})->name('school.transactions');


Route::get('/school/students', [App\Http\Controllers\Client\SchoolPanel\Peoples\StudentController::class, 'index'])->name('school.students');
Route::get('/school/teachers', [App\Http\Controllers\Client\SchoolPanel\Peoples\TeacherController::class, 'index'])->name('school.teachers');

Route::put('/school/teachers-update/{id}', [App\Http\Controllers\Client\SchoolPanel\Peoples\TeacherController::class, 'updateWeb'])->name('school.teachers.update1');
Route::get('/school/createStudent', function () {
    return view('client.schoolPanel.peoples.student.createStudent');
})->name('school.createStudent');

Route::get('/school/showStudent', function () {
    // Redirect to students list since no specific student was selected
    return redirect()->route('school.students');
})->name('school.showStudent');


Route::get('/school/createTeacher', [App\Http\Controllers\Client\SchoolPanel\Peoples\TeacherController::class, 'create'])->name('school.createTeacher');

Route::get('/school/staffs', function () {
    return view('client.schoolPanel.hrm.staffs');
})->name('school.staffs');

Route::get('/school/hrmDepartments', function () {
    return view('client.schoolPanel.hrm.hrmDepartments');
})->name('school.hrmDepartments');

Route::get('/school/hrmDesignation', function () {
    return view('client.schoolPanel.hrm.hrmDesignation');
})->name('school.hrmDesignation');

Route::get('/school/exams', function () {
    return view('client.schoolPanel.examinations.exams');
})->name('school.exams');


// Grade and exam
Route::prefix('school')->group(function () {
    Route::get('/grades', [App\Http\Controllers\GradeController::class, 'index'])->name('school.grades');
    Route::post('/grades', [App\Http\Controllers\GradeController::class, 'store'])->name('grades.store');
    Route::post('/grades/{grade}', [App\Http\Controllers\GradeController::class, 'update'])->name('grades.update');
    Route::delete('/grades/{grade}', [App\Http\Controllers\GradeController::class, 'destroy'])->name('grades.destroy');
    Route::get('/result', [App\Http\Controllers\GradeController::class, 'showResultPageForAdmin'])->name('school.result');
    Route::get('/exam-results/fetch', [App\Http\Controllers\GradeController::class, 'fetchResults'])->name('school.exam-results.fetch');
    Route::get('/school/get-sections/{classId}', [GradeController::class, 'getSections'])->name('school.getSections');
});



Route::get('/school/books', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'index'])->name('school.books.index');
Route::post('/school/books', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'store'])->name('school.books.store');
Route::put('/school/books/{id}', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'update'])->name('school.books.update');
Route::delete('/school/books/{id}', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'destroy'])->name('school.books.destroy');

// Student details fetch for library
Route::get('/school/library/fetch-student/{student_id}', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'fetchStudent'])->name('school.library.fetchStudent');

// Book details fetch for library
Route::get('/school/library/fetch-book/{book_id}', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'fetchBook'])->name('school.library.fetchBook');

// Issued Books routes
Route::get('/school/issueBooks', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'issuedBooks'])->name('school.issueBooks');
Route::post('/school/issueBook', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'issueBook'])->name('school.issueBook.store');
Route::get('/school/issuedBooks/{id}', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'showIssuedBook'])->name('school.issuedBook.show');
Route::get('/school/issuedBooks/{id}/edit', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'editIssuedBook'])->name('school.issuedBook.edit');
Route::put('/school/issuedBooks/{id}', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'updateIssuedBook'])->name('school.issuedBook.update');
Route::post('/school/issuedBooks/{id}/return', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'returnBook'])->name('school.issuedBook.return');
Route::post('/issue-books/{id}/lost', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'markAsLost'])->name('school.issuedBook.lost');


// Return Books route
Route::get('/school/returnBooks', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'returnBooks'])->name('school.returnBooks');

// Student issued books
Route::get('/school/students/{id}/issued-books', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'getStudentIssuedBooks'])->name('school.students.issuedBooks');
Route::get('/school/students/{id}/class', [App\Http\Controllers\Client\SchoolPanel\Peoples\StudentController::class, 'getStudentClass'])->name('school.students.class');

// debug();
// Route::get('/db-test', function () {
//     try {
//         $plan = new App\Models\Plan();
//         $plan->name = 'Test Plan ' . time();
//         $plan->description = 'Testing database connection';
//         $plan->price = 9.99;
//         $plan->billing_cycle = 'monthly';
//         $plan->max_students = 10;
//         $plan->max_teachers = 5;
//         $plan->max_staff = 2;
//         $plan->is_popular = false;
//         $plan->is_active = true;
//         $plan->save();

//         return 'Database connection successful! Created test plan with ID: ' . $plan->id;
//     } catch (\Exception $e) {
//         return 'Database error: ' . $e->getMessage();
//     }
// });

// Add debug route
// Route::get('/debug/plan-subscriptions', function () {
//     $plans = \App\Models\Plan::all();
//     $subscriptions = \App\Models\SchoolSubscription::all();

//     // Count subscriptions per plan
//     $planCounts = [];
//     foreach ($subscriptions as $sub) {
//         if (!isset($planCounts[$sub->plan_id])) {
//             $planCounts[$sub->plan_id] = 0;
//         }
//         if ($sub->status === 'active') {
//             $planCounts[$sub->plan_id]++;
//         }
//     }

//     return [
//         'plans' => $plans->map(function ($plan) use ($planCounts) {
//             return [
//                 'id' => $plan->id,
//                 'name' => $plan->name,
//                 'active' => $plan->is_active,
//                 'subscriptions' => $planCounts[$plan->id] ?? 0
//             ];
//         }),
//         'subscriptions' => $subscriptions->map(function ($sub) {
//             return [
//                 'id' => $sub->id,
//                 'plan_id' => $sub->plan_id,
//                 'school_id' => $sub->school_id,
//                 'status' => $sub->status
//             ];
//         })
//     ];
// });

// Add direct debug route for plans
Route::get('/debug-plans', function () {
    $plans = \App\Models\Plan::all();

    return [
        'count' => $plans->count(),
        'plans' => $plans->map(function ($plan) {
            return [
                'id' => $plan->id,
                'name' => $plan->name,
                'description' => $plan->description,
                'price' => $plan->price,
                'is_active' => $plan->is_active,
                'max_students' => $plan->max_students,
                'max_teachers' => $plan->max_teachers,
                'max_staff' => $plan->max_staff,
            ];
        })
    ];
});

// Temporary debug route for direct SaaS admin plans access
Route::get('/debug-saas-plans', function () {
    $plans = \App\Models\Plan::all();

    // Direct SQL approach to count active users per plan
    $planCounts = \Illuminate\Support\Facades\DB::table('school_subscriptions')
        ->select('plan_id', \Illuminate\Support\Facades\DB::raw('COUNT(DISTINCT school_id) as user_count'))
        ->where('status', 'active')
        ->groupBy('plan_id')
        ->pluck('user_count', 'plan_id')
        ->toArray();

    // Assign the counts to each plan
    foreach ($plans as $plan) {
        $plan->active_subscriptions_count = $planCounts[$plan->id] ?? 0;
    }

    $subscriptions = \App\Models\SchoolSubscription::with(['school', 'plan'])
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();

    return view('saasAdmin.plans.index', compact('plans', 'subscriptions'));
});

// Comprehensive database debug route
Route::get('/debug-database', function () {
    return [
        'plans' => \App\Models\Plan::all(),
        'features' => \App\Models\Feature::all(),
        'schools' => \App\Models\School::all(),
        'users' => \App\Models\User::all(),
        'subscriptions' => \App\Models\SchoolSubscription::all(),
        'plan_features' => \Illuminate\Support\Facades\DB::table('plan_features')->get(),
    ];
});

// Direct HTML display of plans and subscriptions
Route::get('/view-plans', function () {
    $plans = \App\Models\Plan::all();

    // Use raw SQL for consistency with PlanController
    $subscriptionCountsSQL = "
            SELECT plan_id, COUNT(*) as user_count 
            FROM school_subscriptions 
            WHERE status = 'active' 
            GROUP BY plan_id
        ";

    $planCountsRaw = \Illuminate\Support\Facades\DB::select($subscriptionCountsSQL);

    // Convert to associative array
    $subscriptionCounts = [];
    foreach ($planCountsRaw as $row) {
        $subscriptionCounts[$row->plan_id] = $row->user_count;
    }

    // Also get all subscriptions for debugging
    $allSubscriptions = \App\Models\SchoolSubscription::all();

    return view('debug.plans', [
        'plans' => $plans,
        'subscriptionCounts' => $subscriptionCounts,
        'allSubscriptions' => $allSubscriptions
    ]);
});

// Test route for modals
Route::get('/test-modal', function () {
    return view('client.schoolPanel.generalSettings.test-modal');
})->name('test.modal');

// School Panel - Role Management Routes
Route::middleware(['school.auth', 'school.admin'])->prefix('school')->name('school.')->group(function () {
    // ... existing routes ...

    // Role Management Routes
    Route::get('/roles-and-permissions', [RoleController::class, 'index'])->name('rolesAndPermissions');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // User Management Routes
    Route::get('/users', [UserManagementController::class, 'index'])->name('roleUsers');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserManagementController::class, 'show'])->name('users.show');
    Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.resetPassword');
});

// Fee Group Routes
Route::middleware(['auth', 'role:school'])->group(function () {
    // Fee Group
    Route::get('/fee-groups', [FeeGroupController::class, 'index'])->name('fee-groups.index');
    Route::post('/fee-groups', [FeeGroupController::class, 'store']);
    Route::get('/fee-groups/{feeGroup}', [FeeGroupController::class, 'show']);
    Route::put('/fee-groups/{feeGroup}', [FeeGroupController::class, 'update']);
    Route::delete('/fee-groups/{feeGroup}', [FeeGroupController::class, 'destroy']);

    // Fee Types
    Route::get('/fee-types', [FeeTypeController::class, 'index'])->name('fee-types');
    Route::post('/fee-types', [FeeTypeController::class, 'storeFeeType']);
    Route::put('/fee-types/{id}', [FeeTypeController::class, 'update']);
    Route::delete('/fee-types/{id}', [FeeTypeController::class, 'destroy']);
    Route::post('/fee-types/bulk-update', [FeeTypeController::class, 'bulkUpdateStatus'])->name('fee-types.bulk-update');
    Route::get('/fee-types/export', [FeeTypeController::class, 'exportCsv'])->name('fee-types.export');
    Route::post('/fee-types/import', [FeeTypeController::class, 'importCsv'])->name('fee-types.import');
});

// API Routes for AJAX
Route::middleware(['auth', 'role:school'])->prefix('api')->group(function () {
    Route::get('/fee-groups', [FeeGroupController::class, 'getAllFeeGroups']);
    Route::get('/fee-types', [FeeTypeController::class, 'getAllFeeTypes']);
    Route::get('/fee-types/active', [FeeTypeController::class, 'getActiveFeeTypes']);
    Route::get('/fee-types/by-group/{groupId}', [FeeTypeController::class, 'getFeeTypesByGroup']);
});

// Diagnostic routes
Route::middleware(['auth'])->group(function () {
    Route::get('/fee-types/diagnostic', [FeeTypeController::class, 'diagnostic']);
});

// Student routes with subscription check
Route::middleware(['school.auth', 'subscription.feature:student_management'])->group(function () {
    // Route::get('/school/students', [App\Http\Controllers\StudentController::class, 'index'])->name('school.students');
    // Route::get('/school/createStudent', [App\Http\Controllers\StudentController::class, 'create'])->name('school.createStudent');
    // Route::post('/school/students', [App\Http\Controllers\StudentController::class, 'store'])->name('school.students.store');
    // Route::get('/school/students/{id}', [App\Http\Controllers\StudentController::class, 'show'])->name('school.students.show');
    // Route::get('/school/students/{id}/edit', [App\Http\Controllers\StudentController::class, 'edit'])->name('school.students.edit');
    // Route::put('/school/students/{id}', [App\Http\Controllers\StudentController::class, 'update'])->name('school.students.update');
    // Route::delete('/school/students/{id}', [App\Http\Controllers\Client\SchoolPanel\Peoples\StudentController::class, 'destroy'])->name('school.students.destroy');
    Route::post('/school/students/{id}/reset-password', [App\Http\Controllers\Client\SchoolPanel\Peoples\StudentController::class, 'resetPassword'])->name('school.students.reset-password');
    Route::post('/school/students/update-password', [App\Http\Controllers\Client\SchoolPanel\Peoples\StudentController::class, 'updatePassword'])->name('school.students.update-password');

    // Custom routes for our namespace controller
    Route::get('/school/peoples/students/{id}/edit', [App\Http\Controllers\Client\SchoolPanel\Peoples\StudentController::class, 'edit'])->name('school.peoples.students.edit');
    Route::put('/school/peoples/students/{id}', [App\Http\Controllers\Client\SchoolPanel\Peoples\StudentController::class, 'update'])->name('school.peoples.students.update');
    Route::get('/school/peoples/students/{id}/show', [App\Http\Controllers\Client\SchoolPanel\Peoples\StudentController::class, 'show'])->name('school.peoples.students.show');
    Route::get('/student/{id}/fees-pdf', [StudentController::class, 'generateFeesPdf'])
        ->name('school.student.feesPdf');

    Route::get('/school/peoples/students/{id}/document/{document_type}', [App\Http\Controllers\Client\SchoolPanel\Peoples\StudentController::class, 'downloadDocument'])->name('school.peoples.students.document');
});

// API routes for student form without feature checks (for dropdowns, etc.)
Route::middleware(['school.auth'])->group(function () {
    // Transport API endpoints
    Route::get('/school/api/all-routes', [App\Http\Controllers\RouteController::class, 'getAllRoutes'])->name('school.api.all-routes');
    Route::get('/school/api/all-vehicles', [App\Http\Controllers\VehicleController::class, 'getAllVehicles'])->name('school.api.all-vehicles');

    // Hostel API endpoints
    Route::get('/school/api/active-hostels', [App\Http\Controllers\HostelController::class, 'getActiveHostels'])->name('school.api.active-hostels');
    Route::get('/school/api/all-hostel-rooms', [App\Http\Controllers\HostelRoomController::class, 'getAllHostelRooms'])->name('school.api.all-hostel-rooms');
});

// Student store route moved to the subscription middleware group above
// Route::post('/school/peoples/students/store', [StudentController::class, 'store'])->name('school.student.store');

Route::post('/school/teachers', [App\Http\Controllers\Client\SchoolPanel\Peoples\TeacherController::class, 'store'])->name('school.teachers.store');
Route::get('/school/teachers/{id}/edit', [App\Http\Controllers\Client\SchoolPanel\Peoples\TeacherController::class, 'edit'])->name('school.teachers.edit');
Route::get('/school/teachers/{id}', [App\Http\Controllers\Client\SchoolPanel\Peoples\TeacherController::class, 'show'])->name('school.teachers.show');
Route::put('/school/teachers/{id}', [App\Http\Controllers\Client\SchoolPanel\Peoples\TeacherController::class, 'update'])->name('school.teachers.update');
Route::delete('/school/teachers/{id}', [App\Http\Controllers\Client\SchoolPanel\Peoples\TeacherController::class, 'destroy'])->name('school.teachers.destroy');
Route::post('/school/teachers/{id}/reset-password', [App\Http\Controllers\Client\SchoolPanel\Peoples\TeacherController::class, 'resetPassword'])->name('school.teachers.reset-password');
Route::post('/school/teachers/{id}/toggle-status', [App\Http\Controllers\Client\SchoolPanel\Peoples\TeacherController::class, 'toggleStatus'])->name('school.teachers.toggle-status');

// Student routes
Route::get('/school/peoples/students', [StudentController::class, 'index'])->name('school.students');
Route::get('/school/peoples/students/create', [StudentController::class, 'create'])->name('school.createStudent');
Route::post('/school/peoples/students', [StudentController::class, 'store'])->name('school.students.store');
Route::get('/school/peoples/students/{id}', [StudentController::class, 'show'])->name('school.students.show');
Route::get('/school/peoples/students/{id}/edit', [StudentController::class, 'edit'])->name('school.students.edit');
Route::put('/school/peoples/students/{id}', [StudentController::class, 'update'])->name('school.students.update');
Route::delete('/school/peoples/students/{id}', [StudentController::class, 'destroy'])->name('school.students.destroy');
Route::post('/school/peoples/students/{id}/reset-password', [StudentController::class, 'resetPassword'])->name('school.students.reset-password');

// Library management routes
Route::middleware(['school.auth', 'subscription.feature:library_management'])->group(function () {
    Route::get('/school/books', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'index'])->name('school.books.index');
    Route::post('/school/books', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'store'])->name('school.books.store');
    Route::put('/school/books/{id}', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'update'])->name('school.books.update');
    Route::delete('/school/books/{id}', [App\Http\Controllers\Client\SchoolPanel\LibraryController::class, 'destroy'])->name('school.books.destroy');
});

// Complaint Box routes
Route::middleware(['school.auth'])->group(function () {
    Route::get('/school/complaintBox', [App\Http\Controllers\Client\SchoolPanel\ComplaintBoxController::class, 'index'])->name('school.complaintBox');
    Route::get('/school/complaintBox/{id}', [App\Http\Controllers\Client\SchoolPanel\ComplaintBoxController::class, 'show'])->name('school.complaintBox.show');
    Route::post('/school/complaintBox/{id}/status', [App\Http\Controllers\Client\SchoolPanel\ComplaintBoxController::class, 'updateStatus'])->name('school.complaintBox.updateStatus');
});

// Leave Application routes
Route::middleware(['school.auth'])->group(function () {
    Route::get('/school/leaveApplications', [App\Http\Controllers\Client\SchoolPanel\LeaveApplicationController::class, 'index'])->name('school.leaveApplications');
    Route::get('/school/leaveApplications/{id}', [App\Http\Controllers\Client\SchoolPanel\LeaveApplicationController::class, 'show'])->name('school.leaveApplications.show');
    Route::post('/school/leaveApplications/{id}/status', [App\Http\Controllers\Client\SchoolPanel\LeaveApplicationController::class, 'updateStatus'])->name('school.leaveApplications.updateStatus');
});

// Test route to create a student leave directly (for debugging)
Route::get('/test-leave-creation', function () {
    $student = \App\Models\Student::where('school_id', 5)->first();

    if (!$student) {
        return "No student found for school_id 5. Please create a student first.";
    }

    try {
        $leaveId = \App\Models\StudentLeave::generateLeaveId();

        $leave = \App\Models\StudentLeave::create([
            'school_id' => 5,
            'student_id' => $student->id,
            'leave_id' => $leaveId,
            'reason' => 'Test Leave',
            'description' => 'Testing direct leave creation through web route',
            'from_date' => now()->addDays(1),
            'to_date' => now()->addDays(3),
            'status' => 'pending',
        ]);

        return "Leave created successfully! ID: " . $leave->leave_id;
    } catch (\Exception $e) {
        return "Error creating leave: " . $e->getMessage();
    }
});

// Help and Support routes
Route::middleware(['school.auth'])->group(function () {
    Route::get('/school/helpSupport', [App\Http\Controllers\Client\SchoolPanel\HelpSupportController::class, 'index'])->name('school.helpSupport');
    Route::post('/school/helpSupport', [App\Http\Controllers\Client\SchoolPanel\HelpSupportController::class, 'update'])->name('school.helpSupport.update');
});

// Terms and Conditions routes
Route::middleware(['school.auth'])->group(function () {
    Route::get('/school/termsCondition', [App\Http\Controllers\Client\SchoolPanel\TermsConditionController::class, 'index'])->name('school.termsCondition');
    Route::post('/school/termsCondition', [App\Http\Controllers\Client\SchoolPanel\TermsConditionController::class, 'update'])->name('school.termsCondition.update');
    Route::post('/school/termsCondition/upload', [App\Http\Controllers\Client\SchoolPanel\TermsConditionController::class, 'upload'])->name('school.termsCondition.upload');
});

// Help and Support API route - public access
Route::get('/api/help-support/{schoolId}', [App\Http\Controllers\Client\SchoolPanel\HelpSupportController::class, 'getApi'])->name('api.helpSupport');

// School Media routes
Route::middleware(['school.auth'])->group(function () {
    Route::get('/school/media', [App\Http\Controllers\Client\SchoolPanel\MediaController::class, 'index'])->name('school.media.index');
    Route::get('/school/media/create', [App\Http\Controllers\Client\SchoolPanel\MediaController::class, 'create'])->name('school.media.create');
    Route::post('/school/media', [App\Http\Controllers\Client\SchoolPanel\MediaController::class, 'store'])->name('school.media.store');
    Route::get('/school/media/{id}', [App\Http\Controllers\Client\SchoolPanel\MediaController::class, 'show'])->name('school.media.show');
    Route::get('/school/media/{id}/edit', [App\Http\Controllers\Client\SchoolPanel\MediaController::class, 'edit'])->name('school.media.edit');
    Route::put('/school/media/{id}', [App\Http\Controllers\Client\SchoolPanel\MediaController::class, 'update'])->name('school.media.update');
    Route::delete('/school/media/{id}', [App\Http\Controllers\Client\SchoolPanel\MediaController::class, 'destroy'])->name('school.media.destroy');
    Route::post('/school/media/{id}/toggle-featured', [App\Http\Controllers\Client\SchoolPanel\MediaController::class, 'toggleFeatured'])->name('school.media.toggleFeatured');
});

// Programs and Events routes
Route::middleware(['school.auth', 'subscription.feature:programs_events'])->group(function () {
    // Programs routes
    Route::get('/school/programs', [App\Http\Controllers\Client\SchoolPanel\ProgramController::class, 'index'])->name('school.programs.index');
    Route::get('/school/programs/create', [App\Http\Controllers\Client\SchoolPanel\ProgramController::class, 'create'])->name('school.programs.create');
    Route::post('/school/programs', [App\Http\Controllers\Client\SchoolPanel\ProgramController::class, 'store'])->name('school.programs.store');
    Route::get('/school/programs/{program}', [App\Http\Controllers\Client\SchoolPanel\ProgramController::class, 'show'])->name('school.programs.show');
    Route::get('/school/programs/{program}/edit', [App\Http\Controllers\Client\SchoolPanel\ProgramController::class, 'edit'])->name('school.programs.edit');
    Route::put('/school/programs/{program}', [App\Http\Controllers\Client\SchoolPanel\ProgramController::class, 'update'])->name('school.programs.update');
    Route::delete('/school/programs/{program}', [App\Http\Controllers\Client\SchoolPanel\ProgramController::class, 'destroy'])->name('school.programs.destroy');

    // Events routes
    Route::get('/school/events', [App\Http\Controllers\Client\SchoolPanel\EventController::class, 'index'])->name('school.events.index');
    Route::get('/school/events/create', [App\Http\Controllers\Client\SchoolPanel\EventController::class, 'create'])->name('school.events.create');
    Route::post('/school/events', [App\Http\Controllers\Client\SchoolPanel\EventController::class, 'store'])->name('school.events.store');
    Route::get('/school/events/{event}', [App\Http\Controllers\Client\SchoolPanel\EventController::class, 'show'])->name('school.events.show');
    Route::get('/school/events/{event}/edit', [App\Http\Controllers\Client\SchoolPanel\EventController::class, 'edit'])->name('school.events.edit');
    Route::put('/school/events/{event}', [App\Http\Controllers\Client\SchoolPanel\EventController::class, 'update'])->name('school.events.update');
    Route::delete('/school/events/{event}', [App\Http\Controllers\Client\SchoolPanel\EventController::class, 'destroy'])->name('school.events.destroy');
});

// Debug route to check features
Route::middleware(['school.auth'])->get('/debug/school-features', function () {
    $school = auth()->user()->school;
    $subscription = \App\Models\SchoolSubscription::where('school_id', $school->id)
        ->where('status', 'active')
        ->first();

    if (!$subscription) {
        return [
            'status' => 'error',
            'message' => 'No active subscription found'
        ];
    }

    $plan = \App\Models\Plan::find($subscription->plan_id);

    if (!$plan) {
        return [
            'status' => 'error',
            'message' => 'Plan not found'
        ];
    }

    $features = $plan->features()->get();
    $programsEventsFeature = $features->where('code', 'programs_events')->first();

    return [
        'status' => 'success',
        'school' => $school->name,
        'plan' => $plan->name,
        'features' => $features->pluck('name', 'code'),
        'has_programs_events' => $programsEventsFeature ? true : false,
        'programs_events_feature' => $programsEventsFeature,
    ];
});

// School Panel Routes
Route::middleware(['auth', 'school.auth', 'subscription.feature:programs_events'])->prefix('school')->name('school.')->group(function () {
    // ... existing routes ...

    // Calendar Routes
    Route::get('/calendar', [App\Http\Controllers\Client\SchoolPanel\CalendarController::class, 'index'])->name('calendar.index');
    Route::post('/calendar/holidays', [App\Http\Controllers\Client\SchoolPanel\CalendarController::class, 'storeHoliday'])->name('calendar.holidays.store');
    Route::put('/calendar/holidays/{id}', [App\Http\Controllers\Client\SchoolPanel\CalendarController::class, 'updateHoliday'])->name('calendar.holidays.update');
    Route::delete('/calendar/holidays/{id}', [App\Http\Controllers\Client\SchoolPanel\CalendarController::class, 'deleteHoliday'])->name('calendar.holidays.delete');

    // ... existing routes ...
});

// Temporary route for debugging - create test complaint
Route::get('/student/create-test-complaint', function () {
    $student = \App\Models\Student::findOrFail(\Illuminate\Support\Facades\Session::get('student_id'));

    // Create a test complaint
    $complaintData = [
        'school_id' => $student->school_id,
        'student_id' => $student->id,
        'complaint_id' => 'TEST-' . time(),
        'nature' => 'Test Issue',
        'description' => 'This is a test complaint to verify functionality',
        'status' => 'Pending'
    ];

    $complaint = \App\Models\Complaint::create($complaintData);

    return redirect()->route('student.dashboard')
        ->with('success', 'Test complaint created successfully with ID: ' . $complaint->complaint_id);
})->name('student.create-test-complaint');

// Temporary route for testing complaints
Route::get('/test-complaint', function () {
    try {
        $student = \App\Models\Student::findOrFail(\Illuminate\Support\Facades\Session::get('student_id'));

        echo "Student ID: " . $student->student_id . "<br>";
        echo "School ID: " . $student->school_id . "<br><br>";

        // Get existing complaints
        $existingComplaints = \App\Models\Complaint::where('student_id', $student->student_id)->get();

        echo "Existing complaints: " . $existingComplaints->count() . "<br><br>";

        foreach ($existingComplaints as $c) {
            echo "- " . $c->complaint_id . " | " . $c->nature . " | " . $c->status . "<br>";
        }

        echo "<br>Creating test complaint...<br>";

        // Create a test complaint
        $complaintData = [
            'school_id' => $student->school_id,
            'student_id' => $student->student_id,
            'complaint_id' => 'TEST-' . time(),
            'nature' => 'Test Issue',
            'description' => 'This is a test complaint to verify functionality',
            'status' => 'pending'
        ];

        $complaint = \App\Models\Complaint::create($complaintData);

        echo "Test complaint created with ID: " . $complaint->complaint_id;
        echo "<br><br><a href='/student/complaints'>Go to complaints page</a>";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage();
    }
});

// Test route to create complaint
Route::get('/create-test-complaint', function () {
    // Get the current student
    $student = \App\Models\Student::findOrFail(\Illuminate\Support\Facades\Session::get('student_id'));

    echo "<h3>Student Info</h3>";
    echo "ID (Primary Key): " . $student->id . "<br>";
    echo "Student ID (String): " . $student->student_id . "<br>";
    echo "School ID: " . $student->school_id . "<br><br>";

    try {
        // Create a complaint using the correct ID field
        $complaint = new \App\Models\Complaint();
        $complaint->school_id = $student->school_id;
        $complaint->student_id = $student->id; // Use primary key, not student_id string
        $complaint->complaint_id = 'TEST-' . time();
        $complaint->nature = 'Test Issue';
        $complaint->description = 'This is a test complaint to debug the issue';
        $complaint->status = 'pending';
        $complaint->save();

        echo "<h3>Complaint Created Successfully</h3>";
        echo "Complaint ID: " . $complaint->complaint_id . "<br>";
        echo "Database ID: " . $complaint->id . "<br><br>";

        // Get all complaints for this student
        $complaints = \App\Models\Complaint::where('student_id', $student->id)->get();

        echo "<h3>All Complaints for This Student</h3>";
        echo "Count: " . $complaints->count() . "<br><br>";

        if ($complaints->count() > 0) {
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Complaint ID</th><th>Nature</th><th>Status</th><th>Created</th></tr>";

            foreach ($complaints as $c) {
                echo "<tr>";
                echo "<td>" . $c->id . "</td>";
                echo "<td>" . $c->complaint_id . "</td>";
                echo "<td>" . $c->nature . "</td>";
                echo "<td>" . $c->status . "</td>";
                echo "<td>" . $c->created_at . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        }

        echo "<br><a href='/student/complaints'>Go to Complaints Page</a>";
    } catch (\Exception $e) {
        echo "<h3>Error</h3>";
        echo "Message: " . $e->getMessage() . "<br>";
        echo "File: " . $e->getFile() . " (Line " . $e->getLine() . ")<br>";
    }
});

// Fix complaints route
Route::get('/fix-complaints', function () {
    try {
        // Get current student
        $student = \App\Models\Student::findOrFail(\Illuminate\Support\Facades\Session::get('student_id'));

        echo "<h2>Student Information</h2>";
        echo "ID: " . $student->id . "<br>";
        echo "Student ID: " . $student->student_id . "<br>";
        echo "School ID: " . $student->school_id . "<br><br>";

        // Check if any complaints exist
        $existingComplaints = \App\Models\Complaint::all();
        echo "<h3>All Complaints in Database: " . $existingComplaints->count() . "</h3>";

        if ($existingComplaints->count() > 0) {
            echo "<table border='1' cellpadding='4'>";
            echo "<tr><th>ID</th><th>School ID</th><th>Student ID</th><th>Complaint ID</th><th>Status</th></tr>";

            foreach ($existingComplaints as $c) {
                echo "<tr>";
                echo "<td>" . $c->id . "</td>";
                echo "<td>" . $c->school_id . "</td>";
                echo "<td>" . $c->student_id . "</td>";
                echo "<td>" . $c->complaint_id . "</td>";
                echo "<td>" . $c->status . "</td>";
                echo "</tr>";
            }

            echo "</table><br>";
        }

        // Get complaints for this student
        $studentComplaints = \App\Models\Complaint::where('student_id', $student->id)->get();
        echo "<h3>Complaints for Current Student: " . $studentComplaints->count() . "</h3>";

        // Create a new complaint for this student
        echo "<h3>Creating New Test Complaint</h3>";

        $complaintData = [
            'school_id' => $student->school_id,
            'student_id' => $student->id, // Using primary key ID
            'complaint_id' => 'TEST-' . time(),
            'nature' => 'Test Issue',
            'description' => 'This is a test complaint for debugging',
            'status' => 'pending'
        ];

        $newComplaint = \App\Models\Complaint::create($complaintData);
        echo "New complaint created with ID: " . $newComplaint->id . "<br>";
        echo "Complaint ID: " . $newComplaint->complaint_id . "<br><br>";

        // Verify it was created properly
        $updatedComplaints = \App\Models\Complaint::where('student_id', $student->id)->get();
        echo "<h3>Updated Complaints Count: " . $updatedComplaints->count() . "</h3>";

        if ($updatedComplaints->count() > 0) {
            echo "<table border='1' cellpadding='4'>";
            echo "<tr><th>ID</th><th>Student ID</th><th>Complaint ID</th><th>Status</th></tr>";

            foreach ($updatedComplaints as $c) {
                echo "<tr>";
                echo "<td>" . $c->id . "</td>";
                echo "<td>" . $c->student_id . "</td>";
                echo "<td>" . $c->complaint_id . "</td>";
                echo "<td>" . $c->status . "</td>";
                echo "</tr>";
            }

            echo "</table><br>";
        }

        echo "<p><a href='/student/complaints'>Go to complaints page</a></p>";
    } catch (\Exception $e) {
        echo "<h3>Error</h3>";
        echo $e->getMessage() . "<br>";
        echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "<br>";
    }
});

// Fix Leave Applications Routes
Route::get('/fix-leaves', [App\Http\Controllers\FixLeavesController::class, 'diagnoseLeavesIssue']);
Route::get('/fix-leaves/test', [App\Http\Controllers\FixLeavesController::class, 'createTestLeave']);

// Add this route for debugging purposes
Route::get('/school/homework/debug', function () {
    try {
        $user = \Illuminate\Support\Facades\Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not logged in'
            ]);
        }

        $school = \App\Models\School::where('admin_id', $user->id)->first();

        if (!$school) {
            return response()->json([
                'success' => false,
                'message' => 'School not found for this user'
            ]);
        }

        // Check if there are any homework entries
        $homeworkCount = \App\Models\Homework::where('school_id', $school->id)->count();

        // If no homework entries, create a sample one
        if ($homeworkCount == 0) {
            // Get a sample section and subject
            $section = \App\Models\Section::first();
            $subject = \App\Models\Subject::first();

            if ($section && $subject) {
                $homework = new \App\Models\Homework();
                $homework->school_id = $school->id;
                $homework->class_name = 'Sample Class';
                $homework->section_id = $section->id;
                $homework->subject_id = $subject->id;
                $homework->homework_date = now();
                $homework->submission_date = now()->addDays(7);
                $homework->description = 'This is a sample homework entry for debugging';
                $homework->created_by = $user->id;
                $homework->save();

                $homeworkCount = 1;
            }
        }

        // Get homework entries with relationships
        $homeworkEntries = \App\Models\Homework::where('school_id', $school->id)
            ->with(['section', 'subject'])
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'class_name' => $item->class_name,
                    'section' => $item->section ? $item->section->name : 'Unknown',
                    'subject' => $item->subject ? $item->subject->name : 'Unknown',
                    'homework_date' => $item->homework_date->format('Y-m-d'),
                    'submission_date' => $item->submission_date->format('Y-m-d'),
                    'description' => $item->description,
                    'image_path' => $item->image_path ? asset('storage/' . $item->image_path) : null,
                ];
            });

        return response()->json([
            'success' => true,
            'school_id' => $school->id,
            'total_homework_count' => $homeworkCount,
            'homework_entries' => $homeworkEntries
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// School Announcements Routes
Route::middleware(['auth'])->prefix('school')->group(function () {
    // Notice Board
    Route::get('/notices', [App\Http\Controllers\Client\SchoolPanel\Announcements\NoticeController::class, 'index'])->name('school.notices');
    Route::post('/notices', [App\Http\Controllers\Client\SchoolPanel\Announcements\NoticeController::class, 'store'])->name('school.notices.store');
    Route::get('/notices/{id}/edit', [App\Http\Controllers\Client\SchoolPanel\Announcements\NoticeController::class, 'edit'])->name('school.notices.edit');
    Route::put('/notices/{id}', [App\Http\Controllers\Client\SchoolPanel\Announcements\NoticeController::class, 'update'])->name('school.notices.update');
    Route::delete('/notices/{id}', [App\Http\Controllers\Client\SchoolPanel\Announcements\NoticeController::class, 'destroy'])->name('school.notices.destroy');
});

// School Panel Help & Support Routes
Route::prefix('school/help-support')->middleware(['auth', 'school.admin'])->group(function () {
    // Dashboard
    Route::get('/', 'App\Http\Controllers\Client\SchoolPanel\HelpSupport\DashboardController@index')->name('school.helpSupport.dashboard');

    // Help Topics
    Route::get('/help-topics', 'App\Http\Controllers\Client\SchoolPanel\HelpSupport\HelpTopicController@index')->name('school.helpTopics.index');
    Route::get('/help-topics/create', 'App\Http\Controllers\Client\SchoolPanel\HelpSupport\HelpTopicController@create')->name('school.helpTopics.create');
    Route::post('/help-topics', 'App\Http\Controllers\Client\SchoolPanel\HelpSupport\HelpTopicController@store')->name('school.helpTopics.store');
    Route::get('/help-topics/{id}/edit', 'App\Http\Controllers\Client\SchoolPanel\HelpSupport\HelpTopicController@edit')->name('school.helpTopics.edit');
    Route::put('/help-topics/{id}', 'App\Http\Controllers\Client\SchoolPanel\HelpSupport\HelpTopicController@update')->name('school.helpTopics.update');
    Route::delete('/help-topics/{id}', 'App\Http\Controllers\Client\SchoolPanel\HelpSupport\HelpTopicController@destroy')->name('school.helpTopics.destroy');

    // Support Tickets
    Route::get('/support-tickets', 'App\Http\Controllers\Client\SchoolPanel\HelpSupport\SupportTicketController@index')->name('school.supportTickets.index');
    Route::get('/support-tickets/{id}', 'App\Http\Controllers\Client\SchoolPanel\HelpSupport\SupportTicketController@show')->name('school.supportTickets.show');
    Route::post('/support-tickets/{id}/status', 'App\Http\Controllers\Client\SchoolPanel\HelpSupport\SupportTicketController@updateStatus')->name('school.supportTickets.updateStatus');
    Route::post('/support-tickets/{id}/reply', 'App\Http\Controllers\Client\SchoolPanel\HelpSupport\SupportTicketController@addReply')->name('school.supportTickets.addReply');
});

// Student Attendance Routes
// Route::middleware(['auth:student'])->prefix('student')->group(function () {

// });



Route::middleware(['school.auth', 'subscription.feature:examination_management'])->prefix('school')->group(function () {
    // Roles and Permissions routes
    Route::get('/exams', [ExamController::class, 'index'])->name('school.exams.index');
    Route::get('/exams/{id}/edit', [ExamController::class, 'edit'])->name('school.exams.edit');
    Route::post('/exams', [ExamController::class, 'store'])->name('school.exams.store');
    Route::put('/exams/', [ExamController::class, 'update'])->name('school.exams.update');
    Route::delete('/examdeleteSchedule/{id}', [ExamController::class, 'destroyExamSchedule'])->name('exam-schedules.destroy');
    Route::get('/examSchedule', [ExamController::class, 'indexExamSchedule'])->name('school.examSchedule');
    Route::post('/examUpdate', [ExamController::class, 'storeExamSchedule'])->name('school.exam-schedules.store');
    // Show form to edit schedule (AJAX)
    Route::get('/exam-schedule/{examSchedule}/edit', [ExamController::class, 'editExamSchedule'])
        ->name('school.exam-schedules.edit');

    // Update schedule
    Route::put('/exam-schedule/{examSchedule}', [ExamController::class, 'updateExamSchedule'])
        ->name('school.exam-schedules.update');

    Route::get('/examdeleteSchedule/{id}', [ExamController::class, 'destroyExamSchedule'])->name('exam-schedules.destroy');
    Route::patch('/exam-schedules/{examSchedule}/cancel', [ExamController::class, 'cancelExam'])->name('exam-schedules.cancel');



    Route::get('/sections-by-class/{classId}', function ($classId) {
        return \App\Models\Section::where('class_id', $classId)
            ->where('status', 1)
            ->get(['id', 'name']);
    });


    // routes/web.php
    Route::put('/exam-schedules/assign-teacher', [ExamController::class, 'assignTeacher'])
        ->name('exam-schedules.assign-teacher');

    Route::get('/exam-schedules/{id}/evaluators', [ExamController::class, 'getEvaluators']);



    Route::get('/subjects-by-class/{classId}', function ($classId) {
        return DB::table('class_subject')
            ->join('subjects', 'class_subject.subject_id', '=', 'subjects.id')
            ->where('class_subject.class_id', $classId)
            ->select('subjects.id', 'subjects.name')
            ->get();
    });
});
