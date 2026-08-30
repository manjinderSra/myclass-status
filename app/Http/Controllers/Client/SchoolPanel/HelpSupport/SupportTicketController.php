<?php

namespace App\Http\Controllers\Client\SchoolPanel\HelpSupport;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupportTicketController extends Controller
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
     * Display a listing of support tickets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        // Get tickets for this school
        $tickets = SupportTicket::forSchool($schoolId)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($ticket) {
                return [
                    'id' => $ticket->formatted_ticket_id,
                    'subject' => $ticket->subject,
                    'requester' => $ticket->requester_name,
                    'requester_role' => $ticket->requester_role,
                    'status' => ucfirst($ticket->status),
                    'priority' => ucfirst($ticket->priority),
                    'created_at' => $ticket->created_at->format('j M Y'),
                    'last_updated' => $ticket->updated_at->format('j M Y')
                ];
            });
        
        // Get ticket statistics
        $ticketStats = [
            'open' => SupportTicket::forSchool($schoolId)->where('status', 'open')->count(),
            'in_progress' => SupportTicket::forSchool($schoolId)->where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::forSchool($schoolId)->where('status', 'resolved')->count(),
            'closed' => SupportTicket::forSchool($schoolId)->where('status', 'closed')->count(),
            'total' => SupportTicket::forSchool($schoolId)->count()
        ];
        
        return view('client.schoolPanel.helpSupport.supportTickets.index', [
            'tickets' => $tickets,
            'ticketStats' => $ticketStats
        ]);
    }

    /**
     * Display the specified ticket.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        // Find the ticket (assuming id starts with TCK-)
        $ticketId = preg_replace('/^TCK-/', '', $id);
        $ticket = SupportTicket::forSchool($schoolId)->where('ticket_id', $id)->first();
        
        if (!$ticket) {
            // Try to find by ID if not found by ticket_id
            $ticket = SupportTicket::forSchool($schoolId)->find($ticketId);
        }
        
        if (!$ticket) {
            return redirect()->route('school.supportTickets.index')
                ->with('error', 'Ticket not found');
        }
        
        // Get all messages for this ticket
        $messages = $ticket->messages()
            ->with('user')
            ->orderBy('created_at')
            ->get()
            ->map(function ($message) {
                return [
                    'sender' => $message->sender_name ?? $message->user->name,
                    'sender_role' => $message->sender_type,
                    'content' => $message->message,
                    'timestamp' => $message->created_at->format('j M Y H:i'),
                    'attachments' => $message->attachments
                ];
            });
        
        // If the ticket has no messages, add the initial message as the first message
        if ($messages->isEmpty()) {
            $messages = collect([
                [
                    'sender' => $ticket->requester_name,
                    'sender_role' => $ticket->requester_role,
                    'content' => $ticket->message,
                    'timestamp' => $ticket->created_at->format('j M Y H:i')
                ]
            ]);
        }
        
        // Prepare ticket data for the view
        $ticketData = [
            'id' => $ticket->formatted_ticket_id,
            'subject' => $ticket->subject,
            'requester' => $ticket->requester_name,
            'requester_role' => $ticket->requester_role,
            'requester_email' => $ticket->student ? $ticket->student->email : 'Unknown',
            'status' => ucfirst($ticket->status),
            'priority' => ucfirst($ticket->priority),
            'created_at' => $ticket->created_at->format('j M Y'),
            'last_updated' => $ticket->updated_at->format('j M Y'),
            'messages' => $messages
        ];
        
        return view('client.schoolPanel.helpSupport.supportTickets.show', [
            'ticket' => $ticketData
        ]);
    }

    /**
     * Update the ticket status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Open,In Progress,Resolved,Closed'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Find the ticket by ticket_id or by ID
        $ticket = SupportTicket::forSchool($schoolId)->where('ticket_id', $id)->first();
        
        if (!$ticket) {
            // Try to find by ID if not found by ticket_id
            $ticketId = preg_replace('/^TCK-/', '', $id);
            $ticket = SupportTicket::forSchool($schoolId)->find($ticketId);
        }
        
        if (!$ticket) {
            return redirect()->route('school.supportTickets.index')
                ->with('error', 'Ticket not found');
        }
        
        $status = strtolower($request->status);
        
        // If status is being changed to 'closed', call the close method
        if ($status === 'closed' && $ticket->status !== 'closed') {
            $ticket->close(Auth::id());
        } else {
            $ticket->update(['status' => $status]);
        }
        
        // Add a system message about the status change
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'sender_type' => 'School',
            'sender_name' => Auth::user()->name,
            'message' => "Status changed to: " . ucfirst($status),
            'is_read' => true
        ]);
        
        return redirect()->route('school.supportTickets.show', $id)
            ->with('success', 'Ticket status updated successfully!');
    }

    /**
     * Add a reply to the ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function addReply(Request $request, $id)
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            return redirect()->route('school.login')->with('error', 'School not found');
        }
        
        $validator = Validator::make($request->all(), [
            'message' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Find the ticket by ticket_id or by ID
        $ticket = SupportTicket::forSchool($schoolId)->where('ticket_id', $id)->first();
        
        if (!$ticket) {
            // Try to find by ID if not found by ticket_id
            $ticketId = preg_replace('/^TCK-/', '', $id);
            $ticket = SupportTicket::forSchool($schoolId)->find($ticketId);
        }
        
        if (!$ticket) {
            return redirect()->route('school.supportTickets.index')
                ->with('error', 'Ticket not found');
        }
        
        // Add the reply
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'sender_type' => 'School',
            'sender_name' => Auth::user()->name,
            'message' => $request->message,
            'is_read' => true
        ]);
        
        // If the ticket is closed or resolved, change status to 'in_progress'
        if (in_array($ticket->status, ['closed', 'resolved'])) {
            $ticket->update(['status' => 'in_progress']);
        }
        
        return redirect()->route('school.supportTickets.show', $id)
            ->with('success', 'Reply added successfully!');
    }
} 