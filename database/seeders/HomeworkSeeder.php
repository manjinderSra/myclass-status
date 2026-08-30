<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class HomeworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $adminEmail = 'school.admin@example.com';
        $adminUser = DB::table('users')->where('email', $adminEmail)->first();
        
        if ($adminUser) {
            $adminId = $adminUser->id;
            $this->command->info("Using existing admin user with ID: {$adminId}");
        } else {
            // Create an admin user for the school
            $adminId = DB::table('users')->insertGetId([
                'name' => 'School Admin',
                'email' => $adminEmail,
                'password' => Hash::make('password'),
                'role' => 'school',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info("Created new admin user with ID: {$adminId}");
        }

        // Check if school already exists for this admin
        $existingSchool = DB::table('schools')->where('admin_id', $adminId)->first();
        
        if ($existingSchool) {
            $schoolId = $existingSchool->id;
            $this->command->info("Using existing school with ID: {$schoolId}");
        } else {
            // Create a school
            $schoolId = DB::table('schools')->insertGetId([
                'name' => 'Demo School',
                'tagline' => 'Education for All',
                'admin_name' => 'School Admin',
                'email' => 'info@demoschool.com',
                'phone' => '1234567890',
                'website' => 'https://demoschool.com',
                'address' => '123 Education Street, Demo City, Demo State, Demo Country, 12345',
                'about' => 'A demo school for testing purposes',
                'status' => 'active',
                'registration_date' => now(),
                'admin_id' => $adminId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info("Created new school with ID: {$schoolId}");
        }

        // Create classes if they don't exist
        $classNames = [
            'Class 1',
            'Class 2',
            'Class 3',
            'Class 4',
            'Class 5',
        ];

        $classIds = [];
        foreach ($classNames as $className) {
            $existingClass = DB::table('school_classes')
                ->where('school_id', $schoolId)
                ->where('name', $className)
                ->first();
            
            if ($existingClass) {
                $classIds[$className] = $existingClass->id;
                $this->command->info("Using existing class {$className} with ID: {$existingClass->id}");
            } else {
                $classIds[$className] = DB::table('school_classes')->insertGetId([
                    'school_id' => $schoolId,
                    'name' => $className,
                    'total_capacity' => 30,
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info("Created new class {$className} with ID: {$classIds[$className]}");
            }
        }

        // Create sections if they don't exist
        $sectionNames = ['Section A', 'Section B'];
        $sectionIds = [];
        
        foreach ($classNames as $className) {
            $sectionIds[$className] = [];
            
            foreach ($sectionNames as $sectionName) {
                // Make section name unique with a short format
                $classNumber = substr($className, -1);
                $sectionLetter = substr($sectionName, -1);
                $uniqueSectionName = "C{$classNumber}S{$sectionLetter}";
                
                $existingSection = DB::table('sections')
                    ->where('school_id', $schoolId)
                    ->where('name', $uniqueSectionName)
                    ->first();
                
                if ($existingSection) {
                    $sectionIds[$className][$sectionName] = $existingSection->id;
                    $this->command->info("Using existing section {$uniqueSectionName} with ID: {$existingSection->id}");
                } else {
                    $sectionId = DB::table('sections')->insertGetId([
                        'school_id' => $schoolId,
                        'class_id' => $classIds[$className],
                        'name' => $uniqueSectionName,
                        'status' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $sectionIds[$className][$sectionName] = $sectionId;
                    $this->command->info("Created new section {$uniqueSectionName} for {$className} with ID: {$sectionId}");
                    
                    // Also update the class with section ID for the first section
                    if ($sectionName === 'Section A') {
                        DB::table('school_classes')
                            ->where('id', $classIds[$className])
                            ->update(['section_id' => $sectionId]);
                    }
                }
            }
        }

        // Create subjects if they don't exist
        $subjectNames = [
            'Mathematics',
            'Science',
            'English',
            'Hindi',
            'Social Studies',
            'Computer Science',
            'Physical Education',
            'Art',
            'Music',
        ];

        $subjectIds = [];
        foreach ($subjectNames as $subjectName) {
            $code = strtoupper(substr(str_replace(' ', '', $subjectName), 0, 4));
            
            $existingSubject = DB::table('subjects')
                ->where('school_id', $schoolId)
                ->where('name', $subjectName)
                ->first();
            
            if ($existingSubject) {
                $subjectIds[$subjectName] = $existingSubject->id;
                $this->command->info("Using existing subject {$subjectName} with ID: {$existingSubject->id}");
            } else {
                $subjectIds[$subjectName] = DB::table('subjects')->insertGetId([
                    'school_id' => $schoolId,
                    'name' => $subjectName,
                    'code' => $code,
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info("Created new subject {$subjectName} with ID: {$subjectIds[$subjectName]}");
            }
        }

        // Create homeworks for Class 1 Section A
        $class1SectionAId = $sectionIds['Class 1']['Section A'];
        $homeworkData = [
            [
                'subject' => 'Mathematics',
                'homework_date' => now()->format('Y-m-d'),
                'submission_date' => now()->addDays(7)->format('Y-m-d'),
                'description' => 'Class 1 Section A: Complete exercises 1-5 on page 25 of the textbook. Practice addition and subtraction of two-digit numbers.',
            ],
            [
                'subject' => 'English',
                'homework_date' => now()->format('Y-m-d'),
                'submission_date' => now()->addDays(5)->format('Y-m-d'),
                'description' => 'Class 1 Section A: Read the story "The Little Red Hen" and answer the questions on page 15.',
            ],
            [
                'subject' => 'Science',
                'homework_date' => now()->format('Y-m-d'),
                'submission_date' => now()->addDays(3)->format('Y-m-d'),
                'description' => 'Class 1 Section A: Draw and label five different plants found in your garden or neighborhood.',
            ],
            [
                'subject' => 'Hindi',
                'homework_date' => now()->subDays(2)->format('Y-m-d'),
                'submission_date' => now()->addDays(2)->format('Y-m-d'),
                'description' => 'Class 1 Section A: Write five sentences about your family in Hindi.',
            ],
            [
                'subject' => 'Art',
                'homework_date' => now()->subDays(1)->format('Y-m-d'),
                'submission_date' => now()->addDays(6)->format('Y-m-d'),
                'description' => 'Class 1 Section A: Draw a picture of your favorite animal and color it.',
            ],
        ];

        $homeworkCount = 0;
        foreach ($homeworkData as $homework) {
            // Check if similar homework already exists
            $existingHomework = DB::table('homework')
                ->where('school_id', $schoolId)
                ->where('section_id', $class1SectionAId)
                ->where('subject_id', $subjectIds[$homework['subject']])
                ->where('homework_date', $homework['homework_date'])
                ->first();
            
            if (!$existingHomework) {
                DB::table('homework')->insert([
                    'school_id' => $schoolId,
                    'section_id' => $class1SectionAId,
                    'subject_id' => $subjectIds[$homework['subject']],
                    'created_by' => $adminId,
                    'class_name' => 'Class 1',
                    'homework_date' => $homework['homework_date'],
                    'submission_date' => $homework['submission_date'],
                    'description' => $homework['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $homeworkCount++;
                $this->command->info("Created new homework for {$homework['subject']} due on {$homework['submission_date']}");
            } else {
                $this->command->info("Skipping duplicate homework for {$homework['subject']} on {$homework['homework_date']}");
            }
        }

        $this->command->info("School, classes, sections, subjects, and {$homeworkCount} new homeworks created successfully!");
    }
}
