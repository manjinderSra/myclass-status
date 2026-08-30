<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\FeeGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FeeGroupController extends Controller
{
    /**
     * Display a listing of the fee groups.
     */
    public function index()
    {

        // dd("hii");
        try {
            $schoolId = $this->getSchoolId();
            if (!$schoolId) {
                return redirect()->back()->with('error', 'School not found');
            }
            return view('client.schoolPanel.finance.feeGroup');
        } catch (\Exception $e) {
            Log::error('Error showing fee groups page: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Failed to show fee groups page: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created fee group in storage.
     */
    public function store(Request $request)
    {
                try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'fees_group' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'status' => 'nullable|boolean',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }
            
            $schoolId = $this->getSchoolId();
        // dd($schoolId);
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            // Create new fee group
            $feeGroup = FeeGroup::create([
                'school_id' => $schoolId,
                'name' => $request->fees_group,
                'description' => $request->description,
                'status' => $request->status == 1,
            ]);
            Log::info('Fee group created successfully', ['fee_group_id' => $feeGroup->id]);
            return response()->json([
                'success' => true,
                'message' => 'Fee group created successfully',
                'feeGroup' => $feeGroup
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating fee group: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create fee group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified fee group.
     */
    public function show(FeeGroup $feeGroup)
    {
        try {
            // Check if fee group belongs to current school
            if ($feeGroup->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to fee group'
                ], 403);
            }
            return response()->json([
                'success' => true,
                'feeGroup' => $feeGroup
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing fee group: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch fee group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified fee group in storage.
     */
    public function update(Request $request, FeeGroup $feeGroup)
    {
        try {
            // Check if fee group belongs to current school
            if ($feeGroup->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to fee group'
                ], 403);
            }
            
            // Validate request
            $validator = Validator::make($request->all(), [
                'fees_group' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'status' => 'nullable|boolean',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }
            // Update fee group
            $feeGroup->name = $request->fees_group;
            $feeGroup->description = $request->description;
            $feeGroup->status = $request->status == 1;
            $feeGroup->save();
            
            Log::info('Fee group updated successfully', ['fee_group_id' => $feeGroup->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Fee group updated successfully',
                'feeGroup' => $feeGroup
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating fee group: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update fee group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified fee group from storage.
     */
    public function destroy(FeeGroup $feeGroup)
    {
        try {
            // Check if fee group belongs to current school
            if ($feeGroup->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to fee group'
                ], 403);
            }
            
            // Delete fee group
            $feeGroup->delete();
            
            Log::info('Fee group deleted successfully', ['fee_group_id' => $feeGroup->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Fee group deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting fee group: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete fee group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all fee groups for the current school (API endpoint).
     */
    public function getAllFeeGroups()
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            $feeGroups = FeeGroup::where('school_id', $schoolId)
                ->orderBy('name')
                ->get();
                
            // Convert status to explicit boolean for frontend
            $feeGroups->transform(function ($feeGroup) {
                $feeGroup->status = (bool) $feeGroup->status;
                return $feeGroup;
            });
                
            return response()->json([
                'success' => true,
                'feeGroups' => $feeGroups
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching fee groups: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch fee groups: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to get current school ID.
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
} 