<?php

namespace Database\Seeders;

use App\Models\HelpTopic;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class HelpTopicSeeder extends Seeder
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
        
        $helpTopics = [
            [
                'title' => 'Getting Started Guide',
                'category' => 'Getting Started',
                'description' => 'Learn the basics of using the teacher dashboard and managing classes.',
                'content' => '<h2>Introduction to the School Management System</h2>
                <p>Welcome to our comprehensive school management system! This guide will help you get started with the basic features and navigation.</p>
                
                <h3>Logging In</h3>
                <p>To access the system, use your provided credentials at the login page. If you\'ve forgotten your password, use the "Forgot Password" link.</p>
                
                <h3>Dashboard Overview</h3>
                <p>Once logged in, you\'ll see your personalized dashboard with quick access to all main features:</p>
                <ul>
                    <li>Student Management</li>
                    <li>Class Management</li>
                    <li>Attendance</li>
                    <li>Gradebook</li>
                    <li>Calendar</li>
                    <li>Reports</li>
                </ul>
                
                <h3>Getting Help</h3>
                <p>If you need assistance, click on the Help button in the top-right corner or browse our knowledge base articles.</p>',
                'status' => 'Published',
                'view_count' => 152
            ],
            [
                'title' => 'Attendance Management',
                'category' => 'Teachers',
                'description' => 'How to mark and track student attendance, generate reports and handle absence requests.',
                'content' => '<h2>Managing Student Attendance</h2>
                <p>This guide covers all aspects of tracking and managing student attendance.</p>
                
                <h3>Taking Attendance</h3>
                <p>To record attendance:</p>
                <ol>
                    <li>Navigate to the Attendance section</li>
                    <li>Select the class and date</li>
                    <li>Mark each student as Present, Absent, or Late</li>
                    <li>Add any notes if needed</li>
                    <li>Click "Save" to record the attendance</li>
                </ol>
                
                <h3>Generating Reports</h3>
                <p>You can generate various attendance reports from the Reports section:</p>
                <ul>
                    <li>Daily attendance summaries</li>
                    <li>Weekly/monthly attendance trends</li>
                    <li>Individual student attendance records</li>
                    <li>Absence patterns</li>
                </ul>
                
                <h3>Managing Absence Requests</h3>
                <p>When a student submits an absence request:</p>
                <ol>
                    <li>You\'ll receive a notification</li>
                    <li>Review the request details and any attached documentation</li>
                    <li>Approve or deny the request</li>
                    <li>The system will automatically update the attendance record</li>
                </ol>',
                'status' => 'Published',
                'view_count' => 98
            ],
            [
                'title' => 'Grading & Assessment',
                'category' => 'Grading',
                'description' => 'Guidelines for assessing student work, recording grades and providing feedback.',
                'content' => '<h2>Grading and Assessment Guide</h2>
                <p>This article explains how to effectively use the grading system to assess student work and provide meaningful feedback.</p>
                
                <h3>Setting Up Grading Scales</h3>
                <p>Before you begin grading, ensure your grading scale is properly configured:</p>
                <ol>
                    <li>Go to Settings > Grading</li>
                    <li>Select or create a grading scale</li>
                    <li>Define grade categories and weights</li>
                    <li>Save your configuration</li>
                </ol>
                
                <h3>Entering Grades</h3>
                <p>To enter grades for an assignment:</p>
                <ol>
                    <li>Navigate to the Gradebook</li>
                    <li>Select the class and assignment</li>
                    <li>Enter scores for each student</li>
                    <li>Add comments or feedback as needed</li>
                    <li>Click "Save" to record the grades</li>
                </ol>
                
                <h3>Providing Feedback</h3>
                <p>Effective feedback is specific, actionable, and timely. When providing feedback:</p>
                <ul>
                    <li>Highlight specific strengths in the student\'s work</li>
                    <li>Identify areas for improvement</li>
                    <li>Suggest specific strategies for improvement</li>
                    <li>Use a positive and encouraging tone</li>
                </ul>',
                'status' => 'Draft',
                'view_count' => 0
            ],
            [
                'title' => 'Class Management',
                'category' => 'Classes',
                'description' => 'How to create and manage classes, assign students and track progress.',
                'content' => '<h2>Class Management</h2>
                <p>This guide explains how to effectively manage your classes, from creation to student assignments and progress tracking.</p>
                
                <h3>Creating a New Class</h3>
                <ol>
                    <li>Go to the Classes section</li>
                    <li>Click "Create New Class"</li>
                    <li>Fill in the required details (name, subject, grade level)</li>
                    <li>Set the schedule if applicable</li>
                    <li>Click "Create Class"</li>
                </ol>
                
                <h3>Adding Students to a Class</h3>
                <ol>
                    <li>Select the class from your class list</li>
                    <li>Click on "Roster" tab</li>
                    <li>Click "Add Students"</li>
                    <li>Search for students by name or ID</li>
                    <li>Select students to add</li>
                    <li>Click "Add Selected Students"</li>
                </ol>
                
                <h3>Tracking Class Progress</h3>
                <p>To monitor overall class performance:</p>
                <ul>
                    <li>Visit the class dashboard to see attendance trends</li>
                    <li>Check the gradebook summary for grade distribution</li>
                    <li>Use the progress reports to identify struggling students</li>
                    <li>Review assignment completion rates</li>
                </ul>',
                'status' => 'Published',
                'view_count' => 76
            ],
            [
                'title' => 'Student Reports',
                'category' => 'Students',
                'description' => 'How to generate and interpret various types of student reports.',
                'content' => '<h2>Student Reports Guide</h2>
                <p>This guide covers the various reports available for tracking student performance and progress.</p>
                
                <h3>Available Report Types</h3>
                <ul>
                    <li><strong>Progress Reports</strong>: Overview of a student\'s current grades and standing</li>
                    <li><strong>Attendance Reports</strong>: Summary of attendance patterns</li>
                    <li><strong>Behavior Reports</strong>: Log of behavioral incidents and interventions</li>
                    <li><strong>Achievement Reports</strong>: Tracking of skills mastery and standards</li>
                    <li><strong>Comparison Reports</strong>: Student performance relative to class averages</li>
                </ul>
                
                <h3>Generating Reports</h3>
                <ol>
                    <li>Go to the Reports section</li>
                    <li>Select the report type</li>
                    <li>Choose the student(s) to include</li>
                    <li>Set the time period (quarter, semester, year)</li>
                    <li>Select any additional filters or options</li>
                    <li>Click "Generate Report"</li>
                </ol>
                
                <h3>Sharing Reports</h3>
                <p>Reports can be shared with parents, administrators, or other teachers:</p>
                <ol>
                    <li>Open the generated report</li>
                    <li>Click the "Share" button</li>
                    <li>Enter recipient email addresses</li>
                    <li>Add an optional message</li>
                    <li>Click "Send" to distribute the report</li>
                </ol>',
                'status' => 'Archived',
                'view_count' => 45
            ]
        ];
        
        foreach ($schools as $school) {
            // Check if school already has help topics
            $existingTopicsCount = HelpTopic::where('school_id', $school->id)->count();
            
            if ($existingTopicsCount > 0) {
                $this->command->info("School {$school->name} already has {$existingTopicsCount} help topics. Skipping.");
                continue;
            }
            
            $createdCount = 0;
            foreach ($helpTopics as $topicData) {
                // Generate a unique slug with school ID to avoid conflicts
                $slug = Str::slug($topicData['title']) . '-' . $school->id;
                
                try {
                    HelpTopic::create([
                        'school_id' => $school->id,
                        'title' => $topicData['title'],
                        'slug' => $slug,
                        'category' => $topicData['category'],
                        'description' => $topicData['description'],
                        'content' => $topicData['content'],
                        'status' => $topicData['status'],
                        'view_count' => $topicData['view_count'],
                        'created_by' => $adminUser->id,
                        'updated_by' => $adminUser->id
                    ]);
                    $createdCount++;
                } catch (\Exception $e) {
                    $this->command->error("Error creating help topic '{$topicData['title']}' for school {$school->name}: {$e->getMessage()}");
                }
            }
            
            $this->command->info("Created {$createdCount} help topics for school: {$school->name}");
        }
    }
}
