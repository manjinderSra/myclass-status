<?php

namespace App\Http\Controllers\Client\SchoolPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TermsCondition;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\School;

class TermsConditionController extends Controller
{
    /**
     * Display the terms and conditions page.
     *
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
    
     
     
     
    public function index()
    {
        $schoolId = $this->getSchoolId();
        $termsCondition = TermsCondition::where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->first();
        
        return view('client.schoolPanel.generalSettings.termsCondition', compact('termsCondition'));
    }
    
    /**
     * Update the terms and conditions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'version' => 'required|string|max:20',
        ]);
        
        $schoolId = $this->getSchoolId();
        
        try {
            // Create a new version of terms and conditions
            // and set all previous versions to inactive
            TermsCondition::where('school_id', $schoolId)
                ->update(['is_active' => false]);
            
            // Create new active terms and conditions
            $termsCondition = TermsCondition::create([
                'school_id' => $schoolId,
                'title' => $request->title,
                'content' => $request->content,
                'version' => $request->version,
                'is_active' => true,
            ]);
            
            return redirect()->route('school.termsCondition')
                ->with('success', 'Terms and conditions updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating terms and conditions: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while updating the terms and conditions.');
        }
    }
    
    /**
     * Upload and process a terms and conditions file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'version' => 'required|string|max:20',
                'terms_file' => 'required|file|mimes:txt,pdf,doc,docx|max:5120', // 5MB max
            ]);
            
            $schoolId = $this->getSchoolId();
            $file = $request->file('terms_file');
            $content = '';
            
            // Process file content based on file type
            $extension = $file->getClientOriginalExtension();
            
            if ($extension === 'txt') {
                // Handle TXT files directly
                $fileContent = file_get_contents($file->getRealPath());
                // Convert plain text to HTML with paragraphs
                $content = '<p>' . str_replace("\n\n", '</p><p>', str_replace("\n", '<br>', $fileContent)) . '</p>';
                $content = str_replace('<p></p>', '', $content); // Remove empty paragraphs
            } elseif ($extension === 'pdf') {
                // For PDF files, we need to extract text
                // This requires a library like pdftotext or a service
                // For now, we'll store a message about PDFs
                $content = "<h1>PDF File Uploaded: " . $file->getClientOriginalName() . "</h1>";
                $content .= "<p>Please note that this is a PDF file. The content may include formatting that is not preserved in this text representation.</p>";
                $content .= "<p>You may want to manually copy and paste the content from the PDF into the editor for better formatting.</p>";
            } elseif ($extension === 'doc' || $extension === 'docx') {
                // For Word documents, we need a library like PhpWord
                // For now, we'll store a message about Word docs
                $content = "<h1>Word Document Uploaded: " . $file->getClientOriginalName() . "</h1>";
                $content .= "<p>Please note that this is a Word document. The content may include formatting that is not preserved in this text representation.</p>";
                $content .= "<p>You may want to manually copy and paste the content from the Word document into the editor for better formatting.</p>";
            }
            
            // Store the file (optional, if you want to keep the original)
            $path = $file->store('terms', 'public');
            
            // Set all previous versions to inactive
            TermsCondition::where('school_id', $schoolId)
                ->update(['is_active' => false]);
            
            // Create new active terms and conditions
            $termsCondition = TermsCondition::create([
                'school_id' => $schoolId,
                'title' => $request->title,
                'content' => $content,
                'version' => $request->version,
                'is_active' => true,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Terms and conditions uploaded successfully',
                'data' => [
                    'id' => $termsCondition->id,
                    'title' => $termsCondition->title,
                    'version' => $termsCondition->version,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading terms and conditions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get terms and conditions via API using student bearer token authentication.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApiWithToken(Request $request)
    {
        try {
            // Get the authenticated student's school_id
            $student = auth('sanctum')->user();
            
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Invalid or missing token.',
                ], 401);
            }
            
            $schoolId = $this->getSchoolId();
            
            if (!$schoolId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student does not have an associated school',
                ], 400);
            }
            
            $termsCondition = TermsCondition::where('school_id', $schoolId)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->first();
            
            if (!$termsCondition) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terms and conditions not found',
                    'data' => null
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Terms and conditions retrieved successfully',
                'data' => [
                    'title' => $termsCondition->title,
                    'content' => $termsCondition->content,
                    'content_html' => $termsCondition->content,
                    'content_text' => strip_tags($termsCondition->content),
                    'version' => $termsCondition->version,
                    'last_updated' => $termsCondition->updated_at->format('Y-m-d H:i:s')
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving terms and conditions via API: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the information',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
