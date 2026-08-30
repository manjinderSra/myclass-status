<?php

namespace App\Http\Controllers;

use App\Models\Hostel;
use App\Models\HostelRoom;
use App\Models\HostelRoomType;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HostelRoomController extends Controller
{
    /**
     * Display a listing of the hostel rooms.
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

            // Get hostels for dropdown
            $hostels = Hostel::where('school_id', $schoolId)
                ->where('status', true)
                ->orderBy('name')
                ->get();

            // Get room types for dropdown
            $roomTypes = HostelRoomType::where('school_id', $schoolId)
                ->where('status', true)
                ->orderBy('name')
                ->get();

            // ✅ ALWAYS load hostelRooms (for both normal & AJAX requests)
            $hostelRooms = HostelRoom::with(['hostel', 'roomType'])
                ->where('school_id', $schoolId)
                ->orderBy('hostel_id')
                ->orderBy('room_number')
                ->get();

            // ✅ If it's an AJAX request, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success'      => true,
                    'hostelRooms'  => $hostelRooms,
                ]);
            }



            // ✅ For normal page load, send all 3 to the view
            return view(
                'client.schoolPanel.hostel.hostelRooms',
                compact('hostels', 'roomTypes', 'hostelRooms')
            );
        } catch (\Exception $e) {
            Log::error('Error fetching hostel rooms: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch hostel rooms: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to fetch hostel rooms: ' . $e->getMessage());
        }
    }


    /**
     * Store a newly created hostel room in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Hostel Room creation request received', [
                'request_data' => $request->all()
            ]);

            // Validate request
            $validator = Validator::make($request->all(), [
                'hostel_id' => 'required|exists:hostels,id',
                'room_type_id' => 'required|exists:hostel_room_types,id',
                'room_number' => 'required|string|max:20',
                'beds' => 'required|integer|min:1|max:20',
                'description' => 'nullable|string|max:1000',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                Log::warning('Hostel Room validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }

            $schoolId = $this->getSchoolId();
            Log::info('School ID determined for hostel room creation', ['school_id' => $schoolId]);

            if (!$schoolId) {
                Log::warning('Hostel Room creation failed - School not found');
                return response()->json([
                    'success' => false,
                    'message' => 'School not found. Please ensure you are logged in as a school administrator.'
                ], 404);
            }

            // Check if the hostel belongs to the school
            $hostel = Hostel::where('id', $request->hostel_id)
                ->where('school_id', $schoolId)
                ->first();

            if (!$hostel) {
                Log::warning('Hostel Room creation failed - Invalid hostel', [
                    'hostel_id' => $request->hostel_id,
                    'school_id' => $schoolId
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid hostel selected'
                ], 422);
            }

            // Check if the room type belongs to the school
            $roomType = HostelRoomType::where('id', $request->room_type_id)
                ->where('school_id', $schoolId)
                ->first();

            if (!$roomType) {
                Log::warning('Hostel Room creation failed - Invalid room type', [
                    'room_type_id' => $request->room_type_id,
                    'school_id' => $schoolId
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid room type selected'
                ], 422);
            }

            // Check if room with same number already exists in this hostel
            $exists = HostelRoom::where('school_id', $schoolId)
                ->where('hostel_id', $request->hostel_id)
                ->where('room_number', $request->room_number)
                ->exists();

            if ($exists) {
                Log::warning('Hostel Room creation failed - Duplicate room number', [
                    'hostel_id' => $request->hostel_id,
                    'room_number' => $request->room_number,
                    'school_id' => $schoolId
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'A room with this number already exists in the selected hostel'
                ], 422);
            }

            // Create new hostel room
            $statusValue = (bool)($request->status == 1 || $request->status === true || $request->status === 'true' || $request->status === '1');

            // Log the data being passed to create method
            Log::info('Attempting to create hostel room with data', [
                'school_id' => $schoolId,
                'hostel_id' => $request->hostel_id,
                'room_type_id' => $request->room_type_id,
                'room_number' => $request->room_number,
                'beds' => $request->beds,
                'description' => $request->description,
                'status' => $statusValue,
            ]);

            $hostelRoom = HostelRoom::create([
                'school_id' => $schoolId,
                'hostel_id' => $request->hostel_id,
                'room_type_id' => $request->room_type_id,
                'room_number' => $request->room_number,
                'beds' => $request->beds,
                'description' => $request->description,
                'status' => $statusValue,
            ]);

            // Load relationships for the response
            $hostelRoom->load(['hostel', 'roomType']);

            Log::info('Hostel Room created successfully', [
                'hostel_room_id' => $hostelRoom->id,
                'room_number' => $hostelRoom->room_number
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Hostel room created successfully',
                'hostelRoom' => $hostelRoom
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating hostel room: ' . $e->getMessage(), [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create hostel room: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified hostel room.
     */
    public function show(HostelRoom $hostelRoom)
    {
        try {
            // Check if hostel room belongs to current school
            if ($hostelRoom->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to hostel room'
                ], 403);
            }

            // Load relationships
            $hostelRoom->load(['hostel', 'roomType']);

            return response()->json([
                'success' => true,
                'hostelRoom' => $hostelRoom
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing hostel room: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch hostel room: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified hostel room in storage.
     */
    public function update(Request $request, HostelRoom $hostelRoom)
    {
        try {
            // Check if hostel room belongs to current school
            if ($hostelRoom->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to hostel room'
                ], 403);
            }

            // Validate request
            $validator = Validator::make($request->all(), [
                'hostel_id' => 'required|exists:hostels,id',
                'room_type_id' => 'required|exists:hostel_room_types,id',
                'room_number' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('hostel_rooms')->where(function ($query) use ($request, $hostelRoom) {
                        return $query->where('school_id', $this->getSchoolId())
                            ->where('hostel_id', $request->hostel_id)
                            ->where('id', '!=', $hostelRoom->id);
                    }),
                ],
                'beds' => 'required|integer|min:1|max:20',
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

            // Check if the hostel belongs to the school
            $hostel = Hostel::where('id', $request->hostel_id)
                ->where('school_id', $schoolId)
                ->first();

            if (!$hostel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid hostel selected'
                ], 422);
            }

            // Check if the room type belongs to the school
            $roomType = HostelRoomType::where('id', $request->room_type_id)
                ->where('school_id', $schoolId)
                ->first();

            if (!$roomType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid room type selected'
                ], 422);
            }

            // Update hostel room
            $statusValue = (bool)($request->status == 1 || $request->status === true || $request->status === 'true' || $request->status === '1');

            $hostelRoom->update([
                'hostel_id' => $request->hostel_id,
                'room_type_id' => $request->room_type_id,
                'room_number' => $request->room_number,
                'beds' => $request->beds,
                'description' => $request->description,
                'status' => $statusValue,
            ]);

            // Reload relationships
            $hostelRoom->load(['hostel', 'roomType']);

            return response()->json([
                'success' => true,
                'message' => 'Hostel room updated successfully',
                'hostelRoom' => $hostelRoom
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating hostel room: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update hostel room: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified hostel room from storage.
     */
    public function destroy(HostelRoom $hostelRoom)
    {
        try {
            // Check if hostel room belongs to current school
            if ($hostelRoom->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to hostel room'
                ], 403);
            }

            // Delete hostel room
            $hostelRoom->delete();

            return response()->json([
                'success' => true,
                'message' => 'Hostel room deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting hostel room: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete hostel room: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all hostel rooms for the current school.
     */
    public function getAllHostelRooms()
    {
        try {
            $schoolId = $this->getSchoolId();

            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }

            // Get all hostel rooms with their relationships
            $hostelRooms = HostelRoom::with(['hostel', 'roomType'])
                ->where('school_id', $schoolId)
                ->orderBy('hostel_id')
                ->orderBy('room_number')
                ->get();

            return response()->json([
                'success' => true,
                'hostelRooms' => $hostelRooms
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching hostel rooms: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch hostel rooms: ' . $e->getMessage()
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
