<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
    protected $table = 'teacher_attendance';

    protected $fillable = [
        'teacher_id',
        'school_id',
        'attendance_date',
        'status',
        'remarks'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
