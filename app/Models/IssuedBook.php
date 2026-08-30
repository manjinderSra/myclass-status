<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssuedBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_id',
        'school_id',
        'student_id',
        'student_name',
        'student_class',
        'book_id',
        'book_name',
        'book_no',
        'issue_date',
        'due_date',
        'return_date',
        'is_returned',
        'issue_remarks',
        'return_remarks'
    ];

    /**
     * Get the school that owns the issued book.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the book associated with this issued book.
     */
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }
}
