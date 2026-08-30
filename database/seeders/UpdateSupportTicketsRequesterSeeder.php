<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use App\Models\Student;

class UpdateSupportTicketsRequesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = SupportTicket::all();
        
        foreach ($tickets as $ticket) {
            // Check if this is a teacher-created ticket by looking at ticket messages
            $firstMessage = $ticket->messages()->orderBy('created_at')->first();
            
            if ($firstMessage && $firstMessage->sender_type === 'Teacher') {
                // This is a teacher-created ticket
                $ticket->requester_role = 'Teacher';
                $ticket->requester_name = $firstMessage->sender_name ?? 'Teacher';
            } else if ($ticket->student_id) {
                // This is a student-created ticket
                $student = Student::find($ticket->student_id);
                if ($student) {
                    $ticket->requester_role = 'Student';
                    $ticket->requester_name = $student->name ?? ('Student #' . $student->id);
                }
            } else {
                // Default fallback
                $ticket->requester_role = 'Unknown';
                $ticket->requester_name = 'Unknown User';
            }
            
            $ticket->save();
            
            $this->command->info("Updated ticket #{$ticket->id} ({$ticket->ticket_id}): {$ticket->requester_role} - {$ticket->requester_name}");
        }
        
        $this->command->info('All tickets updated with requester information!');
    }
}
