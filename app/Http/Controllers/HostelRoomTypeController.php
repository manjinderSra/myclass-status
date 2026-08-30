<?php

namespace App\Http\Controllers;

use App\Models\HostelRoomType;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HostelRoomTypeController extends Controller
{
    /**
     * Display a listing of the room types.
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
            
            // Get all room types for this school
            $roomTypes = HostelRoomType::where('school_id', $schoolId)
                ->orderBy('name')
                ->get();
            
            // If it's an AJAX request, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'roomTypes' => $roomTypes
                ]);
            }
                
            return view('client.schoolPanel.hostel.roomType', compact('roomTypes'));
        } catch (\Exception $e) {
            Log::error('Error fetching room types: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch room types: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to fetch room types: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created room type in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Room Type creation request received', [
                'request_data' => $request->all()
            ]);
            
            // Validate request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'price' => 'required|numeric|min:0',
                'status' => 'nullable|boolean',
            ]);
            
            if ($validator->fails()) {
                Log::warning('Room Type validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                return response()->json([
                    'success' => false, 
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }
            
            $schoolId = $this->getSchoolId();
            Log::info('School ID determined for room type creation', ['school_id' => $schoolId]);
            
            if (!$schoolId) {
                Log::warning('Room Type creation failed - School not found');
                return response()->json([
                    'success' => false,
                    'message' => 'School not found. Please ensure you are logged in as a school administrator.'
                ], 404);
            }
            
            // Check if room type with same name already exists for this school
            $exists = HostelRoomType::where('school_id', $schoolId)
                ->where('name', $request->name)
                ->exists();
                
            if ($exists) {
                Log::warning('Room Type creation failed - Duplicate name', [
                    'name' => $request->name, 
                    'school_id' => $schoolId
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'A room type with this name already exists'
                ], 422);
            }
            
            // Create new room type
            $statusValue = (bool)($request->status == 1 || $request->status === true || $request->status === 'true' || $request->status === '1');
            
            // Log the data being passed to create method
            Log::info('Attempting to create room type with data', [
                'school_id' => $schoolId,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'status' => $statusValue,
            ]);
            
            $roomType = HostelRoomType::create([
                'school_id' => $schoolId,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'status' => $statusValue,
            ]);
            
            Log::info('Room Type created successfully', [
                'room_type_id' => $roomType->id,
                'name' => $roomType->name
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Room type created successfully',
                'roomType' => $roomType
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating room type: ' . $e->getMessage(), [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create room type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified room type.
     */
    public function show(HostelRoomType $roomType)
    {
        try {
            // Check if room type belongs to current school
            if ($roomType->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to room type'
                ], 403);
            }
            
            return response()->json([
                'success' => true,
                'roomType' => $roomType
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing room type: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch room type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified room type in storage.
     */
    public function update(Request $request, HostelRoomType $roomType)
    {
        try {
            // Check if room type belongs to current school
            if ($roomType->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to room type'
                ], 403);
            }
            
            // Validate request
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('hostel_room_types')->where(function ($query) use ($roomType) {
                        return $query->where('school_id', $this->getSchoolId())
                                    ->where('id', '!=', $roomType->id);
                    }),
                ],
                'description' => 'nullable|string|max:1000',
                'price' => 'required|numeric|min:0',
                'status' => 'nullable|boolean',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            // Update room type
            $statusValue = (bool)($request->status == 1 || $request->status === true || $request->status === 'true' || $request->status === '1');
            
            $roomType->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'status' => $statusValue,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Room type updated successfully',
                'roomType' => $roomType
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating room type: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update room type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified room type from storage.
     */
    public function destroy(HostelRoomType $roomType)
    {
        try {
            // Check if room type belongs to current school
            if ($roomType->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to room type'
                ], 403);
            }
            
            // Check if the room type is in use by any hostel rooms
            $roomsCount = $roomType->rooms()->count();
            if ($roomsCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete room type. It is used by {$roomsCount} hostel room(s)."
                ], 422);
            }
            
            // Delete room type
            $roomType->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Room type deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting room type: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete room type: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get active room types for the current school.
     */
    public function getActiveRoomTypes()
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            // Get all active room types
            $roomTypes = HostelRoomType::where('school_id', $schoolId)
                ->where('status', true)
                ->orderBy('name')
                ->get();
                
            return response()->json([
                'success' => true,
                'roomTypes' => $roomTypes
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching active room types: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch active room types: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get all room types for the current school (both active and inactive).
     */
    public function getAllRoomTypes()
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            // Get all room types without filtering by status
            $roomTypes = HostelRoomType::where('school_id', $schoolId)
                ->orderBy('name')
                ->get();
                
            return response()->json([
                'success' => true,
                'roomTypes' => $roomTypes
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching all room types: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch room types: ' . $e->getMessage()
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
        
        Log::info('Getting school ID for user', [
            'user_id' => $user->id ?? 'No user ID',
            'role' => $user->role ?? 'No role',
            'school_id' => $user->school_id ?? 'No school_id',
        ]);
        
        if ($user->role === 'school') {
            $school = School::where('admin_id', $user->id)->first();
            if ($school) {
                $schoolId = $school->id;
                Log::info('Found school for admin', [
                    'school_id' => $schoolId,
                    'admin_id' => $user->id
                ]);
            } else {
                Log::warning('No school found for admin', [
                    'admin_id' => $user->id
                ]);
            }
        } else if ($user->school_id) {
            $schoolId = $user->school_id;
            Log::info('Using school_id from user', [
                'school_id' => $schoolId
            ]);
        } else {
            Log::warning('No school ID available for user', [
                'user_id' => $user->id,
                'role' => $user->role
            ]);
        }
        
        return $schoolId;
    }
} 