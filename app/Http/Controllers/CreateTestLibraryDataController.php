<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Book;
use App\Models\IssuedBook;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class CreateTestLibraryDataController extends Controller
{
    /**
     * Create test library data for the current student
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createTestData()
    {
        $student = Student::findOrFail(Session::get('student_id'));
        
        // Check if books exist
        $books = Book::where('school_id', $student->school_id)->limit(3)->get();
        
        if ($books->isEmpty()) {
            // Create test books
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
            }
            
            // Refresh the books collection
            $books = Book::where('school_id', $student->school_id)->limit(3)->get();
        }
        
        // Delete existing issued books for this student
        IssuedBook::where('student_id', $student->student_id)->delete();
        
        // Create a current book (not returned)
        $book = $books->first();
        $issueDate = Carbon::now()->subDays(5);
        $dueDate = Carbon::now()->addDays(5);
        
        IssuedBook::create([
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
            'issue_remarks' => 'Test issued book',
        ]);
        
        // Create a returned book
        $book = $books->last();
        $issueDate = Carbon::now()->subDays(30);
        $dueDate = Carbon::parse($issueDate)->addDays(15);
        $returnDate = Carbon::parse($dueDate)->subDays(3);
        
        IssuedBook::create([
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
            'issue_remarks' => 'Test issued book',
            'return_remarks' => 'Returned on time',
        ]);
        
        return redirect()->route('student.library')->with('success', 'Test library data created successfully');
    }
}
