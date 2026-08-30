<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'school_classes';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'school_id',
        'section_id',
        'total_capacity',
        'description',
        'status',
    ];
    
    protected $casts = [
        'status' => 'boolean',
    ];
    
    /**
     * Get the school that owns the class.
     */
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    
    /**
     * Get the sections associated with the class.
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }
    
    /**
     * Get the section this class belongs to (if any).
     */
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    
    /**
     * Get the subjects assigned to this class.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject', 'class_id', 'subject_id')
            ->withTimestamps();
    }
    
    /**
     * Calculate remaining capacity.
     * To be calculated based on students assigned to this class.
     */
    public function getRemainingCapacityAttribute()
    {
        // In the future, this will count students assigned to this class
        // For now, return a fixed value or random number
        return $this->total_capacity - rand(0, $this->total_capacity);
    }
    
    
    public function teacher()
{
    return $this->belongsTo(Teacher::class, 'teacher_id');
}

} 