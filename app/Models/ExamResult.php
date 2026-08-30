<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'school_id',
        'exam_schedule_id',
        'student_id',
        'subject_id',
        'teacher_id',
        'marks_obtained',
        'total_marks',
        'remarks',
        'exam_type'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

  public function examSchedule()
{
    return $this->belongsTo(ExamSchedule::class, 'exam_schedule_id');
}
    
    
      public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
