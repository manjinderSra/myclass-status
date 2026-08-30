<?php

namespace App\Http\Controllers\Client\SchoolPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HelpSupport;
use App\Models\Teacher;
use Auth;
use Illuminate\Support\Facades\Log;

class HelpSupportController extends Controller
{
    /**
     * Display the help and support information.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schoolId = Auth::user()->school_id;
        $helpSupport = HelpSupport::where('school_id', $schoolId)->first();
        
        return view('client.schoolPanel.generalSettings.helpSupport', compact('helpSupport'));
    }
    
    /**
     * Update the help and support information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'address' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'working_hours_start' => 'nullable|string|max:10',
            'working_hours_end' => 'nullable|string|max:10',
            'working_days' => 'nullable|string|max:255',
            'phone_numbers' => 'nullable|string|max:500',
        ]);
        
        $schoolId = Auth::user()->school_id;
        
        try {
            // Find or create help support record
            $helpSupport = HelpSupport::updateOrCreate(
                ['school_id' => $schoolId],
                [
                    'address' => $request->address,
                    'email' => $request->email,
                    'website' => $request->website,
                    'working_hours_start' => $request->working_hours_start,
                    'working_hours_end' => $request->working_hours_end,
                    'working_days' => $request->working_days,
                    'phone_numbers' => $request->phone_numbers,
                ]
            );
            
            return redirect()->route('school.helpSupport')->with('success', 'Help and support information updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating help and support information: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the information.');
        }
    }
    
    /**
     * Get help and support details via API.
     *
     * @param  int  $schoolId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApi($schoolId)
    {
        try {
            $helpSupport = HelpSupport::where('school_id', $schoolId)->first();
            
            if (!$helpSupport) {
                return response()->json([
                    'success' => false,
                    'message' => 'Help and support information not found',
                    'data' => null
                ], 404);
            }
            
            $workingHours = '';
            if ($helpSupport->working_hours_start && $helpSupport->working_hours_end) {
                $workingHours = $helpSupport->working_hours_start . ' to ' . $helpSupport->working_hours_end;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Help and support information retrieved successfully',
                'data' => [
                    'address' => $helpSupport->address,
                    'email' => $helpSupport->email,
                    'website' => $helpSupport->website,
                    'working_hours' => $workingHours,
                    'working_days' => $helpSupport->working_days,
                    'phone_numbers' => $helpSupport->phone_numbers,
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving help and support information via API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the information',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get help and support details via API using student or teacher bearer token authentication.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApiWithToken(Request $request)
    {
        try {
            // Get the authenticated user (student or teacher)
            $user = auth('sanctum')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                ], 401);
            }
            
            $schoolId = null;
            
            // Check if the user is a student or teacher and get school_id accordingly
            if ($user->role === 'student') {
                $schoolId = $user->school_id;
            } elseif ($user->role === 'teacher') {
                // Find the teacher by email
                $teacher = Teacher::where('email', $user->email)->first();
                if ($teacher) {
                    $schoolId = $teacher->school_id;
                }
            }
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User does not have an associated school',
                ], 400);
            }
            
            $helpSupport = HelpSupport::where('school_id', $schoolId)->first();
            
            if (!$helpSupport) {
                return response()->json([
                    'success' => false,
                    'message' => 'Help and support information not found',
                    'data' => null
                ], 404);
            }
            
            $workingHours = '';
            if ($helpSupport->working_hours_start && $helpSupport->working_hours_end) {
                $workingHours = $helpSupport->working_hours_start . ' to ' . $helpSupport->working_hours_end;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Help and support information retrieved successfully',
                'data' => [
                    'address' => $helpSupport->address,
                    'email' => $helpSupport->email,
                    'website' => $helpSupport->website,
                    'working_hours' => $workingHours,
                    'working_days' => $helpSupport->working_days,
                    'phone_numbers' => $helpSupport->phone_numbers,
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving help and support information via API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the information',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
