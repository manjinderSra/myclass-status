<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\SchoolSubscription;
use App\Models\School;
use App\Models\User;

class SubscriptionHelper
{
    /**
     * Check if a school has access to a specific feature
     *
     * @param string $featureCode
     * @param int|null $schoolId
     * @return bool
     */
    public static function hasFeature(string $featureCode, ?int $schoolId = null): bool
    {
        // If no school ID is provided, use the authenticated user's school
        if (!$schoolId && Auth::check()) {
            $user = Auth::user();
            
            // SaaS admin has access to all features
            if ($user->role === 'saasAdmin') {
                return true;
            }
            
            // For school admin, check their administered school
            if ($user->role === 'school') {
                // Find the school where the user is admin
                $school = School::where('admin_id', $user->id)->first();
                if ($school) {
                    $schoolId = $school->id;
                }
            } 
            // For staff users, check their permissions from assigned roles
            else if ($user->school_id) {
                $schoolId = $user->school_id;
                
                // For sidebar display, we need to check specific permissions
                // Map feature codes to feature groups
                $featureGroups = [
                    'role_management' => ['role', 'permission'],
                    'institute_profile' => ['institute', 'profile'],
                    'rules_regulations' => ['rule', 'regulation'],
                    'account_settings' => ['account', 'setting'],
                    'notice_board' => ['notice', 'announcement'],
                    'academic_sections' => ['section', 'academic'],
                    'academic_classes' => ['class', 'academic'],
                    'academic_subjects' => ['subject', 'academic'],
                    'timetable' => ['timetable', 'schedule'],
                    'homework' => ['homework', 'assignment'],
                    'attendance' => ['attendance'],
                    'examination_management' => ['exam', 'examination'],
                    'library_management' => ['library', 'book'],
                    'hostel_management' => ['hostel', 'dormitory'],
                    'transport_management' => ['transport', 'vehicle'],
                    'finance_management' => ['finance', 'fee', 'payment']
                ];
                
                // Check if user has any role with permissions related to the feature
                if (method_exists($user, 'roles') && method_exists($user, 'hasPermission')) {
                    // Get all roles for this user
                    $userRoles = $user->roles;
                    
                    // Look for the feature group keywords in permissions
                    if (isset($featureGroups[$featureCode])) {
                        $keywords = $featureGroups[$featureCode];
                        
                        // Check each role's permissions against the keywords
                        foreach ($userRoles as $role) {
                            foreach ($role->permissions as $permission) {
                                // Check if any keyword matches the permission name or slug
                                foreach ($keywords as $keyword) {
                                    if (stripos($permission->name, $keyword) !== false || 
                                        stripos($permission->slug, $keyword) !== false) {
                                        return true;
                                    }
                                }
                            }
                        }
                    }
                    
                    // If no permission matched, don't show this feature
                    return false;
                }
            }
        }
        
        if (!$schoolId) {
            return false;
        }
        
        // Get the school's active subscription
        $activeSubscription = SchoolSubscription::where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->with(['plan.features'])
            ->latest()
            ->first();
        
        if (!$activeSubscription) {
            return false;
        }
        
        // Check if the plan includes the feature
        return $activeSubscription->plan->features->where('code', $featureCode)->isNotEmpty();
    }
    
    /**
     * Get the allowed value for a feature
     *
     * @param string $featureCode
     * @param int|null $schoolId
     * @return string|null
     */
    public static function getFeatureValue(string $featureCode, ?int $schoolId = null): ?string
    {
        // If no school ID is provided, use the authenticated user's school
        if (!$schoolId && Auth::check()) {
            $user = Auth::user();
            
            // Find the school where the user is admin
            $school = School::where('admin_id', $user->id)->first();
            if ($school) {
                $schoolId = $school->id;
            }
        }
        
        if (!$schoolId) {
            return null;
        }
        
        // Get the school's active subscription
        $activeSubscription = SchoolSubscription::where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->with(['plan.features'])
            ->latest()
            ->first();
        
        if (!$activeSubscription) {
            return null;
        }
        
        // Get the feature and its allowed value
        $feature = $activeSubscription->plan->features->where('code', $featureCode)->first();
        
        return $feature ? $feature->pivot->allowed_value : null;
    }
    
    /**
     * Check if a school is within the allowed limits of teachers, students, and staff
     *
     * @param string $type - 'teachers', 'students', or 'staff'
     * @param int|null $schoolId
     * @return bool
     */
    public static function isWithinUserLimit(string $type, ?int $schoolId = null): bool
    {
        // If no school ID is provided, use the authenticated user's school
        if (!$schoolId && Auth::check()) {
            $user = Auth::user();
            
            // Find the school where the user is admin
            $school = School::where('admin_id', $user->id)->first();
            if ($school) {
                $schoolId = $school->id;
            }
        }
        
        if (!$schoolId) {
            return false;
        }
        
        // Get the school's active subscription
        $activeSubscription = SchoolSubscription::where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->with('plan')
            ->latest()
            ->first();
        
        if (!$activeSubscription) {
            return false;
        }
        
        $plan = $activeSubscription->plan;
        
        // Get the limit from the plan
        $maxLimit = match ($type) {
            'teachers' => $plan->max_teachers,
            'students' => $plan->max_students,
            'staff' => $plan->max_staff,
            default => 0,
        };
        
        // 0 means unlimited
        if ($maxLimit === 0) {
            return true;
        }
        
        // Count the users of this type at the school
        $school = School::find($schoolId);
        $currentCount = User::where(function($query) use ($school, $type) {
            if ($type === 'students') {
                $query->where('role', 'student');
            } elseif ($type === 'teachers') {
                $query->where('role', 'teacher');
            } else {
                $query->whereIn('role', ['finance', 'library', 'administration']);
            }
            
            // Add school admin ID to the query
            $query->whereIn('id', function($subquery) use ($school) {
                $subquery->select('admin_id')
                    ->from('schools')
                    ->where('id', $school->id);
            });
        })->count();
        
        return $currentCount < $maxLimit;
    }
    
    /**
     * Get the count of users of a specific type at a school
     *
     * @param string $type - 'teachers', 'students', or 'staff'
     * @param int|null $schoolId
     * @return int
     */
    public static function getUserCount(string $type, ?int $schoolId = null): int
    {
        // If no school ID is provided, use the authenticated user's school
        if (!$schoolId && Auth::check()) {
            $user = Auth::user();
            
            // Find the school where the user is admin
            $school = School::where('admin_id', $user->id)->first();
            if ($school) {
                $schoolId = $school->id;
            }
        }
        
        if (!$schoolId) {
            return 0;
        }
        
        // Count the users of this type at the school
        $school = School::find($schoolId);
        return User::where(function($query) use ($school, $type) {
            if ($type === 'students') {
                $query->where('role', 'student');
            } elseif ($type === 'teachers') {
                $query->where('role', 'teacher');
            } else {
                $query->whereIn('role', ['finance', 'library', 'administration']);
            }
            
            // Add school admin ID to the query
            $query->whereIn('id', function($subquery) use ($school) {
                $subquery->select('admin_id')
                    ->from('schools')
                    ->where('id', $school->id);
            });
        })->count();
    }
    
    /**
     * Get the maximum allowed users of a specific type for a school
     *
     * @param string $type - 'teachers', 'students', or 'staff'
     * @param int|null $schoolId
     * @return int - 0 means unlimited
     */
    public static function getMaxUserLimit(string $type, ?int $schoolId = null): int
    {
        // If no school ID is provided, use the authenticated user's school
        if (!$schoolId && Auth::check()) {
            $user = Auth::user();
            
            // Find the school where the user is admin
            $school = School::where('admin_id', $user->id)->first();
            if ($school) {
                $schoolId = $school->id;
            }
        }
        
        if (!$schoolId) {
            return 0;
        }
        
        // Get the school's active subscription
        $activeSubscription = SchoolSubscription::where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->with('plan')
            ->latest()
            ->first();
        
        if (!$activeSubscription) {
            return 0;
        }
        
        // Get the limit from the plan
        return match ($type) {
            'teachers' => $activeSubscription->plan->max_teachers,
            'students' => $activeSubscription->plan->max_students,
            'staff' => $activeSubscription->plan->max_staff,
            default => 0,
        };
    }
} 