<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsCondition extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'title',
        'content',
        'version',
        'is_active'
    ];
    
    /**
     * Get the school that owns the terms and conditions.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
