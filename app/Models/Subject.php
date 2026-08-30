<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_id',
        'name',
        'code',
        'description',
        'status',
    ];
    
    /**
     * Get the school that owns the subject.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
    /**
     * Get the classes assigned to this subject.
     */
    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subject', 'subject_id', 'class_id')
            ->withTimestamps();
    }
    
    /**
     * Get the teachers who teach this subject.
     */
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_subject', 'subject_id', 'teacher_id')
            ->withTimestamps();
    }
} 