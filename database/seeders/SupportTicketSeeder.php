<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Student;
use App\Models\SupportTicket;
use App\Models\Teacher;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SupportTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get schools and admin users
        $schools = School::all();
        $adminUser = User::where('role', 'admin')->first();
        
        if (!$adminUser) {
            $adminUser = User::first();
        }
        
        if ($schools->isEmpty()) {
            $this->command->error('No schools found. Please run SchoolSeeder first.');
            return;
        }
        
        $ticketData = [
            [
                'subject' => 'Issue with attendance recording',
                'message' => "I'm having trouble recording student attendance. When I try to mark a student as present, the system shows an error message saying 'Operation failed'. This happens for multiple classes and students.",
                'priority' => 'medium',
                'status' => 'open',
                'messages' => [
                    [
                        'message' => "Thank you for reporting this issue. Could you please provide more information about when this started happening? Also, what browser are you using?",
                        'sender_type' => 'Support'
                    ],
                    [
                        'message' => "This started happening yesterday morning. I'm using Chrome browser on a Windows laptop.",
                        'sender_type' => 'Teacher'
                    ]
                ]
            ],
            [
                'subject' => 'Cannot access grading module',
                'message' => "When I try to access the grading module, I get an error message saying 'You do not have permission to access this module'. I need to enter grades for the mid-term exams by Friday.",
                'priority' => 'high',
                'status' => 'closed',
                'messages' => [
                    [
                        'message' => "I've checked your account permissions and found that your role wasn't properly set up. I've fixed this issue now. Please try accessing the grading module again and let me know if it works.",
                        'sender_type' => 'Support'
                    ],
                    [
                        'message' => "Thank you! I can access the grading module now. Everything is working correctly.",
                        'sender_type' => 'Teacher'
                    ],
                    [
                        'message' => "Great! I'm closing this ticket. Feel free to open a new one if you encounter any other issues.",
                        'sender_type' => 'Support'
                    ]
                ]
            ],
            [
                'subject' => 'Need help with student reports',
                'message' => "I need to generate end-of-term reports for all students in my class. I've tried using the Reports section, but I'm not sure how to include all the information required by our school policy.",
                'priority' => 'low',
                'status' => 'in_progress',
                'messages' => [
                    [
                        'message' => "I'd be happy to help you with the reports. Could you please tell me what specific information you need to include that you're having trouble with?",
                        'sender_type' => 'Support'
                    ],
                    [
                        'message' => "We need to include attendance percentages, grade averages by subject category, and teacher comments. I can't find where to add the teacher comments for each subject.",
                        'sender_type' => 'Teacher'
                    ],
                    [
                        'message' => "To add teacher comments, you need to go to the 'Comments' tab when generating reports. I'm attaching a screenshot showing the exact location. Let me know if this helps!",
                        'sender_type' => 'Support'
                    ]
                ]
            ],
            [
                'subject' => 'Login issue for new teacher',
                'message' => "We have a new teacher who can't log in to the system. Their account was created yesterday, but they get an 'Invalid credentials' error when trying to log in.",
                'priority' => 'medium',
                'status' => 'resolved',
                'messages' => [
                    [
                        'message' => "Could you provide the email address or username of the teacher who's having trouble logging in?",
                        'sender_type' => 'Support'
                    ],
                    [
                        'message' => "Their email is janedoe@example.com",
                        'sender_type' => 'School'
                    ],
                    [
                        'message' => "I've checked the account and found that the account was created but the confirmation email might not have been received. I've manually activated the account and reset the password to a temporary one: 'NewTeacher2023'. Please ask them to log in with this password and change it immediately.",
                        'sender_type' => 'Support'
                    ],
                    [
                        'message' => "Thank you! The teacher was able to log in successfully.",
                        'sender_type' => 'School'
                    ],
                    [
                        'message' => "Great! I'm marking this ticket as resolved. If there are any other issues, please let us know.",
                        'sender_type' => 'Support'
                    ]
                ]
            ]
        ];
        
        foreach ($schools as $school) {
            // Check if school already has support tickets
            $existingTicketsCount = SupportTicket::where('school_id', $school->id)->count();
            
            if ($existingTicketsCount > 0) {
                $this->command->info("School {$school->name} already has {$existingTicketsCount} support tickets. Skipping.");
                continue;
            }
            
            // Get some students for this school
            $students = Student::where('school_id', $school->id)->take(3)->get();
            
            if ($students->isEmpty()) {
                $this->command->info("No students found for school: {$school->name}. Skipping ticket creation.");
                continue;
            }
            
            $createdCount = 0;
            foreach ($ticketData as $index => $data) {
                $student = $students[$index % count($students)];
                
                try {
                    // Generate a unique ticket ID
                    $ticketId = 'TCK-' . $school->id . '-' . Str::random(6);
                    
                    // Create ticket
                    $ticket = SupportTicket::create([
                        'school_id' => $school->id,
                        'student_id' => $student->id,
                        'ticket_id' => $ticketId,
                        'subject' => $data['subject'],
                        'message' => $data['message'],
                        'priority' => $data['priority'],
                        'status' => $data['status'],
                        'closed_at' => $data['status'] === 'closed' ? now()->subDays(rand(1, 5)) : null,
                        'closed_by' => $data['status'] === 'closed' ? $adminUser->id : null
                    ]);
                    
                    // Create initial message from student/teacher
                    TicketMessage::create([
                        'ticket_id' => $ticket->id,
                        'user_id' => $student->user_id ?? $adminUser->id,
                        'sender_type' => 'Teacher', // We'll use 'Teacher' as the sender type even though it's a student
                        'message' => $data['message'],
                        'is_read' => true
                    ]);
                    
                    // Create conversation messages
                    foreach ($data['messages'] as $messageData) {
                        $userId = $messageData['sender_type'] === 'Teacher' 
                            ? ($student->user_id ?? $adminUser->id)
                            : $adminUser->id;
                            
                        TicketMessage::create([
                            'ticket_id' => $ticket->id,
                            'user_id' => $userId,
                            'sender_type' => $messageData['sender_type'],
                            'message' => $messageData['message'],
                            'is_read' => true
                        ]);
                    }
                    
                    $createdCount++;
                } catch (\Exception $e) {
                    $this->command->error("Error creating support ticket '{$data['subject']}' for school {$school->name}: {$e->getMessage()}");
                }
            }
            
            $this->command->info("Created {$createdCount} support tickets for school: {$school->name}");
        }
    }
}
