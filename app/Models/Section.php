<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'school_id',
        'class_id',
        'capacity',
        'status',
    ];
    
    protected $casts = [
        'status' => 'boolean',
    ];
    
    /**
     * Get the school that owns the section.
     */
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    
    /**
     * Get the class that this section belongs to.
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
} 