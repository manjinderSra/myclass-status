<?php

namespace App\Http\Controllers\Client\SchoolPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentLeave;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\School;
class LeaveApplicationController extends Controller
{
    /**
     * Display a listing of leave applications
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
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
    
     
     
     
     
     
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $schoolId = $this->getSchoolId();
            
            // Debug the user and school ID
            Log::info('Auth user details', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'school_id_from_user' => $user->school_id,
                'role' => $user->role ?? 'No role defined'
            ]);
            
            // Check if we have a school ID
            if (!$this->getSchoolId()) {
                Log::error('User has no school_id!', ['user_id' => $user->id]);
                // Create an empty paginator
                $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                    [], // Empty array for items
                    0,  // Total items
                    10, // Items per page
                    1   // Current page
                );
                $emptyPaginator->withPath(request()->url());
                
                return view('client.schoolPanel.leaveApplications.index', [
                    'leaves' => $emptyPaginator,
                    'status' => null,
                    'error' => 'You are not associated with any school. Please contact the administrator.'
                ]);
            }
            
            $status = $request->input('status', null);
            
            $query = StudentLeave::where('school_id', $schoolId)
                        ->with(['student']);
            
            // Filter by status if provided
            if ($status) {
                $query->where('status', $status);
            }
            
            $leaves = $query->orderBy('created_at', 'desc')
                            ->paginate(10);
            
            return view('client.schoolPanel.leaveApplications.index', [
                'leaves' => $leaves,
                'status' => $status
            ]);
        } catch (\Exception $e) {
            Log::error('Error in leave applications: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An error occurred while retrieving leave applications: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified leave application
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            $schoolId = $this->getSchoolId();
            
            // Debug log
            Log::info('Viewing leave application', [
                'leave_id' => $id,
                'user_id' => $user->id,
                'school_id' => $schoolId
            ]);
            
            $leave = StudentLeave::where('id', $id)
                            ->where('school_id', $schoolId)
                            ->with(['student', 'processor'])
                            ->firstOrFail();
            
            Log::info('Leave application found', [
                'leave' => $leave->id,
                'student' => $leave->student_id,
                'reason' => $leave->reason
            ]);
            
            return view('client.schoolPanel.leaveApplications.show', [
                'leave' => $leave
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing leave application: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->with('error', 'Leave application not found or an error occurred: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the status of a leave application
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $schoolId = $this->getSchoolId();
            
            Log::info('Updating leave application status', [
                'leave_id' => $id,
                'user_id' => $user->id,
                'school_id' => $schoolId,
                'new_status' => $request->status
            ]);
            
            $request->validate([
                'status' => 'required|in:pending,approved,rejected',
                'admin_remarks' => 'required_if:status,rejected|nullable|string|max:1000',
            ]);
            
            $leave = StudentLeave::where('id', $id)
                            ->where('school_id', $schoolId)
                            ->firstOrFail();
            
            $leave->status = $request->status;
            $leave->admin_remarks = $request->admin_remarks;
            $leave->processed_by = $user->id;
            $leave->processed_at = now();
            
            $leave->save();
            
            Log::info('Leave application status updated', [
                'leave_id' => $leave->id,
                'new_status' => $leave->status
            ]);
            
            return redirect()->route('school.leaveApplications.show', $leave->id)
                        ->with('success', 'Leave application status updated successfully.');
                        
        } catch (\Exception $e) {
            Log::error('Error updating leave application status: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->with('error', 'An error occurred while updating the leave application status: ' . $e->getMessage());
        }
    }
}
