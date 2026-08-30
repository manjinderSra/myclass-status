<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\IssuedBook;
use App\Models\Book;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentLibraryController extends Controller
{
    /**
     * Show student's library records.
     *
     * @return \Illuminate\View\View
     */
    public function libraryRecords()
    {
        $student = Student::findOrFail(Session::get('student_id'));
        
        // Get currently issued books (not returned) - using integer value 0 instead of boolean false
        $currentBooks = DB::table('issued_books')
                          ->where('student_id', $student->id)
                          ->where('school_id', $student->school_id)
                          ->where('is_returned', 0)
                          ->orderBy('due_date')
                          ->get();
        
        // Get history of returned books - using integer value 1 instead of boolean true
        $returnedBooks = DB::table('issued_books')
                          ->where('student_id', $student->id)
                          ->where('school_id', $student->school_id)
                          ->where('is_returned', 1)
                          ->orderBy('return_date', 'desc')
                          ->get();
        
        // Check for overdue books
        $overdueBooks = $currentBooks->filter(function($book) {
            return Carbon::parse($book->due_date)->isPast();
        });
        
        // Debug data
        $debugInfo = [
            'student_id' => $student->id,
            'school_id' => $student->school_id,
            'total_books' => DB::table('issued_books')->count(),
            'student_books' => DB::table('issued_books')->where('student_id', $student->id)->count(),
            'current_books_count' => $currentBooks->count(),
            'returned_books_count' => $returnedBooks->count(),
            'overdue_books_count' => $overdueBooks->count()
        ];
        
        return view('client.student.dashboard.library-records', compact(
            'currentBooks', 
            'returnedBooks', 
            'overdueBooks', 
            'debugInfo'
        ));
    }
    
    /**
     * Create a test book for the student
     */
    public function createTestBook()
    {
        $student = Student::findOrFail(Session::get('student_id'));
        
        // Check if there are any books
        $book = Book::where('school_id', $student->school_id)->first();
        
        if (!$book) {
            // Create a test book
            $book = Book::create([
                'book_id' => 'BOOK' . rand(1000, 9999),
                'book_name' => 'Test Book',
                'book_no' => 'BN' . rand(100, 999),
                'rack_no' => 'R' . rand(10, 99),
                'publisher' => 'Test Publisher',
                'author' => 'Test Author',
                'subject' => 'Test Subject',
                'qty' => 5,
                'price' => 500,
                'post_date' => now(),
                'school_id' => $student->school_id
            ]);
        }
        
        // Create a test issued book
        IssuedBook::create([
            'issue_id' => 'IB-' . now()->format('Ymd') . '-' . rand(1000, 9999),
            'school_id' => $student->school_id,
            'student_id' => $student->id,
            'student_name' => $student->first_name . ' ' . $student->last_name,
            'student_class' => 'Class ' . $student->class_id,
            'book_id' => $book->book_id,
            'book_name' => $book->book_name,
            'book_no' => $book->book_no,
            'issue_date' => now()->subDays(5),
            'due_date' => now()->addDays(5),
            'is_returned' => 0
        ]);
        
        return redirect()->route('student.library')->with('success', 'Test book created successfully');
    }
} 