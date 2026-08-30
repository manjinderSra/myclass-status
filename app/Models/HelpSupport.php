<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpSupport extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'address',
        'email',
        'website',
        'working_hours_start',
        'working_hours_end',
        'working_days',
        'phone_numbers',
    ];
    
    /**
     * Get the school that owns this help and support information.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
    /**
     * Get formatted working hours.
     *
     * @return string
     */
    public function getWorkingHoursAttribute()
    {
        if ($this->working_hours_start && $this->working_hours_end) {
            return $this->working_hours_start . ' to ' . $this->working_hours_end;
        }
        
        return '';
    }
}
