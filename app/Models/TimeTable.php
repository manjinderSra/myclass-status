<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'class_name',
        'section_id',
        'start_time',
        'duration',
        'created_by',
    ];

    /**
     * Get the school that owns the timetable.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the section associated with the timetable.
     */
    // public function section()
    // {
    //     return $this->belongsTo(Section::class);
    // }


    public function schoolClass()
    {


        return $this->belongsTo(SchoolClass::class, 'class_name', 'name');
    }

    /**
     * Get the user who created the timetable.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the periods for the timetable.
     */
    public function periods()
    {
        return $this->hasMany(TimeTablePeriod::class, 'timetable_id');
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
