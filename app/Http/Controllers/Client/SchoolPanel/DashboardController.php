<?php

namespace App\Http\Controllers\Client\SchoolPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\User;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with dynamic statistics.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return redirect()->route('school.login')->with('error', 'School not found');
            }
            $school = School::find($schoolId);
            $schoolName = $school ? $school->name : 'School Dashboard';

            // Get student statistics
            try {
                $students = Student::where('school_id', $schoolId)->get();
                $totalStudents = $students->count();
                $activeStudents = $students->where('status', 'active')->count();
                $inactiveStudents = $totalStudents - $activeStudents;
            } catch (\Exception $e) {
                Log::error('Error getting student statistics: ' . $e->getMessage());
                $totalStudents = 0;
                $activeStudents = 0;
                $inactiveStudents = 0;
            }
            
            // Get teacher statistics
            try {
                $teachers = Teacher::where('school_id', $schoolId)->get();
                $totalTeachers = $teachers->count();
                $activeTeachers = $teachers->where('status', 'active')->count();
                $inactiveTeachers = $totalTeachers - $activeTeachers;
            } catch (\Exception $e) {
                Log::error('Error getting teacher statistics: ' . $e->getMessage());
                $totalTeachers = 0;
                $activeTeachers = 0;
                $inactiveTeachers = 0;
            }
            
            // Get staff statistics - including all users created by the school panel
            // that aren't students or teachers (based on role field or roles relationship)
            try {
                $staffMembers = User::where('school_id', $schoolId)
                    ->where(function($query) {
                        $query->where(function($subQuery) {
                            $subQuery->where('role', '!=', 'student')
                                  ->where('role', '!=', 'teacher')
                                  ->where('role', '!=', 'school');
                        })
                        ->orWhereHas('roles', function($subQuery) {
                            $subQuery->where('slug', 'staff');
                        });
                    })
                    ->get();
                
                $totalStaff = $staffMembers->count();
                $activeStaff = $staffMembers->where('is_active', true)->count();
                $inactiveStaff = $totalStaff - $activeStaff;
            } catch (\Exception $e) {
                Log::error('Error getting staff statistics: ' . $e->getMessage());
                $totalStaff = 0;
                $activeStaff = 0;
                $inactiveStaff = 0;
            }
            
            // Get subject statistics
            try {
                $subjects = Subject::where('school_id', $schoolId)->get();
                $totalSubjects = $subjects->count();
                $activeSubjects = $subjects->where('status', 'active')->count();
                $inactiveSubjects = $totalSubjects - $activeSubjects;
            } catch (\Exception $e) {
                Log::error('Error getting subject statistics: ' . $e->getMessage());
                $totalSubjects = 0;
                $activeSubjects = 0;
                $inactiveSubjects = 0;
            }
            
            // Calculate percentage change (dummy values for now)
            // In a real implementation, you'd compare with previous period
            $studentChange = 1.2;
            $teacherChange = 1.2;
            $staffChange = 1.2;
            $subjectChange = 1.2;
            
            return view('client.schoolPanel.dashboard.dashboard', [
                'schoolName' => $schoolName,
                'totalStudents' => $totalStudents,
                'activeStudents' => $activeStudents,
                'inactiveStudents' => $inactiveStudents,
                'studentChange' => $studentChange,
                
                'totalTeachers' => $totalTeachers,
                'activeTeachers' => $activeTeachers,
                'inactiveTeachers' => $inactiveTeachers,
                'teacherChange' => $teacherChange,
                
                'totalStaff' => $totalStaff,
                'activeStaff' => $activeStaff,
                'inactiveStaff' => $inactiveStaff,
                'staffChange' => $staffChange,
                
                'totalSubjects' => $totalSubjects,
                'activeSubjects' => $activeSubjects,
                'inactiveSubjects' => $inactiveSubjects,
                'subjectChange' => $subjectChange,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in dashboard: ' . $e->getMessage());
            
            // Provide default values in case of error
            return view('client.schoolPanel.dashboard.dashboard', [
                'totalStudents' => 0,
                'activeStudents' => 0,
                'inactiveStudents' => 0,
                'studentChange' => 0,
                
                'totalTeachers' => 0,
                'activeTeachers' => 0,
                'inactiveTeachers' => 0,
                'teacherChange' => 0,
                
                'totalStaff' => 0,
                'activeStaff' => 0,
                'inactiveStaff' => 0,
                'staffChange' => 0,
                
                'totalSubjects' => 0,
                'activeSubjects' => 0,
                'inactiveSubjects' => 0,
                'subjectChange' => 0,
            ])->with('error', 'Error loading dashboard data. Please try again later.');
        }
    }
    
    /**
     * Get the current school ID.
     *
     * @return int|null
     */
    private function getSchoolId()
    {
        $user = Auth::user();
        
        if (!$user) {
            return null;
        }
        
        // Check if user is associated with a school
        $school = School::where('admin_id', $user->id)->first();
        
        if (!$school) {
            return null;
        }
        
        return $school->id;
    }
} 