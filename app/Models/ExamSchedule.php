<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    protected $fillable = [
        'school_id',
        'exam_id',
        'class',
        'section',
        'subject_id',
        'exam_date',
        'start_time',
        'end_time',
        'duration',
        'room_no',
        'max_marks',
        'min_marks',
        'evaluator_id',
        'exam_type',
        'status',
        'exam_cancel',
        'cancel_reason'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }


    public function examResults()
    {
        return $this->hasMany(ExamResult::class, 'exam_schedule_id');
    }

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class, 'subject_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
    
