<?php

namespace App\Http\Controllers\Client\SchoolPanel;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Models\SchoolEvent;
use App\Models\SchoolProgram;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * Display the calendar view with events, birthdays, and holidays
     */

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




    public function index()
    {
        // Get events for the school
        $events = SchoolEvent::where('school_id', $this->getSchoolId())
            ->get()
            ->map(function ($event) {
                // Format the start and end times properly
                $startTime = $event->start_time ? $event->start_time : '';
                $endTime = $event->end_time ? $event->end_time : '';

                return [
                    'id' => 'event_' . $event->id,
                    'title' => $event->title,
                    'start' => $event->event_date->format('Y-m-d') .
                        ($startTime ? 'T' . $startTime : ''),
                    'end' => $event->event_date->format('Y-m-d') .
                        ($endTime ? 'T' . $endTime : ($startTime ? 'T' . $startTime : '')),
                    'url' => route('school.events.show', $event->id),
                    'backgroundColor' => $this->getEventColor($event->status),
                    'borderColor' => $this->getEventColor($event->status),
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'type' => 'event',
                        'status' => $event->status,
                        'location' => $event->location,
                        'description' => $event->description,
                        'program_id' => $event->program_id
                    ]
                ];
            });

        // Get programs for the school
       $programs = SchoolProgram::where('school_id', $this->getSchoolId())
    ->get()
    ->map(function ($program) {
        return [
            'id' => 'program_' . $program->id,
            'title' => '[Program] ' . $program->title,
            'start' => $program->start_date ? Carbon::parse($program->start_date)->format('Y-m-d') : null,
            'end'   => $program->end_date ? Carbon::parse($program->end_date)->format('Y-m-d') : null,
            'url' => route('school.programs.show', $program->id),
            'backgroundColor' => '#6b21a8',
            'borderColor' => '#6b21a8',
            'textColor' => '#ffffff',
            'allDay' => true,
            'extendedProps' => [
                'type' => 'program',
                'status' => $program->status,
                'coordinator' => $program->coordinator,
                'description' => $program->description,
                'start_date' => $program->start_date,
                'end_date' => $program->end_date
            ]
        ];
    });


        // Get students with birthdays
        $students = collect([]);  // Initialize empty collection

        try {
            // Only proceed if the dob column exists in the students table
            if (Schema::hasColumn('students', 'dob')) {
                $students = Student::where('school_id', $this->getSchoolId())
                    ->whereNotNull('dob')
                    ->get()
                    ->map(function ($student) {
                        $birthDate = Carbon::parse($student->dob);
                        $currentYear = Carbon::now()->year;

                        // Handle class information safely
                        $className = '';
                        if (isset($student->class)) {
                            if (is_object($student->class)) {
                                $className = $student->class->name ?? '';
                            } else {
                                $className = $student->class;
                            }
                        }

                        // Get student name safely
                        $firstName = $student->first_name ?? '';
                        $lastName = $student->last_name ?? '';
                        $fullName = trim("$firstName $lastName");
                        // Fallback to admission number if name is empty
                        if (empty($fullName) && isset($student->admission_number)) {
                            $fullName = "Student " . $student->admission_number;
                        }
                        // Last resort fallback
                        if (empty($fullName)) {
                            $fullName = "Student #" . $student->id;
                        }

                        return [
                            'id' => 'student_' . $student->id,
                            'title' => '🎂 ' . $fullName . '\'s Birthday',
                            'start' => $currentYear . '-' . $birthDate->format('m-d'),
                            'backgroundColor' => '#8e44ad', // Purple
                            'borderColor' => '#8e44ad',
                            'textColor' => '#ffffff',
                            'allDay' => true,
                            'extendedProps' => [
                                'type' => 'birthday',
                                'subtype' => 'student',
                                'name' => $fullName,
                                'class' => $className,
                                'age' => Carbon::now()->year - $birthDate->year
                            ]
                        ];
                    });
            }
        } catch (QueryException $e) {
            // Log the error but continue with an empty collection
            Log::error('Error fetching student birthdays: ' . $e->getMessage());
        }

        // Get teachers with birthdays
        $teachers = collect([]);  // Initialize empty collection

        try {
            // Only proceed if the date_of_birth column exists in the teachers table
            if (Schema::hasColumn('teachers', 'date_of_birth')) {
                $teachers = Teacher::where('school_id', $this->getSchoolId())
                    ->whereNotNull('date_of_birth')
                    ->get()
                    ->map(function ($teacher) {
                        $birthDate = Carbon::parse($teacher->date_of_birth);
                        $currentYear = Carbon::now()->year;

                        // Handle subject information safely
                        $subjectName = '';
                        if (isset($teacher->subject)) {
                            if (is_object($teacher->subject)) {
                                $subjectName = $teacher->subject->name ?? '';
                            } else {
                                $subjectName = $teacher->subject;
                            }
                        }

                        // Get teacher name safely
                        $firstName = $teacher->first_name ?? '';
                        $lastName = $teacher->last_name ?? '';
                        $fullName = trim("$firstName $lastName");
                        // Fallback to employee ID if name is empty
                        if (empty($fullName) && isset($teacher->employee_id)) {
                            $fullName = "Teacher " . $teacher->employee_id;
                        }
                        // Last resort fallback
                        if (empty($fullName)) {
                            $fullName = "Teacher #" . $teacher->id;
                        }

                        return [
                            'id' => 'teacher_' . $teacher->id,
                            'title' => '🎂 ' . $fullName . '\'s Birthday',
                            'start' => $currentYear . '-' . $birthDate->format('m-d'),
                            'backgroundColor' => '#2980b9', // Blue
                            'borderColor' => '#2980b9',
                            'textColor' => '#ffffff',
                            'allDay' => true,
                            'extendedProps' => [
                                'type' => 'birthday',
                                'subtype' => 'teacher',
                                'name' => $fullName,
                                'subject' => $subjectName,
                                'age' => Carbon::now()->year - $birthDate->year
                            ]
                        ];
                    });
            }
        } catch (QueryException $e) {
            // Log the error but continue with an empty collection
            Log::error('Error fetching teacher birthdays: ' . $e->getMessage());
        }

        // Get holidays
        $holidays = collect([]);  // Initialize empty collection

        try {
            $holidays = Holiday::where('school_id', $this->getSchoolId())
                ->get()
                ->map(function ($holiday) {
                    return [
                        'id' => 'holiday_' . $holiday->id,
                        'title' => '📅 ' . $holiday->title,
                        'start' => $holiday->date,
                        'backgroundColor' => '#e74c3c', // Red
                        'borderColor' => '#e74c3c',
                        'textColor' => '#ffffff',
                        'allDay' => true,
                        'extendedProps' => [
                            'type' => 'holiday',
                            'description' => $holiday->description
                        ]
                    ];
                });
        } catch (QueryException $e) {
            // Log the error but continue with an empty collection
            Log::error('Error fetching holidays: ' . $e->getMessage());
        }

        // 🧾 Exam Schedules
        $exams = collect([]);

        try {
            $exams = \App\Models\ExamSchedule::with('subject')
                ->where('school_id', $this->getSchoolId())
                ->get()
                ->map(function ($exam) {
                    $subjectName = $exam->subject->name ?? null; // safe
                    $status = $exam->status ?? '';

                    // color map by status
                    switch (strtolower($status)) {
                        case 'active':
                            $color = '#16a34a'; // green-600
                            break;
                        case 'completed':
                            $color = '#6b7280'; // gray-500
                            break;
                        case 'canceled':
                        case 'cancelled': // handle both spellings
                            $color = '#ef4444'; // red-500
                            break;
                        default:
                            $color = '#f59e0b'; // amber (default)
                    }

                    return [
                        'id' => 'exam_' . $exam->id,
                        'title' => '🧾 ' . ucfirst($exam->subject->name) . ' Exam',
                        'start' => $exam->exam_date . 'T' . $exam->start_time,
                        'end' => $exam->exam_date . 'T' . $exam->end_time,
                        'backgroundColor' => '#f59e0b', // Amber
                        'borderColor' => '#f59e0b',
                        'textColor' => '#ffffff',
                        'allDay' => false,
                        'extendedProps' => [
                            'subject' => $exam->subject->name ?? '',
                            'type' => 'exams',
                            'class' => $exam->class ?? '',
                            'section' => $exam->section ?? '',
                            'room_no' => $exam->room_no ?? '',
                            'duration' => $exam->duration ? $exam->duration . ' mins' : '',
                            'max_marks' => $exam->max_marks ?? '',
                            'min_marks' => $exam->min_marks ?? '',
                            'exam_type' => ucfirst($exam->exam_type ?? ''),
                            'status' => $exam->status ?? '',
                            'cancel_reason' => $exam->cancel_reason ?? '',
                        ]
                    ];
                });
        } catch (QueryException $e) {
            Log::error('Error fetching exam schedules: ' . $e->getMessage());
        }


        // Combine all calendar items
        $calendarItems = $events->concat($programs)->concat($students)->concat($teachers)->concat($holidays)->concat($exams);


        return view('client.schoolPanel.calendar.index', [
            'calendarItems' => $calendarItems
        ]);
    }

    /**
     * Store a new holiday
     */
    public function storeHoliday(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        $holiday = new Holiday();
        $holiday->school_id = $this->getSchoolId();
        $holiday->title = $request->title;
        $holiday->date = $request->date;
        $holiday->description = $request->description;
        $holiday->save();

        return response()->json([
            'success' => true,
            'message' => 'Holiday added successfully',
            'holiday' => [
                'id' => 'holiday_' . $holiday->id,
                'title' => '📅 ' . $holiday->title,
                'start' => $holiday->date,
                'backgroundColor' => '#e74c3c',
                'borderColor' => '#e74c3c',
                'textColor' => '#ffffff',
                'allDay' => true,
                'extendedProps' => [
                    'type' => 'holiday',
                    'description' => $holiday->description
                ]
            ]
        ]);
    }

    /**
     * Update a holiday
     */
    public function updateHoliday(Request $request, $id)
    {
        $holiday = Holiday::findOrFail($id);

        // Check if the holiday belongs to the authenticated user's school
        if ($holiday->school_id != $this->getSchoolId()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        $holiday->title = $request->title;
        $holiday->date = $request->date;
        $holiday->description = $request->description;
        $holiday->save();

        return response()->json([
            'success' => true,
            'message' => 'Holiday updated successfully'
        ]);
    }

    /**
     * Delete a holiday
     */
    public function deleteHoliday($id)
    {
        $holiday = Holiday::findOrFail($id);

        // Check if the holiday belongs to the authenticated user's school
        if ($holiday->school_id != $this->getSchoolId()) {
            abort(403, 'Unauthorized action.');
        }

        $holiday->delete();

        return response()->json([
            'success' => true,
            'message' => 'Holiday deleted successfully'
        ]);
    }

    /**
     * Get color based on event status
     */
    private function getEventColor($status)
    {
        switch ($status) {
            case 'upcoming':
                return '#3498db'; // Blue
            case 'ongoing':
                return '#2ecc71'; // Green
            case 'completed':
                return '#95a5a6'; // Gray
            case 'cancelled':
                return '#e74c3c'; // Red
            default:
                return '#3498db'; // Default blue
        }
    }
}
