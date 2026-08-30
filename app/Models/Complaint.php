<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
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
        'complaint_id',
        'nature',
        'description',
        'status',
        'response',
        'resolved_by',
        'resolved_at',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'resolved_at' => 'datetime',
    ];
    
    /**
     * Get the school that the complaint belongs to.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
    /**
     * Get the student who submitted the complaint.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    /**
     * Get the user who resolved the complaint.
     */
    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
    
    /**
     * Generate a unique complaint ID
     */
    public static function generateComplaintId()
    {
        $prefix = 'COMP';
        $year = date('Y');
        $month = date('m');
        
        // Get the count of complaints for the current month
        $count = self::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->count();
        
        // Increment count and pad with zeros
        $count++;
        $paddedCount = str_pad($count, 4, '0', STR_PAD_LEFT);
        
        // Format: COMP-YYYYMM-0001
        return "{$prefix}-{$year}{$month}-{$paddedCount}";
    }
}
