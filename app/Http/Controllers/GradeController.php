<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Student;

use App\Models\Exam;

use App\Models\ExamSchedule;
use App\Models\ExamResult;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Auth; // or Session

class GradeController extends Controller
{




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






    /**
     * Display a listing of grades.
     */
    public function index()
    {
        $schoolId = $this->getSchoolId(); // Or Session::get('school_id')
        $grades = Grade::where('school_id', $schoolId)->orderBy('min_score', 'desc')->get();
// dd($schoolId);

        return view('client.schoolPanel.examinations.grades', compact('grades'));
    }

    /**
     * Store a newly created grade.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:50',
            'min_score'  => 'required|integer|min:0',
            'max_score'  => 'required|integer|min:0|gte:min_score',
            'description' => 'nullable|string',
        ]);

        Grade::create([
            'school_id'  => $this->getSchoolId(),
            'name'       => $request->name,
            'min_score'  => $request->min_score,
            'max_score'  => $request->max_score,
            'description' => $request->description,
            'status'     => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Grade created successfully.');
    }

    /**
     * Update an existing grade.
     */
    public function update(Request $request, Grade $grade)
    {
        $request->validate([
            'name'       => 'required|string|max:50',
            'min_score'  => 'required|integer|min:0',
            'max_score'  => 'required|integer|min:0|gte:min_score',
            'description' => 'nullable|string',
        ]);

        $grade->update([
            'name'       => $request->name,
            'min_score'  => $request->min_score,
            'max_score'  => $request->max_score,
            'description' => $request->description,
            'status'     => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Grade updated successfully.');
    }

    /**
     * Remove a grade.
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->back()->with('success', 'Grade deleted successfully.');
    }




    public function showResultPageForAdmin()
    {
        $schoolId = $this->getSchoolId();

        // Fetch all exam types for this school
        $examTypes = Exam::where('school_id', $schoolId)->pluck('name', 'id');

        // Fetch distinct academic sessions for this school
        $sessions = Exam::where('school_id', $schoolId)->distinct()->pluck('academic_session');



        return view('client.schoolPanel.exam.result', compact('examTypes', 'sessions'));
    }

    public function fetchResults(Request $request)
    {
        // dd($request->all());

        $exam_type = $request->input('exam_type');           // "Hello testing"
        $academic_session = $request->input('academic_session'); // "2021-2022"
        $class = $request->input('class');                   // "1"
        $section = $request->input('section');

        $schoolId = $this->getSchoolId();

// dd($schoolId);
        $request->validate([
            'academic_session' => 'required|string',
            'exam_type' => 'required|string',
            'class' => 'required|string',
            'section' => 'required|string',
        ]);


        $students = Student::with(['class', 'section'])
            ->where('school_id', $schoolId)
            ->get();


            // dd($students);
        if ($students->isEmpty()) {
            return view('client.schoolPanel.exam.result', [
                'results' => collect(),
                'students' => null,
                'examName' => $request->exam_type,
                'academic_session' => $request->academic_session,
                'totalMarksObtained' => 0,
                'totalMaxMarks' => 0,
                'percentage' => 0,
                'overallGrade' => 'N/A',
                'ranks' => [],
                'examTypes' => Exam::where('school_id', $schoolId)->pluck('name', 'id'),
                'sessions' => Exam::where('school_id', $schoolId)->distinct()->pluck('academic_session'),
                'message' => 'No students found for the selected class and section.',
            ]);
        }


        $examSchedules = DB::table('exam_results as er')
            ->leftJoin('students as s', 's.id', '=', 'er.student_id')
            ->leftJoin('exam_schedules as es', 'es.id', '=', 'er.exam_schedule_id')
            ->leftJoin('exams as e', 'e.id', '=', 'es.exam_id')
            ->select(
                'er.id as exam_result_id',
                'er.school_id',
                'er.exam_schedule_id',
                'er.student_id',
                'er.subject_id',
                'e.name as exam_name',         
                'er.teacher_id',
                'er.marks_obtained',
                'er.total_marks',
                'er.remarks',
                's.first_name',
                's.last_name',
                's.roll_number',
                's.class_id',
                's.section_id',
                'e.name as exam_name',
                'es.class as exam_class',
                'es.section as exam_section',
                'es.subject_id as exam_subject',
                'es.exam_date',
                // 'es.exam_type as schedule_exam_type',
                'er.exam_type as result_exam_type', 
                'es.exam_type as schedule_exam_type', 
                'e.academic_session'
            )
            ->where('er.school_id', $schoolId)
            ->where('es.class', 'like', '%' . $request->class . '%')
            ->where('es.section', 'like', '%' . $request->section . '%')
            // ->where('er.exam_type', 'theory') 
                ->where('e.name', $exam_type) 
            ->where('e.academic_session', $request->input('academic_session'))
            ->orderBy('er.created_at', 'asc')
            ->get();
        // dd($examSchedules);


        $resultsGrouped = $examSchedules->groupBy('exam_schedule_id');

        $ranks = [];

        foreach ($resultsGrouped as $scheduleId => $examResults) {
            $sorted = $examResults->sortByDesc('marks_obtained')->values();
            $rank = 1;
            $prevMarks = null;
            $sameRankCount = 0;

            foreach ($sorted as $index => $res) {
                if ($prevMarks !== null && $res->marks_obtained == $prevMarks) {
                    $sameRankCount++;
                } else {
                    $rank += $sameRankCount;
                    $sameRankCount = 0;
                }

                $ranks[$scheduleId][$res->student_id] = $rank;

                $prevMarks = $res->marks_obtained;
                $rank++;
            }
        }


        if ($examSchedules->isEmpty()) {
            return view('client.schoolPanel.exam.result', [
                'results' => collect(),
                'students' => $students,
                'examName' => $request->exam_type,
                'academic_session' => $request->academic_session,
                'totalMarksObtained' => 0,
                'totalMaxMarks' => 0,
                'percentage' => 0,
                'overallGrade' => 'N/A',
                'ranks' => [],
                'examTypes' => Exam::where('school_id', $schoolId)->pluck('name', 'id'),
                'sessions' => Exam::where('school_id', $schoolId)->distinct()->pluck('academic_session'),
                'message' => 'No exam schedules found for these filters.',
            ]);
        }

        $examScheduleIds = $examSchedules->keys();
// dd($examScheduleIds);

        $results = ExamResult::with(['subject', 'student'])
            // ->whereIn('exam_schedule_id', $examScheduleIds)
            ->get();

// dd($results);

        if ($results->isEmpty()) {
            return view('client.schoolPanel.exam.result', [
                'results' => collect(),
                'students' => $students,
                'examName' => $request->exam_type,
                'academic_session' => $request->academic_session,
                'totalMarksObtained' => 0,
                'totalMaxMarks' => 0,
                'percentage' => 0,
                'overallGrade' => 'N/A',
                'ranks' => [],
                'examTypes' => Exam::where('school_id', $schoolId)->pluck('name', 'id'),
                'sessions' => Exam::where('school_id', $schoolId)->distinct()->pluck('academic_session'),
                'message' => 'No results found for this exam.',
            ]);
        }


        $results = $examSchedules->map(function ($result) use ($schoolId) {
            $result->exam_type_result = $result->result_exam_type; 

            $result->exam_type = $result->schedule_exam_type; 
            $percentage = ($result->total_marks > 0)
                ? ($result->marks_obtained / $result->total_marks) * 100
                : 0;

            $result->grade = Grade::where('school_id', $schoolId)
                ->where('status', 1)
                ->where('min_score', '<=', $percentage)
                ->where('max_score', '>=', $percentage)
                ->value('name') ?? 'N/A';

            return $result;
        });


        $totalMarksObtained = $results->sum('marks_obtained');
        $totalMaxMarks = $results->sum('total_marks');
        $percentage = $totalMaxMarks > 0 ? ($totalMarksObtained / $totalMaxMarks) * 100 : 0;

        $overallGrade = Grade::where('school_id', $schoolId)
            ->where('status', 1)
            ->where('min_score', '<=', $percentage)
            ->where('max_score', '>=', $percentage)
            ->value('name') ?? 'N/A';


        $allStudentTotals = ExamResult::whereIn('exam_schedule_id', $examScheduleIds)
            ->selectRaw('SUM(marks_obtained) as total_marks, student_id')
            ->groupBy('student_id')
            ->orderByDesc('total_marks')
            ->get();


        return view('client.schoolPanel.exam.result', [
            'results' => $results,
            'students' => $students,
            'examName' => $request->exam_type,
            'academic_session' => $request->academic_session,
            'totalMarksObtained' => $totalMarksObtained,
            'totalMaxMarks' => $totalMaxMarks,
            'percentage' => $percentage,
            'overallGrade' => $overallGrade,
            'ranks' => $ranks,
            'examTypes' => Exam::where('school_id', $schoolId)->pluck('name', 'id'),
            'sessions' => Exam::where('school_id', $schoolId)->distinct()->pluck('academic_session'),
            'message' => null,
            'resultsGrouped' => $resultsGrouped,
        ]);
    }
}
