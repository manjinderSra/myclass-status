<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;

class StudentsAndTeachersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a school to associate with these records
        $school = School::first();
        if (!$school) {
            // Create a school if none exists
            $school = School::create([
                'name' => 'Demo School',
                'admin_name' => 'Admin',
                'email' => 'school@example.com',
                'phone' => '1234567890',
                'address' => '123 School St, Education City',
                'status' => 'active',
                'registration_date' => now(),
            ]);
        }

        // Get or create a class
        $class = SchoolClass::where('school_id', $school->id)->first();
        if (!$class) {
            $class = SchoolClass::create([
                'name' => 'Class X',
                'school_id' => $school->id,
                'total_capacity' => 30,
                'status' => true,
            ]);
        }

        // Get or create a section
        $section = Section::where('class_id', $class->id)->first();
        if (!$section) {
            $section = Section::create([
                'name' => 'Section A',
                'school_id' => $school->id,
                'class_id' => $class->id,
                'capacity' => 30,
                'status' => true,
            ]);
        }

        // Create two students
        $students = [
            [
                'academic_year' => date('Y'),
                'admission_number' => 'ADM' . date('Y') . '001',
                'academic_number' => Student::generateAcademicNumber($school->id),
                'admission_date' => now(),
                'status' => 'active',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'class_id' => $class->id,
                'section_id' => $section->id,
                'school_id' => $school->id,
                'gender' => 'male',
                'dob' => '2005-01-15',
                'blood_group' => 'O+',
                'religion' => 'Christian',
                'category' => 'General',
                'primary_contact' => '9876543210',
                'email' => 'john.student@example.com',
                'mother_tongue' => 'English',
                'languages_known' => json_encode(['English', 'Spanish']),
                'profile_image' => 'teachers/profile/7kcNviLbjNLVANw0xA2PtQ6DQWdKFlcEpXutTCOZ.jpg',
                'father_name' => 'Richard Doe',
                'father_email' => 'richard@example.com',
                'father_phone_number' => '9876543200',
                'father_occupation' => 'Engineer',
                'mother_name' => 'Mary Doe',
                'mother_email' => 'mary@example.com',
                'mother_phone_number' => '9876543201',
                'mother_occupation' => 'Doctor',
                'guardian_type' => 'father',
                'guardian_name' => 'Richard Doe',
                'guardian_relation' => 'Father',
                'guardian_email' => 'richard@example.com',
                'guardian_phone_number' => '9876543200',
                'guardian_occupation' => 'Engineer',
                'guardian_address' => '123 Family St, City',
                'current_address' => '123 Family St, City',
                'permanent_address' => '123 Family St, City',
                'student_id' => Student::generateStudentId(),
                'password' => Hash::make('password123'),
            ],
            [
                'academic_year' => date('Y'),
                'admission_number' => 'ADM' . date('Y') . '002',
                'academic_number' => Student::generateAcademicNumber($school->id),
                'admission_date' => now(),
                'status' => 'active',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'class_id' => $class->id,
                'section_id' => $section->id,
                'school_id' => $school->id,
                'gender' => 'female',
                'dob' => '2005-03-20',
                'blood_group' => 'A+',
                'religion' => 'Hindu',
                'category' => 'General',
                'primary_contact' => '9876543220',
                'email' => 'jane.student@example.com',
                'mother_tongue' => 'English',
                'languages_known' => json_encode(['English', 'French']),
                'profile_image' => 'teachers/profile/7kcNviLbjNLVANw0xA2PtQ6DQWdKFlcEpXutTCOZ.jpg',
                'father_name' => 'James Smith',
                'father_email' => 'james@example.com',
                'father_phone_number' => '9876543230',
                'father_occupation' => 'Businessman',
                'mother_name' => 'Emily Smith',
                'mother_email' => 'emily@example.com',
                'mother_phone_number' => '9876543231',
                'mother_occupation' => 'Teacher',
                'guardian_type' => 'mother',
                'guardian_name' => 'Emily Smith',
                'guardian_relation' => 'Mother',
                'guardian_email' => 'emily@example.com',
                'guardian_phone_number' => '9876543231',
                'guardian_occupation' => 'Teacher',
                'guardian_address' => '456 Family Ave, City',
                'current_address' => '456 Family Ave, City',
                'permanent_address' => '456 Family Ave, City',
                'student_id' => Student::generateStudentId(),
                'password' => Hash::make('password123'),
            ]
        ];

        foreach ($students as $studentData) {
            Student::create($studentData);
        }

        // Create two teachers
        $teachers = [
            [
                'school_id' => $school->id,
                'employee_id' => 'T' . date('Y') . rand(1000, 9999),
                'first_name' => 'Michael',
                'last_name' => 'Johnson',
                'email' => 'michael.teacher@example.com',
                'password' => Hash::make('password123'),
                'gender' => 'male',
                'primary_contact' => '8765432100',
                'subject' => 'mathematics',
                'date_of_birth' => '1985-07-12',
                'date_of_joining' => '2020-03-15',
                'blood_group' => 'B+',
                'marital_status' => 'married',
                'qualification' => 'M.Sc., B.Ed.',
                'work_experience' => '8 years',
                'status' => 'active',
                'current_address' => '789 Teacher St, Education City',
                'permanent_address' => '789 Teacher St, Education City',
                'profile_image' => 'teachers/profile/7kcNviLbjNLVANw0xA2PtQ6DQWdKFlcEpXutTCOZ.jpg',
                'basic_salary' => 45000,
                'contract_type' => 'permanent',
            ],
            [
                'school_id' => $school->id,
                'employee_id' => 'T' . date('Y') . rand(1000, 9999),
                'first_name' => 'Sarah',
                'last_name' => 'Williams',
                'email' => 'sarah.teacher@example.com',
                'password' => Hash::make('password123'),
                'gender' => 'female',
                'primary_contact' => '8765432200',
                'subject' => 'science',
                'date_of_birth' => '1990-04-25',
                'date_of_joining' => '2021-06-10',
                'blood_group' => 'A-',
                'marital_status' => 'single',
                'qualification' => 'M.Sc. Physics, B.Ed.',
                'work_experience' => '5 years',
                'status' => 'active',
                'current_address' => '101 Teacher St, Education City',
                'permanent_address' => '101 Teacher St, Education City',
                'profile_image' => 'teachers/profile/7kcNviLbjNLVANw0xA2PtQ6DQWdKFlcEpXutTCOZ.jpg',
                'basic_salary' => 42000,
                'contract_type' => 'permanent',
            ]
        ];

        foreach ($teachers as $teacherData) {
            Teacher::create($teacherData);
        }
    }
}
