<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Permission;
use App\Models\Role;
use App\Models\School;
use App\Models\SchoolSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index()
    {
        // Get current user and school
        $user = Auth::user();
        $school = School::where('admin_id', $user->id)->first();
        
        if (!$school) {
            return redirect()->route('school.dashboard')
                ->with('error', 'School not found.');
        }
        
        // Get all roles for this school
        $roles = Role::where('school_id', $school->id)
            ->withCount('permissions')
            ->withCount('users')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('client.schoolPanel.roleManagement.rolesAndPermissions', [
            'roles' => $roles,
            'school' => $school
        ]);
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        // Get current user and school
        $user = Auth::user();
        $school = School::where('admin_id', $user->id)->first();
        
        if (!$school) {
            return redirect()->route('school.dashboard')
                ->with('error', 'School not found.');
        }
        
        // Get available features based on the school's subscription
        $availableFeatures = $this->getSchoolSubscriptionFeatures($school->id);
        
        // Group features by feature group
        $featureGroups = $availableFeatures->groupBy('feature_group');
        
        return view('client.schoolPanel.roleManagement.createRole', [
            'school' => $school,
            'featureGroups' => $featureGroups
        ]);
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        // Get current user and school
        $user = Auth::user();
        $school = School::where('admin_id', $user->id)->first();
        
        if (!$school) {
            return redirect()->route('school.dashboard')
                ->with('error', 'School not found.');
        }
        
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,NULL,id,school_id,' . $school->id,
            'description' => 'nullable|string',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            // Create new role
            $role = Role::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'school_id' => $school->id,
                'is_system_role' => false
            ]);
            
            // Assign permissions to the role
            $role->permissions()->attach($request->permissions);
            
            DB::commit();
            
            return redirect()->route('school.rolesAndPermissions')
                ->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to create role: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified role and its permissions.
     */
    public function show(Role $role)
    {
        // Get current user and school
        $user = Auth::user();
        $school = School::where('admin_id', $user->id)->first();
        
        if (!$school || $role->school_id !== $school->id) {
            return redirect()->route('school.rolesAndPermissions')
                ->with('error', 'You do not have permission to view this role.');
        }
        
        // Load role with permissions and features
        $role->load(['permissions.feature', 'users']);
        
        // Group permissions by feature group
        $groupedPermissions = $role->permissions->groupBy(function($permission) {
            return $permission->feature->feature_group;
        });
        
        return view('client.schoolPanel.roleManagement.showRole', [
            'role' => $role,
            'groupedPermissions' => $groupedPermissions,
            'school' => $school
        ]);
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {
        // Get current user and school
        $user = Auth::user();
        $school = School::where('admin_id', $user->id)->first();
        
        if (!$school || $role->school_id !== $school->id) {
            return redirect()->route('school.rolesAndPermissions')
                ->with('error', 'You do not have permission to edit this role.');
        }
        
        // If this is a system role, prevent editing
        if ($role->is_system_role) {
            return redirect()->route('school.rolesAndPermissions')
                ->with('error', 'System roles cannot be edited.');
        }
        
        // Get available features based on the school's subscription
        $availableFeatures = $this->getSchoolSubscriptionFeatures($school->id);
        
        // Group features by feature group
        $featureGroups = $availableFeatures->groupBy('feature_group');
        
        // Get all permissions for available features
        $availablePermissions = Permission::whereIn('feature_id', $availableFeatures->pluck('id'))
            ->get();
        
        // Get the IDs of permissions currently assigned to the role
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();
        
        return view('client.schoolPanel.roleManagement.editRole', [
            'role' => $role,
            'school' => $school,
            'featureGroups' => $featureGroups,
            'availablePermissions' => $availablePermissions,
            'rolePermissionIds' => $rolePermissionIds
        ]);
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Get current user and school
        $user = Auth::user();
        $school = School::where('admin_id', $user->id)->first();
        
        if (!$school || $role->school_id !== $school->id) {
            return redirect()->route('school.rolesAndPermissions')
                ->with('error', 'You do not have permission to update this role.');
        }
        
        // If this is a system role, prevent updates
        if ($role->is_system_role) {
            return redirect()->route('school.rolesAndPermissions')
                ->with('error', 'System roles cannot be updated.');
        }
        
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id . ',id,school_id,' . $school->id,
            'description' => 'nullable|string',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            // Update role
            $role->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description
            ]);
            
            // Sync permissions
            $role->permissions()->sync($request->permissions);
            
            DB::commit();
            
            return redirect()->route('school.rolesAndPermissions')
                ->with('success', 'Role updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Failed to update role: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        // Get current user and school
        $user = Auth::user();
        $school = School::where('admin_id', $user->id)->first();
        
        if (!$school || $role->school_id !== $school->id) {
            return redirect()->route('school.rolesAndPermissions')
                ->with('error', 'You do not have permission to delete this role.');
        }
        
        // If this is a system role, prevent deletion
        if ($role->is_system_role) {
            return redirect()->route('school.rolesAndPermissions')
                ->with('error', 'System roles cannot be deleted.');
        }
        
        // Check if any users are assigned to this role
        $usersCount = $role->users()->count();
        if ($usersCount > 0) {
            return redirect()->route('school.rolesAndPermissions')
                ->with('error', "This role is assigned to {$usersCount} users. Please reassign those users before deleting this role.");
        }
        
        // Delete the role
        $role->delete();
        
        return redirect()->route('school.rolesAndPermissions')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Get the permissions available for a specific role
     */
    public function getAvailablePermissions(Request $request)
    {
        // Get current user and school
        $user = Auth::user();
        $school = School::where('admin_id', $user->id)->first();
        
        if (!$school) {
            return response()->json(['error' => 'School not found.'], 404);
        }
        
        // Get available features based on the school's subscription
        $availableFeatures = $this->getSchoolSubscriptionFeatures($school->id);
        
        // Get all permissions for available features
        $permissions = Permission::whereIn('feature_id', $availableFeatures->pluck('id'))
            ->with('feature')
            ->get()
            ->map(function($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'description' => $permission->description,
                    'feature' => [
                        'id' => $permission->feature->id,
                        'name' => $permission->feature->name,
                        'group' => $permission->feature->feature_group
                    ]
                ];
            });
        
        return response()->json(['permissions' => $permissions]);
    }

    /**
     * Get features available for a school based on its active subscription
     */
    private function getSchoolSubscriptionFeatures($schoolId)
    {
        // Get the school's active subscription
        $activeSubscription = SchoolSubscription::where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->with(['plan.features'])
            ->latest()
            ->first();
        
        if (!$activeSubscription) {
            return collect([]);
        }
        
        // Return features from the active subscription
        return $activeSubscription->plan->features;
    }
}
