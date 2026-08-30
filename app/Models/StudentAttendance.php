<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;
    
    protected $table = 'student_attendance';
    
    protected $fillable = [
        'school_id',
        'student_id',
        'class_name',
        'section_id',
        'attendance_date',
        'status',
        'remarks',
        'teacher_id',
        'created_by',
    ];
    
    protected $casts = [
        'attendance_date' => 'date',
    ];
    
    /**
     * Get the school that owns the attendance record.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
    /**
     * Get the student that owns the attendance record.
     */

    public function student()
{
    return $this->belongsTo(Student::class, 'student_id', 'id');
}

public function section()
{
    return $this->belongsTo(Section::class, 'section_id', 'id');
}

    
    /**
     * Get the teacher who marked the attendance.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
} 