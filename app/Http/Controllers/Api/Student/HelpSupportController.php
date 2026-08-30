<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HelpSupport;
use App\Models\Student;
use App\Models\HelpTopic;
use App\Models\FAQ;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class HelpSupportController extends Controller
{
    /**
     * Get help and support information for students
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHelpSupport(Request $request)
    {
        try {
            // Get the authenticated student using the student guard and load school relationship
            $student = Student::with('school')->find($request->user()->id);
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing student token.',
                ], 401);
            }
            
            // Get school ID from the student
            $schoolId = $student->school_id;
            
            if (!$schoolId) {
                // Log the issue for debugging
                Log::error('Student missing school_id', [
                    'student_id' => $student->id,
                    'student_email' => $student->email,
                    'student_data' => $student->toArray()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Student does not have an associated school',
                ], 400);
            }
            
            // Get help support information
            $helpSupport = HelpSupport::where('school_id', $schoolId)->first();
            
            // Get help topics
            $helpTopics = HelpTopic::where('school_id', $schoolId)
                ->where('status', 'Published')
                ->select('id', 'title', 'description', 'category')
                ->get();
            
            // Get FAQs
            $faqs = FAQ::where('school_id', $schoolId)
                ->orderBy('priority')
                ->select('question', 'answer')
                ->get();
            
            // Format working hours
            $workingHours = '';
            if ($helpSupport && $helpSupport->working_hours_start && $helpSupport->working_hours_end) {
                $workingHours = $helpSupport->working_hours_start . ' to ' . $helpSupport->working_hours_end;
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Help and support information retrieved successfully',
                'data' => [
                    'contact' => $helpSupport ? [
                        'address' => $helpSupport->address,
                        'email' => $helpSupport->email,
                        'website' => $helpSupport->website,
                        'working_hours' => $workingHours,
                        'working_days' => $helpSupport->working_days,
                        'phone_numbers' => $helpSupport->phone_numbers,
                    ] : null,
                    'help_topics' => $helpTopics,
                    'faqs' => $faqs
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving student help and support information: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the information',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 