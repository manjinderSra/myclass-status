<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\TimeTablePeriod;
use App\Models\TimeTable;
use App\Models\SchoolClass;
use Exception;
use Carbon\Carbon;

class TeacherBirthdayController extends Controller
{
    /**
     * Get all teacher birthdays from the same school
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function allBirthdays(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                    'error_code' => 'UNAUTHORIZED'
                ], 401);
            }
            
            // If user has teacher role, proceed
            if ($user->role !== 'teacher') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. User is not a teacher.',
                    'error_code' => 'NOT_A_TEACHER'
                ], 403);
            }
            
            // Find the teacher by email
            $teacher = Teacher::where('email', $user->email)->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher profile not found',
                    'error_code' => 'PROFILE_NOT_FOUND'
                ], 404);
            }
            
            // Get all teachers with birthdays from the same school
            $allTeachers = Teacher::where('school_id', $teacher->school_id)
                ->where('status', 'active')
                ->with('subject')
                ->get()
                ->filter(function($colleague) {
                    // Only include teachers with a valid birth date
                    return $colleague->date_of_birth !== null;
                })
                ->map(function($colleague) {
                    // Get the current date in month-day format
                    $currentDate = now()->format('m-d');
                    $birthdayDate = $colleague->date_of_birth->format('m-d');
                    $isToday = ($birthdayDate === $currentDate);
                    
                    // Get subject information
                    $subjectInfo = null;
                    if ($colleague->subject_id && $colleague->subject) {
                        $subjectInfo = [
                            'id' => $colleague->subject_id,
                            'name' => $colleague->subject->name
                        ];
                    } else if ($colleague->subject) {
                        // Check if subject is a numeric ID
                        if (is_numeric($colleague->subject)) {
                            // Try to find the subject by ID
                            $subjectModel = \App\Models\Subject::find($colleague->subject);
                            if ($subjectModel) {
                                $subjectInfo = [
                                    'id' => $subjectModel->id,
                                    'name' => $subjectModel->name
                                ];
                            } else {
                                // Fallback to just using the subject value
                                $subjectInfo = $colleague->subject;
                            }
                        } else {
                            // If it's not numeric, use as is
                            $subjectInfo = $colleague->subject;
                        }
                    }
                    
                    return [
                        'id' => $colleague->id,
                        'employee_id' => $colleague->employee_id,
                        'name' => $colleague->first_name . ' ' . $colleague->last_name,
                        'subject' => $subjectInfo,
                        'dob' => $colleague->date_of_birth ? $colleague->date_of_birth->format('Y-m-d') : null,
                        'birthday_month' => $colleague->date_of_birth ? $colleague->date_of_birth->format('F') : null,
                        'birthday_day' => $colleague->date_of_birth ? $colleague->date_of_birth->format('d') : null,
                        'is_today' => $isToday,
                        'profile_image' => $colleague->profile_image ? url('storage/' . $colleague->profile_image) : null
                    ];
                })
                ->sortBy(function($teacher) {
                    // Sort by month and then by day
                    $month = Carbon::parse($teacher['dob'])->format('m');
                    $day = Carbon::parse($teacher['dob'])->format('d');
                    return $month . $day;
                })
                ->values();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'today' => now()->format('Y-m-d'),
                    'birthdays_count' => count($allTeachers),
                    'birthdays' => $allTeachers
                ]
            ]);
            
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Teacher all birthdays error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving all birthdays',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get the authenticated teacher's own birthday details
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myBirthday(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                    'error_code' => 'UNAUTHORIZED'
                ], 401);
            }
            
            // If user has teacher role, proceed
            if ($user->role !== 'teacher') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. User is not a teacher.',
                    'error_code' => 'NOT_A_TEACHER'
                ], 403);
            }
            
            // Find the teacher by email
            $teacher = Teacher::where('email', $user->email)->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher profile not found',
                    'error_code' => 'PROFILE_NOT_FOUND'
                ], 404);
            }
            
            if (!$teacher->date_of_birth) {
                return response()->json([
                    'success' => false,
                    'message' => 'Birth date not available for this teacher',
                    'error_code' => 'DOB_NOT_AVAILABLE'
                ], 404);
            }
            
            // Calculate days until next birthday
            $currentDate = now()->format('m-d');
            $birthdayDate = $teacher->date_of_birth->format('m-d');
            $isToday = ($birthdayDate === $currentDate);
            $daysUntil = 0;
            
            if (!$isToday) {
                if ($birthdayDate < $currentDate) {
                    // Next year's birthday
                    $nextBirthday = Carbon::createFromFormat('m-d', $birthdayDate)->addYear();
                    $daysUntil = now()->diffInDays($nextBirthday, false);
                } else {
                    // This year's birthday
                    $nextBirthday = Carbon::createFromFormat('m-d', $birthdayDate);
                    $daysUntil = now()->diffInDays($nextBirthday, false);
                }
            }
            
            // Calculate age
            $age = $teacher->date_of_birth->age;
            $nextAge = $age + 1;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'employee_id' => $teacher->employee_id,
                    'name' => $teacher->first_name . ' ' . $teacher->last_name,
                    'dob' => $teacher->date_of_birth->format('Y-m-d'),
                    'birthday_month' => $teacher->date_of_birth->format('F'),
                    'birthday_day' => $teacher->date_of_birth->format('d'),
                    'age' => $age,
                    'next_age' => $nextAge,
                    'is_today' => $isToday,
                    'days_until_next_birthday' => $daysUntil,
                    'profile_image' => $teacher->profile_image ? url('storage/' . $teacher->profile_image) : null
                ]
            ]);
            
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Teacher my birthday error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving birthday information',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
    
    /**
     * Get birthdays for the teachers who teach the same subjects
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subjectColleagueBirthdays(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                    'error_code' => 'UNAUTHORIZED'
                ], 401);
            }
            
            // If user has teacher role, proceed
            if ($user->role !== 'teacher') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. User is not a teacher.',
                    'error_code' => 'NOT_A_TEACHER'
                ], 403);
            }
            
            // Find the teacher by email
            $teacher = Teacher::where('email', $user->email)->with('subject')->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher profile not found',
                    'error_code' => 'PROFILE_NOT_FOUND'
                ], 404);
            }
            
            // Get the teacher's subject ID
            $subjectId = null;
            
            if ($teacher->subject_id) {
                $subjectId = $teacher->subject_id;
            } else if (is_numeric($teacher->subject)) {
                $subjectId = $teacher->subject;
            }
            
            if (!$subjectId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher not assigned to any subject',
                    'error_code' => 'NO_SUBJECT_ASSIGNED'
                ], 404);
            }
            
            // Get colleagues who teach the same subject
            $colleagues = Teacher::where('school_id', $teacher->school_id)
                ->where('id', '!=', $teacher->id)
                ->where(function($query) use ($subjectId) {
                    $query->where('subject_id', $subjectId)
                          ->orWhere('subject', $subjectId);
                })
                ->where('status', 'active')
                ->get()
                ->filter(function($colleague) {
                    return $colleague->date_of_birth !== null;
                })
                ->map(function($colleague) {
                    $currentDate = now()->format('m-d');
                    $birthdayDate = $colleague->date_of_birth->format('m-d');
                    $isToday = ($birthdayDate === $currentDate);
                    
                    return [
                        'id' => $colleague->id,
                        'employee_id' => $colleague->employee_id,
                        'name' => $colleague->first_name . ' ' . $colleague->last_name,
                        'type' => 'teacher',
                        'dob' => $colleague->date_of_birth->format('Y-m-d'),
                        'birthday_month' => $colleague->date_of_birth->format('F'),
                        'birthday_day' => $colleague->date_of_birth->format('d'),
                        'is_today' => $isToday,
                        'profile_image' => $colleague->profile_image ? url('storage/' . $colleague->profile_image) : null
                    ];
                })
                ->sortBy(function($colleague) {
                    // Sort by month and then by day
                    $month = Carbon::parse($colleague['dob'])->format('m');
                    $day = Carbon::parse($colleague['dob'])->format('d');
                    return $month . $day;
                })
                ->values();
            
            // Get subject name
            $subjectName = null;
            if ($teacher->subject && $teacher->subject->name) {
                $subjectName = $teacher->subject->name;
            } else if (is_numeric($teacher->subject)) {
                $subjectModel = \App\Models\Subject::find($teacher->subject);
                if ($subjectModel) {
                    $subjectName = $subjectModel->name;
                }
            } else {
                $subjectName = $teacher->subject;
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'subject_id' => $subjectId,
                    'subject_name' => $subjectName,
                    'today' => now()->format('Y-m-d'),
                    'birthdays_count' => count($colleagues),
                    'birthdays' => $colleagues
                ]
            ]);
            
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Teacher subject colleague birthdays error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving colleague birthdays',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Get birthdays of students and teachers from the classes the authenticated teacher teaches
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function teachingClassesBirthdays(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                    'error_code' => 'UNAUTHORIZED'
                ], 401);
            }
            
     
            
            // Find the teacher by email
            $teacher = Teacher::where('email', $user->email)->first();
            
            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher profile not found',
                    'error_code' => 'PROFILE_NOT_FOUND'
                ], 404);
            }
            
            // Get all classes this teacher teaches
            $timetables = TimeTable::where('school_id', $teacher->school_id)
                ->get();
                
            $classIds = [];
            
            foreach ($timetables as $timetable) {
                $teacherPeriods = TimeTablePeriod::where('timetable_id', $timetable->id)
                    ->where('teacher', $teacher->id)
                    ->count();
                
                if ($teacherPeriods > 0) {
                    // Get the class ID from the class name
                    $class = \App\Models\SchoolClass::where('name', $timetable->class_name)
                        ->where('school_id', $teacher->school_id)
                        ->first();
                    
                    if ($class) {
                        $classIds[] = $class->id;
                    }
                }
            }
            
            // Remove duplicates
            $classIds = array_unique($classIds);
            
            if (empty($classIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Teacher does not teach any classes',
                    'error_code' => 'NO_CLASSES_ASSIGNED'
                ], 404);
            }
            
            // Get students from these classes with birthdays
            $students = \App\Models\Student::whereIn('class_id', $classIds)
                ->where('school_id', $teacher->school_id)
                ->where('status', 'active')
                ->with(['class', 'section'])
                ->get()
                ->filter(function($student) {
                    // Only include students with a valid birth date
                    return $student->dob !== null;
                })
                ->map(function($student) {
                    // Get the current date in month-day format
                    $currentDate = now()->format('m-d');
                    $birthdayDate = $student->dob->format('m-d');
                    $isToday = ($birthdayDate === $currentDate);
                    
                    return [
                        'id' => $student->id,
                        'student_id' => $student->student_id,
                        'name' => $student->first_name . ' ' . $student->last_name,
                        'type' => 'student',
                        'class' => $student->class->name ?? null,
                        'section' => $student->section->name ?? null,
                        'dob' => $student->dob ? $student->dob->format('Y-m-d') : null,
                        'birthday_month' => $student->dob ? $student->dob->format('F') : null,
                        'birthday_day' => $student->dob ? $student->dob->format('d') : null,
                        'is_today' => $isToday,
                        'profile_image' => $student->profile_image ? url('storage/' . $student->profile_image) : null
                    ];
                });
                
            // Get other teachers who teach these classes
            $teacherIds = [];
            
            foreach ($timetables as $timetable) {
                // Get the class ID from the class name
                $class = \App\Models\SchoolClass::where('name', $timetable->class_name)
                    ->where('school_id', $teacher->school_id)
                    ->first();
                
                if ($class && in_array($class->id, $classIds)) {
                    $classTeachers = TimeTablePeriod::where('timetable_id', $timetable->id)
                        ->where('teacher', '!=', $teacher->id)
                        ->whereNotNull('teacher')
                        ->pluck('teacher')
                        ->toArray();
                    
                    $teacherIds = array_merge($teacherIds, $classTeachers);
                }
            }
            
            // Remove duplicates
            $teacherIds = array_unique($teacherIds);
            
            // Get teacher details with birthdays
            $teachers = Teacher::whereIn('id', $teacherIds)
                ->where('status', 'active')
                ->get()
                ->filter(function($colleague) {
                    return $colleague->date_of_birth !== null;
                })
                ->map(function($colleague) {
                    $currentDate = now()->format('m-d');
                    $birthdayDate = $colleague->date_of_birth->format('m-d');
                    $isToday = ($birthdayDate === $currentDate);
                    
                    // Get subject information
                    $subjectInfo = null;
                    if ($colleague->subject_id && $colleague->subject) {
                        $subjectInfo = [
                            'id' => $colleague->subject_id,
                            'name' => $colleague->subject->name
                        ];
                    } else if ($colleague->subject) {
                        // Check if subject is a numeric ID
                        if (is_numeric($colleague->subject)) {
                            // Try to find the subject by ID
                            $subjectModel = \App\Models\Subject::find($colleague->subject);
                            if ($subjectModel) {
                                $subjectInfo = [
                                    'id' => $subjectModel->id,
                                    'name' => $subjectModel->name
                                ];
                            } else {
                                // Fallback to just using the subject value
                                $subjectInfo = $colleague->subject;
                            }
                        } else {
                            // If it's not numeric, use as is
                            $subjectInfo = $colleague->subject;
                        }
                    }
                    
                    return [
                        'id' => $colleague->id,
                        'employee_id' => $colleague->employee_id,
                        'name' => $colleague->first_name . ' ' . $colleague->last_name,
                        'type' => 'teacher',
                        'subject' => $subjectInfo,
                        'dob' => $colleague->date_of_birth ? $colleague->date_of_birth->format('Y-m-d') : null,
                        'birthday_month' => $colleague->date_of_birth ? $colleague->date_of_birth->format('F') : null,
                        'birthday_day' => $colleague->date_of_birth ? $colleague->date_of_birth->format('d') : null,
                        'is_today' => $isToday,
                        'profile_image' => $colleague->profile_image ? url('storage/' . $colleague->profile_image) : null
                    ];
                });
            
            // Combine students and teachers
            $allBirthdays = $students->concat($teachers)
                ->sortBy(function($person) {
                    // Sort by month and then by day
                    if (isset($person['dob']) && $person['dob']) {
                        $month = date('m', strtotime($person['dob']));
                        $day = date('d', strtotime($person['dob']));
                        return $month . $day;
                    }
                    return '9999'; // Put entries without DOB at the end
                })
                ->values();
            
            // Get class names
            $classNames = \App\Models\SchoolClass::whereIn('id', $classIds)
                ->pluck('name')
                ->toArray();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'classes' => $classNames,
                    'today' => now()->format('Y-m-d'),
                    'students_count' => count($students),
                    'teachers_count' => count($teachers),
                    'birthdays_count' => count($allBirthdays),
                    'birthdays' => $allBirthdays
                ]
            ]);
            
        } catch (Exception $e) {
            // Log the exception
            \Log::error('Teaching classes birthdays error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while retrieving birthdays',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }
} 