<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ExamSchedule;
use App\Models\ExamResult;
use Illuminate\Support\Facades\Session;
use App\Models\Student;
use App\Models\Subject;

use App\Models\Section;

use App\Models\SchoolClass;

use Illuminate\Support\Facades\DB;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherExamResultController extends Controller
{
  public function index()
{
    $teacherId = Session::get('teacher_id');
    if (!$teacherId) {
        return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
    }

    $teacher = Teacher::find($teacherId);
    if (!$teacher) {
        return redirect()->route('teacher.login')->with('error', 'Teacher not found.');
    }

    // Fetch exams assigned to this teacher and status Completed
    $schedules = ExamSchedule::with('exam')
        ->where('status', 'Completed')
        ->where('subject_id', $teacher->subject_id) 
        // ->whereRaw("JSON_CONTAINS(evaluator_id, '\"$teacher->id\"')") // only assigned to this teacher
        ->orderBy('exam_date', 'desc')
        ->get();

      
// dd($schedules);
    return view('client.teacher.result.index', compact('schedules'));
}


    // Show Exam Result
    public function show($scheduleId)
{
    $teacherId = Session::get('teacher_id');

    if (!$teacherId) {
        return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
    }

    // ✅ Proper teacher query with join
 $teacher = DB::table('teachers')
    ->leftJoin('subjects', 'subjects.id', '=', 'teachers.subject_id')
    ->select(
        'teachers.*',
        'subjects.id as subject_id',
        'subjects.name as subject_name'
    )
    ->where('teachers.id', $teacherId)
    ->first();


    if (!$teacher) {
        return redirect()->route('teacher.login')->with('error', 'Teacher not found.');
    }

    // ✅ Load exam schedule with exam relation
    $schedule = ExamSchedule::with('exam')->findOrFail($scheduleId);

    // ✅ Ensure section exists for this teacher’s school
    $section = Section::firstOrCreate([
        'name' => trim($schedule->section),
        'school_id' => $teacher->school_id,
    ]);

    if (!$section) {
        return back()->with('error', 'Section not found for this schedule.');
    }

    // ✅ Find class within this section
    $class = SchoolClass::where('name', $schedule->class)
        ->where('section_id', $section->id)
        ->first();

    if (!$class) {
        return back()->with('error', 'Class not found for this schedule.');
    }

    // ✅ Get students for class + section
    $students = Student::where('class_id', $class->id)
        ->where('section_id', $section->id)
        ->orderBy('first_name')
        ->get();

    // ✅ Fetch existing marks for this teacher
    $existingMarks = ExamResult::where('exam_schedule_id', $scheduleId)
        ->where('teacher_id', $teacher->id)
        ->get()
        ->keyBy('student_id');

    // ✅ Return view with all required data
    return view('client.teacher.result.upload', compact('schedule', 'students', 'teacher', 'existingMarks'));
}



//     // Add Exam Result 
//     public function store(Request $request, $scheduleId)
//     {
        
//         // dd($request->all());
//      $subject_id = $request->post('subject_id');

// $results['exam_t'] = DB::table('exam_schedules as es')
//     ->leftJoin('exam_results as er', 'er.exam_schedule_id', '=', 'es.id')
//     ->select(
//         'es.id as schedule_id',
//         'es.exam_type as schedule_exam_type', // exam_type from exam_schedules
//         'er.id as result_id',
//         'er.student_id',
//         'er.exam_type as result_exam_type',
//         'er.marks_obtained',
//         'er.total_marks'
//     )
//     ->where('er.subject_id', $subject_id)
//     ->get();

//         echo'<pre>';
//         print_r($results);
//         die();
//         $request->validate([
//             'total_marks' => 'required|numeric|min:1',
//             'marks' => 'required|array',
//             'marks.*.student_id' => 'required|exists:students,id',
//             'marks.*.marks_obtained' => [
//                 'required',
//                 'numeric',
//                 'min:0',
//                 function ($attribute, $value, $fail) use ($request) {
//                     if ($value > $request->total_marks) {
//                         $fail('Marks obtained cannot be greater than total marks.');
//                     }
//                 }
//             ],
//         ]);
//         $teacherId = Session::get('teacher_id');

//         if (!$teacherId) {
//             return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
//         }
//         $teacher = Teacher::find($teacherId);

//         if (!$teacher) {
//             return redirect()->route('teacher.login')->with('error', 'Teacher not found.');
//         }
//         $schedule = ExamSchedule::findOrFail($scheduleId);
// // dd($schedule);
//         foreach ($request->marks as $markData) {
//             ExamResult::updateOrCreate(
//                 [
//                     'exam_schedule_id' => $schedule->id,
//                     'student_id' => $markData['student_id'],
//                     'subject_id' => $request->subject_id,
//                 ],
//                 [
//                     'school_id' => $teacher->school_id,
//                     'teacher_id' => $teacher->id,
//                     'marks_obtained' => $markData['marks_obtained'],
//                     'total_marks' => $request->total_marks,
//                     'remarks' => $markData['remarks'] ?? null,
//                     'exam_type'=> "practical"
//                 ]
//             );
//         }
//         return redirect()->route('teacher.exams.results.index')
//             ->with('success', 'Marks uploaded successfully.');
//     }
public function store(Request $request, $scheduleId)
{
    // dd($request->all());
    // echo'<pre>';
    // print_r($request->all());
    // die();  
    // 🔹 Step 1: Validate input
    $request->validate([
        'total_marks' => 'required|numeric|min:1',
        'marks' => 'required|array',
        'marks.*.student_id' => 'required|exists:students,id',
        'marks.*.marks_obtained' => [
            'required',
            'numeric',
            'min:0',
            function ($attribute, $value, $fail) use ($request) {
                if ($value > $request->total_marks) {
                    $fail('Marks obtained cannot be greater than total marks.');
                }
            }
        ],
    ]);

    // 🔹 Step 2: Get logged-in teacher
    $teacherId = Session::get('teacher_id');
    if (!$teacherId) {
        return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
    }

    $teacher = Teacher::find($teacherId);
    if (!$teacher) {
        return redirect()->route('teacher.login')->with('error', 'Teacher not found.');
    }

    // 🔹 Step 3: Get the exam schedule and its exam_type
    $schedule = ExamSchedule::findOrFail($scheduleId);

    // Just to confirm what you're getting (optional debug)
    // dd($schedule->exam_type);

    // 🔹 Step 4: Store or update each student's result
    foreach ($request->marks as $markData) {
        ExamResult::updateOrCreate(
            [
                'exam_schedule_id' => $schedule->id,
                'student_id' => $markData['student_id'],
                'subject_id' => $request->subject_id,
            ],
            [
                'school_id' => $teacher->school_id,
                'teacher_id' => $teacher->id,
                'marks_obtained' => $markData['marks_obtained'],
                'total_marks' => $request->total_marks,
                'remarks' => $markData['remarks'] ?? null,
                'exam_type' => $schedule->exam_type, // ✅ fetch from exam_schedules
            ]
        );
    }

    // 🔹 Step 5: Redirect back with success message
    return redirect()->route('teacher.exams.results.index')
        ->with('success', 'Marks uploaded successfully.');
}


    // View Exam Result
    public function viewResults($scheduleId)
    {
        $teacherId = Session::get('teacher_id');

        if (!$teacherId) {
            return redirect()->route('teacher.login')->with('error', 'Please login to access this page.');
        }
        $teacher = Teacher::findOrFail($teacherId);
        $results = ExamResult::with(['student', 'subject', 'examSchedule.exam'])
            ->where('exam_schedule_id', $scheduleId)
            ->where('teacher_id', $teacher->id)
            ->get();

        $groupedResults = $results->groupBy(function ($item) {
            return $item->examSchedule->exam->name ?? 'Unnamed Exam';
        });

        return view('client.teacher.result.show', [
            'teacher' => $teacher,
            'results' => $groupedResults
        ]);
    }
}
