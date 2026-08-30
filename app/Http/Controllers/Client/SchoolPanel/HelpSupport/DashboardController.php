<?php

namespace App\Http\Controllers\Client\SchoolPanel\HelpSupport;

use App\Http\Controllers\Controller;
use App\Models\HelpTopic;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private function getSchoolId()
    {
        if (Auth::check()) {
            // Get the school from the request or find it by admin_id
            $school = request()->school ?? \App\Models\School::where('admin_id', Auth::id())->first();
            
            if ($school) {
                return $school->id;
            }
        }
        return null;
    }

    /**
     * Display the help and support dashboard.
     */
    public function index()
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        // Get summary statistics
        $stats = [
            'activeHelpTopics' => HelpTopic::forSchool($schoolId)->where('status', 'Published')->count(),
            'totalHelpTopicViews' => HelpTopic::forSchool($schoolId)->sum('view_count'),
            'openTickets' => SupportTicket::forSchool($schoolId)->where('status', 'open')->count(),
            'resolvedTicketsThisMonth' => SupportTicket::forSchool($schoolId)
                ->where('status', 'resolved')
                ->whereMonth('updated_at', now()->month)
                ->count()
        ];
        
        // Get recent help topics
        $recentHelpTopics = HelpTopic::forSchool($schoolId)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($topic) {
                return [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'views' => $topic->view_count,
                    'status' => $topic->status,
                    'created_at' => $topic->created_at->format('j M Y')
                ];
            });
        
        // Get recent support tickets
        $recentTickets = SupportTicket::forSchool($schoolId)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($ticket) {
                return [
                    'id' => $ticket->formatted_ticket_id,
                    'subject' => $ticket->subject,
                    'requester' => $ticket->requester_name,
                    'status' => ucfirst($ticket->status),
                    'created_at' => $ticket->created_at->format('j M Y')
                ];
            });
        
        return view('client.schoolPanel.helpSupport.dashboard', [
            'stats' => $stats,
            'recentHelpTopics' => $recentHelpTopics,
            'recentTickets' => $recentTickets
        ]);
    }
} 