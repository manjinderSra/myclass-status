<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTablePeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'timetable_id',
        'day',
        'name',
        'subject',
        'teacher',
        'time_from',
        'time_to',
        'period_type',
    ];

    /**
     * Get the timetable that owns the period.
     */
    // public function timetable()
    // {
    //     return $this->belongsTo(TimeTable::class);
    // }
    public function timetable()
    {
        return $this->belongsTo(TimeTable::class, 'timetable_id');
    }

    /**
     * Get the subject associated with this period.
     */
    public function subjectRelation()
    {
        return $this->belongsTo(Subject::class, 'subject');
    }

public function subject()
{
    return $this->belongsTo(Subject::class, 'subject_id');
}

public function teacher()
{
    return $this->belongsTo(Teacher::class, 'teacher_id');
}

    /**
     * Get the teacher associated with this period.
     */
    public function teacherRelation()
    {
        return $this->belongsTo(Teacher::class, 'teacher');
    }

    /**
     * Get the subject name attribute
     */
    public function getSubjectNameAttribute()
    {
        return $this->subjectRelation ? $this->subjectRelation->name : null;
    }

    /**
     * Get the teacher name attribute
     */
    public function getTeacherNameAttribute()
    {
        return $this->teacherRelation ? $this->teacherRelation->name : null;
    }
}
