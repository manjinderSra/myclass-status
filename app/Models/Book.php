<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'book_name',
        'book_no',
        'rack_no',
        'publisher',
        'author',
        'subject',
        'qty',
        'price',
        'post_date',
        'school_id',
        'image_path'
    ];

    /**
     * Get the school that owns the book.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
} 