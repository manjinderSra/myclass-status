<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\School;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        // Get current school based on logged-in user
        $schoolId = null;
        $user = Auth::user();
        
        if ($user->role === 'school') {
            $school = \App\Models\School::where('admin_id', $user->id)->first();
            if ($school) {
                $schoolId = $school->id;
            }
        } else if ($user->school_id) {
            $schoolId = $user->school_id;
        }
        
        if (!$schoolId) {
            return redirect()->back()->with('error', 'School not found');
        }
        
        // Get all users belonging to this school (except the school owner)
        $users = User::where('school_id', $schoolId)
            ->where('id', '!=', $user->id)
            ->with('roles.permissions')
            ->get();
        
        // Get available roles for this school
        $roles = Role::where('school_id', $schoolId)->get();
        
        return view('client.schoolPanel.role.users', compact('users', 'roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'role' => 'required|string|in:staff,teacher,administration,finance,library',
                'phone' => 'nullable|string|max:20',
                'gender' => 'nullable|string|in:male,female,other',
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id',
            ]);
            
            // Get current school based on logged-in user
            $schoolId = null;
            $currentUser = Auth::user();
            
            if ($currentUser->role === 'school') {
                $school = \App\Models\School::where('admin_id', $currentUser->id)->first();
                if ($school) {
                    $schoolId = $school->id;
                }
            }
            
            if (!$schoolId) {
                return response()->json(['error' => 'School not found'], 400);
            }
            
            
     
            // Generate a random password
            $password = $this->generateRandomPassword();
            
            // Create the user
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role' => $validated['role'],
                'school_id' => $schoolId,
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'is_active' => true,
                'username' => User::generateUsername($validated['first_name'], $validated['last_name']),
            ]);
            
            // Assign roles
            $roles = Role::whereIn('id', $validated['roles'])->get();
            
            // Ensure the roles belong to the current school
            $validRoles = $roles->where('school_id', $schoolId);
            
            if ($validRoles->count() > 0) {
                // Log role assignment
                Log::info('Assigning roles to new user:', [
                    'user_id' => $user->id,
                    'roles' => $validRoles->pluck('id')->toArray()
                ]);
                
                // Initialize the roles relationship before using it
                if (!$user->roles) {
                    $user->roles = collect();
                }
                
                // Assign roles safely with error handling
                try {
                    $roleIds = $validRoles->pluck('id')->toArray();
                    $user->roles()->sync($roleIds);
                } catch (\Exception $e) {
                    Log::error('Error assigning roles to user: ' . $e->getMessage(), [
                        'user_id' => $user->id,
                        'roles' => $validRoles->pluck('id')->toArray(),
                        'exception' => $e
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'user' => $user,
                'password' => $password, // Return password so it can be shared with the user
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the specified user.
     */
    public function show($id)
    {
        // Get current user and school
        $user = Auth::user();
        $school = School::where('admin_id', $user->id)->first();
        
        if (!$school) {
            return response()->json([
                'success' => false,
                'message' => 'School not found'
            ], 404);
        }
        
        // Get the user
        $targetUser = User::where('id', $id)
            ->where('school_id', $school->id)
            ->with('roles')
            ->first();
        
        if (!$targetUser) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $targetUser->id,
                'name' => $targetUser->name,
                'email' => $targetUser->email,
                'roles' => $targetUser->roles->map(function($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name
                    ];
                }),
                'created_at' => $targetUser->created_at->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'role' => 'required|string|in:staff,teacher,administration,finance,library',
                'phone' => 'nullable|string|max:20',
                'gender' => 'nullable|string|in:male,female,other',
                'roles' => 'required|array',
                'roles.*' => 'exists:roles,id',
            ]);
            
            // Get current school based on logged-in user
            $schoolId = null;
            $currentUser = Auth::user();
            
            if ($currentUser->role === 'school') {
                $school = \App\Models\School::where('admin_id', $currentUser->id)->first();
                if ($school) {
                    $schoolId = $school->id;
                }
            } else if ($currentUser->school_id) {
                $schoolId = $currentUser->school_id;
            }
            
            if (!$schoolId) {
                return response()->json(['error' => 'School not found'], 400);
            }
            
            // Find the user
            $user = User::where('id', $id)
                ->where('school_id', $schoolId)
                ->first();
                
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            // Update user
            $user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'] ?? null,
            ]);
            
            // Update roles
            $roles = Role::whereIn('id', $validated['roles'])->where('school_id', $schoolId)->get();
            
            // Log role update
            Log::info('Updating roles for user:', [
                'user_id' => $user->id,
                'roles' => $roles->pluck('id')->toArray()
            ]);
            
            try {
                $user->roles()->sync($roles->pluck('id')->toArray());
            } catch (\Exception $e) {
                Log::error('Error updating roles for user: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'roles' => $roles->pluck('id')->toArray(),
                    'exception' => $e
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => $user->load('roles'),
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage(), [
                'user_id' => $id,
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        try {
            // Get current school based on logged-in user
            $schoolId = null;
            $currentUser = Auth::user();
            
            if ($currentUser->role === 'school') {
                $school = \App\Models\School::where('admin_id', $currentUser->id)->first();
                if ($school) {
                    $schoolId = $school->id;
                }
            } else if ($currentUser->school_id) {
                $schoolId = $currentUser->school_id;
            }
            
            if (!$schoolId) {
                return response()->json(['error' => 'School not found'], 400);
            }
            
            // Find and delete user
            $user = User::where('id', $id)
                ->where('school_id', $schoolId)
                ->first();
                
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            // Delete the user
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage(), [
                'user_id' => $id,
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a new password for a user.
     */
    public function resetPassword($id)
    {
        try {
            // Get current school based on logged-in user
            $schoolId = null;
            $currentUser = Auth::user();
            
            if ($currentUser->role === 'school') {
                $school = \App\Models\School::where('admin_id', $currentUser->id)->first();
                if ($school) {
                    $schoolId = $school->id;
                }
            } else if ($currentUser->school_id) {
                $schoolId = $currentUser->school_id;
            }
            
            if (!$schoolId) {
                return response()->json(['error' => 'School not found'], 400);
            }
            
            // Find user
            $user = User::where('id', $id)
                ->where('school_id', $schoolId)
                ->first();
                
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            // Generate a new password
            $password = $this->generateRandomPassword();
            
            // Update the user's password
            $user->update([
                'password' => Hash::make($password)
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully',
                'password' => $password
            ]);
        } catch (\Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage(), [
                'user_id' => $id,
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle user active status
     */
    public function toggleActive($id)
    {
        try {
            // Get current school based on logged-in user
            $schoolId = null;
            $currentUser = Auth::user();
            
            if ($currentUser->role === 'school') {
                $school = \App\Models\School::where('admin_id', $currentUser->id)->first();
                if ($school) {
                    $schoolId = $school->id;
                }
            } else if ($currentUser->school_id) {
                $schoolId = $currentUser->school_id;
            }
            
            if (!$schoolId) {
                return response()->json(['error' => 'School not found'], 400);
            }
            
            // Find user
            $user = User::where('id', $id)
                ->where('school_id', $schoolId)
                ->first();
                
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            
            // Toggle is_active status
            $user->update([
                'is_active' => !$user->is_active
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully',
                'is_active' => $user->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling user status: ' . $e->getMessage(), [
                'user_id' => $id,
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a random password
     */
    private function generateRandomPassword($length = 8)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }
        
        return $password;
    }
}
