<?php

namespace App\Http\Controllers;

use App\Models\Hostel;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HostelController extends Controller
{
    /**
     * Display a listing of hostels.
     */
    public function index(Request $request)
    {
        try {
            // Get current school based on logged-in user
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'School not found'
                    ], 404);
                }
                return redirect()->back()->with('error', 'School not found');
            }
            
            // Get all hostels for this school
            $hostels = Hostel::where('school_id', $schoolId)
                ->orderBy('name')
                ->get();
            
            // Check if this is an AJAX request
            if ($request->ajax()) {
                $viewContent = view('client.schoolPanel.hostel.partials.hostel_table', compact('hostels'))->render();
                return response($viewContent);
            }
                
            return view('client.schoolPanel.hostel.hostelList', compact('hostels'));
        } catch (\Exception $e) {
            Log::error('Error fetching hostels: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch hostels: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to fetch hostels: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created hostel in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'type' => 'required|in:Boys,Girls,Co-ed',
                'address' => 'required|string|max:1000',
                'intake' => 'required|integer|min:1',
                'description' => 'nullable|string|max:1000',
                'status' => 'nullable|boolean',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            // Check if hostel with same name already exists for this school
            $exists = Hostel::where('school_id', $schoolId)
                ->where('name', $request->name)
                ->exists();
                
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'A hostel with this name already exists'
                ], 422);
            }
            
            // Create new hostel
            $statusValue = (bool)($request->status == 1 || $request->status === true || $request->status === 'true' || $request->status === '1');
            
            $hostel = Hostel::create([
                'school_id' => $schoolId,
                'name' => $request->name,
                'type' => $request->type,
                'address' => $request->address,
                'intake' => $request->intake,
                'description' => $request->description,
                'status' => $statusValue,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Hostel created successfully',
                'hostel' => $hostel
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating hostel: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create hostel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified hostel.
     */
    public function show(Hostel $hostel)
    {
        try {
            // Check if hostel belongs to current school
            if ($hostel->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to hostel'
                ], 403);
            }
            
            return response()->json([
                'success' => true,
                'hostel' => $hostel
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing hostel: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch hostel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified hostel in storage.
     */
    public function update(Request $request, Hostel $hostel)
    {
        try {
            // Check if hostel belongs to current school
            if ($hostel->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to hostel'
                ], 403);
            }
            
            // Validate request
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('hostels')->where(function ($query) use ($hostel) {
                        return $query->where('school_id', $this->getSchoolId())
                                    ->where('id', '!=', $hostel->id);
                    }),
                ],
                'type' => 'required|in:Boys,Girls,Co-ed',
                'address' => 'required|string|max:1000',
                'intake' => 'required|integer|min:1',
                'description' => 'nullable|string|max:1000',
                'status' => 'nullable|boolean',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            // Update hostel
            $statusValue = (bool)($request->status == 1 || $request->status === true || $request->status === 'true' || $request->status === '1');
            
            $hostel->update([
                'name' => $request->name,
                'type' => $request->type,
                'address' => $request->address,
                'intake' => $request->intake,
                'description' => $request->description,
                'status' => $statusValue,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Hostel updated successfully',
                'hostel' => $hostel
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating hostel: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update hostel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified hostel from storage.
     */
    public function destroy(Hostel $hostel)
    {
        try {
            // Check if hostel belongs to current school
            if ($hostel->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to hostel'
                ], 403);
            }
            
            // Delete hostel (will cascade to rooms due to foreign key constraint)
            $hostel->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Hostel deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting hostel: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete hostel: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get active hostels for the current school.
     */
    public function getActiveHostels()
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            // Get all active hostels
            $hostels = Hostel::where('school_id', $schoolId)
                ->where('status', true)
                ->orderBy('name')
                ->get();
                
            return response()->json([
                'success' => true,
                'hostels' => $hostels
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching active hostels: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch active hostels: ' . $e->getMessage()
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