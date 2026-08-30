<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Homework extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'class_name',
        'section_id',
        'subject_id',
        'homework_date',
        'submission_date',
        'description',
        'image_path',
        'created_by',
    ];

    protected $casts = [
        'homework_date' => 'date',
        'submission_date' => 'date',
    ];

    /**
     * Get the school that owns the homework.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function submission()
    {
        return $this->hasOne(HomeworkSubmission::class, 'homework_id')
            ->where('student_id', Session::get('student_id'));
    }


    /**
     * Get the section associated with the homework.
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the subject associated with the homework.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the user who created the homework.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }


    public function submissions()
    {
        return $this->hasMany(HomeworkSubmission::class);
    }

    public function isSubmittedBy($studentId)
    {
        return $this->submissions()->where('student_id', $studentId)->exists();
    }
}
