<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentLeave;
use App\Models\Student;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class StudentDashboardController extends Controller
{
    /**
     * Show the student dashboard page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('client.student.dashboard.dashboard');
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
            $path = $file->store('leave_attachments/' . $student->id, 'public');
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
        
        // Get recent complaints (limited to 5)
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
        
        $complaints = Complaint::where('student_id', $student->id)
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
        
        return view('client.student.dashboard.complaints-all', compact('complaints'));
    }

    /**
     * Show details of a specific complaint.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function complaintDetails($id)
    {
        $student = Student::findOrFail(Session::get('student_id'));
        
        $complaint = Complaint::where('id', $id)
                        ->where('student_id', $student->id)
                        ->with('resolver')
                        ->first();
        
        if (!$complaint) {
            return view('client.student.dashboard.complaints-details')->with('error', 'Complaint not found');
        }
        
        return view('client.student.dashboard.complaints-details', compact('complaint'));
    }

    /**
     * Submit a new complaint.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
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
            'student_id' => $student->id,
            'complaint_id' => Complaint::generateComplaintId(),
            'nature' => $request->nature,
            'description' => $request->description,
            'status' => 'pending',
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
            'student_id' => $student->id,
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
                    ->where(function($query) {
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
}
