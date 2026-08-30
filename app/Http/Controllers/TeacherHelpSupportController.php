<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\HelpTopic;
use App\Models\SupportTicket;
use App\Models\Teacher;
use App\Models\TicketMessage;
use App\Models\Student;
use App\Models\HelpSupport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;

class TeacherHelpSupportController extends Controller
{
    /**
     * Display the help and support page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get the current teacher's ID and school ID
        $teacherId = Session::get('teacher_id');
        $schoolId = Session::get('teacher_school');
        
        if (!$teacherId || !$schoolId) {
            return redirect()->route('teacher.login')->with('error', 'Session expired. Please login again.');
        }
        
        // Get teacher details
        $teacher = Teacher::find($teacherId);
        
        if (!$teacher) {
            return redirect()->route('teacher.login')->with('error', 'Teacher not found. Please login again.');
        }
        
        // Get help topics for this school
        $helpTopicsFromDB = HelpTopic::where('school_id', $schoolId)
            ->where('status', 'Published')
            ->orderBy('title')
            ->get();
            
        // Transform help topics to include icons
        $icons = [
            'Getting Started' => 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25',
            'Teachers' => 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5',
            'Students' => 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5',
            'Classes' => 'M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5',
            'Attendance' => 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1',
            'Grading' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z',
            'Communication' => 'M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46'
        ];
        
        $defaultIcon = 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z M15 12a3 3 0 11-6 0 3 3 0 016 0z';
        
        $helpTopics = $helpTopicsFromDB->map(function($topic) use ($icons, $defaultIcon) {
            $iconPath = $icons[$topic->category] ?? $defaultIcon;
            
            return [
                'id' => $topic->id,
                'title' => $topic->title,
                'description' => $topic->description,
                'icon' => $topic->icon ?? $iconPath,
                'slug' => $topic->slug
            ];
        });
        
        // Get the teacher's support tickets
        $ticketsQuery = SupportTicket::where('school_id', $schoolId);
        
        // We need to link tickets to teachers. Since our database schema doesn't have teacher_id,
        // we'll need to find tickets that belong to this teacher through the TicketMessage table
        $teacherUserId = $teacher->user_id;
        
        if ($teacherUserId) {
            $ticketIds = TicketMessage::where('user_id', $teacherUserId)
                ->where('sender_type', 'Teacher')
                ->pluck('ticket_id')
                ->unique();
                
            $ticketsQuery->whereIn('id', $ticketIds);
        }
        
        $recentTickets = $ticketsQuery->orderByDesc('created_at')
            ->get()
            ->map(function($ticket) {
                return [
                    'id' => $ticket->formatted_ticket_id,
                    'title' => $ticket->subject,
                    'status' => ucfirst($ticket->status),
                    'created_at' => $ticket->created_at->format('j M Y'),
                    'priority' => ucfirst($ticket->priority)
                ];
            });
        
        // Get contact information from the help_supports table
        $helpSupport = HelpSupport::where('school_id', $schoolId)->first();
        
        $contactInfo = [];
        
        if ($helpSupport) {
            $contactInfo = [
                'email' => $helpSupport->email ?? 'support@schoolsystem.com',
                'phone' => $helpSupport->phone_numbers ?? '+1 (800) 555-1234',
                'hours' => ($helpSupport->working_hours_start && $helpSupport->working_hours_end && $helpSupport->working_days) 
                    ? "{$helpSupport->working_days}, {$helpSupport->working_hours_start} - {$helpSupport->working_hours_end}"
                    : 'Monday - Friday, 8:00 AM - 5:00 PM'
            ];
        } else {
            $contactInfo = [
                'email' => 'support@schoolsystem.com',
                'phone' => '+1 (800) 555-1234',
                'hours' => 'Monday - Friday, 8:00 AM - 5:00 PM'
            ];
        }

        return view('client.teacher.help-support.index', compact('helpTopics', 'recentTickets', 'contactInfo'));
    }

    /**
     * Submit a new support ticket
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);
        
        // Get the current teacher's ID and school ID
        $teacherId = Session::get('teacher_id');
        $schoolId = Session::get('teacher_school');
        
        if (!$teacherId || !$schoolId) {
            return redirect()->route('teacher.login')->with('error', 'Session expired. Please login again.');
        }
        
        // Get teacher details
        $teacher = Teacher::find($teacherId);
        
        if (!$teacher) {
            return redirect()->route('teacher.login')->with('error', 'Teacher not found. Please login again.');
        }
        
        try {
            // Generate a unique ticket ID
            $ticketId = 'TCK-' . $schoolId . '-' . Str::random(6);
            
            // Get a student to associate the ticket with (the current DB schema requires a student_id)
            $student = Student::where('school_id', $schoolId)->first();
            
            if (!$student) {
                return redirect()->route('teacher.help-support')
                    ->with('error', 'Unable to create ticket. Please contact the administrator.');
            }
            
            // Create the support ticket
            $ticket = SupportTicket::create([
                'school_id' => $schoolId,
                'student_id' => $student->id, // Using a student ID as a workaround
                'requester_role' => 'Teacher',
                'requester_name' => $teacher->full_name,
                'ticket_id' => $ticketId,
                'subject' => $request->subject,
                'message' => $request->description,
                'priority' => strtolower($request->priority),
                'status' => 'open'
            ]);
            
            // Create the initial message from the teacher
            if ($ticket) {
                // Find the admin user for this message
                $adminUser = User::where('role', 'admin')->first();
                $userId = $adminUser ? $adminUser->id : 1;
                
                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $userId, // Use admin user ID
                    'sender_type' => 'Teacher',
                    'sender_name' => $teacher->full_name,
                    'message' => $request->description,
                    'is_read' => true
                ]);
            }
            
            return redirect()->route('teacher.help-support')
                ->with('success', 'Your support ticket has been submitted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('teacher.help-support')
                ->with('error', 'Error creating ticket. Please try again later.');
        }
    }

    /**
     * View a specific help topic
     *
     * @param  string  $topic
     * @return \Illuminate\Http\Response
     */
    public function viewTopic($topic)
    {
        // Get the current teacher's school ID
        $schoolId = Session::get('teacher_school');
        
        if (!$schoolId) {
            return redirect()->route('teacher.login')->with('error', 'Session expired. Please login again.');
        }
        
        // Get the help topic from the database
        $helpTopic = HelpTopic::where('slug', $topic)
            ->where('school_id', $schoolId)
            ->where('status', 'Published')
            ->first();
        
        if (!$helpTopic) {
            return redirect()->route('teacher.help-support')
                ->with('error', 'Topic not found.');
        }
        
        // Increment view count
        $helpTopic->incrementViewCount();
        
        // Get related topics
        $relatedTopics = HelpTopic::where('school_id', $schoolId)
            ->where('status', 'Published')
            ->where('id', '!=', $helpTopic->id)
            ->where(function($query) use ($helpTopic) {
                $query->where('category', $helpTopic->category)
                      ->orWhere('title', 'like', '%' . substr($helpTopic->title, 0, 5) . '%');
            })
            ->limit(3)
            ->get();
        
        // Prepare topic details
        $topicDetails = [
            'id' => $helpTopic->id,
            'title' => $helpTopic->title,
            'content' => $helpTopic->content,
            'last_updated' => $helpTopic->updated_at->format('j M Y'),
            'category' => $helpTopic->category,
            'related_topics' => $relatedTopics
        ];
        
        return view('client.teacher.help-support.topic', compact('topicDetails'));
    }
    
    /**
     * View a specific support ticket and its conversation thread
     *
     * @param  string  $ticketId
     * @return \Illuminate\Http\Response
     */
    public function viewTicket($ticketId)
    {
        // Get the current teacher's ID and school ID
        $teacherId = Session::get('teacher_id');
        $schoolId = Session::get('teacher_school');
        
        if (!$teacherId || !$schoolId) {
            return redirect()->route('teacher.login')->with('error', 'Session expired. Please login again.');
        }
        
        // Get teacher details
        $teacher = Teacher::find($teacherId);
        
        if (!$teacher) {
            return redirect()->route('teacher.login')->with('error', 'Teacher not found. Please login again.');
        }
        
        // Handle both formatted ticket IDs (TCK-123) and database IDs
        if (strpos($ticketId, 'TCK-') === 0) {
            $ticket = SupportTicket::where('ticket_id', $ticketId)
                ->where('school_id', $schoolId)
                ->first();
        } else {
            $ticket = SupportTicket::where('id', $ticketId)
                ->where('school_id', $schoolId)
                ->first();
        }
        
        if (!$ticket) {
            return redirect()->route('teacher.help-support')
                ->with('error', 'Ticket not found.');
        }
        
        // Get the conversation thread
        $messages = TicketMessage::where('ticket_id', $ticket->id)
            ->orderBy('created_at')
            ->get()
            ->map(function($message) use ($teacher) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_type' => $message->sender_type,
                    'sender' => $message->sender_name ?? ($message->sender_type === 'Teacher' ? $teacher->full_name : 'Support Team'),
                    'sender_role' => $message->sender_type,
                    'created_at' => $message->created_at->format('j M Y, h:i A'),
                    'is_read' => $message->is_read
                ];
            });
        
        // Mark all unread messages as read
        TicketMessage::where('ticket_id', $ticket->id)
            ->where('sender_type', '!=', 'Teacher')
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        // Prepare ticket details
        $ticketDetails = [
            'id' => $ticket->id,
            'formatted_id' => $ticket->ticket_id,
            'subject' => $ticket->subject,
            'message' => $ticket->message,
            'status' => ucfirst($ticket->status),
            'priority' => ucfirst($ticket->priority),
            'created_at' => $ticket->created_at->format('j M Y, h:i A'),
            'updated_at' => $ticket->updated_at->format('j M Y, h:i A'),
            'messages' => $messages,
            'requester_name' => $ticket->requester_name ?? $teacher->full_name,
            'requester_role' => $ticket->requester_role ?? 'Teacher'
        ];
        
        return view('client.teacher.help-support.ticket', compact('ticketDetails'));
    }
    
    /**
     * Reply to a support ticket
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $ticketId
     * @return \Illuminate\Http\Response
     */
    public function replyToTicket(Request $request, $ticketId)
    {
        $request->validate([
            'message' => 'required|string'
        ]);
        
        // Get the current teacher's ID and school ID
        $teacherId = Session::get('teacher_id');
        $schoolId = Session::get('teacher_school');
        
        if (!$teacherId || !$schoolId) {
            return redirect()->route('teacher.login')->with('error', 'Session expired. Please login again.');
        }
        
        // Get teacher details
        $teacher = Teacher::find($teacherId);
        
        if (!$teacher) {
            return redirect()->route('teacher.login')->with('error', 'Teacher not found. Please login again.');
        }
        
        // Find the ticket
        $ticket = SupportTicket::where('id', $ticketId)
            ->where('school_id', $schoolId)
            ->first();
        
        if (!$ticket) {
            return redirect()->route('teacher.help-support')
                ->with('error', 'Ticket not found.');
        }
        
        try {
            // Find the admin user for this message
            $adminUser = User::where('role', 'admin')->first();
            $userId = $adminUser ? $adminUser->id : 1;
            
            // Create the message with admin user ID
            $message = TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $userId, // Use admin user ID 
                'sender_type' => 'Teacher',
                'sender_name' => $teacher->full_name,
                'message' => $request->message,
                'is_read' => true
            ]);
            
            // Update the ticket status if it was closed
            if ($ticket->status === 'closed') {
                $ticket->status = 'reopened';
                $ticket->save();
            }
            
            // Update the ticket's updated_at timestamp
            $ticket->touch();
            
            return redirect()->route('teacher.help-support.ticket', $ticket->id)
                ->with('success', 'Your reply has been sent successfully.');
        } catch (\Exception $e) {
            return redirect()->route('teacher.help-support.ticket', $ticket->id)
                ->with('error', 'Error sending reply. Please try again later.');
        }
    }
} 