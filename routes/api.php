<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SaasAdminAuthController;
use App\Http\Controllers\Api\StudentAuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\StudentAnnouncementController;
use App\Http\Controllers\Api\TeacherAuthController;
use App\Models\School;
use App\Models\Plan;
use App\Models\SchoolSubscription;
use App\Http\Controllers\Client\SchoolPanel\HelpSupportController;
use App\Http\Controllers\Api\TeacherBirthdayController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\ScreenShotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication routes
Route::post('/saasAdmin/register', [SaasAdminAuthController::class, 'register']);
Route::post('/saasAdmin/login', [SaasAdminAuthController::class, 'login']);
// Student authentication routes
Route::post('/student/login', [StudentAuthController::class, 'login']);
// Teacher authentication routes
Route::post('/teacher/login', [TeacherAuthController::class, 'login']);

// Teacher Homework API Routes - Session-based authentication via X-Teacher-ID header


// Routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/saasAdmin/logout', [SaasAdminAuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    

    Route::prefix('teacher')->group(function () {
    Route::get('/homework', [App\Http\Controllers\Api\TeacherHomeworkController::class, 'index']);
    
    Route::get('/homeworks/{homework}/submissions', [App\Http\Controllers\Api\TeacherHomeworkController::class, 'submissions']);
    
    Route::post('/homework', [App\Http\Controllers\Api\TeacherHomeworkController::class, 'store']);
    Route::get('/homework/{id}', [App\Http\Controllers\Api\TeacherHomeworkController::class, 'show']);
    Route::put('/homework/{id}', [App\Http\Controllers\Api\TeacherHomeworkController::class, 'update']);
    Route::delete('/homework/{id}', [App\Http\Controllers\Api\TeacherHomeworkController::class, 'destroy']);
});
    

    Route::get('/account', [AccountController::class, 'getAccount']);
    Route::post('/screenshot', [ScreenShotController::class, 'store']);

    
    // Student authenticated routes
    Route::post('/student/logout', [StudentAuthController::class, 'logout']);
    Route::get('/student/profile', [StudentController::class, 'profile']);
    Route::get('/student/details', [StudentController::class, 'getDetails']);
    Route::get('/student/timetable', [StudentController::class, 'timetable']);
    Route::get('/student/timetable/today', [StudentController::class, 'todayTimetable']);
    Route::get('/student/timetable/weekly', [StudentController::class, 'weeklyTimetable']);
    Route::get('/student/attendance', [App\Http\Controllers\Api\Student\AttendanceController::class, 'getAttendance']);
    
    
    
    //homework
    Route::get('/student/home-work',[StudentController::class, 'indexHomeWork']);
    Route::post('/student/homework/{id}/submit',[StudentController::class, 'submitHomework']);
    Route::get('/student/exams', [StudentController::class, 'indexExam']);
    // Student announcements route
    Route::get('/student/announcements', [StudentAnnouncementController::class, 'index']);
    
    // Student transport routes
    Route::get('/student/transport', [App\Http\Controllers\Api\StudentTransportController::class, 'getTransportDetails']);
    
    // Teacher endpoints
    Route::get('/student/teachers', [StudentController::class, 'timetableTeachers']);
    Route::get('/student/teachers/{id}', [StudentController::class, 'teacherDetails']);
    
    // Teacher authenticated routes
    Route::get('/teacher/profile', [TeacherAuthController::class, 'profile']);
    Route::post('/teacher/logout', [TeacherAuthController::class, 'logout']);
    Route::get('/teacher/students', [TeacherAuthController::class, 'teachingStudents']);
    Route::post('/teacher/change-password', [TeacherAuthController::class, 'changePassword']);
    
    // Teacher leave application routes
    Route::get('/teacher/leave-applications', [App\Http\Controllers\Api\TeacherLeaveController::class, 'getLeaveApplications']);
    Route::get('/teacher/leave-applications/{id}', [App\Http\Controllers\Api\TeacherLeaveController::class, 'getLeaveDetails']);
    Route::post('/teacher/leave-applications/{id}/update-status', [App\Http\Controllers\Api\TeacherLeaveController::class, 'updateLeaveStatus']);
    
    // Teacher announcements routes
    Route::get('/teacher/announcements', [App\Http\Controllers\Api\TeacherAnnouncementController::class, 'index']);
    Route::get('/teacher/announcements/{id}', [App\Http\Controllers\Api\TeacherAnnouncementController::class, 'show']);
    
    // Teacher birthdays routes
    Route::get('/teacher/birthdays', [App\Http\Controllers\Api\TeacherBirthdayController::class, 'allBirthdays']);
    Route::get('/teacher/birthday/my', [App\Http\Controllers\Api\TeacherBirthdayController::class, 'myBirthday']);
    Route::get('/teacher/birthday/subject-colleagues', [App\Http\Controllers\Api\TeacherBirthdayController::class, 'subjectColleagueBirthdays']);
    
    // Birthday endpoints
    Route::get('/student/birthdays', [StudentController::class, 'allBirthdays']);
    Route::get('/student/birthday/my', [StudentController::class, 'myBirthday']);
    Route::get('/student/birthday/class', [StudentController::class, 'classBirthdays']);
    
    // Complaint endpoints
    Route::post('/student/complaints', [App\Http\Controllers\Api\StudentComplaintController::class, 'submitComplaint']);
    Route::get('/student/complaints', [App\Http\Controllers\Api\StudentComplaintController::class, 'myComplaints']);
    Route::get('/student/complaints/{id}', [App\Http\Controllers\Api\StudentComplaintController::class, 'complaintDetails']);
    
    // Leave Application endpoints
    Route::post('/student/leaves', [App\Http\Controllers\Api\StudentLeaveController::class, 'submitLeave']);
    Route::get('/student/getleaves', [App\Http\Controllers\Api\StudentLeaveController::class, 'myLeaveApplications']);
    Route::get('/student/leaves/{id}', [App\Http\Controllers\Api\StudentLeaveController::class, 'leaveDetails']);
    
    // Calendar endpoints
    Route::get('/student/calendar', [App\Http\Controllers\Api\StudentCalendarController::class, 'getCalendarEvents']);
    Route::get('/student/calendar/all', [App\Http\Controllers\Api\StudentCalendarController::class, 'getAllCalendarData']);
    
    // Password update routes
    Route::post('/update-password', [App\Http\Controllers\Api\UserPasswordController::class, 'update']);
    Route::post('/student/update-password', [App\Http\Controllers\Api\StudentAuthController::class, 'updatePassword']);

    // School media endpoints
    Route::get('/media', [App\Http\Controllers\Api\SchoolMediaController::class, 'index']);
    Route::get('/media/{id}', [App\Http\Controllers\Api\SchoolMediaController::class, 'show']);
    Route::get('/media/photos', [App\Http\Controllers\Api\SchoolMediaController::class, 'photos']);
    Route::get('/media/videos', [App\Http\Controllers\Api\SchoolMediaController::class, 'videos']);
    Route::get('/media/featured', [App\Http\Controllers\Api\SchoolMediaController::class, 'featured']);
    Route::get('/media/categories', [App\Http\Controllers\Api\SchoolMediaController::class, 'categories']);
    
    // Program and Event Media API endpoints
    Route::get('/media/programs', [App\Http\Controllers\Api\SchoolMediaController::class, 'programMedia']);
    Route::get('/media/events', [App\Http\Controllers\Api\SchoolMediaController::class, 'eventMedia']);
    Route::get('/media/gallery', [App\Http\Controllers\Api\SchoolMediaController::class, 'gallery']);

    // Program and Event Images API endpoints
    Route::get('/program-images', [App\Http\Controllers\Api\ProgramEventMediaController::class, 'programImages']);
    Route::get('/event-images', [App\Http\Controllers\Api\ProgramEventMediaController::class, 'eventImages']);
    Route::get('/gallery-images', [App\Http\Controllers\Api\ProgramEventMediaController::class, 'galleryImages']);
    Route::get('/debug-event-images', [App\Http\Controllers\Api\ProgramEventMediaController::class, 'debugEventImages']);

    // Teacher Birthday API Routes
    Route::get('/teacher/birthdays/all', [TeacherBirthdayController::class, 'allBirthdays']);
    Route::get('/teacher/birthdays/my', [TeacherBirthdayController::class, 'myBirthday']);
    Route::get('/teacher/birthdays/subject', [TeacherBirthdayController::class, 'sameSubjectBirthdays']);
    Route::get('/teacher/birthdays/teaching-classes', [TeacherBirthdayController::class, 'teachingClassesBirthdays']);

    // Teacher Timetable API Routes
    Route::get('/teacher/timetable', [App\Http\Controllers\Api\TeacherTimetableController::class, 'getWeeklyTimetable']);
    
    Route::get('/teacher/timetable/today', [App\Http\Controllers\Api\TeacherTimetableController::class, 'getTodayTimetable']);
    Route::get('/teacher/timetable/class/{timetableId}', [App\Http\Controllers\Api\TeacherTimetableController::class, 'getClassTimetable']);
    Route::get('/teacher/attendance', [App\Http\Controllers\Api\TeacherTimetableController::class, 'getTimetable']);
    Route::get('/teacher/attendance/students/{id}/{schoolid}', [App\Http\Controllers\Api\TeacherTimetableController::class, 'getStudentsByClassId']);
    
    Route::post('/teacher/attendance/mark', [App\Http\Controllers\Api\TeacherTimetableController::class, 'markAttendance']);
 Route::get('/teacher/exam-schedules', [App\Http\Controllers\Api\TeacherTimetableController::class, 'indexTeacherExam']);
   
});

// Subscription-related API endpoints
Route::get('/schools/{school}/subscription', function (School $school) {
    $subscription = SchoolSubscription::where('school_id', $school->id)
        ->where('status', 'active')
        ->whereDate('end_date', '>=', now())
        ->with('plan')
        ->latest()
        ->first();
    
    return response()->json([
        'subscription' => $subscription
    ]);
});

Route::get('/plans/{plan}', function (Plan $plan) {
    return response()->json([
        'plan' => $plan
    ]);
});

// Student API Routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Help and support route with bearer token authentication
    Route::get('/help-support', [App\Http\Controllers\Client\SchoolPanel\HelpSupportController::class, 'getApiWithToken']);
    
    // Student-specific help support route
    Route::get('/student/help-support', [App\Http\Controllers\Api\Student\HelpSupportController::class, 'getHelpSupport']);
});

// Terms and conditions route with bearer token authentication
Route::middleware('auth:sanctum')->get('/student/terms-conditions', [App\Http\Controllers\Client\SchoolPanel\TermsConditionController::class, 'getApiWithToken']);

// Terms and Conditions API Endpoint
Route::middleware('auth:sanctum')->get('/terms-and-conditions', [App\Http\Controllers\Client\SchoolPanel\TermsConditionController::class, 'getApiWithToken']);

// Student Transport API for mobile app
Route::middleware('auth:sanctum')->get('/transport/student', [App\Http\Controllers\Api\StudentTransportController::class, 'getTransportDetails']);

// Programs and Events API Endpoints
Route::get('/schools/{schoolId}/programs', [App\Http\Controllers\Api\SchoolProgramEventController::class, 'getPrograms']);
Route::get('/schools/{schoolId}/programs/{programId}', [App\Http\Controllers\Api\SchoolProgramEventController::class, 'getProgramDetails']);
Route::get('/schools/{schoolId}/events', [App\Http\Controllers\Api\SchoolProgramEventController::class, 'getEvents']);
Route::get('/schools/{schoolId}/events/{eventId}', [App\Http\Controllers\Api\SchoolProgramEventController::class, 'getEventDetails']);

// Student Routes
// Route::middleware(['auth:api'])->prefix('student')->group(function () {
   
// });
