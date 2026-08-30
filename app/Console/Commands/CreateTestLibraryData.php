<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Book;
use App\Models\IssuedBook;
use Carbon\Carbon;

class CreateTestLibraryData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'library:create-test-data {student_id? : The ID of the student to create data for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test data for the library module';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $studentId = $this->argument('student_id');
        
        if ($studentId) {
            $student = Student::find($studentId);
            if (!$student) {
                $this->error("Student with ID $studentId not found");
                return 1;
            }
            $students = [$student];
        } else {
            $students = Student::limit(5)->get();
            if ($students->isEmpty()) {
                $this->error("No students found in the database");
                return 1;
            }
        }
        
        $this->info("Creating test library data for " . count($students) . " students");
        
        foreach ($students as $student) {
            // Create test books if needed
            $books = Book::where('school_id', $student->school_id)->limit(3)->get();
            
            if ($books->isEmpty()) {
                $this->info("Creating test books for school ID {$student->school_id}");
                for ($i = 1; $i <= 3; $i++) {
                    $book = Book::create([
                        'book_id' => 'BOOK' . rand(1000, 9999),
                        'book_name' => 'Test Book ' . $i,
                        'book_no' => 'BK' . rand(1000, 9999),
                        'rack_no' => 'R' . rand(10, 99),
                        'publisher' => 'Test Publisher',
                        'author' => 'Test Author',
                        'subject' => 'Test Subject',
                        'qty' => rand(5, 20),
                        'price' => rand(100, 1000),
                        'post_date' => Carbon::now()->subDays(rand(1, 30)),
                        'school_id' => $student->school_id,
                    ]);
                    $this->info("Created book: {$book->book_name} (ID: {$book->book_id})");
                }
                
                $books = Book::where('school_id', $student->school_id)->limit(3)->get();
            }
            
            // Delete existing issued books for this student
            $deleted = IssuedBook::where('student_id', $student->student_id)->delete();
            if ($deleted > 0) {
                $this->info("Deleted $deleted existing issued books for student {$student->student_id}");
            }
            
            // Create current books (not returned)
            $this->createCurrentBooks($student, $books);
            
            // Create returned books
            $this->createReturnedBooks($student, $books);
            
            $this->info("Completed creating test data for student {$student->first_name} {$student->last_name} ({$student->student_id})");
        }
        
        $this->info("Test data creation completed");
        return 0;
    }
    
    /**
     * Create current (not returned) books for a student
     */
    private function createCurrentBooks($student, $books)
    {
        $count = rand(1, 2);
        $this->info("Creating $count current books for student {$student->student_id}");
        
        for ($i = 0; $i < $count; $i++) {
            $book = $books->random();
            $issueDate = Carbon::now()->subDays(rand(1, 15));
            $dueDate = Carbon::parse($issueDate)->addDays(rand(7, 30));
            
            $issuedBook = IssuedBook::create([
                'issue_id' => 'IB-' . Carbon::now()->format('Ymd') . '-' . rand(1000, 9999),
                'school_id' => $student->school_id,
                'student_id' => $student->student_id,
                'student_name' => $student->first_name . ' ' . $student->last_name,
                'student_class' => $student->class_id ? 'Class ' . $student->class_id : null,
                'book_id' => $book->book_id,
                'book_name' => $book->book_name,
                'book_no' => $book->book_no,
                'issue_date' => $issueDate->format('Y-m-d'),
                'due_date' => $dueDate->format('Y-m-d'),
                'is_returned' => 0,
                'issue_remarks' => 'Test issued book ' . ($i+1),
            ]);
            
            $this->info("Created current book: {$book->book_name} for student {$student->student_id}");
        }
    }
    
    /**
     * Create returned books for a student
     */
    private function createReturnedBooks($student, $books)
    {
        $count = rand(1, 2);
        $this->info("Creating $count returned books for student {$student->student_id}");
        
        for ($i = 0; $i < $count; $i++) {
            $book = $books->random();
            $issueDate = Carbon::now()->subDays(rand(30, 60));
            $dueDate = Carbon::parse($issueDate)->addDays(rand(7, 21));
            $returnDate = Carbon::parse($dueDate)->subDays(rand(1, 5)); // Return before due date
            
            $issuedBook = IssuedBook::create([
                'issue_id' => 'IB-' . Carbon::now()->format('Ymd') . '-' . rand(1000, 9999),
                'school_id' => $student->school_id,
                'student_id' => $student->student_id,
                'student_name' => $student->first_name . ' ' . $student->last_name,
                'student_class' => $student->class_id ? 'Class ' . $student->class_id : null,
                'book_id' => $book->book_id,
                'book_name' => $book->book_name,
                'book_no' => $book->book_no,
                'issue_date' => $issueDate->format('Y-m-d'),
                'due_date' => $dueDate->format('Y-m-d'),
                'return_date' => $returnDate->format('Y-m-d'),
                'is_returned' => 1,
                'issue_remarks' => 'Test issued book ' . ($i+1),
                'return_remarks' => 'Returned on time',
            ]);
            
            $this->info("Created returned book: {$book->book_name} for student {$student->student_id}");
        }
    }
} 