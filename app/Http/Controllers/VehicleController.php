<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Vehicle;
use App\Models\VehicleDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    /**
     * Display a listing of the vehicles.
     */
    public function index()
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return redirect()->back()->with('error', 'School not found');
            }
            
            $vehicles = Vehicle::where('school_id', $schoolId)
                ->orderBy('vehicle_no')
                ->get();
                
            $drivers = VehicleDriver::where('school_id', $schoolId)
                ->where('status', true)
                ->orderBy('driver_name')
                ->get();
                
            return view('client.schoolPanel.transport.vehicles', compact('vehicles', 'drivers'));
        } catch (\Exception $e) {
            Log::error('Error fetching vehicles: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to fetch vehicles: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'vehicle_no' => 'required|string|max:50',
                'vehicle_model' => 'required|string|max:100',
                'made_year' => 'required|string|max:20',
                'registration_no' => 'required|string|max:50|unique:vehicles,registration_no',
                'chassis_no' => 'required|string|max:50|unique:vehicles,chassis_no',
                'seat_capacity' => 'required|integer|min:1|max:100',
                'gps_tracking_id' => 'nullable|string|max:50',
                'driver_id' => 'nullable|exists:vehicle_drivers,id',
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
            
            // Create new vehicle
            $vehicle = Vehicle::create([
                'school_id' => $schoolId,
                'vehicle_no' => $request->vehicle_no,
                'vehicle_model' => $request->vehicle_model,
                'made_year' => $request->made_year,
                'registration_no' => $request->registration_no,
                'chassis_no' => $request->chassis_no,
                'seat_capacity' => $request->seat_capacity,
                'gps_tracking_id' => $request->gps_tracking_id,
                'driver_id' => $request->driver_id,
                'status' => $request->has('status') ? 1 : 0,
            ]);
            
            Log::info('Vehicle created successfully', ['vehicle_id' => $vehicle->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Vehicle created successfully',
                'vehicle' => $vehicle
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating vehicle: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified vehicle.
     */
    public function show(Vehicle $vehicle)
    {
        try {
            // Check if vehicle belongs to current school
            if ($vehicle->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to vehicle'
                ], 403);
            }
            
            // Load the driver relationship
            $vehicle->load('driver');
            
            return response()->json([
                'success' => true,
                'vehicle' => $vehicle
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing vehicle: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified vehicle in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        try {
            // Check if vehicle belongs to current school
            if ($vehicle->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to vehicle'
                ], 403);
            }
            
            // Validate request
            $validator = Validator::make($request->all(), [
                'vehicle_no' => 'required|string|max:50',
                'vehicle_model' => 'required|string|max:100',
                'made_year' => 'required|string|max:20',
                'registration_no' => 'required|string|max:50|unique:vehicles,registration_no,' . $vehicle->id,
                'chassis_no' => 'required|string|max:50|unique:vehicles,chassis_no,' . $vehicle->id,
                'seat_capacity' => 'required|integer|min:1|max:100',
                'gps_tracking_id' => 'nullable|string|max:50',
                'driver_id' => 'nullable|exists:vehicle_drivers,id',
                'status' => 'nullable|boolean',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }
            
            // Update vehicle
            $vehicle->vehicle_no = $request->vehicle_no;
            $vehicle->vehicle_model = $request->vehicle_model;
            $vehicle->made_year = $request->made_year;
            $vehicle->registration_no = $request->registration_no;
            $vehicle->chassis_no = $request->chassis_no;
            $vehicle->seat_capacity = $request->seat_capacity;
            $vehicle->gps_tracking_id = $request->gps_tracking_id;
            $vehicle->driver_id = $request->driver_id;
            $vehicle->status = $request->has('status') ? 1 : 0;
            $vehicle->save();
            
            Log::info('Vehicle updated successfully', ['vehicle_id' => $vehicle->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Vehicle updated successfully',
                'vehicle' => $vehicle
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating vehicle: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified vehicle from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        try {
            // Check if vehicle belongs to current school
            if ($vehicle->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to vehicle'
                ], 403);
            }
            
            // Delete vehicle
            $vehicle->delete();
            
            Log::info('Vehicle deleted successfully', ['vehicle_id' => $vehicle->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Vehicle deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting vehicle: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete vehicle: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all vehicles for the current school (API endpoint).
     */
    public function getAllVehicles()
    {
        try {
            Log::info('getAllVehicles API called');
            
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                Log::error('School not found in getAllVehicles');
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            $vehicles = Vehicle::with('driver')
                ->where('school_id', $schoolId)
                ->orderBy('vehicle_no')
                ->get();
                
            Log::info('Vehicles fetched successfully', ['count' => $vehicles->count()]);
            
            return response()->json([
                'success' => true,
                'vehicles' => $vehicles
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching vehicles: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vehicles: ' . $e->getMessage()
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
