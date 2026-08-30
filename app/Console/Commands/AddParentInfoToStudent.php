<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;

class AddParentInfoToStudent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:add-parent-info {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add parent information to a student for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $studentId = $this->argument('id');
        
        $student = Student::find($studentId);
        
        if (!$student) {
            $this->error("Student with ID {$studentId} not found.");
            return 1;
        }
        
        // Add father information
        $student->father_name = 'John Doe';
        $student->father_email = 'john.doe@example.com';
        $student->father_phone_number = '9876543210';
        $student->father_occupation = 'Software Engineer';
        
        // Add mother information
        $student->mother_name = 'Jane Doe';
        $student->mother_email = 'jane.doe@example.com';
        $student->mother_phone_number = '9876543211';
        $student->mother_occupation = 'Doctor';
        
        // Add guardian information (optional)
        $student->guardian_type = 'relative';
        $student->guardian_name = 'Mike Smith';
        $student->guardian_relation = 'Uncle';
        $student->guardian_email = 'mike.smith@example.com';
        $student->guardian_phone_number = '9876543212';
        $student->guardian_occupation = 'Teacher';
        $student->guardian_address = '123 Guardian St, City';
        
        // Add address information
        $student->current_address = '456 Main St, City, State, 12345';
        $student->permanent_address = '789 Home St, Hometown, State, 54321';
        
        // Save the changes
        $student->save();
        
        $this->info("Parent information added to student {$student->first_name} {$student->last_name}.");
        
        return 0;
    }
}
