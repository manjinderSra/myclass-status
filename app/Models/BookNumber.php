<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookNumber extends Model
{
    use HasFactory;

    protected $table = 'book_numbers';

    protected $fillable = [
        'book_id',
        'issued_books_id',
        'book_no',
        'status'
    ];

    /**
     * Relationship: BookNumber belongs to Book
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Relationship: BookNumber belongs to IssuedBook
     */
    public function issuedBook()
    {
        return $this->belongsTo(IssuedBook::class, 'issued_books_id');
    }
}
