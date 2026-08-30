<?php

namespace App\Http\Controllers\Client\SchoolPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookNumber;
use App\Models\Student;
use App\Models\IssuedBook;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Exception;

class LibraryController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = auth()->user();

        // Find the school
        $school = \App\Models\School::where('admin_id', $user->id)->first();

        if (!$school) {
            return view('client.schoolPanel.library.books', ['books' => []]);
        }

        $books = Book::where('school_id', $school->id)->get();
        return view('client.schoolPanel.library.books', compact('books'));
    }

    public function store(Request $request)
    {
        try {
            // Validate basic fields
            $validated = $request->validate([
                'book_id' => 'required|string|unique:books,book_id|max:50',
                'book_name' => 'required|string|max:255',
                'rack_no' => 'required|string|max:50',
                'publisher' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'qty' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'post_date' => 'required|date',
                'book_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

                'book_numbers' => 'required|array|min:1',
                'book_numbers.*' => 'required|string|max:255|distinct',  // no duplicates within form
            ]);

            // Auth
            $user = auth()->user();
            $school = School::where('admin_id', $user->id)->first();

            if (!$school) {
                throw new \Exception('School not found for this user');
            }

            // Save book first
            $book = new Book();
            $book->book_id     = $validated['book_id'];
            $book->book_name   = $validated['book_name'];
            $book->rack_no     = $validated['rack_no'];
            $book->publisher   = $validated['publisher'];
            $book->author      = $validated['author'];
            $book->subject     = $validated['subject'];
            $book->qty         = $validated['qty'];
            $book->price       = $validated['price'];
            $book->post_date   = $validated['post_date'];
            $book->school_id   = $school->id;

            // Image
            if ($request->hasFile('book_image')) {
                $book->image_path = $request->file('book_image')->store('books', 'public');
            }

            $book->save();

            // CHECK ONLY FOR THIS BOOK, NOT ALL BOOKS
            foreach ($request->book_numbers as $manualNumber) {
                $exists = BookNumber::where('book_id', $book->id)
                    ->where('book_no', $manualNumber)
                    ->exists();

                if ($exists) {
                    throw new \Exception("Book number '{$manualNumber}' already exists for this book.");
                }
            }

            // Insert numbers
            foreach ($request->book_numbers as $manualNumber) {
                BookNumber::create([
                    'book_id' => $book->id,
                    'issued_books_id' => null,
                    'book_no' => $manualNumber,
                    'status' => 'return',
                ]);
            }

            return redirect()->route('school.books.index')->with('success', 'Book added successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate
            $validated = $request->validate([
                'edit_book_name' => 'required|string|max:255',
                'edit_rack_no' => 'required|string|max:50',
                'edit_publisher' => 'required|string|max:255',
                'edit_author' => 'required|string|max:255',
                'edit_subject' => 'required|string|max:255',
                'edit_qty' => 'required|integer|min:1',
                'new_book_numbers.*' => 'nullable|string|max:50', // manual numbers for new copies
                'edit_price' => 'required|numeric|min:0',
                'edit_post_date' => 'required|date',
                'edit_book_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $user = auth()->user();
            $school = \App\Models\School::where('admin_id', $user->id)->first();

            if (!$school) {
                throw new \Exception('School not found');
            }

            $book = Book::where('id', $id)
                ->where('school_id', $school->id)
                ->firstOrFail();

            $oldQty = $book->qty;
            $newQty = $validated['edit_qty'];

            // Update basic fields
            $book->book_name = $validated['edit_book_name'];
            $book->rack_no = $validated['edit_rack_no'];
            $book->publisher = $validated['edit_publisher'];
            $book->author = $validated['edit_author'];
            $book->subject = $validated['edit_subject'];
            $book->qty = $newQty;
            $book->price = $validated['edit_price'];
            $book->post_date = $validated['edit_post_date'];

            if ($request->hasFile('edit_book_image')) {
                if ($book->image_path && Storage::disk('public')->exists($book->image_path)) {
                    Storage::disk('public')->delete($book->image_path);
                }

                $book->image_path = $request->file('edit_book_image')->store('books', 'public');
            }

            $book->save();

            // ------------------------------------------
            // HANDLE QTY INCREASE (MANUAL ADD)
            // ------------------------------------------
            if ($newQty > $oldQty) {

                $toAdd = $newQty - $oldQty;

                if (!$request->new_book_numbers || count($request->new_book_numbers) != $toAdd) {
                    throw new \Exception("Enter book numbers for all new copies!");
                }

                foreach ($request->new_book_numbers as $manualNumber) {

                    if (!$manualNumber) continue;

                    \App\Models\BookNumber::create([
                        'book_id' => $book->id,
                        'issued_books_id' => null,
                        'book_no' => $manualNumber,
                        'status' => 'return',
                    ]);
                }
            }

            // ------------------------------------------
            // HANDLE QTY REDUCTION
            // ------------------------------------------
            if ($newQty < $oldQty) {

                $toRemove = $oldQty - $newQty;

                $removeList = \App\Models\BookNumber::where('book_id', $book->id)
                    ->where('status', 'return')
                    ->orderBy('id', 'desc')
                    ->take($toRemove)
                    ->get();

                if ($removeList->count() < $toRemove) {
                    throw new \Exception("Cannot reduce qty — some copies are issued.");
                }

                foreach ($removeList as $bn) {
                    $bn->delete();
                }
            }

            return redirect()->route('school.books.index')->with('success', 'Book updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = auth()->user();

            $school = \App\Models\School::where('admin_id', $user->id)->first();

            if (!$school) {
                throw new \Exception('School not found for this user');
            }

            $book = Book::where('id', $id)
                ->where('school_id', $school->id)
                ->firstOrFail();

            // -----------------------------------------
            // 1. CHECK IF ANY COPY IS ISSUED
            // -----------------------------------------
            $issuedCount = \App\Models\BookNumber::where('book_id', $book->id)
                ->where('status', 'issued')
                ->count();

            if ($issuedCount > 0) {
                throw new \Exception("Cannot delete book — some copies are currently issued.");
            }

            // -----------------------------------------
            // 2. DELETE BOOK NUMBERS (copies)
            // -----------------------------------------
            \App\Models\BookNumber::where('book_id', $book->id)->delete();

            // -----------------------------------------
            // 3. DELETE IMAGE IF EXISTS
            // -----------------------------------------
            if ($book->image_path && Storage::disk('public')->exists($book->image_path)) {
                Storage::disk('public')->delete($book->image_path);
            }

            // -----------------------------------------
            // 4. DELETE THE BOOK
            // -----------------------------------------
            $book->delete();

            return redirect()->route('school.books.index')
                ->with('success', 'Book deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    /**
     * Fetch student details by student ID
     */
    public function fetchStudent($student_id)
    {
        try {
            // Get the authenticated user
            $user = auth()->user();

            // Find the school
            $school = \App\Models\School::where('admin_id', $user->id)->first();

            if (!$school) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }

            // Find the student by student_id and school_id
            $student = Student::where('student_id', $student_id)
                ->where('school_id', $school->id)
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            // Get the student's class and section
            $class = $student->class ? $student->class->name : '';
            $section = $student->section ? $student->section->name : '';

            // Format class and section together
            $classSection = $class;
            if ($section) {
                $classSection .= ", " . $section;
            }

            // Prepare the response data
            $responseData = [
                'success' => true,
                'student' => [
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'student_id' => $student->student_id,
                    'class' => $classSection,
                    'status' => $student->status,
                    'image' => $student->profile_image ? asset('storage/' . $student->profile_image) : asset('img/default-student.png')
                ]
            ];

            return response()->json($responseData);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching student details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch book details by book ID
     */
    public function fetchBook($book_id)
    {
        try {
            $user = auth()->user();

            $school = \App\Models\School::where('admin_id', $user->id)->first();
            if (!$school) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }

            // Get book
            $book = Book::where('book_id', $book_id)
                ->where('school_id', $school->id)
                ->first();

            if (!$book) {
                return response()->json([
                    'success' => false,
                    'message' => 'Book not found'
                ], 404);
            }

            // Fetch all book numbers
            $bookNumbers = \App\Models\BookNumber::where('book_id', $book->id)
                ->select('id', 'book_no', 'status')
                ->get();

            // Calculate counts
            $totalCopies      = $bookNumbers->count();
            $availableCopies  = $bookNumbers->where('status', 'return')->count();

            return response()->json([
                'success' => true,
                'book' => [
                    'book_id'           => $book->book_id,
                    'book_name'         => $book->book_name,
                    'author'            => $book->author,
                    'subject'           => $book->subject,
                    'rack_no'           => $book->rack_no,

                    // NEW IMPORTANT FIELDS
                    'total_copies'      => $totalCopies,
                    'available_copies'  => $availableCopies,
                    'manual_book_numbers' => $bookNumbers,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching book details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the issued books
     */
    public function issuedBooks()
    {
        $user = auth()->user();
        $school = \App\Models\School::where('admin_id', $user->id)->first();

        if (!$school) {
            return view('client.schoolPanel.library.issueBooks', ['issuedBooks' => []]);
        }

        $issuedBooks = IssuedBook::where('school_id', $school->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.schoolPanel.library.issueBooks', compact('issuedBooks'));
    }

    /**
     * Store a newly issued book
     */
    public function issueBook(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'student_id' => 'required|string',
                'student_name' => 'required|string',
                'student_class' => 'nullable|string',
                'book_id' => 'required|string',
                'book_name' => 'required|string',
                'book_number_id' => 'required|integer',
                'issue_date' => 'required|date',
                'due_date' => 'required|date|after_or_equal:issue_date',
                'issue_remarks' => 'nullable|string'
            ]);

            // Auth user + school
            $user = auth()->user();
            $school = \App\Models\School::where('admin_id', $user->id)->first();

            if (!$school) {
                throw new \Exception('School not found for this user');
            }

            // Validate book exists
            $book = Book::where('book_id', $validated['book_id'])
                ->where('school_id', $school->id)
                ->first();

            if (!$book) {
                throw new \Exception('Book not found');
            }

            // Validate selected copy
            $bookNumber = \App\Models\BookNumber::where('id', $validated['book_number_id'])
                ->where('book_id', $book->id)
                ->where('status', 'return')
                ->first();

            if (!$bookNumber) {
                throw new \Exception('Selected book copy is not available');
            }

            // Generate issue ID
            $issueId = $this->generateIssueId();

            // Create issue record
            $issuedBook = new IssuedBook();
            $issuedBook->issue_id = $issueId;
            $issuedBook->school_id = $school->id;
            $issuedBook->student_id = $validated['student_id'];
            $issuedBook->student_name = $validated['student_name'];
            $issuedBook->student_class = $validated['student_class'];
            $issuedBook->book_id = $validated['book_id'];
            $issuedBook->book_name = $validated['book_name'];
            $issuedBook->book_no = $bookNumber->book_no;
            $issuedBook->issue_date = $validated['issue_date'];
            $issuedBook->due_date = $validated['due_date'];
            $issuedBook->issue_remarks = $validated['issue_remarks'];
            $issuedBook->is_returned = false;
            $issuedBook->save();

            // Mark this copy as issued
            $bookNumber->status = 'issued';
            $bookNumber->issued_books_id = $issuedBook->id;
            $bookNumber->save();

            // Reduce main stock
            $book->qty -= 1;
            $book->save();

            // Return JSON for AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Book issued successfully'
                ]);
            }

            // Normal redirect
            return redirect()->route('school.issueBooks')->with('success', 'Book issued successfully');
        } catch (Exception $e) {

            // AJAX error response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            // Normal error
            return back()->withInput()->with('error', 'Failed to issue book: ' . $e->getMessage());
        }
    }



    /**
     * Show the details of an issued book
     */
    public function showIssuedBook($id)
    {
        // Get the authenticated user
        $user = auth()->user();

        // Find the school
        $school = \App\Models\School::where('admin_id', $user->id)->first();

        if (!$school) {
            return redirect()->route('school.issueBooks')->with('error', 'School not found');
        }

        $issuedBook = IssuedBook::where('id', $id)
            ->where('school_id', $school->id)
            ->firstOrFail();

        return view('client.schoolPanel.library.showIssuedBook', compact('issuedBook'));
    }

    /**
     * Show the form for editing an issued book
     */
    public function editIssuedBook($id)
    {
        // Get the authenticated user
        $user = auth()->user();

        // Find the school
        $school = \App\Models\School::where('admin_id', $user->id)->first();

        if (!$school) {
            return redirect()->route('school.issueBooks')->with('error', 'School not found');
        }

        $issuedBook = IssuedBook::where('id', $id)
            ->where('school_id', $school->id)
            ->firstOrFail();

        return view('client.schoolPanel.library.editIssuedBook', compact('issuedBook'));
    }

    /**
     * Update an issued book
     */
    public function updateIssuedBook(Request $request, $id)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'due_date' => 'required|date',
                'issue_remarks' => 'nullable|string'
            ]);

            // Get the authenticated user
            $user = auth()->user();

            // Find the school
            $school = \App\Models\School::where('admin_id', $user->id)->first();

            if (!$school) {
                throw new \Exception('School not found for this user');
            }

            // Find the issued book
            $issuedBook = IssuedBook::where('id', $id)
                ->where('school_id', $school->id)
                ->firstOrFail();

            // Update the issued book
            $issuedBook->due_date = $validated['due_date'];
            $issuedBook->issue_remarks = $validated['issue_remarks'];
            $issuedBook->save();

            return redirect()->route('school.issueBooks')->with('success', 'Issued book updated successfully');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Failed to update issued book: ' . $e->getMessage());
        }
    }

    /**
     * Return a book (mark as returned)
     */
    public function returnBook(Request $request, $id)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'return_date' => 'required|date',
                'return_remarks' => 'nullable|string'
            ]);

            // Auth user
            $user = auth()->user();

            // School
            $school = \App\Models\School::where('admin_id', $user->id)->first();
            if (!$school) {
                throw new \Exception('School not found for this user');
            }

            // Issued Book
            $issuedBook = IssuedBook::where('id', $id)
                ->where('school_id', $school->id)
                ->firstOrFail();

            if ($issuedBook->is_returned) {
                throw new \Exception('Book is already returned');
            }

            // Mark as returned
            $issuedBook->return_date = $validated['return_date'];
            $issuedBook->return_remarks = $validated['return_remarks'];
            $issuedBook->is_returned = true;
            $issuedBook->save();

            /*
        |--------------------------------------------------------------------------
        | NEW: Restore the specific copy in book_numbers table
        |--------------------------------------------------------------------------
        */
            $bookCopy = \App\Models\BookNumber::where('issued_books_id', $issuedBook->id)->first();

            if ($bookCopy) {
                $bookCopy->status = 'return';          // available again
                $bookCopy->issued_books_id = null;     // remove link to issued book
                $bookCopy->save();
            }

            /*
        |--------------------------------------------------------------------------
        | Update main book qty
        |--------------------------------------------------------------------------
        */
            $book = Book::where('book_id', $issuedBook->book_id)
                ->where('school_id', $school->id)
                ->first();

            if ($book) {
                $book->qty = $book->qty + 1;
                $book->save();
            }

            return redirect()->route('school.issueBooks')->with('success', 'Book returned successfully');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Failed to return book: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the returned books
     */
  public function returnBooks(Request $request)
{
    $user = auth()->user();

    $school = \App\Models\School::where('admin_id', $user->id)->first();

    if (!$school) {
        return view('client.schoolPanel.library.returnBooks', ['returnedBooks' => []]);
    }

    // Fetch RETURNED or LOST books
    $query = IssuedBook::where('school_id', $school->id)
        ->where(function ($q) {
            $q->where('is_returned', true)
              ->orWhere('is_lost', true);
        });

    // FILTER : From Date
    if ($request->from_date) {
        $query->where(function ($q) use ($request) {
            $q->whereDate('return_date', '>=', $request->from_date)
              ->orWhereDate('lost_date', '>=', $request->from_date);
        });
    }

    // FILTER : To Date
    if ($request->to_date) {
        $query->where(function ($q) use ($request) {
            $q->whereDate('return_date', '<=', $request->to_date)
              ->orWhereDate('lost_date', '<=', $request->to_date);
        });
    }

    // FILTER : Student ID
    if ($request->student_id) {
        $query->where('student_id', 'like', '%' . $request->student_id . '%');
    }

    // Get results
    $returnedBooks = $query->orderByRaw("
        CASE 
            WHEN return_date IS NOT NULL THEN return_date
            WHEN lost_date IS NOT NULL THEN lost_date
            ELSE created_at
        END DESC
    ")->get();

    // ADD STATUS + FIX DISPLAY DATE
    foreach ($returnedBooks as $book) {

        if ($book->is_returned) {
            $book->status = "Returned";
            $book->final_date = $book->return_date;

        } elseif ($book->is_lost) {
            $book->status = "Lost";
            $book->final_date = $book->lost_date;

        } else {
            $book->status = "Issued";
            $book->final_date = null;
        }
    }

    return view('client.schoolPanel.library.returnBooks', compact('returnedBooks'));
}


    /**
     * Generate a unique issue ID
     */
    private function generateIssueId()
    {
        // Generate an issue ID with format IB-YYYYMMDD-XXXX where XXXX is a random number
        $now = new \DateTime();
        $dateString = $now->format('Ymd');
        $randomNum = mt_rand(1000, 9999);

        $issueId = "IB-{$dateString}-{$randomNum}";

        // Check if the ID already exists, regenerate if needed
        while (IssuedBook::where('issue_id', $issueId)->exists()) {
            $randomNum = mt_rand(1000, 9999);
            $issueId = "IB-{$dateString}-{$randomNum}";
        }

        return $issueId;
    }

    /**
     * Get issued books for a specific student
     */
    public function getStudentIssuedBooks($student_id)
    {
        try {
            // Get the authenticated user
            $user = auth()->user();

            // Find the school
            $school = \App\Models\School::where('admin_id', $user->id)->first();

            if (!$school) {
                return response()->json([
                    'success' => false,
                    'message' => 'School not found'
                ], 404);
            }

            // Find all issued books for the student
            $issuedBooks = IssuedBook::where('student_id', $student_id)
                ->where('school_id', $school->id)
                ->orderBy('issue_date', 'desc')
                ->get();

            // Fetch book details including image_path for each issued book
            foreach ($issuedBooks as $issuedBook) {
                $book = Book::where('book_id', $issuedBook->book_id)
                    ->where('school_id', $school->id)
                    ->first();

                if ($book) {
                    $issuedBook->book_details = [
                        'book_id' => $book->book_id,
                        'book_name' => $book->book_name,
                        'author' => $book->author,
                        'publisher' => $book->publisher,
                        'image_path' => $book->image_path
                    ];
                    // Also set the image_path directly on the issued book
                    $issuedBook->image_path = $book->image_path;
                }
            }

            return response()->json([
                'success' => true,
                'issuedBooks' => $issuedBooks
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching student\'s issued books: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAsLost(Request $request, $id)
    {
        $request->validate([
            'lost_date' => 'required|date',
            'lost_remarks' => 'nullable|string|max:500',
        ]);

        $issuedBook = IssuedBook::findOrFail($id);

        // If already returned, do not allow lost
        if ($issuedBook->is_returned) {
            return back()->with('error', 'This book is already returned.');
        }

        // Update book_numbers status
        BookNumber::where('book_no', $issuedBook->book_no)
            ->update(['status' => 'lost']);

        // Update issued_books table
        $issuedBook->is_lost = true;
        $issuedBook->lost_date = $request->lost_date;
        $issuedBook->lost_remarks = $request->lost_remarks;
        $issuedBook->save();

        return redirect()->route('school.issueBooks')
            ->with('success', 'Book marked as lost successfully.');
    }
}
