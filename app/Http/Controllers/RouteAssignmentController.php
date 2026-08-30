<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\RouteDetail;
use App\Models\VehicleDriver;
use App\Models\Vehicle;
use App\Models\RouteAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RouteAssignmentController extends Controller
{
    /**
     * Display the vehicle assignment page
     */
    public function index()
    {
        return view('client.schoolPanel.transport.assignVehicle');
    }
    
    /**
     * Assign a vehicle and driver to a route
     */
    public function assignVehicle(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'route_id' => 'required|exists:route_details,id',
                'driver_id' => 'required|exists:vehicle_drivers,id',
                'vehicle_id' => 'required|exists:vehicles,id',
                'is_update' => 'nullable|boolean',
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
            
            // Check if route belongs to current school
            $route = RouteDetail::where('id', $request->route_id)
                ->where('school_id', $schoolId)
                ->first();
                
            if (!$route) {
                return response()->json([
                    'success' => false,
                    'message' => 'Route not found or does not belong to your school'
                ], 404);
            }
            
            // Check if driver belongs to current school
            $driver = VehicleDriver::where('id', $request->driver_id)
                ->where('school_id', $schoolId)
                ->first();
                
            if (!$driver) {
                return response()->json([
                    'success' => false,
                    'message' => 'Driver not found or does not belong to your school'
                ], 404);
            }
            
            // Check if vehicle belongs to current school
            $vehicle = Vehicle::where('id', $request->vehicle_id)
                ->where('school_id', $schoolId)
                ->first();
                
            if (!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found or does not belong to your school'
                ], 404);
            }
            
            // Verify vehicle is assigned to this driver
            if ($vehicle->driver_id != $driver->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected vehicle is not assigned to this driver'
                ], 422);
            }
            
            // Use transaction to ensure data consistency
            DB::beginTransaction();
            
            try {
                // Check if this route already has an assignment
                $existingAssignment = RouteAssignment::where('school_id', $schoolId)
                    ->where('route_detail_id', $request->route_id)
                    ->first();
                
                $isUpdating = $request->has('is_update') && $request->is_update;
                $actionMessage = 'assigned to';
                    
                if ($existingAssignment) {
                    // Update existing assignment
                    $existingAssignment->vehicle_id = $request->vehicle_id;
                    $existingAssignment->driver_id = $request->driver_id;
                    $existingAssignment->status = true;
                    $existingAssignment->save();
                    
                    $assignment = $existingAssignment;
                    $actionMessage = $isUpdating ? 'updated for' : 'assigned to';
                } else {
                    // Create new assignment
                    $assignment = RouteAssignment::create([
                        'school_id' => $schoolId,
                        'route_detail_id' => $request->route_id,
                        'vehicle_id' => $request->vehicle_id,
                        'driver_id' => $request->driver_id,
                        'status' => true,
                    ]);
                }
                
                DB::commit();
                
                // Load relationships for the response
                $assignment->load(['route', 'vehicle', 'driver']);
                
                Log::info('Route assignment saved successfully', [
                    'route_id' => $request->route_id,
                    'vehicle_id' => $request->vehicle_id,
                    'driver_id' => $request->driver_id
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => "Vehicle and driver successfully {$actionMessage} route",
                    'assignment' => $assignment
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error assigning vehicle to route: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign vehicle to route: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get all route assignments for the current school
     */
    public function getAssignedRoutes()
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            // Get all routes for this school
            $routes = RouteDetail::with(['pickupPoints'])
                ->where('school_id', $schoolId)
                ->get();
                
            // Get all assignments for this school
            $assignments = RouteAssignment::with(['vehicle', 'driver'])
                ->where('school_id', $schoolId)
                ->get()
                ->keyBy('route_detail_id');
                
            // Combine routes with their assignments
            $routes->each(function($route) use ($assignments) {
                if (isset($assignments[$route->id])) {
                    $assignment = $assignments[$route->id];
                    $route->vehicle = $assignment->vehicle;
                    $route->driver = $assignment->driver;
                } else {
                    $route->vehicle = null;
                    $route->driver = null;
                }
            });
            
            return response()->json([
                'success' => true,
                'routes' => $routes
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching assigned routes: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch assigned routes: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get the current school ID based on authenticated user
     * Works with both web session and API token authentication
     * 
     * @return int|null The school ID or null if not found
     */
    private function getSchoolId()
    {
        $user = auth()->user();
        
        if (!$user) {
            return null;
        }
        
        // If the user has a direct school_id property
        if ($user->school_id) {
            return $user->school_id;
        }
        
        // If user is a school admin
        if ($user->role === 'school') {
            $school = \App\Models\School::where('admin_id', $user->id)->first();
            if ($school) {
                return $school->id;
            }
        }
        
        // If user is a student or parent
        if ($user->role === 'student' || $user->role === 'parent') {
            $student = \App\Models\Student::where('id', $user->id)->first();
            if ($student) {
                return $student->school_id;
            }
        }
        
        // For staff/teachers, we can add additional role checks here
        
        return null;
    }

    /**
     * Get transport details for the authenticated student
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentTransportDetails()
    {
        try {
            // Get the authenticated user
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 401);
            }
            
            // Find the student - could be the user or a related student
            $student = null;
            $schoolId = null;
            
            if ($user->role === 'student') {
                // The user is a student
                $student = \App\Models\Student::where('id', $user->id)->first();
                if ($student) {
                    $schoolId = $student->school_id;
                }
            } elseif ($user->role === 'parent') {
                // The user is a parent, get their first student
                // This can be enhanced to support multiple children later
                $student = \App\Models\Student::where('parent_id', $user->id)->first();
                if ($student) {
                    $schoolId = $student->school_id;
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only students or parents can access this information.'
                ], 403);
            }
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }
            
            // Check if student has transport enabled
            if (!$student->transport_enabled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transport not enabled for this student'
                ], 404);
            }
            
            // Get pickup point details
            $pickupPoint = \App\Models\PickupPoint::where('id', $student->pickup_point_id)
                ->where('school_id', $schoolId)
                ->first();
                
            if (!$pickupPoint) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pickup point not found for this student'
                ], 404);
            }
            
            // Get route details for this pickup point
            $route = RouteDetail::where('id', $pickupPoint->route_id)
                ->where('school_id', $schoolId)
                ->first();
                
            if (!$route) {
                return response()->json([
                    'success' => false,
                    'message' => 'Route not found for this pickup point'
                ], 404);
            }
            
            // Get assignment details for this route
            $assignment = RouteAssignment::where('route_detail_id', $route->id)
                ->where('school_id', $schoolId)
                ->first();
                
            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'No vehicle assigned to this route'
                ], 404);
            }
            
            // Get vehicle details
            $vehicle = \App\Models\Vehicle::find($assignment->vehicle_id);
            
            // Get driver details
            $driver = \App\Models\VehicleDriver::find($assignment->driver_id);
            
            // Prepare response data
            $transportDetails = [
                'student' => [
                    'id' => $student->id,
                    'admission_number' => $student->admission_number,
                    'student_id' => $student->student_id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                ],
                'pickup_point' => [
                    'id' => $pickupPoint->id,
                    'name' => $pickupPoint->name,
                    'address' => $pickupPoint->address,
                    'pickup_time' => $pickupPoint->pickup_time,
                    'drop_time' => $pickupPoint->drop_time,
                ],
                'route' => [
                    'id' => $route->id,
                    'name' => $route->route_name,
                    'description' => $route->description,
                ],
                'vehicle' => $vehicle ? [
                    'id' => $vehicle->id,
                    'number' => $vehicle->vehicle_no,
                    'model' => $vehicle->model,
                    'capacity' => $vehicle->capacity,
                ] : null,
                'driver' => $driver ? [
                    'id' => $driver->id,
                    'name' => $driver->name,
                    'contact' => $driver->contact_number,
                    'license_number' => $driver->license_number,
                ] : null,
            ];
            
            return response()->json([
                'success' => true,
                'transport_details' => $transportDetails
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching student transport details: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch student transport details: ' . $e->getMessage()
            ], 500);
        }
    }
} 