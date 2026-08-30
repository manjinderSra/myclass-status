<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Teacher;



use App\Models\ExamSchedule;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    // Get School Id
    private function getSchoolId()
    {
        $user = Auth::user();
        $schoolId = null;

        if ($user->role === 'school') {
            $school = School::where('admin_id', $user->id)->first();
            if ($school) {
                $schoolId = $school->id;
            }
        } else if ($user->school_id) {
            $schoolId = $user->school_id;
        }

        return $schoolId;
    }


    // Index
 public function index(Request $request)
{
    $schoolId = $this->getSchoolId();

    // Get all distinct sessions for the school
    $sessions = Exam::where('school_id', $schoolId)
        ->select('academic_session')
        ->distinct()
        ->orderBy('academic_session', 'desc')
        ->pluck('academic_session');

    // Selected session
    $selectedSession = $request->query('session');

    // Get exams for that school and session
    $exams = Exam::where('school_id', $schoolId)
        ->when($selectedSession, fn($q) => $q->where('academic_session', $selectedSession))
        ->orderBy('start_date', 'asc')
        ->get()
        ->groupBy('academic_session');

    // ✅ Load schedules only for that school
    $schedules = ExamSchedule::with(['exam', 'subject'])
        ->where('school_id', $schoolId)
        ->latest()
        ->get();

    return view('client.schoolPanel.exam.index', compact('exams', 'sessions', 'selectedSession', 'schedules'));
}




    // Create Exam Term 
    public function store(Request $request)
    {

        $schoolId = $this->getSchoolId();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'academic_session' => [
                'required',
                'string',
                'max:50',
                // Accepts either "2025" or "2025-2026"
                'regex:/^\d{4}(-\d{4})?$/'
            ],
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ], [
            'academic_session.regex' => 'Academic session must be a year (2025) or a range (2025-2026).'
        ]);

        // dd($validated);
        $validated['school_id'] = $schoolId;

        Exam::create($validated);

        return redirect()->back()->with('success', 'Exam created successfully.');
    }


    // Edit Term 
    public function edit($id)
    {
        $exam = Exam::findOrFail($id);
        return response()->json($exam); // Return data for modal (AJAX)
    }


  public function editExamSchedule(ExamSchedule $examSchedule)
{

    // Load exam relation
    $examSchedule->load('exam');

    $classes = SchoolClass::where('school_id', $this->getSchoolId())->get();
    $sections = Section::where('school_id', $this->getSchoolId())->get();
    $teachers = Teacher::where('school_id', $this->getSchoolId())->get();

    return response()->json([
        'schedule' => $examSchedule,
        'classes' => $classes,
        'sections' => $sections,
        'teachers' => $teachers,
    ]);
}
public function cancelExam(Request $request, $id)
{
    $schedule = ExamSchedule::findOrFail($id);

    // Mark as canceled
    $schedule->status = 'Canceled';
    $schedule->cancel_reason = $request->cancel_reason;

    $schedule->save();

    return response()->json(['success' => true]);
}




public function updateExamSchedule(Request $request, ExamSchedule $examSchedule)
{

    // Find class and section by ID
    $class = SchoolClass::findOrFail($request->class_id);
    $section = Section::findOrFail($request->section_id);
    $validated = $request->validate([
        'exam_id' => 'required|exists:exams,id',
        'class_id' => 'required|exists:school_classes,id',
        'section_id' => 'required|exists:sections,id',
        'subject_id' => 'required|string|max:255',
        'exam_date' => 'required|date',
        'start_time' => 'required|date_format:H:i:s',
'end_time'   => 'required|date_format:H:i:s',


        'duration' => 'required|integer|min:1',
        'room_no' => 'required|string|min:1', // Allow multiple rooms
        'max_marks' => 'required|integer|min:1',
        'min_marks' => 'required|integer|min:0',
        'exam_type' => 'required|in:theory,practical', 
    ]);

    // Prepare fields for saving
    $examSchedule->update([
        'exam_id'   => $validated['exam_id'],
        'class'     => $class->name,
        'section'   => $section->name,
        'subject_id'   => $validated['subject_id'],
        'exam_date' => $validated['exam_date'],
        'start_time'=> $validated['start_time'],
        'end_time'  => $validated['end_time'],
        'duration'  => $validated['duration'],
        'room_no'   => $validated['room_no'], 
        'max_marks' => $validated['max_marks'],
        'min_marks' => $validated['min_marks'],
        'exam_type' => $validated['exam_type']
    ]);

    return redirect()->back()->with('success', 'Exam schedule updated successfully.');
}

    public function update(Request $request)
    {


        $exam = Exam::findOrFail($request->id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        $exam->update($validated);

        return redirect()->back()->with('success', 'Exam updated successfully');
    }

    // Delete Term
    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();

        return response()->json(['success' => true, 'message' => 'Exam deleted successfully']);
    }




    public function indexExamSchedule()
{
    $schoolId = $this->getSchoolId();

    $schedules = ExamSchedule::with(['exam', 'subject'])->latest()->get();

    $exams = Exam::where('school_id', $schoolId)->get();
    $classes = SchoolClass::where('school_id', $schoolId)->get();
    $sections = Section::where('school_id', $schoolId)->get();
    $teachers = Teacher::where('school_id', $schoolId)->get();
    $subjects = Subject::where('school_id', $schoolId)->get(); 

    return view('client.schoolPanel.examinations.examSchedule', compact(
        'schedules', 'exams', 'classes', 'sections', 'teachers', 'subjects'
    ));
}




    // Add Exam
  public function storeExamSchedule(Request $request)
{
    $class = SchoolClass::where('school_id', $this->getSchoolId())->where('id', $request->class_id)->first();
    $section = Section::where('id', $request->section_id)->first();

    // Determine status
    $status = 'Active'; // default

    $examDate = \Carbon\Carbon::parse($request->exam_date);
    $today = \Carbon\Carbon::today();

    // If exam date is in the past and not canceled, mark as Completed
    if ($examDate->lt($today) && empty($request->cancel_reason)) {
        $status = 'Completed';
    }

    // If cancel_reason is provided, mark as Canceled
    if (!empty($request->cancel_reason)) {
        $status = 'Canceled';
    }

    ExamSchedule::create([
        'school_id'    => $this->getSchoolId(),
        'exam_id'      => $request->exam_id,
        'class'        => $class->name,
        'section'      => $section->name,
        'exam_date'    => $request->exam_date,
        'subject_id'   => $request->subject_id,
        'start_time'   => $request->start_time,
        'end_time'     => $request->end_time,
        'duration'     => $request->duration,
        'room_no'      => $request->room_no ,
        'max_marks'    => $request->max_marks,
        'min_marks'    => $request->min_marks,
        'exam_type'    => $request->exam_type,
        'status'       => $status, // save status
        'cancel_reason'=> $request->cancel_reason ?? null, // save cancel reason if provided
    ]);

    return redirect()->back()->with('success', 'Exam schedule added successfully.');
}



    // Update Exam
    // public function updateExamSchedule(Request $request, ExamSchedule $examSchedule)
    // {
    //     $validated = $request->validate([
    //         'exam_id' => 'required|exists:exams,id',
    //         'class' => 'required|string|max:50',
    //         'section' => 'required|string|max:50',
    //         'subject' => 'required|string|max:255',
    //         'exam_date' => 'required|date',
    //         'start_time' => 'required|date_format:H:i',
    //         'end_time' => 'required|date_format:H:i',
    //         'duration' => 'required|integer|min:1',
    //         'room_no' => 'required|string|max:50',
    //         'max_marks' => 'required|integer|min:1',
    //         'min_marks' => 'required|integer|min:0',
    //     ]);

    //     $examSchedule->update($validated);

    //     return redirect()->back()->with('success', 'Exam schedule updated successfully.');
    // }

//     public function editExamSchedule(ExamSchedule $examSchedule)
// {
//     return response()->json($examSchedule);
// }


    // Delete Exam
    public function destroyExamSchedule($examSchedule)
    {

        $debug = ExamSchedule::where('id', $examSchedule)->delete();

        return redirect()->back()->with('success', 'Exam schedule deleted successfully.');
    }


    // Assign Teacher
    public function assignTeacher(Request $request)
    {
        $schedule = ExamSchedule::findOrFail($request->schedule_id);
        $schedule->evaluator_id = json_encode($request->evaluator_id ?? []);
        $schedule->save();
        return redirect()->back()->with('success', 'Teacher(s) assigned successfully.');
    }

    // Get Assign Teacher
    public function getEvaluators($id)
    {
        $schedule = ExamSchedule::findOrFail($id);
        return json_decode($schedule->evaluator_id, true) ?? [];
    }

    // Edit Schedule Exam

}
