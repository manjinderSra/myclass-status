<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\StudentLeave;
use App\Models\Student;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\Exam;
use App\Models\ExamResult;

use App\Models\Grade;


use App\Models\ExamSchedule;
use App\Models\Complaint;
use App\Models\IssuedBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentDashboardController extends Controller
{
    /**
     * Show the student dashboard page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the authenticated student
        $student = Student::findOrFail(Session::get('student_id'));

        // Debug student data
        Log::info('Student data:', [
            'student_id' => $student->student_id,
            'school_id' => $student->school_id,
        ]);

        // Get recent complaints (limited to 5)
        $recentComplaints = Complaint::where('student_id', $student->student_id)
            ->where('school_id', $student->school_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Debug complaints
        Log::info('Recent complaints count: ' . $recentComplaints->count());

        // Get announcements for dashboard stats
        $totalAnnouncements = \App\Models\Notice::where('school_id', $student->school_id)
            ->where(function ($query) {
                $query->whereJsonContains('recipients', 'Student')
                    ->orWhereNull('recipients');
            })
            ->count();

        $recentAnnouncements = \App\Models\Notice::where('school_id', $student->school_id)
            ->where(function ($query) {
                $query->whereJsonContains('recipients', 'Student')
                    ->orWhereNull('recipients');
            })
            ->where('publish_date', '>=', now()->subDays(7))
            ->count();

        return view('client.student.dashboard.dashboard', compact(
            'recentComplaints',
            'totalAnnouncements',
            'recentAnnouncements'
        ));
    }

    /**
     * Show the student profile page.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        // Get the authenticated student
        $student = Student::findOrFail(Session::get('student_id'));

        // Store student details in session for the view
        Session::put([
            'student_name' => $student->first_name . ' ' . $student->last_name,
            'student_admission_number' => $student->admission_number,
            'student_class' => $student->class->name ?? 'Not Assigned',
            'student_section' => $student->section->name ?? 'Not Assigned',
            'student_profile_image' => $student->profile_image,
            'student_dob' => $student->dob ? $student->dob->format('d M Y') : 'Not Available',
            'student_gender' => $student->gender,
            'student_blood_group' => $student->blood_group,
            'student_email' => $student->email,
            'student_contact' => $student->primary_contact,
            'student_address' => $student->current_address,
            'student_academic_year' => $student->academic_year,
            'student_admission_date' => $student->admission_date ? $student->admission_date->format('d M Y') : 'Not Available',
            'student_roll_number' => $student->academic_number,
            'student_father_name' => $student->father_name,
            'student_father_contact' => $student->father_phone_number,
            'student_mother_name' => $student->mother_name,
            'student_mother_contact' => $student->mother_phone_number,
            'student_guardian_name' => $student->guardian_name,
            'student_guardian_contact' => $student->guardian_phone_number,
        ]);

        return view('client.student.dashboard.profile');
    }

    /**
     * Update student password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $student = Student::findOrFail(Session::get('student_id'));

        if (!Hash::check($request->current_password, $student->password)) {
            return back()->with('error', 'Current password is incorrect');
        }

        $student->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password updated successfully');
    }

    /**
     * Show the leave application form page.
     *
     * @return \Illuminate\View\View
     */
    public function leaves()
    {
        $student = Student::findOrFail(Session::get('student_id'));

        // Get recent leave applications (limited to 5)
        $leaveApplications = StudentLeave::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('client.student.dashboard.leave', compact('leaveApplications'));
    }

    /**
     * Show all leave applications for the student.
     *
     * @return \Illuminate\View\View
     */
    public function allLeaves()
    {
        $student = Student::findOrFail(Session::get('student_id'));

        $leaveApplications = StudentLeave::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.student.dashboard.leave-all', compact('leaveApplications'));
    }

    /**
     * Show details of a specific leave application.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function leaveDetails($id)
    {
        $student = Student::findOrFail(Session::get('student_id'));

        $leave = StudentLeave::where('id', $id)
            ->where('student_id', $student->id)
            ->with('processor')
            ->first();

        if (!$leave) {
            return view('client.student.dashboard.leave-details')->with('error', 'Leave application not found');
        }

        // Add the leave_days attribute
        $leave->leave_days = $leave->getLeaveDaysAttribute();

        return view('client.student.dashboard.leave-details', compact('leave'));
    }

    /**
     * Submit a new leave application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitLeave(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:100',
            'description' => 'required|string',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $student = Student::findOrFail(Session::get('student_id'));

        $leaveData = [
            'school_id' => $student->school_id,
            'student_id' => $student->id,
            'leave_id' => StudentLeave::generateLeaveId(),
            'reason' => $request->reason,
            'description' => $request->description,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'status' => 'pending',
        ];

        // Handle file upload if present
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('leave_attachments/' . $student->student_id, 'public');
            $leaveData['attachment_path'] = $path;
        }

        StudentLeave::create($leaveData);

        return redirect()->route('student.leaves')->with('success', 'Leave application submitted successfully');
    }

    /**
     * Show the complaint submission form page.
     *
     * @return \Illuminate\View\View
     */
    public function complaints()
    {
        $student = Student::findOrFail(Session::get('student_id'));

        // Get recent complaints (limited to 5) - using ID (primary key)
        $complaints = Complaint::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('client.student.dashboard.complaints', compact('complaints'));
    }

    /**
     * Show all complaints for the student.
     *
     * @return \Illuminate\View\View
     */
    public function allComplaints()
    {
        $student = Student::findOrFail(Session::get('student_id'));

        // Get all complaints - using ID (primary key)
        $complaints = Complaint::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.student.dashboard.complaints-all', compact('complaints'));
    }

    /**
     * Show details of a specific complaint.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function complaintDetails($id)
    {
        $student = Student::findOrFail(Session::get('student_id'));

        // Get the complaint with the given ID - check it belongs to this student
        $complaint = Complaint::where('id', $id)
            ->where('student_id', $student->id)
            ->first();

        if (!$complaint) {
            return view('client.student.dashboard.complaints-details')->with('error', 'Complaint not found');
        }

        return view('client.student.dashboard.complaints-details', compact('complaint'));
    }

    /**
     * Submit a new complaint.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function submitComplaint(Request $request)
    {
        $request->validate([
            'nature' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        $student = Student::findOrFail(Session::get('student_id'));

        $complaintData = [
            'school_id' => $student->school_id,
            'student_id' => $student->id, // Use ID (primary key)
            'complaint_id' => Complaint::generateComplaintId(),
            'nature' => $request->nature,
            'description' => $request->description,
            'status' => 'pending'
        ];

        Complaint::create($complaintData);

        return redirect()->route('student.complaints')->with('success', 'Complaint submitted successfully');
    }

    /**
     * Show the student timetable page.
     *
     * @return \Illuminate\View\View
     */
    public function timetable()
    {
        $student = Student::findOrFail(Session::get('student_id'));

        if (!$student->class_id || !$student->section_id) {
            return view('client.student.dashboard.timetable')->with('error', 'You are not assigned to any class or section');
        }

        $timetable = \App\Models\TimeTable::where('school_id', $student->school_id)
            ->where('class_name', $student->class->name ?? '')
            ->where('section_id', $student->section_id)
            ->first();

        if (!$timetable) {
            return view('client.student.dashboard.timetable')->with('error', 'No timetable found for your class');
        }

        // Get all periods grouped by day
        $periods = \App\Models\TimeTablePeriod::where('timetable_id', $timetable->id)
            ->with(['subjectRelation', 'teacherRelation'])
            ->orderBy('time_from')
            ->get();

        // Group periods by day
        $groupedPeriods = $periods->groupBy('day');

        // Define days order for consistent display
        $daysOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Today's day
        $today = now()->format('l'); // Returns day name (Monday, Tuesday, etc.)

        return view('client.student.dashboard.timetable', compact('timetable', 'groupedPeriods', 'daysOrder', 'today'));
    }

    /**
     * Show the rules and regulations page.
     *
     * @return \Illuminate\View\View
     */
    public function rulesAndRegulations()
    {
        $student = Student::findOrFail(Session::get('student_id'));

        // Get school rules categories with their rules
        $ruleCategories = \App\Models\RuleCategory::where('school_id', $student->school_id)
            ->with('rules')
            ->get();

        return view('client.student.dashboard.rules', compact('ruleCategories'));
    }

    /**
     * Show the help and support page.
     *
     * @return \Illuminate\View\View
     */
    public function helpAndSupport()
    {
        $student = Student::findOrFail(Session::get('student_id'));

        // Get school contact information
        $school = \App\Models\School::find($student->school_id);

        // Get FAQs if they exist
        $faqs = \App\Models\FAQ::where('school_id', $student->school_id)
            ->orderBy('priority')
            ->get();

        return view('client.student.dashboard.help', compact('school', 'faqs'));
    }

    /**
     * Submit a support ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitSupportTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'priority' => 'required|in:low,medium,high',
        ]);

        $student = Student::findOrFail(Session::get('student_id'));

        $ticketData = [
            'school_id' => $student->school_id,
            'student_id' => $student->student_id,
            'ticket_id' => 'TKT' . date('YmdHis') . rand(100, 999),
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'status' => 'open',
        ];

        \App\Models\SupportTicket::create($ticketData);

        return redirect()->route('student.help')->with('success', 'Support ticket submitted successfully');
    }

    /**
     * Show the programs and events page.
     *
     * @return \Illuminate\View\View
     */
    public function programsAndEvents()
    {
        $student = Student::findOrFail(Session::get('student_id'));

        // Get active programs
        $programs = \App\Models\SchoolProgram::where('school_id', $student->school_id)
            ->active()
            ->orderBy('created_at', 'desc')
            ->get();

        // Get upcoming events
        $upcomingEvents = \App\Models\SchoolEvent::where('school_id', $student->school_id)
            ->upcoming()
            ->take(5)
            ->get();

        // Get featured events
        $featuredEvents = \App\Models\SchoolEvent::where('school_id', $student->school_id)
            ->featured()
            ->upcoming()
            ->take(3)
            ->get();

        return view('client.student.dashboard.programs-events', compact('programs', 'upcomingEvents', 'featuredEvents'));
    }

    /**
     * Show details of a specific program.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function programDetails($id)
    {
        $student = Student::findOrFail(Session::get('student_id'));

        $program = \App\Models\SchoolProgram::where('id', $id)
            ->where('school_id', $student->school_id)
            ->where('status', 'active')
            ->first();

        if (!$program) {
            return redirect()->route('student.programs-events')->with('error', 'Program not found');
        }

        // Get events for this program
        $events = \App\Models\SchoolEvent::where('program_id', $program->id)
            ->orderBy('event_date', 'desc')
            ->get();

        return view('client.student.dashboard.program-details', compact('program', 'events'));
    }

    /**
     * Show details of a specific event.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function eventDetails($id)
    {
        $student = Student::findOrFail(Session::get('student_id'));

        $event = \App\Models\SchoolEvent::where('id', $id)
            ->where('school_id', $student->school_id)
            ->with('program')
            ->first();

        if (!$event) {
            return redirect()->route('student.programs-events')->with('error', 'Event not found');
        }

        return view('client.student.dashboard.event-details', compact('event'));
    }

    /**
     * Show events and programs in a calendar view.
     *
     * @return \Illuminate\View\View
     */
    public function calendar()
    {
        $student = Student::findOrFail(Session::get('student_id'));

        // Get events
        $events = \App\Models\SchoolEvent::where('school_id', $student->school_id)
            ->where(function ($query) {
                $query->where('status', 'upcoming')
                    ->orWhere('status', 'ongoing');
            })
            ->orderBy('event_date')
            ->get();

        // Get active programs
        $programs = \App\Models\SchoolProgram::where('school_id', $student->school_id)
            ->where('status', 'active')
            ->get();

        // Get holidays
        $holidays = \App\Models\Holiday::where('school_id', $student->school_id)
            ->get();

        // Get students from same class/section with birthdays
        $studentBirthdays = \App\Models\Student::where('school_id', $student->school_id)
            ->where('class_id', $student->class_id)
            ->where('section_id', $student->section_id)
            ->whereNotNull('dob')
            ->get();

        // Get all teachers from the school (since we don't have a way to filter by class)
        $teacherBirthdays = \App\Models\Teacher::where('school_id', $student->school_id)
            ->whereNotNull('date_of_birth')
            ->get();

        return view('client.student.dashboard.calendar', compact('events', 'programs', 'holidays', 'studentBirthdays', 'teacherBirthdays'));
    }

    /**
     * Show student's library records.
     *
     * @return \Illuminate\View\View
     */
    public function libraryRecords()
    {
        $student = Student::findOrFail(Session::get('student_id'));

        // ============================
        // CURRENTLY ISSUED BOOKS
        // ============================
        $currentBooks = IssuedBook::where('student_id', $student->student_id)
            ->where('school_id', $student->school_id)
            ->where('is_returned', false)
            ->where('is_lost', false)
            ->orderBy('due_date')
            ->get();


        // ============================
        // HISTORY: RETURNED + LOST BOOKS
        // ============================
        $returnedBooks = IssuedBook::where('student_id', $student->student_id)
            ->where('school_id', $student->school_id)
            ->where(function ($q) {
                $q->where('is_returned', true)
                    ->orWhere('is_lost', true);
            })
            ->orderBy('return_date', 'desc')
            ->orderBy('lost_date', 'desc')
            ->get();

        // ============================
        // ADD BOOK IMAGES
        // ============================
        $bookData = Book::where('school_id', $student->school_id)
            ->get()
            ->keyBy('book_id');

        foreach ($currentBooks as $book) {
            $book->image_path = $bookData[$book->book_id]->image_path ?? null;
        }

        foreach ($returnedBooks as $book) {
            $book->image_path = $bookData[$book->book_id]->image_path ?? null;

            // STATUS
            if ($book->is_returned) {
                $book->status = "Returned";
                $book->final_date = $book->return_date;
            } elseif ($book->is_lost) {
                $book->status = "Lost";
                $book->final_date = $book->lost_date;
            }
        }

        // ============================
        // OVERDUE BOOKS
        // ============================
        $overdueBooks = $currentBooks->filter(function ($book) {
            return \Carbon\Carbon::parse($book->due_date)->isPast();
        });

        // ============================
        // SHOW 5 BOOKS IF NONE ISSUED
        // ============================
        $allBooks = [];
        if ($currentBooks->isEmpty() && $returnedBooks->isEmpty()) {
            $allBooks = $bookData->take(5);
        }

        return view('client.student.dashboard.library-records', compact(
            'currentBooks',
            'returnedBooks',
            'overdueBooks',
            'allBooks'
        ));
    }


    /**
     * Show the announcements page for the student.
     *
     * @return \Illuminate\View\View
     */
    public function announcements()
    {
        // Get the authenticated student
        $student = Student::findOrFail(Session::get('student_id'));

        // Get notices for this student's school
        // Filter by recipients to include only notices intended for students
        $notices = \App\Models\Notice::where('school_id', $student->school_id)
            ->where(function ($query) {
                $query->whereJsonContains('recipients', 'Student')
                    ->orWhereNull('recipients');
            })
            ->orderBy('publish_date', 'desc')
            ->paginate(10);

        return view('client.student.dashboard.announcements', compact('notices'));
    }



    public function indexHomeWork()
    {
        // Get the logged-in student
        $student = Student::findOrFail(Session::get('student_id'));

        // Fetch homework for the student's class and section
        $homework = Homework::where('school_id', $student->school_id)
            ->where('class_name', $student->class->name) // assuming class_id maps to class_name in homework
            ->where('section_id', $student->section_id)
            ->orderBy('homework_date', 'desc')
            ->get();

        return view('client.student.dashboard.homework', compact('homework'));
    }



    // public function submitHomeWork(Request $request, $id)
    // {
    //     $student = Student::with(['class', 'section'])->findOrFail(Session::get('student_id'));

    //     $request->validate([
    //         'submission_file' => 'required|file|mimes:pdf,doc,docx,jpg,png,webp',
    //     ]);
    //     $hw = Homework::findOrFail($id);
    //     // Store file
    //     $filePath = $request->file('submission_file')->store('homework_submissions', 'public');
    //     // Create submission record (assuming HomeworkSubmission model exists)
    //     $d = HomeworkSubmission::create([
    //         'homework_id' => $hw->id,
    //         'student_id' => auth()->id(),
    //         'file_path' => $filePath,
    //         'submitted_at' => now(),
    //     ]);
    //     return back()->with('success', 'Homework submitted successfully!');
    // }
    public function submitHomeWork(Request $request, $id)
    {
        $studentId = Session::get('student_id');

        $student = Student::with(['class', 'section'])
            ->findOrFail($studentId);

        $request->validate([
            'submission_file' => 'required|file|mimetypes:application/pdf,application/x-pdf,application/octet-stream,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/png,image/webp',
        ]);

        $hw = Homework::findOrFail($id);

        $dueDate = \Carbon\Carbon::parse($hw->submission_date)->startOfDay();
        $today = now()->startOfDay();

        if ($today->greaterThan($dueDate)) {
            return back()->with('error', 'The submission date has passed. You cannot submit this homework now.');
        }


        $alreadySubmitted = HomeworkSubmission::where('homework_id', $id)
            ->where('student_id', $studentId)
            ->exists();

        if ($alreadySubmitted) {
            return back()->with('error', 'You have already submitted this homework.');
        }

        // Store file
        $extension = $request->file('submission_file')->getClientOriginalExtension();

        $filePath = $request->file('submission_file')->storeAs(
            'homework_submissions',
            uniqid() . '.' . $extension,
            'public'
        );


        // Save submission
        HomeworkSubmission::create([
            'homework_id' => $hw->id,
            'student_id' => $studentId,
            'file_path' => $filePath,
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Homework submitted successfully!');
    }



    public function downloadSubmission($id)
    {
        $submission = HomeworkSubmission::findOrFail($id);

        $filePath = $submission->file_path;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, "File not found");
        }

        $mime = Storage::disk('public')->mimeType($filePath);
        $fileName = $submission->original_name ?? basename($filePath);

        return response()->download(
            Storage::disk('public')->path($filePath),
            $fileName,
            ['Content-Type' => $mime]
        );
    }

    public function indexExam()
    {
        $student = Student::with(['class', 'section'])->findOrFail(Session::get('student_id'));

        // Fetch exams for student's class + section
        $examSchedules = ExamSchedule::where('class', $student->class->name)
            ->where('section', $student->section->name)
            ->with(['exam'])
            ->orderBy('exam_date', 'desc')
            ->paginate(10);

        return view('client.student.exams.index', compact('student', 'examSchedules'));
    }


    public function showResultPage()
    {

        $student = Student::with(['class', 'section'])->findOrFail(Session::get('student_id'));
        // dd($student);
        // Fetch distinct exam types
        $examTypes = Exam::whereHas('examSchedules', function ($q) use ($student) {
            $q->where('class', $student->class->name)
                ->where('section', $student->section->name);
        })->pluck('name', 'id');

        // Fetch distinct sessions
        $sessions = Exam::whereHas('examSchedules', function ($q) use ($student) {
            $q->where('class', $student->class->name)
                ->where('section', $student->section->name);
        })->distinct()->pluck('academic_session');

        $studentId = Session::get('student_id');

        // Fetch exam results with relationships
        $results = ExamResult::with(['subject', 'teacher', 'examSchedule.exam', 'examSchedule.teacher'])
            ->where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Attach exam_schedule for each result based on subject name
        foreach ($results as $result) {
            $subjectName = $result->subject->name ?? null;
            $studentClass = $student->class->name;
            $studentSection = $student->section->name;

            if ($subjectName) {
                $schedule = ExamSchedule::with(['exam', 'teacher'])
                    ->where('subject_id',  $result->subject_id)
                    ->where('class', $studentClass)
                    ->where('section', $studentSection)
                    ->orderBy('exam_date', 'desc') // latest first
                    ->first();

                $result->examSchedule = $schedule;
            }
        }


        // Group by subject_id and get latest results with grades
        $latestResults = $results->groupBy('subject_id')->map(function ($group) {
            return $group->map(function ($result) {
                $percentage = ($result->total_marks > 0)
                    ? ($result->marks_obtained / $result->total_marks) * 100
                    : 0;

                $result->grade = Grade::where('status', 1)
                    ->where('min_score', '<=', $percentage)
                    ->where('max_score', '>=', $percentage)
                    ->value('name') ?? 'N/A';

                return $result;
            })->first(); // Get only the latest result per subject
        })->values(); // Reset keys to numeric indices

        return view('client.student.result.index', compact('examTypes', 'sessions', 'student', 'latestResults'));
    }

    public function fetchResults(Request $request)
    {
        // dd($request);
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'academic_session' => 'required|string',
        ]);

        $studentId = Session::get('student_id');
        $student = Student::with(['class', 'section'])->findOrFail($studentId);

        // Get exam schedules for this exam and session
        $examSchedules = ExamSchedule::where('exam_id', $request->exam_id)
            ->whereHas('exam', function ($q) use ($request) {
                $q->where('academic_session', $request->academic_session);
            })
            ->where('class', $student->class->name)
            ->where('section', $student->section->name)
            ->get(); // get full schedules to access exam_type
        // dd($examSchedules);
        $examScheduleIds = $examSchedules->pluck('id');

        // Fetch exam results for the student
        $results = ExamResult::with(['subject'])
            ->whereIn('exam_schedule_id', $examScheduleIds)
            ->where('student_id', $studentId)
            ->get();

        // Assign exam_type and per-subject grade
        $results = $results->map(function ($result) use ($examSchedules) {
            $schedule = $examSchedules->firstWhere('id', $result->exam_schedule_id);
            $result->exam_type = $schedule->exam_type ?? 'theory';

            // Calculate subject percentage
            $percentage = ($result->total_marks > 0) ? ($result->marks_obtained / $result->total_marks) * 100 : 0;

            // Assign grade
            $result->grade = Grade::where('status', 1)
                ->where('min_score', '<=', $percentage)
                ->where('max_score', '>=', $percentage)
                ->value('name') ?? 'N/A';

            return $result;
        });

        // --- CALCULATIONS ---
        $totalMarksObtained = $results->sum('marks_obtained');
        $totalMaxMarks = $results->sum('total_marks');
        $percentage = ($totalMaxMarks > 0) ? ($totalMarksObtained / $totalMaxMarks) * 100 : 0;

        // Determine overall grade
        $overallGrade = Grade::where('status', 1)
            ->where('min_score', '<=', $percentage)
            ->where('max_score', '>=', $percentage)
            ->value('name') ?? 'N/A';

        // --- RANK CALCULATION ---
        $allStudentTotals = ExamResult::select('student_id')
            ->whereIn('exam_schedule_id', $examScheduleIds)
            ->groupBy('student_id')
            ->selectRaw('SUM(marks_obtained) as total_marks, student_id')
            ->orderByDesc('total_marks')
            ->get();

        $rank = 1;
        foreach ($allStudentTotals as $key => $s) {
            if ($s->student_id == $studentId) {
                $rank = $key + 1;
                break;
            }
        }
        $examName = $examSchedules->first()?->exam?->name ?? 'Exam';

        return view('client.student.result.show', [
            'results' => $results,
            'student' => $student,
            'academic_session' => $request->academic_session,
            'totalMarksObtained' => $totalMarksObtained,
            'totalMaxMarks' => $totalMaxMarks,
            'percentage' => $percentage,
            'overallGrade' => $overallGrade,
            'rank' => $rank,
            'examName' => $examName,
        ]);
    }
}
