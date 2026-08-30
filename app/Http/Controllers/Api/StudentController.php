<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\HomeworkSubmission;

use App\Models\ExamSchedule;


use App\Models\Homework;
use App\Models\TimeTable;
use App\Models\TimeTablePeriod;
use App\Models\Teacher;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Session;

class StudentController extends Controller
{
    /**
     * Get the authenticated student's profile
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        try {
            $student = $request->user()->load('school');
            
            // Check if we have a valid student
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authenticated student not found',
                    'error_code' => 'STUDENT_NOT_FOUND'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'student_id' => $student->student_id,
                    'admission_number' => $student->admission_number,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'email' => $student->email,
                    'phone' => $student->primary_contact,
                    'class' => $student->class->name ?? null,
                    'section' => $student->section->name ?? null,
                    'profile_image' => $student->profile_image ? asset('storage/' . $student->profile_image) : null,
                    'gender' => $student->gender,
                    'dob' => $student->dob ? $student->dob->format('Y-m-d') : null,
                    'blood_group' => $student->blood_group,
                    'address' => $student->current_address,
                    'school_id' => $student->school_id,
                    'status' => $student->status,
                    'academic_year'=>$student->academic_year,
                    'school' => [
                        'name' => $student->school->name ?? null,
                        'logo' => $student->school->logo ? asset('storage/' . $student->school->logo) : null,
                        'tagline' => $student->school->tagline ?? null,
                        'website' => $student->school->website ?? null,
                        'address' => $student->school->address ?? null
                    ],
                    'parent_details' => [
                        'father_name' => $student->father_name,
                        'father_phone' => $student->father_phone_number,
                        'father_email' => $student->father_email,
                        'mother_name' => $student->mother_name,
                        'mother_phone' => $student->mother_phone_number,
                        'mother_email' => $student->mother_email,
                        'guardian_name' => $student->guardian_name,
                        'guardian_phone' => $student->guardian_phone_number,
                        'guardian_email' => $student->guardian_email,
                    ],
                ]
            ]);
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Student profile error: ' . $e->getMessage());
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving the profile',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
    
    /**
     * Get the student's timetable
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function timetable(Request $request)
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
            
            if (!$student->class_id || !$student->section_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not assigned to any class or section',
                    'error_code' => 'NO_CLASS_ASSIGNED'
                ], 404);
            }
            
            $timetable = TimeTable::where('school_id', $student->school_id)
                            ->where('class_name', $student->class->name ?? '')
                            ->where('section_id', $student->section_id)
                            ->first();
                            
            if (!$timetable) {
                return response()->json([
                    'success' => false,
                    'message' => 'No timetable found for this class',
                    'error_code' => 'TIMETABLE_NOT_FOUND'
                ], 404);
            }
            
            try {
                $periods = TimeTablePeriod::where('timetable_id', $timetable->id)
                        ->with(['subjectRelation', 'teacherRelation'])
                        ->get()
                        ->map(function($period) {
                            $data = [
                                'id' => $period->id,
                                'day' => $period->day,
                                'start_time' => $period->time_from,
                                'end_time' => $period->time_to,
                                'period_type' => $period->period_type
                            ];
                            
                            if ($period->period_type === 'regular') {
                                $data['subject'] = [
                                    'id' => $period->subject,
                                    'name' => $period->subjectRelation ? $period->subjectRelation->name : 'Unknown Subject'
                                ];
                                $data['teacher'] = [
                                    'id' => $period->teacher,
                                    'name' => $period->teacherRelation ? $period->teacherRelation->first_name . ' ' . $period->teacherRelation->last_name : 'Unknown Teacher'
                                ];
                            } else {
                                $data['name'] = $period->name;
                            }
                            
                            return $data;
                        });
            } catch (Exception $e) {
                // If there's an error in processing periods, return empty array but still respond
                \Log::error('Error processing timetable periods: ' . $e->getMessage());
                $periods = [];
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'class' => $student->class->name ?? null,
                    'section' => $student->section->name ?? null,
                    'periods' => $periods
                ]
            ]);
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Student timetable error: ' . $e->getMessage());
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving the timetable',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get basic information about the authenticated student
     * Useful for quick validation of token and retrieving essential details
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails(Request $request)
    {
        try {
            $student = $request->user()->load(['school', 'class', 'section']);
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authenticated student not found',
                    'error_code' => 'STUDENT_NOT_FOUND'
                ], 404);
            }
            
            // Return comprehensive student information
            return response()->json([
                'success' => true,
                'data' => [
                    'token' => $request->bearerToken(),
                    'student_id' => $student->student_id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name,
                    'email' => $student->email,
                    'admission_number' => $student->admission_number,
                    'gender' => $student->gender,
                    'dob' => $student->dob ? $student->dob->format('Y-m-d') : null,
                    'phone' => $student->primary_contact,
                    'blood_group' => $student->blood_group,
                    'class' => $student->class->name ?? null,
                    'class_id' => $student->class_id,
                    'section' => $student->section->name ?? null,
                    'section_id' => $student->section_id,
                    'roll_number' => $student->roll_number,
                    'profile_image' => $student->profile_image ? asset('storage/' . $student->profile_image) : null,
                    'school_id' => $student->school_id,
                    'academic_year' => $student->academic_year,
                    'status' => $student->status,
                    'house' => $student->house,
                    'religion' => $student->religion,
                    'category' => $student->category,
                    'school' => [
                        'name' => $student->school->name ?? null,
                        'logo' => $student->school->logo ? asset('storage/' . $student->school->logo) : null,
                        'tagline' => $student->school->tagline ?? null
                    ],
                    'parent_details' => [
                        'father_name' => $student->father_name,
                        'father_phone' => $student->father_phone_number,
                        'mother_name' => $student->mother_name,
                        'mother_phone' => $student->mother_phone_number
                    ]
                ]
            ]);
            
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Student details error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving student details',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get the student's timetable for the current day
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function todayTimetable(Request $request)
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
            
            if (!$student->class_id || !$student->section_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not assigned to any class or section',
                    'error_code' => 'NO_CLASS_ASSIGNED'
                ], 404);
            }
            
            // Get the current day of the week
            $currentDay = now()->format('l'); // Returns day name (Monday, Tuesday, etc.)
            
            $timetable = TimeTable::where('school_id', $student->school_id)
                            ->where('class_name', $student->class->name ?? '')
                            ->where('section_id', $student->section_id)
                            ->first();
                            
            if (!$timetable) {
                return response()->json([
                    'success' => false,
                    'message' => 'No timetable found for this class',
                    'error_code' => 'TIMETABLE_NOT_FOUND'
                ], 404);
            }
            
            try {
                $periods = TimeTablePeriod::where('timetable_id', $timetable->id)
                        ->where('day', $currentDay)
                        ->with(['subjectRelation', 'teacherRelation'])
                        ->orderBy('time_from')
                        ->get()
                        ->map(function($period) {
                            $data = [
                                'id' => $period->id,
                                'day' => $period->day,
                                'start_time' => $period->time_from,
                                'end_time' => $period->time_to,
                                'period_type' => $period->period_type
                            ];
                            
                            if ($period->period_type === 'regular') {
                                $data['subject'] = [
                                    'id' => $period->subject,
                                    'name' => $period->subjectRelation ? $period->subjectRelation->name : 'Unknown Subject'
                                ];
                                $data['teacher'] = [
                                    'id' => $period->teacher,
                                    'name' => $period->teacherRelation ? $period->teacherRelation->first_name . ' ' . $period->teacherRelation->last_name : 'Unknown Teacher'
                                ];
                            } else {
                                $data['name'] = $period->name;
                            }
                            
                            return $data;
                        });
            } catch (Exception $e) {
                // If there's an error in processing periods, return empty array but still respond
                \Log::error('Error processing today\'s timetable periods: ' . $e->getMessage());
                $periods = [];
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'class' => $student->class->name ?? null,
                    'section' => $student->section->name ?? null,
                    'day' => $currentDay,
                    'date' => now()->format('Y-m-d'),
                    'periods' => $periods
                ]
            ]);
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Student today\'s timetable error: ' . $e->getMessage());
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving today\'s timetable',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
    
    /**
     * Get the student's weekly timetable organized by days
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function weeklyTimetable(Request $request)
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
            
            if (!$student->class_id || !$student->section_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not assigned to any class or section',
                    'error_code' => 'NO_CLASS_ASSIGNED'
                ], 404);
            }
            
            $timetable = TimeTable::where('school_id', $student->school_id)
                            ->where('class_name', $student->class->name ?? '')
                            ->where('section_id', $student->section_id)
                            ->first();
                            
            if (!$timetable) {
                return response()->json([
                    'success' => false,
                    'message' => 'No timetable found for this class',
                    'error_code' => 'TIMETABLE_NOT_FOUND'
                ], 404);
            }
            
            try {
                $allPeriods = TimeTablePeriod::where('timetable_id', $timetable->id)
                        ->with(['subjectRelation', 'teacherRelation'])
                        ->orderBy('day')
                        ->orderBy('time_from')
                        ->get();
                
                // Group by days
                $dayMap = [
                    'Monday' => [],
                    'Tuesday' => [],
                    'Wednesday' => [],
                    'Thursday' => [],
                    'Friday' => [],
                    'Saturday' => [],
                    'Sunday' => []
                ];
                
                foreach ($allPeriods as $period) {
                    $data = [
                        'id' => $period->id,
                        'start_time' => $period->time_from,
                        'end_time' => $period->time_to,
                        'period_type' => $period->period_type
                    ];
                    
                    if ($period->period_type === 'regular') {
                        $data['subject'] = [
                            'id' => $period->subject,
                            'name' => $period->subjectRelation ? $period->subjectRelation->name : 'Unknown Subject'
                        ];
                        $data['teacher'] = [
                            'id' => $period->teacher,
                            'name' => $period->teacherRelation ? $period->teacherRelation->first_name . ' ' . $period->teacherRelation->last_name : 'Unknown Teacher'
                        ];
                    } else {
                        $data['name'] = $period->name;
                    }
                    
                    $dayMap[$period->day][] = $data;
                }
                
                // Remove empty days
                foreach ($dayMap as $day => $periods) {
                    if (empty($periods)) {
                        unset($dayMap[$day]);
                    }
                }
                
            } catch (Exception $e) {
                // If there's an error in processing periods, return empty array but still respond
                \Log::error('Error processing weekly timetable periods: ' . $e->getMessage());
                $dayMap = [];
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'class' => $student->class->name ?? null,
                    'section' => $student->section->name ?? null,
                    'timetable' => $dayMap
                ]
            ]);
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Student weekly timetable error: ' . $e->getMessage());
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving the weekly timetable',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get the list of teachers from the student's timetable
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function timetableTeachers(Request $request)
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
            
            if (!$student->class_id || !$student->section_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not assigned to any class or section',
                    'error_code' => 'NO_CLASS_ASSIGNED'
                ], 404);
            }
            
            $timetable = TimeTable::where('school_id', $student->school_id)
                            ->where('class_name', $student->class->name ?? '')
                            ->where('section_id', $student->section_id)
                            ->first();
                            
            if (!$timetable) {
                return response()->json([
                    'success' => false,
                    'message' => 'No timetable found for this class',
                    'error_code' => 'TIMETABLE_NOT_FOUND'
                ], 404);
            }
            
            try {
                // Get all unique teacher IDs from the timetable
                $teacherIds = TimeTablePeriod::where('timetable_id', $timetable->id)
                        ->where('period_type', 'regular')
                        ->whereNotNull('teacher')
                        ->pluck('teacher')
                        ->unique()
                        ->values();
                
                // Get teacher details
                $teachers = Teacher::whereIn('id', $teacherIds)
                        ->get()
                        ->map(function($teacher) use ($timetable) {
                            // Get the subjects taught by this teacher in this class
                            $subjects = TimeTablePeriod::where('timetable_id', $timetable->id)
                                ->where('teacher', $teacher->id)
                                ->with('subjectRelation')
                                ->get()
                                ->pluck('subjectRelation')
                                ->unique('id')
                                ->filter()
                                ->values()
                                ->map(function($subject) {
                                    return [
                                        'id' => $subject->id,
                                        'name' => $subject->name
                                    ];
                                });
                            
                            return [
                                'id' => $teacher->id,
                                'employee_id' => $teacher->employee_id,
                                'name' => $teacher->first_name . ' ' . $teacher->last_name,
                                'email' => $teacher->email,
                                'gender' => $teacher->gender,
                                'contact' => $teacher->primary_contact,
                                'profile_image' => $teacher->profile_image ? asset('storage/' . $teacher->profile_image) : null,
                                'qualification' => $teacher->qualification,
                                'experience' => $teacher->work_experience,
                                'subjects' => $subjects
                            ];
                        });
                
            } catch (Exception $e) {
                // If there's an error in processing teachers, return empty array but still respond
                \Log::error('Error processing timetable teachers: ' . $e->getMessage());
                $teachers = [];
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'class' => $student->class->name ?? null,
                    'section' => $student->section->name ?? null,
                    'teachers_count' => count($teachers),
                    'teachers' => $teachers
                ]
            ]);
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Student timetable teachers error: ' . $e->getMessage());
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving the teachers list',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get teacher details by ID
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function teacherDetails(Request $request, $id)
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
            
            // Find the teacher
            $teacher = Teacher::where('id', $id)
                        ->where('school_id', $student->school_id)
                        ->first();
                        
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher not found',
                    'error_code' => 'TEACHER_NOT_FOUND'
                ], 404);
            }
            
            // Get teacher's schedule from the timetable for the student's class
            $timetable = TimeTable::where('school_id', $student->school_id)
                            ->where('class_name', $student->class->name ?? '')
                            ->where('section_id', $student->section_id)
                            ->first();
            
            $schedule = [];
            
            if ($timetable) {
                $periods = TimeTablePeriod::where('timetable_id', $timetable->id)
                        ->where('teacher', $teacher->id)
                        ->with('subjectRelation')
                        ->orderBy('day')
                        ->orderBy('time_from')
                        ->get();
                
                // Group by days
                $dayMap = [
                    'Monday' => [],
                    'Tuesday' => [],
                    'Wednesday' => [],
                    'Thursday' => [],
                    'Friday' => [],
                    'Saturday' => [],
                    'Sunday' => []
                ];
                
                foreach ($periods as $period) {
                    $dayMap[$period->day][] = [
                        'id' => $period->id,
                        'start_time' => $period->time_from,
                        'end_time' => $period->time_to,
                        'subject' => $period->subjectRelation ? [
                            'id' => $period->subjectRelation->id,
                            'name' => $period->subjectRelation->name
                        ] : null
                    ];
                }
                
                // Remove empty days
                foreach ($dayMap as $day => $periods) {
                    if (empty($periods)) {
                        unset($dayMap[$day]);
                    }
                }
                
                $schedule = $dayMap;
            }
            
            // Return comprehensive teacher information
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $teacher->id,
                    'employee_id' => $teacher->employee_id,
                    'name' => $teacher->first_name . ' ' . $teacher->last_name,
                    'first_name' => $teacher->first_name,
                    'last_name' => $teacher->last_name,
                    'email' => $teacher->email,
                    'gender' => $teacher->gender,
                    'primary_contact' => $teacher->primary_contact,
                    'date_of_birth' => $teacher->date_of_birth ? $teacher->date_of_birth->format('Y-m-d') : null,
                    'date_of_joining' => $teacher->date_of_joining ? $teacher->date_of_joining->format('Y-m-d') : null,
                    'profile_image' => $teacher->profile_image ? asset('storage/' . $teacher->profile_image) : null,
                    'blood_group' => $teacher->blood_group,
                    'qualification' => $teacher->qualification,
                    'work_experience' => $teacher->work_experience,
                    'languages_known' => $teacher->languages_known,
                    'subject' => $teacher->subject()->first() ? [
                        'id' => $teacher->subject()->first()->id,
                        'name' => $teacher->subject()->first()->name
                    ] : null,
                    'schedule' => $schedule
                ]
            ]);
            
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Teacher details error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving teacher details',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get all student birthdays from the same school
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function allBirthdays(Request $request)
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
            
            // Get all students with birthdays from the same school
            $allStudents = Student::where('school_id', $student->school_id)
                ->where('status', 'active')
                ->with(['class', 'section'])
                ->get()
                ->filter(function($classmate) {
                    // Only include students with a valid birth date
                    return $classmate->dob !== null;
                })
                ->map(function($classmate) {
                    // Get the current date in month-day format
                    $currentDate = now()->format('m-d');
                    $birthdayDate = $classmate->dob->format('m-d');
                    $isToday = ($birthdayDate === $currentDate);
                    
                    return [
                        'id' => $classmate->id,
                        'student_id' => $classmate->student_id,
                        'name' => $classmate->first_name . ' ' . $classmate->last_name,
                        'class' => $classmate->class->name ?? null,
                        'section' => $classmate->section->name ?? null,
                        'dob' => $classmate->dob ? $classmate->dob->format('Y-m-d') : null,
                        'birthday_month' => $classmate->dob ? $classmate->dob->format('F') : null,
                        'birthday_day' => $classmate->dob ? $classmate->dob->format('d') : null,
                        'is_today' => $isToday,
                        'profile_image' => $classmate->profile_image ? asset('storage/' . $classmate->profile_image) : null
                    ];
                })
                ->sortBy(function($student) {
                    // Sort by month and then by day
                    return $student['dob'];
                })
                ->values();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'today' => now()->format('Y-m-d'),
                    'birthdays_count' => count($allStudents),
                    'birthdays' => $allStudents
                ]
            ]);
            
        } catch (Exception $e) {
            // Log the exception
            \Log::error('All birthdays error: ' . $e->getMessage());
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving all birthdays',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get the authenticated student's own birthday details
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myBirthday(Request $request)
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
            
            if (!$student->dob) {
                return response()->json([
                    'success' => false,
                    'message' => 'Birth date not available for this student',
                    'error_code' => 'DOB_NOT_AVAILABLE'
                ], 404);
            }
            
            // Calculate days until next birthday
            $currentDate = now()->format('m-d');
            $birthdayDate = $student->dob->format('m-d');
            $isToday = ($birthdayDate === $currentDate);
            $daysUntil = 0;
            
            if (!$isToday) {
                if ($birthdayDate < $currentDate) {
                    // Next year's birthday
                    $nextBirthday = \Carbon\Carbon::createFromFormat('m-d', $birthdayDate)->addYear();
                    $daysUntil = now()->diffInDays($nextBirthday, false);
                } else {
                    // This year's birthday
                    $nextBirthday = \Carbon\Carbon::createFromFormat('m-d', $birthdayDate);
                    $daysUntil = now()->diffInDays($nextBirthday, false);
                }
            }
            
            // Calculate age
            $age = $student->dob->age;
            $nextAge = $age + 1;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'student_id' => $student->student_id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'dob' => $student->dob->format('Y-m-d'),
                    'birthday_month' => $student->dob->format('F'),
                    'birthday_day' => $student->dob->format('d'),
                    'age' => $age,
                    'next_age' => $nextAge,
                    'is_today' => $isToday,
                    'days_until_next_birthday' => $daysUntil,
                    'profile_image' => $student->profile_image ? asset('storage/' . $student->profile_image) : null
                ]
            ]);
            
        } catch (Exception $e) {
            // Log the exception
            \Log::error('My birthday error: ' . $e->getMessage());
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving birthday information',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
    
    /**
     * Get birthdays for the student's class including teachers
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function classBirthdays(Request $request)
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
            
            if (!$student->class_id || !$student->section_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not assigned to any class or section',
                    'error_code' => 'NO_CLASS_ASSIGNED'
                ], 404);
            }
            
            // Get classmates with birthdays
            $classmates = Student::where('school_id', $student->school_id)
                ->where('class_id', $student->class_id)
                ->where('section_id', $student->section_id)
                ->where('status', 'active')
                ->get()
                ->filter(function($classmate) {
                    return $classmate->dob !== null;
                })
                ->map(function($classmate) {
                    $currentDate = now()->format('m-d');
                    $birthdayDate = $classmate->dob->format('m-d');
                    $isToday = ($birthdayDate === $currentDate);
                    
                    return [
                        'id' => $classmate->id,
                        'student_id' => $classmate->student_id,
                        'name' => $classmate->first_name . ' ' . $classmate->last_name,
                        'type' => 'student',
                        'dob' => $classmate->dob->format('Y-m-d'),
                        'birthday_month' => $classmate->dob->format('F'),
                        'birthday_day' => $classmate->dob->format('d'),
                        'is_today' => $isToday,
                        'profile_image' => $classmate->profile_image ? asset('storage/' . $classmate->profile_image) : null
                    ];
                });
            
            // Get the timetable for this class
            $timetable = TimeTable::where('school_id', $student->school_id)
                ->where('class_name', $student->class->name ?? '')
                ->where('section_id', $student->section_id)
                ->first();
            
            $teachers = [];
            
            if ($timetable) {
                // Get teachers who teach this class
                $teacherIds = TimeTablePeriod::where('timetable_id', $timetable->id)
                    ->where('period_type', 'regular')
                    ->whereNotNull('teacher')
                    ->pluck('teacher')
                    ->unique()
                    ->values();
                
                // Get teacher details with birthdays
                $teachers = Teacher::whereIn('id', $teacherIds)
                    ->get()
                    ->filter(function($teacher) {
                        return $teacher->date_of_birth !== null;
                    })
                    ->map(function($teacher) {
                        $currentDate = now()->format('m-d');
                        $birthdayDate = $teacher->date_of_birth->format('m-d');
                        $isToday = ($birthdayDate === $currentDate);
                        
                        return [
                            'id' => $teacher->id,
                            'employee_id' => $teacher->employee_id,
                            'name' => $teacher->first_name . ' ' . $teacher->last_name,
                            'type' => 'teacher',
                            'dob' => $teacher->date_of_birth->format('Y-m-d'),
                            'birthday_month' => $teacher->date_of_birth->format('F'),
                            'birthday_day' => $teacher->date_of_birth->format('d'),
                            'is_today' => $isToday,
                            'profile_image' => $teacher->profile_image ? asset('storage/' . $teacher->profile_image) : null
                        ];
                    });
            }
            
            // Combine classmates and teachers
            $allBirthdays = $classmates->concat($teachers)
                ->sortBy(function($person) {
                    // Sort by month and then by day
                    return $person['dob'];
                })
                ->values();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'class' => $student->class->name ?? null,
                    'section' => $student->section->name ?? null,
                    'today' => now()->format('Y-m-d'),
                    'students_count' => count($classmates),
                    'teachers_count' => count($teachers),
                    'birthdays_count' => count($allBirthdays),
                    'birthdays' => $allBirthdays
                ]
            ]);
            
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Class birthdays error: ' . $e->getMessage());
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving class birthdays',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
    
    
    
    
public function indexHomeWork(Request $request)
{
    // Get logged-in student
    $student = $request->user(); // via auth:student middleware

    // Optional: filter by subject_id
    $subjectId = $request->query('subject_id');

    // Fetch homework
    $query = Homework::where('school_id', $student->school_id)
                     ->where('class_name', $student->class->name)
                     ->where('section_id', $student->section_id);

    if ($subjectId) {
        $query->where('subject_id', $subjectId);
    }

    $homework = $query->orderBy('homework_date', 'desc')->get();

    // Group by subject
    $grouped = $homework->groupBy(function($hw) {
        return $hw->subject->name ?? 'Unknown Subject';
    });

    // Format response
    $data = $grouped->map(function($hwList) {
        return $hwList->map(function($hw) {
  
            return [
                'id' => $hw->id,
                'description' => $hw->description,
                'homework_date' => $hw->homework_date,
                'submission_date' => $hw->submission_date,
                'attachment' => $hw->image_path ? asset('storage/' . $hw->image_path) : null,
                'teacher' => $hw->teacher ? $hw->teacher->first_name.' '.$hw->teacher->last_name : null
            ];
        })->sortByDesc('homework_date')->values(); 
    });

    return response()->json([
        'success' => true,
        'homework' => $data
    ]);
}



public function submitHomework(Request $request, $id)
{
    // Validate file
    $request->validate([
        'submission_file' => 'required|file|mimes:pdf,doc,docx,jpg,png,webp', // 2MB max
    ]);

    // Get logged-in student
    $student = $request->user();

    // Find homework
    $hw = Homework::findOrFail($id);

    // Optional: check if the student already submitted
    $existing = HomeworkSubmission::where('homework_id', $hw->id)
                                  ->where('student_id', $student->id)
                                  ->first();
    if ($existing) {
        return response()->json([
            'success' => false,
            'message' => 'You have already submitted this homework.'
        ], 400);
    }

    // Store file
    $filePath = $request->file('submission_file')->store('homework_submissions', 'public');

    // Save submission
    $submission = HomeworkSubmission::create([
        'homework_id' => $hw->id,
        'student_id' => $student->id,
        'file_path' => $filePath,
        'submitted_at' => now(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Homework submitted successfully!',
        'submission' => [
            'id' => $submission->id,
            'file_path' => asset('storage/' . $submission->file_path),
            'submitted_at' => $submission->submitted_at
        ]
    ]);
}



 public function indexExam(Request $request)
    {
         try {
        $studentId = $request->user()->id ?? Session::get('student_id');
        if (!$studentId) {
            return response()->json([
                'status'  => false,
                'message' => 'Student not found in session or token.',
            ], 404);
        }
        $student = Student::findOrFail($studentId);

        // Fetch exams for class + section (no pagination for grouping)
        $examSchedules = ExamSchedule::where('class', $student->class->name)
            ->where('section', $student->section->name)
            ->with(['exam'])
            ->orderBy('exam_date', 'asc')
            ->get();

        // Group by exam name (Mid-Term, Final Term, etc.)
        $grouped = $examSchedules->groupBy(function ($schedule) {
            return $schedule->exam->name ?? 'Other Exams';
        });

        // Format data for frontend
        $formatted = $grouped->map(function ($items, $examName) {
            return [
                'exam_name' => $examName,
                'schedules' => $items->map(function ($item) {
                    return [
                        'id'         => $item->id,
                        'exam_date'  => $item->exam_date,
                        'start_time' => $item->start_time,
                        'end_time'   => $item->end_time,
                        'subject'    => $item->subject ?? null,
                    ];
                })->values()
            ];
        })->values();

        return response()->json([
            'status'  => true,
            'student' => [
                'id'      => $student->id,
                'name'    => $student->name,
                'class'   => $student->class->name,
                'section' => $student->section->name,
            ],
            'data' => $formatted,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Something went wrong: ' . $e->getMessage(),
        ], 500);
    }
    }
    
    
    
    
    
} 