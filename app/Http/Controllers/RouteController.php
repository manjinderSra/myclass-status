<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\RouteDetail;
use App\Models\PickupPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RouteController extends Controller
{
    /**
     * Display a listing of the routes.
     */
    public function index()
    {
        try {
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return redirect()->back()->with('error', 'School not found');
            }
            
            $routes = RouteDetail::with('pickupPoints')
                ->where('school_id', $schoolId)
                ->orderBy('route_name')
                ->get();
                
            return view('client.schoolPanel.transport.routes', compact('routes'));
        } catch (\Exception $e) {
            Log::error('Error fetching routes: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to fetch routes: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created route in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'route_name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'pickup_points' => 'required|array|min:1',
                'pickup_points.*.name' => 'required|string|max:255',
                'pickup_points.*.latitude' => 'nullable|string',
                'pickup_points.*.longitude' => 'nullable|string',
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
            
            // Use transaction to ensure data consistency
            DB::beginTransaction();
            
            try {
                // Create new route
                $route = RouteDetail::create([
                    'school_id' => $schoolId,
                    'route_name' => $request->route_name,
                    'description' => $request->description,
                    'status' => $request->has('status') ? 1 : 0,
                ]);
                
                // Create pickup points
                $sequence = 0;
                foreach ($request->pickup_points as $point) {
                    PickupPoint::create([
                        'route_detail_id' => $route->id,
                        'name' => $point['name'],
                        'latitude' => $point['latitude'] ?? null,
                        'longitude' => $point['longitude'] ?? null,
                        'sequence' => $sequence++,
                    ]);
                }
                
                DB::commit();
                
                // Load pickup points for the response
                $route->load('pickupPoints');
                
                Log::info('Route created successfully', ['route_id' => $route->id]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Route created successfully',
                    'route' => $route
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating route: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create route: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified route.
     */
    public function show(RouteDetail $route)
    {
        try {
            // Check if route belongs to current school
            if ($route->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to route'
                ], 403);
            }
            
            // Load the pickup points
            $route->load('pickupPoints');
            
            return response()->json([
                'success' => true,
                'route' => $route
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing route: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch route: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified route in storage.
     */
    public function update(Request $request, RouteDetail $route)
    {
        try {
            // Check if route belongs to current school
            if ($route->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to route'
                ], 403);
            }
            
            // Validate request
            $validator = Validator::make($request->all(), [
                'route_name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'pickup_points' => 'required|array|min:1',
                'pickup_points.*.name' => 'required|string|max:255',
                'pickup_points.*.latitude' => 'nullable|string',
                'pickup_points.*.longitude' => 'nullable|string',
                'status' => 'nullable|boolean',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }
            
            // Use transaction to ensure data consistency
            DB::beginTransaction();
            
            try {
                // Update route details
                $route->route_name = $request->route_name;
                $route->description = $request->description;
                $route->status = $request->has('status') ? 1 : 0;
                $route->save();
                
                // Delete existing pickup points
                $route->pickupPoints()->delete();
                
                // Create new pickup points
                $sequence = 0;
                foreach ($request->pickup_points as $point) {
                    PickupPoint::create([
                        'route_detail_id' => $route->id,
                        'name' => $point['name'],
                        'latitude' => $point['latitude'] ?? null,
                        'longitude' => $point['longitude'] ?? null,
                        'sequence' => $sequence++,
                    ]);
                }
                
                DB::commit();
                
                // Load pickup points for the response
                $route->load('pickupPoints');
                
                Log::info('Route updated successfully', ['route_id' => $route->id]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Route updated successfully',
                    'route' => $route
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating route: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update route: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified route from storage.
     */
    public function destroy(RouteDetail $route)
    {
        try {
            // Check if route belongs to current school
            if ($route->school_id != $this->getSchoolId()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to route'
                ], 403);
            }
            
            // Use transaction to ensure data consistency
            DB::beginTransaction();
            
            try {
                // Delete all pickup points (this should happen automatically due to the foreign key constraint)
                $route->pickupPoints()->delete();
                
                // Delete route
                $route->delete();
                
                DB::commit();
                
                Log::info('Route deleted successfully', ['route_id' => $route->id]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Route deleted successfully'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting route: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete route: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all routes for the current school (API endpoint).
     */
    public function getAllRoutes()
    {
        try {
            Log::info('getAllRoutes API called');
            
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                Log::error('School not found in getAllRoutes');
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }
            
            $routes = RouteDetail::with('pickupPoints')
                ->where('school_id', $schoolId)
                ->orderBy('route_name')
                ->get();
                
            Log::info('Routes fetched successfully', ['count' => $routes->count()]);
            
            return response()->json([
                'success' => true,
                'routes' => $routes
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching routes: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch routes: ' . $e->getMessage()
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
