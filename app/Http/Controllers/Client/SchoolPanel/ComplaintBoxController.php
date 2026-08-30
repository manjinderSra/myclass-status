<?php

namespace App\Http\Controllers\Client\SchoolPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\School;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class ComplaintBoxController extends Controller
{
    /**
     * Display a listing of complaints
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
           
            
            // Check if we have a school ID
            if (!$this->getSchoolId()) {
                // Create an empty paginator instead of a collection
                $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                    [], // Empty array for items
                    0,  // Total items
                    10, // Items per page
                    1   // Current page
                );
                $emptyPaginator->withPath(request()->url());
                
                return view('client.schoolPanel.complaintBox.index', [
                    'complaints' => $emptyPaginator,
                    'status' => null,
                    'error' => 'You are not associated with any school. Please contact the administrator.'
                ]);
            }
            
            $schoolId = $this->getSchoolId();
            
            // Debug output
            Log::info('School ID: ' . $schoolId);
            
            // Check if this school exists
            $school = School::find($schoolId);
            if (!$school) {
                Log::error('School not found!', ['school_id' => $schoolId]);
                // Create an empty paginator instead of a collection
                $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                    [], // Empty array for items
                    0,  // Total items
                    10, // Items per page
                    1   // Current page
                );
                $emptyPaginator->withPath(request()->url());
                
                return view('client.schoolPanel.complaintBox.index', [
                    'complaints' => $emptyPaginator,
                    'status' => null,
                    'error' => 'School not found. Please contact the administrator.'
                ]);
            }
            
            Log::info('School found: ' . $school->name);
            
            // Count all complaints for this school for debugging
            $allComplaintsCount = Complaint::where('school_id', $schoolId)->count();
            Log::info('Total complaints for this school: ' . $allComplaintsCount);
            
            if ($allComplaintsCount == 0) {
                Log::warning('No complaints found for this school. Checking if we need to create a sample complaint.');
                
                // Check if we should auto-create a sample complaint for testing (only in local environment)
                if (app()->environment('local') || app()->environment('development')) {
                    Log::info('Attempting to create a sample complaint for testing.');
                    
                    // Find a student in this school
                    $student = Student::where('school_id', $schoolId)->first();
                    
                    if ($student) {
                        Log::info('Found student for sample complaint: ' . $student->id);
                        
                        try {
                            $complaint = new Complaint();
                            $complaint->school_id = $schoolId;
                            $complaint->student_id = $student->id;
                            $complaint->complaint_id = Complaint::generateComplaintId();
                            $complaint->nature = 'Sample Complaint';
                            $complaint->description = 'This is a sample complaint for testing the complaint box feature.';
                            $complaint->status = 'pending';
                            $complaint->save();
                            
                            Log::info('Sample complaint created with ID: ' . $complaint->id);
                        } catch (\Exception $e) {
                            Log::error('Failed to create sample complaint: ' . $e->getMessage());
                        }
                    } else {
                        Log::warning('No students found for school ID ' . $schoolId . ', cannot create sample complaint.');
                    }
                }
            }
            
            $status = $request->input('status', null);
            
            $query = Complaint::where('school_id', $schoolId)
                        ->with(['student']);
            
            // Debug: Log the SQL query
            Log::info('SQL Query: ' . $query->toSql());
            Log::info('Query Bindings: ' . json_encode($query->getBindings()));
            
            // Filter by status if provided
            if ($status) {
                $query->where('status', $status);
                // Debug: Log the updated query
                Log::info('Filtered SQL Query: ' . $query->toSql());
                Log::info('Filtered Query Bindings: ' . json_encode($query->getBindings()));
            }
            
            $complaints = $query->orderBy('created_at', 'desc')
                            ->paginate(10);
            
            // Debug: Log the result count
            Log::info('Complaints count after query: ' . $complaints->count());
            
            return view('client.schoolPanel.complaintBox.index', [
                'complaints' => $complaints,
                'status' => $status
            ]);
        } catch (\Exception $e) {
            Log::error('Error in complaint box: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An error occurred while retrieving complaints: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified complaint
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
            Log::info('Viewing complaint', [
                'complaint_id' => $id,
                'user_id' => $user->id,
                'school_id' => $schoolId
            ]);
            
            $complaint = Complaint::where('id', $id)
                            ->where('school_id', $schoolId)
                            ->with(['student', 'resolver'])
                            ->firstOrFail();
            
            Log::info('Complaint found', [
                'complaint' => $complaint->id,
                'student' => $complaint->student_id,
                'nature' => $complaint->nature
            ]);
            
            return view('client.schoolPanel.complaintBox.show', [
                'complaint' => $complaint
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing complaint: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return back()->with('error', 'Complaint not found or an error occurred: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the status of a complaint
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        
       
            $user = Auth::user();
            $schoolId = $this->getSchoolId();
          
            
          
            
           
            
            $complaint = Complaint::where('id', $id)
                            ->where('school_id', $schoolId)
                            ->firstOrFail();
            
            $complaint->status = (string)$request->status;
            
            if ($request->status == 'resolved' || $request->status == 'rejected') {
                $complaint->response = $request->response;
                $complaint->resolved_by = $user->id;
                $complaint->resolved_at = now();
            }
            
            $complaint->save();
            
          
            
            return redirect()->route('school.complaintBox.show', $complaint->id)
                        ->with('success', 'Complaint status updated successfully.');
      
    }
}
