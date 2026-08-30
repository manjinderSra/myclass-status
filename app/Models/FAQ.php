<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FAQ extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'school_id',
        'priority',
        'is_active'
    ];

    /**
     * Get the school that this FAQ belongs to
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}