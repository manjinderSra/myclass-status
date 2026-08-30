<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $features = [
            // General Settings
            ['name' => 'Institute Profile', 'code' => 'institute_profile', 'description' => 'Manage institute profile settings', 'feature_group' => 'general_settings', 'value_type' => 'boolean', 'is_active' => true],
            ['name' => 'Rules & Regulations', 'code' => 'rules_regulations', 'description' => 'Manage institute rules and regulations', 'feature_group' => 'general_settings', 'value_type' => 'boolean', 'is_active' => true],
            ['name' => 'Account Settings', 'code' => 'account_settings', 'description' => 'Manage account settings', 'feature_group' => 'general_settings', 'value_type' => 'boolean', 'is_active' => true],
            ['name' => 'Notice Board', 'code' => 'notice_board', 'description' => 'Manage notice board announcements', 'feature_group' => 'general_settings', 'value_type' => 'boolean', 'is_active' => true],
            ['name' => 'Role Management', 'code' => 'role_management', 'description' => 'Manage user roles and permissions', 'feature_group' => 'general_settings', 'value_type' => 'boolean', 'is_active' => true],
            
            // Academics
            ['name' => 'Academic Sections', 'code' => 'academic_sections', 'description' => 'Manage academic sections', 'feature_group' => 'academics', 'value_type' => 'boolean', 'is_active' => true],
            ['name' => 'Academic Classes', 'code' => 'academic_classes', 'description' => 'Manage academic classes', 'feature_group' => 'academics', 'value_type' => 'boolean', 'is_active' => true],
            ['name' => 'Academic Subjects', 'code' => 'academic_subjects', 'description' => 'Manage academic subjects', 'feature_group' => 'academics', 'value_type' => 'boolean', 'is_active' => true],
            ['name' => 'Attendance', 'code' => 'attendance', 'description' => 'Manage student attendance', 'feature_group' => 'academics', 'value_type' => 'boolean', 'is_active' => true],
            ['name' => 'Timetable', 'code' => 'timetable', 'description' => 'Manage class timetables', 'feature_group' => 'academics', 'value_type' => 'boolean', 'is_active' => true],
            ['name' => 'Homework', 'code' => 'homework', 'description' => 'Manage student homework', 'feature_group' => 'academics', 'value_type' => 'boolean', 'is_active' => true],
            ['name' => 'Student Management', 'code' => 'student_management', 'description' => 'Manage students and related operations', 'feature_group' => 'academics', 'value_type' => 'boolean', 'is_active' => true],
            
            // Hostel
            ['name' => 'Hostel Management', 'code' => 'hostel_management', 'description' => 'Manage hostel facilities', 'feature_group' => 'hostel', 'value_type' => 'boolean', 'is_active' => true],
            
            // Transport
            ['name' => 'Transport Management', 'code' => 'transport_management', 'description' => 'Manage transport facilities', 'feature_group' => 'transport', 'value_type' => 'boolean', 'is_active' => true],
            
            // Finance
            ['name' => 'Finance Management', 'code' => 'finance_management', 'description' => 'Manage school finances', 'feature_group' => 'finance', 'value_type' => 'boolean', 'is_active' => true],
            
            // Examinations
            ['name' => 'Examination Management', 'code' => 'examination_management', 'description' => 'Manage examinations', 'feature_group' => 'examinations', 'value_type' => 'boolean', 'is_active' => true],
            
            // Library
            ['name' => 'Library Management', 'code' => 'library_management', 'description' => 'Manage library resources', 'feature_group' => 'library', 'value_type' => 'boolean', 'is_active' => true],
            
            // Resource Limits (Number-based features)
            ['name' => 'Maximum Students', 'code' => 'max_students', 'description' => 'Maximum number of students allowed', 'feature_group' => 'limits', 'value_type' => 'number', 'is_active' => true],
            ['name' => 'Maximum Teachers', 'code' => 'max_teachers', 'description' => 'Maximum number of teachers allowed', 'feature_group' => 'limits', 'value_type' => 'number', 'is_active' => true],
            ['name' => 'Maximum Staff', 'code' => 'max_staff', 'description' => 'Maximum number of staff allowed', 'feature_group' => 'limits', 'value_type' => 'number', 'is_active' => true],
            ['name' => 'Storage Space', 'code' => 'storage_space', 'description' => 'Storage space in MB', 'feature_group' => 'limits', 'value_type' => 'number', 'is_active' => true],
            ['name' => 'Maximum File Size', 'code' => 'max_file_size', 'description' => 'Maximum file size in MB', 'feature_group' => 'limits', 'value_type' => 'number', 'is_active' => true],
        ];

        foreach ($features as $feature) {
            // Check if the feature already exists
            $exists = DB::table('features')->where('code', $feature['code'])->exists();
            
            if (!$exists) {
                DB::table('features')->insert($feature);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not removing features on rollback as they might be used by plans
    }
};
