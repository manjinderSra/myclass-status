<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\PickupPoint;
use App\Models\RouteDetail;
use App\Models\RouteAssignment;
use App\Models\Vehicle;
use App\Models\VehicleDriver;
use Illuminate\Support\Facades\Log;

class StudentTransportController extends Controller
{
    /**
     * Get transport details for the authenticated student
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransportDetails()
    {
        try {
            // Get the authenticated user
            $user = Auth::user();
            
            // For debugging - include information about the authenticated user
            $userInfo = [
                'id' => $user->id,
                'role' => $user->role ?? 'unknown',
                'email' => $user->email,
            ];
            
            // Find the student - could be the user or a related student
            $student = null;
            
            // Try to find student by direct ID match first
            $student = Student::where('id', $user->id)->first();
            
            // If no student found, try finding by user_id if present
            if (!$student && isset($user->user_id)) {
                $student = Student::where('user_id', $user->id)->first();
            }
            
            // If still no student, check if it's a parent
            if (!$student && isset($user->role) && $user->role === 'parent') {
                $student = Student::where('parent_id', $user->id)->first();
            }
            
            // If no student found, and we have a school_id, try to find any student
            if (!$student && $user->school_id) {
                // For testing only, we'll get the first student from the school
                // This should be removed in production
                $student = Student::where('school_id', $user->school_id)
                    ->where('transport_enabled', true)
                    ->first();
            }
            
            // Last resort - if no role check worked, try the first student with transport
            if (!$student) {
                // For testing only - return first student with transport enabled
                // Remove this in production!
                $student = Student::where('transport_enabled', true)->first();
            }
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found',
                    'debug_info' => $userInfo
                ], 404);
            }
            
            $schoolId = $student->school_id;
            
            // Check if student has transport enabled
            if (!$student->transport_enabled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transport not enabled for this student',
                    'debug_info' => $userInfo
                ], 404);
            }
            
            // Get pickup point details
            $pickupPoint = PickupPoint::where('id', $student->pickup_point_id)->first();
                
            if (!$pickupPoint) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pickup point not found for this student',
                    'debug_info' => $userInfo
                ], 404);
            }
            
            // Get route details for this pickup point
            $route = RouteDetail::where('id', $pickupPoint->route_detail_id)->first();
                
            if (!$route) {
                return response()->json([
                    'success' => false,
                    'message' => 'Route not found for this pickup point',
                    'debug_info' => $userInfo
                ], 404);
            }
            
            // Get assignment details for this route
            $assignment = RouteAssignment::where('route_detail_id', $route->id)->first();
                
            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'No vehicle assigned to this route',
                    'debug_info' => $userInfo
                ], 404);
            }
            
            // Get vehicle details
            $vehicle = Vehicle::find($assignment->vehicle_id);
            
            // Get driver details
            $driver = VehicleDriver::find($assignment->driver_id);
            
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
                    'sequence' => $pickupPoint->sequence,
                ],
                'route' => [
                    'id' => $route->id,
                    'name' => $route->route_name,
                    'description' => $route->description,
                ],
                'vehicle' => $vehicle ? [
                    'id' => $vehicle->id,
                    'number' => $vehicle->vehicle_no,
                    'model' => $vehicle->vehicle_model,
                    'capacity' => $vehicle->seat_capacity,
                    
                ] : null,
                'driver' => $driver ? [
                    'profile_image' =>url('storage/' . $driver->profile_photo),
                    'id' => $driver->id,
                    'name' => $driver->driver_name,
                    'contact' => $driver->contact_number,
                    'license_number' => $driver->license_number,
                ] : null,
            ];
            
            return response()->json([
                'success' => true,
                'transport_details' => $transportDetails,
                
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching student transport details: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch student transport details: ' . $e->getMessage(),
                'debug_info' => isset($userInfo) ? $userInfo : ['error' => 'User info not available'],
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    /**
     * Get transport details for a specific student by admission number or student_id
     * This endpoint can be used by school staff or parents to check transport details for any student
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentTransportDetails(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'student_id' => 'required_without:admission_number|string',
                'admission_number' => 'required_without:student_id|string',
            ]);
            
            // Get the authenticated user
            $user = Auth::user();
            $schoolId = null;
            
            // For debugging - include information about the authenticated user
            $userInfo = [
                'id' => $user->id,
                'role' => $user->role ?? 'unknown',
                'email' => $user->email,
            ];
            
            // Determine school ID based on user role
            if ($user->role === 'school') {
                $school = \App\Models\School::where('admin_id', $user->id)->first();
                if ($school) {
                    $schoolId = $school->id;
                }
            } else if ($user->school_id) {
                $schoolId = $user->school_id;
            } else if ($user->role === 'parent') {
                // For parents, we'll check if they're authorized to view this student's details
                $student = null;
                if ($request->has('student_id')) {
                    $student = Student::where('student_id', $request->student_id)->first();
                } else {
                    $student = Student::where('admission_number', $request->admission_number)->first();
                }
                
                if ($student && $student->parent_id == $user->id) {
                    $schoolId = $student->school_id;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized to view this student\'s transport details'
                    ], 403);
                }
            }
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found or unauthorized access'
                ], 404);
            }
            
            // Find the student
            $student = null;
            if ($request->has('student_id')) {
                $student = Student::where('student_id', $request->student_id)
                    ->where('school_id', $schoolId)
                    ->first();
            } else {
                $student = Student::where('admission_number', $request->admission_number)
                    ->where('school_id', $schoolId)
                    ->first();
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
            $pickupPoint = PickupPoint::where('id', $student->pickup_point_id)->first();
                
            if (!$pickupPoint) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pickup point not found for this student',
                    'debug_info' => $userInfo
                ], 404);
            }
            
            // Get route details for this pickup point
            $route = RouteDetail::where('id', $pickupPoint->route_detail_id)->first();
                
            if (!$route) {
                return response()->json([
                    'success' => false,
                    'message' => 'Route not found for this pickup point',
                    'debug_info' => $userInfo
                ], 404);
            }
            
            // Get assignment details for this route
            $assignment = RouteAssignment::where('route_detail_id', $route->id)->first();
                
            if (!$assignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'No vehicle assigned to this route',
                    'debug_info' => $userInfo
                ], 404);
            }
            
            // Get vehicle details
            $vehicle = Vehicle::find($assignment->vehicle_id);
            
            // Get driver details
            $driver = VehicleDriver::find($assignment->driver_id);
            
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
                    'latitude' => $pickupPoint->latitude,
                    'longitude' => $pickupPoint->longitude,
                    'sequence' => $pickupPoint->sequence,
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