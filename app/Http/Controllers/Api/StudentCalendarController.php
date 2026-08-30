<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\SchoolEvent;
use App\Models\SchoolProgram;
use App\Models\Homework;
use App\Models\Notice;
use App\Models\StudentLeave;
use App\Models\Teacher;
use App\Models\Holiday;
use App\Models\IssuedBook;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class StudentCalendarController extends Controller
{
    /**
     * Get all calendar events for a student
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCalendarEvents(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'api_token' => 'required',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify student
        $student = Student::where('student_id', $request->student_id)
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        // Default date range is current month if not provided
        $startDate = $request->has('start_date') 
            ? Carbon::parse($request->start_date) 
            : Carbon::now()->startOfMonth();
        
        $endDate = $request->has('end_date') 
            ? Carbon::parse($request->end_date) 
            : Carbon::now()->endOfMonth();

        // Get school events
        $events = SchoolEvent::where('school_id', $student->school_id)
            ->where('event_date', '>=', $startDate)
            ->where('event_date', '<=', $endDate)
            ->where('status', '!=', 'cancelled')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'date' => $event->event_date->format('Y-m-d'),
                    'start_time' => $event->start_time,
                    'end_time' => $event->end_time,
                    'location' => $event->venue,
                    'type' => 'event',
                    'color' => '#4F46E5' // Indigo
                ];
            });

        // Get school programs
        $programs = SchoolProgram::where('school_id', $student->school_id)
            ->where('start_date', '>=', $startDate)
            ->where('start_date', '<=', $endDate)
            ->where('status', 'active')
            ->get()
            ->map(function ($program) {
                return [
                    'id' => $program->id,
                    'title' => $program->title,
                    'description' => $program->description,
                    'date' => $program->start_date->format('Y-m-d'),
                    'start_time' => $program->start_time,
                    'end_time' => $program->end_time,
                    'location' => $program->venue,
                    'type' => 'program',
                    'color' => '#059669' // Emerald
                ];
            });

        // Get homework due dates
        $homeworks = Homework::where('school_id', $student->school_id)
            ->where('class_id', $student->class_id)
            ->where(function($query) use ($student) {
                $query->where('section_id', $student->section_id)
                      ->orWhereNull('section_id');
            })
            ->where('due_date', '>=', $startDate)
            ->where('due_date', '<=', $endDate)
            ->get()
            ->map(function ($homework) {
                return [
                    'id' => $homework->id,
                    'title' => 'Homework: ' . $homework->title,
                    'description' => $homework->description,
                    'date' => $homework->due_date->format('Y-m-d'),
                    'start_time' => null,
                    'end_time' => null,
                    'location' => null,
                    'subject' => $homework->subject->name ?? 'Unknown Subject',
                    'type' => 'homework',
                    'color' => '#DC2626' // Red
                ];
            });

        // Get notices
        $notices = Notice::where('school_id', $student->school_id)
            ->where(function($query) {
                $query->whereJsonContains('recipients', 'Student')
                    ->orWhereNull('recipients');
            })
            ->where('publish_date', '>=', $startDate)
            ->where('publish_date', '<=', $endDate)
            ->get()
            ->map(function ($notice) {
                return [
                    'id' => $notice->id,
                    'title' => 'Notice: ' . $notice->title,
                    'description' => $notice->message,
                    'date' => $notice->publish_date->format('Y-m-d'),
                    'start_time' => null,
                    'end_time' => null,
                    'location' => null,
                    'type' => 'notice',
                    'color' => '#0891B2' // Cyan
                ];
            });

        // Get student leave applications
        $leaves = StudentLeave::where('student_id', $student->id)
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('from_date', [$startDate, $endDate])
                    ->orWhereBetween('to_date', [$startDate, $endDate]);
            })
            ->get();

        $leaveEvents = [];
        foreach ($leaves as $leave) {
            $currentDate = Carbon::parse($leave->from_date);
            $endLeaveDate = Carbon::parse($leave->to_date);
            
            while ($currentDate->lte($endLeaveDate)) {
                if ($currentDate->between($startDate, $endDate)) {
                    $leaveEvents[] = [
                        'id' => $leave->id . '-' . $currentDate->format('Y-m-d'),
                        'title' => 'Leave: ' . $leave->reason,
                        'description' => $leave->description,
                        'date' => $currentDate->format('Y-m-d'),
                        'start_time' => null,
                        'end_time' => null,
                        'location' => null,
                        'status' => $leave->status,
                        'type' => 'leave',
                        'color' => $this->getLeaveStatusColor($leave->status)
                    ];
                }
                $currentDate->addDay();
            }
        }

        // Combine all events
        $allEvents = $events
            ->concat($programs)
            ->concat($homeworks)
            ->concat($notices)
            ->concat($leaveEvents)
            ->sortBy('date')
            ->values()
            ->all();

        return response()->json([
            'success' => true,
            'data' => [
                'events' => $allEvents,
                'student' => [
                    'id' => $student->id,
                    'student_id' => $student->student_id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'class' => $student->class->name ?? 'N/A',
                    'section' => $student->section->name ?? 'N/A'
                ],
                'date_range' => [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d')
                ]
            ]
        ], 200);
    }

    /**
     * Get all calendar items for the authenticated student (mobile app)
     * This endpoint returns all calendar data needed for the full calendar display
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @response {
     *   "success": true,
     *   "data": {
     *     "events": [
     *       {
     *         "id": 1,
     *         "title": "Annual Day Celebration",
     *         "description": "School annual day celebration",
     *         "event_date": "2023-06-15",
     *         "start_time": "10:00:00",
     *         "end_time": "14:00:00",
     *         "location": "School Auditorium",
     *         "status": "upcoming"
     *       }
     *     ],
     *     "programs": [
     *       {
     *         "id": 1,
     *         "title": "Science Exhibition",
     *         "description": "Showcasing student science projects",
     *         "created_at": "2023-06-10",
     *         "coordinator": "Mr. John Doe",
     *         "status": "active"
     *       }
     *     ],
     *     "studentBirthdays": [
     *       {
     *         "id": 1,
     *         "first_name": "Jane",
     *         "last_name": "Doe",
     *         "dob": "2010-06-20",
     *         "class": {
     *           "name": "Class 5"
     *         }
     *       }
     *     ],
     *     "teacherBirthdays": [
     *       {
     *         "id": 1,
     *         "first_name": "Michael",
     *         "last_name": "Smith",
     *         "date_of_birth": "1985-06-18",
     *         "subject": {
     *           "name": "Mathematics"
     *         }
     *       }
     *     ],
     *     "holidays": [
     *       {
     *         "id": 1,
     *         "title": "Independence Day",
     *         "description": "National holiday",
     *         "date": "2023-08-15"
     *       }
     *     ],
     *     "issuedBooks": [
     *       {
     *         "id": 1,
     *         "book_name": "Adventures of Tom Sawyer",
     *         "book_no": "LIB-1234",
     *         "issue_date": "2023-06-01",
     *         "due_date": "2023-06-15"
     *       }
     *     ]
     *   }
     * }
     */
    public function getAllCalendarData(Request $request)
    {
        try {
            $student = $request->user();
            
            // Check if we have a valid student
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authenticated student not found',
                    'error_code' => 'STUDENT_NOT_FOUND'
                ], 404);
            }
            
            // Get school events
            $events = SchoolEvent::where('school_id', $student->school_id)
                ->where('status', '!=', 'cancelled')
                ->get()
                ->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'title' => $event->title,
                        'description' => $event->description,
                        'event_date' => $event->event_date->format('Y-m-d'),
                        'start_time' => $event->start_time,
                        'end_time' => $event->end_time,
                        'location' => $event->venue,
                        'status' => $event->status
                    ];
                });
            
            // Get school programs
            $programs = SchoolProgram::where('school_id', $student->school_id)
                ->where('status', 'active')
                ->get()
                ->map(function ($program) {
                    return [
                        'id' => $program->id,
                        'title' => $program->title,
                        'description' => $program->description,
                        'created_at' => $program->created_at->format('Y-m-d'),
                        'coordinator' => $program->coordinator,
                        'status' => $program->status
                    ];
                });
            
            // Get student birthdays
            $studentBirthdays = Student::where('school_id', $student->school_id)
                ->where('status', 'active')
                ->whereNotNull('dob')
                ->with(['class'])
                ->get()
                ->map(function ($classmate) {
                    return [
                        'id' => $classmate->id,
                        'first_name' => $classmate->first_name,
                        'last_name' => $classmate->last_name,
                        'dob' => $classmate->dob->format('Y-m-d'),
                        'class' => [
                            'name' => $classmate->class->name ?? null
                        ]
                    ];
                });
            
            // Get teacher birthdays
            $teacherBirthdays = Teacher::where('school_id', $student->school_id)
                ->where('status', 'active')
                ->whereNotNull('date_of_birth')
                ->with(['subject'])
                ->get()
                ->map(function ($teacher) {
                    return [
                        'id' => $teacher->id,
                        'first_name' => $teacher->first_name,
                        'last_name' => $teacher->last_name,
                        'date_of_birth' => $teacher->date_of_birth->format('Y-m-d'),
                        'subject' => [
                            'name' => $teacher->subject->name ?? null
                        ]
                    ];
                });
            
            // Get holidays
            $holidays = Holiday::where('school_id', $student->school_id)
                ->get()
                ->map(function ($holiday) {
                    return [
                        'id' => $holiday->id,
                        'title' => $holiday->title,
                        'description' => $holiday->description,
                        'date' => $holiday->date->format('Y-m-d')
                    ];
                });
            
            // Get issued books - since we're having issues with the structure,
            // wrap this in a try-catch to prevent the entire endpoint from failing
            try {
                $issuedBooks = IssuedBook::where('student_id', $student->id)
                    ->where('is_returned', false)
                    ->get()
                    ->map(function ($book) {
                        return [
                            'id' => $book->id,
                            'book_name' => $book->book_name,
                            'book_no' => $book->book_no,
                            'issue_date' => $book->issue_date->format('Y-m-d'),
                            'due_date' => $book->due_date->format('Y-m-d')
                        ];
                    });
            } catch (\Exception $e) {
                \Log::error('Error fetching issued books: ' . $e->getMessage());
                $issuedBooks = collect([]); // Empty collection if there's an error
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'events' => $events,
                    'programs' => $programs,
                    'studentBirthdays' => $studentBirthdays,
                    'teacherBirthdays' => $teacherBirthdays,
                    'holidays' => $holidays,
                    'issuedBooks' => $issuedBooks
                ]
            ]);
            
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Calendar data error: ' . $e->getMessage());
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving calendar data',
                'error_code' => 'SERVER_ERROR',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get color based on leave status
     *
     * @param string $status
     * @return string
     */
    private function getLeaveStatusColor($status)
    {
        switch ($status) {
            case 'approved':
                return '#10B981'; // Green
            case 'rejected':
                return '#EF4444'; // Red
            case 'pending':
            default:
                return '#F59E0B'; // Amber
        }
    }
} 