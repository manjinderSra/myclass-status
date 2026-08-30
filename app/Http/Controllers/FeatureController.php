<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeatureController extends Controller
{
    /**
     * Display a listing of the features.
     */
    public function index()
    {
        $features = Feature::orderBy('feature_group')->get();
        $featureGroups = Feature::select('feature_group')->distinct()->orderBy('feature_group')->pluck('feature_group');
        
        return view('saasAdmin.features.index', compact('features', 'featureGroups'));
    }

    /**
     * Show the form for creating a new feature.
     */
    public function create()
    {
        $featureGroups = Feature::select('feature_group')->distinct()->pluck('feature_group');
        return view('saasAdmin.features.create', compact('featureGroups'));
    }

    /**
     * Store a newly created feature in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:features',
            'description' => 'nullable|string',
            'feature_group' => 'required|string|max:50',
            'value_type' => 'required|in:boolean,number,text',
            'is_active' => 'boolean',
        ]);

        Feature::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'feature_group' => $request->feature_group,
            'value_type' => $request->value_type,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('saasAdmin.features')->with('success', 'Feature created successfully.');
    }

    /**
     * Show the form for editing the specified feature.
     */
    public function edit(Feature $feature)
    {
        $featureGroups = Feature::select('feature_group')->distinct()->pluck('feature_group');
        return view('saasAdmin.features.edit', compact('feature', 'featureGroups'));
    }

    /**
     * Update the specified feature in storage.
     */
    public function update(Request $request, Feature $feature)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:features,code,' . $feature->id,
            'description' => 'nullable|string',
            'feature_group' => 'required|string|max:50',
            'value_type' => 'required|in:boolean,number,text',
            'is_active' => 'boolean',
        ]);

        $feature->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'feature_group' => $request->feature_group,
            'value_type' => $request->value_type,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('saasAdmin.features')->with('success', 'Feature updated successfully.');
    }

    /**
     * Remove the specified feature from storage.
     */
    public function destroy(Feature $feature)
    {
        // Check if the feature is used in any plans
        $usedInPlans = $feature->plans()->count();
        
        if ($usedInPlans > 0) {
            return back()->with('error', 'Cannot delete feature that is used in plans.');
        }
        
        $feature->delete();
        return redirect()->route('saasAdmin.features')->with('success', 'Feature deleted successfully.');
    }
    
    /**
     * Add default features to the database
     */
    public function addDefaultFeatures()
    {
        try {
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

            $addedCount = 0;
            foreach ($features as $featureData) {
                $exists = Feature::where('code', $featureData['code'])->exists();
                
                if (!$exists) {
                    Feature::create($featureData);
                    $addedCount++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "{$addedCount} default features added successfully.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding default features: ' . $e->getMessage(),
            ], 500);
        }
    }
}
