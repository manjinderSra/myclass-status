<?php

namespace App\Http\Controllers\Client\SchoolPanel\Announcements;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notice;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoticeController extends Controller
{
    /**
     * Display the notice board view with notices.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        try {
            // Get notices for this school
            $notices = Notice::where('school_id', $schoolId)
                ->orderBy('publish_date', 'desc')
                ->get();
                
            return view('client.schoolPanel.announcements.noticeBoard', [
                'notices' => $notices
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading notices: ' . $e->getMessage());
            return view('client.schoolPanel.announcements.noticeBoard', [
                'notices' => []
            ])->with('error', 'An error occurred while loading notices.');
        }
    }
    
    /**
     * Store a new notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
           'message' => [
    'required',
    'string',
    function ($attribute, $value, $fail) {
        if (str_word_count($value) > 30) {
            $fail('The '.$attribute.' may not be greater than 30 words.');
        }
    },
],
            'publish_on' => 'required|date',
            'recipients' => 'required|array',
            'recipients.*' => 'string|in:Student,Teacher,Admin,Library,Finance',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('school.notices')
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            // Create the notice
            Notice::create([
                'school_id' => $schoolId,
                'title' => $request->title,
                'message' => $request->message,
                'publish_date' => $request->publish_on,
                'recipients' => $request->recipients,
                'created_by' => Auth::id(),
            ]);
            
            return redirect()->route('school.notices')
                ->with('success', 'Notice created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('school.notices')
                ->with('error', 'Failed to create notice: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Show the form for editing a notice.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        // Find the notice
        $notice = Notice::where('school_id', $schoolId)
            ->where('id', $id)
            ->firstOrFail();
        
        // Get all notices for display
        $notices = Notice::where('school_id', $schoolId)
            ->orderBy('publish_date', 'desc')
            ->get();
        
        return view('client.schoolPanel.announcements.noticeBoard', [
            'notices' => $notices,
            'editNotice' => $notice
        ]);
    }
    
    /**
     * Update a notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        // Find the notice
        $notice = Notice::where('school_id', $schoolId)
            ->where('id', $id)
            ->firstOrFail();
        
        // Validate the request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
                       'message' => [
    'required',
    'string',
    function ($attribute, $value, $fail) {
        if (str_word_count($value) > 30) {
            $fail('The '.$attribute.' may not be greater than 30 words.');
        }
    },
],
            'publish_on' => 'required|date',
            'recipients' => 'required|array',
            'recipients.*' => 'string|in:Student,Teacher,Admin,Library,Finance',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('school.notices.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            // Update the notice
            $notice->update([
                'title' => $request->title,
                'message' => $request->message,
                'publish_date' => $request->publish_on,
                'recipients' => $request->recipients,
            ]);
            
            return redirect()->route('school.notices')
                ->with('success', 'Notice updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('school.notices.edit', $id)
                ->with('error', 'Failed to update notice: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Delete a notice.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        // Find the notice
        $notice = Notice::where('school_id', $schoolId)
            ->where('id', $id)
            ->firstOrFail();
        
        try {
            // Delete the notice
            $notice->delete();
            
            return redirect()->route('school.notices')
                ->with('success', 'Notice deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('school.notices')
                ->with('error', 'Failed to delete notice: ' . $e->getMessage());
        }
    }
    
    /**
     * Get the current school ID.
     *
     * @return int|null
     */
    private function getSchoolId()
    {
        $user = Auth::user();
        
        if (!$user) {
            return null;
        }
        
        // Check if user is associated with a school
        $school = School::where('admin_id', $user->id)->first();
        
        if (!$school) {
            return null;
        }
        
        return $school->id;
    }
}
