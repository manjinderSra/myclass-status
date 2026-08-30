<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentLeave extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'student_id',
        'leave_id',
        'reason',
        'description',
        'from_date',
        'to_date',
        'status',
        'admin_remarks',
        'processed_by',
        'processed_at',
        'attachment_path',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'processed_at' => 'datetime',
    ];
    
    /**
     * Get the school that the leave application belongs to.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
    /**
     * Get the student who submitted the leave application.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    /**
     * Get the user who processed the leave application.
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
    
    /**
     * Calculate the number of leave days.
     *
     * @return int
     */
    public function getLeaveDaysAttribute()
    {
        return $this->from_date->diffInDays($this->to_date) + 1;
    }
    
    /**
     * Generate a unique leave ID
     */
    public static function generateLeaveId()
    {
        $prefix = 'LEAVE';
        $year = date('Y');
        $month = date('m');
        $timestamp = time();
        $randomStr = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);
        
        // Get the count of leave applications for the current month
        $count = self::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->count();
        
        // Increment count and pad with zeros
        $count++;
        $paddedCount = str_pad($count, 4, '0', STR_PAD_LEFT);
        
        // Format: LEAVE-YYYYMM-0001-TIMESTAMP-RND
        return "{$prefix}-{$year}{$month}-{$paddedCount}-{$randomStr}";
    }
}
