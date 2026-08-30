<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TeacherAnnouncementsController extends Controller
{
    /**
     * Display a listing of announcements for teachers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get the current teacher's school ID
        $schoolId = Session::get('teacher_school');
        
        // Fetch notices for this school that are either for all users or specifically for teachers
        $notices = Notice::where('school_id', $schoolId)
                        ->where(function($query) {
                            $query->whereJsonContains('recipients', 'Teacher')
                                  ->orWhereNull('recipients');
                        })
                        ->with('creator:id,name') // Join with users table to get creator name
                        ->orderBy('publish_date', 'desc')
                        ->paginate(10);
                        
        // Add the creator's name to each notice
        $notices->each(function($notice) {
            $notice->created_by_name = $notice->creator ? $notice->creator->name : 'Admin';
        });
        
        return view('client.teacher.announcements.index', compact('notices'));
    }
    
    /**
     * Mark all announcements as read for the current teacher.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function markAllAsRead(Request $request)
    {
        // Get the current teacher's ID
        $teacherId = Session::get('teacher_id');
        
        // Logic to mark all announcements as read
        // This would typically update a teacher_notice pivot table or similar
        
        return redirect()->back()->with('success', 'All announcements marked as read.');
    }
} 