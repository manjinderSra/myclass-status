<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\VehicleDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VehicleDriverController extends Controller
{
    /**
     * Display a listing of the drivers.
     */
    public function index()
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return redirect()->back()->with('error', 'School not found');
            }
            
            $drivers = VehicleDriver::where('school_id', $schoolId)
                ->orderBy('driver_name')
                ->get();
                
            return view('client.schoolPanel.transport.vehicleDrivers', compact('drivers'));
        } catch (\Exception $e) {
            Log::error('Error fetching vehicle drivers: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to fetch vehicle drivers: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created driver in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'driver_name' => 'required|string|max:255',
                'contact_number' => 'required|string|max:20',
                'license_number' => 'required|string|max:50|unique:vehicle_drivers,license_number',
                'address' => 'nullable|string|max:500',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            // Handle profile photo upload
            $profilePhotoPath = null;
            if ($request->hasFile('profile_photo')) {
                $profilePhotoPath = $request->file('profile_photo')->store('driver_photos', 'public');
            }
            
            // Create new driver
            $driver = VehicleDriver::create([
                'school_id' => $schoolId,
                'driver_name' => $request->driver_name,
                'contact_number' => $request->contact_number,
                'license_number' => $request->license_number,
                'address' => $request->address,
                'profile_photo' => $profilePhotoPath,
                'status' => $request->has('status') ? 1 : 0,
            ]);
            
            Log::info('Vehicle driver created successfully', ['driver_id' => $driver->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Vehicle driver created successfully',
                'driver' => $driver
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating vehicle driver: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create vehicle driver: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified driver.
     */
    public function show(VehicleDriver $vehicleDriver)
    {
        try {
            // Check if driver belongs to current school
            if ($vehicleDriver->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to vehicle driver'
                ], 403);
            }
            
            return response()->json([
                'success' => true,
                'driver' => $vehicleDriver
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing vehicle driver: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vehicle driver: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified driver in storage.
     */
    public function update(Request $request, VehicleDriver $vehicleDriver)
    {
        try {
            // Check if driver belongs to current school
            if ($vehicleDriver->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to vehicle driver'
                ], 403);
            }
            
            // Validate request
            $validator = Validator::make($request->all(), [
                'driver_name' => 'required|string|max:255',
                'contact_number' => 'required|string|max:20',
                'license_number' => 'required|string|max:50|unique:vehicle_drivers,license_number,' . $vehicleDriver->id,
                'address' => 'nullable|string|max:500',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'status' => 'nullable|boolean',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }
            
            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($vehicleDriver->profile_photo) {
                    Storage::disk('public')->delete($vehicleDriver->profile_photo);
                }
                
                $profilePhotoPath = $request->file('profile_photo')->store('driver_photos', 'public');
                $vehicleDriver->profile_photo = $profilePhotoPath;
            }
            
            // Update driver
            $vehicleDriver->driver_name = $request->driver_name;
            $vehicleDriver->contact_number = $request->contact_number;
            $vehicleDriver->license_number = $request->license_number;
            $vehicleDriver->address = $request->address;
            $vehicleDriver->status = $request->has('status') ? 1 : 0;
            $vehicleDriver->save();
            
            Log::info('Vehicle driver updated successfully', ['driver_id' => $vehicleDriver->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Vehicle driver updated successfully',
                'driver' => $vehicleDriver
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating vehicle driver: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vehicle driver: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified driver from storage.
     */
    public function destroy(VehicleDriver $vehicleDriver)
    {
        try {
            // Check if driver belongs to current school
            if ($vehicleDriver->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to vehicle driver'
                ], 403);
            }
            
            // Delete profile photo if exists
            if ($vehicleDriver->profile_photo) {
                Storage::disk('public')->delete($vehicleDriver->profile_photo);
            }
            
            // Delete driver
            $vehicleDriver->delete();
            
            Log::info('Vehicle driver deleted successfully', ['driver_id' => $vehicleDriver->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Vehicle driver deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting vehicle driver: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete vehicle driver: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all vehicle drivers for the current school (API endpoint).
     */
    public function getAllDrivers()
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            $drivers = VehicleDriver::with('vehicle')
                ->where('school_id', $schoolId)
                ->orderBy('driver_name')
                ->get();
                
            return response()->json([
                'success' => true,
                'drivers' => $drivers
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching vehicle drivers: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vehicle drivers: ' . $e->getMessage()
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
