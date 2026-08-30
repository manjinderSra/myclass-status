<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IssuedBookTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the current student ID from the session (we'll create for all students)
        $students = DB::table('students')->limit(10)->get();
        
        if ($students->isEmpty()) {
            $this->command->info('No students found. Aborting seeder.');
            return;
        }
        
        // Generate some sample books if none exist
        $books = DB::table('books')->limit(5)->get();
        
        if ($books->isEmpty()) {
            $this->command->info('No books found. Creating sample books...');
            // Create some sample books
            for ($i = 1; $i <= 5; $i++) {
                DB::table('books')->insert([
                    'book_id' => 'BOOK' . rand(1000, 9999),
                    'book_name' => 'Sample Book ' . $i,
                    'book_no' => 'BK' . rand(1000, 9999),
                    'rack_no' => 'R' . rand(10, 99),
                    'publisher' => 'Test Publisher',
                    'author' => 'Test Author',
                    'subject' => 'Test Subject',
                    'qty' => rand(5, 20),
                    'price' => rand(100, 1000),
                    'post_date' => Carbon::now()->subDays(rand(1, 30)),
                    'school_id' => $students->first()->school_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            
            $books = DB::table('books')->limit(5)->get();
        }
        
        // Create some test issued books for each student
        foreach ($students as $student) {
            // First, make sure there are no existing records for this student
            DB::table('issued_books')->where('student_id', $student->id)->delete();
            
            // Create 1-3 current books (not returned)
            $currentBooksCount = rand(1, 3);
            for ($i = 0; $i < $currentBooksCount; $i++) {
                $book = $books->random();
                $issueDate = Carbon::now()->subDays(rand(1, 30));
                $dueDate = Carbon::parse($issueDate)->addDays(rand(7, 21));
                
                // Create issue ID
                $issueId = 'IB-' . Carbon::now()->format('Ymd') . '-' . rand(1000, 9999);
                
                DB::table('issued_books')->insert([
                    'issue_id' => $issueId,
                    'school_id' => $student->school_id,
                    'student_id' => $student->id,
                    'student_name' => $student->first_name . ' ' . $student->last_name,
                    'student_class' => $student->class_id ? 'Class ' . $student->class_id : null,
                    'book_id' => $book->book_id,
                    'book_name' => $book->book_name,
                    'book_no' => $book->book_no,
                    'issue_date' => $issueDate,
                    'due_date' => $dueDate,
                    'is_returned' => 0,  // Not returned
                    'issue_remarks' => 'Test issue remarks',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            
            // Create 1-2 returned books
            $returnedBooksCount = rand(1, 2);
            for ($i = 0; $i < $returnedBooksCount; $i++) {
                $book = $books->random();
                $issueDate = Carbon::now()->subDays(rand(30, 60));
                $dueDate = Carbon::parse($issueDate)->addDays(rand(7, 21));
                $returnDate = Carbon::parse($dueDate)->subDays(rand(1, 5)); // Return before due date
                
                // Create issue ID
                $issueId = 'IB-' . Carbon::now()->format('Ymd') . '-' . rand(1000, 9999);
                
                DB::table('issued_books')->insert([
                    'issue_id' => $issueId,
                    'school_id' => $student->school_id,
                    'student_id' => $student->id,
                    'student_name' => $student->first_name . ' ' . $student->last_name,
                    'student_class' => $student->class_id ? 'Class ' . $student->class_id : null,
                    'book_id' => $book->book_id,
                    'book_name' => $book->book_name,
                    'book_no' => $book->book_no,
                    'issue_date' => $issueDate,
                    'due_date' => $dueDate,
                    'return_date' => $returnDate,
                    'is_returned' => 1,  // Returned
                    'issue_remarks' => 'Test issue remarks',
                    'return_remarks' => 'Test return remarks',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            
            // Create 0-1 overdue books
            if (rand(0, 1) == 1) {
                $book = $books->random();
                $issueDate = Carbon::now()->subDays(rand(30, 45));
                $dueDate = Carbon::parse($issueDate)->subDays(rand(5, 15)); // Due date in the past
                
                // Create issue ID
                $issueId = 'IB-' . Carbon::now()->format('Ymd') . '-' . rand(1000, 9999);
                
                DB::table('issued_books')->insert([
                    'issue_id' => $issueId,
                    'school_id' => $student->school_id,
                    'student_id' => $student->id,
                    'student_name' => $student->first_name . ' ' . $student->last_name,
                    'student_class' => $student->class_id ? 'Class ' . $student->class_id : null,
                    'book_id' => $book->book_id,
                    'book_name' => $book->book_name,
                    'book_no' => $book->book_no,
                    'issue_date' => $issueDate,
                    'due_date' => $dueDate,
                    'is_returned' => 0,  // Not returned
                    'issue_remarks' => 'Test issue remarks',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
        
        $this->command->info('Created test issued books for ' . $students->count() . ' students.');
    }
} 