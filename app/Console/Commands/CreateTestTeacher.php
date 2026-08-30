<?php

namespace App\Console\Commands;

use App\Models\Teacher;
use App\Models\School;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateTestTeacher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test-teacher {school_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test teacher account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get school parameter or prompt for it
        $schoolId = $this->argument('school_id');
        
        if (!$schoolId) {
            $schools = School::pluck('name', 'id')->toArray();
            
            if (empty($schools)) {
                $this->error('No schools found. Please create a school first.');
                return 1;
            }
            
            $schoolId = $this->choice('Select a school:', $schools);
        }
        
        // Generate employee ID
        $employeeId = 'TCH-' . strtoupper(Str::random(2)) . rand(1000, 9999);
        
        // Generate a password
        $plainPassword = 'teacher123'; // Default password for testing
        
        // Create the teacher
        $teacher = new Teacher([
            'school_id' => $schoolId,
            'employee_id' => $employeeId,
            'first_name' => 'Test',
            'last_name' => 'Teacher',
            'email' => 'test.teacher' . rand(100, 999) . '@example.com',
            'password' => Hash::make($plainPassword),
            'gender' => 'male',
            'primary_contact' => '9876543210',
            'subject' => 'Mathematics',
            'date_of_birth' => now()->subYears(30),
            'date_of_joining' => now()->subMonths(6),
            'status' => 'active',
        ]);
        
        $teacher->save();
        
        $this->info('Test teacher created successfully!');
        $this->table(
            ['Employee ID', 'Password', 'Name', 'Email'],
            [[$employeeId, $plainPassword, $teacher->first_name . ' ' . $teacher->last_name, $teacher->email]]
        );
        
        return 0;
    }
} 