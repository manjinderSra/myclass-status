<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;
    protected $table = 'exams'; 

    protected $fillable = [
        'school_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'academic_session',
        'exam_type', 
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function examSchedules(): HasMany
    {
        return $this->hasMany(ExamSchedule::class, 'exam_id');
    }
}

